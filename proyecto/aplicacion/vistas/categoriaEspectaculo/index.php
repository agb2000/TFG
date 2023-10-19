<?php

// Añadimos los js necesarios
$this->textoHead = CPager::requisitos().CHTML::scriptFichero("/javascript/admin.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Categoria",
        "enlace" => array("categoriaEspectaculo")
    ],
];

// Llamamos al objeto CGRID para dibujar la tabla para el crud Categoria Espectaculos
$crud_categoria = new CGrid($cabecera, $filas, ["class" => "table table-striped table-info"]);
$pagi = new CPager($paginador, []); 

// Mostramos por pantalla el paginador y el crud de la tabla
echo $pagi->dibujate()."<br>".PHP_EOL;
echo $crud_categoria->dibujate();