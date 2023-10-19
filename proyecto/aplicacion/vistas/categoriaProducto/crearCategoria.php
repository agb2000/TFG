<?php

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Categoria",
        "enlace" => array("categoriaProducto")
    ],
    [
        "texto" => "CrearCategoria",
        "enlace" => ["categoriaProducto", "CrearCategoria"]
    ]
];

// Mostramos los errores del modelo de validacion
echo CHTML::modeloErrorSumario($categoria)."<br>".PHP_EOL;

// Realizamos un formulario para crear un modelo de Tipo Categoria Espectaculo
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($categoria, "nombre_categoria", ["class" => "form-label"]);
        echo CHTML::modeloText($categoria, "nombre_categoria", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($categoria, "descripcion_categoria", ["class" => "form-label"]);
        echo CHTML::modeloTextArea($categoria, "descripcion_categoria", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Crear Categoria", ["class" => "btn btn-primary mb-3", "name" => "crear_categoria"]);
        echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger mb-3"]), Sistema::app()->generaURL(["categoriaProducto"]));
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();