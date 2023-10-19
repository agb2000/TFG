<?php

// Definimos nuestra barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Cliente",
        "enlace" => array("cliente")
    ],
    [
        "texto" => "Modificar Cliente",
        "enlace" => Sistema::app()->generaURL(["cliente", "Modificar"], ["id" => $_GET["id"]])
    ]
];

// Mostramos los errores del modelo de validacion
echo CHTML::modeloErrorSumario($cliente)."<br>".PHP_EOL;

// Realizamos un formulario para modificar los datos un modelo de Tipo Cliente
echo CHTML::iniciarForm(atributosHTML:["class" => "row g-3"]);

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
        echo CHTML::modeloListaDropDown($cliente, "nombre_role", Sistema::app()->ACL()->dameRoles(), ["class" => "form-select"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "fecha_nacimiento", ["class" => "form-label"]);
        echo CHTML::modeloDate($cliente, "fecha_nacimiento", ["class" => "form-control"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "col-md-6"], "", false);
        echo CHTML::modeloLabel($cliente, "contrasenia", ["class" => "form-label"]);
        echo CHTML::modeloPassword($cliente, "contrasenia", ["class" => "form-control", "maxlength" => "32"]);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-2"], "", false);
        echo CHTML::modeloLabel($cliente, "borrado", ["class" => "form-label"])."<br>".PHP_EOL;
        echo CHTML::dibujaEtiqueta("article");
            echo CHTML::modeloListaRadioButton($cliente, "borrado", ["false"]);
        echo CHTML::dibujaEtiquetaCierre("article");
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", ["class" => "mb-3"], "", false);
        echo CHTML::campoBotonSubmit("Modificar Cliente", ["class" => "btn btn-primary", "name" => "actualizar"]);
        echo CHTML::link(CHTML::botonHtml("Ver Cliente", ["class" => "btn btn-success"]), Sistema::app()->generaURL(["cliente", "ver"],["id" => $_GET["id"]]));
        echo CHTML::link(CHTML::botonHtml("Borrar Cliente", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["cliente", "Borrar"],["id" => $_GET["id"]]));
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();