<?php

/**
 * Estamos definiendo el modelo Participantes de Espectaculos
 */
class Part_Espe extends CActiveRecord{

    /**
     * Estamos redefiniendo el metodo fijarNombre, el cual le asignamos un nombre al modelo
     */
    protected function fijarNombre(){
        return "Part_Espe";
    } // End del metodo fijar Nombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la vista cons_participantes_espectaculos de la base de datos
     */
    protected function fijarTabla(){
        return "cons_participantes_espectaculos	";
    } // End de la funcion fijarTabla
   
    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la vista cons_participantes_espectaculos
     */
    protected function fijarId(){
        return "cod_participantes_espectaculos";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Part_Espe
     */
    protected function fijarAtributos(){
        return ["cod_participantes_espectaculos", "cod_participantes", "cod_espectaculo", "borrado"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion asignamos a cada atributo una descripcion
     */
    protected function fijarDescripciones(){
        return [
            "cod_participantes_espectaculos" => "Codigo de Participantes Espectaculo",
            "cod_participantes" => "Nombre de Participantes",
            "cod_espectaculo" => "Nombre de Espectaculos",
            "borrado" => "Borrado"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "cod_participantes_espectaculos, cod_participantes, cod_espectaculo",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "cod_participantes, cod_espectaculo",
                "TIPO" => "REQUERIDO",
            ],
            [
                "ATRI" => "cod_participantes",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarParticipantes"
            ],
            [
                "ATRI" => "cod_espectaculo",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarEspectaculo"
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
     * Estamos definiendo la comprobacion de que el actor añadido exista en la tabla PARTICIPANTES de nuestra base de datos
     */
    public function validarParticipantes(){
        // Escapamos nuestras variables
        $cod = intval($this->cod_participantes);

        // Comprobamos de que exista el PARTICIPANTE
        if (Participantes::dameParticipantes($cod) == false){
            $this->setError("cod_participantes", "ERROR!! El participante seleccionado no existe");
        } // End if de la validacion
    } // End del metodo validarParticipante

    /**
     * Estamos definiendo la comprobacion de que el espectaculo añadido exista en la tabla ESPECTACULOS de nuestra base de datos
     */
    public function validarEspectaculo(){
        // Escapamos nuestras variables
        $cod = intval($this->cod_espectaculo);

        // Comprobamos de que exista el ESPECTACULO
        if (Espectaculo::dameEspectaculos($cod) == false){
            $this->setError("cod_espectaculo", "ERROR!! El espectaculo seleccionado no existe");
        } // End if de la validacion
    } // End del metodo validarEspectaculo

    /**
     * Estamos redefiniendo el metodo afterCreate se encarga de asignar valores por defecto a los atributos.
     */
    protected function afterCreate(){
        $this->borrado = 0;
    } // End de la funcion de afterCreate

    /**
     * Estamos definiendo un metodo privado se encarga de comprobar si ese participante
     * esta asignado previamente al mismo espectaculo
     * 
     * @param boolean $insert ---> True si estamos insertando y False no estamos insertando
     * @return boolean  TRUE ---> Si hay datos   
     *                  FALSE ---> No hay datos
     */
    private function validarAsigancion(bool $insert): bool{
        // Validamos y ejecutamos la sentencia
        $cod_espe = intval($this->cod_espectaculo);
        $cod_parti = intval($this->cod_participantes);
        $nombre_parti = Participantes::dameParticipantes($cod_parti);
        $nombre_espe = Espectaculo::dameEspectaculos($cod_espe);
        $id = intval($this->cod_participantes_espectaculos);

        // Realizamos nuestra sentencia SQL
        $sentencia = "Select * from participantes_espectaculos where cod_participantes = '$cod_parti' and cod_espectaculo = '$cod_espe'";

        // Validamos la sentencia comprobamos si esta añadido ese participante a ese espectaculo
        if ($insert === true){
            if ($this->ejecutarSentencia($sentencia)) {
                $this->setError("cod_espectaculo", "ERROR!! '$nombre_parti' esta asignado al espectaculo: '$nombre_espe'");
                return true;
            } // End if de validacion
            return false;
        } // End if 1
        // A la hora de actualizar validamos si otro participante esta añadido al mismo espectaculo o el mismo
        else{
            if ($this->ejecutarSentencia($sentencia)){
                $sentencia .= " and cod_participantes_espectaculos = '$id'";
                if ($this->ejecutarSentencia($sentencia)) {
                    return false;
                } // End if 3
                else{
                    $this->setError("cod_espectaculo", "ERROR!! '$nombre_parti' esta asignado al espectaculo: '$nombre_espe'");
                    return true;
                } // End else 3
            } // End if 2
            return false;
        } // End else 2
    } // End de la funcion de validarAsigancion

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar un Participante Espectaculo
     */
    protected function fijarSentenciaInsert(){
        // Escapamos los caracteres
        $cod_espe = intval($this->cod_espectaculo);
        $cod_parti = intval($this->cod_participantes);

        // Llamamos a la funcion privada de validarAsignacion
        if ($this->validarAsigancion(true) == false){
            // Realizamos el insert Participantes Espectaculo
            return "insert into participantes_espectaculos (". 
                " cod_participantes, cod_espectaculo, borrado". 
                " ) values ('$cod_parti', '$cod_espe', false)"; 
        } // End if de la funcion privada
    } // End del metodo de insertar

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar un Participante Espectaculo
     */
    protected function fijarSentenciaUpdate(){
        // Escapamos los caracteres
        $cod_espe = intval($this->cod_espectaculo);
        $cod_parti = intval($this->cod_participantes);
        $borrado = CGeneral::addSlashes($this->borrado);
        $cod_participantes_espectaculos = intval($this->cod_participantes_espectaculos);
       
        // Llamamos a la funcion privada de validarAsignacion
        if ($this->validarAsigancion(false) == false){
            // Realizamos el update Participantes Espectaculo
            return "update participantes_espectaculos set ". 
                " cod_participantes = '$cod_parti', ".
                " cod_espectaculo = '$cod_espe', ".
                " borrado = '$borrado' ".
                " where cod_participantes_espectaculos = {$cod_participantes_espectaculos}"; 
        } // End if de la funcion privada
    } // End del metodo de sentencia de actualizar

} // End del modelo Participantes Espectaculos