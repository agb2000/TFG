<?php

/**
 * Estamos definiendo el modelo Comentarios
 */
class Comentarios extends CActiveRecord{

    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "comentario";
    } // End del metodo fijar Nombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la tabla comentarios de la base de datos
     */
    protected function fijarTabla(){
        return "cons_comentarios_espectaculos";
    } // End de la funcion fijarTabla

    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la tabla comentarios
     */
    protected function fijarId(){
        return "cod_comentarios";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Categoria_Espectaculo
     */
    protected function fijarAtributos(){
        return ["cod_comentarios", "cod_cliente", "cod_espectaculo", "comentario", "fecha_publicacion", "valoracion", "borrado"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_comentarios" => "Codigo de Comentarios",
            "cod_espectaculo" => "Nombre del Espectaculo",
            "cod_cliente" => "Nick del Cliente",
            "comentario" => "Introduzca un Comentario",
            "fecha_publicacion" => "Fecha de Publicacion",
            "valoracion" => "Indique la valoracion",
            "borrado" => "Borrado"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "comentario, valoracion, cod_cliente",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "cod_comentarios, cod_espectaculo",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "cod_espectaculo",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarEspectaculo"
            ],
            [
                "ATRI" => "cod_cliente",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarCliente"
            ],
            [
                "ATRI" => "comentario",
                "TIPO" => "CADENA",
                "TAMANIO" => "500",
                "MENSAJE" => "ERROR!! La longuitud maxima del nombre del foro es 500 caracteres"
            ],
            [
                "ATRI" => "fecha_publicacion",
                "TIPO" => "FECHA",
                "MENSAJE" => "ERROR!! Formato no Valido dd/mm/yyyy"
            ],
            [
                "ATRI" => "valoracion",
                "TIPO" => "ENTERO",
                "MIN" => 1,
                "MAX" => 5,
                "MENSAJE" => "ERROR!! Debes seleccionar indicar una valoracion del 1 al 5"
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
     * Estamos definiendo la funcion de validarEspectaculo donde comprobaremos que el codigo del espectaculo
     * que le pasamos exista en la base de datos de Espectaculos
     */
    public function validarEspectaculo(){
        // Definimos nuestras variables locales
        $cod_espectaculo = intval($this->cod_espectaculo);

        // Validamos de que el espectaculo exista 
        if (Espectaculo::dameEspectaculos($cod_espectaculo) == false){
            $this->setError("cod_espectaculo", "ERROR!! El espectaculo seleccionado no existe");
        } // End if de la validacion

    } // End de la funcion validarEspectaculo

    /**
     * Estamos definiendo el metodo validarCliente el cual se encarga de validarCliente de que el
     * usuario que le pasamos comprobamos de que exista en la tabla clientes
     */
    public function validarCliente(){
        // Definimos nuestras variables locales
        $cod_usuario = CGeneral::addSlashes($this->cod_cliente);
        $usuario = new Cliente();

        // Realizamos la busqueda de la sentencia para comprobar de que exista el nick del cliente
        $datos = $usuario->buscarTodos(["where" => "nick_cliente = '$cod_usuario'"]);

        // En caso de que no haya usuarios devolveremos un error
        if (!$datos){
            $this->setError("cod_cliente", "ERROR!! No hay usuario");
        } // End if 1
        else{
            // Escapamos los caracteres del nick
            $nombre = mb_strtolower(Sistema::app()->Acceso()->getNick());

            // Comprobamos de que el usuario registrado en la aplicacion sea igual al nick
            if ($nombre != mb_strtolower($datos[0]["nick_cliente"])){
                $this->setError("cod_usuario", "ERROR!! ERROR!! Con el nick del usuario");
            } // End if 2
            else{
                $this->cod_cliente = $datos[0]["cod_cliente"];
            } // End else 2
        } // End else 1

    } // En de la funcion validarCliente

    /**
     * Estamos definiendo el metodo afterCreate para asignarle valores por defecto a los atributos
     */
    protected function afterCreate() {
        $this->comentario = "";
        $this->borrado = 0;
        $this->fecha_creacion = date_format(date_create(), "Y-m-d");
    } // En de la funcion afterCreate

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar un Comentario
     */
    protected function fijarSentenciaInsert() {
        // Escapamos los caracteres
        $cod_espectaculo = intval($this->cod_espectaculo);
        $cod_cliente = intval($this->cod_cliente);
        $comentario = CGeneral::addSlashes(mb_strtolower($this->comentario));
        $fecha_publicacion = CGeneral::fechaNormalAMysql($this->fecha_publicacion);
        $valoracion = intval($this->valoracion);

        // Realizamos el insert Comentarios
        return "insert into comentarios (" .
            " cod_espectaculo, cod_cliente, comentario, fecha_publicacion, valoracion, borrado" .
            " ) values ('$cod_espectaculo', '$cod_cliente', '$comentario', '$fecha_publicacion', '$valoracion', false)";
    } // En de la funcion fijarSentenciaInsert
    
} // End del modelo Categoria Foro
