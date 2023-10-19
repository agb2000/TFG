<?php

/**
 * Estamos definiendo el modelo Categoria Foro
 */
class Categoria_Foro extends CActiveRecord{
    
    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "CategoriaForo";
    } // End del metodo fijar Nombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la tabla categoria_foro
     */
    protected function fijarTabla(){
        return "categoria_foro";
    } // End de la funcion fijarTabla

    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la tabla categoria Foro
     */
    protected function fijarId(){
        return "cod_categoria_foro";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Espectaculo
     */
    protected function fijarAtributos(){
        return ["cod_categoria_foro", "nombre_categoria_foro", "contador_comentarios", "fecha_creacion", "borrado"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_categoria_foro" => "Codigo de Categoria Foro",
            "nombre_categoria_foro" => "Nombre del Tema del Foro",
            "contador_comentarios" => "Contador de Comentarios",
            "fecha_creacion" => "Fecha de Realizacion",
            "borrado" => "Borrado"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "nombre_categoria_foro, fecha_creacion",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "cod_categoria_foro",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "nombre_categoria_foro",
                "TIPO" => "CADENA",
                "TAMANIO" => "120",
                "MENSAJE" => "ERROR!! La longuitud maxima del nombre del foro es 120 caracteres"
            ],
            [
                "ATRI" => "contador_comentarios",
                "TIPO" => "ENTERO",
                "MIN" => 0
            ],
            [
                "ATRI" => "fecha_creacion",
                "TIPO" => "FECHA",
                "MENSAJE" => "ERROR!! Formato no Valido dd/mm/yyyy"
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
       $this->nombre_categoria_foro = "";
       $this->contador_comentarios = 0;
       $this->borrado = 0; 
       $this->fecha_creacion = date_format(date_create(), "Y-m-d");
    } // End de la funcion de afterCreate

    /**
     * Estamos definiendo un metodo privado se encarga de comprobar si existe un nombre
     * de categoria foro con ese nombre de categoria foro
     * 
     * @param boolean $insert ---> True si estamos insertando y False no estamos insertando
     * @return boolean  TRUE ---> Si hay datos   
     *                  FALSE ---> No hay datos
     */
    private function validarNombreCategoria(bool $insert){
        // Validamos y ejecutamos la sentencia
        $nombre_categoria_foro = CGeneral::addSlashes(str_replace(' ', '', mb_strtolower($this->nombre_categoria_foro)));
        $sentencia = "Select * from categoria_foro where replace(lower(nombre_categoria_foro), ' ', '') = '$nombre_categoria_foro'";
        $cod_categoria_foro = intval($this->cod_categoria_foro);

        // Validamos la sentencia y establecemos el return
        if ($insert === true){
            if ($this->ejecutarSentencia($sentencia)) {
                $this->setError("nombre_categoria_foro", "ERROR!! El nombre de la categoria foro ya existe");
                return true;
            } // End if de validacion
            return false;
        } // End if 1
        else{
            if ($this->ejecutarSentencia($sentencia)) {
                $sentencia .= " and cod_categoria_foro = '$cod_categoria_foro'";
                if ($this->ejecutarSentencia($sentencia)) {
                    return false;
                } // End if 3
                else {
                    $this->setError("nombre_categoria_foro", "ERROR!! El nombre de la categoria foro ya existe");
                    return true;
                } // End else 3
            } // End if de validacion
            return false;
        } // End else 1
    } // End del metodo privado NombreCategoria

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar una Categoria Foro
     */
    protected function fijarSentenciaInsert(){
        // Escapamos los caracteres
      $nombre = CGeneral::addSlashes(mb_strtolower($this->nombre_categoria_foro));
      $fecha_creacion = CGeneral::fechaNormalAMysql($this->fecha_creacion);

      // Comprobamos si existe o no ese nombre categoria_foro con ese nombre
      if ($this->validarNombreCategoria(true) == false){
        // Realizamos el insert categoria_foro
        return "insert into categoria_foro (". 
            " nombre_categoria_foro, contador_comentarios, fecha_creacion, borrado". 
            " ) values ('$nombre', '0', '$fecha_creacion', false)"; 
        } // End if de la funcion privada
    } // End de la funcion fijarInsert

    /**
     * Estamos redefiniendo el metodo fijarUpdate se encarga de actualizar una Categoria Foro
     */
    protected function fijarSentenciaUpdate(){
        // Escapamos los caracteres
        $nombre = CGeneral::addSlashes(mb_strtolower($this->nombre_categoria_foro));
        $contador = intval($this->contador_comentarios);
        $borrado = CGeneral::addSlashes($this->borrado);
        $fecha_creacion = CGeneral::fechaNormalAMysql($this->fecha_creacion);
        $cod_categoria_foro = intval($this->cod_categoria_foro);
       
        // Llamamos a la funcion privada de validarNombreCategoria
        if ($this->validarNombreCategoria(false) == false){
            // Realizamos el update Categoria_Foro
            return "update categoria_foro set ". 
                " nombre_categoria_foro = '$nombre', ". 
                " contador_comentarios = '$contador', ". 
                " fecha_creacion = '$fecha_creacion', ". 
                " borrado = '$borrado' ".
                " where cod_categoria_foro = {$cod_categoria_foro}"; 
        } // End if de la funcion privada
    } // End de la funcion de fijar Update

    /**
     * Estamos definiendo el metodo de clase dameCategorias_Foro se encarga de mostrar los siguientes resultados:
     *      1. Un array con todas los categorias_foro de la base de datos
     *      2. El nombre de categoria_foro correspondiente al cod_categoria_foro que se le pasa como parámetro
     *      3. Boolean FALSE cuando le pasemos un cod_categoria_foro y no exista
     *
     * @param integer|null $cod_categoria_foro ----> Codigo de Categoria_Foro. La clave del array
     * @return array|string|false ----> ARRAY (Devolvemos el array de categoria_foro, cuando no le pasemos ningun cod_categoria_foro)
     *                                  STRING (Devolvemos el nombre categoria_foro, cuando el cod_categoria_foro se encuentre en el array)
     *                                  FALSE (Devolvemos FALSE, si el cod_categoria_foro no se encuentre en el array de categoria_foro)
     */
    public static function dameCategorias_Foro (int $cod_categoria_foro=null):array|string|false{
        // Definimos nuestras variables locales
        $categoria_foro = new Categoria_Foro();
        $estados = $categoria_foro->buscarTodos(["where" => "borrado = 0"]);
        $array_categoria_foro = [];

        // Validamos si no hay ocurrido ningun error a la hora de obtener todas las categorias_foro
        if (!$estados){
            return false;
        } // End if de validacion de estados

        // Guardamos las descripciones en un array
        foreach($estados as $fila => $valor){
            $array_categoria_foro[$fila+1] = $estados[$fila]["nombre_categoria_foro"];
        } // End foreach

        // Comprobamos si es nulo
        if (is_null($cod_categoria_foro)){
            return $array_categoria_foro;
        } // End if de nulos

        // Comprobamos de que exsita en el array categoria foro
        if (array_key_exists($cod_categoria_foro, $array_categoria_foro)){
            return $array_categoria_foro[$cod_categoria_foro];
        } // End if de exista en categorias foro

        // En caso de que no haya ninguna categoria foro con ese codigo
        return false;
    } // End de la funcion de clase de Categorias_Foro

} // End del modelo Categoria Foro