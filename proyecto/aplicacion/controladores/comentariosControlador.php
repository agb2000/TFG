<?php

/**
 * Estamos definiendo el Controlador de comentarios
 */
class comentariosControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para  donde realizaremos el crear comentario en un espectaculo
     * y borrar comentario de un espectaculo
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->accionDefecto = 'index';
        $this->plantilla = "comentarios";
    } // End del constructor

    /**
     * Estamos definiendo la accion de Crear Comentario la cual se encarga de añadir un comentario de un cliente a un espectaculo
     */
    public function accionCrearComentarios(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

        // Definimos nuestras variables locales
        $comentario = new Comentarios();
        $espectaculo = new Espectaculo();
        $id = 0;

        // Obtenemos el Nombre del Modelo
        $nombre = $comentario->getNombre();

        // Obtenemos el id del espectaculo
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del id

        // Validamos de que exista el espectaculo
        $filas = $espectaculo->buscarPorId($id);

        // Comprobamos de que exista el espectaculo
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de ese espectaculo");
            return;
        } // End if de la validacion 

        // Llamamos al boton del formulario de crear comentario
        if (isset($_POST["crear_comentario"])){
            // Guardamos los valores
            $comentario->setValores($_POST[$nombre]);
            $comentario->fecha_publicacion = date_format(new DateTime($comentario->fecha_publicacion), "d/m/Y");
            $comentario->cod_espectaculo = $id;
            $comentario->cod_cliente = Sistema::app()->Acceso()->getNick();

            // Guardamos y validamos los valores para cada atributo del objeto Comentario
            if ($comentario->validar()){
                if ($comentario->guardar()){
                    Sistema::app()->irAPagina(["buscador", "Datos"], ["id" => $_GET["id"]]);
                    return;
                } // End if 2
                else{
                    $comentario->fecha_publicacion = CGeneral::fechaNormalAMysql($comentario->fecha_publicacion);
                    $this->dibujaVista("crearComentarios", ["comentario" => $comentario], "Crear Comentarios");
                    exit;
                } // End else 2
            } // End if 1 
            else{
                $comentario->fecha_publicacion = CGeneral::fechaNormalAMysql($comentario->fecha_publicacion);
                $this->dibujaVista("crearComentarios", ["comentario" => $comentario], "Crear Comentarios");
                exit;
            } // End else 1
        } // End del boton formulario
        
        // Llamamos a al vista de Crear Comentarios
        $this->dibujaVista("crearComentarios", ["comentario" => $comentario], "Crear Comentario");
        exit;
    } // En de la accion Crear Comentarios

    /**
     * Estamos definiendo la accion de Borrar Comentarios la cual se encarga de borrar un comentario de un espectaculo
     */
    public function accionBorrarComentarios(){
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
        $comentarios = new Comentarios();
        $espectaculo = new Espectaculo();
        $id = 0;
        $id_comentario = 0;

        // Comprobamos de que exista
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if 

        // Buscamos por id del espectaculo
        $filas = $espectaculo->buscarPorId($id);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de ese Espectaculo");
            return;
        }// End if de validacion

        // Comprobamos de que exista del id del comentario
        if (isset($_GET["cod_comentarios"])){
            $id_comentario = intval($_GET["cod_comentarios"]);
        } // End if del $_GET[cod_comentarios]

        // Buscamos por id del comentario
        $filas = $comentarios->buscarPorId($id_comentario);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de ese Comentario");
            return;
        }// End if

        // Realizamos la sentencia de borrado logico
        $sentencia = "update comentarios set borrado = true where cod_comentarios = '$id_comentario' and 
            cod_espectaculo = '$id'";

        // Comprobamos si se ha realizado
        if (!$comentarios->ejecutarSentencia($sentencia)) {
            Sistema::app()->paginaError(404, "ERROR!! Se ha producido un error en la base de datos");
            return;
        } // End if

        // Redireccionamos a la accion Consultar Categoria
        Sistema::app()->irAPagina(["buscador", "Datos"], ["id" => $id]);
        return;
    } // End de la accion Borrar Comentario

} // End de la clase Controlador comentarios