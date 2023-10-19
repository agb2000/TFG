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
        "texto" => "Modificar Espe_Sala",
        "enlace" => Sistema::app()->generaURL(["espesala", "Modificar"], ["id" => $_GET["id"]])
    ]
];

// Mostraremos los erroes de valicaciones del modelo en especifico
echo CHTML::modeloErrorSumario($espe_sala)."<br>".PHP_EOL;

// Realizamos un formulario para mostrar todos los datos del modelo Espectaculos Sala en especifico
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

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-2"], "", false);
        echo CHTML::modeloLabel($espe_sala, "borrado", ["class" => "form-label"]);
        echo CHTML::dibujaEtiqueta("article");
            echo CHTML::modeloListaRadioButton($espe_sala, "borrado", ["false"]);
        echo CHTML::dibujaEtiquetaCierre("article");
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Actualizar", ["class" => "btn btn-primary", "name" => "actualizar"]);
        echo CHTML::link(CHTML::boton("Ver Espe_Sala", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["Espe_Sala", "Ver"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Espe_Sala", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();