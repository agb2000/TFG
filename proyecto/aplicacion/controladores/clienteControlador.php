<?php

/**
 * Estamos definiendo el Controlador de Cliente
 */
class clienteControlador extends CControlador {

    /**
     * Estamos definiendo el constructor para el crud de Cliente
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->accionDefecto = "Index";
        $this->plantilla = "dashboard";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de dibujar el crud de Cliente
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
        $cliente = new Cliente();
        $datos_filtrados = ["borrado" => "", "rol" => ""];
        $opciones = [];

        // Hacemos el filtrado de pagina
        if (isset($_POST["filtrar"])){
            // Filtramos la fecha
            if (isset($_POST["rol"])) {
                if (!empty($_POST["rol"])) {
                    $rol = intval($_POST["rol"]);
                    $opciones["where"] = " cod_acl_role = '$rol'";
                } // End if de cadena vacia
                $datos_filtrados["rol"] = $_POST["rol"];
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
                if (isset($datos_filtrados["rol"]) && $datos_filtrados["rol"] != ""){
                    $cod = intval($datos_filtrados["rol"]);
                    $opciones["where"] = " cod_acl_role = '$cod'";
                } // End if 2
                else{
                    $datos_filtrados = ["borrado" => "", "rol" => ""];
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
                    $datos_filtrados = ["borrado" => "", "rol" => ""];
                } // End else 2
            } // End if 1
            else{
                $datos_filtrados = ["borrado" => "", "rol" => ""];
            } // End else 1

        // Estamos definiendo el funcionamiento del paginador de Cliente
        $registros = intval($cliente->buscarTodosNRegistros($opciones));

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
        $filas = $cliente->buscarTodos($opciones);

        // En caso de que haya fila guardaremos las acciones de ver/modificar/borrar Cliente
        if ($filas) {
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de ver los datos de Cliente
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/24x24/ver.png"),
                    Sistema::app()->generaURL(
                        ["cliente", "Ver"],
                        ["id" => $filas[$key]["cod_cliente"]]
                    )
                );

                // Estamos haciendo el boton de modificar los datos de Cliente
                $cadena .= CHTML::link(
                    CHTML::imagen("/imagenes/24x24/modificar.png"),
                    Sistema::app()->generaURL(
                        ["cliente", "Modificar"],
                        ["id" => $filas[$key]["cod_cliente"]]
                    )
                );

                // Estamos haciendo el boton de borrar los datos de Cliente
                $cadena .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png"), 
                Sistema::app()->generaURL(["cliente", "Borrar"],
                    ["id" => $filas[$key]["cod_cliente"]]), 
                    ["class" => "borrar", "title" => "Borrar Cliente ".$filas[$key]["cod_cliente"]]);

                // Añadimos la columna operaciones a cada fila
                $filas[$key]["operaciones"] = $cadena;

                // Reemplazamos cuando sea 0 por NO y 1 por SI
                if ($filas[$key]["borrado"] == 0) {
                    $filas[$key]["borrado"] = str_replace("0", "No", $filas[$key]["borrado"]);
                } // End if
                else {
                    $filas[$key]["borrado"] = str_replace("1", "Si", $filas[$key]["borrado"]);
                } // End else

                // Cambiamos el formato de la fecha para que sea en formato DIA/MES/AÑO
                $filas[$key]["fecha_nacimiento"] = CGeneral::fechaMysqlANormal($filas[$key]["fecha_nacimiento"]);
                
                // Cambiamos el nombre del cliente para que la primera letra sea mayuscula
                $filas[$key]["nombre_cliente"] = mb_strtoupper(
                    mb_substr($filas[$key]["nombre_cliente"], 0, 1)).
                    mb_substr($filas[$key]["nombre_cliente"], 1, mb_strlen($filas[$key]["nombre_cliente"])
                );
            } // End foreach

        } // End if filas

        // Definimos la cabecera de la tabla de datos
        $cabecera = [
            [
                "CAMPO" => "nombre_cliente",
                "ETIQUETA" => "Nombre"
            ],
            [
                "CAMPO" => "apellidos_cliente",
                "ETIQUETA" => "Apellidos"
            ],
            [
                "CAMPO" => "nick_cliente",
                "ETIQUETA" => "Nick"
            ],
            [
                "CAMPO" => "nif_cliente",
                "ETIQUETA" => "Nif"
            ],
            [
                "CAMPO" => "fecha_nacimiento",
                "ETIQUETA" => "Fecha_Nac"
            ],
            [
                "CAMPO" => "nombre_role",
                "ETIQUETA" => "Role"
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
            "URL" => Sistema::app()->generaURL(array("cliente", "Index")),
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

        // Llamamos a la vista index de Cliente
        $this->dibujaVista("index", ["filas" => $filas, "cabecera" => $cabecera, "paginador" => $opcPaginador], "Cliente");
        exit;
    } // End de la accion Index

    /**
     * Estamos definiendo la accionCrearCliente se encarga de crear un CLIENTE
     */
    public function accionCrearCliente(){
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
        $cliente = new Cliente();
        $nombre = $cliente->getNombre();

        // Llamamos al boton del formulario de crear cliente
        if (isset($_POST["crear_usuario"])){
            // Guardamos los valores
            $cliente->setValores($_POST[$nombre]);

            // Guardamos y validamos los valores para cada atributo del objeto Cliente
            if ($cliente->validar()){
                if ($cliente->guardar()){
                    Sistema::app()->irAPagina(["cliente"]);
                    return; 
                } // End if 2
                else{
                    $this->dibujaVista("crearCliente", ["cliente" => $cliente], "Crear Cliente");
                    exit;
                } // End else 2
            } // End id 1
            else{
                $this->dibujaVista("crearCliente", ["cliente" => $cliente], "Crear Cliente");
                exit;
            } // End else 1
        } // End del formulario de crear cliente

        // Llamamos a al vista de crearCliente
        $this->dibujaVista("crearCliente", ["cliente" => $cliente], "Crear Cliente");
        exit;
    } // End de la accion de Crear Cliente

