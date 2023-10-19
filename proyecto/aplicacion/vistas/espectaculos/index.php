<?php

// Añadimos los js necesarios
$this->textoHead = CPager::requisitos().CHTML::scriptFichero("/javascript/admin.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Espectaculos",
        "enlace" => array("espectaculos")
    ],
];

// Filtrado de datos
$filtrado  = CHTML::iniciarForm("","POST",array()).
    CHTML::campoLabel("Nombre_Categoria: ", "nombre_categoria").
    CHTML::campoListaDropDown("nombre_categoria", "", 
        Categoria_Espectaculo::dameCategorias_Espectaculo())."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".

    CHTML::campoLabel("Borrado", "borrado")."&nbsp;&nbsp;".
    CHTML::campoRadioButton("borrado", "", ["value" => 0]).
    CHTML::campoLabel("False", "borrado")."&nbsp;&nbsp;".
    CHTML::campoRadioButton("borrado", "", ["value" => 1]).
    CHTML::campoLabel("True", "borrado")."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".

    CHTML::campoBotonSubmit("Filtrar", ["name" => "filtrar", "class" => "btn btn-primary"]).
CHTML::finalizarForm();

// Definimos el crud de espectaculos y el paginador
$crud_espectaculo = new CGrid($cabecera, $filas, ["class" => "table table-striped table-info"]);
$pagi = new CPager($paginador, []); 
$cajaFiltrado = new CCaja("Criterios de filtrado (en caja)", $filtrado, ["class" => "caja"]);

// Dibujamos el crud de espectaculos y el paginador
echo $pagi->dibujate()."<br>".PHP_EOL;
echo $cajaFiltrado->dibujate()."<br>".PHP_EOL;
echo $crud_espectaculo->dibujate();