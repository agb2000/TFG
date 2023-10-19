<?php

/**
 * Estamos definiendo la clase Admin Controlador se encargar de mostrar la ADMINISTRACION DE DATOS
 */
class adminControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para la ADMINSITRACION DE DATOS
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->accionDefecto = 'index';
        $this->plantilla = "dashboard";
    } // End del constructor

    /**
     * Estamos definiendo la accionIndex la cual se encarga de redireccionarnos siempre al crud de Categorias Productos
     * cada vez que vamos a la seccion principal de admin
     */
    public function accionIndex(){
        // Redireccionamos a la pagina CategoriaProducto
        Sistema::app()->irAPagina(["categoriaProducto", "Index"]);
        exit; 
    } // End de la accion Index

} // End de la clase ADMINCONTROLADOR