<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definimos la barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "CategoriaForo",
        "enlace" => array("categoriaForo")
    ],
    [
        "texto" => "Modificar CategoriaForo",
        "enlace" => Sistema::app()->generaURL(array("categoriaForo", "Modificar"), ["id" => $_GET["id"]])
    ]
];

// Mostraremos todos los errores correspondiente a la hora de crear un tema del Foro
echo CHTML::modeloErrorSumario($tema_foro)."<br>".PHP_EOL;

// Mostraremos un formulario donde el usuario podrá crear el tema del foro
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);
    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($tema_foro, "nombre_categoria_foro", ["class" => "form-label"]);
        echo CHTML::modeloText($tema_foro, "nombre_categoria_foro", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($tema_foro, "fecha_creacion", ["class" => "form-label"]);
        echo CHTML::modeloDate($tema_foro, "fecha_creacion",  ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-2"], "", false);
        echo CHTML::modeloLabel($tema_foro, "borrado", ["class" => "form-label"]);
        echo CHTML::dibujaEtiqueta("article");
            echo CHTML::modeloListaRadioButton($tema_foro, "borrado", ["false"]);
        echo CHTML::dibujaEtiquetaCierre("article");
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Actualizar", ["class" => "btn btn-primary", "name" => "actualizar"]);
        echo CHTML::link(CHTML::boton("Ver Categoria Foro", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["categoriaForo", "Ver"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Categoria Foro", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");
    echo CHTML::finalizarForm();
    