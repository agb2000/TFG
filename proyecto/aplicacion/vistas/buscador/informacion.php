<?php
setlocale(LC_TIME, "esp_esp","es_ES"); 

// Añadimos los js necesarios
$this->textoHead = CPager::requisitos().CHTML::scriptFichero("/javascript/verespe.js",["defer"=>"defer"]);

// Definimos nuestro objeto CPaginador
$pagi = new CPager($paginador);

// Mostraremos toda la información necesarios de los espectaculos
echo CHTML::dibujaEtiqueta("article", ["id" => "Informacion"], null, false);

    echo CHTML::dibujaEtiqueta("div", []);
        echo CHTML::imagen($datos->imagen, "Imagen del Espectaculo", []);
    echo CHTML::dibujaEtiquetaCierre("div");

    echo CHTML::dibujaEtiqueta("div", [], null, false);
        echo CHTML::dibujaEtiqueta("p", [], "Entradas ".$datos->titulo);
        echo CHTML::dibujaEtiqueta("label", [], "Inicio: ".CGeneral::fechaMysqlANormal($datos->fecha_lanzamiento));
        echo CHTML::dibujaEtiqueta("label", [], "Fin: ".CGeneral::fechaMysqlANormal($datos->fecha_finalizacion));
        if (isset($nombre)) echo CHTML::dibujaEtiqueta("label", [], "Actores: ".$nombre);
        echo "<br>".PHP_EOL.CHTML::dibujaEtiqueta("div", ["disabled" => "disabled"],  $datos->sinopsis);
    echo CHTML::dibujaEtiquetaCierre("div");
echo CHTML::dibujaEtiquetaCierre("article");

// Creamos nuestro article que mostraremos las sesiones disponibles y los comentarios del espectaculo
echo CHTML::dibujaEtiqueta("article", ["id" => "sesiones"], null, false);
    // Titulo
    echo CHTML::dibujaEtiqueta("h2", [], "SESIONES DISPONIBLES");

    // Recorremos el array de sesiones 
    foreach ($sesiones as $key => $value) {
        foreach($value as $key2 => $value2){
        echo CHTML::dibujaEtiqueta("div", ["class" => "card mb-2"], null, false);
            echo CHTML::dibujaEtiqueta("div", ["class" => "card-body"], null, false);
                echo CHTML::dibujaEtiqueta("h5", ["class" => "card-title"], utf8_encode(strftime("%A, %d de %B de %Y", strtotime($value2["fecha"]))), true);
                echo CHTML::dibujaEtiqueta("h5", ["class" => "card-title"], $value2["nombre_sala"], true);
                echo CHTML::dibujaEtiqueta("h5", ["class" => "card-text"], $value[0]["hora_inicio"]." ---- ".$value2["hora_fin"], true);
                echo CHTML::link("Comprar Entradas", Sistema::app()->generaURL(["entradas", "Entradas"], ["id" => $value2["cod_sesion"]]), ["class" => "btn btn-dark"]);
            echo CHTML::dibujaEtiquetaCierre("div");
        echo CHTML::dibujaEtiquetaCierre("div");
        } // End foreach 2
    } // End foreach 1

    // Hacemos un salto de linea
    echo "<br><br><br>".PHP_EOL;

    // Mostraremos los comentarios realizados de ese espectaculo
    echo CHTML::dibujaEtiqueta("div");
        // Titulo
        echo CHTML::dibujaEtiqueta("h2", [], "COMENTARIOS");

        // Boton de añadir Comentario
        echo CHTML::link("Añadir Comentarios", Sistema::app()->generaURL(["comentarios", "CrearComentarios"], ["id" => $_GET["id"]]), ["class" => "btn btn-primary", "id"=>"anadir_comentario"]);

        // Dibujamos el paginador de los comentario
        echo $pagi->dibujate()."<br>".PHP_EOL;

        // Comprobamos de que haya comentarios
        if ($comentarios){
            // Recorremos el array de comentarios de ese espectaculo
            foreach ($comentarios as $key => $value) {
                echo CHTML::dibujaEtiqueta("div", ["class" => "comentario"], null, false);
                    // Mostraremos la informacion de los comentarios realizados
                    echo CHTML::dibujaEtiqueta("label", [], "Nombre del Usuario: ".$value["nick_cliente"]);
                    echo CHTML::dibujaEtiqueta("label", ["class" => "fecha"], "Fecha de Publicacion: ".CGeneral::fechaMysqlANormal($value["fecha_publicacion"]));
                    echo CHTML::dibujaEtiqueta("label", ["class" => "fecha"], "Valoracion: ".$value["valoracion"]);
                    echo CHTML::dibujaEtiqueta("hr", [], null);
                    echo CHTML::dibujaEtiqueta("h4", [], $value["comentario"]);
                    echo CHTML::dibujaEtiqueta("hr", [], null);

                    // Comprobamos de que si el usuario regsitrado sea admin le aparezca el boton de borrar comentario
                    if (Sistema::app()->Acceso()->puedePermiso(2)){
                        echo CHTML::link("Borrar Comentario", Sistema::app()->generaURL(["comentarios", "BorrarComentarios"], ["id" => $_GET["id"], "cod_comentarios" => $value["cod_comentarios"]]), 
                            ["class" => "btn btn-danger"])."<br><br>".PHP_EOL;
                    } // End if si tiene permiso 2
                echo CHTML::dibujaEtiquetaCierre("div");
            } // End foreach
        } // End if de la validacion
    echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("article");
