<?php

/**
 * Estamos definiendo el modelo Categoría de Clientes
 */
class Cliente extends CActiveRecord{

    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "Cliente";
    } // End del metodo fijar Nombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la vista cons_clientes de la base de datos
     */
    protected function fijarTabla(){
        return "cons_clientes";
    } // End de la funcion fijarTabla

    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria a la vista cons_clientes
     */
    protected function fijarId(){
        return "cod_cliente";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los atributos del modelo Cliente
     */
    protected function fijarAtributos(){
        return [
            "cod_cliente", "nombre_cliente", "apellidos_cliente", "nick_cliente", "nif_cliente",
            "fecha_nacimiento", "poblacion", "nombre_role", "contrasenia", "borrado"
        ];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion asignamos a cada atributo una descripcion
     */
    protected function fijarDescripciones(){
        return [
            "cod_cliente" => "Codigo del Cliente",
            "nombre_cliente" => "Nombre del Usuario",
            "apellidos_cliente" => "Apellidos del Usuario",
            "nick_cliente" => "Nick del Usuario",
            "nif_cliente" => "Dni del Usuario",
            "fecha_nacimiento" => "Fecha Nacimiento",
            "poblacion" => "Poblacion",
            "nombre_role" => "Nombre Role",
            "contrasenia" => "Contrasenia",
            "borrado" => "Borrado"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de fijar las restricciones a cada atributo.
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "nombre_cliente, apellidos_cliente, nick_cliente, nif_cliente, fecha_nacimiento, contrasenia",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "cod_cliente, nombre_role",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "nombre_cliente, apellidos_cliente",
                "TIPO" => "CADENA",
                "TAMANIO" => 60,
                "MENSAJE" => "ERROR!! LONGUITUD MAXIMA DE 60 CARACTERES EN NOMBRE Y APELLIDOS"
            ],
            [
                "ATRI" => "nick_cliente, poblacion",
                "TIPO" => "CADENA",
                "TAMANIO" => 32,
                "MENSAJE" => "ERROR! LONGUITUD MAXIMA DE 32 CARACTERES EN NICK O EN POBLACION"
            ],
            [
                "ATRI" => "nif_cliente",
                "TIPO" => "CADENA",
                "TAMANIO" => 9,
                "MENSAJE" => "ERROR! LONGUITUD MAXIMA DE 9 CARACTERES EN NIF"
            ],
            [
                "ATRI" => "nif_cliente",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarnif"
            ],
            [
                "ATRI" => "fecha_nac",
                "TIPO" => "DATE",
                "MENSAJE" => "ERROR!! FORMATO NO VALIDO DD/MM/YYYY"
            ],
            [
                "ATRI" => "fecha_nacimiento",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarfecha"
            ],
            [
                "ATRI" => "nombre_role",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarole"
            ],
            [
                "ATRI" => "contrasenia",
                "TIPO" => "CADENA",
                "TAMANIO" => 32,
                "MENSAJE" => "ERROR!! LONGUITUD MAXIMA DE 32 CARACTERES EN CONTRASENIA"
            ],
            [
                "ATRI" => "borrado",
                "TIPO" => "ENTERO",
                "MIN" => 0,
                "MAX" => 1
            ]
        ];
    } // End de la funcion de fijar restricciones

    /**
     * Estamos definiendo la funcion validarnif la cual se encarga de validar que el dni introducido
     * sea correcto o no. El dni esta formado por 8 numeros y 1 letra
     */
    public function validarnif(){
        // Realizamos una expresion regular para que el dni introducido sea correcto
        if (preg_match("/(\d{1,8})([A-Z]){1}/i", $this->nif_cliente) == false) {
            $this->setError("nif_cliente", "ERROR!! EL DNI INTRODUCIDO NO ES CORRECTO");
        } // End if de validar Dni
    } // End de la funcion de validarNif

    /**
     * Estamos definiendo la funcion validarFecha la cual se encarga de validar de que el cliente
     * tenga 18 años para poder registrarse en la pagina web
     */
    public function validarfecha(){
        // Comprobamos de que la fecha introducida no este vacia
        if ($this->fecha_nacimiento != "") {
            // Definimos nuestras variables locales
            $this->fecha_nacimiento = CGeneral::fechaNormalAMysql($this->fecha_nacimiento);
            $fecha =  date("Y-d-m", strtotime(date("d-m-Y") . "- 18 year"));

            // Hacemos una comprobacion para que sea amyor de edad
            if ($this->fecha_nacimiento > $fecha) {
                $this->setError("fecha_nacimiento", "ERROR!! NO TIENES 18 ANOS");
            } // End if 2
        } // End if 1
    } // End de la funcion de validarFecha

    /**
     * Estamos definiendo la funcion validarRole la cual se encarga de validar que el rol
     * seleccionado exista
     */
    public function validarole(){
        // Comprobamos de que exista el role seleccionado
        if (!array_key_exists($this->nombre_role, Sistema::app()->ACL()->dameRoles())) {
            $this->setError("nombre_role", "ERROR!! EL ROLE INTRODUCIDO NO EXISTE");
        } // End if 
    } // End de la funcion de validarRole

    /**
     * Estamos definiendo un metodo privado se encarga de comprobar si existe el nick
     * del cliente en la tabla CLIENTE.
     * 
     * @param boolean $insert ---> True si estamos insertando y False no estamos insertando
     * @return boolean  TRUE ---> Si hay datos   
     *                  FALSE ---> No hay datos
     */
    private function validarNick(bool $insert): bool{
        // Validamos y ejecutamos la sentencia
        $nick = CGeneral::addSlashes(str_replace(' ', '', mb_strtolower($this->nick_cliente)));
        $sentencia = "Select * from cliente where replace(lower(nick_cliente), ' ', '') = '$nick'";
        $cod_cliente = intval($this->cod_cliente);

        // Validamos la sentencia y establecemos el return
        if ($insert === true) {
            if ($this->ejecutarSentencia($sentencia)) {
                $this->setError("nick_cliente", "ERROR!! El nick introducido ya existe");
                return true;
            } // End if de validacion
            return false;
        } // End if
        else {
            if ($this->ejecutarSentencia($sentencia)) {
                $sentencia .= " and cod_cliente = '$cod_cliente'";
                if ($this->ejecutarSentencia($sentencia)) {
                    return false;
                } // End if 3
                else {
                    $this->setError("nick_cliente", "ERROR!! El nick introducido ya existe");
                    return true;
                } // End else 3
            } // End if de validacion
            return false;
        } // End else
    } // End de la funcion de validarNick

    /**
     * Estamos definiendo un metodo privado se encarga de comprobar si existe el nick
     * del cliente en la tabla ACL_USUARIOS.
     * 
     * @param boolean $insert ---> True si estamos insertando y False no estamos insertando
     * @return boolean  TRUE ---> Si hay datos   
     *                  FALSE ---> No hay datos
     */
    private function validarNick_ACL(string $nick, bool $insert): bool{
        // Validamos el nick para la ACL
        $nick = CGeneral::addSlashes(str_replace(' ', '', mb_strtolower($this->nick_cliente)));

        // Validamos la sentencia y establecemos el return
        if ($insert === true) {
            if (Sistema::app()->ACL()->existeUsuario($nick) == true){
                $this->setError("nick_cliente", "ERROR!! El nick introducido ya existe en la tabla ALC");
                return true;
            } // End if de la validacion
            return false;
        } // End if
        else {
            // Validamos el codigo del cliente e incrementamos el codigo por se encuentra el usuario admin en la ACL
            $cod_cliente = intval($this->cod_cliente + 1);

            // Comprobamos de que exista el nick del usuario en la ACL
            if (!Sistema::app()->ACL()->existeUsuario($nick)){
                return false;
            } // End if de la validacion

            // Obtenemos la comprobacion para obtener el nick del cliente en la ACL
            if (Sistema::app()->ACL()->getNick($cod_cliente) == $nick) {
                return false;
            } // End if de validacion

            // Si no devolvemos un error
            $this->setError("nick_cliente", "ERROR!! El nick introducido ya existe en la tabla ALC");
            return true;
        } // End else
    } // End de la funcion de validarNick en la acl

    /**
     * Estamos definiendo un metodo privado se encarga de comprobar si existe el dni
     * del cliente en la tabla CLIENTE.
     * 
     * @param boolean $insert ---> True si estamos insertando y False no estamos insertando
     * @return boolean  TRUE ---> Si hay datos   
     *                  FALSE ---> No hay datos
     */
    private function existe_nif(bool $insert): bool{
        // Validamos y ejecutamos la sentencia
        $nif = CGeneral::addSlashes($this->nif_cliente);
        $sentencia = "Select * from cliente where nif_cliente = '$nif'";
        $cod_cliente = intval($this->cod_cliente);

        // Validamos la sentencia y establecemos el return
        if ($insert === true) {
            if ($this->ejecutarSentencia($sentencia)) {
                $this->setError("nif_cliente", "ERROR!! El dni introducido ya existe");
                return true;
            } // End if de validacion
            return false;
        } // End if
        else{
            if ($this->ejecutarSentencia($sentencia)) {
                $sentencia .= " and cod_cliente = '$cod_cliente'";
                if ($this->ejecutarSentencia($sentencia)) {
                    return false;
                } // End if 3
                else {
                    $this->setError("nif_cliente", "ERROR!! El dni introducido ya existe");
                    return true;
                } // End else 3
            } // End if de validacion
            return false;
        } // End else
    } // End de la funcion de existe_nif

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar un Cliente
     */
    protected function fijarSentenciaInsert(){
        // Escapamos los caracteres
        $nombre = mb_strtolower(CGeneral::addSlashes($this->nombre_cliente));
        $apellidos = mb_strtolower(CGeneral::addSlashes($this->apellidos_cliente));
        $nick = mb_strtolower(CGeneral::addSlashes($this->nick_cliente));
        $dni = mb_strtoupper(CGeneral::addSlashes($this->nif_cliente));
        $fecha_nac = CGeneral::addSlashes(CGeneral::fechaNormalAMysql($this->fecha_nacimiento));
        $poblacion = CGeneral::addslashes($this->poblacion);
        $contrasenia = CGeneral::addSlashes(mb_strtolower($this->contrasenia));
        $role = intval($this->nombre_role);

        // Comprobamos si existe o no ese cliente con ese nombre y nick para poder crear un cliente
        if ($this->validarNick(true) == false && $this->existe_nif(true) == false && $this->validarNick_ACL($nick, true) == false) {
            // Añadimos el usuario cliente en la tabla ACL
            Sistema::app()->ACL()->anadirUsuario($nombre, $nick, $contrasenia, $role);

            // Realizamos el insert cliente
            return "insert into cliente (" .
                " nombre_cliente, apellidos_cliente, nick_cliente, nif_cliente, fecha_nacimiento, poblacion, borrado" .
                " ) values ('$nombre', '$apellidos', '$nick', '$dni', '$fecha_nac', '$poblacion', false)";
        } // End if de validacion de nick

    } // End de la funcion de fijar Insert

    /**
     * Estamos redefiniendo el metodo fijarUpdate se encarga de actualizar un Cliente
     */
    protected function fijarSentenciaUpdate(){
        // Definimos nuestras variables locales
        $cliente = new Cliente();

        // Escapamos los caracteres
        $cod_cliente = intval($this->cod_cliente);
        $nombre = mb_strtolower(CGeneral::addSlashes($this->nombre_cliente));
        $apellidos = mb_strtolower(CGeneral::addSlashes($this->apellidos_cliente));
        $nick_nuevo = mb_strtolower(CGeneral::addSlashes($this->nick_cliente));
        $dni = CGeneral::addSlashes(mb_strtoupper($this->nif_cliente));
        $fecha_nac = CGeneral::addSlashes(CGeneral::fechaNormalAMysql($this->fecha_nacimiento));
        $poblacion = CGeneral::addslashes($this->poblacion);
        $contrasenia = CGeneral::addSlashes(mb_strtolower($this->contrasenia));
        $role = intval($this->nombre_role);
        $borrado = intval($this->borrado);
        $nick = $cliente->buscarTodos(["where" => "cod_cliente = '$cod_cliente'"]);

        // Comprobamos si existe o no ese cliente con ese nombre y nick para poder actualizar un cliente
        if ($this->validarNick(false) == false && $this->existe_nif(false) == false && $this->validarNick_ACL($nick_nuevo, false) == false) {
            // Haremos una actualizacion del cliente en la tabla ACL
            Sistema::app()->ACL()->setContrasenia(Sistema::app()->ACL()->getCodUsuario($nick[0]["nick_cliente"]), $contrasenia);
            Sistema::app()->ACL()->setUsuarioRole(Sistema::app()->ACL()->getCodUsuario($nick[0]["nick_cliente"]), $role);
            Sistema::app()->ACL()->setBorrado(Sistema::app()->ACL()->getCodUsuario($nick[0]["nick_cliente"]), $borrado);
            Sistema::app()->ACL()->setNick(Sistema::app()->ACL()->getCodUsuario($nick[0]["nick_cliente"]), $nick_nuevo);

            // Realizamos el update Cliente
            return "update cliente set " .
                "nombre_cliente = '$nombre', " .
                "apellidos_cliente = '$apellidos', " .
                "nick_cliente = '$nick_nuevo', " .
                "nif_cliente = '$dni', " .
                "fecha_nacimiento = '$fecha_nac', " .
                "poblacion = '$poblacion', " .
                "borrado = '$borrado'" .
                "where cod_cliente = '$cod_cliente'";
        } // End if de comprobacion
    } // End de la funcion de fijar Update
    
} // End del modelo Cliente