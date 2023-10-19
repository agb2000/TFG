<?php

/**
 * Estamos definiendo el modelo Espectaculo Sala
 */
class Espe_Sala extends CActiveRecord {

    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "Espe_Sala";
    } // End del metodo fijar Nombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la vista cons_espe_sala de la base de datos
     */
    protected function fijarTabla(){
        return "cons_espe_sala";
    } // End de la funcion fijarTabla

    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la tabla Espe_Sala
     */
    protected function fijarId(){
        return "cod_espe_sala";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Categoria_Espectaculo
     */
    protected function fijarAtributos(){
        return ["cod_espe_sala", "cod_espectaculos", "cod_sala", "titulo", "nombre_sala", "capacidad_maxima", "borrado"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_espe_sala" => "Codigo de Espectaculo En Sala",
            "cod_espectaculos" => "Nombre del Espectaculo",
            "cod_sala" => "Asignacion de la Sala",
            "borrado" => "Borrado"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "cod_espectaculos, cod_sala, fecha",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "cod_espectaculos, cod_sala",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "cod_espectaculos",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarEspectaculo"
            ],
            [
                "ATRI" => "cod_sala",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarSala"
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
     * Estamos definiendo la validacion de Sala para comprobar de que sala exista
     */
    public function validarSala(){
        // Definimos nuestras variables locales
        $cod_sala = intval($this->cod_sala);

        // Comprobamos de que existe la sala
        if (Sala::dameSala($cod_sala) == false){
            $this->setError("cod_sala", "ERROR!! La sala seleccionada no existe");
        } // End if de la validacion

    } // End if de validarSala

    /**
     * Estamos definiendo la validacion de Espectaculo para comprobar de que el espectaculo exista
     */
    public function validarEspectaculo(){
        // Definimos nuestras variables locales
        $cod_espectaculo = intval($this->cod_espectaculos);

        // Validamos de que el espectaculo exista 
        if (Espectaculo::dameEspectaculos($cod_espectaculo) == false){
            $this->setError("cod_espectaculos", "ERROR!! El espectaculo seleccionado no existe");
        } // End if de la validacion

    } // End if de validarEspectaculo

    /**
     * Estamos definiendo un metodo privado se encarga de comprobar si existe esa sala esta asiganada a ese espectaculo
     * 
     * @param boolean $insert ---> True si estamos insertando y False no estamos insertando
     * @return boolean  TRUE ---> Si hay datos   
     *                  FALSE ---> No hay datos
     */
    private function validarEspeSala(bool $insert): bool{
        // Validamos y ejecutamos la sentencia
        $cod_sala = intval($this->cod_sala);
        $cod_espectaculo = intval($this->cod_espectaculos);
        $nombre_sala = Sala::dameSala($cod_sala);
        $nombre_espe = Espectaculo::dameEspectaculos($cod_espectaculo);
        $sentencia = "Select * from espe_sala where cod_sala = '$cod_sala' and cod_espectaculos = '$cod_espectaculo'";
        $id = intval($this->cod_espe_sala);

        // Validamos la sentencia y establecemos el return
        if ($insert === true){
            if ($this->ejecutarSentencia($sentencia)) {
                $this->setError("cod_espectaculos", "ERROR!! El espectaculo '$nombre_espe' esta asignado en $nombre_sala");
                return true;
            } // End if de validacion
            return false;
        } // End if 1
        else{
            if ($this->ejecutarSentencia($sentencia)) {
                $sentencia .= " and cod_espe_sala = '$id'";
                if ($this->ejecutarSentencia($sentencia)) {
                    return false;
                } // End if 3
                else{
                    $this->setError("cod_espectaculos", "ERROR!! El espectaculo '$nombre_espe' esta asignado en $nombre_sala");
                    return true;
                } // End else 3
            } // End if de validacion
            return false;
        } // End else 1
    } // End de la funcion de validarEspeSala

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar un Espectaculo a una Sala
     */
    protected function fijarSentenciaInsert() {
        // Escapamos los caracteres
        $cod_espectaculo = intval($this->cod_espectaculos);
        $cod_sala = intval($this->cod_sala);

        // Llamamos a la funcion privada de validarEspeSala
        if ($this->validarEspeSala(true) == false){
            return "insert into espe_sala (" .
                " cod_espectaculos, cod_sala, borrado" .
                " ) values ('$cod_espectaculo', '$cod_sala', false)";
        } // End if de la funcion privada
    } // End del metodo de insertar

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar un Espectaculo en una Sala
     */
    protected function fijarSentenciaUpdate(){
        // Escapamos los caracteres
        $cod_espectaculos = intval($this->cod_espectaculos);
        $cod_sala = intval($this->cod_sala);
        $borrado = CGeneral::addSlashes($this->borrado);
        $cod_espe_sala = intval($this->cod_espe_sala);
       
        // Llamamos a la funcion privada de validarEspeSala
        if ($this->validarEspeSala(false) == false){
            // Realizamos el update Participantes Espectaculo
            return "update espe_sala set ". 
                " cod_espectaculos = '$cod_espectaculos', ".
                " cod_sala = '$cod_sala', ".
                " borrado = '$borrado' ".
                " where cod_espe_sala = {$cod_espe_sala}"; 
        } // End if de la funcion privada
    } // End del metodo de sentencia de actualizar

    /**
     * Estamos definiendo el metodo de clase dameEspe_Sala, se encarga de mostrar:
     *      1. Un array con todas las espes_salas de la bd
     *      2. El nombre de sala correspondiente con el cod_espe_sala
     *      3. Boolean false cuando le pasemos un cod_espe_sala y no exista
     *
     * @param integer|null $cod_espe_sala ----> Codigo de Espe Sala. La clave del array
     * @return array|string|false ----> ARRAY (Devolvemos el array de Espe Sala, cuando no le pasemos ningun cod_espe_sala)
     *                                  STRING (Devolvemos el nombre de la sala concatenado a al titulo del espectaculo, cuando el cod_espe_sala se encuentre en el array)
     *                                  FALSE (Devolvemos FALSE, si el cod_espe_sala no se encuentre en el array_espe_salas)
     */
    public static function dameEspe_Sala (int $cod_espe_sala=null):array|string|false{
        // Definimos nuestras variables locales
        $espe_sala = new Espe_Sala();
        $estados = $espe_sala->buscarTodos(["where" => "borrado = 0"]);
        $array_espe_salas = [];

        // Validamos si no hay ocurrido ningun error a la hora de obtener todas las espes_salas
        if (!$estados){
            return false;
        } // End if de validacion de estados

        // Guardamos el titulo del espectaculo concatenado al nombre de la Sala
        foreach($estados as $fila => $valor){
            $array_espe_salas[$valor["cod_espe_sala"]] = $valor["titulo"]." ".$valor["nombre_sala"];
        } // End foreach

        // Comprobamos si es nulo
        if (is_null($cod_espe_sala)){
            return $array_espe_salas;
        } // End if de nulos

        // Comprobamos de que exsita en el array espe_salas
        if (array_key_exists($cod_espe_sala, $array_espe_salas)){
            return $array_espe_salas[$cod_espe_sala];
        } // End if de exista en espe_salas

        // En caso de que no haya ningun espe_salas con ese codigo
        return false;
    } // End de la funcion de clase de dameEspe_Sala

} // End del modelo Espe_Sala