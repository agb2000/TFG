<?php

// Añadimos los js necesarios
$this->textoHead = CPager::requisitos().CHTML::scriptFichero("/javascript/tabla.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion 
$this->barraubi = [
    [   
        "texto" => "Ventas",
        "enlace" => array("venta")
    ],
];

// Llamamos al objeto CGRID para dibujar la tabla para el crud Venta y el CPager para realizar el paginador
$crud_venta = new CGrid($cabecera, $filas, ["class" => "table table-striped table-info"]);
$pagi = new CPager($paginador, []); 

// Mostramos por pantalla el paginador y el crud de la tabla
echo $pagi->dibujate()."<br>".PHP_EOL;
echo $crud_venta->dibujate();
