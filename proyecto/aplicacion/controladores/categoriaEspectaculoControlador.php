<?php

/**
 * Estamos definiendo el Controlador de Categorira Espectáculo
 */
class categoriaEspectaculoControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para el crud de Categoría Espectaculo.
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->plantilla = "dashboard";
        $this->accionDefecto = "Index";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de dibujar el crud de Categorias Espectaculo y realizariamos un paginador.
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
        $cat_espe = new Categoria_Espectaculo();

        // Estamos definiendo el funcionamiento del paginador de Categoria Espectaculo
        $registros = intval($cat_espe->buscarTodosNRegistros());

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
        $filas = $cat_espe->buscarTodos($opciones);

        // En caso de que haya fila guardaremos las acciones de ver/modificar/borrar Categoria_Espectaculo
        if ($filas){
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de ver los datos de categoria espectaculo
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/24x24/ver.png"),
                    Sistema::app()->generaURL(
                        ["categoriaEspectaculo", "Ver"],
                        ["id" => $filas[$key]["cod_categoria_espectaculo"]]
                    )
                );

                // Estamos definiendo el boton de modificar datos de categoria espectaculo
                $cadena .= CHTML::link(
                    CHTML::imagen("/imagenes/24x24/modificar.png"),
                    Sistema::app()->generaURL(
                        ["categoriaEspectaculo", "Modificar"],
                        ["id" => $filas[$key]["cod_categoria_espectaculo"]]
                    )
                );

                // Estamos definiendo el boton de borrar Categoria Espectaculo
                $cadena .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png"), 
                Sistema::app()->generaURL(["categoriaEspectaculo", "Borrar"],
                    ["id" => $filas[$key]["cod_categoria_espectaculo"]]), 
                    ["class" => "borrar", "title" => "Borrar Categoria Espectaculo ".$filas[$key]["cod_categoria_espectaculo"]]);
                
                // Guardamos las operaciones del crud
                $filas[$key]["operaciones"] = $cadena;     

                // Hacemos un reemplazo para el que cuando salgo un 0 devuelva no y cuando salga un 1 devuelva un si
                if ($filas[$key]["borrado"] == 0){
                    $filas[$key]["borrado"] = str_replace("0", "No", $filas[$key]["borrado"]);
                } // End if 1
                else{
                    $filas[$key]["borrado"] = str_replace("1", "Si", $filas[$key]["borrado"]);
                } // End else 1

                // Realizamos de que la primera letra del nombre categoria espectaculo sea mayuscula
                $filas[$key]["nombre_categoria_espectaculo"] = mb_strtoupper(mb_substr($filas[$key]["nombre_categoria_espectaculo"], 0, 1)).
                mb_strtolower(mb_substr($filas[$key]["nombre_categoria_espectaculo"], 1, mb_strlen($filas[$key]["nombre_categoria_espectaculo"])));
            } // End foreach

        } // End if filas

        // Definimos la cabecera para la tabla de Categoria Espectaculo
        $cabecera = [
            [
                "CAMPO" => "nombre_categoria_espectaculo",
                "ETIQUETA" => "Nombre de la Categoria Espectaculo"
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
            "URL" => Sistema::app()->generaURL(array("categoriaEspectaculo", "Index")),
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

        // Llamamos a la vista index de Categoria Espectaculo
        $this->dibujaVista("index", ["filas" => $filas, "cabecera" => $cabecera, "paginador" => $opcPaginador], 
            "Categoria Espectaculo");
        exit;
    } // End de la accion Index

    /**
     * Definiriamos la accion de CrearCategoria para poder crear un modelo de Tipo Categoria Espectaculo
     */
    public function accionCrearCategoria(){
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
        $cat_espe = new Categoria_Espectaculo();
        $nombre = $cat_espe->getNombre();

        // Llamamos al boton del formulario de crear categoria Espectaculo
        if (isset($_POST["crear_cat_espe"])){
            // Guardamos los valores
            $cat_espe->setValores($_POST[$nombre]);

            // Guardamos y validamos los valores para cada atributo del objeto categoria Espectaculo
            if ($cat_espe->validar()){
                if ($cat_espe->guardar()){
                    Sistema::app()->irAPagina(["categoriaEspectaculo"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("crearCategoria", ["cat_espe" => $cat_espe], "Crear Categoria Espectaculo");
                    exit;
                } // End else 2
            } // End if 1 
            else{
                $this->dibujaVista("crearCategoria", ["cat_espe" => $cat_espe], "Crear Categoria Espectaculo");
                exit;
            } // End else 1
        } // End del boton formulario

        // Llamamos a la vista de Crear Categoria Espectaculo
        $this->dibujaVista("crearCategoria", ["cat_espe" => $cat_espe], "Crear Categoria Espectaculo");
        exit;
    } // End de la accion de Crear Categoria Espectaculo

    /**
     * Estamos definiendo la accion de Ver para mostrar todos los datos de un modelo de Tipo Categoria Espectaculo en específico
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
        $cat_espe = new Categoria_Espectaculo();
        $id = 0;

        // Obtenemos el id de ese modelo
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la validacion

        // Buscamos por ID
        $filas = $cat_espe->buscarPorId($id);

        // Comprobariamos si ese modelo existe o no
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado esa Categoria de Espectaculo no existe");
            return;
        } // End if

        // Llamamos al ver Datos del modelo Categoria Espectaculo
        $this->dibujaVista("verDatos", ["cat_espe" => $cat_espe], "Ver Categoria Espectaculo");
        exit;
    } // End de la accion Ver

    /**
     * Estamos definiendo la accion de Modificar Categoria de Espectaculo de un modelo en específico
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
        $cat_espe = new Categoria_Espectaculo();
        $nombre = $cat_espe->getNombre();
        $id = 0;

        // Obtenemos el id del modelo en especifico
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la validacion

        // Buscamos si el existe
        $filas = $cat_espe->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado esa Categoria Espectaculo");
            return;
        } // End if de filas

        // Llamamos al formulario para modificar los datos
        if (isset($_POST["actualizar"])){
            // Le asignamos los valores y el cod de categoria espectaculo
            $cat_espe->setValores($_POST[$nombre]);
            $cat_espe->cod_categoria_espectaculo = $id;

            if ($cat_espe->validar()){
                if ($cat_espe->guardar()){
                    Sistema::app()->irAPagina(["categoriaEspectaculo"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("modificarDatos", ["cat_espe" => $cat_espe], "Modificar Categoria Espectaculo");
                    exit;
                } // End else 2
            } // End if 1
            else{
                $this->dibujaVista("modificarDatos", ["cat_espe" => $cat_espe], "Modificar Categoria Espectaculo");
                exit;
            } // End else 1

        } // End if del boton actualizar

        // Llamamos a la vista de modificar Datos de ese Modelo
        $this->dibujaVista("modificarDatos", ["cat_espe" => $cat_espe], "Modificar Categoria Espectaculo");
        exit;
    } // End de la accion Modificar

    /**
     * Estamos definiendo la accion de Borrar Categoria de Espectaculo de un modelo en específico
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
        $cat_espe = new Categoria_Espectaculo();
        $id = 0;

        // Comprobamos de que exista
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if 

        // Buscamos por id
        $filas = $cat_espe->buscarPorId($id);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de esa Participante");
            return;
        }// End if

        // Realizamos la sentencia de borrado logico
        $sentencia = "update categoria_espectaculo set borrado = true where cod_categoria_espectaculo = '$id'";

        // Comprobamos si se ha realizado
        if (!$cat_espe->ejecutarSentencia($sentencia)) {
            Sistema::app()->paginaError(404, "ERROR!! Se ha producido un error en la base de datos");
            return;
        } // End if

        // Redireccionamos a la accion Consultar Categoria
        Sistema::app()->irAPagina(["categoriaEspectaculo", "Index"]);
        return;
    } // End de la accion Borrar

} // End de la clase participantes Controlador