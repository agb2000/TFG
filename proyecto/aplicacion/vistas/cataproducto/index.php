<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/productos.js",["defer"=>"defer"]);

// Definimos un titulo donde mostraremos el texto de PRODUCTOS
echo CHTML::dibujaEtiqueta("h2", ["id" => "titulo"], "Productos", true);

// Dibujamos un article donde mostraremos los productos correspondientes
echo CHTML::dibujaEtiqueta("article", ["id" => "mostrar_productos"], null, false);

echo CHTML::dibujaEtiquetaCierre("article");

?>