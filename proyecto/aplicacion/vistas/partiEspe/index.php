<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/admin.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Participantes_Espectaculos",
        "enlace" => array("partiEspe")
    ]
];

// Definimos el crud de participante espectaculo y el paginador
$crud_espe_parti = new CGrid($cabecera, $filas, ["class" => "table table-striped table-info"]);
$pagi = new CPager($paginador, []); 

// Dibujamos nuestro paginador y nuestro crud de Participantes Espectaculos
echo $pagi->dibujate()."<br>".PHP_EOL;
echo $crud_espe_parti->dibujate();