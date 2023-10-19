<?php

// Añadimos los js necesarios
$this->textoHead = CPager::requisitos().CHTML::scriptFichero("/javascript/admin.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Espe_Sala",
        "enlace" => array("espesala")
    ]
];

// Filtrado de datos
$filtrado  = CHTML::iniciarForm("","POST",array()).
    CHTML::campoLabel("Nombre Sala: ", "nombre_sala").
    CHTML::campoListaDropDown("nombre_sala", "", 
       Sala::dameSala())."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".

    CHTML::campoLabel("Borrado", "borrado")."&nbsp;&nbsp;".
    CHTML::campoRadioButton("borrado", "", ["value" => 0]).
    CHTML::campoLabel("False", "borrado")."&nbsp;&nbsp;".
    CHTML::campoRadioButton("borrado", "", ["value" => 1]).
    CHTML::campoLabel("True", "borrado")."&nbsp;&nbsp;".

    CHTML::campoBotonSubmit("Filtrar", ["name" => "filtrar", "class" => "btn btn-primary"]).
CHTML::finalizarForm();

// Definimos los objetos del Crud Espe_Sala y el paginador 
$crud_espe_sala = new CGrid($cabecera, $filas, ["class" => "table table-striped table-info"]);
$paginador = new CPager($paginador, []);
$cajaFiltrado = new CCaja("Criterios de filtrado (en caja)", $filtrado, ["class" => "caja"]);

// Debujamos el crud espe_Sala y el paginador
echo $paginador->dibujate()."<br>".PHP_EOL;
echo $cajaFiltrado->dibujate()."<br>".PHP_EOL;
echo $crud_espe_sala->dibujate();