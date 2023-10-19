<?php

/**
 * Estamos definiendo el modelo Contenido_Foro
 */
class Contenido_Foro extends CActiveRecord{

    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "ContenidoForo";
    } // End del metodo fijar Nombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la tabla contenido_foro de la base de datos
     */
    protected function fijarTabla(){
        return "cons_comentarios_foro";
    } // End de la funcion fijarTabla

    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la tabla Espe_Sala
     */
    protected function fijarId(){
        return "cod_contenido_foro";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Categoria_Espectaculo
     */
    protected function fijarAtributos(){
        return ["cod_contenido_foro", "cod_categoria_foro", "cod_cliente", "fecha_publi", "contenido_foro",  "borrado"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_categoria_foro" => "Nombre Categoria del Foro",
            "cod_cliente" => "Nick del Usuario",
            "fecha_publi" => "Fecha de Publicacion",
            "contenido_foro" => "Contenido para el Foro",
            "borrado" => "Borrado"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "cod_contenido_foro, cod_categoria_foro",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "cod_categoria_foro, cod_cliente, contenido_foro",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "cod_categoria_foro",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarCategoria"
            ],
            [
                "ATRI" => "cod_cliente",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarCliente"
            ],
            [
                "ATRI" => "fecha_publi",
                "TIPO" => "FECHA",
                "MENSAJE" => "ERROR!! Formato establecido dd/mm/yyyy"
            ],
            [
                "ATRI" => "contenido_foro",
                "TIPO" => "CADENA",
                "TAMANIO" => "800",
                "MENSAJE" => "ERROR!! El tamaño maximo del contenido_foro es de 800 caracteres"
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
        $this->fecha_publi = date_format(date_create(), "Y-m-d");
        $this->borrado = 0;
        $this->contenido_foro = "";
    } // End de la funcion de afterCreate

    /**
     * Estamos definiendo la funcion validarCategoria el cual se encarga de validar de que el codigo de categoria
     * foro que le pasamos exista en la base de datos de Categorias_Foro
     */
    public function validarCategoria(){
        // Escapamos el codigo de categoria foro
        $cod_categoria_foro = intval($this->cod_categoria_foro);

        // Validamos de que exista esa Categoria Foro
        if (!Categoria_Foro::dameCategorias_Foro($cod_categoria_foro)){
            $this->setError("cod_categoria_foro", "ERROR!! Esa categoria del foro no existe");
        } // End if de la validacion de Categoria Foro
    } // End de la funcion validarCategoria

    /**
     * Estamos definiendo el metodo de validarCliente el cual se encarga de validar de que el nick 
     * que le pasamos exista en la tabla Cliente de la base de datos.
     */
    public function validarCliente(){
        // Definimos nuestras variables locales
        $cod_cliente = CGeneral::addSlashes($this->cod_cliente);
        $usuario = new Cliente();

        // Realizamos la busqueda de la sentencia para comprobar de que exista el nick del cliente
        $datos = $usuario->buscarTodos(["where" => "nick_cliente = '$cod_cliente'"]);

        // En caso de que no haya usuarios devolveremos un error
        if (!$datos){
            $this->setError("cod_cliente", "ERROR!! No hay usuario");
        } // End if 1
        else{
            // Escapamos los caracteres del nick
            $nombre = mb_strtolower(Sistema::app()->Acceso()->getNick());

            // Comprobamos de que el usuario registrado en la aplicacion sea igual al nick
            if ($nombre != mb_strtolower($datos[0]["nick_cliente"])){
                $this->setError("cod_cliente", "ERROR!! ERROR!! Con el nick del usuario");
            } // End if 2
            else{
                $this->cod_cliente = $datos[0]["cod_cliente"];
            } // End else 2
        } // End else 1
    } // End de la funcion validarCliente

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar un Contenido_Foro
     */
    protected function fijarSentenciaInsert(){
        $cod_categoria_foro = intval($this->cod_categoria_foro);
        $cod_cliente = intval($this->cod_cliente);
        $contenido = CGeneral::addSlashes($this->contenido_foro);
        $fecha = date_format(date_create(), "Y-m-d");

        // Realizamos el insert Categorias
        return "insert into contenido_foro (". 
            " cod_categoria_foro, cod_cliente, contenido_foro, fecha_publicacion, borrado". 
            " ) values ('$cod_categoria_foro', '$cod_cliente', '$contenido', '$fecha', false)"; 
    } // End de la funcion SentenciaInsert

} // End del modelo Contenido Foro