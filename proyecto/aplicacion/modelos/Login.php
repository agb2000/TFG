<?php

/**
 * Estamos definiendo el modelo Login
 */
class Login extends CActiveRecord{

    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "Login";
    } // End de la funcion fijarNombre

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Login
     */
    protected function fijarAtributos(){
        return ["nick", "contrasenia"];
    } // End de la funcion fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "nick" => "Nick del Usuario",
            "contrasenia" => "Contrasenia del Usuario"
        ];
    } // End de la funcion fijarDescripciones

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "nick, contrasenia",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "nick, contrasenia",
                "TIPO" => "CADENA",
                "TAMANIO" => 120
            ],
            [
                "ATRI" => "contrasenia",
                "TIPO" => "FUNCION",
                "FUNCION" => "autentificar"
            ]
        ];
    } // End de la funcion de fijarRestricciones

    /**
     * Estamos redefiniendo el metodo afterCreate el cual se encarga de asignar valores por
     * defecto a los atributos correspondientes.
     */
    protected function afterCreate(){
        $this->nick = "";
        $this->contrasenia = "";
    } // End de la funcion de valores por defecto (afterCreate)

    /**
     * Estamos definiendo el metodo de autentificar el cual se encargar de comprobar a traves de la ACL BD
     * de que el nick del usuario y la contraseña asignada se encuentre en nuestra base de datos de la ACL.
     *
     */
    public function autentificar(){
        // Comprobamos de que el nick y la contraseña no este vacia
        if($this->nick != "" && $this->contrasenia != ""){
            // Comprobamos de que sea valida ese nick con esa contraseña sino nos saltaría un error
            if (Sistema::app()->ACL()->esValido($this->nick, $this->contrasenia)){
                Sistema::app()->Acceso()->registrarUsuario($this->nick, Sistema::app()->ACL()->getNombre(Sistema::app()->ACL()->getCodUsuario($this->nick)), 
                        Sistema::app()->ACL()->getPermisos(Sistema::app()->ACL()->getCodUsuario($this->nick)));
            } // End if 2
            else{
                $this->setError("contrasenia", "ERROR!! La contrasenia y el nick del usuario no coindicen");
            } // End else 2
        } // End if 1
    } // End de la funcion autentificar

} // End de la clase modelo login