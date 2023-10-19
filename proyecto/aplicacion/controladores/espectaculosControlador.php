<?php

/**
 * Estamos definiendo el Controlador de Espectaculos
 */
class espectaculosControlador extends CControlador{
    
    /**
     * Estamos definiendo el constructor para el crud de Espectaculo.
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->plantilla = "dashboard";
        $this->accionDefecto = "Index";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de dibujar el crud de Espectaculo y realizariamos un paginador.
     */
    public function accionIndex(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

        // Comprobamos si tiene el permiso 2
        if (!Sistema::app()->Acceso()->puedePermiso(2)){
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina");
            return;
        } // End if si tiene permiso 2
        
        // Definimos nuestras variables locales
        $espectaculo = new Espectaculo();
        $datos_filtrados = ["nombre_categoria" => "", "borrado" => ""];
        $opciones = [];

        // Hacemos el filtrado de pagina
        if (isset($_POST["filtrar"])){
            // Filtramos el nombre de Categoria
            if (isset($_POST["nombre_categoria"])) {
                if (!empty($_POST["nombre_categoria"])) {
                    $nombre = intval($_POST["nombre_categoria"]);
                    $opciones["where"] = " cod_categoria_espectaculo = '$nombre'";
                } // End if de cadena vacia
                $datos_filtrados["nombre_categoria"] = $_POST["nombre_categoria"];
            } // End if de existe nombre_categoria

            // Filtramos el borrado
            if (isset($_POST["borrado"])) {
                $borrado = CGeneral::addSlashes($_POST["borrado"]);
                if (count($opciones) > 0 && !empty($_POST["borrado"])) {
                    $opciones["where"] = $opciones["where"] . " and borrado = '{$borrado}'";
                } // End if 
                if (!$opciones) {
                    $opciones["where"] = " borrado = '{$borrado}'";
                } // End if
                $datos_filtrados["borrado"] = $_POST["borrado"];
            } // End if de filtrado de borrado

            $_SESSION["datos_filtrados"] = $datos_filtrados;
        } // End del boton del filtrar

         // Comprobamos los datos de sesion
         if (isset($_SESSION["datos_filtrados"])){
            // Sobrecargamos el datos_filtrados
            $datos_filtrados = $_SESSION["datos_filtrados"];

            // Comprobamos de que no este en blanco
            if (isset($datos_filtrados["nombre_categoria"]) && $datos_filtrados["nombre_categoria"] != ""){
                $cod = intval($datos_filtrados["nombre_categoria"]);
                $opciones["where"] = " cod_categoria_espectaculo = '$cod'";
            } // End if 2
            else{
                $datos_filtrados = ["nombre_categoria" => "", "borrado" => ""];
            } // End else 2

            // Comprobamos de que no este en blanco
            if (isset($datos_filtrados["borrado"]) && $datos_filtrados["borrado"] != ""){
                $cod = intval($datos_filtrados["borrado"]);
                if (count($opciones) > 0){
                    $opciones["where"] .= " and borrado = '$cod'";
                } else{
                    $opciones["where"] .= "borrado = '$cod'";
                }
            } // End if 2
            else{
                $datos_filtrados = ["nombre_categoria" => "", "borrado" => ""];
            } // End else 2
        } // End if 1
        else{
            $datos_filtrados = ["nombre_categoria" => "", "borrado" => ""];
        } // End else 1

        // Estamos definiendo el funcionamiento del paginador de Espectaculo
        $registros = intval($espectaculo->buscarTodosNRegistros($opciones));

        $tamPagina = 4;

        if (isset($_GET["reg_pag"]))
            $tamPagina = intval($_GET["reg_pag"]);

        $numPaginas = ceil($registros / $tamPagina);
        $pag = 1;

        if (isset($_GET["pag"])){
            $pag=intval($_GET["pag"]);
        }

        if ($pag > $numPaginas)
            $pag = $numPaginas;

        $inicio = $tamPagina * ($pag-1);
        if ($inicio<0)
            $inicio=0;

        $opciones["limit"] = "$inicio, $tamPagina";   

        // Estamos definiendo el funcionamiento del Paginador 
        $filas = $espectaculo->buscarTodos($opciones);

        // En caso de que haya fila guardaremos las acciones de ver/modificar/borrar Espectaculo
        if ($filas){
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de ver los datos de espectaculo
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/24x24/ver.png"),
                    Sistema::app()->generaURL(
                        ["espectaculos", "Ver"],
                        ["id" => $filas[$key]["cod_espectaculos"]]
                    )
                );

                // Estamos definiendo el boton de modificar datos de espectaculo
                $cadena .= CHTML::link(
                    CHTML::imagen("/imagenes/24x24/modificar.png"),
                    Sistema::app()->generaURL(
                        ["espectaculos", "Modificar"],
                        ["id" => $filas[$key]["cod_espectaculos"]]
                    )
                );

                // Estamos definiendo el boton de borrar Espectaculo
                $cadena .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png"), 
                Sistema::app()->generaURL(["espectaculos", "Borrar"],
                    ["id" => $filas[$key]["cod_espectaculos"]]), 
                    ["class" => "borrar", "title" => "Borrar Espectaculo ".$filas[$key]["cod_espectaculos"]]);
                
