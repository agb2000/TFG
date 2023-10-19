<?php

/**
 * Estamos definiendo el Controlador de Participantes Espectaculo
 */
class partiEspeControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para el crud de Participantes en Espectaculos.
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->plantilla = "dashboard";
        $this->accionDefecto = "Index";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de dibujar el crud de Participantes en Espectaculo y realizariamos un paginador
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
        $part_espe = new Part_Espe();

        // Estamos definiendo el funcionamiento del paginador de Participantes en Espectaculo
        $registros = intval($part_espe->buscarTodosNRegistros());

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
        $filas = $part_espe->buscarTodos($opciones);

        // En caso de que haya fila guardaremos las acciones de ver/modificar/borrar Participantes en Espectaculos
        if ($filas){
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de ver los datos Participantes en Espectaculos
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/24x24/ver.png"),
                    Sistema::app()->generaURL(
                        ["partiEspe", "Ver"],
                        ["id" => $filas[$key]["cod_participantes_espectaculos"]]
                    )
                );

                // Estamos haciendo el boton de modificar los datos Participantes en Espectaculos
                $cadena .= CHTML::link(
                    CHTML::imagen("/imagenes/24x24/modificar.png"),
                    Sistema::app()->generaURL(
                        ["partiEspe", "Modificar"],
                        ["id" => $filas[$key]["cod_participantes_espectaculos"]]
                    )
                );

                // Estamos haciendo el boton de borrar los datos Participantes en Espectaculos
                $cadena .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png"), 
                Sistema::app()->generaURL(["partiEspe", "Borrar"],
                    ["id" => $filas[$key]["cod_participantes_espectaculos"]]), 
                    ["class" => "borrar", "title" => "Borrar Categoria Espectaculo ".$filas[$key]["cod_participantes_espectaculos"]]);
                
                // Añadimos la columna operaciones a cada fila
                $filas[$key]["operaciones"] = $cadena;     

                // Reemplazamos cuando sea 0 por NO y 1 por SI
                if ($filas[$key]["borrado"] == 0){
                    $filas[$key]["borrado"] = str_replace("0", "No", $filas[$key]["borrado"]);
                } // End if 1
                else{
                    $filas[$key]["borrado"] = str_replace("1", "Si", $filas[$key]["borrado"]);
                } // End else 1

                // Cambiamos de formato a la fecha de Inicio y a la fecha fin
                $filas[$key]["fecha_lanzamiento"] = CGeneral::fechaMysqlANormal($filas[$key]["fecha_lanzamiento"]);
                $filas[$key]["fecha_finalizacion"] = CGeneral::fechaMysqlANormal($filas[$key]["fecha_finalizacion"]);

                // Hacemos la tranformacion de la imagen en un CHTML de tipo imagen
                $filas[$key]["imagen"] = CHTML::imagen($filas[$key]["imagen"], "Pancarta", ["class" => "card-img-top"]);
            } // End foreach
        } // End if filas

        // Definimos la cabecera de la tabla de datos Participantes en Espectaculo
        $cabecera = [
            [
                "CAMPO" => "titulo",
                "ETIQUETA" => "Titulo"
            ],
            [
                "CAMPO" => "nombre_participante",
                "ETIQUETA" => "Actor"
            ],
            [
                "CAMPO" => "duracion",
                "ETIQUETA" => "Duracion"
            ],
            [
                "CAMPO" => "fecha_lanzamiento",
                "ETIQUETA" => "Fecha Inicio"
            ],
            [
                "CAMPO" => "fecha_finalizacion",
                "ETIQUETA" => "Fecha Fin"
            ],
            [
                "CAMPO" => "imagen",
                "ETIQUETA" => "Pancarta"
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
            "URL" => Sistema::app()->generaURL(array("partiEspe", "Index")),
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

        // Llamamos a la vista index de Participantes en Espectaculo
        $this->dibujaVista("index", ["filas" => $filas, "cabecera" => $cabecera, "paginador" => $opcPaginador], "Participante Espectaculo");
        exit;
    } // End de la accion Index

    /**
     * Estamos definiendo la accionCrearParticipantes_Espectaculos se encarga de añadir un Participante a un Espectaculo
     */
    public function accionCrearParticipantes_Espectaculos(){
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
        $parti_espe = new Part_Espe();
        $nombre = $parti_espe->getNombre();

        // Llamamos al boton del formulario de añadir un Participante a un Espectaculo
        if (isset($_POST["crear_parti_espe"])){
            // Guardamos los valores
            $parti_espe->setValores($_POST[$nombre]);

            // Guardamos y validamos los valores para cada atributo del objeto Part_Espe
            if ($parti_espe->validar()){
                if ($parti_espe->guardar()){
                    Sistema::app()->irAPagina(["partiEspe"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("crearPartiEspe", ["parti_espe" => $parti_espe], "Crear Participante Espectaculo");
                    exit;
                } // End else 2
            } // End if 1 
            else{
                $this->dibujaVista("crearPartiEspe", ["parti_espe" => $parti_espe], "Crear Participante Espectaculo");
                exit;
            } // End else 1
        } // End del boton formulario

        // Llamamos a al vista de crearPartiEspe
        $this->dibujaVista("crearPartiEspe", ["parti_espe" => $parti_espe], "Crear Participante Espectaculo");
        exit;
    } // End de la accion Añadir un Participante a un Espectaculo

    /**
     * Estamos definiendo la accion de Ver para mostrar todos los datos de un modelo de PartiEspe en específico
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
        $part_espe = new Part_Espe();
        $id = 0;

        // Obtenemos el id de ese modelo
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del id

        // Buscamos por ID
        $filas = $part_espe->buscarPorId($id);

        // Comprobariamos si ese modelo existe o no
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id Participante Espectaculo");
            return;
        } // End if de la validacion

        // Llamamos al ver Datos del modelo Participante_Espectaculo
        $this->dibujaVista("verDatos", ["part_espe" => $part_espe], "Ver Participante Espectaculo");
        exit;
    } // End de la accion Ver

    /**
     * Estamos definiendo la accion de Modificar Participante Espectaculo de un modelo en específico
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
        $part_espe = new Part_Espe();
        $nombre = $part_espe->getNombre();
        $id = 0;

        // Obtenemos el id del modelo en especifico
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del ID

        $filas = $part_espe->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id Participantes Espectaculo");
            return;
        } // End if de que exista

        // Llamamos al formulario para modificar los datos
        if (isset($_POST["actualizar"])){
            // Establecemos los valores para el modelo
            $part_espe->setValores($_POST[$nombre]);
            $part_espe->cod_participantes_espectaculos = $id;

            // Validamos el modelo y actualizamos los datos y los errores
            if ($part_espe->validar()){
                if ($part_espe->guardar()){
                    Sistema::app()->irAPagina(["partiEspe"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("modificarDatos", ["part_espe" => $part_espe], "Modificar Participantes Espectaculos");
                    exit;
                } // End else 2
            } // End if 1
            else{
                $this->dibujaVista("modificarDatos", ["part_espe" => $part_espe], "Modificar Participantes Espectaculos");
                exit;
            } // End else 1

        } // End if del boton actualizar

        // Llamamos a la vista de modificar Datos de ese Modelo
        $this->dibujaVista("modificarDatos", ["part_espe" => $part_espe], "Modificar Participantes Espectaculos");
        exit;
    } // End de la accion Modificar

    /**
     * Estamos definiendo la accion de Borrar Participante Espectaculo de un modelo en específico
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
        $part_espe = new Part_Espe();
        $id = 0;

        // Comprobamos de que exista
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if 

        // Buscamos por id
        $filas = $part_espe->buscarPorId($id);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id de Participantes Espectaculos");
            return;
        }// End if

        // Realizamos la sentencia de borrado logico
        $sentencia = "update participantes_espectaculos set borrado = true where cod_participantes_espectaculos = '$id'";

        // Comprobamos si se ha realizado
        if (!$part_espe->ejecutarSentencia($sentencia)) {
            Sistema::app()->paginaError(404, "ERROR!! Se ha producido un error en la base de datos");
            return;
        } // End if

        // Redireccionamos a la accion Consultar Categoria
        Sistema::app()->irAPagina(["partiEspe", "Index"]);
        return;
    } // End de la accion Borrar

} // End de la clas Controlador Participantes Espectaculos