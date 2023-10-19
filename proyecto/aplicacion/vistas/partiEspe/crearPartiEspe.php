<?php

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Participantes_Especatculos",
        "enlace" => array("partiEspe")
    ],
    [
        "texto" => "Crear Participantes_Especatculos",
        "enlace" => ["partiEspe", "CrearParticipantes_Espectaculos"]
    ]
];

// Mostraremos los errores del modelo Participantes Espectaculo
echo CHTML::modeloErrorSumario($parti_espe)."<br>".PHP_EOL;

// Realizaremos un formulario con los campos a rellenar del modelo Participantes Espectaculos
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($parti_espe, "cod_participantes", ["class" => "form-label"]);
        if (Participantes::dameParticipantes() != false)
            echo CHTML::modeloListaDropDown($parti_espe, "cod_participantes", Participantes::dameParticipantes(), ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($parti_espe, "cod_espectaculo", ["class" => "form-label"]);
        if (Espectaculo::dameEspectaculos() != false)
            echo CHTML::modeloListaDropDown($parti_espe, "cod_espectaculo", Espectaculo::dameEspectaculos(), ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Crear Participante", ["class" => "btn btn-primary", "name" => "crear_parti_espe"]);
        echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["partiEspe"]));
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();