<?php

/**
 * Estamos definiendo el modelo Producto
 */
class Ventas_Productos extends CActiveRecord{

    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "Ventas_Productos";
    } // End del metodo fijar Nombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la vista cons_compras_productos de la base de datos
     */
    protected function fijarTabla(){
        return "cons_compras_productos";
    } // End de la funcion fijarTabla

    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la vista cod_ventas_productos
     */
    protected function fijarId(){
        return "cod_ventas_productos";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Productos
     */
    protected function fijarAtributos() {
        return ["cod_ventas_productos", "cod_producto", "cod_ventas", "unidades", "imagen", "nick_cliente", 
        "importe_base", "fecha", "importe_total", "nombre_producto"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_ventas_productos" => "Codigo de Ventas de Productos",
            "cod_producto" => "Codigo de Producto",
            "cod_ventas" => "Codigo de Ventas",
            "unidades" => "Unidades del Producto",
            "fecha" => "Fecha de la Compra",
            "nick_cliente" => "Nick del Cliente",
            "imagen" => "Imagen del Producto",
            "importe_base" => "Importe Base del Producto",
            "importe_total" => "Importe Total del Producto",
            "nombre_producto" => "Nombre del Producto"
        ];
    } // End de la accion de fijarDescripciones

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "cod_producto, cod_ventas, unidades, importe_base, importe_total",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "cod_ventas, cod_producto, unidades",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "nombre_producto",
                "TIPO" => "CADENA",
                "TAMANIO" => 120
            ],
            [
                "ATRI" => "importe_total, importe_base",
                "TIPO" => "REAL",
                "MIN" => 0
            ]
        ];
    } // End de la funcion de fijar restricciones

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar un producto en la tabla productos
     */
    protected function fijarSentenciaInsert(){
        $cod_producto = intval($this->cod_producto);
        $cod_ventas = intval($this->cod_ventas);
        $unidades = intval($this->unidades);
        $importe_base = floatval($this->importe_base);
        $importe_total = floatval($this->importe_total);

        return "insert into ventas_productos (".
            "cod_producto, cod_ventas, unidades, importe_base, importe_total".
            " ) values('$cod_producto', '$cod_ventas', '$unidades', '$importe_base', '$importe_total')";
    } // End de la accion de fijarInsert
    
} // End del modelo Ventas_Productos