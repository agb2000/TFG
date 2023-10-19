<?php

// Añadimos los js necesarios
$this->textoHead = CPager::requisitos().CHTML::scriptFichero("/javascript/admin.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Sesiones",
        "enlace" => array("sesiones")
    ],
];

// Filtrado de datos
$filtrado  = CHTML::iniciarForm("","POST",array()).
    CHTML::campoLabel("Fecha: ", "fecha").
    CHTML::campoDate("fecha", "", [])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".

    CHTML::campoLabel("Borrado", "borrado")."&nbsp;&nbsp;".
    CHTML::campoRadioButton("borrado", "", ["value" => 0]).
    CHTML::campoLabel("False", "borrado")."&nbsp;&nbsp;".
    CHTML::campoRadioButton("borrado", "", ["value" => 1]).
    CHTML::campoLabel("True", "borrado")."&nbsp;&nbsp;".

    CHTML::campoBotonSubmit("Filtrar", ["name" => "filtrar", "class" => "btn btn-primary"]).
CHTML::finalizarForm();


// Llamamos a la clase CGrid y CPager para poder realizar el cru de Sesiones y su Paginador
$crud_sesion = new CGrid($cabecera, $filas, ["class" => "table table-striped table-info"]);
$pagi = new CPager($paginador, []); 
$cajaFiltrado = new CCaja("Criterios de filtrado (en caja)", $filtrado, ["class" => "caja"]);

// Dibujamos el paginador y el crud de Sesiones
echo $pagi->dibujate()."<br>".PHP_EOL;
echo $cajaFiltrado->dibujate()."<br>".PHP_EOL;
echo $crud_sesion->dibujate();