                // Guardamos las operaciones del crud
                $filas[$key]["operaciones"] = $cadena;     

                // Hacemos un reemplazo para el que cuando salgo un 0 devuelva no y cuando salga un 1 devuelva un si
                if ($filas[$key]["borrado"] == 0){
                    $filas[$key]["borrado"] = str_replace("0", "No", $filas[$key]["borrado"]);
                } // End if 1
                else{
                    $filas[$key]["borrado"] = str_replace("1", "Si", $filas[$key]["borrado"]);
                } // End else 2

                // Cambiamos el formato a la fecha de lanzamiento y de finalizacion para sea en formato DIA/MES/AÑO
                $filas[$key]["fecha_lanzamiento"] = CGeneral::fechaMysqlANormal($filas[$key]["fecha_lanzamiento"]);
                $filas[$key]["fecha_finalizacion"] = CGeneral::fechaMysqlANormal($filas[$key]["fecha_finalizacion"]);

                // Hacemos que la imagen se transforme en una etiqueta img
                $filas[$key]["imagen"] = CHTML::imagen($filas[$key]["imagen"], "Imagen del Especatculo", ["class" => "card-img-top"]);
            } // End foreach

        } // End if filas

        // Definimos la cabecera para la tabla de Espectaculo
        $cabecera = [
            [
                "CAMPO" => "titulo",
                "ETIQUETA" => "Titulo"
            ],
            [
                "CAMPO" => "duracion",
                "ETIQUETA" => "Duracion"
            ],
            [
                "CAMPO" => "fecha_lanzamiento",
                "ETIQUETA" => "Fecha Ini"
            ],
            [
                "CAMPO" => "fecha_finalizacion",
                "ETIQUETA" => "Fecha Fin"
            ],
            [
                "CAMPO" => "imagen",
                "ETIQUETA" => "Foto"
            ],
            [
                "CAMPO" => "nombre_categoria_espectaculo",
                "ETIQUETA" => "Categoria Espectaculo"
            ],
            [
                "CAMPO" => "borrado",
                "ETIQUETA" => "¿Esta Borrado?"
            ],
            [
                "CAMPO" => "operaciones",
                "ETIQUETA" => "Acciones"
            ]
        ];

        // Definimos nuestro paginador
        $opcPaginador = array(
            "URL" => Sistema::app()->generaURL(array("espectaculos", "Index")),
            "TOTAL_REGISTROS" => $registros,
            "PAGINA_ACTUAL" => $pag,
            "REGISTROS_PAGINA" => $tamPagina,
            "TAMANIOS_PAGINA" => array(
                2 => "2",
                5 => "5",
                8 => "8",
                10 => "10",
                20 => "20",
                30 => "30"
            ),
            "MOSTRAR_TAMANIOS" => true,
            "PAGINAS_MOSTRADAS" => 4,
        );

        // Llamamos a la vista index de Espectaculo
        $this->dibujaVista("index", ["filas" => $filas, "cabecera" => $cabecera, "paginador" => $opcPaginador, "datos_filtrados" => $datos_filtrados], "Espectaculo");
        exit;
    } // End de la accion Index

    /**
     * Definiriamos la accion de CrearEspectaulos para poder crear un modelo de Tipo Espectaculo
     */
    public function accionCrearEspectaculos(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

        // Comprobamos si tiene el permiso 2
        if (!Sistema::app()->Acceso()->puedePermiso(2)){
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina ");
            return;
        } // End if si tiene permiso 2

        // Definimos nuestras variables locales
        $espectaculo = new Espectaculo();
        $nombre = $espectaculo->getNombre();

        // Llamamos al boton del formulario de crear espectaculo
        if (isset($_POST["crear_espectaculo"])){
            // Guardamos los valores y cambiamos el formato a la fecha para sea DIA/MES/AÑO
            $espectaculo->setValores($_POST[$nombre]);
            $espectaculo->fecha_lanzamiento = CGeneral::fechaMysqlANormal($espectaculo->fecha_lanzamiento);
            $espectaculo->fecha_finalizacion = CGeneral::fechaMysqlANormal($espectaculo->fecha_finalizacion);

            // Guardamos y validamos los valores para cada atributo del objeto ESPECTACULO
            if ($espectaculo->validar()){
                if ($espectaculo->guardar()){
                    Sistema::app()->irAPagina(["espectaculos"]);
                    return;
                } // End if 2
                else{
                    $espectaculo->fecha_lanzamiento = CGeneral::fechaNormalAMysql($espectaculo->fecha_lanzamiento);
                    $espectaculo->fecha_finalizacion = CGeneral::fechaNormalAMysql($espectaculo->fecha_finalizacion);
                    $this->dibujaVista("crearEspectaculo", ["espectaculo" => $espectaculo], "Crear Espectaculo");
                    exit;
                } // End else 2
            } // End if 1 
            else{
                $espectaculo->fecha_lanzamiento = CGeneral::fechaNormalAMysql($espectaculo->fecha_lanzamiento);
                $espectaculo->fecha_finalizacion = CGeneral::fechaNormalAMysql($espectaculo->fecha_finalizacion);
                $this->dibujaVista("crearEspectaculo", ["espectaculo" => $espectaculo], "Crear Espectaculo");
                exit;
            } // End else 1
        } // End del boton formulario
        
        // Llamamos a al vista de crearEspectaculo
        $this->dibujaVista("crearEspectaculo", ["espectaculo" => $espectaculo], "Crear Espectaculo");
        exit;
    } // End if de accion de Crear Categoria

    /**
     * Estamos definiendo la accion de Ver para mostrar todos los datos de un modelo de Tipo Espectaculo en específico
     */
    public function accionVer(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            return;
        } // End if si hay usuario

        // Comprobamos si tiene el permiso 2
        if (!Sistema::app()->Acceso()->puedePermiso(2)){
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina ");
            return;
        } // End if si tiene permiso 2

        // Definimos nuestras variables locales
        $espectaculo = new Espectaculo();
        $id = 0;

        // Obtenemos el id de ese modelo
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End de la obtencion del if

        // Buscamos por ID
        $filas = $espectaculo->buscarPorId($id);

        // Comprobariamos si ese modelo existe o no
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de ese Espectaculo");
            return;
        } // End if

        // Llamamos al ver Datos del modelo Espectaculo
        $this->dibujaVista("verDatos", ["espectaculo" => $espectaculo], "Ver Espectaculo");
        exit;
    } // End de la accion Ver

    /**
     * Estamos definiendo la accion de Modificar Espectaculo de un modelo en específico
     */
    public function accionModificar(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

        // Comprobamos si tiene el permiso 2
        if (!Sistema::app()->Acceso()->puedePermiso(2)){
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina ");
            return;
        } // End if si tiene permiso 2

        // Definimos nuestras variables locales
        $espectaculo = new Espectaculo();
        $nombre = $espectaculo->getNombre();
        $id = 0;

        // Obtenemos el id del modelo en especifico
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del if

        // Buscamos al espectaculo por el ID si existe
        $filas = $espectaculo->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de ese Espectaculo");
            return;
        } // End if de la validacion

        // Llamamos al formulario para modificar los datos
        if (isset($_POST["actualizar"])){
            // Le asignamos los valores y el cod de categoria espectaculo
            $espectaculo->setValores($_POST[$nombre]);
            $espectaculo->cod_espectaculos = $id;
            $espectaculo->fecha_lanzamiento = CGeneral::fechaMysqlANormal($espectaculo->fecha_lanzamiento);
            $espectaculo->fecha_finalizacion = CGeneral::fechaMysqlANormal($espectaculo->fecha_finalizacion);

            if ($espectaculo->validar()){
                if ($espectaculo->guardar()){
                    Sistema::app()->irAPagina(["espectaculos"]);
                    return;
                } // End if 2
                else{
                    $espectaculo->fecha_lanzamiento = CGeneral::fechaNormalAMysql($espectaculo->fecha_lanzamiento);
                    $espectaculo->fecha_finalizacion = CGeneral::fechaNormalAMysql($espectaculo->fecha_finalizacion);
                    $this->dibujaVista("modificarDatos", ["espectaculo" => $espectaculo], "Modificar Espectaculo");
                    exit;
                } // End else 2
            } // End if 1
            else{
                $espectaculo->fecha_lanzamiento = CGeneral::fechaNormalAMysql($espectaculo->fecha_lanzamiento);
                $espectaculo->fecha_finalizacion = CGeneral::fechaNormalAMysql($espectaculo->fecha_finalizacion);
                $this->dibujaVista("modificarDatos", ["espectaculo" => $espectaculo], "Modificar Espectaculo");
                exit;
            } // End else 1
        } // End if del boton actualizar

        // Llamamos a la vista de modificar Datos de ese Modelo
        $this->dibujaVista("modificarDatos", ["espectaculo" => $espectaculo], "Modificar Espectaculo");
        exit;
    } // End de la accion Modificar

    /**
     * Estamos definiendo la accion de Borrar Espectaculo de un modelo en específico
     */
    public function accionBorrar(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

        // Comprobamos si tiene el permiso 2
        if (!Sistema::app()->Acceso()->puedePermiso(2)){
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina ");
            return;
        } // End if si tiene permiso 2

        // Definimos variables locales
        $espectaculo = new Espectaculo();
        $id = 0;

        // Comprobamos de que exista
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if 

        // Buscamos por id
        $filas = $espectaculo->buscarPorId($id);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de ese Espectaculo");
            return;
        }// End if

        // Realizamos la sentencia de borrado logico
        $sentencia = "update espectaculos set borrado = true where cod_espectaculos = '$id'";

        // Comprobamos si se ha realizado
        if (!$espectaculo->ejecutarSentencia($sentencia)) {
            Sistema::app()->paginaError(404, "ERROR!! Se ha producido un error en la base de datos");
            return;
        } // End if

        // Redireccionamos a la accion Consultar Espectaculos
        Sistema::app()->irAPagina(["espectaculos", "Index"]);
        return;
    } // End de la accion de Borrar
    
    /**
     * Estamos definiendo la accion de Ajax Espectaculos donde mostraremos todos los titulos que se encuentren
     * en nuestra base de datos que no este borrado
     */
    public function accionAjaxEspectaculos(){
        // Definimos nuestras variables locales
        $espectaculo = new Espectaculo();

        // Hacemos el REQUEST del metodo POST
        if ($_SERVER["REQUEST_METHOD"] == "POST"){

            // Comprobamos de que haya espectaculos disponibles
            if (Espectaculo::dameEspectaculos() == false){
                echo json_encode(["correcto" => false, "datos" => "No hay espectaculos hasta el momento"], JSON_PRETTY_PRINT|JSON_INVALID_UTF8_IGNORE);
                return;
            } // End if de la obtencion de los Espectáculos

            // Validamos la obtencion del POST del nombre
            if (isset($_POST["nombre"])){

                // Comprobamos de si se encuentra vacio
                if ($_POST["nombre"] == ""){
                    echo json_encode(["correcto" => true, "datos" => Espectaculo::dameEspectaculos()], JSON_PRETTY_PRINT|JSON_INVALID_UTF8_IGNORE);
                    return; 
                } // End if de la validacion del nombre si esta vacio

                // Escapamos el nombre
                $nombre = CGeneral::addSlashes(mb_strtolower($_POST["nombre"]));

                // Realizamos la busqueda de todos los espectaculos
                $filas = $espectaculo->buscarTodos(["where" => "lower(titulo) like '%$nombre%' and borrado = 0"]);

                // Comprobamos de que no sea falso la devolucion de las filas
                if ($filas == false){
                    echo json_encode(["correcto" => false, "datos" => "No hemos encontrado espectaculos"], JSON_PRETTY_PRINT|JSON_INVALID_UTF8_IGNORE);
                    return;
                } // End if de la validacion

                // Mostraremos los titulos de los Espectaculos
                echo json_encode(["correcto" => true, "datos" => $filas], JSON_PRETTY_PRINT|JSON_INVALID_UTF8_IGNORE);
                return;
            } // End if del POST["NOMBRE"]
        } // End del REQUEST POST
    } // End if de la accion de Ajax Espectaculos

} // End de la clase Espectaculos Controlador