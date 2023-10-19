<?php

/**
 * Estamos definiendo el Controlador de Sesiones
 */
class sesionesControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para el crud de Sesiones.
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->plantilla = "dashboard";
        $this->accionDefecto = "Index";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de dibujar el crud de Sesiones y realizariamos un paginador.
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
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina ");
            return;
        } // End if si tiene permiso 2

        // Definimos nuestras variables locales
        $session = new Sesiones();
        $datos_filtrados = ["borrado" => "", "fecha" => ""];
        $opciones = [];

        // Hacemos el filtrado de pagina
        if (isset($_POST["filtrar"])){
            // Filtramos la fecha
            if (isset($_POST["fecha"])) {
                if (!empty($_POST["fecha"])) {
                    $fecha = CGeneral::fechaNormalAMysql($_POST["fecha"]);
                    $opciones["where"] = " fecha >= '$fecha'";
                } // End if de cadena vacia
                $datos_filtrados["fecha"] = $_POST["fecha"];
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

        // Estamos definiendo el funcionamiento del paginador de Sesiones
        $registros = intval($session->buscarTodosNRegistros($opciones));

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

        // Mostramos la fecha mas reciente
        $opciones["order"] = "fecha desc";

        // Estamos definiendo el funcionamiento del Paginador 
        $filas = $session->buscarTodos($opciones);

        // En caso de que haya fila guardaremos las acciones de ver/modificar/borrar Sesiones
        if ($filas){
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de ver los datos de Sesiones
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/24x24/ver.png"),
                    Sistema::app()->generaURL(
                        ["sesiones", "Ver"],
                        ["id" => $filas[$key]["cod_sesion"]]
                    )
                );

                // Estamos definiendo el boton de modificar datos de Sesiones
                $cadena .= CHTML::link(
                    CHTML::imagen("/imagenes/24x24/modificar.png"),
                    Sistema::app()->generaURL(
                        ["sesiones", "Modificar"],
                        ["id" => $filas[$key]["cod_sesion"]]
                    )
                );

                // Estamos definiendo el boton de borrar Sesiones
                $cadena .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png"), 
                Sistema::app()->generaURL(["sesiones", "Borrar"],
                    ["id" => $filas[$key]["cod_sesion"]]), 
                    ["class" => "borrar", "title" => "Borrar Sesion ".$filas[$key]["cod_sesion"]]);
                
                // Guardamos las operaciones del crud
                $filas[$key]["operaciones"] = $cadena;     

                // Hacemos un reemplazo para el que cuando salgo un 0 devuelva no y cuando salga un 1 devuelva un si
                if ($filas[$key]["borrado"] == 0){
                    $filas[$key]["borrado"] = str_replace("0", "No", $filas[$key]["borrado"]);
                } // End if 1
                else{
                    $filas[$key]["borrado"] = str_replace("1", "Si", $filas[$key]["borrado"]);
                } // End else 1

                // Realizamos el formateo de fecha para que nos muestre la fecha DIA/MES/AÑO
                $filas[$key]["fecha"] = CGeneral::fechaMysqlANormal($filas[$key]["fecha"]);
            } // End foreach
        } // End if filas

        // Definimos la cabecera para la tabla de Sesiones
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
                "CAMPO" => "fecha",
                "ETIQUETA" => "Fecha Realizacion"
            ],
            [
                "CAMPO" => "hora_inicio",
                "ETIQUETA" => "Hora de Inicio"
            ],
            [
                "CAMPO" => "hora_fin",
                "ETIQUETA" => "Hora de Fin"
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
            "URL" => Sistema::app()->generaURL(array("sesiones", "Index")),
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

        // Llamamos a la vista index de Sesiones
        $this->dibujaVista("index", ["session" => $session, "filas" => $filas, "cabecera" => $cabecera, "paginador" => $opcPaginador], "Sesiones");
        exit;
    } // End de la accion Index

    /**
     * Definiriamos la accion de CrearSesiones para poder crear un modelo de Tipo Sesiones
     */
    public function accionCrearSesiones(){
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
        $sesion = new Sesiones();
        $nombre = $sesion->getNombre();

        // Llamamos al boton del formulario de crear sesion
        if (isset($_POST["crear_sesion"])){
            // Asignamos valores al modelo de Sesiones
            $sesion->setValores($_POST[$nombre]);

            // En caso en que la fecha no este vacia cambiamos el formato MYSQL
            if ($sesion->fecha != ""){
                $sesion->fecha = CGeneral::fechaMysqlANormal($sesion->fecha);
            } // End if de validacion del formato Fecha

            // Dividimos la hora en un array para comprobar de que tenga el formato (horas:minutos:segundos)
            $inicio = explode(":", $sesion->hora_inicio);
            $fin = explode(":", $sesion->hora_fin);

            // Comprobamos de que la hora inicio no este vacia y comprobamos de que tenga 3 partes
            if ($sesion->hora_inicio != "" && count($inicio) < 3){
                $sesion->hora_inicio = $sesion->hora_inicio.":00";
            } // End if de la validacion de hora inicio

            // Comprobamos de que la hora fin no este vacia y comprobamos de que tenga 3 partes
            if ($sesion->hora_fin != "" && count($fin) < 3){
                $sesion->hora_fin = $sesion->hora_fin.":00";
            } // End if de la validacion de hora fin

            // Guardamos y validamos los valores para cada atributo del objeto Sesion
            if ($sesion->validar()){
                if ($sesion->guardar()){
                    Sistema::app()->irAPagina(["sesiones"]);
                    return; 
                } // End if 2
                else{
                    $sesion->fecha = CGeneral::fechaNormalAMysql($sesion->fecha);
                    $this->dibujaVista("crearSesion", ["sesion" => $sesion], "Crear Sesion");
                    exit;
                } // End else 2
            } // End if 1
            else{
                $sesion->fecha = CGeneral::fechaNormalAMysql($sesion->fecha);
                $this->dibujaVista("crearSesion", ["sesion" => $sesion], "Crear Sesion");
                exit;
            } // End else 1
        } // End del boton formulario

        // Llamamos a la vista de Crear Sesion
        $this->dibujaVista("crearSesion", ["sesion" => $sesion], "Crear Sesion");
        exit;
    } // End de la accion de Crear Sesion

    /**
     * Estamos definiendo la accion de Ver para mostrar todos los datos de un modelo de Tipo Sesion en específico
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
        $sesion = new Sesiones();
        $id = 0;

        // Obtenemos el id de ese modelo
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del id

        // Buscamos por ID
        $filas = $sesion->buscarPorId($id);

        // Comprobariamos si ese modelo existe o no
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id Sesion");
            return;
        } // End if de la validacion

        // Llamamos al ver Datos del modelo Sesion
        $this->dibujaVista("verDatos", ["sesion" => $sesion], "Ver Sesion");
        exit;
    } // End de la accion Ver

    /**
     * Estamos definiendo la accion de Modificar Sesion de un modelo en específico
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
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina");
            return;
        } // End if si tiene permiso 2

        // Definimos nuestras variables locales
        $sesion = new Sesiones();
        $nombre = $sesion->getNombre();
        $id = 0;

        // Obtenemos el id del modelo en especifico
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del ID

        // Comprobamos de que exista sala con ese ID
        $filas = $sesion->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id Sesion");
            return;
        } // End if de que exista

        // Llamamos al formulario para modificar los datos
        if (isset($_POST["actualizar"])){
            // Establecemos los valores para el modelo
            $sesion->setValores($_POST[$nombre]);
            $sesion->cod_sesion = $id;
            $sesion->fecha = CGeneral::fechaMysqlANormal($sesion->fecha);

            // Dividimos la hora en un array para comprobar de que tenga el formato (horas:minutos:segundos)
            $inicio = explode(":", $sesion->hora_inicio);
            $fin = explode(":", $sesion->hora_fin);

            // Comprobamos de que la hora inicio no este vacia y comprobamos de que tenga 3 partes
            if ($sesion->hora_inicio != "" && count($inicio) < 3){
                $sesion->hora_inicio = $sesion->hora_inicio.":00";
            } // End if de la validacion de hora inicio

            // Comprobamos de que la hora fin no este vacia y comprobamos de que tenga 3 partes
            if ($sesion->hora_fin != "" && count($fin) < 3){
                $sesion->hora_fin = $sesion->hora_fin.":00";
            } // End if de la validacion de hora fin

            // Validamos el modelo y actualizamos los datos y los errores
            if ($sesion->validar()){
                if ($sesion->guardar()){
                    Sistema::app()->irAPagina(["sesiones"]);
                    return;
                } // End if 2
                else{
                    $sesion->fecha = CGeneral::fechaNormalAMysql($sesion->fecha);
                    $this->dibujaVista("modificarDatos", ["sesion" => $sesion], "Modificar Sesion");
                    exit;
                } // End else 2
            } // End if 1
            else{
                $sesion->fecha = CGeneral::fechaNormalAMysql($sesion->fecha);
                $this->dibujaVista("modificarDatos", ["sesion" => $sesion], "Modificar Sesion");
                exit;
            } // End else 1
        } // End if del boton actualizar

        // Llamamos a la vista de modificar Datos de ese Modelo
        $this->dibujaVista("modificarDatos", ["sesion" => $sesion], "Modificar Sesion");
        exit;
    } // End de la accion Modificar

    /**
     * Estamos definiendo la accion de Borrar Sesion de un modelo en específico
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
        $sesion = new Sesiones();
        $id = 0;

        // Comprobamos de que exista
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if 

        // Buscamos por id
        $filas = $sesion->buscarPorId($id);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id de Sesion");
            return;
        }// End if

        // Realizamos la sentencia de borrado logico
        $sentencia = "update sesiones set borrado = true where cod_sesion = '$id'";

        // Comprobamos si se ha realizado
        if (!$sesion->ejecutarSentencia($sentencia)) {
            Sistema::app()->paginaError(404, "ERROR!! Se ha producido un error en la base de datos");
            return;
        } // End if

        // Redireccionamos a la accion Consultar Sesion
        Sistema::app()->irAPagina(["sesiones", "Index"]);
        return;
    } // End de la accion Borrar

} // End de la clase sesiones Controlador