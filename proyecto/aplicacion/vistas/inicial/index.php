<?php

// Añadimos los js necesarios
$this->textoHead = CHTML::scriptFichero("/javascript/main.js",["defer"=>"defer"]);

// Definimos nuestras variables locales
$espectaculos = new Espectaculo();

// Definimos nuestro div donde mostraremos los tipos de espectaculos correspondientes a sus categorias
echo CHTML::dibujaEtiqueta("article", ["id" => "mostrar_espectaculos"], null, false);

    // Comprobamos de que haya categorias correspondientes 
    if (Categoria_Espectaculo::dameCategorias_Espectaculo() != false){

        // En caso de que haya categorias los recorremos y lo mostraremos
        foreach (Categoria_Espectaculo::dameCategorias_Espectaculo() as $key => $value) {
            
            // Escapamos los caracteres para que la primera letra sea mayuscula
            $nombre = mb_strtoupper(mb_substr($value, 0, 1)).mb_strtolower(mb_substr($value, 1, mb_strlen($value)));

            echo "<br>".PHP_EOL;

            // Creamos un div donde mostraremos los espectaculos correspondientes a esa categoria
            echo CHTML::dibujaEtiqueta("div", ["class" => "espectaculo"], null, false);

                // Mostraremos el tipo Categoria de Espectaculo
                echo CHTML::dibujaEtiqueta("p", ["class" => "titulo"], $nombre, true);

                // Escapamos los caracteres y obtenemos los espectaculos de esa categoria espectaculo
                $nombre_categoria = CGeneral::addSlashes($value);
                $filas = $espectaculos->buscarTodos(["where" => "nombre_categoria_espectaculo = '$value' and borrado = '0'"]);

                // Crearemos un div donde mostraremos los espectaculos
                echo CHTML::dibujaEtiqueta("div", ["class" => "espectaculos_de"]);

                // Validamos de que haya espectaculos
                if ($filas){

                    // Recorremos los espectaculos
                    foreach ($filas as $key2 => $value2) {

                        // Crearemos un card donde mostraremos la imagen, el titulo y un enlace para comprar entradas y mostrar información
                        echo CHTML::dibujaEtiqueta("div", ["class" => "card"], null, false);
                            echo CHTML::imagen($value2["imagen"], "Imagen del Espectaculo", ["class" => "card-img-top"]);
                            echo CHTML::dibujaEtiqueta("div", ["class" => "card-body"], null, false);
                                echo CHTML::dibujaEtiqueta("p", ["class" => "card-text"], $value2["titulo"]);
                                echo CHTML::link(CHTML::botonHtml("Información", ["class" => "btn btn-dark"]), Sistema::app()->generaURL(["buscador", "Datos"], ["id" => $value2["cod_espectaculos"]]));
                            echo CHTML::dibujaEtiquetaCierre("div");
                        echo CHTML::dibujaEtiquetaCierre("div");

                    } // End foreach
                } // End if de filas

                echo CHTML::dibujaEtiquetaCierre("div");
            echo CHTML::dibujaEtiquetaCierre("div");
        } // End foreach
    } // End if 

echo CHTML::dibujaEtiquetaCierre("article");
