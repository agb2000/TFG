<?php

/**
 * Estamos definiendo el Controlador de Participantes
 */
class participantesControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para el crud de Participantes.
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->plantilla = "dashboard";
        $this->accionDefecto = "Index";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de dibujar el crud de Participantes y realizariamos un paginador
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
        $participantes = new Participantes();

        // Estamos definiendo el funcionamiento del paginador de Participantes
        $registros = intval($participantes->buscarTodosNRegistros());

        $tamPagina = 5;

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
        $filas = $participantes->buscarTodos($opciones);

        // En caso de que haya fila guardaremos las acciones de ver/modificar/borrar Participantes
        if ($filas){
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de ver los datos de Participantes
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/24x24/ver.png"),
                    Sistema::app()->generaURL(
                        ["participantes", "Ver"],
                        ["id" => $filas[$key]["cod_participante"]]
                    )
                );

                // Estamos definiendo el boton de modificar datos de Participantes
                $cadena .= CHTML::link(
                    CHTML::imagen("/imagenes/24x24/modificar.png"),
                    Sistema::app()->generaURL(
                        ["participantes", "Modificar"],
                        ["id" => $filas[$key]["cod_participante"]]
                    )
                );

                // Estamos definiendo el boton de borrar Participantes
                $cadena .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png"), 
                Sistema::app()->generaURL(["participantes", "Borrar"],
                    ["id" => $filas[$key]["cod_participante"]]), 
                    ["class" => "borrar", "title" => "Borrar Participante ".$filas[$key]["cod_participante"]]);
                
                // Guardamos las operaciones del crud
                $filas[$key]["operaciones"] = $cadena;     

                // Hacemos un reemplazo para el que cuando salgo un 0 devuelva no y cuando salga un 1 devuelva un si
                if ($filas[$key]["borrado"] == 0){
                    $filas[$key]["borrado"] = str_replace("0", "No", $filas[$key]["borrado"]);
                } // End if 1
                else{
                    $filas[$key]["borrado"] = str_replace("1", "Si", $filas[$key]["borrado"]);
                } // End else 1
            } // End foreach
        } // End if filas

        // Definimos la cabecera para la tabla de Participantes
        $cabecera = [
            [
                "CAMPO" => "nombre_participante",
                "ETIQUETA" => "Nombre del Participante"
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
            "URL" => Sistema::app()->generaURL(array("participantes")),
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
            "MOSTRAR_TAMANIOS" => false,
            "PAGINAS_MOSTRADAS" => 4,
        );

        // Llamamos a la vista index de Participantes
        $this->dibujaVista("index", ["filas" => $filas, "cabecera" => $cabecera, "paginador" => $opcPaginador], "Participante");
        exit;
    } // End de la accion Index

    /**
     * Definiriamos la accion de CrearParticipantes para poder crear un modelo de Tipo Participantes
     */
    public function accionCrearParticipantes(){
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
        $participante = new Participantes();
        $nombre = $participante->getNombre();

        // Llamamos al boton del formulario de crear Participantes
        if (isset($_POST["crear_participante"])){
            // Guardamos los valores
            $participante->setValores($_POST[$nombre]);

            // Guardamos y validamos los valores para cada atributo del objeto Participante
            if ($participante->validar()){
                if ($participante->guardar()){
                    Sistema::app()->irAPagina(["participantes"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("crearParticipantes", ["participante" => $participante], "Crear Participante");
                    exit;
                } // End else 2
            } // End if 1 
            else{
                $this->dibujaVista("crearParticipantes", ["participante" => $participante], "Crear Participante");
                exit;
            } // End else 1
        } // End del boton formulario

        // Llamamos a la vista de Crear Participantes
        $this->dibujaVista("crearParticipantes", ["participante" => $participante], "Crear Participante");
        exit;
    } // End de la accion Crear Participantes

    /**
     * Estamos definiendo la accion de Ver para mostrar todos los datos de un modelo de Tipo Participante en específico
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
        $participante = new Participantes();
        $id = 0;

        // Obtenemos el id de ese modelo
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        }

        // Buscamos por ID
        $filas = $participante->buscarPorId($id);

        // Comprobariamos si ese modelo existe o no
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos ese Participante");
            return;
        }

        // Llamamos al ver Datos del modelo Participantes
        $this->dibujaVista("verDatos", ["participante" => $participante], "Ver Participante");
        exit;
    } // End de la accion Ver

    /**
     * Estamos definiendo la accion de Modificar Participante de un modelo en específico
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
        $participante = new Participantes();
        $nombre = $participante->getNombre();
        $id = 0;

        // Obtenemos el id del modelo en especifico
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del ID

        // Validamos de que exista ese ID Participante
        $filas = $participante->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de ese Participante");
            return;
        } // End if de la validacion

        // Llamamos al formulario para modificar los datos
        if (isset($_POST["actualizar"])){
            // Le asignamos los valores y el cod de participante
            $participante->setValores($_POST[$nombre]);
            $participante->cod_participante = $id;

            if ($participante->validar()){
                if ($participante->guardar()){
                    Sistema::app()->irAPagina(["participantes"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("modificarDatos", ["participante" => $participante], "Modificar Participante");
                    exit;
                } // End else 2
            } // End if 1
            else{
                $this->dibujaVista("modificarDatos", ["participante" => $participante], "Modificar Participante");
                exit;
            } // End else 1
        } // End if del boton actualizar

        // Llamamos a la vista de modificar Datos de ese Modelo
        $this->dibujaVista("modificarDatos", ["participante" => $participante], "Modificar Participante");
        exit;
    } // End de la accion Modificar

    /**
     * Estamos definiendo la accion de Borrar Participante de un modelo en específico
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
        $participante = new Participantes();
        $id = 0;

        // Comprobamos de que exista
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if 

        // Buscamos por id
        $filas = $participante->buscarPorId($id);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de ese Participante");
            return;
        }// End if

        // Realizamos la sentencia de borrado logico
        $sentencia = "update participantes set borrado = true where cod_participante = '$id'";

        // Comprobamos si se ha realizado
        if (!$participante->ejecutarSentencia($sentencia)) {
            Sistema::app()->paginaError(404, "ERROR!! Se ha producido un error en la base de datos");
            return;
        } // End if

        // Redireccionamos a la accion Consultar Categoria
        Sistema::app()->irAPagina(["participantes", "Index"]);
        return;
    } // End de la accion Borrar

} // End de la clase participantes Controlador