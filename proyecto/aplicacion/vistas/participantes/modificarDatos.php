<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion de Modificar Participantes
$this->barraubi = [
    [   
        "texto" => "Participante",
        "enlace" => array("participantes")
    ],
    [   
        "texto" => "Modificar Participante",
        "enlace" => Sistema::app()->generaURL(array("participantes", "Modificar"), ["id" => $_GET["id"]])
    ]
];

// Definimos nuestro error Sumario para que nos muestre los errores de modificacion de Participantes
echo CHTML::modeloErrorSumario($participante)."<br>".PHP_EOL;

// Dibujamos un div para mostrar el formulario de datos de Participantes
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6", "id" => "cat_es"], "", false);
        echo CHTML::modeloLabel($participante, "nombre_participante", ["class" => "form-label"]);
        echo CHTML::modeloText($participante, "nombre_participante", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-2"], null, false);
        echo CHTML::modeloLabel($participante, "borrado", ["class" => "form-label"]);
        echo CHTML::dibujaEtiqueta("article");
            echo CHTML::modeloListaRadioButton($participante, "borrado", ["false"]);
        echo CHTML::dibujaEtiquetaCierre("article");
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Actualizar", ["class" => "btn btn-primary", "name" => "actualizar"]);
        echo CHTML::link(CHTML::boton("Ver Participante", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["participantes", "Ver"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Particpante", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();