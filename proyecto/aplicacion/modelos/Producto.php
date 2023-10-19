<?php

/**
 * Estamos definiendo el modelo Producto
 */
class Producto extends CActiveRecord{

    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "Producto";
    } // End del metodo fijar Nombre
    
    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la vista cons_productos_categorias de la base de datos
     */
    protected function fijarTabla(){
        return "cons_productos_categorias";
    } // End de la funcion fijarTabla
    
    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la vista cons_productos_categorias
     */
    protected function fijarId(){
        return "cod_producto";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Productos
     */
    protected function fijarAtributos(){
        return ["cod_producto", "cod_categoria_producto", "nombre_producto", "unidades", 
                "precio", "fecha_alta", "imagen", "borrado"];
    }// End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_producto" => "Codigo de Producto",
            "cod_categoria_producto" => "Codigo de Categoria",
            "nombre_producto" => "Nombre del Producto",
            "unidades" => "Unidades del Producto",
            "precio" => "Precio del Producto",
            "fecha_alta" => "Fecha Alta del Producto",
            "imagen" => "Imagen del Producto",
            "borrado" => "¿Esta borrado el Producto?"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "cod_categoria_producto, nombre_producto, precio, fecha_alta, imagen",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "cod_categoria_producto",
                "TIPO" => "ENTERO",
            ],
            [
                "ATRI" => "cod_categoria",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarCategoria"
            ],
            [
                "ATRI" => "nombre_producto",
                "TIPO" => "CADENA",
                "TAMANIO" => 60
            ],
            [
                "ATRI" => "unidades",
                "TIPO" => "ENTERO",
                "MIN" => 0,
                "DEFECTO" => 0,
                "MENSAJE" => "ERROR!! Las unidades del producto no puede ser negativo"
            ],
            [
                "ATRI" => "precio",
                "TIPO" => "REAL",
                "MIN" => 0,
                "MENSAJE" => "ERROR!! EL precio del producto no puede ser negativo"
            ],
            [
                "ATRI" => "fecha_alta",
                "TIPO" => "FECHA",
                "MENSAJE" => "ERROR!! Formato no Valido dd/mm/yyyy"
            ],
            [
                "ATRI" => "fecha_alta",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarFecha"
            ],
            [
                "ATRI" => "imagen",
                "TIPO" => "CADENA",
                "TAMANIO" => "600",
                "MENSAJE" => "ERRORR! La longuitud maxima de la imagen es de 600 caracteres"
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
     * Estamos definiendo el metodo de validarCategoria para comprobar si existe la categoria_producto
     */
    public function validarCategoria(){
        $cod_categoria = $this->cod_categoria_producto;
        if (Categoria_Producto::dameCategorias_Productos($cod_categoria) == false && is_null($cod_categoria) == true){
            $this->setError("cod_categoria_producto", "ERROR!! La categoria seleccionada no existe");
        } // End if
    } // End del metodo de validarCategoria

    /**
     * Estamos definiendo el metodo de validaFecha se encargar de validar la fecha
     */
    public function validarFecha(){
        // Establecemos el formato de la fecha de comparacion
        $this->fecha_alta = CGeneral::fechaNormalAMysql($this->fecha_alta);
        $fecha_inicio = date_format(date_create("01/01/2010"), "Y-m-d");
        $fecha_final = date_format(date_create(), "Y-m-d");

        // Comprobamos de que si la fecha inicio es mayor que la hemos introducido
        if ($this->fecha_alta < $fecha_inicio){
            $this->setError("fecha_alta", "ERROR!! La fecha no puede ser inferior a 01/01/2010");
        } // End if 1

        // Comprobamos de que si la fecha que hemos introducido es mayor que la fecha actual
        if ($this->fecha_alta > $fecha_final){
            $this->setError("fecha_alta", "ERROR!! La fecha no puede ser superior a la fecha_actual");
        } // End if 2

    } // End del metodo de validarFecha

    /**
     * Estamos redefiniendo el metodo afterCreate ese encarga de asignar valores por defecto a los atributos correspondientes.
     */
    protected function afterCreate(){
        $this->nombre_producto = "";
        $this->unidades = 0;
        $this->precio = 0;
        $this->fecha_alta = date_format(date_create(), "Y-m-d");
        $this->foto = "";
    } // End de la funcion de afterCreate

    /**
     * Estamos redefiniendo el metodo afterBuscar para realizar busqueda a la base de datos
     */
    protected function afterBuscar(){
        $this->nombre = CGeneral::addSlashes($this->nombre);
        $this->descripcion_categoria = CGeneral::addSlashes($this->descripcion_categoria);
        $this->borrado = CGeneral::addSlashes($this->borrado);
    } // End del metodo afterBuscar

    /**
     * Estamos definiendo un metodo privado se encarga de comprobar si existe un producto con ese nombre.
     * 
     * @param boolean $insert ---> True si estamos insertando y False no estamos insertando
     * @return boolean  TRUE ---> Si hay datos   
     *                  FALSE ---> No hay datos
     */
    private function validarNombreProducto(bool $insert): bool{
        // Validamos y ejecutamos la sentencia
        $nombre_producto = CGeneral::addSlashes(str_replace(' ', '', mb_strtolower($this->nombre_producto)));
        $sentencia = "Select * from producto where replace(lower(nombre_producto), ' ', '') = '$nombre_producto'";
        $cod_producto = intval($this->cod_producto);

        // Validamos la sentencia y establecemos el return
        if ($insert === true){
            if ($this->ejecutarSentencia($sentencia)) {
                $this->setError("nombre_producto", "ERROR!! El nombre del producto ya existe");
                return true;
            } // End if de validacion
            return false;
        } // End if 1
        else{
            if ($this->ejecutarSentencia($sentencia)) {
                $sentencia .= " and cod_producto = '$cod_producto'";
                if ($this->ejecutarSentencia($sentencia)) {
                    return false;
                } // End if 3
                    $this->setError("nombre_producto", "ERROR!! El nombre del producto ya existe");
                    return true;
            } // End if de validacion
            return false;
        } // End else 1
    } // End de la funcion de validarNombreProducto

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar un producto en la tabla productos
     */
    protected function fijarSentenciaInsert(){
        // Escapamos los caracteres
        $cod_categoria = intval($this->cod_categoria_producto);
        $nombre = CGeneral::addSlashes($this->nombre_producto); 
        $unidades = intval($this->unidades);
        $precio = floatval($this->precio);
        $fecha_alta = CGeneral::addSlashes($this->fecha_alta);
        $imagen = CGeneral::addSlashes($this->imagen);
        
        // Realizamos el insert Productos
        if ($this->validarNombreProducto(true) == false){
            return "insert into producto (". 
            " cod_categoria_producto, nombre_producto, unidades, precio, fecha_alta, imagen, borrado". 
            " ) values ('$cod_categoria', '$nombre', '$unidades', '$precio', '$fecha_alta',  '$imagen', false)"; 
        }
    } // End del metodo de insertar

    /**
     * Estamos redefiniendo el metodo fijarUpdate se encarga de actualizar un producto en la tabla productos
     */
    protected function fijarSentenciaUpdate(){
        // Escapamos los caracteres
        $cod_producto = intval($this->cod_producto);
        $cod_categoria = intval($this->cod_categoria_producto);
        $nombre = CGeneral::addSlashes($this->nombre_producto); 
        $unidades = intval($this->unidades);
        $precio = floatval($this->precio);
        $fecha_alta = CGeneral::fechaNormalAMysql($this->fecha_alta);
        $imagen = CGeneral::addSlashes($this->imagen);
        $borrado = CGeneral::addSlashes($this->borrado);
        
        // Realizamos el update Productos
        if ($this->validarNombreProducto(false) == false){
            return "update producto set ". 
                " cod_categoria_producto = '$cod_categoria', ". 
                " nombre_producto = '$nombre', ". 
                " unidades = '$unidades', ".
                " precio = '$precio', ".
                " fecha_alta = '$fecha_alta', ".
                " imagen = '$imagen', ".
                " borrado = '$borrado' ".
                " where cod_producto = {$cod_producto}"; 
        }
    } // End del metodo de sentencia de actualizar

} // End del modelo Productos