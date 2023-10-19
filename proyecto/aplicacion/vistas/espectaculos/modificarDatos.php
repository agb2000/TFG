<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Espectaculo",
        "enlace" => array("espectaculos")
    ],
    [
        "texto" => "Modificar Espectaculo",
        "enlace" => Sistema::app()->generaURL(["espectaculos", "Modificar"], ["id" => $_GET["id"]])
    ]
];

// Mostraremos los erroes de valicaciones del modelo en especifico
echo CHTML::modeloErrorSumario($espectaculo)."<br>".PHP_EOL;

// Realizamos un formulario para modificar todos los datos del modelo Espectaculo en especifico
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-3"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($espectaculo, "titulo", ["class" => "form-label"]);
        echo CHTML::modeloText($espectaculo, "titulo", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($espectaculo, "duracion", ["class" => "form-label"]);
        echo CHTML::modeloNumber($espectaculo, "duracion", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($espectaculo, "fecha_lanzamiento", ["class" => "form-label"]);
        echo CHTML::modeloDate($espectaculo, "fecha_lanzamiento", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($espectaculo, "fecha_finalizacion", ["class" => "form-label"]);
        echo CHTML::modeloDate($espectaculo, "fecha_finalizacion", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($espectaculo, "sinopsis", ["class" => "form-label"]);
        echo CHTML::modeloTextArea($espectaculo, "sinopsis", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($espectaculo, "imagen", ["class" => "form-label"]);
        echo CHTML::modeloText($espectaculo, "imagen", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($espectaculo, "cod_categoria_espectaculo", ["class" => "form-label"]);
        if (Categoria_Espectaculo::dameCategorias_Espectaculo())
            echo CHTML::modeloListaDropDown($espectaculo, "cod_categoria_espectaculo", Categoria_Espectaculo::dameCategorias_Espectaculo(), ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-5"], "", false);
        echo CHTML::modeloLabel($espectaculo, "borrado", ["class" => "form-label"]);
        echo CHTML::dibujaEtiqueta("article");
            echo CHTML::modeloListaRadioButton($espectaculo, "borrado", ["false"]);
        echo CHTML::dibujaEtiquetaCierre("article");
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Actualizar", ["class" => "btn btn-primary", "name" => "actualizar"]);
        echo CHTML::link(CHTML::boton("Ver Espectaculo", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["espectaculos", "Ver"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Espectaculo", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();