<?php

/**
 * Estamos definiendo el modelo Participantes
 */
class Participantes extends CActiveRecord{

    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "Participantes";
    } // End del FijarNombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la tabla participantes de la base de datos
     */
    protected function fijarTabla(){
        return "participantes";
    } // End del fijarTabla

    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la tabla participantes
     */
    protected function fijarId(){
        return "cod_participante";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Participantes
     */
    protected function fijarAtributos(){
        return ["cod_participante", "nombre_participante", "borrado"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_participante" => "Codigo del Participante",
            "nombre_participante" => "Nombre del Participante",
            "borrado" => "Borrado"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "cod_participante",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "nombre_participante",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "nombre_participante",
                "TIPO" => "CADENA",
                "TAMANIO" => "50",
                "MENSAJE" => "ERROR!! Has superado la longuitud máxima que es de 50 caracteres"
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
        $this->cod_participante = 0;
        $this->nombre_participante = "";
        $this->borrado = 0;
    } // End de la funcion de afterCreate

    /**
     * Estamos definiendo un metodo privado se encarga de comprobar si existe un nombre de participante con ese nombre
     * 
     * @param boolean $insert ---> True si estamos insertando y False no estamos insertando
     * @return boolean  TRUE ---> Si hay datos   
     *                  FALSE ---> No hay datos
     */
    private function validarParticipante(bool $insert): bool{
        // Validamos y ejecutamos la sentencia
        $participante = CGeneral::addSlashes(str_replace(' ', '', mb_strtolower($this->nombre_participante)));
        $sentencia = "Select * from participantes where replace(lower(nombre_participante), ' ', '') = '$participante'";
        $cod_participante = intval($this->cod_participante);

        // Validamos la sentencia y establecemos el return
        if ($insert === true){
            if ($this->ejecutarSentencia($sentencia)) {
                $this->setError("nombre_participante", "ERROR!! El nombre del participante ya existe");
                return true;
            } // End if de validacion
            return false;
        } // End if 1
        else {
            if ($this->ejecutarSentencia($sentencia)) {
                $sentencia .= " and cod_participante = '$cod_participante'";
                if ($this->ejecutarSentencia($sentencia)) {
                    return false;
                } // End if 3
                else {
                    $this->setError("nombre_participante", "ERROR!! El nombre del participante ya existe");
                    return true;
                } // End else 3
            } // End if de validacion
            return false;
        } // End else
    } // End de la funcion de validarParticipante

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar un participante
     */
    protected function fijarSentenciaInsert(){
        // Escapamos los caracteres
        $nombre = mb_strtolower(CGeneral::addSlashes($this->nombre_participante));

        // Comprobamos la validacion del nombre del Actor para insertar un PARTICIPANTE
        if ($this->validarParticipante(true) == false){
            // Realizamos el insert Categorias
            return "insert into participantes (". 
                " nombre_participante,  borrado". 
                " ) values ('$nombre', false)"; 
        } // End if de la validacionParticipante
    } // End de la funcion fijarSentenciaInsert 
  
    /**
     * Estamos redefiniendo el metodo fijarUpdate se encarga de actualizar un participante
     */
    protected function fijarSentenciaUpdate(){
        // Escapamos los caracteres
        $cod_participante = intval($this->cod_participante);
        $nombre = mb_strtolower(CGeneral::addSlashes($this->nombre_participante));
        $borrado = CGeneral::addSlashes($this->borrado);
         
        if ($this->validarParticipante(false) == false){
            // Llamamos a la funcion privada de validarNombreCategoria
            return "update participantes set ". 
                    " nombre_participante = '$nombre', ". 
                    " borrado = '$borrado' ".
                    " where cod_participante = {$cod_participante}"; 
        }
    } // End de a funcion fijarSetenciaUpdate

    /**
     * Estamos definiendo el metodo de clase dameParticipantes se encarga de mostrar los siguientes resultados:
     *      1. Un array con todas los Participantes de la base de datos
     *      2. El nombre de participantes correspondiente al cod_participante que se le pasa como parámetro
     *      3. Boolean FALSE cuando le pasemos un cod_participante y no exista
     *
     * @param integer|null $cod_participante ----> Codigo de Categoria de Participante. La clave del array
     * @return array|string|false ----> ARRAY (Devolvemos el array de Participantes, cuando no le pasemos ningun cod_participante)
     *                                  STRING (Devolvemos el nombre de Participante, cuando el cod_participante se encuentre en el array)
     *                                  FALSE (Devolvemos FALSE, si el cod_participante no se encuentre en el array_participantes)
     */
    public static function dameParticipantes(int $cod_participante=null):array|string|false{
        // Definimos nuestras variables locales
        $participantes = new Participantes();
        $nombre_participantes = $participantes->buscarTodos(["where" => "borrado = 0"]);
        $array_participantes = [];

        // Validamos si no hay ocurrido ningun error a la hora de obtener todos los participantes
        if (!$nombre_participantes){
            return false;
        } // End if de validacion de estados

        // Guardamos los nombres de los participantes en un array asociado por posicion que hacemos referencia al cod_participante suyo
        foreach($nombre_participantes as $fila => $valor){
            $array_participantes[$valor["cod_participante"]] = $valor["nombre_participante"];
        } // End foreach

        // Comprobamos si es nulo
        if (is_null($cod_participante)){
            return $array_participantes;
        } // End if de nulos

        // Comprobamos de que exsita en el array participantes
        if (array_key_exists($cod_participante, $array_participantes)){
            return $array_participantes[$cod_participante];
        } // End if de exista en categorias

        // En caso de que no haya ningun participante, con ese codigo
        return false;
    } // End de la funcion de clase de dameParticipantes

} // End del modelo Participantes 