<?php

// Añadimos los js necesarios
$this->textoHead = CPager::requisitos().CHTML::scriptFichero("/javascript/main.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion 
$this->barraubi = [
    [   
        "texto" => "Ver Entradas",
        "enlace" => array("inicial", "Entradas")
    ],
];

// Filtrado de datos
$filtrado  = CHTML::iniciarForm("","POST",array()).
    CHTML::campoLabel("Espectaculos: ", "titulo_espe").
    CHTML::campoListaDropDown("titulo_espe", $datos_filtrados["titulo_espe"], 
        Espectaculo::dameEspectaculos())."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".

    CHTML::campoBotonSubmit("Filtrar", ["name" => "filtrar", "class" => "btn btn-primary"]).
CHTML::finalizarForm();

// Llamamos al objeto CGRID para dibujar la tabla para el crud las entradas
$crud_entradas = new CGrid($cabecera, $filas, ["class" => "table table-striped table-info", "id" => "tabla"]);
$pagi = new CPager($paginador, []); 
$cajaFiltrado = new CCaja("Criterios de filtrado (en caja)", $filtrado, ["class" => "caja"]);

// Mostramos por pantalla el paginador y el crud de la tabla de entradas
echo $pagi->dibujate()."<br>".PHP_EOL;
echo $cajaFiltrado->dibujate()."<br>".PHP_EOL;
echo $crud_entradas->dibujate();