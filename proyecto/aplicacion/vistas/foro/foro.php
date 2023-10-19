<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/main.js",["defer"=>"defer"]);

// Definimos la barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Inicio",
        "enlace" => array("inicial")
    ],
    [   
        "texto" => "Foro de Cinema",
        "enlace" => Sistema::app()->generaURL(array("foro", "Foro_Cinema"))
    ]
];

// Definiremos la tabla para mostrar todos los temas del Foro Disponibles
$tabla_foro = new CGrid($cabecera, $filas, ["class" => "table table-danger"]);

// Definiremos un boton para añadir un tema para el foro y dibujaremos la tabla
echo CHTML::dibujaEtiqueta("article", ["id" => "foro"], null, false);
    // Comprobamos de que el usuario registrado tengan el rol de administrador
    if (Sistema::app()->Acceso()->puedePermiso(2)){
        echo CHTML::link(CHTML::boton("Añadir Tema Foro", ["class" => "btn btn-primary"]), Sistema::app()->generaURL(["foro", "CrearTemaForo"]));
    } // End if
    echo $tabla_foro->dibujate();
echo CHTML::dibujaEtiquetaCierre("article");