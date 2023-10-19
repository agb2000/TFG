<?php

/**
 * Estamos definiendo el modelo Sala
 */
class Sala extends CActiveRecord{
    
    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "Sala";
    } // End del metodo fijar Nombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la tabla sala de la base de datos
     */
    protected function fijarTabla(){
        return "sala";
    } // End de la funcion fijarTabla

    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la tabla sala
     */
    protected function fijarId(){
        return "cod_sala";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Categoria_Espectaculo
     */
    protected function fijarAtributos(){
        return ["cod_sala", "nombre_sala", "capacidad_maxima", "precio_sala","borrado"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_sala" => "Codigo de Sala",
            "nombre_sala" => "Nombre de Sala",
            "capacidad_maxima" => "Capacidad Maxima de Sala",
            "precio_sala" => "Precio de la Sala",
            "borrado" => "Borrado"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "nombre_sala, capacidad_maxima",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "cod_sala",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "capacidad_maxima",
                "TIPO" => "ENTERO",
                "MIN" => 20,
                "MAX" => 90,
                "MENSAJE" => "ERROR!! La capacidad minima es de 20 butacas y la capacidad maxima es de 90"
            ],
            [
                "ATRI" => "nombre_sala",
                "TIPO" => "CADENA",
                "TAMANIO" => "120",
                "MENSAJE" => "ERROR!! La longuitud maxima del nombre del foro es 120 caracteres"
            ],
            [
                "ATRI" => "precio_sala",
                "TIPO" => "REAL",
                "MIN" => 5,
                "MAX" => 70,
                "MENSAJE" => "ERROR!! El precio no puede ser inferior a 5€"
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
       $this->nombre_sala = "";
       $this->capacidad_maxima = 0;
       $this->borrado = 0; 
    } // End de la funcion de afterCreate

    /**
     * Estamos definiendo un metodo privado se encarga de comprobar si existe una sala con ese nombre
     * 
     * @param boolean $insert ---> True si estamos insertando y False no estamos insertando
     * @return boolean  TRUE ---> Si hay datos   
     *                  FALSE ---> No hay datos
     */
    private function validarNombreSala(bool $insert){
        // Validamos y ejecutamos la sentencia
        $nombre_sala = CGeneral::addSlashes(str_replace(' ', '', mb_strtolower($this->nombre_sala)));
        $sentencia = "Select * from sala where REPLACE(nombre_sala, ' ', '') = '$nombre_sala'";
        $cod_sala = intval($this->cod_sala);

        // Validamos la sentencia y establecemos el return
        if ($insert === true){
            if ($this->ejecutarSentencia($sentencia)) {
                $this->setError("nombre_sala", "ERROR!! El nombre de la sala ya existe");
                return true;
            } // End if de validacion
            return false;
        } // End if 1
        else{
            if ($this->ejecutarSentencia($sentencia)) {
                $sentencia .= " and cod_sala = '$cod_sala'";
                if ($this->ejecutarSentencia($sentencia)){
                    return false;
                } // Enf if 3
                $this->setError("nombre_sala", "ERROR!! El nombre de la sala ya existe");
                return true;
            } // End if 2
            return false;
        } // End else 1
    } // End de la funcion validarNombreSala

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar una Sala
     */
    protected function fijarSentenciaInsert(){
        // Escapamos los caracteres
        $nombre = CGeneral::addSlashes(mb_strtolower($this->nombre_sala));
        $capacidad = intval($this->capacidad_maxima);
        $precio_sala = floatval($this->precio_sala);

        // Comprobamos si existe o no esa sala con ese nombre para crear una Sala
        if ($this->validarNombreSala(true) == false){
            return "insert into sala (". 
                " nombre_sala, capacidad_maxima, precio_sala, borrado". 
                " ) values ('$nombre', '$capacidad', '$precio_sala', false)"; 
        } // End if de la funcion privada

    } // End de la funcion de fijar Insert

    /**
     * Estamos redefiniendo el metodo fijarUpdate se encarga de actualizar una Sala
     */
    protected function fijarSentenciaUpdate(){
        // Escapamos los caracteres
        $nombre = CGeneral::addSlashes(mb_strtolower($this->nombre_sala));
        $capacidad = intval($this->capacidad_maxima);
        $borrado = CGeneral::addSlashes($this->borrado);
        $cod_sala = intval($this->cod_sala);
        $precio_sala = floatval($this->precio_sala);
       
        // Llamamos a la funcion privada de validarNombreSala
        if ($this->validarNombreSala(false) == false){
            // Realizamos el update Categorias
            return "update sala set ". 
                " nombre_sala = '$nombre', ". 
                " capacidad_maxima = '$capacidad', ". 
                " precio_sala = '$precio_sala', ". 
                " borrado = '$borrado' ".
                " where cod_sala = {$cod_sala}"; 
        } // End if de la funcion privada

    } // End de la funcion de fijar Update

    /**
     * Estamos definiendo el metodo de clase dameSala, se encarga de mostrar:
     *      1. Un array con todas las salas de la bd
     *      2. El nombre de sala correspondiente con el cod_sala
     *      3. Boolean false cuando le pasemos un cod_sala y no exista
     *
     * @param integer|null $cod_sala ----> Codigo de Sala. La clave del array
     * @return array|string|false ----> ARRAY (Devolvemos el array de Salas, cuando no le pasemos ningun cod_sala)
     *                                  STRING (Devolvemos el nombre de la sala, cuando el cod_sala se encuentre en el array)
     *                                  FALSE (Devolvemos FALSE, si el cod_sala no se encuentre en el array_sala)
     */
    public static function dameSala (int $cod_sala=null):array|string|false{
        // Definimos nuestras variables locales
        $sala = new Sala();
        $filas = $sala->buscarTodos(["where" => "borrado = 0"]);
        $array_salas = [];

        // Validamos si no hay ocurrido ningun error a la hora de obtener todas las salas
        if (!$filas){
            return false;
        } // End if de validacion de estados

        // Guardamos el nombre de la sala en un array
        foreach($filas as $fila => $valor){
            $array_salas[$valor["cod_sala"]] = $valor["nombre_sala"];
        } // End foreach

        // Comprobamos si es nulo
        if (is_null($cod_sala)){
            return $array_salas;
        } // End if de nulos

        // Comprobamos de que exsita en el array sala
        if (array_key_exists($cod_sala, $array_salas)){
            return $array_salas[$cod_sala];
        } // End if de exista en sala

        // En caso de que no haya ninguna sala con ese codigo
        return false;
    } // End de la funcion de clase de dameSala

} // End del modelo Sala
