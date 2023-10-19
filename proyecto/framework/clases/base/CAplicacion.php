<?php
class CAplicacion
{
	private $_controlDefecto = "inicial";
	private $_BD;
	private $_URL_AMIGABLES = false;
	private CSesion $_SESION;
	private CAcceso $_Acceso;
	private CACLBD $_ACL;

	private $_prop = array();

	public function __construct($config)
	{
		//se carga la configuracion

		//controlador inicial
		if (isset($config["CONTROLADOR"])) {
			if (is_array($config["CONTROLADOR"]))
				$this->_controlDefecto = $config["CONTROLADOR"][0];
			else
				$this->_controlDefecto = $config["CONTROLADOR"];
		}

		//rutas para incluir las clases
		if (isset($config["RUTAS_INCLUDE"])) {
			foreach ($config["RUTAS_INCLUDE"] as $ruta) {
				if (substr($ruta, 0, 1) != '/')
					$ruta = RUTA_BASE . "/" . $ruta;

				Sistema::nuevaRuta($ruta);
			}
		}


		//url amigables
		if (isset($config["URL_AMIGABLES"])) {
			$this->_URL_AMIGABLES = (bool)$config["URL_AMIGABLES"];
		}

		//variable disponibles en app
		if (isset($config["VARIABLES"])) {
			foreach ($config["VARIABLES"] as $clave => $valor) {
				$this->_prop[$clave] = $valor;
			}
		}

		if (isset($config["BD"]) && $config["BD"]["hay"] === true) {
			$this->_BD = new CBaseDatos(
				$config["BD"]["servidor"],
				$config["BD"]["usuario"],
				$config["BD"]["contra"],
				$config["BD"]["basedatos"]
			);
		}

		// Comprobamos si existe el config la variable "sesion"
		if (isset($config["SESION"])) {
			if (
				isset($config["SESION"]["controlAutomatico"])
				&& $config["SESION"]["controlAutomatico"] === true
			) {
				$this->_SESION = new CSesion();
				$this->_SESION->crearSesion();
			} // End if si exsite controlautomatico y que sea verdadero
		} // End if si existe la variable "sesion" en el config 

		// Creamos el objeto CAcceso siempre
		$this->_Acceso = new CAcceso();

		// Comprobamos si existe en el config la variable "ACL"
		if (isset($config["ACL"])) {
			if (
				isset($config["ACL"]["controlAutomatico"])
				&& $config["ACL"]["controlAutomatico"] === true && $config["BD"]["hay"] === true
			) {
				$this->_ACL = new CACLBD(
					$config["BD"]["servidor"],
					$config["BD"]["usuario"],
					$config["BD"]["contra"],
					$config["BD"]["basedatos"]
				);
			} // End if si exsite controlautomatico y que sea verdadero
		} // End if si existe la variable "ACL" en el config 


	} // End del constructor

	public function BD()
	{
		return $this->_BD;
	}

	public function SESION(): CSesion
	{
		return $this->_SESION;
	} // End del metodo sesion

	public function Acceso(): CAcceso
	{
		return $this->_Acceso;
	} // End del metodo Acceso

	public function ACL(): CACLBD
	{
		return $this->_ACL;
	}

	/**
	 * Estamos definiendo la funcion obtener_url, donde guardaremos la url de la pagina 
	 * donde llama a login.php. Lo guardaremos en sesion.
	 * @return void
	 */
	function obtener_url(): void
	{
		$url = "";

		$url = $_SERVER["REQUEST_URI"];

		$_SESSION["url"] = $url;
	} // End de la funcion de obtener_url

	public function analizaURL()
	{
		$ac = "";
		$co = "";

		if (isset($_REQUEST["co"]))
			$co = trim($_REQUEST["co"]);

		if (isset($_REQUEST["ac"]))
			$ac = trim($_REQUEST["ac"]);

		unset($_REQUEST["co"]);
		unset($_REQUEST["ac"]);
		unset($_GET["co"]);
		unset($_GET["ac"]);
		unset($_POST["co"]);
		unset($_POST["ac"]);

		$res = array();

		if ($co != "") {
			$res[] = $co;
			if ($ac != "") {
				$res[] = $ac;
			}
		}

		if ($res != array())
			return $res;
		else
			return null;
	}

