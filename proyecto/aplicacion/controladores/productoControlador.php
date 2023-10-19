<?php

/**
 * Estamos definiendo el Controlador de Producto
 */
class productoControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para el crud de Producto.
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->accionDefecto = "Index";
        $this->plantilla = "dashboard";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de dibujar el crud de Productos y realizariamos un paginador
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
        $producto = new Producto();
        $datos_filtrados = ["nombre_categoria" => "", "borrado" => ""];
        $opciones = [];

        // Hacemos el filtrado de pagina
        if (isset($_POST["filtrar"])){
            // Filtramos el nombre de Categoria
            if (isset($_POST["nombre_categoria"])) {
                if (!empty($_POST["nombre_categoria"])) {
                    $nombre = intval($_POST["nombre_categoria"]);
                    $opciones["where"] = " cod_categoria_producto = '$nombre'";
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
                $opciones["where"] = " cod_categoria_producto = '$cod'";
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

        // Estamos definiendo el funcionamiento del paginador de Categoria Producto
        $registros = intval($producto->buscarTodosNRegistros($opciones));

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

        // Establecemos el limite
        $opciones["limit"] = "$inicio, $tamPagina";  

        // Obtenemos las filas con esas condiciones
        $filas = $producto->buscarTodos($opciones);

        // En caso de que haya fila guardaremos las acciones de ver/modificar/borrar Producto
        if ($filas){
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de ver los datos de Producto
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/24x24/ver.png"),
                    Sistema::app()->generaURL(
                        ["producto", "Ver"],
                        ["id" => $filas[$key]["cod_producto"]]
                    )
                );

                // Estamos definiendo el boton de modificar datos de Producto
                $cadena .= CHTML::link(
                    CHTML::imagen("/imagenes/24x24/modificar.png"),
                    Sistema::app()->generaURL(
                        ["producto", "Modificar"],
                        ["id" => $filas[$key]["cod_producto"]]
                    )
                );

                // Estamos definiendl el boton de borrar Producto
                $cadena .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png"), 
                    Sistema::app()->generaURL(["producto", "Borrar"],
                        ["id" => $filas[$key]["cod_producto"]]), 
                        ["class" => "borrar", "title" => "Borrar Producto ".$filas[$key]["cod_producto"]]);
                
                // Añadimos la columna operaciones a cada fila
                $filas[$key]["operaciones"] = $cadena;     

                // Reemplazamos cuando sea 0 por NO y 1 por SI
                if ($filas[$key]["borrado"] == 0){
                    $filas[$key]["borrado"] = str_replace("0", "No", $filas[$key]["borrado"]);
                } // End if
                else{
                    $filas[$key]["borrado"] = str_replace("1", "Si", $filas[$key]["borrado"]);
                } // End else

                // Cambiamos el formato de la fecha para que sea en formato dia/mes/Año
                $filas[$key]["fecha_alta"] = CGeneral::fechaMysqlANormal($filas[$key]["fecha_alta"]);

                // Guardaremos la imagen
                $filas[$key]["imagen"] = CHTML::imagen($filas[$key]["imagen"], "Imagen del Producto", ["class" => "card-img-top"]);
            } // End foreach
        } // End if filas

        // Definimos la cabecera de la tabla de datos
        $cabecera = [
            [
                "CAMPO" => "nombre_producto",
                "ETIQUETA" => "Nombre"
            ],
            [
                "CAMPO" => "unidades",
                "ETIQUETA" => "Unidades"
            ],
            [
                "CAMPO" => "precio",
                "ETIQUETA" => "Precio"
            ],
            [
                "CAMPO" => "fecha_alta",
                "ETIQUETA" => "Fecha_Alta"
            ],
            [
                "CAMPO" => "nombre_categoria",
                "ETIQUETA" => "Nombre_Categoria"
            ],
            [
                "CAMPO" => "imagen",
                "ETIQUETA" => "Imagen"
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
            "URL" => Sistema::app()->generaURL(array("producto")),
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

        // Llamamos a la vista index de Producto
        $this->dibujaVista("index", ["filas" => $filas, "cabecera" => $cabecera, "paginador" => $opcPaginador, "datos_filtrados" => $datos_filtrados], "Producto");
        exit;
    } // End de la accion Index

    /**
     * Estamos definiendo la accionCrearProducto se encarga de crear un producto
     */
    public function accionCrearProducto(){
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
        $producto = new Producto();
        $nombre = $producto->getNombre();

        // Llamamos al boton del formulario de crear Producto
        if (isset($_POST["crear_producto"])){
            // Guardamos los valores y cambiamos el formato a la fecha
            $producto->setValores($_POST[$nombre]);
            $producto->fecha_alta = date_format(new DateTime($producto->fecha_alta), "d/m/Y");

            // Guardamos y validamos los valores para cada atributo del objeto CategoriaProducto
            if ($producto->validar()){
                if ($producto->guardar()){
                    Sistema::app()->irAPagina(["producto"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("crearProducto", ["producto" => $producto], "Crear Producto");
                    exit;
                } // End else 2
            } // End if 1 
            else{
                $this->dibujaVista("crearProducto", ["producto" => $producto], "Crear Producto");
                exit;
            } // End else 1
        } // End del boton formulario

        // Llamamos a al vista de crearProducto
        $this->dibujaVista("crearProducto", ["producto" => $producto], "Crear Producto");
        exit;
    } // End de la accion de Crear Producto

    /**
     * Estamos definiendo la accion de Ver para mostrar todos los datos de un modelo de Tipo Producto en específico
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
        $producto = new Producto();
        $id = 0;

        // Obtenemos el id de ese modelo
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        }

        // Buscamos por ID
        $filas = $producto->buscarPorId($id);

        // Comprobariamos si ese modelo existe o no
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id Producto");
            return;
        } // End if de la validacion

        // Llamamos al ver Datos del modelo Producto
        $this->dibujaVista("verDatos", ["producto" => $producto], "Ver Producto");
        exit;
    } // End de la accion de Ver 

    /**
     * Estamos definiendo la accion de Modificar Producto de un modelo en específico
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
        $producto = new Producto();
        $nombre = $producto->getNombre();
        $id = 0;

        // Obtenemos el id del modelo en especifico
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del id

        // Comprobamos de que exista ese ID PRODUCTO
        $filas = $producto->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id Producto");
            return;
        } // End if de filas

        // Llamamos al formulario para modificar los datos
        if (isset($_POST["actualizar"])){
            // Asignamos valores al modelo y modificamos el formato de la fecha
            $producto->setValores($_POST[$nombre]);
            $producto->cod_producto = $id;
            $producto->fecha_alta = date_format(new DateTime($producto->fecha_alta), "d/m/Y");

            if ($producto->validar()){
                if ($producto->guardar()){
                    Sistema::app()->irAPagina(["producto"]);
                    return;
                } // End if 2
                else{
                    $this->dibujaVista("modificarDatos", ["producto" => $producto], "Modificar Producto");
                    exit;
                } // End else 2
            } // End if 1
            else{
                $this->dibujaVista("modificarDatos", ["producto" => $producto], "Modificar Producto");
                exit;
            } // End else 1

        } // End if del boton actualizar

        // Llamamos a la vista de modificar Datos de ese Modelo
        $this->dibujaVista("modificarDatos", ["producto" => $producto], "Modificar Producto");
        exit;
    } // End de la accion de Modificar

    /**
     * Estamos definiendo la accion de Borrar Producto de un modelo en específico
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
        $producto = new Producto();
        $id = 0;

        // Comprobamos de que exista
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if 

        // Buscamos por id
        $filas = $producto->buscarPorId($id);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con ese id Producto");
            return;
        }// End if

        // Realizamos la sentencia de borrado logico
        $sentencia = "update producto set borrado = true where cod_producto = '$id'";

        // Comprobamos si se ha realizado
        if (!$producto->ejecutarSentencia($sentencia)) {
            Sistema::app()->paginaError(404, "ERROR!! Se ha producido un error en la base de datos");
            return;
        } // End if

        // Redireccionamos a la accion Consultar Categoria
        Sistema::app()->irAPagina(["producto", "Index"]);
        return;
    } // End de la accion Borrar

} // End de la clase producto Controlador