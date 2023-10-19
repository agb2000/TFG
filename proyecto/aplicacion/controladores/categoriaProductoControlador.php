<?php

/**
 * Estamos definiendo el Controlador de Categorira Producto
 */
class categoriaProductoControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para el crud de Categoría Producto.
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->accionDefecto = "Index";
        $this->plantilla = "dashboard";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de dibujar el crud de Categorias Productos y realizariamos un paginador
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
        $categoria = new Categoria_Producto();

        // Estamos definiendo el funcionamiento del paginador de Categoria Producto
        $registros = intval($categoria->buscarTodosNRegistros());

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
        $filas = $categoria->buscarTodos($opciones);

        // En caso de que haya fila guardaremos las acciones de ver/modificar/borrar Categoria_Producto
        if ($filas){
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de ver los datos de Categoria Producto
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/24x24/ver.png"),
                    Sistema::app()->generaURL(
                        ["categoriaProducto", "Ver"],
                        ["id" => $filas[$key]["cod_categoria_producto"]]
                    ),
                    ["title" => "Ver Categoria ".$filas[$key]["cod_categoria_producto"]]
                );

                // Estamos definiendo el boton de modificar datos de Categoria Producto
                $cadena .= CHTML::link(
                    CHTML::imagen("/imagenes/24x24/modificar.png"),
                    Sistema::app()->generaURL(
                        ["categoriaProducto", "Modificar"],
                        ["id" => $filas[$key]["cod_categoria_producto"]]
                    ),
                    ["title" => "Modificar Categoria ".$filas[$key]["cod_categoria_producto"]]
                );

                // Estamos definiendl el boton de borrar Categoria Producto
                $cadena .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png"), 
                    Sistema::app()->generaURL(["categoriaProducto", "Borrar"],
                        ["id" => $filas[$key]["cod_categoria_producto"]]), 
                ["class" => "borrar", "title" => "Borrar Categoria ".$filas[$key]["cod_categoria_producto"]]);

                // Añadimos la columna operaciones a cada fila
                $filas[$key]["operaciones"] = $cadena;     

                // Reemplazamos cuando sea 0 por NO y 1 por SI
                if ($filas[$key]["borrado"] == 0){
                    $filas[$key]["borrado"] = str_replace("0", "No", $filas[$key]["borrado"]);
                } // End if
                else{
                    $filas[$key]["borrado"] = str_replace("1", "Si", $filas[$key]["borrado"]);
                } // End else 

                // Hacemos que el nombre de categoria sea la primera letra mayuscula y las demas letras en minusculas
                $filas[$key]["nombre_categoria"] = mb_strtoupper(mb_substr($filas[$key]["nombre_categoria"], 0, 1)).
                mb_strtolower(mb_substr($filas[$key]["nombre_categoria"], 1, mb_strlen($filas[$key]["nombre_categoria"])));
            } // End foreach
        } // End if $filas

        // Definimos la cabecera de la tabla de datos
        $cabecera = [
            [
                "CAMPO" => "nombre_categoria",
                "ETIQUETA" => "Nombre Categoria"
            ],
            [
                "CAMPO" => "descripcion_categoria",
                "ETIQUETA" => "Descripcion Categoria"
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
            "URL" => Sistema::app()->generaURL(array("categoriaProducto", "Index")),
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

        // Llamamos a la vista index de categoriaProducto
        $this->dibujaVista("index", ["cabecera" => $cabecera, "filas" => $filas, "paginador" => $opcPaginador], 
            "Categoria Producto");
        exit;
    } // End la accion Index

    /**
     * Estamos definiendo la accionCrearCategoria se encarga de crear un categoria de producto
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
        $categoria = new Categoria_Producto();
        $nombre = $categoria->getNombre();

        // Llamamos al boton del formulario de crear categoria
        if (isset($_POST["crear_categoria"])){
            // Guardamos los valores
            $categoria->setValores($_POST[$nombre]);

            // Guardamos y validamos los valores para cada atributo del objeto CategoriaProducto
            if ($categoria->validar()){
                if ($categoria->guardar()){
                    Sistema::app()->irAPagina(["categoriaProducto"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("crearCategoria", ["categoria" => $categoria], "Crear Categoria Producto");
                    exit;
                } // End else 2
            } // End if 1 
            else{
                $this->dibujaVista("crearCategoria", ["categoria" => $categoria], "Crear Categoria Producto");
                exit;
            } // End else 1
        } // End del boton formulario
        
        // Llamamos a al vista de crearCategoria
        $this->dibujaVista("crearCategoria", ["categoria" => $categoria], "Crear Categoria Producto");
        exit;
    } // End de la accion de Crear Categoria Producto

    /**
     * Estamos definiendo la accion de Ver para mostrar todos los datos de un modelo de Tipo Categoria Producto en específico
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
        $categoria = new Categoria_Producto();
        $id = 0;

        // Obtenemos el id de ese modelo
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del id

        // Buscamos por ID
        $filas = $categoria->buscarPorId($id);

        // Comprobariamos si ese modelo existe o no
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id Categoria Producto");
            return;
        } // End if de la validacion

        // Llamamos al ver Datos del modelo Categoria Producto
        $this->dibujaVista("verDatos", ["categoria" => $categoria], "Ver Categoria Producto");
        exit;
    } // End de la accion Ver

    /**
     * Estamos definiendo la accion de Modificar Categoria de Producto de un modelo en específico
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
        $categoria = new Categoria_Producto();
        $nombre = $categoria->getNombre();
        $id = 0;

        // Obtenemos el id del modelo en especifico
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del ID

        $filas = $categoria->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id Categoria Producto");
            return;
        } // End if de que exista

        // Llamamos al formulario para modificar los datos
        if (isset($_POST["actualizar"])){
            // Establecemos los valores para el modelo
            $categoria->setValores($_POST[$nombre]);
            $categoria->cod_categoria_producto = $id;

            // Validamos el modelo y actualizamos los datos y los errores
            if ($categoria->validar()){
                if ($categoria->guardar()){
                    Sistema::app()->irAPagina(["categoriaProducto"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("modificarDatos", ["categoria" => $categoria], "Modificar Categoria Producto");
                    exit;
                } // End else 2
            } // End if 1
            else{
                $this->dibujaVista("modificarDatos", ["categoria" => $categoria], "Modificar Categoria Producto");
                exit;
            } // End else 1

        } // End if del boton actualizar

        // Llamamos a la vista de modificar Datos de ese Modelo
        $this->dibujaVista("modificarDatos", ["categoria" => $categoria], "Modificar Categoria Producto");
        exit;
    } // End de la accion Modificar

    /**
     * Estamos definiendo la accion de Borrar Categoria de Producto de un modelo en específico
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
        $categoria = new Categoria_Producto();
        $id = 0;

        // Comprobamos de que exista
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if 

        // Buscamos por id
        $filas = $categoria->buscarPorId($id);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id Categoria Producto");
            return;
        }// End if

        // Realizamos la sentencia de borrado logico
        $sentencia = "update categoria_producto set borrado = true where cod_categoria_producto = '$id'";

        // Comprobamos si se ha realizado
        if (!$categoria->ejecutarSentencia($sentencia)) {
            Sistema::app()->paginaError(404, "ERROR!! Se ha producido un error en la base de datos");
            return;
        } // End if

        // Redireccionamos a la accion Consultar Categoria
        Sistema::app()->irAPagina(["categoriaProducto", "Index"]);
        return;
    } // End de la accion Borrar

} // End de la clase categoria Controlador