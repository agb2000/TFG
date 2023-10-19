<?php

/**
 * Estamos definiendo el Controlador de Espectaculo Sala
 */
class espesalaControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para el crud de Espe_Sala.
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->plantilla = "dashboard";
        $this->accionDefecto = "Index";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de dibujar el crud de Espe_Sala y realizariamos un paginador
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
        $espe_sala = new Espe_Sala();
        $datos_filtrados = ["nombre_sala" => "", "borrado" => ""];
        $opciones = [];

        // Hacemos el filtrado de pagina
        if (isset($_POST["filtrar"])){
            // Filtramos el nombre de Categoria
            if (isset($_POST["nombre_sala"])) {
                if (!empty($_POST["nombre_sala"])) {
                    $nombre = intval($_POST["nombre_sala"]);
                    $opciones["where"] = " cod_sala = '$nombre'";
                } // End if de cadena vacia
                $datos_filtrados["nombre_sala"] = $_POST["nombre_sala"];
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
            if (isset($datos_filtrados["nombre_sala"]) && $datos_filtrados["nombre_sala"] != ""){
                $cod = intval($datos_filtrados["nombre_sala"]);
                $opciones["where"] = " cod_sala = '$cod'";
            } // End if 2
            else{
                $datos_filtrados = ["nombre_sala" => "", "borrado" => ""];
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
                $datos_filtrados = ["nombre_sala" => "", "borrado" => ""];
            } // End else 2
        } // End if 1
        else{
            $datos_filtrados = ["nombre_sala" => "", "borrado" => ""];
        } // End else 1

        // Estamos definiendo el funcionamiento del paginador de Espe_Sala
        $registros = intval($espe_sala->buscarTodosNRegistros($opciones));

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
        $filas = $espe_sala->buscarTodos($opciones);

        // En caso de que haya fila guardaremos las acciones de ver/modificar/borrar Espe_Sala
        if ($filas){
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de ver los datos de Espe_Sala
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/24x24/ver.png"),
                    Sistema::app()->generaURL(
                        ["espesala", "Ver"],
                        ["id" => $filas[$key]["cod_espe_sala"]]
                    )
                );

                // Estamos haciendo el boton de modificar los datos de Espe_Sala
                $cadena .= CHTML::link(
                    CHTML::imagen("/imagenes/24x24/modificar.png"),
                    Sistema::app()->generaURL(
                        ["espesala", "Modificar"],
                        ["id" => $filas[$key]["cod_espe_sala"]]
                    )
                );

                // Estamos haciendo el boton de borrar los datos de Espe_Sala
                $cadena .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png"), 
                Sistema::app()->generaURL(["espesala", "Borrar"],
                    ["id" => $filas[$key]["cod_espe_sala"]]), 
                ["class" => "borrar", "title" => "Borrar Espe_Sala ".$filas[$key]["cod_espe_sala"]]);
                
                // Añadimos la columna operaciones a cada fila
                $filas[$key]["operaciones"] = $cadena;     

                // Reemplazamos cuando sea 0 por NO y 1 por SI
                if ($filas[$key]["borrado"] == 0){
                    $filas[$key]["borrado"] = str_replace("0", "No", $filas[$key]["borrado"]);
                } // End if 
                else{
                    $filas[$key]["borrado"] = str_replace("1", "Si", $filas[$key]["borrado"]);
                } // End else
            } // End foreach
        } // End if filas

        // Definimos la cabecera de la tabla de datos
        $cabecera = [
            [
                "CAMPO" => "titulo",
                "ETIQUETA" => "Titulo"
            ],
            [
                "CAMPO" => "nombre_sala",
                "ETIQUETA" => "Nombre Sala"
            ],
            [
                "CAMPO" => "capacidad_maxima",
                "ETIQUETA" => "Capacidad Maxima"
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

        // Definimos el paginador
        $opcPaginador = array(
            "URL" => Sistema::app()->generaURL(array("espesala", "Index")),
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

        // Llamamos a la vista index de espesala
        $this->dibujaVista("index", ["filas" => $filas, "cabecera" => $cabecera, "paginador" => $opcPaginador, "datos_filtrados" => $datos_filtrados], "Espe_Sala");
        exit;
    } // End de la accion Index

    /**
     * Estamos definiendo la accionCrearEspe_Sala se encarga de crear un espectaculo sala
     */
    public function accionCrearEspe_Sala(){
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
        $espe_sala = new Espe_Sala();
        $nombre = $espe_sala->getNombre();

        // Llamamos al boton del formulario de crear Espe_Sala
        if (isset($_POST["crear_espe_sala"])){
            // Guardamos los valores
            $espe_sala->setValores($_POST[$nombre]);

            // Guardamos y validamos los valores para cada atributo del objeto Espe_Sala
            if ($espe_sala->validar()){
                if ($espe_sala->guardar()){
                    Sistema::app()->irAPagina(["espesala"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("crearEspeSala", ["espe_sala" => $espe_sala], "Crear Espe_Sala");
                    exit;
                } // End else 2
            } // End if 1 
            else{
                $this->dibujaVista("crearEspeSala", ["espe_sala" => $espe_sala], "Crear Espe_Sala");
                exit;
            } // End else 1
        } // End del boton formulario

        // Llamamos a al vista de crearEspe_Sala
        $this->dibujaVista("crearEspeSala", ["espe_sala" => $espe_sala], "Crear Espe_Sala");
        exit;
    } // End de la accion de Crear Espe_Sala

    /**
     * Estamos definiendo la accion de Ver para mostrar todos los datos de un modelo de Tipo Espe_Sala en específico
     */
    public function accionVer(){
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
        $espe_sala = new Espe_Sala();
        $id = 0;

        // Obtenemos el id de ese modelo
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del id

        // Buscamos por ID
        $filas = $espe_sala->buscarPorId($id);

        // Comprobariamos si ese modelo existe o no
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id Espe_Sala");
            return;
        } // End if de la validacion

        // Llamamos al ver Datos del modelo Espe_Sala
        $this->dibujaVista("verDatos", ["espe_sala" => $espe_sala], "Ver Espectaculo Sala");
        exit;
    } // End de la accion Ver

    /**
     * Estamos definiendo la accion de Modificar Espectaculo_Sala de un modelo en específico
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
        $espe_sala = new Espe_Sala();
        $nombre = $espe_sala->getNombre();
        $id = 0;

        // Obtenemos el id del modelo en especifico
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del ID

        // Buscamos por el ID ESPE_SALA si existe
        $filas = $espe_sala->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id Espectaculo Sala");
            return;
        } // End if de que exista

        // Llamamos al formulario para modificar los datos
        if (isset($_POST["actualizar"])){
            // Establecemos los valores para el modelo
            $espe_sala->setValores($_POST[$nombre]);
            $espe_sala->cod_participantes_espectaculos = $id;

            // Validamos el modelo y actualizamos los datos y los errores
            if ($espe_sala->validar()){
                if ($espe_sala->guardar()){
                    Sistema::app()->irAPagina(["espesala"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("modificarDatos", ["espe_sala" => $espe_sala], "Modificar Espectaculo Sala");
                    exit;
                } // End else 2
            } // End if 1
            else{
                $this->dibujaVista("modificarDatos", ["espe_sala" => $espe_sala], "Modificar Espectaculo Sala");
                exit;
            } // End else 1
        } // End if del boton actualizar

        // Llamamos a la vista de modificar Datos de ese Modelo
        $this->dibujaVista("modificarDatos", ["espe_sala" => $espe_sala], "Modificar Espectaculo Sala");
        exit;
    } // End de la accion Modificar

    /**
     * Estamos definiendo la accion de Borrar Espectaculo Sala de un modelo en específico
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
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina");
            return;
        } // End if si tiene permiso 2

        // Definimos variables locales
        $espe_sala = new Espe_Sala();
        $id = 0;

        // Comprobamos de que exista
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if 

        // Buscamos por id
        $filas = $espe_sala->buscarPorId($id);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id de Espectaculos Sala");
            return;
        }// End if

        // Realizamos la sentencia de borrado logico
        $sentencia = "update espe_sala set borrado = true where cod_espe_sala = '$id'";

        // Comprobamos si se ha realizado
        if (!$espe_sala->ejecutarSentencia($sentencia)) {
            Sistema::app()->paginaError(404, "ERROR!! Se ha producido un error en la base de datos");
            return;
        } // End if

        // Redireccionamos a la accion Consultar Categoria
        Sistema::app()->irAPagina(["espesala", "Index"]);
        return;
    } // End de la accion Borrar

} // End del controlador EspeSala