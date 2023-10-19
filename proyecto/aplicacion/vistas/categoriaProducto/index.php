<?php

// Añadimos los js necesarios
$this->textoHead = CPager::requisitos().CHTML::scriptFichero("/javascript/admin.js",["defer"=>"defer"]);

// Definimos nuestro array de ubicacion
$this->barraubi = [
    [   
        "texto" => "Categoria",
        "enlace" => array("categoriaProducto")
    ],
];

// Creamos nuestro objeto table y paginador para el modelo CategoriaProducto
$crud_categoria_producto = new CGrid($cabecera, $filas, ["class" => "table table-striped table-info"]);
$pagi = new CPager($paginador, []); 

// Mostramos el crud de CategoriaProducto y el paginador
echo $pagi->dibujate()."<br>".PHP_EOL;
echo $crud_categoria_producto->dibujate();