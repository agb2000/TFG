<?php

// Añadimos los js necesarios
$this->textoHead = CPager::requisitos().CHTML::scriptFichero("/javascript/admin.js",["defer"=>"defer"]);

// Definimos la barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Sala",
        "enlace" => array("sala")
    ],
];

// Definimos nuestro crud de sala y el paginador
$crud_sala = new CGrid($cabecera, $filas, ["class" => "table table-striped table-info"]);
$pagi = new CPager($paginador, []); 

// Dibujamos el crud de sala y el paginador
echo $pagi->dibujate()."<br>".PHP_EOL;
echo $crud_sala->dibujate();