    /**
     * Estamos definiendo la accion de Ver para mostrar todos los datos de un modelo de Tipo Cliente en específico
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
        $cliente = new Cliente();
        $id = 0;

        // Obtenemos el id de ese modelo
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del id

        // Buscamos por ID
        $filas = $cliente->buscarPorId($id);

        // Comprobariamos si ese modelo existe o no
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de ese cliente");
            return;
        } // End if de la validacion

        // Llamamos al ver Datos del modelo Cliente
        $this->dibujaVista("verDatos", ["cliente" => $cliente], "Ver Cliente");
        exit;
    } // End de la accion Ver

    /**
     * Estamos definiendo la accion de Modificar Cliente de un modelo en específico
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
        $cliente = new Cliente();
        $nombre = $cliente->getNombre();
        $id = 0;

        // Obtenemos el id del modelo en especifico
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del ID

        // Buscamos por el ID cliente si existe
        $filas = $cliente->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de ese Cliente");
            return;
        } // End if de que exista

        // Obtenemos el nombre del role en específico
        $cliente->nombre_role = Sistema::app()->ACL()->getCodRole($cliente->nombre_role);

        // Llamamos al formulario para modificar los datos
        if (isset($_POST["actualizar"])){
            // Establecemos los valores para el modelo
            $cliente->setValores($_POST[$nombre]);
            $cliente->cod_cliente = $id;

            // Validamos el modelo y actualizamos los datos y los errores
            if ($cliente->validar()){
                if ($cliente->guardar()){
                    Sistema::app()->irAPagina(["cliente"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("modificarDatos", ["cliente" => $cliente], "Modificar Cliente");
                    exit;
                } // End else 2
            } // End if 1
            else{
                $this->dibujaVista("modificarDatos", ["cliente" => $cliente], "Modificar Cliente");
                exit;
            } // End else 1

        } // End if del boton actualizar

        // Llamamos a la vista de modificar Datos de ese Modelo
        $this->dibujaVista("modificarDatos", ["cliente" => $cliente], "Modificar Cliente");
        exit;
    } // End de la accion de Modificar

    /**
     * Estamos definiendo la accion de Borrar Cliente de un modelo en específico
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

        // Definimos nuestras variables locales
        $cliente = new Cliente();
        $id = 0;

        // Comprobamos de que exista
        if(isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if

        // Buscamos al cliente por el codigo si existe
        $filas = $cliente->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de ese Cliente");
            return;
        } // End if de que exista

        // Realizamos la sentencia de borrado logico
        $sentencia = "update cliente set borrado = true where cod_cliente = '$id'";

        // Comprobamos si se ha realizado
        if (!$cliente->ejecutarSentencia($sentencia)){
            Sistema::app()->paginaError(404, "ERROR!! Se ha producido un error en la base de datos");
            return;
        } // End if

        // Redireccionamos a la accion Consultar Cliente
        Sistema::app()->irAPagina(["cliente", "Index"]);
        return;
    } // End de la accion de Borrar 

} // End de la clase controlador cliente