<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/main.js",["defer"=>"defer"]);

// Definimos la barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Inicio",
        "enlace" => array("Inicial")
    ],
    [   
        "texto" => "Contenido Foro",
        "enlace" => Sistema::app()->generaURL(array("foro", "Foro_Cinema"))
    ],
    [   
        "texto" => "Crear Foro",
        "enlace" => Sistema::app()->generaURL(array("foro", "CrearTemaForo"))
    ]
];

// Mostraremos todos los errores correspondiente a la hora de crear un tema del Foro
echo CHTML::modeloErrorSumario($tema_foro)."<br>".PHP_EOL;

// Mostraremos un formulario donde el usuario podrá crear el tema del foro
echo CHTML::dibujaEtiqueta("article", ["id" => "foro"], null, false);

    echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);
        echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
            echo CHTML::modeloLabel($tema_foro, "nombre_categoria_foro", ["class" => "form-label"]);
            echo CHTML::modeloText($tema_foro, "nombre_categoria_foro", ["class" => "form-control"]);
        echo CHTML::dibujaEtiquetaCierre("div");

        echo CHTML::dibujaEtiqueta("div", ["class" => "col-8"], "", false);
            echo CHTML::campoBotonSubmit("Crear Tema Foro", ["class" => "btn btn-primary", "name" => "crear_tema"]);
            echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["foro", "Foro_Cinema"]));
        echo CHTML::dibujaEtiquetaCierre("div");
    echo CHTML::finalizarForm();
    
echo CHTML::dibujaEtiquetaCierre("article");