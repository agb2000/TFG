<?php

/**
 * Estamos definiendo la clase ACLBaseDatos, el cual hereda de ACLBase. 
 * Almacenaremos los roles y los usuarios en Base de Datos
 */
class CACLBD extends CACLBase{

    // Definimos las variables de instancia
    private $_hayConeccion;
    private $_prefijo='_$"_';
    private CBaseDatos $_bd;
    
    /**
     * Estamos haciendo el constructor de la clase ACLBaseDatos. 
     * Hacemos la conexion a la Base de Datos
     * @param string $servidor ----> Nombre del Servidor 
     * @param string $usuario ----> Nombre del Usuario
     * @param string $contra ----> Contraseña del Usuario
     * @param string $bd ----> Nombre de la Base de Datos Acceder
     */
    public function __construct(string $servidor, string $usuario, string $contra, string $bd){
        $this->_hayConeccion = true;
        $this->_bd = new CBaseDatos($servidor, $usuario, $contra, $bd);
        if (!$this->_bd || $this->_bd->error()<>0)
            $this->_hayConeccion=false;
    } // End del constructor
    
    /**
     * Añade un role a nuesta ACL
     * 
     * @param string $nombre Nombre del role a añadir 
     * @param array $permisos Permisos que tendrá el role. Array con hasta 10 permisos
     * @return bool True si se ha podido crear, false en caso contrario
     */
    public function anadirRole(string $nombre, array $permisos = []):bool{
        // Comprobamos si hay conexion a la Base de Datos
        if (!$this->_hayConeccion)
            return false;
        
        // Estamos cambiando el nombre del role a minuscula
        $nombre=mb_substr(mb_strtolower($nombre),0,30);
        
        // Comprobamos de que si existe el role
        if ($this->existeNombreRole($nombre))
            return false;
        
        // Estamos asignando el array de permisos hasta 10 permisos
        for ($cont = 1; $cont<=10; $cont++) {
            if (isset($permisos[$cont])) {
                $Array_Permisos[$cont] = isset($permisos[$cont]) && (boolean)$permisos[$cont]?'1':'0';
            } // End if
            else {
                $Array_Permisos[$cont] = '0';
            } // End else 
        } // End for
        
        // Escapamos la cadena, para ser usada en el SQL
        $nombre = CGeneral::addSlashes($nombre);
        
        // Creamos la sentencia sql
        $sentencia="insert into acl_roles (".
            "     nombre, ".
            "    perm1, perm2,perm3,perm4,perm5,".
            "    perm6,perm7, perm8, perm9, perm10".
            "       ) values (".
            "     '$nombre', ".
            "    {$Array_Permisos[1]}, {$Array_Permisos[2]},".
            "    {$Array_Permisos[3]},{$Array_Permisos[4]},".
            "    {$Array_Permisos[5]}, {$Array_Permisos[6]},".
            "    {$Array_Permisos[7]}, {$Array_Permisos[8]},".
            "    {$Array_Permisos[9]}, {$Array_Permisos[10]}".
            "          )";
        
        // Ejecutamos la sentecia sql
        $resultado=$this->_bd->crearConsulta($sentencia);

        // Devolvemos true o false
        return $resultado == true;
    } // End del metodo añadir Role


    /**
     * Funcion privada para comprobar que un role (nombre) exite
     *
     * @param string $role role a comprobar
     * @return boolean devuelve true si existe, false en otro caso
     */
    private function existeNombreRole(string $role):bool{
        // Comprobamos si hay conexion
        if (!$this->_hayConeccion)
            return false;
        
        // Establecemos el nombre de role a minuscula
        $role=mb_substr(mb_strtolower($role),0,30);
            
        // Escribimos la sentencia sql
        $sentencia="select * from acl_roles where nombre='$role'";

        // Ejecutamos la sentencia sql
        $resultado=$this->_bd->crearConsulta($sentencia);

        // Comprobamos si la sentecia sql, se ha ejecutado correctamente
        if (!$resultado)
            return false;
            
        // Obtenemos en una fila el resultado
        $fila=$resultado->fila();
                
        // Comprobamos de que de $fila, sea distinto de false
        return ($fila!=false); 
    } // End del metodo existeNombreRole

