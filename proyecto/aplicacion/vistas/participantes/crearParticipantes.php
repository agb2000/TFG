<?php

// Definimos nuestra barra de ubicacion de Crear Participantes
$this->barraubi = [
    [   
        "texto" => "Participante",
        "enlace" => array("participantes")
    ],
    [   
        "texto" => "Crear Participante",
        "enlace" => Sistema::app()->generaURL(array("participantes", "CrearParticipantes"))
    ]
];

// Hacemos nuestro modelo de error Sumario de Participantes
echo CHTML::modeloErrorSumario($participante)."<br>".PHP_EOL;

// Definimos nuestro formulario de Creacion de Participantes
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($participante, "nombre_participante", ["class" => "form-label"]);
        echo CHTML::modeloText($participante, "nombre_participante", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Crear Participante", ["class" => "btn btn-primary", "name" => "crear_participante"]);
        echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["participantes"]));
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();