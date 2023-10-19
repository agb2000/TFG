<?php

/**
 * Estamos definiendo el Controlador de Registro para el funcionamiento del Login y el registro de Usuarios
 */
class registroControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para Registrar/Iniciar Sesion.
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->plantilla = "registro";
    } // End del constructor

    /**
     * Estamos definiendo la accion Login la cual se encarga de realizar el Iniciar Sesion de nuestra aplicacion. Tiene una pequeña funcion
     * especial que es para comprobar temas de permisos o de copiar url comprobar de que exista el usuario se redireccionará a la url
     */
    public function accionLogin(){
        // Definimos nuestras variables locales
        $login = new Login();
        $nombre = $login->getNombre();

        // Llamamos al boton del formulario de Inciar Sesion
        if (isset($_POST["iniciar_sesion"])){
            // Guardamos los valores
            $login->setValores($_POST[$nombre]);

             // Guardamos y validamos los valores para cada atributo del objeto Login
            if ($login->validar()){
                // Comprobamos la llamada de la url de donde la sacamos y nos redireccionamos a esa pagina
                if (isset($_SESSION["url"])){
                    $url = explode("/", $_SESSION["url"]);
                    $cadena = explode("&", $url[3]);
                    $parametros = [];
                    $array_url = [];

                    foreach($url as $key => $value){
                        if ($value != "")
                            $array_url[] = $value; 
                    } // End foreach 1 

                    foreach($cadena as $key => $value){
                        if ($value != ""){
                            $aux = explode("=", $value);
                            $parametros[$aux[0]] = $aux[1];
                        }
                    } // End foreach 2

                    Sistema::app()->irAPagina($array_url, $parametros);
                    return;
                } // End if 1
                Sistema::app()->irAPagina(["inicial"]);
                return;
            } // End if 2
            else{
                $this->dibujaVista("login", ["login" => $login], "Iniciar Sesion");
                exit;
            } // End else 2
        } // End if del boton de Iniciar Sesion

        // Llamamos a la vista de Inciar Sesion
        $this->dibujaVista("login", ["login" => $login], "Iniciar Sesion");
        exit;
    } // End de la accion Login

    /**
     * Estamos definiendo la accion Registrar para registrar un usuario en nuestra aplicacion
     */
    public function accionRegistrarse(){
        // Definimos nuestras variables locales
        $cliente = new Cliente();
        $nombre = $cliente->getNombre();

        // Asignamos el role de tipo NORMAL para el usuario
        $cliente->nombre_role = Sistema::app()->ACL()->getCodRole("normal");

        // Llamamos al boton del formulario de Crear Usuario
        if (isset($_POST["crear_usuario"])){
            $cliente->setValores($_POST[$nombre]);

             // Guardamos y validamos los valores para cada atributo del objeto Cliente
            if ($cliente->validar()){
                if ($cliente->guardar()){
                    // Llamamos al Acceso para guardar el usuario registrado en Sesion
                    Sistema::app()->Acceso()->registrarUsuario($cliente->nick_cliente, 
                            Sistema::app()->ACL()->getNombre(Sistema::app()->ACL()->getCodUsuario($cliente->nick_cliente)), 
                            Sistema::app()->ACL()->getPermisos(Sistema::app()->ACL()->getCodUsuario($cliente->nick_cliente)));
                    Sistema::app()->irAPagina(["inicial"]);
                    return; 
                } // End if 2
                else{
                    $this->dibujaVista("registrar", ["usuario" => $cliente], "Registrarse");
                    exit;
                } // End else 2
            } // End if 1
            else{
                $this->dibujaVista("registrar", ["usuario" => $cliente], "Registrarse");
                exit;
            } // End else 2
        } // End del formulario de crear cliente

        // Llamamos a la vista de Registrar Usuario
        $this->dibujaVista("registrar", ["usuario" => $cliente], "Registrar Usuario");
        exit; 
    } // End de la accion Registrarse

    /**
     * Estamos definiendo la accion Cerrar la cual se encarga de quitar el usuario registrado de la sesion
     */
    public function accionCerrar(){
        Sistema::app()->Acceso()->quitarRegistroUsuario();
        session_destroy();
        Sistema::app()->irAPagina(["inicial"]);
        return;
    } // End de la accion de Cerrar Sesion

} // End de la clase registro Controlador