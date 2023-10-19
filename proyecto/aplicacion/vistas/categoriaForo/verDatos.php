<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definimos la barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "CategoriaForo",
        "enlace" => array("categoriaForo")
    ],
    [
        "texto" => "Ver CategoriaForo",
        "enlace" => Sistema::app()->generaURL(array("categoriaForo", "Ver"), ["id" => $_GET["id"]])
    ]
];

// Mostraremos un formulario donde el usuario podrá crear el tema del foro
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);
    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($tema_foro, "nombre_categoria_foro", ["class" => "form-label"]);
        echo CHTML::modeloText($tema_foro, "nombre_categoria_foro", ["class" => "form-control", "disabled" => "disabled"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($tema_foro, "fecha_creacion", ["class" => "form-label"]);
        echo CHTML::modeloDate($tema_foro, "fecha_creacion",  ["class" => "form-control", "disabled" => "disabled"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::link(CHTML::boton("Volver Atras", ["class" => "btn btn-primary"]), Sistema::app()->generaURL(["categoriaForo"]));
        echo CHTML::link(CHTML::boton("Modificar Categoria Foro", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["categoriaForo", "Modificar"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Categoria Foro", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");
echo CHTML::finalizarForm();