<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/mian.js",["defer"=>"defer"]);

// Definimos la barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Inicio",
        "enlace" => array("Inicial")
    ],
    [   
        "texto" => "Foro Cinema",
        "enlace" => Sistema::app()->generaURL(array("foro", "Foro_Cinema"))
    ],
    [   
        "texto" => "Contenido",
        "enlace" => Sistema::app()->generaURL(array("foro", "VerContenido"), ["id" => intval($_GET["id"])])
    ],
    [   
        "texto" => "Crear Comentario",
        "enlace" => Sistema::app()->generaURL(array("foro", "AnadirComentarioForo"), ["id" => intval($_GET["id"])])
    ]
];

// Mostraremos todos los errores correspondiente a la hora de crear un comentario del foro
echo CHTML::modeloErrorSumario($comentario)."<br>".PHP_EOL;

// Realizaremos un formulario con los campos correspondientes a la hora de crear un comentario del foro
echo CHTML::dibujaEtiqueta("article", ["id" => "foro"], null, false);
    echo CHTML::iniciarForm(atributosHTML:["class" => "row g-3"]);
        echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-12"], "", false);
            echo CHTML::modeloLabel($comentario, "contenido_foro", ["class" => "form-label"]);
            echo CHTML::modeloTextArea($comentario, "contenido_foro", ["class" => "form-control", "maxlength" => "60"]);
        echo CHTML::dibujaEtiquetaCierre("div");

        echo CHTML::dibujaEtiqueta("div", ["class" => "col-8"], "", false);
            echo CHTML::campoBotonSubmit("Crear Comentario", ["class" => "btn btn-primary", "name" => "crear_comentario"]);
            echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["foro", "VerContenido"], ["id" => intval($_GET["id"])]));
        echo CHTML::dibujaEtiquetaCierre("div");
    echo CHTML::finalizarForm();
echo CHTML::dibujaEtiquetaCierre("article");