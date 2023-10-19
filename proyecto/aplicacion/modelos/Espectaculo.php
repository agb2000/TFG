<?php

/**
 * Estamos definiendo el modelo Espectaculo
 */
class Espectaculo extends CActiveRecord{

    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "Espectaculo";
    } // End del metodo fijar Nombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la vista cons_espectaculo_categoria
     */
    protected function fijarTabla(){
        return "cons_espectaculo_categoria";
    } // End de la funcion fijarTabla

    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la vista cons_espectaculo_categoria
     */
    protected function fijarId(){
        return "cod_espectaculos";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Espectaculo
     */
    protected function fijarAtributos(){
        return ["cod_espectaculos", "cod_categoria_espectaculo", "titulo", "duracion", 
            "fecha_lanzamiento", "fecha_finalizacion", "sinopsis", "imagen", "borrado"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_espectaculos" => "Codigo del Espectaculo",
            "cod_categoria_espectaculo" => "Codigo de la Categoria del Espectactulo",
            "titulo" => "Titulo del Espectaculo",
            "duracion" => "Duracion del Espectaculo",
            "fecha_lanzamiento" => "Fecha Lanzamiento del Espectaculo",
            "fecha_finalizacion" => "Fecha Finalizacion del Espectaculo",
            "sinopsis" => "Sinopsis del Espectaculo",
            "imagen" => "Foto del Espectaculo",
            "borrado" => "Borrado"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "cod_categoria_espectaculo, titulo, duracion, fecha_lanzamiento, fecha_finalizacion, sinopsis, imagen",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "cod_categoria_espectaculo, duracion, cod_espectaculos",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "cod_categoria_espectaculo",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarCategoria_Espe"
            ],
            [
                "ATRI" => "titulo",
                "TIPO" => "CADENA",
                "TAMANIO" => 60,
                "MENSAJE" => "ERROR!! La longuitud maxima del titulo del espectaculo es 60 caracteres"
            ],
            [
                "ATRI" => "duracion",
                "TIPO" => "ENTERO",
                "MIN" => 30,
                "MENSAJE" => "ERROR!! La duracion del espectaculo debe ser mayor a 30 minutos"
            ],
            [
                "ATRI" => "fecha_lanzamiento, fecha_finalizacion",
                "TIPO" => "FECHA",
                "MENSAJE" => "ERROR!! Formato no Valido dd/mm/yyyy"
            ],
            [
                "ATRI" => "fecha_lanzamiento",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarFechaLanzamiento"
            ],
            [
                "ATRI" => "fecha_finalizacion",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarFechaFinalizacion"
            ],
            [
                "ATRI" => "sinopsis",
                "TIPO" => "CADENA",
                "TAMANIO" => 950,
                "MENSAJE" => "ERROR!! El tamaño maximo de la sinopsis es de 950 caracteres"
            ],
            [
                "ATRI" => "imagen",
                "TIPO" => "CADENA",
                "TAMANIO" => 300,
                "MENSAJE" => "ERROR!! El tamaño maximo de la url de la imagen es de 300 caracteres"
            ],
            [
                "ATRI" => "borrado",
                "TIPO" => "ENTERO",
                "MIN" => 0,
                "MAX" => 1
            ]
        ];
    } // End del metodo fijarRestricciones

    /**
     * Estamos redefiniendo el metodo afterCreate se encarga de asignar valores por defecto a los atributos.
     */
    protected function afterCreate(){
        $this->titulo = "";
        $this->fecha_lanzamiento = date_format(date_create(), "Y-m-d");
        $this->fecha_finalizacion = date_format(date_create(), "Y-m-d");
        $this->sinopsis = "";
        $this->imagen = "";
        $this->borrado = 0;
    } // End de la funcion de afterCreate

    /**
     * Definimos la validacion de que la categoria asignada al espectaculo exista previamente
     */
    public function validarCategoria_Espe(){
        // Definimos las variables locales
        $cod_categoria_espectaculo = intval($this->cod_categoria_espectaculo);

        // Validamos de que exista la categoria 
        if (!Categoria_Espectaculo::dameCategorias_Espectaculo($cod_categoria_espectaculo)){
            $this->setError("cod_categoria_espectaculo", "ERROR!! Esa categoria del espectaculo no existe");
        } // End if de la validacion

    } // En de la funcion de validarCategoria_Espe

    /**
     * Definimos la restriccion de que la fecha lanzamiento debe ser mayor o igual al actual
     */
    public function validarFechaLanzamiento(){
        // Definimos las variables locales
        $fecha_actual = date_format(date_create(), "Y-m-d");

        // Comprobamos de que la fecha de lanzamiento no este vacia
        if ($this->fecha_lanzamiento != ""){
            // Escapamos la fecha realizada y cambiamos el formato
            $fecha_inicio = CGeneral::fechaNormalAMysql($this->fecha_lanzamiento);

            // Comprobamos de que la validacion
            if ($fecha_actual > $fecha_inicio){
                $this->setError("fecha_lanzamiento", "ERROR!! La fecha de inicio no puede ser mayor a la actual");
            } // End if de la validacion

        } // End if de fecha vacia
    } // End if de la validacion de Fecha Lanzamiento

    /**
     * Definimos la restriccion de que la fecha finalizacion debe ser mayor o igual a la fecha de inicio
     */
    public function validarFechaFinalizacion(){
        // Comprobamos de que la fecha de lanzamiento y finalizacion no esten vacio
        if ($this->fecha_lanzamiento != "" && $this->fecha_finalizacion != "")
            // Definimos nuestras variables locales
            $fecha_inicio = CGeneral::fechaNormalAMysql($this->fecha_lanzamiento);
            $fecha_fin = CGeneral::fechaNormalAMysql($this->fecha_finalizacion);

            // Hacemos la comprobacion
            if ($fecha_fin < $fecha_inicio){
                $this->setError("fecha_finalizacion", "Error!! La fecha de finalizacion debe ser mayor que la fecha inicio");
            } // End if de la validacion

    } // End de la validacion de Fecha Finalizacion

    /**
     * Estamos definiendo un metodo privado se encarga de comprobar si existe el titulo
     * de ese espectaculo previamente en nuestra base de datos
     * 
     * @param boolean $insert ---> True si estamos insertando y False no estamos insertando
     * @return boolean  TRUE ---> Si hay datos   
     *                  FALSE ---> No hay datos
     */
    private function validarNombreEspectaculo(bool $insert): bool{
        // Validamos y ejecutamos la sentencia
        $titulo = CGeneral::addSlashes(str_replace(' ', '', mb_strtolower($this->titulo)));
        $sentencia = "Select * from espectaculos where replace(lower(titulo), ' ', '') = '$titulo'";
        $cod_espectaculo = intval($this->cod_espectaculos);

        // Validamos la sentencia y establecemos el return
        if ($insert === true) {
            if ($this->ejecutarSentencia($sentencia)) {
                $this->setError("titulo", "ERROR!! El titulo del espectaculo ya existe");
                return true;
            } // End if de validacion
            return false;
        } // End if
        else {
            if ($this->ejecutarSentencia($sentencia)) {
                $sentencia .= " and cod_espectaculos = '$cod_espectaculo'";
                if ($this->ejecutarSentencia($sentencia)) {
                    return false;
                } // End if 3
                else {
                    $this->setError("titulo", "ERROR!! El titulo del espectaculo ya existe");
                    return true;
                } // End else 3
            } // End if de validacion
            return false;
        } // End else
    } // End de la funcion de validarNombreCategoria

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar un Espectaculo
     */
    protected function fijarSentenciaInsert(){
        // Escapamos los caracteres
        $cod_categoria_espectaculo = intval($this->cod_categoria_espectaculo);
        $titulo = CGeneral::addSlashes(mb_strtolower($this->titulo));
        $duracion = intval($this->duracion);
        $fecha_inicio = CGeneral::fechaNormalAMysql($this->fecha_lanzamiento);
        $fecha_fin = CGeneral::fechaNormalAMysql($this->fecha_finalizacion);
        $sinopsis = CGeneral::addSlashes(mb_strtolower($this->sinopsis));
        $imagen = CGeneral::addSlashes(mb_strtolower($this->imagen));

        // Comprobamos si existe o no ese espectaculo con ese titulo
        if ($this->validarNombreEspectaculo(true) == false){
            // Realizamos el insert Categorias
            return "insert into espectaculos (". 
                "cod_categoria_espectaculo, titulo, duracion, fecha_lanzamiento, fecha_finalizacion, sinopsis, imagen, borrado". 
                ") values ('$cod_categoria_espectaculo', '$titulo', '$duracion', '$fecha_inicio', '$fecha_fin', '$sinopsis', '$imagen', false)";
        } // End if de la validacion de titulo   

    } // End if de la funcion fijarInsert
  
    /**
     * Estamos redefiniendo el metodo fijarUpdate se encarga de actualizar un Espectaculo
     */
    protected function fijarSentenciaUpdate(){
        // Escapamos los caracteres
        $cod_espectaculos = intval($this->cod_espectaculos);
        $cod_categoria_espectaculo = intval($this->cod_categoria_espectaculo);
        $titulo = CGeneral::addSlashes(mb_strtolower($this->titulo));
        $duracion = intval($this->duracion);
        $fecha_inicio = CGeneral::fechaNormalAMysql($this->fecha_lanzamiento);
        $fecha_fin = CGeneral::fechaNormalAMysql($this->fecha_finalizacion);
        $sinopsis = CGeneral::addSlashes(mb_strtolower($this->sinopsis));
        $imagen = CGeneral::addSlashes(mb_strtolower($this->imagen));
        $borrado = CGeneral::addSlashes($this->borrado);
         
        // Llamamos a la funcion privada de validarNombreCategoria
        if ($this->validarNombreEspectaculo(false) == false){
            // Realizamos el update Espectaculos
            return "update espectaculos set ". 
                " cod_espectaculos = '$cod_espectaculos', ". 
                " cod_categoria_espectaculo = '$cod_categoria_espectaculo', ". 
                " titulo = '$titulo', ".
                " duracion = '$duracion', ".
                " fecha_lanzamiento = '$fecha_inicio', ".
                " fecha_finalizacion = '$fecha_fin', ".
                " sinopsis = '$sinopsis', ".
                " imagen = '$imagen', ".
                " borrado = '$borrado' ".
                " where cod_espectaculos = {$cod_espectaculos}"; 
        } // End if de validar Nombre del Espectaculo

    } // End de la funcion de fijar Update

    /**
     * Estamos definiendo el metodo de clase dameEspectaculos se encarga de mostrar los siguientes resultados:
     *      1. Un array con todas los espectaculos de la base de datos
     *      2. El titulo del espectaculo correspondiente al cod_espectaculo que se le pasa como parámetro
     *      3. Boolean FALSE cuando le pasemos un cod_espectaculo y no exista
     *
     * @param integer|null $cod_espectaculo ----> Codigo de Espectáculos. La clave del array
     * @return array|string|false ----> ARRAY (Devolvemos el array de nombre_espectaculos, cuando no le pasemos ningun cod_espectaculo)
     *                                  STRING (Devolvemos el titulo nombre_espectaculos, cuando el cod_espectaculo se encuentre en el array)
     *                                  FALSE (Devolvemos FALSE, si el cod_espectaculo no se encuentre en el array de nombre_espectaculos)
     */
    public static function dameEspectaculos (int $cod_espectaculo=null):array|string|false{
        // Definimos nuestras variables locales
        $espectaculo = new Espectaculo();
        $estados = $espectaculo->buscarTodos(["where" => "borrado = 0"]);
        $nombre_espectaculos = [];

        // Validamos si no hay ocurrido ningun error a la hora de obtener todas los espectaculos
        if (!$estados){
            return false;
        } // End if de validacion de estados

        // Guardamos los titulos en un array
        foreach($estados as $fila => $valor){
            $nombre_espectaculos[$valor["cod_espectaculos"]] = $valor["titulo"];
        } // End foreach

        // Comprobamos si es nulo
        if (is_null($cod_espectaculo)){
            return $nombre_espectaculos;
        } // End if de nulos

        // Comprobamos de que exsita en el array nombre_espectaculos
        if (array_key_exists($cod_espectaculo, $nombre_espectaculos)){
            return $nombre_espectaculos[$cod_espectaculo];
        } // End if de exista en categorias

        // En caso de que no haya ninguna categoria, con ese codigo
        return false;
    } // End de la funcion de clase de dameEspectaculos
    
} // End del modelo espectaculo