    /**
     * Función que localiza un role y devuelve el código de ese role o false si no
     * lo encuentra. 
     * (FUNCION QUE ESTABA VACIA)
     * @param string $nombre nombre del role
     * @return integer|false Devuelve el código de role para el nombre indicado o false si no encuentra el role
     */
    public function getCodRole(string $nombre):int|false{
        // Comprobamos si existe Role
        if (!$this->existeNombreRole($nombre))
            return false;

        // Creamos la Sentencia SQL
        $sentencia = "select * from acl_roles where nombre='$nombre'";

        // Ejecutamos la Sentencia SQL
        $resultado =  $this->_bd->crearConsulta($sentencia);

        // Obtenemos la fila
        $fila = $resultado->fila();

        // Comprobamos si existe Role
        return (is_null($fila)) ? false : $fila["cod_acl_role"];
    } // End del metodo getCodRole

    /**
     * Función que comprueba la existencia de un role
     *
     * @param integer $codRole role a buscar
     * @return boolean Devuelve true si lo encuentra o false en caso contrario 
     */
    public function existeRole(int $codRole):bool{
        // Comprobamos si hay conexion establecida
        if (!$this->_hayConeccion)
            return false;
        
        // Parseamos el cod_role
        $codRole=(int)$codRole;
        
        // Realizamos la sentencia SQL
        $consulta = "SELECT * from acl_roles ".
                    "      where cod_acl_role = $codRole";

        // Obtenemos la fila de la sentencia de ejecucion
        $resul = $this->_bd->crearConsulta($consulta)->fila();

        // Comprobamos si el resultado de la ejecucion SQL sea nulo
        if(is_null($resul))
            return false;

        // En caso de haya valores en la fila
        return true;
    } // End del metodo existeRole

    /**
     * Función que devuelve los permisos de un role dado
     *
     * @param integer $codRole Role a buscar
     * @return array|false Devuelve los permisos o false si no encuentra el role
     */
    public function getPermisosRole(int $codRole):array|false{
        // Comprobamos de que haya conexion
        if (!$this->_hayConeccion)
            return false;
        
        // Parseamos el cod_role
        $codRole=(int)$codRole;

        // Comprobamos si existe el Role
        if (!$this->existeRole($codRole))
            return false;
            
        // Realizamos la sentencia sql
        $consulta = "SELECT `perm1`, `perm2`, `perm3`, `perm4`, `perm5`,".
                    "      `perm6`, `perm7`, `perm8`, `perm9`, `perm10` ".
                    "     FROM `acl_roles` ".
                    "     WHERE cod_acl_role = $codRole";
        
        // Ejecutamos la sentencia SQL
        $resul = $this->_bd->crearConsulta($consulta)->fila();

        // Creamos un array, donde alamacenaremos los valores true y false
        $perm=[];

        // Recorremos el bucle for y almacenamos valores true y false en el array
        for($cont=1;$cont<=10;$cont++)
            $perm[$cont] = (bool)$resul["perm".$cont];
        
        // Devolvemos el array de Permisos
        return ($perm);
    } // End del metodo getPermisosRole

    /**
     * Función que devuelve si un role tiene o no un permiso concreto
     * (FUNCION QUE ESTABA VACIA)
     * @param integer $codRole Role a buscar
     * @param integer $numero Número de permiso
     * @return boolean True si encuentra el role y lo tiene. False en cualquier otro caso
     */
    function getPermisoRole(int $codRole, int $numero):bool{
        // Comprobamos si existe el Role
        if (!$this->existeRole($codRole))
            return false;
        
        // Comprobamos si existe el numero del permiso en el array
        foreach($this->getPermisosRole($codRole) as $key => $value){
            // Comprobamos si el key == $numero
            if ($key == $numero)
                return true;
        } // End foreach
        return false;
    } // End del metodo getPermisoRole

