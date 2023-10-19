<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion 
$this->barraubi = [
    [   
        "texto" => "Sesiones",
        "enlace" => array("sesiones")
    ],
    [   
        "texto" => "Ver Sesiones",
        "enlace" => Sistema::app()->generaURL(array("sesiones", "Ver"), ["id" => $_GET["id"]])
    ]
];

// Dibujamos un formulario para mostrar datos de esa Sesion
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-3"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sesion, "hora_inicio", ["class" => "form-label"]);
        echo CHTML::modeloTime($sesion, "hora_inicio", ["class" => "form-control", "disabled" => true, "id" => "nombre_categoria"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sesion, "hora_fin", ["class" => "form-label"]);
        echo CHTML::modeloTime($sesion, "hora_fin", ["class" => "form-control", "disabled" => true, "id" => "nombre_categoria"]);
    echo CHTML::dibujaEtiquetaCierre("div");


    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sesion, "fecha", ["class" => "form-label"]);
        echo CHTML::modeloDate($sesion, "fecha", ["class" => "form-control", "disabled" => true]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::link(CHTML::boton("Volver Atras", ["class" => "btn btn-primary"]), Sistema::app()->generaURL(["sesiones"]));
        echo CHTML::link(CHTML::boton("Modificar Sesion", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["sesiones", "Modificar"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Sesion", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();