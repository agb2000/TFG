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
        "texto" => "Modificar Participantes_Espectaculos",
        "enlace" => Sistema::app()->generaURL(["partiEspe", "Modificar"], ["id" => $_GET["id"]])
    ]
];

// Mostraremos los erroes de valicaciones del modelo en especifico
echo CHTML::modeloErrorSumario($part_espe)."<br>".PHP_EOL;

// Realizamos un formulario para mostrar todos los datos del modelo Participante Espectaculo en especifico
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($part_espe, "cod_participantes", ["class" => "form-label"]);
        if (Participantes::dameParticipantes() != false)
            echo CHTML::modeloListaDropDown($part_espe, "cod_participantes", Participantes::dameParticipantes(), ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($part_espe, "cod_espectaculo", ["class" => "form-label"]);
        if (Espectaculo::dameEspectaculos() != false)
            echo CHTML::modeloListaDropDown($part_espe, "cod_espectaculo", Espectaculo::dameEspectaculos(), ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-2"], "", false);
        echo CHTML::modeloLabel($part_espe, "borrado", ["class" => "form-label"]);
        echo CHTML::dibujaEtiqueta("article");
            echo CHTML::modeloListaRadioButton($part_espe, "borrado", ["false"]);
        echo CHTML::dibujaEtiquetaCierre("article");
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Actualizar", ["class" => "btn btn-primary", "name" => "actualizar"]);
        echo CHTML::link(CHTML::boton("Ver Parti_Espectaculo", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["partiEspe", "Ver"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::boton("Borrar Parti_Espectaculo", ["class" => "btn btn-danger"]), "", ["id" => "borrar"]);
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();