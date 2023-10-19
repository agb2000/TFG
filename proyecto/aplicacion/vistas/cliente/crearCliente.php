<?php

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Cliente",
        "enlace" => array("cliente")
    ],
    [
        "texto" => "Crear Cliente",
        "enlace" => ["cliente", "CrearCliente"]
    ]
];

// Mostramos los errores del modelo de validacion
echo CHTML::modeloErrorSumario($cliente)."<br>".PHP_EOL;

// Realizamos un formulario para crear un modelo de Tipo Cliente
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-2"]);

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "nombre_cliente", ["class" => "form-label"]);
        echo CHTML::modeloText($cliente, "nombre_cliente", ["class" => "form-control", "maxlength" => "60"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "apellidos_cliente", ["class" => "form-label"]);
        echo CHTML::modeloText($cliente, "apellidos_cliente", ["class" => "form-control", "maxlength" => "60"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "nick_cliente", ["class" => "form-label"]);
        echo CHTML::modeloText($cliente, "nick_cliente", ["class" => "form-control", "maxlength" => "32"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "nif_cliente", ["class" => "form-label"]);
        echo CHTML::modeloText($cliente, "nif_cliente", ["class" => "form-control", "maxlength" => "9"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "poblacion", ["class" => "form-label"]);
        echo CHTML::modeloText($cliente, "poblacion", ["class" => "form-control", "maxlength" => "32"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "nombre_role", ["class" => "form-label"]);
        echo CHTML::modeloListaDropDown($cliente, "nombre_role", Sistema::app()->ACL()->dameRoles(), ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "fecha_nacimiento", ["class" => "form-label"]);
        echo CHTML::modeloDate($cliente, "fecha_nacimiento", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "contrasenia", ["class" => "form-label"]);
        echo CHTML::modeloPassword($cliente, "contrasenia", ["class" => "form-control", "maxlength" => "32"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Crear Usuario", ["class" => "btn btn-primary", "name" => "crear_usuario"]);
        echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["cliente"]));
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();