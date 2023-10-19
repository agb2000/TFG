<?php

// Añadimos los js necesarios
$this->textoHead = CPager::requisitos().CHTML::scriptFichero("/javascript/admin.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion de Principal de la pagina index
$this->barraubi = [
    [   
        "texto" => "Producto",
        "enlace" => array("producto")
    ],
];

// Filtrado de datos
$filtrado  = CHTML::iniciarForm("","POST",array()).
    CHTML::campoLabel("Nombre_Categoria: ", "nombre_categoria").
    CHTML::campoListaDropDown("nombre_categoria", "", Categoria_Producto::dameCategorias_Productos())."&nbsp;&nbsp;".

    CHTML::campoLabel("Borrado", "borrado")."&nbsp;&nbsp;".
    CHTML::campoRadioButton("borrado", "", ["value" => 0]).
    CHTML::campoLabel("False", "borrado")."&nbsp;&nbsp;".
    CHTML::campoRadioButton("borrado", "", ["value" => 1]).
    CHTML::campoLabel("True", "borrado")."&nbsp;&nbsp;".

    CHTML::campoBotonSubmit("Filtrar", ["name" => "filtrar", "class" => "btn btn-primary"]).
CHTML::finalizarForm();

// Definimos los objetos del crud de productos, paginador y filtrado
$crud_productos = new CGrid($cabecera, $filas, ["class" => "table table-striped table-info"]);
$pagi = new CPager($paginador, []); 
$cajaFiltrado = new CCaja("Criterios de filtrado (en caja)", $filtrado, ["class" => "caja"]);

// Mostramos el crud de productos y el paginador
echo $pagi->dibujate()."<br>".PHP_EOL;
echo $cajaFiltrado->dibujate()."<br>".PHP_EOL;
echo $crud_productos->dibujate();