<?php

/**
 * Estamos definiendo el modelo Categoría de Espectaculos
 */
class Categoria_Espectaculo extends CActiveRecord{

    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "CategoriaEspectaculo";
    } // End del metodo fijar Nombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la tabla categoria_espectáculos de la base de datos
     */
    protected function fijarTabla(){
        return "categoria_espectaculo";
    } // End de la funcion fijarTabla
   
    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la tabla categoria_espectáculos
     */
    protected function fijarId(){
        return "cod_categoria_espectaculo";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Categoria_Espectaculo
     */
    protected function fijarAtributos(){
        return ["cod_categoria_espectaculo", "nombre_categoria_espectaculo",  "borrado"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_categoria_espectaculo" => "Codigo de Categoria Espectaculo",
            "nombre_categoria_espectaculo" => "Nombre de Categoria Espectaculo",
            "borrado" => "Borrado"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "cod_categoria_espectaculo",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "nombre_categoria_espectaculo",
                "TIPO" => "REQUERIDO",
            ],
            [
                "ATRI" => "nombre_categoria_espectaculo",
                "TAMANIO" => 120,
                "MENSAJE" => "ERROR!! La longuitud maxima de la cadena es de 120 caracteres"
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
        $this->cod_categoria_espectaculo = 0;
        $this->nombre_categoria_espectaculo = "";
        $this->borrado = 0;
    } // End de la funcion de afterCreate

    /**
     * Estamos definiendo un metodo privado se encarga de comprobar si existe una categoria de espectaculo con ese nombre
     * 
     * @param boolean $insert ---> True si estamos insertando y False no estamos insertando
     * @return boolean  TRUE ---> Si hay datos   
     *                  FALSE ---> No hay datos
     */
    private function validarNombreCategoria(bool $insert): bool{
        // Validamos y ejecutamos la sentencia
        $nombre_categoria_espectaculo = CGeneral::addSlashes(mb_strtolower($this->nombre_categoria_espectaculo));
        $sentencia = "Select * from categoria_espectaculo where lower(nombre_categoria_espectaculo) = '$nombre_categoria_espectaculo'";
        $cod_categoria_espectaculo = intval($this->cod_categoria_espectaculo);

        // Validamos la sentencia en caso de que se realize un insert
        if ($insert === true){
            if ($this->ejecutarSentencia($sentencia)) {
                $this->setError("nombre_categoria_espectaculo", "ERROR!! El nombre de la categoria_espectaculo ya existe");
                return true;
            } // End if de validacion
            return false;
        } // End if 1

        // Validamos la sentencia en caso de que realize un insert
        else{
            if ($this->ejecutarSentencia($sentencia)) {
                $sentencia .= " and cod_categoria_espectaculo = '$cod_categoria_espectaculo'";
                if ($this->ejecutarSentencia($sentencia)){
                    return false;
                } // Enf if 3
                $this->setError("nombre_categoria_espectaculo", "ERROR!! El nombre de la categoria_espectaculo ya existe");
                return true;
            } // End if de validacion
            return false;
        } // End else 1
    } // End de la funcion de validarNombreCategoria

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar una categoria_espectaculo
     */
    protected function fijarSentenciaInsert(){
        // Definimos nuestras variables locales y escapamos los caracteres
        $nombre = CGeneral::addSlashes(mb_strtolower($this->nombre_categoria_espectaculo));

        // Llamamos a la funcion privada de validarNombreCategoria y realizamos una insercción a la tabla CATEGORÍA_ESPECTACULOS
        if ($this->validarNombreCategoria(true) == false){
            return "insert into categoria_espectaculo (". 
                " nombre_categoria_espectaculo,  borrado". 
                " ) values ('$nombre', false)"; 
        } // End if de la funcion privada

    } // End del metodo de insertar

    /**
     * Estamos redefiniendo el metodo fijarUpdate se encarga de actualizar una categoría_espectaculo
     */
    protected function fijarSentenciaUpdate(){
        // Definimos nuestras variables locales y escapamos los caracteres
        $nombre = CGeneral::addSlashes(mb_strtolower($this->nombre_categoria_espectaculo));
        $borrado = CGeneral::addSlashes($this->borrado);
        $cod_categoria_espectaculo = intval($this->cod_categoria_espectaculo);
       
        // Llamamos a la funcion privada de validarNombreCategoria y realizamos una actualización a la tabla CATEGORÍA_ESPECTACULOS
        if ($this->validarNombreCategoria(false) == false){
            return "update categoria_espectaculo set ". 
                " nombre_categoria_espectaculo = '$nombre', ".
                " borrado = '$borrado' ".
                " where cod_categoria_espectaculo = {$cod_categoria_espectaculo}"; 
        } // End if de la funcion privada

    } // End del metodo de sentencia de actualizar

    /**
     * Estamos definiendo el metodo de clase dameCategorias_Espectaculos se encarga de mostrar los siguientes resultados:
     *      1. Un array con todas las categoria_espectaculos de la base de dato
     *      2. La descripcion de categoria_espectaculo correspondiente al cod_categoria_espectaculo que se le pasa como parámetro
     *      3. Boolean FALSE cuando le pasemos un cod_categoria_espectaculo y no exista
     *
     * @param integer|null $cod_categoria_espe ----> Codigo de Categoria de Espectáculos. La clave del array
     * @return array|string|false ----> ARRAY (Devolvemos el array de Categorias_Espectaculos, cuando no le pasemos ningun cod_categoria_espe)
     *                                  STRING (Devolvemos el nombre_categoria_espectaculo, cuando el cod_categoria_espe se encuentre en el array)
     *                                  FALSE (Devolvemos FALSE, si el cod_categoria no se encuentre en el array_categorias_espectaculos)
     */
    public static function dameCategorias_Espectaculo (int $cod_categoria_espe=null):array|string|false{
        // Definimos nuestras variables locales
        $categoria_espectaculo = new Categoria_Espectaculo();
        $estados = $categoria_espectaculo->buscarTodos(["where" => "borrado = 0"]);
        $array_categoria_espectaculos = [];

        // Validamos si no hay ocurrido ningun error a la hora de obtener todas las categorias_espectaculos
        if (!$estados){
            return false;
        } // End if de validacion de estados

        // Guardamos los nombres en un array asociado por posicion que hacemos referencia al cod_categoria_espectaculo suyo
        foreach($estados as $fila => $valor){
            $array_categoria_espectaculos[$valor["cod_categoria_espectaculo"]] = $valor["nombre_categoria_espectaculo"];
        } // End foreach

        // Comprobamos si es nulo el codigo de categoría espectaculos que se le pasa por parámetro
        if (is_null($cod_categoria_espe)){
            return $array_categoria_espectaculos;
        } // End if de nulos

        // Comprobamos de que exista en el array categorias_espectaculos
        if (array_key_exists($cod_categoria_espe, $array_categoria_espectaculos)){
            return $array_categoria_espectaculos[$cod_categoria_espe];
        } // End if de exista en categorias_espectaculos

        // En caso de que no haya ninguna categoria_espectaculo con ese codigo
        return false;
    } // End de la funcion de clase de Categorias_Productos

} // End del modelo de CATEGORIA_ESPECTACULO