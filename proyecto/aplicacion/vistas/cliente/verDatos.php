<?php

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Cliente",
        "enlace" => array("cliente")
    ],
    [
        "texto" => "Ver Cliente",
        "enlace" => Sistema::app()->generaURL(["cliente", "Ver"], ["id" => $_GET["id"]])
    ]
];

// Mostraremos los datos del Cliente en un formulario 
echo CHTML::dibujaEtiqueta("form", ["class" => "row g-2"], "", false);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "nombre_cliente", ["class" => "form-label"]);
        echo CHTML::modeloText($cliente, "nombre_cliente", ["class" => "form-control", "maxlength" => "60", "disabled" => true]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "apellidos_cliente", ["class" => "form-label"]);
        echo CHTML::modeloText($cliente, "apellidos_cliente", ["class" => "form-control", "maxlength" => "60", "disabled" => true]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "nick_cliente", ["class" => "form-label"]);
        echo CHTML::modeloText($cliente, "nick_cliente", ["class" => "form-control", "maxlength" => "32", "disabled" => true]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "nif_cliente", ["class" => "form-label"]);
        echo CHTML::modeloText($cliente, "nif_cliente", ["class" => "form-control", "maxlength" => "9", "disabled" => true]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "poblacion", ["class" => "form-label"]);
        echo CHTML::modeloText($cliente, "poblacion", ["class" => "form-control", "maxlength" => "32", "disabled" => true]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "nombre_role", ["class" => "form-label"]);
        echo CHTML::modeloText($cliente, "nombre_role", ["class" => "form-control", "disabled" => true]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "fecha_nacimiento", ["class" => "form-label"]);
        echo CHTML::modeloDate($cliente, "fecha_nacimiento", ["class" => "form-control", "disabled" => true]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-primary"]), Sistema::app()->generaURL(["cliente"]));
        echo CHTML::link(CHTML::botonHtml("Modificar Cliente", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["cliente", "Modificar"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::botonHtml("Borrar Cliente", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["cliente", "Borrar"],["id" => $_GET["id"]]));
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("form");