<?php

/**
 * Estamos definiendo la clase CCaja el cual se encarga de heredar de la Clase CWidget,
 * y dibujariamos una caja con el contenido correspondiente
 */
class CCaja extends CWidget{

    // Definimos nuestras variables de instancia
    private string $_titulo;
    private string $_contenido;
    private array $_atributosHTML;

    // Definimos el constructor de CCaja
    public function __construct(string $titulo, string $contenido="", array $atributosHTML=[]){
        // Verificamos el titulo de la caja y asignamos el titulo de la caja
        if ($titulo == "")
            $this->_titulo = "Criterios de filtrado (en caja)";
        else
            $this->_titulo = $titulo;

        // Asignamos el contenido de la caja
        $this->_contenido = $contenido;

        // Verificamos los atributosHTML de la caja y asignamos el class
        $this->_atributosHTML = $atributosHTML;
        if (!isset($atributosHTML["class"]))
            $this->_atributosHTML["class"] = "caja";

    } // End del constructor
    
    public function dibujaApertura():string{
        ob_start();

        echo CHTML::dibujaEtiqueta("div", $this->_atributosHTML, "", false);
        echo CHTML::dibujaEtiqueta("div", ["class" => "titulo", "onclick" => "contenido()"], $this->_titulo, true);
        echo CHTML::dibujaEtiqueta("div", ["class" => "cuerpo"], $this->_contenido, true);

        $contenido = ob_get_contents();
        ob_end_clean();

        return $contenido;
    } // End de la clase dibujaApertura

    public function dibujaFin():string{
        return CHTML::dibujaEtiquetaCierre("div");
    }

    public function dibujate():string
    {
        return $this->dibujaApertura().$this->dibujaFin();
    }

    public static function requisitos():string{
        $script = <<<EOF
        function contenido(){
            let cuerpo = document.getElementsByClassName("cuerpo")[0];
        
            if (cuerpo.style.display == "block" || cuerpo.style.display == ""){
                cuerpo.style.display = "none";
            }
            else{
                cuerpo.style.display = "block"
            }
        }
        EOF;

        return CHTML::script($script);
    }

} // End de la clase CCaja