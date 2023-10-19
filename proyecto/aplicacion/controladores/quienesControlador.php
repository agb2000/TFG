<?php

/**
 * Estamos definiendo el Controlador de Quienes Somos
 */
class quienesControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para mostrar en una pagina información de Quienes Somos
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla principal
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->plantilla = "quienes";
        $this->accionDefecto = "Index";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de mostrar en una pagina información de Quienes Somos
     */
    public function accionIndex(){
        $this->dibujaVista("index", [], "Quienes Somos");
    } // End de la accionIndes

} // End de la clase Quines Controlador