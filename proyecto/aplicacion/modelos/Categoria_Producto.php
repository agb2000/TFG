<?php

/**
 * Estamos definiendo el modelo de Categorias Producto
 */
class Categoria_Producto extends CActiveRecord{

    /**
     * Estamos redefiniendo el metodo fijarNombre el cual le asignamos un nombre al modelo
     */
    protected function fijarNombre(){
        return "CategoriaProducto";
    } // End del metodo fijar Nombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la tabla categoria_producto de la bd
     */
    protected function fijarTabla(){
        return "categoria_producto";
    } // End de la funcion fijarTabla
   
    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la tabla categoria_producto
     */
    protected function fijarId(){
        return "cod_categoria_producto";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los atributos del modelo Categoria_Producto
     */
    protected function fijarAtributos(){
        return ["cod_categoria_producto", "nombre_categoria", "descripcion_categoria", "borrado"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion asignamos a cada atributo una descripcion
     */
    protected function fijarDescripciones(){
        return [
            "nombre_categoria" => "Nombre de Categoria",
            "descripcion_categoria" => "Descripcion de Categoria",
            "borrado" => "Borrado"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de fijar las restricciones a cada atributo.
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "cod_categoria_producto",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "nombre_categoria, descripcion_categoria",
                "TIPO" => "REQUERIDO",
            ],
            [
                "ATRI" => "nombre_categoria",
                "TAMANIO" => 60,
                "MENSAJE" => "Longuitud maxima de la cadena es de 60"
            ],
            [
                "ATRI" => "descripcion_categoria",
                "TAMANIO" => 120,
                "MENSAJE" => "Longuitud maxima de la cadena es de 120"
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
        $this->cod_categoria = 0;
        $this->nombre_categoria = "";
        $this->descripcion_categoria = "";
        $this->borrado = 0;
    } // End de la funcion de afterCreate

    /**
     * Estamos definiendo un metodo privado se encarga de comprobar si existe una categoria de producto con ese nombre.
     * 
     * @param boolean $insert ---> True si estamos insertando y False no estamos insertando
     * @return boolean  TRUE ---> Si hay datos   
     *                  FALSE ---> No hay datos
     */
    private function validarNombreCategoria(bool $insert): bool{
        // Validamos y ejecutamos la sentencia
        $nombre_categoria = CGeneral::addSlashes(str_replace(' ', '', mb_strtolower($this->nombre_categoria)));
        $sentencia = "Select * from categoria_producto where replace(lower(nombre_categoria), ' ', '') = '$nombre_categoria'";
        $cod_categoria = intval($this->cod_categoria_producto);

        // Validamos la sentencia y establecemos el return
        if ($insert === true){
            if ($this->ejecutarSentencia($sentencia)) {
                $this->setError("nombre_categoria", "ERROR!! El nombre de la categoria ya existe");
                return true;
            } // End if de validacion
            return false;
        } // End if 1
        else{
            if ($this->ejecutarSentencia($sentencia)) {
                $sentencia .= " and cod_categoria_producto = '$cod_categoria'";
                if ($this->ejecutarSentencia($sentencia)){
                    return false;
                } // End if 3
                $this->setError("nombre_categoria", "ERROR!! El nombre de la categoria ya existe");
                return true;
            } // End if de validacion
            return false;
        } // End else 1
    } // End de la funcion de validarNombreCategoria

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar una categoria_producto
     */
    protected function fijarSentenciaInsert(){
        // Escapamos los caracteres
        $nombre = CGeneral::addSlashes(mb_strtolower($this->nombre_categoria)); 
        $descripcion = CGeneral::addSlashes($this->descripcion_categoria); 

        // Llamamos a la funcion privada de validarNombreCategoria
        if ($this->validarNombreCategoria(true) == false){
            // Realizamos el insert Categorias
            return "insert into categoria_producto (". 
                " nombre_categoria, descripcion_categoria, borrado". 
                " ) values ('$nombre', '$descripcion', false)"; 
        } // End if de la funcion privada
    } // End del metodo de insertar

    /**
     * Estamos redefiniendo el metodo fijarUpdate se encarga de actualizar una categoria_producto
     */
    protected function fijarSentenciaUpdate(){
        // Escapamos los caracteres
        $nombre = CGeneral::addSlashes($this->nombre_categoria);
        $descripcion = CGeneral::addSlashes($this->descripcion_categoria);
        $borrado = CGeneral::addSlashes($this->borrado);
        $cod_categoria = intval($this->cod_categoria_producto);
       
        // Llamamos a la funcion privada de validarNombreCategoria
        if ($this->validarNombreCategoria(false) == false){
            // Realizamos el update Categorias
            return "update categoria_producto set ". 
                " nombre_categoria = '$nombre', ". 
                " descripcion_categoria = '$descripcion', ". 
                " borrado = '$borrado' ".
                " where cod_categoria_producto = {$cod_categoria}"; 
        } // End if de la funcion privada
    } // End del metodo de sentencia de actualizar

    /**
     * Estamos definiendo el metodo de clase dameCategorias, se encarga de mostrar:
     *      1. Un array con todas las categoria_producto de la bd
     *      2. La descripcion de categoria_producto correspondiente 
     *      3. Boolean false cuando le pasemos un cod_categoria_producto y no exista
     *
     * @param integer|null $cod_categoria ----> Codigo de Categoria_Producto. La clave del array
     * @return array|string|false ----> ARRAY (Devolvemos el array de Categorias_Productos, cuando no le pasemos ningun cod_categoria_producto)
     *                                  STRING (Devolvemos la descripcion_categoria_producto, cuando el cod_categoria se encuentre en el array)
     *                                  FALSE (Devolvemos FALSE, si el cod_categoria no se encuentre en el array_categorias)
     */
    public static function dameCategorias_Productos (int $cod_categoria=null):array|string|false{
        // Definimos nuestras variables locales
        $categoria = new Categoria_Producto();
        $estados = $categoria->buscarTodos(["where" => "borrado = 0"]);
        $nombre_categoria = [];

        // Validamos si no hay ocurrido ningun error a la hora de obtener todas las categorias_Producos
        if (!$estados){
            return false;
        } // End if de validacion de estados

        // Guardamos las descripciones en un array
        foreach($estados as $fila => $valor){
            $nombre_categoria[$fila+1] = $estados[$fila]["nombre_categoria"];
        } // End foreach

        // Comprobamos si es nulo
        if (is_null($cod_categoria)){
            return $nombre_categoria;
        } // End if de nulos

        // Comprobamos de que exsita en el array categorias
        if (array_key_exists($cod_categoria, $nombre_categoria)){
            return $nombre_categoria[$cod_categoria];
        } // End if de exista en categorias

        // En caso de que no haya ninguna categoria, con ese codigo
        return false;
    } // End de la funcion de clase de Categorias_Productos

} // End del modelo Categoria_Producto

