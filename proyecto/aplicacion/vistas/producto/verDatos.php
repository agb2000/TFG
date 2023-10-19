<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion de Producto
$this->barraubi = [
    [   
        "texto" => "Producto",
        "enlace" => array("Producto")
    ],
    [   
        "texto" => "Ver Producto",
        "enlace" => Sistema::app()->generaURL(array("producto", "Ver"),["id" => $_GET["id"]])
    ]
];

// Mostramos los datos del modelo Producto en Especifico
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-3"]);
    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($producto, "nombre_producto", ["class" => "form-label"]);
        echo CHTML::modeloText($producto, "nombre_producto", ["class" => "form-control", "disabled" => "disabled"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($producto, "unidades", ["class" => "form-label"]);
        echo CHTML::modeloNumber($producto, "unidades", ["class" => "form-control", "disabled" => "disabled"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($producto, "precio", ["class" => "form-label"]);
        echo CHTML::modeloNumber($producto, "precio", ["class" => "form-control", "step" => "any", "disabled" => "disabled"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($producto, "fecha_alta", ["class" => "form-label"]);
        echo CHTML::modeloDate($producto, "fecha_alta", ["class" => "form-control", "disabled" => "disabled"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($producto, "imagen", ["class" => "form-label"]);
        echo CHTML::imagen($producto->Imagen, "Imagen del Producto", ["class" => "form-control", "disabled" => "disabled", "id" => "imagen"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($producto, "cod_categoria_producto", ["class" => "form-label"]);
        echo CHTML::modeloListaDropDown($producto, "cod_categoria_producto", Categoria_Producto::dameCategorias_Productos(), ["class" => "form-control", "disabled" => "disabled"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::link(CHTML::boton("Volver Atras", ["class" => "btn btn-primary"]), Sistema::app()->generaURL(["producto"]));
        echo CHTML::link(CHTML::boton("Modificar Producto", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["producto", "Modificar"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Producto", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");
    
echo CHTML::finalizarForm();