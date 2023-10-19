<?php

// Realizamos la barra de Ubicacion de Crear Producto
$this->barraubi = [
    [   
        "texto" => "Producto",
        "enlace" => array("Producto")
    ],
    [   
        "texto" => "Crear Producto",
        "enlace" => Sistema::app()->generaURL(array("producto", "CrearProducto"))
    ]
];

// Mostramos los errores de la modificacion del producto
echo CHTML::modeloErrorSumario($producto)."<br>".PHP_EOL;

// Realizamos el formulario para crear un Producto
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-3"]);
    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($producto, "nombre_producto", ["class" => "form-label"]);
        echo CHTML::modeloText($producto, "nombre_producto", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($producto, "unidades", ["class" => "form-label"]);
        echo CHTML::modeloNumber($producto, "unidades", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($producto, "precio", ["class" => "form-label"]);
        echo CHTML::modeloNumber($producto, "precio", ["class" => "form-control", "step" => "any"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($producto, "fecha_alta", ["class" => "form-label"]);
        echo CHTML::modeloDate($producto, "fecha_alta", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($producto, "imagen", ["class" => "form-label"]);
        echo CHTML::modeloText($producto, "imagen", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($producto, "cod_categoria_producto", ["class" => "form-label"]);
        if (Categoria_Producto::dameCategorias_Productos() != false)
            echo CHTML::modeloListaDropDown($producto, "cod_categoria_producto", Categoria_Producto::dameCategorias_Productos(), ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Crear Producto", ["class" => "btn btn-primary", "name" => "crear_producto"]);
        echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["producto"]));
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();