<?php

// Añadimos los js necesarios
$this->textoHead = CPager::requisitos().CHTML::scriptFichero("/javascript/main.js",["defer"=>"defer"]);

// Definimos la barra de ubicacion
$this->barraubi = [
    [   
        "texto" => "Inicio",
        "enlace" => array("Inicial")
    ],
    [   
        "texto" => "Foro",
        "enlace" => Sistema::app()->generaURL(array("foro", "Foro_Cinema"))
    ],
    [   
        "texto" => "Contenido",
        "enlace" => Sistema::app()->generaURL(array("foro", "VerContenido"), ["id" => $id])
    ]
];

// Creamos el paginador
$pagi = new CPager($paginador, []); 

// Mostraremos los comentarios realizados del tema en especifico
echo CHTML::dibujaEtiqueta("article", ["id" => "contenido_foro"], null, false);

    // Llamamos al boton de para Añadir un Comentario
    echo CHTML::link("Añadir Comentario", Sistema::app()->generaURL(["foro", "AnadirComentarioForo"], ["id" => $id]), ["class" => "btn btn-primary"]);
    
    // Tiulo del foro
    echo CHTML::dibujaEtiqueta("h4", ["class" => "titulo_foro"], $nombre)."<br>".PHP_EOL;

    // Dibujamos el paginador
    echo $pagi->dibujate()."<br>".PHP_EOL;

    // Comprobamos de que haya comentarios
    if ($comentarios){

        // Recorremos los comentarios disponibles del foro
        foreach ($comentarios as $key => $value) {
            echo CHTML::dibujaEtiqueta("div", ["class" => "comentario"], null, false);
                echo CHTML::dibujaEtiqueta("label", [], "Usuario: ".$value["nick_cliente"]);
                echo CHTML::dibujaEtiqueta("label", [], "Fecha: ".CGeneral::fechaMysqlANormal($value["fecha_publicacion"]));
                echo CHTML::dibujaEtiqueta("hr", [], null);
                echo CHTML::dibujaEtiqueta("h4", [], $value["contenido_foro"]);
                echo CHTML::dibujaEtiqueta("hr", [], null);

                // Comprobamos de que el usuario registrado tenga el rol de admin para borra contenido del Foro
                if (Sistema::app()->Acceso()->puedePermiso(2)){
                    echo CHTML::link("Borrar Comentario", Sistema::app()->generaURL(["foro", "BorrarContenido"], ["id" => $_GET["id"], "cod_contenido_foro" => $value["cod_contenido_foro"]]), 
                        ["class" => "btn btn-danger"]);
                } // End if si tiene permiso 2
                
            echo CHTML::dibujaEtiquetaCierre("div");
        } // End foreach
    } // End if de la validacion

echo CHTML::dibujaEtiquetaCierre("article");