<?php

/**
 * Estamos definiendo la clase CSesion el cual se encarga de inicializar o no la
 * variable superglobal $_SESION
 */
class CSesion{

    // Estamos definiendo el constructor
    function __construct(){} // End del constructor

    /**
     * Estamos definiendo la clase crearSesion, el cual se encarga de de crear de Incializar
     * la variable superglobal $_SESION
     */
    public function crearSesion(){
        if (!isset($_SESSION)){
            session_start();
        } // End if 
    } // End del metodo crearSesion

    /**
     * Estamos definiendo la clase haySesion, el cual comprueba si la sesion esta iniciada o no
     *
     * @return boolean ----> TRUE: La sesion esta iniciada
     *                 ----> FALSE: La sesion no esta iniciada
     */
    public function haySesion():bool{
        return isset($_SESSION);
    } // End del metodo haySesion

    /**
     * Estamos definiendo la clase destruirSesion, el cual se encargar de eliminar la sesion
     */
    public function destruirSesion(){
        session_destroy();
    } // End del metodo destruirSesion

} // End de la clase CSesion