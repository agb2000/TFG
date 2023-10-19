<?php

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Sala",
        "enlace" => array("sala")
    ],
    [
        "texto" => "Crear Sala",
        "enlace" => ["sala", "CrearSala"]
    ]
];

// Definimos nuestra caja de errores de validacion del modelo Sala
echo CHTML::modeloErrorSumario($sala)."<br>".PHP_EOL;

// Realizamos un formulario para crear un modelo de tipo Sala con los campos en específico
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sala, "nombre_sala", ["class" => "form-label"]);
        echo CHTML::modeloText($sala, "nombre_sala", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sala, "capacidad_maxima", ["class" => "form-label"]);
        echo CHTML::modeloNumber($sala, "capacidad_maxima", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sala, "precio_sala", ["class" => "form-label"]);
        echo CHTML::modeloNumber($sala, "precio_sala", ["class" => "form-control", "step" => "any"]);
    echo CHTML::dibujaEtiquetaCierre("div");


    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Crear Sala", ["class" => "btn btn-primary", "name" => "crear_sala"]);
        echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["sala"]));
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();