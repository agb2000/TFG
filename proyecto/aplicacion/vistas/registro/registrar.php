<?php

// Definimos nuestras barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Inicio",
        "enlace" => array("inicial")
    ],
    [   
        "texto" => "Registrarse",
        "enlace" => Sistema::app()->generaURL(array("registro", "Registrarse"))
    ]
];

// Definimos un contenedor donde mostraremos todos los errores a la hora de hacer el Registrarse
echo CHTML::modeloErrorSumario($usuario)."<br>".PHP_EOL;

// Dibujamos nuestro article donde contendrá el formulario para registrarse
echo CHTML::dibujaEtiqueta("article", ["id" => "registrar"], null, false);

    // Definimos el titulo
    echo CHTML::dibujaEtiqueta("h2", ["id" => "titulo"], "Registrar Usuario");

    // Mostramos el formulario con los campos a rellenar para registrarse
    echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

        echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-5"], "", false);
            echo CHTML::modeloLabel($usuario, "nombre_cliente", ["class" => "form-label"]);
            echo CHTML::modeloText($usuario, "nombre_cliente", ["class" => "form-control", "maxlength" => "60"]);
        echo CHTML::dibujaEtiquetaCierre("div");

        echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-5"], "", false);
            echo CHTML::modeloLabel($usuario, "apellidos_cliente", ["class" => "form-label"]);
            echo CHTML::modeloText($usuario, "apellidos_cliente", ["class" => "form-control", "maxlength" => "60"]);
        echo CHTML::dibujaEtiquetaCierre("div");

        echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-5"], "", false);
            echo CHTML::modeloLabel($usuario, "nick_cliente", ["class" => "form-label"]);
            echo CHTML::modeloText($usuario, "nick_cliente", ["class" => "form-control", "maxlength" => "32"]);
        echo CHTML::dibujaEtiquetaCierre("div");

        echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-5"], "", false);
            echo CHTML::modeloLabel($usuario, "nif_cliente", ["class" => "form-label"]);
            echo CHTML::modeloText($usuario, "nif_cliente", ["class" => "form-control", "maxlength" => "9"]);
        echo CHTML::dibujaEtiquetaCierre("div");

        echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-5"], "", false);
            echo CHTML::modeloLabel($usuario, "poblacion", ["class" => "form-label"]);
            echo CHTML::modeloText($usuario, "poblacion", ["class" => "form-control", "maxlength" => "32"]);
        echo CHTML::dibujaEtiquetaCierre("div");

        echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-5"], "", false);
            echo CHTML::modeloLabel($usuario, "fecha_nacimiento", ["class" => "form-label"]);
            echo CHTML::modeloDate($usuario, "fecha_nacimiento", ["class" => "form-control"]);
        echo CHTML::dibujaEtiquetaCierre("div");

        echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-5"], "", false);
            echo CHTML::modeloLabel($usuario, "contrasenia", ["class" => "form-label"]);
            echo CHTML::modeloPassword($usuario, "contrasenia", ["class" => "form-control", "maxlength" => "32"]);
        echo CHTML::dibujaEtiquetaCierre("div");

        echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
            echo CHTML::campoBotonSubmit("Crear Usuario", ["class" => "btn btn-primary", "name" => "crear_usuario"]);
            echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["inicial"]));
        echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::finalizarForm();

echo CHTML::dibujaEtiquetaCierre("article");