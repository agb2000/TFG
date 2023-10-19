<?php

/**
 * Estamos definiendo el Controlador de Sala
 */
class salaControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para el crud de Sala.
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->plantilla = "dashboard";
        $this->accionDefecto = "Index";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de dibujar el crud de Salas y realizariamos un paginador.
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
        $sala = new Sala();

        // Estamos definiendo el funcionamiento del paginador de Salas
        $registros = intval($sala->buscarTodosNRegistros());

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
        $filas = $sala->buscarTodos($opciones);

        // En caso de que haya fila guardaremos las acciones de ver/modificar/borrar Sala
        if ($filas){
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de ver los datos de Sala
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/24x24/ver.png"),
                    Sistema::app()->generaURL(
                        ["sala", "Ver"],
                        ["id" => $filas[$key]["cod_sala"]]
                    )
                );

                // Estamos haciendo el boton de modificar los datos de Sala
                $cadena .= CHTML::link(
                    CHTML::imagen("/imagenes/24x24/modificar.png"),
                    Sistema::app()->generaURL(
                        ["sala", "Modificar"],
                        ["id" => $filas[$key]["cod_sala"]]
                    )
                );

                // Estamos haciendo el boton de borrar los datos de Sala
                $cadena .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png"), 
                Sistema::app()->generaURL(["sala", "Borrar"],
                    ["id" => $filas[$key]["cod_sala"]]), 
                    ["class" => "borrar", "title" => "Borrar Sala ".$filas[$key]["cod_sala"]]);
                
                // Guardamos las operaciones del crud
                $filas[$key]["operaciones"] = $cadena;
                
                // Hacemos un reemplazo para el precio
                $filas[$key]["precio_sala"] = $filas[$key]["precio_sala"]." €";

                // Hacemos un reemplazo para el que cuando salgo un 0 devuelva no y cuando salga un 1 devuelva un si
                if ($filas[$key]["borrado"] == 0){
                    $filas[$key]["borrado"] = str_replace("0", "No", $filas[$key]["borrado"]);
                } // End if 1
                else{
                    $filas[$key]["borrado"] = str_replace("1", "Si", $filas[$key]["borrado"]);
                } // End else 1
            } // End foreach

        } // End if filas

        // Definimos la cabecera para la tabla de Sala
        $cabecera = [
            [
                "CAMPO" => "nombre_sala",
                "ETIQUETA" => "Nombre Sala"
            ],
            [
                "CAMPO" => "capacidad_maxima",
                "ETIQUETA" => "Capacidad"
            ],
            [
                "CAMPO" => "precio_sala",
                "ETIQUETA" => "Precio"
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
            "URL" => Sistema::app()->generaURL(array("sala")),
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

        // Llamamos a la vista index de Sala
        $this->dibujaVista("index", ["filas" => $filas, "cabecera" => $cabecera, "paginador" => $opcPaginador], "Sala");
        exit;
    } // End de la accion Index

    /**
     * Definiriamos la accion de CrearSala para poder crear un modelo de Tipo Sala
     */
    public function accionCrearSala(){
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
        $sala = new Sala();
        $nombre = $sala->getNombre();

        // Llamamos al boton del formulario de crear Sala
        if (isset($_POST["crear_sala"])){
            // Guardamos los valores
            $sala->setValores($_POST[$nombre]);

            // Guardamos y validamos los valores para cada atributo del objeto Sala
            if ($sala->validar()){
                if ($sala->guardar()){
                    Sistema::app()->irAPagina(["sala"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("crearSala", ["sala" => $sala], "Crear Sala");
                    exit;
                } // End else 2
            } // End if 1 
            else{
                $this->dibujaVista("crearSala", ["sala" => $sala], "Crear Sala");
                exit;
            } // End else 1
        } // End del boton formulario

        // Llamamos a la vista de Crear Sala
        $this->dibujaVista("crearSala", ["sala" => $sala], "Crear Sala");
        exit;
    } // End de la accion de CrearSala

    /**
     * Estamos definiendo la accion de Ver para mostrar todos los datos de un modelo de Tipo Sala en específico
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
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina ");
            return;
        } // End if si tiene permiso 2

        // Definimos nuestras variables locales
        $sala = new Sala();
        $id = 0;

        // Obtenemos el id de ese modelo
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del ID

        // Buscamos por ID
        $filas = $sala->buscarPorId($id);

        // Comprobariamos si ese modelo existe o no
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de esa Sala");
            return;
        } // End if

        // Llamamos al ver Datos del modelo Sala
        $this->dibujaVista("verDatos", ["sala" => $sala], "Ver Sala");
        exit;
    } // End de la accion Ver

    /**
     * Estamos definiendo la accion de Modificar Sala de un modelo en específico
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
        $sala = new Sala();
        $nombre = $sala->getNombre();
        $id = 0;

        // Obtenemos el id del modelo en especifico
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End de la obtencion del ID

        // Comprobamos de que exista la sala con ese ID
        $filas = $sala->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado esa Sala");
            return;
        } // End if de la validacion

        // Llamamos al formulario para modificar los datos
        if (isset($_POST["actualizar"])){
            // Le asignamos los valores y el cod de sala
            $sala->setValores($_POST[$nombre]);
            $sala->cod_sala = $id;

            if ($sala->validar()){
                if ($sala->guardar()){
                    Sistema::app()->irAPagina(["sala"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("modificarDatos", ["sala" => $sala], "Modificar Sala");
                    exit;
                } // End else 2
            } // End if 1
            else{
                $this->dibujaVista("modificarDatos", ["sala" => $sala], "Modificar Sala");
                exit;
            } // End else 1

        } // End if del boton actualizar

        // Llamamos a la vista de modificar Datos de ese Modelo
        $this->dibujaVista("modificarDatos", ["sala" => $sala], "Modificar Sala");
        exit;
    } // End de la accion Modificar

    /**
     * Estamos definiendo la accion de Borrar Sala de un modelo en específico
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
        $sala = new Sala();
        $id = 0;

        // Comprobamos de que exista
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if 

        // Buscamos por id
        $filas = $sala->buscarPorId($id);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de esa Sala");
            return;
        }// End if

        // Realizamos la sentencia de borrado logico
        $sentencia = "update sala set borrado = true where cod_sala = '$id'";

        // Comprobamos si se ha realizado
        if (!$sala->ejecutarSentencia($sentencia)) {
            Sistema::app()->paginaError(404, "ERROR!! Se ha producido un error en la base de datos");
            return;
        } // End if

        // Redireccionamos a la accion Consultar Categoria
        Sistema::app()->irAPagina(["sala", "Index"]);
        return;
    } // End de la accion Borrar

} // End de la Sala Controlador