    /**
     * Función que añade un nuevo usuario a nuestra ACL
     *
     * @param string $nombre Nombre del usuario
     * @param string $nick Nick unico para el usuario
     * @param string $contrasena contraseña del usuario
     * @param integer $codRole Role a asignarle
     * @return boolean Devuelve true si puede crearlo. False en caso contrario
     */
    public function anadirUsuario(string $nombre, string $nick, string $contrasena, int $codRole):bool{
        // Comprobamos si hay conexion a la BD
        if (!$this->_hayConeccion)
            return false;
        
        // Comprobamos si existe el Role
        if (!$this->existeRole($codRole))
            return false;
        
        // Establecemos el nick de usuario BD
        $nick=mb_strtolower($nick);
        
        // Comprobamos si existe el Usuario
        if ($this->existeUsuario($nick))    
            return false;
        
        // Escapamos los caracteres de la contraseña, nombre, nick para la sentencia SQL
        $contrasena=CGeneral::addSlashes($this->_prefijo.$contrasena);
        $nombre=CGeneral::addSlashes(mb_substr($nombre,0,50));
        $nick=CGeneral::addSlashes(mb_substr($nick,0,50));
        
        // Realizamos la sentecia SQL
        $consulta = "INSERT INTO  acl_usuarios (".
                    " nick,nombre,contrasenia,cod_acl_role,borrado".
                    "    ) VALUES (".
                    "'$nick', '$nombre', md5('$contrasena'), $codRole,false)";

        // Ejecutamos la sentencia SQL
        $resultado=$this->_bd->crearConsulta($consulta);

        // Devolvemos true o false
        return $resultado == true;
    } // End del metodo anadirUsuario
  
    /**
     * Obtiene el código de usuario para un nick dado
     *
     * @param string $nick nick del usuario a buscar
     * @return integer|false Devuelve el codigo del usuario o false si no lo encuentra
     */
    function getCodUsuario(string $nick):int|false{
        // Comprobamos si hay conexion
        if (!$this->_hayConeccion)
            return false;
        
        // Establecemos el nick a minuscula
        $nick=mb_strtolower($nick);
            
        // Realizamos la sentencia SQL
        $consulta = "SELECT cod_acl_usuario FROM acl_usuarios ".
                    "        WHERE nick = '$nick'";
        
        // Ejecutamos la sentencia SQL
        $resul = $this->_bd->crearConsulta($consulta)->fila();

        // Si no nos devuelve nada FALSE, y si nos devuelve algo cod_usuario
        return (is_null($resul)? false : intval($resul["cod_acl_usuario"]));
    } // End del metodo getCodUsuario

    /**
     * Verifica si existe un usuario dado un código
     * (FUNCION QUE ESTABA VACIA)
     * @param integer $codUsuario Código del usuario a verificar
     * @return boolean Devuelve si existe o no el usuario
     */
    function existeCodUsuario(int $codUsuario):bool{
        // Comprobamos si hay conexion
        if (!$this->_hayConeccion)
            return false;

        // Parseamos el codUsuario
        $codUsuario = intval($codUsuario);

        // Realizamos la sentencia SQL
        $sentencia = "Select cod_acl_usuario from acl_usuarios where cod_acl_usuario = '$codUsuario'";

        // Ejecutamos la sentencia SQL
        $fila = $this->_bd->crearConsulta($sentencia)->fila();

        // Si no nos devuelve nada FALSE, y si nos devuelve algo existe el codUsuario
        return is_null($fila)? false : true;
    } // End del metodo existeCodUsuario

    /**
     * Verifica si existe o no un usuario con el nick dado
     *
     * @param string $nick Nick del usuario a comprobar
     * @return boolean Devuelve true si encuentra el usuario y 
     * false en caso contrario
     */
    function existeUsuario(string $nick):bool{
        // Comprobamos si el nick esta asociado a un cod_usuario, significa que esta introducido en la base de datos
        if ($this->getCodUsuario($nick)!== false)
            return true;

        // No esta introducido en la base de datos
        return false;
    } // End del metodo existeUsuario

