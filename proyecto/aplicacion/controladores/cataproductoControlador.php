<?php

/**
 * Estamos definiendo la clase inicialControlador se seria nuestro controlador inicial cada vez que accedemos 
 * a la pagina principal de nuestra aplicacion
 */
class cataproductoControlador extends CControlador {

	// Definimos nuestras variables privadas
	private array $_array_productos = [];

	/**
     * Estamos definiendo el constructor para nuestra pagina principal
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla principal
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
	public function __construct(){
		// Llamamos a nuestras funciones de plantilla y accionDefecto
		$this->plantilla = "productos";
		$this->accionDefecto = "Productos";

		// Comprobamos si existe previamente la SESSION ARRAY_PRODUCTOS y guardamos los productos guardados en sesion en la variable privada
		if (isset($_SESSION["array_productos"])) {
			$this->_array_productos = $_SESSION["array_productos"];
		} // End if
		else {
			$this->_array_productos = [];
		} // End else
	} // End del constructor
	
	/**
	 * Estamos definiendo la accionProductos la cual se encarga de que cuando pulsemos en el boton de añadir producto se 
	 * añadirá un producto a la cesta de la compra, donde se guardará ese producto en la $_SESION["ARRAY_PRODUCTOS"]
	 */
	public function accionProductos(){
		// Llamamos a la vista de productos donde mostraremos los productos disponibles
		$this->dibujaVista("index", [], "Productos Cinema");
		exit;
	} // End de la accion de Productos

	/**
	 * Estamos definiendo la accion Anadir Productos la cual se encarga de recibir los datos de un producto lo guardamos en un array
	 * y ese array se guarda en una sesion
	 */
	public function accionAnadirProductos(){
		// Definimos nuestras variables locales
		$productosconbd = new Producto();
		$id = 0;

		// Comprobamos de que se le haya pasado el id del producto añadido
		if ($_SERVER["REQUEST_METHOD"] == "POST"){
			if (isset($_POST["cod_producto"])) {
				// Escapamos el id del producto
				$id = intval($_POST["cod_producto"]);
	
				// Validamos de que haya producto con ese id
				if ($productosconbd->buscarPorId($id)) {
	
					// Una vez validado el producto añadidos el producto al array privado de productos
					$this->_array_productos[] = [
						"cod_producto" => $id,
						"nombre" => $productosconbd->nombre_producto,
						"precio" => $productosconbd->precio,
						"cantidad" => 1,
						"imagen" => $productosconbd->imagen
					];
	
					// Una vez guardado el producto en el array privado guardamos el array privado en una sesión
					$_SESSION["array_productos"] = $this->_array_productos;
					echo json_encode(true, JSON_PRETTY_PRINT);
					return;
				} // End if de la validacion de producto con ese id
			} // End if
		} // End del SERVER[REQUEST_METHOD] POST
	} // End de la accion Añadir Productos

	/**
	 * Estamos definiendo la accion ObtenerProductos hace una conexion ajax desde JS a esta accion para obtener todos los productos que
	 * no este borrado y se encuentran en nuestra base de datos
	 */
	public function accionObtenerProductos() {
		// Definimos nuestras variables locales 
		$productos = new Producto();

		// Comprobamos de que la metodo sea el GET para obtener todos los productos correspondientes
		if ($_SERVER['REQUEST_METHOD'] = "GET"){

			// Realizamos una sentencia donde mostraremos todos los productos que no esten borrado y lo mostraremos de forma ascendente el nombre de la categoria
			$datos_productos = $productos->buscarTodos(["where" => "borrado = 0", "order" => "nombre_categoria asc"]);

			// Validamos de que haya productos 
			if ($datos_productos){
				echo json_encode(["correcto" => true, "datos" => $datos_productos], JSON_PRETTY_PRINT);
				return;
			} // End if 1
			else{
				echo json_encode(["correcto" => false], JSON_PRETTY_PRINT);
				return;
			} // End else 1
		} // End if del GET
	} // End de la accion de Obtener Productos

