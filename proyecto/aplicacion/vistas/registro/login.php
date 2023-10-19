<?php

// Definimos nuestras barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Inicio",
        "enlace" => array("inicial")
    ],
    [   
        "texto" => "Iniciar Sesion",
        "enlace" => Sistema::app()->generaURL(array("registro", "Login"))
    ]
];

// Definimos un contenedor donde mostraremos todos los errores a la hora de hacer el login
echo CHTML::modeloErrorSumario($login);

// Dibujamos nuestro article donde contendrá el formulario para iniciar sesion
echo CHTML::dibujaEtiqueta("article", ["id" => "login"], "", false);

    // Definimos el titulo
    echo CHTML::dibujaEtiqueta("h2", ["id" => "titulo"], "Iniciar Sesion");

    // Mostramos el formulario con los campos a rellenar para iniciar sesion
    echo CHTML::iniciarForm(atributosHTML:["class" => "row g-3"]);

        echo CHTML::dibujaEtiqueta("div", ["class" => "col-11"], "", false);
            echo CHTML::modeloLabel($login, "nick", ["class" => "form-label"]);
            echo CHTML::modeloText($login, "nick", ["class" => "form-control"]);
        echo CHTML::dibujaEtiquetaCierre("div");

        echo CHTML::dibujaEtiqueta("div", ["class" => "col-11"], "", false);
            echo CHTML::modeloLabel($login, "contrasenia", ["class" => "form-label"]);
            echo CHTML::modeloPassword($login, "contrasenia", ["class" => "form-control"]);
        echo CHTML::dibujaEtiquetaCierre("div");

        echo CHTML::dibujaEtiqueta("div", ["class" => "col-11"], "", false);
            echo CHTML::campoBotonSubmit("Iniciar Sesion", ["class" => "btn btn-success", "name" => "iniciar_sesion"]);
        echo CHTML::dibujaEtiquetaCierre("div");
    echo CHTML::finalizarForm();

    // Hacemos una pregunta de que si no esta registrada
    echo CHTML::dibujaEtiqueta("div", ["class" => "col-12"], "", false);
        echo CHTML::campoLabel("¿Estas Registrado?", "", [])."&nbsp;".CHTML::link("Registrate Ahora", Sistema::app()->generaURL(["registro", "Registrarse"]));
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("article");

