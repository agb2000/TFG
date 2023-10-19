<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Espe_Sala",
        "enlace" => array("espesala")
    ],
    [
        "texto" => "Ver Espe_Sala",
        "enlace" => Sistema::app()->generaURL(["espesala", "Ver"], ["id" => $_GET["id"]])
    ]
];

// Realizamos un formulario para mostrar todos los datos del modelo Espe_Sala en especifico
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-3"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
    echo CHTML::modeloLabel($espe_sala, "cod_espectaculos", ["class" => "form-label"]);
        if (Espectaculo::dameEspectaculos() != false)
        echo CHTML::modeloListaDropDown($espe_sala, "cod_espectaculos", Espectaculo::dameEspectaculos(), ["class" => "form-control", "disabled" => "disabled"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
    echo CHTML::modeloLabel($espe_sala, "cod_sala", ["class" => "form-label"]);
        if (Sala::dameSala() != false)
        echo CHTML::modeloListaDropDown($espe_sala, "cod_sala", Sala::dameSala(), ["class" => "form-control", "disabled" => "disabled"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::link(CHTML::boton("Volver Atras", ["class" => "btn btn-primary"]), Sistema::app()->generaURL(["espesala"]));
        echo CHTML::link(CHTML::boton("Modificar Espe_Sala", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["espesala", "Modificar"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Espe_Sala", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();