<?php

/**
 * Estamos definiendo la clase FORO Controlador
 */
class foroControlador extends CControlador{
    
	/**
     * Estamos definiendo el constructor para el FORO
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla principal
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->plantilla = "foro";
        $this->accionDefecto = "Foro_Cinema";
    } // End del constructor

    /**
     * Estamos definiendo la accionForo_Cinema la cual se encarga de mostrarnos todos los temas del foro disponibles
     */
    public function accionForo_Cinema(){
		// Definimos nuestras variables locales
		$foro = new Categoria_Foro();
		$filas = $foro->buscarTodos(["where" => "borrado = 0"]);

		// En caso de que hay filas mostraremos todos los datos
		if ($filas){
			foreach ($filas as $key => $value) {
            	// Estamos haciendo el boton de ver los Comentarios del Foro
			  	$cadena = CHTML::link(
					CHTML::imagen("/imagenes/24x24/ver.png"),
					Sistema::app()->generaURL(
						["foro", "VerContenido"],
						["id" => $filas[$key]["cod_categoria_foro"]]
					),
					["title" => "Tema ".$filas[$key]["cod_categoria_foro"]]
				);

				// Cambiamos el formato de la fecha para sea en DIA/MES/AÑO
				$filas[$key]["fecha_creacion"] = CGeneral::fechaMysqlANormal($filas[$key]["fecha_creacion"]);
				
				// Añadimos un enlace para el usuario pueda pulsar en el nombre del Tema del FORO
				$filas[$key]["nombre_categoria_foro"] = CHTML::link(
					$filas[$key]["nombre_categoria_foro"], 
					Sistema::app()->generaURL(["foro", "VerContenido"],
						["id" => $filas[$key]["cod_categoria_foro"]])
				);

				// Guardamos las operaciones
				$filas[$key]["operaciones"] = $cadena;
			} // End foreach
		} // End if

		// Definimos la cabecera de la tabla donde mostraremos la informacion del Tema del Foro
		$cabecera = [
			[
				"CAMPO" => "fecha_creacion",
				"ETIQUETA" => "Fecha Realización"
			],
			[
				"CAMPO" => "nombre_categoria_foro",
				"ETIQUETA" => "Tema de Foro"
			],
			[
				"CAMPO" => "contador_comentarios",
				"ETIQUETA" => "Contador de Comentarios"
			],
			[
				"CAMPO" => "operaciones",
				"ETIQUETA" => "Acciones"
			]
		];

		// Llamamos a la vista del foro para mostrar los resultados
		$this->dibujaVista("foro", ["cabecera" => $cabecera, "filas" => $filas], "Foro de Cinema");
		exit;
	} // End de la accion Foro_Cinema

	/**
	 * Estamos definiendo la accion Crear Tema Foro se encarga de la creacion de un tema para el foro
	 */
	public function accionCrearTemaForo(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

		// Definimos nuestras variables locales
		$tema_foro = new Categoria_Foro();
        $nombre = $tema_foro->getNombre();

        // Llamamos al boton del formulario de crear tema del foro
        if (isset($_POST["crear_tema"])){
            // Guardamos los valores
            $tema_foro->setValores($_POST[$nombre]);
			$tema_foro->fecha_creacion = date('Y-m-d');
            $tema_foro->fecha_creacion = CGeneral::fechaMysqlANormal($tema_foro->fecha_creacion);

            // Guardamos y validamos los valores para cada atributo del objeto CATEGORIA_FORO
            if ($tema_foro->validar()){
                if ($tema_foro->guardar()){
                    Sistema::app()->irAPagina(["foro", "Foro_Cinema"]);
                    return;
                } // End if 2
                else{
					$tema_foro->fecha_creacion = CGeneral::fechaNormalAMysql($tema_foro->fecha_creacion);
                    $this->dibujaVista("crearforo", ["tema_foro" => $tema_foro], "Foro de Cinema");
                    exit;
                } // End else 2
            } // End if 1 
            else{
				$tema_foro->fecha_creacion = CGeneral::fechaNormalAMysql($tema_foro->fecha_creacion);
                $this->dibujaVista("crearforo", ["tema_foro" => $tema_foro], "Foro de Cinema");
                exit;
            } // End else 1
        } // End del boton formulario

		// Llamamos al vista donde mostraremos el formulario para crear un tema del foro
		$this->dibujaVista("crearforo", ["tema_foro" => $tema_foro], "Foro de Cinema");
		exit;
	} // End de la accion de Crear el Tema

