<?php

/**
 * Estamos definiendo la clase CAcceso
 */
class CAcceso{

    // Variables de instancia
    private $_validado;
    private $_nick;
    private $_nombre;
    private $_permisos;
    private CSesion $_csesion;

    // Constructor
    public function __construct(){
        $this->_csesion = new CSesion();

        if (!$this->_csesion->haySesion()) {
            $this->_csesion->crearSesion();
        } // End if

        $this->_validado = false;
        $this->_nick = "";
        $this->_nombre = "";
        $this->_permisos = [];
        $this->recogerDeSesion();
    } // End del constructor

    /**
     * Funcion privada para guardar la información en la sesión
     *
     * @return boolean Devuelve true si se ha podido hacer. False en cualquier otro caso
     */
    private function escribirASesion(): bool{
        // Comprobamos si existe la varible SuperGlobal $_SESSION
        if (!isset($_SESSION))
            return false;

        // En caso de que sea valido, asignamos en la matriz unos valores especificos
        if ($this->_validado) {
            $_SESSION["acceso"]["validado"] = true; // $this->_validado
            $_SESSION["acceso"]["nick"] = $this->_nick;
            $_SESSION["acceso"]["nombre"] = $this->_nombre;
            $_SESSION["acceso"]["permisos"] = $this->_permisos;
        } // End if
        else {
            $_SESSION["acceso"]["validado"] = false; // $this->_validado
        } // End else
        return true;
    } // End del metodo privado escribirASession

    /**
     * Función privada que recoje la información de la sesión
     *
     * @return boolean Devuelve true si se ha podido recoger
     */
    private function recogerDeSesion(): bool{
        // Comprobamos si no existe $_SESSION, $_SESSION["ACCESO"], $_SESSION["ACCESO"]["VALIDADO"] == FALSE.
        if (
            !isset($_SESSION) || !isset($_SESSION["acceso"]) ||
            !isset($_SESSION["acceso"]["validado"]) || $_SESSION["acceso"]["validado"] == false
        ) {
            $this->_validado = false; // Establecemos el validado a false
        } // End if
        // En caso contrario
        else {
            $this->_validado = true;
            $this->_nick = $_SESSION["acceso"]["nick"];
            $this->_nombre = $_SESSION["acceso"]["nombre"];
            $this->_permisos = $_SESSION["acceso"]["permisos"];
        } // End else

        return true;
    } // End del metodo recogerDeSesion

    /**
     * Sirve para registrar un usuario en la aplicación. Almacena
     * los valores en las propiedades apropiadas y en la sesión 
     * para guardar en la sesión la información del usuario validado.
     *
     * @param string $nick nick del usuario a registrar
     * @param string $nombre nombre del usuario a registrar
     * @param array $permisos permisos del usuario a registrar
     * @return boolean Devuelve true si ha podido registrar el usuario
     */
    public function registrarUsuario(string $nick, string $nombre, array $permisos): bool{
        // Comprobamos si nick esta vacio o no
        if ($nick == "")
            $this->_validado = false;
        else
            $this->_validado = true;

        // Asignamos valores
        $this->_nick = $nick;
        $this->_nombre = $nombre;
        $this->_permisos = $permisos;

        // Escribimos en la sesion y comprobamos si no ha escrito o no
        if (!$this->escribirASesion())
            return false;

        // Devolvemos true
        return true;
    } // End del metodo registrarUsuario

    /**
     * Elimina la información de registro de un usuario
     *
     * @return boolean Devuelve true si ha podido hacerlo
     */
    public function quitarRegistroUsuario(): bool{
        // Asignamos de que validado sea false
        $this->_validado = false;

        // Comprobamos si a la hora de escribir en sesion se ha distinto de false
        if (!$this->escribirASesion())
            return false;

        // Devolvemos true
        return true;
    } // End del metodo quitarRegistroUsuario


    /**
     * Función que devuelve si hay o no un usuario registrado
     *
     * @return boolean Devuelve true si hay usuario registrado. False en caso contrario
     */
    public function hayUsuario(): bool{
        return $this->_validado;
    } // End del metodo hayUsuario


    /**
     * Función que devuelve si el usuario registrado tiene o no el permiso indicado
     *
     * @param integer $numero Numero de permiso a comprobar
     * @return bool Devuelve true si hay usuario registrado y tiene el permiso indicado
     */
    public function puedePermiso(int $numero): bool{
        // Comprobamos si hay un usuario registrado
        if (!$this->hayUsuario())
            return false;

        // Comprobamos si hay tiene el permiso establecido
        return $this->_permisos[$numero];
    } // End del metodo puedePermiso

    /**
     * Devuelve el nick del usuario indicado o false si no hay usuario
     *
     * @return string|false
     */
    public function getNick(): string|false{
        if (!$this->hayUsuario())
            return false;

        return $this->_nick;
    } // End metodo getNick

    /**
     * Devuelve el nombre del usuario registrado o false si no hay usuario
     *
     * @return string|false
     */
    public function getNombre(): string|false{
        if (!$this->hayUsuario())
            return false;

        return $this->_nombre;
    } // End del metodo getNombre
    
} // End de la clase Acceso
