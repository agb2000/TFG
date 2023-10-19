<?php

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Espe_Sala",
        "enlace" => array("espesala")
    ],
    [
        "texto" => "Crear Zona",
        "enlace" => ["espesala", "CrearEspe_Sala"]
    ]
];

// Definimos nuestra caja de errores de validacion del modelo Espectaculo Sala
echo CHTML::modeloErrorSumario($espe_sala,)."<br>".PHP_EOL;

// Realizamos un formulario para crear un modelo de tipo Espectaculo Sala con los campos en específico
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-3"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($espe_sala, "cod_espectaculos", ["class" => "form-label"]);
        if (Espectaculo::dameEspectaculos() != false)
            echo CHTML::modeloListaDropDown($espe_sala, "cod_espectaculos", Espectaculo::dameEspectaculos(), ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($espe_sala, "cod_sala", ["class" => "form-label"]);
        if (Sala::dameSala() != false)
            echo CHTML::modeloListaDropDown($espe_sala, "cod_sala", Sala::dameSala(), ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Crear Espectaculo Sala", ["class" => "btn btn-primary", "name" => "crear_espe_sala"]);
        echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["espesala"]));
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();