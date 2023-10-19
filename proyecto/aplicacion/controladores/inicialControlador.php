<?php

/**
 * Estamos definiendo la clase inicialControlador se seria nuestro controlador inicial cada vez que accedemos 
 * a la pagina principal de nuestra aplicacion
 */
class inicialControlador extends CControlador {

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
		$this->plantilla = "main";
		$this->accionDefecto = "Index";
	} // End del constructor

	/**
	 * Estamos definiendo la accion Inicial la cual se encarga de mostrar la vista principal de nuestra pagina web
	 * en la cual mostraremos los los espectaculos correspondientes a sus categorias mientras que no este borrado
	 */
	public function accionIndex(){
		// Definimos roles de nuestra pagina web
		Sistema::app()->ACL()->anadirRole("normal", [1 => true]);
		Sistema::app()->ACL()->anadirRole("administrador", [1 => true, 2 => true]);

		// Definimos el usuario admin que va a ser nuestro SuperAdministrador
		Sistema::app()->ACL()->anadirUsuario("Administrador", "admin", "admin", 2);

		// Llamamos a la vista para mostrar la pagina principal de nuestra pagina web
		$this->dibujaVista("index", [], "Cinema"); 
		exit;
	} // End de la accion index

	/**
	 * Estamos definiendo la accionUsuario la cual se encarga de comprobar si hay usuario registrado o no
	 */
	public function accionUsuario(){
		if ($_SERVER['REQUEST_METHOD'] == 'GET'){
			// Validamos si hay usuario registrado o no
			if (Sistema::app()->Acceso()->hayUsuario() == true){
				echo json_encode(true, JSON_PRETTY_PRINT);
				return;
			} // End if 
			else{
				echo json_encode(false, JSON_PRETTY_PRINT);
				return;
			} // End else
		} // End del SERVER[REQUEST_METHOD] GET
	} // End de la accion Usuario

	/**
	 * Estamos definiendo la accion Ver Compras el cual se encarga de obtener todas la informacion de la compra realizada por el cliente
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
        } // End if de la obtencion del ID

		// Comprobamos de que exista de haya compras realizadas por el cliente
        $filas = $ventas->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de esa Compra Realizada");
            return;
        } // End if de filas

		// Obtenemos los datos de Compras del Cliente
		$datos = $ventas_productos->buscarTodos(["where" => "cod_ventas = '$id'"]);

		// Llamamos a la vista Compras donde mostraremos las compras realizadas
		$this->dibujaVista("verCompras", ["datos" => $datos], "Ver Compras");
		exit;
	} // End de la accion de Compras

	/**
	 * Estamos definiendo la accion Compras el cual se encarga de obtener todas las compras realizadas por el cliente
	 */
	public function accionCompras(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
			Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

		// Definimos nuestras variables locales
		$ventas = new Ventas();
		$nombre = CGeneral::addSlashes(Sistema::app()->Acceso()->getNick());
        $datos_filtrados = ["fecha" => ""];
        $opciones = ["where" => "nick_cliente = '$nombre'"];

        // Hacemos el filtrado de pagina
       if (isset($_POST["filtrar"])){
        // Filtramos el nombre de Categoria
        if (isset($_POST["fecha"])) {
            if (!empty($_POST["fecha"])) {
                $fecha = CGeneral::fechaNormalAMysql($_POST["fecha"]);
                $opciones["where"] .= " and fecha >= '$fecha'";
            } // End if de cadena vacia
            $datos_filtrados["fecha"] = $_POST["fecha"];
        } // End if de existe nombre_categoria

        $_SESSION["datos_filtrados"] = $datos_filtrados;
    } // End del boton del filtrar

    // Comprobamos los datos de sesion
    if (isset($_SESSION["datos_filtrados"])){
        // Sobrecargamos el datos_filtrados
        $datos_filtrados = $_SESSION["datos_filtrados"];

        // Comprobamos de que no este en blanco
        if (isset($datos_filtrados["fecha"]) && $datos_filtrados["fecha"] != ""){
            $fecha = CGeneral::fechaNormalAMysql($datos_filtrados["fecha"]);
            $opciones["where"] .= " and fecha >= '$fecha'";
        } // End if 2
        else{
            $datos_filtrados = ["fecha" => ""];
        } // End else 2
    } // End if 1
    else{
        $datos_filtrados = ["fecha" => ""];
    } // End else 1

        // Estamos definiendo el funcionamiento del paginador de Compras
        $registros = intval($ventas->buscarTodosNRegistros($opciones));

        $tamPagina = 7;

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

        $opciones["order"] = "fecha desc";

		// Definimos nuestras sentencias
		$filas = $ventas->buscarTodos($opciones);

		// Comprobamos de que haya compras
		if ($filas){
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de descargar todos los productos comprados en esa compra
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/descargar.png"),
                    Sistema::app()->generaURL(
                        ["pdf", "Compra"],
                        ["id" => $filas[$key]["cod_ventas"]]
                    ),
                    ["title" => "Ver Venta ".$filas[$key]["cod_ventas"]]
                );

				// Cambiamos el formato de la fecha para que sea en formato DIA/MES/AÑO
				$filas[$key]["fecha"] = CGeneral::fechaMysqlANormal($filas[$key]["fecha"]);

                // Añadimos la columna operaciones a cada fila
                $filas[$key]["operaciones"] = $cadena;
            } // End foreach
        } // End if $filas

        // Definimos la cabecera de la tabla de datos
        $cabecera = [
            [
                "CAMPO" => "nick_cliente",
                "ETIQUETA" => "Nick Cliente"
            ],
            [
                "CAMPO" => "fecha",
                "ETIQUETA" => "Fecha de Compra"
            ],
            [
                "CAMPO" => "importe_total",
                "ETIQUETA" => "Importe Total"
            ],
            [
                "CAMPO" => "operaciones",
                "ETIQUETA" => "Acciones"
            ]
        ];

        // Definimos el paginador
        $opcPaginador = array(
            "URL" => Sistema::app()->generaURL(array("inicial", "Compras")),
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

		// Llamamos a la vista donde mostraremos las compras del cliente
		$this->dibujaVista("verDatosCompras", ["filas" => $filas, "cabecera" => $cabecera, "paginador" => $opcPaginador,
            "datos_filtrados" => $datos_filtrados], "Ver Datos Compras");
		exit;
	} // End de la accionCompras

	/**
	 * Estamos definiendo la accionEntradas el cual se encarga mostrar todas las entradas del cliente que haya comprado
	 */
	public function accionEntradas(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
			Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

		// Definimos nuestras variables locales
		$reservar_entradas = new Reservar();
		$nombre = CGeneral::addSlashes(Sistema::app()->Acceso()->getNick());
        $opciones = ["where" => "nick_cliente = '$nombre'"];

        // Hacemos el filtrado de pagina
        if (isset($_POST["filtrar"])){
            // Filtramos el nombre de Categoria
            if (isset($_POST["titulo_espe"])) {
                if (!empty($_POST["titulo_espe"])) {
                    $titulo = intval($_POST["titulo_espe"]);
                    $opciones["where"] .= " and cod_espectaculos = '$titulo'";
                } // End if de cadena vacia
                $datos_filtrados["titulo_espe"] = $_POST["titulo_espe"];
            } // End if de existe nombre_categoria

            $_SESSION["datos_filtrados"] = $datos_filtrados;
        } // End del boton del filtrar

        // Comprobamos los datos de sesion
        if (isset($_SESSION["datos_filtrados"])){
            // Sobrecargamos el datos_filtrados
            $datos_filtrados = $_SESSION["datos_filtrados"];

            // Comprobamos de que no este en blanco
            if (isset($datos_filtrados["titulo_espe"]) && $datos_filtrados["titulo_espe"] != ""){
                $cod = intval($datos_filtrados["titulo_espe"]);
                $opciones["where"] .= " and cod_espectaculos = '$cod'";
            } // End if 2
            else{
                $datos_filtrados = ["titulo_espe" => ""];
            } // End else 2
        } // End if 1
        else{
            $datos_filtrados = ["titulo_espe" => ""];
        } // End else 1

        // Estamos definiendo el funcionamiento del paginador de ver Entradas
        $registros = intval($reservar_entradas->buscarTodosNRegistros($opciones));

        $tamPagina = 7;

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

		// Definimos nuestras sentencias
		$filas = $reservar_entradas->buscarTodos($opciones);

		// Comprobamos de que tenga entradas
		if ($filas){
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de Descargar las entradas
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/descargar.png"),
                    Sistema::app()->generaURL(
                        ["pdf", "Entradas"],
                        ["id" => $filas[$key]["cod_reserva"]]
                    ),
                    ["title" => "Ver Entradas ".$filas[$key]["cod_reserva"]]
                );

				// Añadimos el signo del dinero al precio_total de la entradas
				$filas[$key]["precio_total"] = $filas[$key]["precio_total"]."€";

                // Añadimos la columna operaciones a cada fila
                $filas[$key]["operaciones"] = $cadena;
            } // End foreach
        } // End if $filas

        // Definimos la cabecera de la tabla de datos de entradas
        $cabecera = [
            [
                "CAMPO" => "nick_cliente",
                "ETIQUETA" => "Nick Cliente"
            ],
            [
                "CAMPO" => "num_asientos",
                "ETIQUETA" => "Numero Asiento"
            ],
            [
                "CAMPO" => "precio_total",
                "ETIQUETA" => "Precio Entrada"
            ],
            [
                "CAMPO" => "titulo",
                "ETIQUETA" => "Titulo Espectaculo"
            ],
            [
                "CAMPO" => "nombre_sala",
                "ETIQUETA" => "Sala"
            ],
            [
                "CAMPO" => "operaciones",
                "ETIQUETA" => "Acciones"
            ]
        ];

        // Definimos el paginador
        $opcPaginador = array(
            "URL" => Sistema::app()->generaURL(array("inicial", "Entradas")),
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

		// Llamamos a la vista para ver las entradas
		$this->dibujaVista("verEntradas", ["filas" => $filas, "cabecera" => $cabecera, "paginador" => $opcPaginador, "datos_filtrados" => $datos_filtrados], "Ver Entradas");
		exit;
	} // End de la accionEntradas

} // End del controlador Inicial
