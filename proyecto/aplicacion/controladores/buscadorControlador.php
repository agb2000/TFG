<?php

/**
 * Estamos definiendo el Controlador de Buscador
 */
class buscadorControlador extends CControlador{
    

    /**
     * Estamos definiendo el constructor para buscador donde realizaremos la compra de entradas
     * la informacion de los espectaculos y donde el usuario podra añadir y ver los comentarios
     * del espectaculo
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->accionDefecto = 'index';
        $this->plantilla = "verespe";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de realizar la busqueda de espectaculos 
     * en el buscador
     */
    public function accionIndex(){
        // Definimos nuestras variables locales
        $espectaculo = new Espectaculo();

        // Realizamos la busqueda de espectaculos que no esten borrados en nuestra bd
        if (isset($_POST["buscador"])){
            // Comprobamos de que el texto que nosotros escribimos en la base de datos no este vacio
            if ($_POST["buscador"] == ""){
                // Comprobamos de que haya espectaculos disponibles
                if (Espectaculo::dameEspectaculos() != false){
                    $this->dibujaVista("index", ["datos" => $espectaculo->buscarTodos(["where" => "borrado = 0"])], "Buscador");
                    exit;
                } // End if 3
            } // End if 2
            else{
                // Escapamos el textos que hemos introducido en el buscador
                $nombre = CGeneral::addSlashes(mb_strtolower($_POST["buscador"]));

                // Buscamos los espectaculos que coincidan con el texto que hemos introducido
                $filas = $espectaculo->buscarTodos(["where" => "lower(titulo) like '%$nombre%'"]);

                // Validamos que haya espectaculos
                if (!$filas){
                    $this->dibujaVista("index", [], "Buscador");
                    exit;
                } // End if de filas

                // Llamamos a la vista index de buscador
                $this->dibujaVista("index", ["datos" => $filas], "Buscador");
                exit;
            } // End else 2
        } // End if 1

        // Llamamos a la vista index de buscador
        $this->dibujaVista("index", [], "Buscador");
        exit;
    } // End de la accion Index

    /**
     * Estamos definiendo la accion Datos la cual se encarga de mostrarnos informacion del espectaculo disponible,
     * fecha disponibles, la sesiones disponibles y por ultimo un apartado para añadir comentarios
     */
    public function accionDatos(){
        // Definimos los modelos necesarios
        $espectaculo = new Espectaculo();
        $espe_sala = new Espe_Sala();
        $sesion = new Sesiones();
        $comentarios = new Comentarios();
        $actores = new Part_Espe();

        // Definimos nuestras variables locales
        $array_sesiones= [];
        $id = 0;

        // Obtenemos el id del Espectaculo
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // Obtencion del ID espectaculo

        // Comprobamos de que el espectaculo exista
        $filas = $espectaculo->buscarPorId($id);

        // En caso de que no se encuentre ese espectaculo nos redireccionaría a la pagina de error
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No hemos encontrado ese espectaculo");
            return;
        } // End if de filas

        // Vamos a obtener los esp_sala disponibles que hay con ese espectaculo
        $filas = $espe_sala->buscarTodos(["where" => "cod_espectaculos = '$id'"]);

        // Recorremos las filas con los espe_sala disponibles con ese cod_espectaculo
        foreach($filas as $key => $value){
            // Definimos nuestras variables locales
            $cod_espe_sala = intval($value["cod_espe_sala"]);

            // Buscamos las sesiones disponibles con ese cod_espe_sala y que no este borrado
            $datos = $sesion->buscarTodos(["where" => "cod_espe_sala = '$cod_espe_sala' and borrado = '0'", "order" => "fecha desc"]);

            // En caso de que haya sesiones lo añadiremos al array de sesiones
            if ($datos != false){
                $array_sesiones[] = $datos;
            } // End if
        } // End foreach

        // Estamos definiendo el funcionamiento del paginador de Comentarios de ese Espectaculo
        $registros = intval($comentarios->buscarTodosNRegistros(["where" => "cod_espectaculo = '$id' and borrado = '0'"]));

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
        
		// Buscamos todos los comentarios que se hayan escrito a ese espectaculo en específico
		$filas = $comentarios->buscarTodos(["where" => "cod_espectaculo = '$id' and borrado = '0'", 
            "limit" => $opciones["limit"]]);

        // Definimos el paginador de Comentarios del Espectaculos
        $opcPaginador = array(
            "URL" => Sistema::app()->generaURL(["buscador", "Datos"], ["id" => $id]),
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

        // Vamos a sacar todos los actores que participan en el espectaculo
        $datos_actores = $actores->buscarTodos(["where" => "cod_espectaculo = '$id' and borrado = '0' "]);

        // En caso de que haya datos guardaremos todos los nombres de los actores en una variables
        if ($datos_actores){
            // Definimos nuestra variables locales
            $nombre = null;

            // Recorremos el array de actores y sacamos el nombre
            foreach($datos_actores as $key => $value){
                $nombre .= $value["nombre_participante"]." ";
            } // End foreach

            // Llamamos a la vista donde enviaremos los datos
            $this->dibujaVista("informacion", ["datos" => $espectaculo, "sesiones" => $array_sesiones, 
                "paginador" => $opcPaginador, "comentarios" => $filas, "nombre" => $nombre], "Datos Espectaculos");
            exit;
        } // End if
        
        // Llamamos a la vista donde mostraremos las información del espectaculos las sesiones disponibles, el paginador de comentarios y todos los comentarios disponibles
        $this->dibujaVista("informacion", ["datos" => $espectaculo, "sesiones" => $array_sesiones, 
            "paginador" => $opcPaginador, "comentarios" => $filas], "Datos Espectaculos");
        exit;
    } // End de la accionDatos

} // End de la clase Controlador Buscador