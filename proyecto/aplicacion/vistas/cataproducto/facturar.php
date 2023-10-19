<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js", ["defer" => "defer"]);

// Definimos nuestras variables locales
$precio_total = 0;

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [
        "texto" => "Inicio",
        "enlace" => array("inicial")
    ]
];

// Estamos mostrando los errores a la hora de realizar la validacion
if (isset($errores) && count($errores) != 0) {

    echo CHTML::dibujaEtiqueta("div", ["class" => "error"], null, false);
    // Recorremos el array de errores
    foreach ($errores as $value) {
        // Mostramos todos los errores que se hayan encontrado
        foreach ($value->getErrores() as $value) {
            echo $value . "<br>" . PHP_EOL;
        } // End foreach 2
    } // End foreach 1
    echo CHTML::dibujaEtiquetaCierre("div") . "<br>" . PHP_EOL;
} // End if de la validacion

// Dibujamos en una tabla todos los productos de la cesta y realizaremos la compra
echo CHTML::dibujaEtiqueta("article", [], null, false);

    echo CHTML::iniciarForm();

        // Mostramos la cabecera de la tabla productos
        echo CHTML::dibujaEtiqueta("table", ["class" => "table table-info table-striped", "id" => "tabla_compras"], "", false);
            echo CHTML::dibujaEtiqueta("tr");
                echo CHTML::dibujaEtiqueta("th", [], CHTML::modeloLabel($cabecera, "nombre"));
                echo CHTML::dibujaEtiqueta("th", [], CHTML::modeloLabel($cabecera, "cantidad"));
                echo CHTML::dibujaEtiqueta("th", [], CHTML::modeloLabel($cabecera, "precio"));
                echo CHTML::dibujaEtiqueta("th", [], CHTML::modeloLabel($cabecera, "imagen"));
                echo CHTML::dibujaEtiqueta("th", [], "Accion");
            echo CHTML::dibujaEtiquetaCierre("tr");

        // Mostramos los productos correspondientes en una celda
        foreach ($productos as $key => $value) {
            
            // Obtener el precio total de la compra realizada
            $precio_total += $value["precio"] * $value["cantidad"];

            // Mostramos los producto
            echo CHTML::dibujaEtiqueta("tr", [], "", false);
                echo CHTML::dibujaEtiqueta("td", [], $value["nombre"]); // Mostraremos el nombre del producto
                echo CHTML::dibujaEtiqueta("td", [], CHTML::campoNumber("unidad" . $value["cod_producto"], $value["cantidad"], ["min" => 0])); // Indicaremos la cantidad del producto
                echo CHTML::dibujaEtiqueta("td", [], $value["precio"]); // Obtenderemos el precio del producto
                echo CHTML::dibujaEtiqueta("td", [], CHTML::imagen($value["imagen"], "imagen", ["class" => "crudImagen"])); // Mostraremos la imagen del producto
                echo CHTML::dibujaEtiqueta("td", ["class" => "borrar_producto", "id" => $value["cod_producto"]], 
                CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png", ""), 
                    Sistema::app()->generaURL(["cataproducto", "BorrarProducto"],["id" => $value["cod_producto"]]))); // Haremos el boton de eliminar
            echo CHTML::dibujaEtiquetaCierre("tr");
        } // End foreach

        // Mostraremos un boton para pagar y mostraremos el precio total
            echo CHTML::dibujaEtiqueta("tr", [], "", false);
                echo CHTML::dibujaEtiqueta("td", ["colspan" => "5;"], CHTML::campoBotonSubmit("Pagar Productos", ["name" => "pagar", "class" => "btn btn-dark"]) .
                    CHTML::dibujaEtiqueta("label", ["class" => "total"], "Total A Pagar => " . $precio_total) . "€");
            echo CHTML::dibujaEtiquetaCierre("tr");

        echo CHTML::dibujaEtiquetaCierre("table");

    echo CHTML::finalizarForm();

echo CHTML::dibujaEtiquetaCierre("article");