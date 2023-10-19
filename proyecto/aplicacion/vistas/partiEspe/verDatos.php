<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/borrar.js",["defer"=>"defer"]);

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Participantes_Espectaculos",
        "enlace" => array("partiEspe")
    ],
    [
        "texto" => "Ver Participantes_Espectaculos",
        "enlace" => Sistema::app()->generaURL(["partiEspe", "Ver"], ["id" => $_GET["id"]])
    ]
];

// Realizamos un formulario para mostrar todos los datos del modelo Participante Espectaculo en especifico
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($part_espe, "cod_participantes", ["class" => "form-label"]);
        if (Participantes::dameParticipantes() != false)
            echo CHTML::modeloListaDropDown($part_espe, "cod_participantes", Participantes::dameParticipantes(), ["class" => "form-control", "disabled" => "disabled"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($part_espe, "cod_espectaculo", ["class" => "form-label"]);
        if (Espectaculo::dameEspectaculos() != false)
            echo CHTML::modeloListaDropDown($part_espe, "cod_espectaculo", Espectaculo::dameEspectaculos(), ["class" => "form-control", "disabled" => "disabled"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::link(CHTML::boton("Volver Atras", ["class" => "btn btn-primary"]), Sistema::app()->generaURL(["partiEspe"]));
        echo CHTML::link(CHTML::boton("Modificar Parti_Espectaculo", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["partiEspe", "Modificar"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Parti_Espectaculo", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();