	/**
     * Estamos definiendo la accion de Ver todos los comentarios de un tema en específico
     */
	public function accionVerContenido(){
		// Definimos nuestras variables globales
		$contenido = new Contenido_Foro();
		$tema = new Categoria_Foro();
        $filas = [];
		$nombre = "";
		$id = 0;

		// Obtenemos el id del tema donde se va a escribir
		if (isset($_GET["id"])){
			$id = intval($_GET["id"]);
		} // End if existe $_GET["id"]

		// Validamos si existe el tema del foro
		if (!$tema->buscarPorId($id)){
			Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado ese tema");
            return;
		} // End if de que exista ese tema en específico

		// Buscamos el nombre de la categoria del foro a traves del ID
		if ($tema->buscarPorId($id)){
    		$nombre = $tema->nombre_categoria_foro;
		} // End if de la validacion

        // Estamos definiendo el funcionamiento del paginador de Categoria Espectaculo
        $registros = intval($contenido->buscarTodosNRegistros(["where" => "cod_categoria_foro = '$id' and borrado = 0"]));

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

		// Buscamos todos los comentarios realizados del tema en específico
		$filas = $contenido->buscarTodos(["where" => "cod_categoria_foro = '$id' and borrado = 0", "limit" => $opciones["limit"]]);

        // Definimos las opciones del paginador
		$opcPaginador = array(
            "URL" => Sistema::app()->generaURL(["foro", "VerContenido"], ["id" => $id]),
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

		// Llamamos a la vista
		$this->dibujaVista("contenidoforo", ["id" => $id, "comentarios" => $filas, "nombre" => $nombre, "paginador" => $opcPaginador], "Ver Contenido Foro");
		exit;
	} // End de la accion de Ver el contenido del foro

	/**
	 * Estamos definiendo la accion de Añadir Comentario a un tema en específico de un foro determinado
	 */
	public function accionAnadirComentarioForo(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

		// Definimos nuestras variables locales
		$comentario_foro = new Contenido_Foro();
		$nombre = $comentario_foro->getNombre();
		$tema = new Categoria_Foro();
		$id = 0;

		// Obtenemos el id del tema donde se va a escribir
		if (isset($_GET["id"])){
			$id = intval($_GET["id"]);
		} // End if existe $_GET["id"]

		// Validamos si existe el tema del foro
		if (!$tema->buscarPorId($id)){
			Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado ese tema del Foro");
            return;
		} // End if de que exista ese tema en específico

		// Llamamos al boton de crear comentario para el foro
		if (isset($_POST["crear_comentario"])){
            // Guardamos los valores
            $comentario_foro->setValores($_POST[$nombre]);
			$comentario_foro->cod_categoria_foro = $id;
			$comentario_foro->cod_cliente = Sistema::app()->Acceso()->getNick();
            $comentario_foro->fecha_publi = date('Y-m-d');
            $comentario_foro->fecha_publi = CGeneral::fechaMysqlANormal($comentario_foro->fecha_publi);

            // Guardamos y validamos los valores para cada atributo del objeto Contenido_Foro
            if ($comentario_foro->validar()){
                if ($comentario_foro->guardar()){
                    Sistema::app()->irAPagina(["foro", "VerContenido"], ["id" => $id]);
                    return;
                } // End if 2
                else{
					$comentario_foro->fecha_publi = CGeneral::fechaNormalAMysql($comentario_foro->fecha_publi);
                    $this->dibujaVista("comentarioForo", ["comentario" => $comentario_foro], "Foro de Cinema");
                    exit;
                } // End else 2
            } // End if 1 
            else{
				$comentario_foro->fecha_publi = CGeneral::fechaNormalAMysql($comentario_foro->fecha_publi);
                $this->dibujaVista("comentarioForo", ["comentario" => $comentario_foro], "Foro de Cinema");
                exit;
            } // End else 1
        } // End del boton formulario

        // Llamamos a la vista
		$this->dibujaVista("comentarioForo", ["comentario" => $comentario_foro], "Crear Comentario Foro");
		exit;
	} // End de la accion AnadirComentarioForo

    /**
     * Estamos definiendo la accion Borrar Contenido la cual se encarga de borrar un comentario de un foro en especifico
     */
	public function accionBorrarContenido(){
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
        $contenido = new Contenido_Foro();
        $cate_foro = new Categoria_Foro();
        $id = 0;
        $id_contenido = 0;

        // Comprobamos de que exista
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if 

        // Buscamos por id
        $filas = $cate_foro->buscarPorId($id);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de esa Categoria Foro");
            return;
        }// End if

        // Comprobamos de que exista
        if (isset($_GET["cod_contenido_foro"])){
            $id_contenido = intval($_GET["cod_contenido_foro"]);
        } // End if 

        // Buscamos por id
        $filas = $contenido->buscarPorId($id_contenido);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de ese Contenido_foro");
            return;
        }// End if

        // Realizamos la sentencia de borrado logico
        $sentencia = "update contenido_foro set borrado = true where cod_contenido_foro = '$id_contenido' and cod_categoria_foro = '$id'";

        // Comprobamos si se ha realizado
        if (!$contenido->ejecutarSentencia($sentencia)) {
            Sistema::app()->paginaError(404, "ERROR!! Se ha producido un error en la base de datos");
            return;
        } // End if

        $sentencia = "update categoria_foro set contador_comentarios = contador_comentarios - 1 where cod_categoria_foro = '$id'";
        $contenido->ejecutarSentencia($sentencia);

        // Redireccionamos a la accion Consultar Categoria
        Sistema::app()->irAPagina(["foro", "VerContenido"], ["id" => $id]);
        return;
    } // End de la accion BorrarContenido

} // End del clase Controlador Foro