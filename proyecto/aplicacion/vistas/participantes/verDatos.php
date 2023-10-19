<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion 
$this->barraubi = [
    [   
        "texto" => "Participante",
        "enlace" => array("participantes")
    ],
    [   
        "texto" => "Ver Participante",
        "enlace" => Sistema::app()->generaURL(array("participantes", "Ver"), ["id" => $_GET["id"]])
    ]
];

// Dibujamos un div para mostrar el formulario de datos de Participante
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6", "id" => "cat_es"], "", false);
        echo CHTML::modeloLabel($participante, "nombre_participante", ["class" => "form-label"]);
        echo CHTML::modeloText($participante, "nombre_participante", ["class" => "form-control", "disabled" => true]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::link(CHTML::boton("Volver Atras", ["class" => "btn btn-primary"]), Sistema::app()->generaURL(["participantes"]));
        echo CHTML::link(CHTML::boton("Modificar Participante", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["participantes", "Modificar"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Participante", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();