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
        "texto" => "Modificar Sala",
        "enlace" => Sistema::app()->generaURL(array("sala", "Modificar"), ["id" => $_GET["id"]])
    ]
];

// Mostrariamos los errores a la hora de modificar un modelo
echo CHTML::modeloErrorSumario($sala)."<br>".PHP_EOL;

// Dibujamos un formulario con los datos de ese modelo seleccionado y modificariamos aquellos campos
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

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-2"], "", false);
        echo CHTML::modeloLabel($sala, "borrado", ["class" => "form-label"]);
        echo CHTML::dibujaEtiqueta("article");
            echo CHTML::modeloListaRadioButton($sala, "borrado", ["false"]);
        echo CHTML::dibujaEtiquetaCierre("article");
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Actualizar", ["class" => "btn btn-primary", "name" => "actualizar"]);
        echo CHTML::link(CHTML::boton("Ver Sala", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["sala", "Ver"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Sala", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();