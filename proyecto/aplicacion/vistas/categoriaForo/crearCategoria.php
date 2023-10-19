<?php

// Definimos la barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "CategoriaForo",
        "enlace" => array("categoriaForo")
    ],
    [
        "texto" => "Crear CategoriaForo",
        "enlace" => ["categoriaForo", "CrearCategoriaForo"]
    ]
];


// Mostraremos todos los errores correspondiente a la hora de crear un tema del Foro
echo CHTML::modeloErrorSumario($tema_foro)."<br>".PHP_EOL;

// Mostraremos un formulario donde el usuario podrá crear el tema del foro
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);
    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($tema_foro, "nombre_categoria_foro", ["class" => "form-label"]);
        echo CHTML::modeloText($tema_foro, "nombre_categoria_foro", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($tema_foro, "fecha_creacion", ["class" => "form-label"]);
        echo CHTML::modeloDate($tema_foro, "fecha_creacion",  ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Crear Categoria Foro", ["class" => "btn btn-primary", "name" => "crear_categoria_foro"]);
        echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["categoriaForo", "Index"]));
    echo CHTML::dibujaEtiquetaCierre("div");
echo CHTML::finalizarForm();
    