	/**
	 * Estamos definiendo la accion Cesta en la cual se encarga de mostrar todos los productos que hemos añadido a la cesta
	 * en una tabla donde mostraremos sus datos correspondientes y realizaremos una compra de esos productos correspondientes
	 * y validaremos la accion compra.
	 */
	public function accionCesta() {
		// Definimos nuestras variables
		$productos = new ProductoSinBD();

		// Definimos nuestros array locales necesarioas
		$nuevo_array_productos = [];
		$errores = [];
		$array_precio = [];

		// Definimos una variable auxiliar para sumar el precio_total
		$cantidad_total = 0;

		// Eliminamos los repetidos del array de los productos añadidos por el nombre del producto para que solo se añada un tipo de cada producto
		$filas = array_intersect_key($this->_array_productos, array_unique(array_column($this->_array_productos, "nombre")));

		// Reiniciamos el array 
		foreach($filas as $key => $value){
			$nuevo_array_productos[] = $value;
		} // End foreach

		// Llamamos al boton del formulario para pagar los productos añadidos a la vista
		if (isset($_POST["pagar"])){
			// Recorremos el array para recorrer los productos y valdiarlos y realizar la venta y la venta_producto
			foreach ($nuevo_array_productos as $key => $value) {

				// Cambiamos el valor de la cantidad del array 
				$nuevo_array_productos[$key]["cantidad"] = intval($_POST["unidad".$nuevo_array_productos[$key]["cod_producto"]]);

				// Definimos nuestras variables para los productos sin BASE DE DATOS
				$productos = new ProductoSinBD();

				// Asiganamos valores al producto sin BASE DE DATOS
				$productos->cod_producto = $nuevo_array_productos[$key]["cod_producto"];
				$productos->cantidad = intval($_POST["unidad".$nuevo_array_productos[$key]["cod_producto"]]);
				$productos->nombre = $value["nombre"];
				$productos->precio = $value["precio"];
				$productos->imagen = $value["imagen"];

				// Validamos el producto sin BD y comprobamos de que el producto sea correcto
				if ($productos->validar() == false){
					$errores[] = $productos; // Guardamos los errores en un array
				} // End if de validacion
				else{
					// En caso correcto guardamos el precio, codigo del producto y la cantidad del producto en otro array
					$array_precio[] = [$productos->precio, $productos->cod_producto, $productos->cantidad];

					// Incrementamos la cantidad total para el precio total de la compra
					$cantidad_total += ($productos->precio * $productos->cantidad); 
				} // End else

			} // End foreach

			// Comprobamos de que no haya errores
			if (!$errores){
				// Definimos nuestras variables locales
				$ventas = new Ventas();

				// Asignamos valor al modelo VENTA añadiendo el nick del usuario iniciado sesion y el precio_total
				$ventas->cod_usuario = Sistema::app()->Acceso()->getNick();
				$ventas->importe_total = $cantidad_total;

				// Validamos el modelo VENTA por si se encuentra errores
				if ($ventas->validar() == false){
					$errores[] = $ventas;
				} // End if
				else{
					// Llamamos al metodo guardar e insertamos en la base de datos
					if ($ventas->guardar() == false){
						Sistema::app()->paginaError(404, "ERROR!! Ha ocurrido un error a la hora de insertar un venta en la base de datos");
						return;
					} // End if de la validacion

					// Obtenemos el ultimo ID de venta para realizar otro insert en VENTAS_PRODUCTOS
					$sentencia = "Select max(cod_ventas) as cod_ventas from ventas";
					$cod_venta = $ventas->ejecutarSentencia($sentencia);

					// Una vez ejecutado la sentencia SQL parseamos a INT el CODIGO DE VENTA
					$cod_venta = intval($cod_venta[0]["cod_ventas"]);

					// Recorremos el array_precio donde mostraremos los campos que se añadira al objeto VENTAS_PRODUCTOS
					foreach ($array_precio as $key => $value) {
						// Definimos las variables
						$ventas_productos = new Ventas_Productos();

						// Asignamos los valores a los atributos para completar el objeto VENTAS_PRODUCTOS
						$ventas_productos->cod_ventas = $cod_venta;
						$ventas_productos->cod_producto = $value[1];
						$ventas_productos->unidades = $value[2];
						$ventas_productos->importe_base = $value[0];
						$ventas_productos->importe_total = $cantidad_total;

						// Guardamos el objeto y lo insertamos en la base de datos
						$ventas_productos->guardar();
					} // End foreach

					// Reiniciamos la SESION["ARRAY_PRODUCTOS"] para que se guarde en sesion un array vacio
					$_SESSION["array_productos"] = [];

					// Redireccionamos a la pagina Ver_Compras donde mostraremos un resumen de la compra
					Sistema::app()->irAPagina(["cataproducto", "Ver_Compras"],["id" => $cod_venta]);
					return;
				} // End else 1
			} // End if de errores
			else{
				// Guardamos en sesion el nuevo array de productos porque ocurrio errores
				$_SESSION["array_productos"] = $nuevo_array_productos;	
			} // End else de errores

			// Llamamos a la vista FACTURAR donde mostraremos los erroes de los modelos
			$this->dibujaVista("facturar", ["productos" => $nuevo_array_productos, "cabecera" => $productos, "errores" => $errores], "Cesta Productos");
			exit;
		} // End if

		// Llamamos a la vista para ver lor productos de la cesta y realizar la compra
		$this->dibujaVista("facturar", ["productos" => $nuevo_array_productos, "cabecera" => $productos], "Cesta Productos");
		exit;
	} // End de la accion de Facturar

