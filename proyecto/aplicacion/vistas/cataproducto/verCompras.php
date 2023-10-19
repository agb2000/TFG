<?php

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Inicio",
        "enlace" => array("inicial")
    ],
    [
        "texto" => "Ver Compras",
        "enlace" => Sistema::app()->generaURL(["cataproducto", "Ver_Compras"], ["id" => $_GET["id"]])
    ]
];

// Dibujamos un div donde mostraremos un resumen de las compras realizadas
echo CHTML::dibujaEtiqueta("article", ["id" => "resumen"], null, false);

    // Mostraremoslos datos de la compra realizada
    foreach ($datos as $key => $value) {
        echo CHTML::dibujaEtiqueta("div", [], null, false);
            echo CHTML::dibujaEtiqueta("label", [], "<b>Nombre del Producto:</b> ".$value["nombre_producto"])."<br>".PHP_EOL;
            echo CHTML::dibujaEtiqueta("label", [], "<b>Número de Unidades: </b>".$value["unidades"])."<br>".PHP_EOL;
            echo CHTML::dibujaEtiqueta("label", [], "<b>Precio de la Unidad: </b>".$value["importe_base"])." €<br>".PHP_EOL;
            echo CHTML::dibujaEtiqueta("label", [], "<b>Precio de la Unidad Total: </b>".($value["importe_base"]) * $value["unidades"])." €<br>".PHP_EOL;
            echo CHTML::dibujaEtiqueta("label", [], "<b>Fecha de la Compra: </b>".CGeneral::fechaMysqlANormal($value["fecha"]))."<br>".PHP_EOL;
            echo CHTML::dibujaEtiqueta("label", [], "<b>Precio Total de la Compra: </b>".$value["importe_total"])." €<br>".PHP_EOL;
        echo CHTML::dibujaEtiquetaCierre("div");
    } // End foreach

echo CHTML::dibujaEtiquetaCierre("article");

// Definimos nuestro botones para volver al indice y para descargar el PDF
echo CHTML::dibujaEtiqueta("div", ["id" => "botones"], null, false);
    echo CHTML::link(CHTML::boton("Descargar en PDF", ["class" => "btn btn-primary"]), Sistema::app()->generaURL(["pdf", "Compra"], ["id" => $_GET["id"]]));
    echo CHTML::link(CHTML::boton("Volver al Inicio", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["inicial", "Index"]));
echo CHTML::dibujaEtiquetaCierre("div");       
