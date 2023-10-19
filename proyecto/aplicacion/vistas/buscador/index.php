<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/verespe.js",["defer"=>"defer"]);

// Dibujamos un article donde mostraremos todos los espectaculos disponibles o que coincidan con el texto introducido
echo CHTML::dibujaEtiqueta("article", ["id" => "ver_espectaculos"], null, false);

    // Comprobamos de que haya espectaculos
    if (isset($datos)){
        // Recorremos los espectaculos
        foreach($datos as $key => $value){
            // Mostraremos todos los espectaculos disponibles en un card
            echo CHTML::dibujaEtiqueta("div", ["class" => "espe"], null, false);
            
                // Mostraremos la imagen
                echo CHTML::dibujaEtiqueta("div", ["class" => "imagen"], null, false);
                    echo CHTML::imagen($value["imagen"], "Imagen del Concierto", []);
                echo CHTML::dibujaEtiquetaCierre("div");
                
                // Mostraremos la informacion del espectaculo
                echo CHTML::dibujaEtiqueta("div", ["class" => "contenido"], null, false);
                    echo CHTML::dibujaEtiqueta("h2", [], $value["titulo"]);
                    echo CHTML::dibujaEtiqueta("hr");
                    echo CHTML::dibujaEtiqueta("p", [], "Disponible desde ".CGeneral::fechaMysqlANormal($value["fecha_lanzamiento"])." hasta ".CGeneral::fechaMysqlANormal($value["fecha_finalizacion"]));
                    echo CHTML::link(CHTML::botonHtml("INFORMACIÓN", ["class" => "btn btn-primary"]), Sistema::app()->generaURL(["buscador", "Datos"], ["id" => $value["cod_espectaculos"]]));
                echo CHTML::dibujaEtiquetaCierre("div");

            echo CHTML::dibujaEtiquetaCierre("div");
        } // End foreach
    } // End if 1
echo CHTML::dibujaEtiquetaCierre("article");