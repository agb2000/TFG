<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion 
$this->barraubi = [
    [   
        "texto" => "Categoria",
        "enlace" => array("categoriaProducto")
    ],
    [   
        "texto" => "Ver Categoria",
        "enlace" => Sistema::app()->generaURL(array("categoriaProducto", "Ver"), ["id" => $_GET["id"]])
    ]
];

// Dibujamos un formulario para mostrar datos de esa Categoria Producto
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($categoria, "nombre_categoria", ["class" => "form-label"]);
        echo CHTML::modeloText($categoria, "nombre_categoria", ["class" => "form-control", "disabled" => true, "id" => "nombre_categoria"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($categoria, "descripcion_categoria", ["class" => "form-label"]);
        echo CHTML::modeloTextArea($categoria, "descripcion_categoria", ["class" => "form-control", "disabled" => true]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::link(CHTML::boton("Volver Atras", ["class" => "btn btn-primary"]), Sistema::app()->generaURL(["categoriaProducto"]));
        echo CHTML::link(CHTML::boton("Modificar Categoria", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["categoriaProducto", "Modificar"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Categoria", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();