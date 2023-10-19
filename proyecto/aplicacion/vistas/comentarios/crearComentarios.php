<?php

// Mostraremos todos los errores correspondiente a la hora de crear un Comentario
echo "<br>".PHP_EOL.CHTML::modeloErrorSumario($comentario)."<br>".PHP_EOL;

// Mostraremos un formulario donde el usuario podrá crear el Comentario
echo CHTML::dibujaEtiqueta("article", ["id" => "comentarios"], null, false);

    echo CHTML::iniciarForm(atributosHTML:["class" => "form-row"]);
        echo CHTML::dibujaEtiqueta("div", ["class" => "col"], "", false);
            echo CHTML::modeloLabel($comentario, "comentario", ["class" => "form-label"]);
            echo CHTML::modeloTextArea($comentario, "comentario", ["class" => "form-control"]);
        echo CHTML::dibujaEtiquetaCierre("div");

        echo CHTML::dibujaEtiqueta("div", ["class" => "col"], "", false);
            echo CHTML::modeloLabel($comentario, "valoracion", ["class" => "form-label"]);
            echo CHTML::modeloListaDropDown($comentario, "valoracion", [0,1,2,3,4,5], ["class" => "form-control", "linea"=>false]);
        echo CHTML::dibujaEtiquetaCierre("div");

        echo CHTML::dibujaEtiqueta("div", ["class" => "col"], "", false);
            echo CHTML::campoBotonSubmit("Crear Comentario", ["class" => "btn btn-primary", "name" => "crear_comentario"]);
            echo CHTML::link(CHTML::botonHtml("Volver Al Indice", ["class" => "btn btn-danger"]), Sistema::app()->generaURL(["buscador", "Datos"], ["id" => $_GET["id"]]));
        echo CHTML::dibujaEtiquetaCierre("div");
    echo CHTML::finalizarForm();
    
echo CHTML::dibujaEtiquetaCierre("article");