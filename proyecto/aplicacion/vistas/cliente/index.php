<?php

// Añadimos los js necesarios
$this->textoHead = CPager::requisitos().CHTML::scriptFichero("/javascript/admin.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Cliente",
        "enlace" => array("cliente")
    ],
];

// Filtrado de datos
$filtrado  = CHTML::iniciarForm("","POST",array()).
    CHTML::campoLabel("Nombre Rol: ", "rol").
    CHTML::campoListaDropDown("rol", "", Sistema::app()->ACL()->dameRoles()).
        "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".

    CHTML::campoLabel("Borrado", "borrado")."&nbsp;&nbsp;".
    CHTML::campoRadioButton("borrado", "", ["value" => 0]).
    CHTML::campoLabel("False", "borrado")."&nbsp;&nbsp;".
    CHTML::campoRadioButton("borrado", "", ["value" => 1]).
    CHTML::campoLabel("True", "borrado")."&nbsp;&nbsp;".

    CHTML::campoBotonSubmit("Filtrar", ["name" => "filtrar", "class" => "btn btn-primary"]).
CHTML::finalizarForm();

// Definimos nuestro crud de Cliente y nuestro paginador
$crud_cliente = new CGrid($cabecera, $filas, ["class" => "table table-striped table-info"]);
$pagi = new CPager($paginador, []); 
$cajaFiltrado = new CCaja("Criterios de filtrado (en caja)", $filtrado, ["class" => "caja"]);

// Dibujamos el paginador de Cliente y dibujamos el crud de Clientes
echo $pagi->dibujate()."<br>".PHP_EOL;
echo $cajaFiltrado->dibujate()."<br>".PHP_EOL;
echo $crud_cliente->dibujate();