	public function generaURL($accion, $parametros = null)
	{
		$ruta = "";

		if (is_array($accion)) {
			if (!$this->_URL_AMIGABLES) {
				if ($accion) {
					$ruta = "co=" . $accion[0];
					if (isset($accion[1]))
						$ruta .= "&ac=" . $accion[1];
				}

				if ($parametros) {

					if ($ruta != "")
						$ruta .= "&";
					$ruta .= $this->generaCadenaParametros($parametros);
				}
				$final = "/index.php";
				if ($ruta != "") {
					$final .= "?" . $ruta;
				}
			} else {
				if ($accion) {
					$ruta = "$accion[0]";
					if (isset($accion[1]))
						$ruta .= "/$accion[1]";
					$ruta .= "";
				}

				if ($parametros) {
					$aux = $this->generaCadenaParametros($parametros);
					$ruta .= "/$aux";
				}
				$final = "/";
				if ($ruta != "") {
					$final .= $ruta;
				}
			}
		} else {

			$final = "";

			if ($parametros) {
				$final = $this->generaCadenaParametros($parametros);
			}
			if (strpos($accion, "=") !== false) {
				$final = "&amp;$final";
			}

			if (strpos($accion, ".") !== false) {
				if (strpos($accion, "?") === false) {
					$accion .= "?";
				}
				$final = $accion . $final;
			} else {
				if (strpos($accion, "=") === false)
					$final = "/$final";
				$final = $accion . $final;
			}
		}
		return $final;
	}
	private function generaCadenaParametros($parametros)
	{
		$aux = "";
		foreach ($parametros as $clave => $valor) {
			if (is_array($valor)) {
				foreach ($valor as $c => $v) {
					if ($aux != "")
						$aux .= "&";
					$aux .= "{$clave}[$c]=$v";
				}
			} else {
				if ($aux != "")
					$aux .= "&";
				$aux .= "$clave=$valor";
			}
		}

		return $aux;
	}


	public function ejecutaControlador($accion)
	{
		$contro = $this->_controlDefecto;
		if ($accion) {
			$contro = $accion[0];
		}
		$contro = $contro . "Controlador";

		//compruebo si existe el controlador
		$ruta = RUTA_BASE . "/aplicacion/controladores/" . $contro . ".php";
		if (!file_exists($ruta)) {
			$this->paginaError(404, "Pagina no encontrada");
			exit;
		}

		if (!class_exists($contro, false))
			include($ruta);

		//creo una instancia para el controlador
		$contro = new $contro();

		$acc = $contro->accionDefecto;
		if (isset($accion[1]))
			$acc = $accion[1];

		if (!$contro->ejecutar($acc)) {
			$this->paginaError(404, "Pagina no encontrada");
			exit;
		}
	}


	public function irAPagina($accion, $parametros = null)
	{
		$url = $this->generaURL($accion, $parametros);

		header("location: " . $url);
	}

	public function ejecutar()
	{
		$accion = $this->analizaURL();
		if (!$accion) {
			$accion = array($this->_controlDefecto);
		}

		$this->ejecutaControlador($accion);
	}


	public function paginaError($numError, $mensaje = null)
	{
		$errores = array(
			404 => "Pagina no encontrada",
			400 => "Solicitud incorrecta"
		);


		$numError = intval($numError);

		if ($mensaje == null) {
			if (isset($errores[$numError]))
				$mensaje = $errores[$numError];
			else
				$mensaje = "Se ha producido un error.";
		}

		$ruta = RUTA_BASE . "/aplicacion/vistas/plantillas/error.php";

		if (file_exists($ruta))
			include($ruta);
		else {
			echo "Error $numError: $mensaje";
		}
		exit;
	}



	public function __set($nombre, $valor)
	{
		if (!isset($this->_prop[$nombre]))
			throw new Exception("La propiedad no es valida", 1);

		$this->_prop[$nombre] = $valor;
	}

	public function __get($nombre)
	{
		if (!isset($this->_prop[$nombre]))
			throw new Exception("La propiedad no es valida", 1);

		return ($this->_prop[$nombre]);
	}

	public function __isset($nombre)
	{
		return isset($this->_prop[$nombre]);
	}

	public function __unset($nombre)
	{
		if (isset($this->_prop[$nombre]))
			unset($this->_prop[$nombre]);
	}
}
