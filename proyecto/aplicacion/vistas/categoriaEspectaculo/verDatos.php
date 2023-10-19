<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definimos la barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Categoria Espectaculo",
        "enlace" => array("categoriaEspectaculo")
    ],
    [   
        "texto" => "Ver Categoria",
        "enlace" => Sistema::app()->generaURL(array("categoriaEspectaculo", "Ver"), ["id" => $_GET["id"]])
    ]
];

// Dibujamos un div para mostrar el formulario de datos de categoria Espectaculo
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6", "id" => "cat_es"], "", false);
        echo CHTML::modeloLabel($cat_espe, "nombre_categoria_espectaculo", ["class" => "form-label"]);
        echo CHTML::modeloText($cat_espe, "nombre_categoria_espectaculo", ["class" => "form-control", "disabled" => true]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::link(CHTML::boton("Volver Atras", ["class" => "btn btn-primary"]), Sistema::app()->generaURL(["categoriaEspectaculo"]));
        echo CHTML::link(CHTML::boton("Modificar Categoria Espectaculo", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["categoriaEspectaculo", "Modificar"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Categoria Espectaculo", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();