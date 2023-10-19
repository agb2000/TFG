<?php

// Definimos nuestra barra de ubicacion para Categoria Espectaculo
$this->barraubi = [
    [   
        "texto" => "Categoria Espectaculo",
        "enlace" => array("categoriaEspectaculo")
    ],
    [
        "texto" => "Crear Categoria Espectaculo",
        "enlace" => ["categoriaEspectaculo", "CrearCategoria"]
    ]
];

// Mostrariamos los errores a la hora de crear un modelo de Tipo Categoria Espectaculo, por si ocurre algun error
echo CHTML::modeloErrorSumario($cat_espe)."<br>".PHP_EOL;

// Dibujariamos un formulario con los campos necesarios para crear un modelo Categoria Espectaculo
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-3"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cat_espe, "nombre_categoria_espectaculo", ["class" => "form-label"]);
        echo CHTML::modeloText($cat_espe, "nombre_categoria_espectaculo", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Crear Categoria Espectaculo", ["class" => "btn btn-primary", "name" => "crear_cat_espe"]);
        echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["categoriaEspectaculo"]));
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();