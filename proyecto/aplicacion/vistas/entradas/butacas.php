<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/verespe.js",["defer"=>"defer"]);

echo "<br>".PHP_EOL;
echo CHTML::dibujaEtiqueta("div", ["class" => "error", "id" => "error"], null, false);

echo CHTML::dibujaEtiquetaCierre("div");
echo "<br>".PHP_EOL;

// Dibujamos el article donde mostraremos las butacas disponibles 
echo CHTML::dibujaEtiqueta("article", ["id" => "info_butacas"], null, false);

    // Mostraremos todas la butacas de la sala
    echo CHTML::dibujaEtiqueta("article", ["id" => "butacas"], null, false);
        for ($i=1; $i<= $capacidad_maxima; $i++) {  
            // Mostramos el numero de la butaca
            echo CHTML::dibujaEtiqueta("div", ["class" => "asientos"], $i, true);
        } // End for
    echo CHTML::dibujaEtiquetaCierre("article");

    // Aquí mostraremos en un texto la butaca seleccionada
    echo CHTML::dibujaEtiqueta("article", ["id" => "info_asiento"], null, false);
        echo CHTML::dibujaEtiqueta("h3", [], "INFORMACION DE ASIENTOS");
        echo CHTML::dibujaEtiqueta("div", ["id" => "entradas"], null, false);
        echo CHTML::dibujaEtiquetaCierre("div");
        echo CHTML::botonHtml("COMPRAR ENTRADAS", ["class" => "btn btn-danger", "id" => "comprar_entradas"]);
    echo CHTML::dibujaEtiquetaCierre("article");

echo CHTML::dibujaEtiquetaCierre("article");