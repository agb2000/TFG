<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion 
$this->barraubi = [
    [   
        "texto" => "Sala",
        "enlace" => array("sala")
    ],
    [   
        "texto" => "Ver Sala",
        "enlace" => Sistema::app()->generaURL(array("sala", "Ver"), ["id" => $_GET["id"]])
    ]
];

// Dibujamos un formulario para mostrar datos de esa Sala
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sala, "nombre_sala", ["class" => "form-label"]);
        echo CHTML::modeloText($sala, "nombre_sala", ["class" => "form-control", "disabled" => true, "id" => "nombre_categoria"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sala, "capacidad_maxima", ["class" => "form-label"]);
        echo CHTML::modeloNumber($sala, "capacidad_maxima", ["class" => "form-control", "disabled" => true]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sala, "precio_sala", ["class" => "form-label"]);
        echo CHTML::modeloNumber($sala, "precio_sala", ["class" => "form-control", "disabled" => true]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::link(CHTML::boton("Volver Atras", ["class" => "btn btn-primary"]), Sistema::app()->generaURL(["sala"]));
        echo CHTML::link(CHTML::boton("Modificar Sala", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["sala", "Modificar"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Sala", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();