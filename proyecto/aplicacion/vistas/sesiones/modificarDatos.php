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
        "texto" => "Modificar Sesiones",
        "enlace" => Sistema::app()->generaURL(array("sesiones", "Modificar"), ["id" => $_GET["id"]])
    ]
];

// Mostraremos todos los errores de valicacion del modelo Sesion
echo CHTML::modeloErrorSumario($sesion)."<br>".PHP_EOL;

// Dibujamos un formulario para mostrar datos de esa Sesion
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-3"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sesion, "hora_inicio", ["class" => "form-label"]);
        echo CHTML::modeloTime($sesion, "hora_inicio", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sesion, "hora_fin", ["class" => "form-label"]);
        echo CHTML::modeloTime($sesion, "hora_fin", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");


    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sesion, "fecha", ["class" => "form-label"]);
        echo CHTML::modeloDate($sesion, "fecha", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-5"], "", false);
        echo CHTML::modeloLabel($sesion, "borrado", ["class" => "form-label"]);
        echo CHTML::dibujaEtiqueta("article");
            echo CHTML::modeloListaRadioButton($sesion, "borrado", ["false"]);
        echo CHTML::dibujaEtiquetaCierre("article");
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Actualizar", ["class" => "btn btn-primary", "name" => "actualizar"]);
        echo CHTML::link(CHTML::boton("Ver Sesion", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["sesiones", "Ver"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Sesion", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();