	/**
	 * Estamos definiendo la accion Ver compras que se encarga de mostrarnos un resumen de la compra realizada
	 */
	public function accionVer_Compras(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
			Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario
		
		// Definimos nuestras variables locales
		$ventas = new Ventas();
		$ventas_productos = new Ventas_Productos();

        // Obtenemos el id del modelo en especifico
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        }

        $filas = $ventas->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de esa Compra Realizada");
            return;
        } // End if de filas

		$datos = $ventas_productos->buscarTodos(["where" => "cod_ventas = '$id'"]);

		$this->dibujaVista("verCompras", ["datos" => $datos], "Ver Compras");
		exit;
	} // End de la accion de Compras

	/**
	 * Estamos definiendo la accion de Borrar Producto la cual se encarga de que cuando pulsemos en la imagen de la cruz roja de borrar elimina
	 * el producto de la cesta de la compra.
	 */
	public function accionBorrarProducto(){
		// Definimos nuestras variables locales
		$productos = new Producto();
		$id = 0;

		// Obtenemos el ID del producto
		if (isset($_GET["id"])){
			$id = intval($_GET["id"]);
		} // End if de la obtencion del ID

		// Validamos de que el producto a borrar exista
		if (!$productos->buscarPorId($id)){
			Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado el producto para eliminarlo");
            return;
		} // End if de la validacion del productos

		// Eliminamos los repetidos del array de los productos añadidos por el nombre del producto para que solo se añada un tipo de cada producto
		$filas = array_intersect_key($this->_array_productos, array_unique(array_column($this->_array_productos, "nombre")));

		// Recorremos el array de productos para eliminar el producto del array
		foreach ($filas as $key => $value) {

			// Comprobamos de que el cod_producto sea igual al id
			if ($value["cod_producto"] == $id) {
				unset($filas[$key]);
			} // End if de la validacion

		} // End del foreach

		// Guardamos el nuevo array en sesion
		$_SESSION["array_productos"] = $filas;	

		// Redireccionamos a la pagina de la cesta 
		Sistema::app()->irAPagina(["cataproducto", "Cesta"]);
		return;
	} // End de la accion de borrar Producto

} // End del controlador cataproducto