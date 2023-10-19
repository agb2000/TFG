<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/admin.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion de Participantes
$this->barraubi = [
    [   
        "texto" => "Participantes",
        "enlace" => array("participantes")
    ],
];

// Definimos nuestro crud de Participantes y nuestro paginador
$crud_participantes = new CGrid($cabecera, $filas, ["class" => "table table-striped table-info"]);
$pagi = new CPager($paginador, []); 

// Dibujamos nuestro paginador y nuestro crud de participantes
echo $pagi->dibujate()."<br>".PHP_EOL;
echo $crud_participantes->dibujate();