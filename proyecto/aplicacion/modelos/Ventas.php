<?php

/**
 * Estamos definiendo el modelo Ventas
 */
class Ventas extends CActiveRecord{
    
    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "Ventas";
    } // End del metodo fijarNombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la tabla cons_compras de la base de datos
     */
    protected function fijarTabla() {
        return "cons_compras";
    } // End del metodo fijarTabla

    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la tabla cons_compras
     */
    protected function fijarId(){
        return "cod_ventas";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Categoria_Espectaculo
     */
    protected function fijarAtributos(){
        return ["cod_ventas", "cod_usuario", "nick_cliente", "fecha", "importe_total"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_ventas" => "Codigo de Ventas",
            "cod_usuario" => "Codigo de Usuario",
            "nick_cliente" => "Nombre de Usuario",
            "fecha" => "Fecha de Venta",
            "importe_total" => "Importe Total de la Cesta"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "cod_usuario",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarUsuario"
            ],
            [
                "ATRI" => "fecha",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarFecha"
            ],
            [
                "ATRI" => "cod_usuario, fecha",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "cod_ventas",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "fecha",
                "TIPO" => "DATE",
                "MENSAJE" => "ERROR!! Formato de la fecha erroneo dd/mm/yyyy"
            ],
            [
                "ATRI" => "importe_total",
                "TIPO" => "REAL",
                "MIN" => 1,
                "MENSAJE" => "ERROR!! No hay ningun producto en la cesta"
            ]
        ];
    } // End de la funcion de fijar restricciones

    /**
     * Estamos definiendo le metodo de validar fechar el cual se encarga de asignarle una fecha a la venta
     */
    public function validarFecha(){
        $this->fecha = date_format(date_create(), "Y-m-d");
    } // End de la funcion de validarFecha

    /**
     * Estamos definiendo el metodo validarUsuario el cual se encarga de validarUsuario de que el
     * usuario que le pasamos comprobamos de que exista en la tabla clientes
     */
    public function validarUsuario(){
        // Definimos nuestras variables locales
        $cod_usuario = CGeneral::addSlashes($this->cod_usuario);
        $usuario = new Cliente();

        // Realizamos la busqueda de la sentencia para comprobar de que exista el nick del cliente
        $datos = $usuario->buscarTodos(["where" => "nick_cliente = '$cod_usuario'"]);

        // En caso de que no haya usuarios devolveremos un error
        if (!$datos){
            $this->setError("cod_usuario", "ERROR!! No hay usuario");
        } // End if 1
        else{
            // Escapamos los caracteres del nick
            $nombre = mb_strtolower(Sistema::app()->Acceso()->getNick());

            // Comprobamos de que el usuario registrado en la aplicacion sea igual al nick
            if ($nombre != mb_strtolower($datos[0]["nick_cliente"])){
                $this->setError("cod_usuario", "ERROR!! ERROR!! Con el nick del usuario");
            } // End if 2
            else{
                $this->cod_usuario = $datos[0]["cod_cliente"];
            } // End else 2

        } // End else 1
    } // End de la funcion de validarUsuario

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar una VENTA
     */
    protected function fijarSentenciaInsert(){
        // Escapacamos los caracteres
        $cod_usuario = intval($this->cod_usuario);
        $fecha = date_format(date_create(), "Y-m-d");
        $importe = floatval($this->importe_total);

        // Realizamos una sentencia de insertar venta
        return "insert into ventas (" .
            " cod_usuario, fecha, importe_total" .
            " ) values ('$cod_usuario', '$fecha', '$importe')";
    } // End de la funcion de fijar Insert

} // End del modelo Venta