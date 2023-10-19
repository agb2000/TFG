<?php

/**
 * Estamos definiendo el modelo Reservar
 */
class Reservar extends CActiveRecord{
    
    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "Reservar";
    } // End del metodo fijar Nombre
    
    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la vista cons_reservar de la base de datos
     */
    protected function fijarTabla(){
        return "cons_reservas";
    } // End de la funcion fijarTabla
    
    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la vista cons_productos_categorias
     */
    protected function fijarId(){
        return "cod_reserva";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Productos
     */
    protected function fijarAtributos(){
        return ["cod_reserva", "cod_sesion", "cod_cliente", "num_asientos", "precio_total"];
    }// End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_reserva" => "Codigo de Reserva",
            "cod_sesion" => "Codigo de Sesion",
            "cod_cliente" => "Codigo del Cliente",
            "num_asientos" => "Numero de Asientos",
            "precio_total" => "Precio Total"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "cod_sesion, cod_cliente, num_asientos, precio_total",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "cod_reserva, cod_sesion, num_asientos",
                "TIPO" => "ENTERO",
            ],
            [
                "ATRI" => "cod_sesion",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarSesion"
            ],
            [
                "ATRI" => "cod_cliente",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarCliente"
            ],
            [
                "ATRI" => "precio_total",
                "TIPO" => "REAL"
            ]
        ];
    } // End del metodo fijarRestricciones
    
    /**
     * Estamos comprobando de que exista la sesion para poder comprar una entrada
     */
    public function validarSesion(){
        // Definimos nuestras variables locales
        $cod_sesion = intval($this->cod_sesion);
        $sesion = new Sesiones();
        $filas = $sesion->buscarPorId($cod_sesion);

        // Validamos 
        if ($filas == false && is_null($cod_sesion) == true){
            $this->setError("cod_sesion", "ERROR!! La sesion seleccionada no existe");
        } // End if
        else{
            // Asignamos valores
            $espe_sala = new Espe_Sala();
            $cod_espe_sala = intval($sesion->cod_espe_sala);
            $filas = $espe_sala->buscarPorId($cod_espe_sala);

            if ($filas != false){
                $sala = new Sala();
                $cod_sala = intval($espe_sala->cod_sala);

                $filas = $sala->buscarPorId($cod_sala);

                if ($filas != false){
                    $this->precio_total = floatval($sala->precio_sala);
                } // End if 2
            } // End if 1
        } // End else
    } // End del metodo de validarCategoria
    
    /**
     * Estamos validando de que el cliente exista con ese nick en nuestra base de datos
     */
    public function validarCliente(){
        // Definimos nuestras variables locales
        $cod_cliente = CGeneral::addSlashes($this->cod_cliente);
        $cliente = new Cliente();
        $filas = $cliente->buscarPor(["where" => "nick_cliente = '$cod_cliente'"]);

        // Validamos
        if ($filas == false || is_null($cod_cliente) == true){
            $this->setError("cod_cliente", "ERROR!! El cliente registrado no existe");
        } // End if
        else{
            $this->cod_cliente = intval($cliente->cod_cliente);
        } // End else

    } // End del metodo de validarFecha

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar un producto en la tabla reservar
     */
    protected function fijarSentenciaInsert(){
        // Escapamos los caracteres
        $cod_sesion = intval($this->cod_sesion);
        $cod_cliente = intval($this->cod_cliente); 
        $num_asientos = intval($this->num_asientos);
        $precio = floatval($this->precio_total);
        
        // Realizamos el insert reservar
        return "insert into reservar (". 
            " cod_sesion, cod_cliente, num_asientos, precio_total". 
            " ) values ('$cod_sesion', '$cod_cliente', '$num_asientos', '$precio')"; 
    } // End del metodo de insertar

    /**
     * Estamos redefiniendo el metodo fijarUpdate se encarga de actualizar un producto en la tabla productos
     */
    protected function fijarSentenciaUpdate(){
        // // Escapamos los caracteres
        $cod_reserva = intval($this->cod_reserva);
        $cod_sesion = intval($this->cod_sesion);
        $cod_cliente = intval($this->cod_cliente); 
        $num_asientos = intval($this->num_asientos);
        $precio = floatval($this->precio_total);
        
        // // Realizamos el update Reservar
        return "update reservar set ". 
            " cod_reservar = '$cod_reserva', ". 
            " cod_sesion = '$cod_sesion', ". 
            " cod_cliente = '$cod_cliente', ".
            " num_asientos = '$num_asientos', ".
            " precio = '$precio' ".
            " where cod_reservar = {$cod_reserva}"; 
    } // End del metodo de sentencia de actualizar
    
} // End de la clase Reservar