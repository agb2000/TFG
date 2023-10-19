<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Producto",
        "enlace" => array("Producto")
    ],
    [   
        "texto" => "Modificar Producto",
        "enlace" => Sistema::app()->generaURL(array("producto", "Modificar"), ["id" => $_GET["id"]])
    ]
];

// Mostraremos los errores del modelo Productos
echo CHTML::modeloErrorSumario($producto)."<br>".PHP_EOL;

// Realizaremos un formulario para modificar los datos de un producto en especifico
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);
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
        echo CHTML::modeloListaDropDown($producto, "cod_categoria_producto", Categoria_Producto::dameCategorias_Productos(), ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-2"], "", false);
        echo CHTML::modeloLabel($producto, "borrado", ["class" => "form-label"]);
        echo CHTML::dibujaEtiqueta("article");
            echo CHTML::modeloListaRadioButton($producto, "borrado", ["false"]);
        echo CHTML::dibujaEtiquetaCierre("article");
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Actualizar", ["class" => "btn btn-primary", "name" => "actualizar"]);
        echo CHTML::link(CHTML::boton("Ver Producto", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["producto", "Ver"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Producto", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();