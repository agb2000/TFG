<?php

// Añadimos los js necesarios
$this->textoHead = CPager::requisitos();

// Definimos nuestra barra de ubicacion 
$this->barraubi = [
    [   
        "texto" => "Ver Compras",
        "enlace" => array("inicial", "Compras")
    ],
];

// Filtrado de datos
$filtrado  = CHTML::iniciarForm("","POST",array()).
    CHTML::campoLabel("Fecha: ", "fecha").
    CHTML::campoDate("fecha", $datos_filtrados["fecha"])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".

    CHTML::campoBotonSubmit("Filtrar", ["name" => "filtrar", "class" => "btn btn-primary"]).
CHTML::finalizarForm();


// Llamamos al objeto CGRID para dibujar la tabla para el crud Compras del Cliente
$crud_venta = new CGrid($cabecera, $filas, ["class" => "table table-striped table-info", "id" => "tabla"]);
$pagi = new CPager($paginador, []); 
$cajaFiltrado = new CCaja("Criterios de filtrado (en caja)", $filtrado, ["class" => "caja"]);

// Mostramos por pantalla el paginador y el crud de la tabla
echo $pagi->dibujate()."<br>".PHP_EOL;
echo $cajaFiltrado->dibujate()."<br>".PHP_EOL;
echo $crud_venta->dibujate();