    /**
     * Función que comprueba que existe un usuario y la contraseña indicada es la correcta
     * (FUNCION QUE ESTABA VACIA)
     * @param string $nick Nick del usuario a comprobar
     * @param string $contrasena Contraseña del usuario a comprobar
     * @return boolean Devuelve true si existe el usuario y tiene la contraseña indicada. 
     * False en otro caso
     */
    function esValido(string $nick, string $contrasena):bool{
        // Parseamos el nick a minuscula
        $nick = mb_strtolower($nick);

        // Comprobamos si existe Usuario
        if (!$this->existeUsuario($nick))
            return false;

        // Escapamos los caracteres para la sentencia SQL
        $nick = CGeneral::addSlashes($nick);
        $contrasena = CGeneral::addSlashes($this->_prefijo.$contrasena);

        // Realizamos la sentencia SQL
        $sentencia = "Select * from acl_usuarios where nick='$nick'
                and borrado = 0 and contrasenia = md5('$contrasena')";

        // Ejecutamos la sentencia SQL
        $fila = $this->_bd->crearConsulta($sentencia)->fila();

        // Devolvemos si existe usuario
        return is_null($fila)? false : true;
    } // End del metodo esValido


    /**
     * Función que comprueba si un usuario tiene un permiso concreto
     *
     * @param integer $codUsuario Usuario a buscar
     * @param integer $numero Permiso a buscar
     * @return boolean Devuelve true si existe el usuario y tiene el permiso. 
     * False en otro caso
     */
    function getPermiso(int $codUsuario, int $numero):bool{
        // Obtenemos el array de permisos que tiene el usuario
        $resul = $this->getPermisos($codUsuario);

        // Comprobamos tiene permisos ese usuario o si existe el usuario
        if ($resul===false)
            return false;

        // Devolvemos permiso del usuario en especifico
        return $resul[$numero];
    } // End del metodo getPermiso

    /**
     * Función que devuelve los permisos de un usuario
     *
     * @param integer $codUsuario Usuario a buscar
     * @return array|false Devuelve los permisos del usuario o false si 
     * no existe el usuario
     */
    function getPermisos(int $codUsuario):array|false{
        // Comprobamos si hay conexion
        if (!$this->_hayConeccion)
            return false;

        // Comprobamos si existe el codUsuario
        if (!$this->existeCodUsuario($codUsuario))
            return false;

        // Obtenemos el cod_role del usuario
        if (!$this->getUsuarioRole($codUsuario))
            return false;

        // Obtenemos el cod_role del usuario
        $cod_role_usuario = $this->getUsuarioRole($codUsuario);

        // Obtenemos los permisos del usuario
        return $this->getPermisosRole($cod_role_usuario);
    } // End del metodo getPermisos

    /**
     * Función que devuelve el nombre de un usuario
     *
     * @param integer $codUsuario Usuario a buscar
     * @return string|false Devuelve el nombre del usuario o false si no existe
     */
    function getNombre(int $codUsuario):string|false{
        // Comprobamos si hay conexion
        if (!$this->_hayConeccion)
            return false;
            
        // Parseamos el cod_usuario
        $codUsuario=intval($codUsuario);
        
        // Realizamos la sentencia SQL
        $consulta = "SELECT nombre FROM acl_usuarios ".
                    "    WHERE cod_acl_usuario = $codUsuario";

        // Ejecutamos la sentencia SQL
        $resul = $this->_bd->crearConsulta($consulta)->fila();

        // Comprobamos si hay valores o no
        if (is_null($resul))
            return false;

        // Devolvemos nombre
        return $resul["nombre"];
    } // End del metodo getNombre

    /**
     * Devuelve si un usuario está borrado
     *
     * @param integer $codUsuario Usuario a buscar.
     * @return boolean true si el usuario existe y no está borrado.
     * False en otro caso
     */
    function getBorrado(int $codUsuario):bool{
        // Comprobamos si hay conexion
        if (!$this->_hayConeccion)
            return false;
        
        // Parseamos el cod_usuario
        $codUsuario=intval($codUsuario);
        
        // Realizamos la sentencia SQL
        $consulta = "SELECT borrado FROM acl_usuarios ".
                    "    WHERE cod_acl_usuario = $codUsuario";

        // Ejecutamos la sentencia SQL
        $resul = $this->_bd->crearConsulta($consulta)->fila();

        // Comprobamos si hay valores
        if (is_null($resul))
            return false;

        // Devuelve true si esta borrado, false si no esta borrado
        return boolval($resul["borrado"]);
    } // End del metodo getBorrado

