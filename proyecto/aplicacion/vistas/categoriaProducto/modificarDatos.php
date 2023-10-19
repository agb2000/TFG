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
        "texto" => "Modificar Categoria",
        "enlace" => Sistema::app()->generaURL(array("categoriaProducto", "Modificar"), ["id" => $_GET["id"]])
    ]
];

// Mostraremos los erroes de valicaciones del modelo en especifico
echo CHTML::modeloErrorSumario($categoria)."<br>".PHP_EOL;

// Dibujamos un formulario para mostrar datos de Categoria Producto en Especifico
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($categoria, "nombre_categoria", ["class" => "form-label"]);
        echo CHTML::modeloText($categoria, "nombre_categoria", ["class" => "form-control", "id" => "nombre_categoria"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($categoria, "descripcion_categoria", ["class" => "form-label"]);
        echo CHTML::modeloTextArea($categoria, "descripcion_categoria", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-2"], "", false);
        echo CHTML::modeloLabel($categoria, "borrado", ["class" => "form-label"]);
        echo CHTML::dibujaEtiqueta("article");
            echo CHTML::modeloListaRadioButton($categoria, "borrado", ["false"]);
        echo CHTML::dibujaEtiquetaCierre("article");
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Actualizar", ["class" => "btn btn-primary", "name" => "actualizar"]);
        echo CHTML::link(CHTML::boton("Ver Categoria", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["categoriaProducto", "Ver"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Categoria", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();
