<?php

/**
 * Estamos definiendo el modelo Producto Sin Base de Datos
 */
class ProductoSinBD extends CActiveRecord{

    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "FacturaSinBd";
    } // End del metodo fijar Nombre

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo ProductoSinBD
     */
    protected function fijarAtributos(){
        return ["cod_producto", "nombre", "precio", "cantidad", "imagen"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_producto" => "Codigo de Producto",
            "nombre" => "Nombre de Producto",
            "precio" => "Precio de Producto",
            "cantidad" => "Unidades de Producto",
            "imagen" => "Imagen de Producto"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "cod_producto, cantidad",
                "TIPO" => "ENTERO",
                "MIN" => 0
            ],
            [
                "ATRI" => "nombre",
                "TIPO" => "CADENA",
                "MIN" => 60
            ],
            [
                "ATRI" => "precio",
                "TIPO" => "REAL",
                "MIN" => 0
            ],
            [
                "ATRI" => "cantidad",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarCantidad"
            ],
        ];
    } // End de la funcion restricciones

    /**
     * Estamos definiendo la validacion de Cantidad el cual se encarga de validar de que la cantidad del 
     * producto sea mayor o igual que 0. Pero nunca en negativo si estuviese en negativo nos saltaría un 
     * error.
     */
    public function validarCantidad(){
        // Definimos nuestras variables locales
        $producto = new Producto();
        $unidad = intval($this->cantidad);
        $id = intval($this->cod_producto);

        // Buscamos los datos de ese producto para sacarlos
        $datos = $producto->buscarTodos(["where" => "cod_producto = '$id'"]);

        // En caso de que no haya datos con ese cod_producto
        if (!$datos){
            $this->setError("cod_producto", "ERROR!! No hay datos con ese producto");
        } // End if 

        // Hacemos la comprobacion de unidades
        if ($datos[0]["unidades"] < $unidad){
            $this->setError("cantidad", "ERROR!! Has superado el limite maximo de unidades del ".$datos[0]["nombre_producto"]." que es ".$datos[0]["unidades"]);
        } // End if
    } // End del metodo validarCantidad

} // End del modelo ProductoSinBD