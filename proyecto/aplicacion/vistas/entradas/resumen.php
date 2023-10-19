<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/verespe.js",["defer"=>"defer"]);

// Estamos haciendo el resumen de las compras de entradas con la informacion
echo CHTML::dibujaEtiqueta("article", ["id" => "resumen"], null, false);
    echo CHTML::dibujaEtiqueta("h1", [], "ENTRADAS COMPRADAS");
    echo CHTML::dibujaEtiqueta("h4", ["id" => "resumen_info"], null, true);
echo CHTML::dibujaEtiquetaCierre("article");

// Definimos nuestro botones para volver al indice y para descargar el PDF
echo CHTML::dibujaEtiqueta("div", ["id" => "botones"], null, false);
    echo CHTML::link(CHTML::boton("Descargar en PDF", ["class" => "btn btn-primary"]), "", ["id" => "pdf"]);
    echo CHTML::link(CHTML::boton("Volver al Inicio", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["inicial", "Index"]));
echo CHTML::dibujaEtiquetaCierre("div");     