        /**
     * Devuelve si un usuario está borrado
     *
     * @param integer $codUsuario Usuario a buscar.
     * @return boolean true si el usuario existe y no está borrado.
     * False en otro caso
     */
    function getNick(int $codUsuario):bool|string{
        // Comprobamos si hay conexion
        if (!$this->_hayConeccion)
            return false;
        
        // Parseamos el cod_usuario
        $codUsuario=intval($codUsuario);
        
        // Realizamos la sentencia SQL
        $consulta = "SELECT nick FROM acl_usuarios ".
                    "    WHERE cod_acl_usuario = $codUsuario";

        // Ejecutamos la sentencia SQL
        $resul = $this->_bd->crearConsulta($consulta)->fila();

        // Comprobamos si hay valores
        if (is_null($resul))
            return false;

        // Devuelve true si esta borrado, false si no esta borrado
        return $resul["nick"];
    } // End del metodo getBorrado

    /**
     * Devuelve el role que tiene un usuario concreto
     * (FUNCION QUE ESTABA VACIA)
     * @param integer $codUsuario Usuario a buscar
     * @return integer|false Devuelve el role del usuario o false si no existe.
     */
    function getUsuarioRole(int $codUsuario):int|false{
        // Comprobamos si hay conexion
        if (!$this->_hayConeccion)
            return false;

        // Comprobamos si existe el usuario
        if (!$this->existeCodUsuario($codUsuario))
            return false;

        // Parseamos el cod_usuario
        $codUsuario = intval($codUsuario);

        // Realizamos la sentencia SQL
        $sentencia = "Select cod_acl_role from acl_usuarios ".
            "where cod_acl_usuario = '$codUsuario'";
        
        // Ejecutamos la sentencia
        $fila = $this->_bd->crearConsulta($sentencia)->fila();

        // Devolvemos el resultado
        return is_null($fila)? false : $fila["cod_acl_role"];
    } // End del metodo getUsuarioRole

    /**
     * Función que asigna un nombre a un usuario
     *
     * @param integer $codUsuario Usuario a buscar
     * @param string $nombre Nombre a asignar
     * @return boolean Devuelve true si ha podido asignar el nombre, false en otro caso
     */
    function setNombre(int $codUsuario,string $nombre):bool{
        // Comprobamos si hya conexion
        if (!$this->_hayConeccion)
            return false;
        
        // Parseamos el cod_usuario
        $codUsuario=intval($codUsuario);
        
        // Escapamos los caracteres de nombre, para sentencia SQL
        $nombre=CGeneral::addSlashes(mb_substr($nombre,0,50));
            
        // Realizamos la consulta
        $consulta = "UPDATE acl_usuarios SET nombre = '$nombre' ".  
                    "    WHERE cod_acl_usuario = '$codUsuario'";
                    
        // Ejecutamos la sentencia SQL
        $this->_bd->crearConsulta($consulta);

        // Devovlemos true
        return true;
    } // End del metodo setNombre

    /**
     * Undocumented function
     *
     * @param integer $codUsuario
     * @param string $nick
     * @return boolean
     */
    function setNick(int $codUsuario, string $nick):bool{
        // Comprobamos si hya conexion
        if (!$this->_hayConeccion)
            return false;
        
        // Parseamos el cod_usuario
        $codUsuario=intval($codUsuario);
        
        // Escapamos los caracteres de nombre, para sentencia SQL
        $nombre=CGeneral::addSlashes(mb_substr($nick,0,50));
            
        // Realizamos la consulta
        $consulta = "UPDATE acl_usuarios SET nick = '$nick' ".  
                    "    WHERE cod_acl_usuario = '$codUsuario'";
                    
        // Ejecutamos la sentencia SQL
        $this->_bd->crearConsulta($consulta);

        // Devovlemos true
        return true;
    } // End del metodo setNombre


    /**
     * Función que asigna una contraseña a un usuario
     *
     * @param integer $codUsuario usuario a buscar
     * @param string $contrasenia contraseña a asignar
     * @return boolean Devuelve true si ha podido asignar la contraseña
     * False en otro caso
     */
    function setContrasenia(int $codUsuario, string $contrasenia):bool{
        // Comprobamos si hay conexion
        if (!$this->_hayConeccion)
            return false;
            
        // Parseamos el codUsario
        $codUsuario=intval($codUsuario);

        // Escapamos la contrasena, para el SQL
        $contrasenia=CGeneral::addSlashes($this->_prefijo.$contrasenia);

        // Realizamos la sentencia SQL        
        $consulta = "UPDATE acl_usuarios SET contrasenia = md5('$contrasenia') ".
                    "    WHERE cod_acl_usuario = '$codUsuario'";

        // Ejecutamos la sentencia SQL
        $this->_bd->crearConsulta($consulta);
        
        // Devolvemos true
        return true;
    } // End del metodo setContrasenia

