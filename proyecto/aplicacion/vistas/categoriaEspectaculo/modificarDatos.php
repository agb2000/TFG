<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definiriamos la barra de ubicacion para Modificar Categoria Espectaculo
$this->barraubi = [
    [   
        "texto" => "Categoria Espetaculo",
        "enlace" => array("categoriaEspectaculo")
    ],
    [   
        "texto" => "Modificar Espetaculo",
        "enlace" => Sistema::app()->generaURL(array("categoriaEspectaculo", "Modificar"), ["id" => $_GET["id"]])
    ]
];

// Mostrariamos los errores a la hora de modificar un modelo
echo CHTML::modeloErrorSumario($cat_espe)."<br>".PHP_EOL;

// Dibujamos un formulario con los datos de ese modelo seleccionado y modificariamos aquellos campos
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6", "id" => "cat_es"], "", false);
        echo CHTML::modeloLabel($cat_espe, "nombre_categoria_espectaculo", ["class" => "form-label"]);
        echo CHTML::modeloText($cat_espe, "nombre_categoria_espectaculo", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-2"], "", false);
        echo CHTML::modeloLabel($cat_espe, "borrado", ["class" => "form-label"]);
        echo CHTML::dibujaEtiqueta("article");
            echo CHTML::modeloListaRadioButton($cat_espe, "borrado", ["false"]);
        echo CHTML::dibujaEtiquetaCierre("article");
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Actualizar", ["class" => "btn btn-primary", "name" => "actualizar"]);
        echo CHTML::link(CHTML::boton("Ver Categoria Espectaculo", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["categoriaEspectaculo", "Ver"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Categoria Espectaculo", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();