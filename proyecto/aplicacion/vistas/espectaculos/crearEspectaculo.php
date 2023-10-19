<?php

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Espectaculo",
        "enlace" => array("espectaculos")
    ],
    [
        "texto" => "Crear Espectaculo",
        "enlace" => ["espectaculos", "CrearEspectaculos"]
    ]
];

// Definimos nuestra caja de errores de validacion del modelo Espectaculo
echo CHTML::modeloErrorSumario($espectaculo)."<br>".PHP_EOL;

// Realizamos un formulario para crear un modelo de tipo Espectaculo con los campos en específico
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($espectaculo, "titulo", ["class" => "form-label"]);
        echo CHTML::modeloText($espectaculo, "titulo", ["class" => "form-control", "maxlength" => "60"]);
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

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Crear Especatculo", ["class" => "btn btn-primary", "name" => "crear_espectaculo"]);
        echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["cliente"]));
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();