    /**
     * Función que borra/desborra lógicamente un usuario 
     *
     * @param integer $codUsuario Usuario a buscar
     * @param boolean $borrado Estado a asignar
     * @return boolean Devuelve true si ha podido asignar el estado. 
     * False en otro caso
     */
    function setBorrado(int $codUsuario, bool $borrado):bool{
        // Comprobamos si hay conexion
        if (!$this->_hayConeccion)
            return false;

        // Obtenemos true o false
        $borrado=$borrado?"1":"0";

        // Realizamos la consulta SQL
        $consulta = "UPDATE acl_usuarios SET borrado = '$borrado' ".
                    "    WHERE cod_acl_usuario  = '$codUsuario'";

        // Ejecutamos la sentencia SQL
        $this->_bd->crearConsulta($consulta);

        // Devolvemos true
        return true;
    } // End del metodo setBorrado

    /**
     * Función que cambia el role de un usuario
     *
     * @param integer $codUsuario Usuario a buscar
     * @param integer $role Role a asignar
     * @return boolean Devuelve true si ha podido asignar el role al usuario.
     * False si no existe el usuario, role o no ha podido asignarlo
     */
    function setUsuarioRole(int $codUsuario, int $role):bool{
        // Comprobamos si hay conexion
        if (!$this->_hayConeccion)
            return false;

        // Comprobamos si existe cod_usuario
        if (!$this->existeCodUsuario($codUsuario))
            return false;

        // Comprobamos si existe role 
        if (!$this->existeRole($role))
            return false;

        // Realizamos la consulta SQL
        $consulta = "UPDATE acl_usuarios SET cod_acl_role = '$role'".
            " where cod_acl_usuario = '$codUsuario'";

        // Ejecutamos la sentencia SQL
        $this->_bd->crearConsulta($consulta);

        // Devolvemos true
        return true;
    } // End del metodo setUsuarioRole


    /**
     * Devuelve un array con todos los usuarios existentes. 
     * La clave es el codigo de usuario, el valor es el nick del usuario 
     *
     * @return array Array con todos los usuarios existentes
     */
    function dameUsuarios():array{
        // Comprobamos si hay conexion
        if (!$this->_hayConeccion)
            return false;
        
        // Realizamos la consulta SQL
        $consulta = "SELECT cod_acl_usuario, nick ".
                    "      from acl_usuarios ".
                    "ORDER BY cod_acl_usuario";

        // Ejecutamos la sentencia SQL
        $datos = $this->_bd->crearConsulta($consulta);

        $res = [];

        // Recorremos los datos de la base de datos y lo almacenamos en un array
        while($fila = $datos->fila())
            $res[(int)$fila["cod_acl_usuario"]]=$fila["nick"];
        
        // Devolvemos el array
        return $res;
    } // End del metodo dameUsuarios

    /**
     * Devuelve un array con todos los roles existentes. 
     * La clave es el codigo de role, el valor es el nombre del role 
     *
     * @return array Array con todos los roles existentes
     */
    function dameRoles():array{
        // Comprobamos si hay conexion
        if (!$this->_hayConeccion)
            return false;
     
        // Realizamos la consulta SQL
        $consulta = "SELECT cod_acl_role, nombre ".
                    "      from acl_roles ".
                    "ORDER BY cod_acl_role";

        // Ejecutamos la sentencia SQL
        $datos = $this->_bd->crearConsulta($consulta);

        $res = [];

        // Recorremos los datos de la base de datos y lo almacenamos en un array
        while($fila=$datos->fila())
            $res[(int)$fila["cod_acl_role"]]=$fila["nombre"];
     
        // Devolvemos el array
        return $res;
    } // End del metodo dameRole

} // End del metodo ACLBaseDatos

?>