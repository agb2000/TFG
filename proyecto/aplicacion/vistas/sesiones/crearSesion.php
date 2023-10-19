<?php

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Sesion",
        "enlace" => array("sesiones")
    ],
    [
        "texto" => "Crear Sesion",
        "enlace" => ["sesiones", "CrearSesiones"]
    ]
];

// Mostraremos los errores de validacion del modelo Sesion cuando creamos el modelo
echo CHTML::modeloErrorSumario($sesion)."<br>".PHP_EOL;

// Realizamos un formulario para crear un modelo de tipo Sesion con los campos en específico
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-3"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sesion, "cod_espe_sala", ["class" => "form-label"]);
        // Validamos para mostrar que haya espectaculos añadidos a una Sala
        if (Espe_Sala::dameEspe_Sala() != false)
            echo CHTML::modeloListaDropDown($sesion, "cod_espe_sala", Espe_Sala::dameEspe_Sala(), ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sesion, "hora_inicio", ["class" => "form-label"]);
        echo CHTML::modeloTime($sesion, "hora_inicio", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sesion, "hora_fin", ["class" => "form-label"]);
        echo CHTML::modeloTime($sesion, "hora_fin", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($sesion, "fecha", ["class" => "form-label"]);
        echo CHTML::modeloDate($sesion, "fecha", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Crear Sesion", ["class" => "btn btn-primary", "name" => "crear_sesion"]);
        echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["sesiones"]));
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();