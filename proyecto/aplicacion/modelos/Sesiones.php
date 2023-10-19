<?php

/**
 * Estamos definiendo el modelo Sesiones
 */
class Sesiones extends CActiveRecord{
    
    /**
     * Estamos redefiniendo el metodo fijarNombre le asignaremos un nombre al modelo
     */
    protected function fijarNombre(){
        return "Sesion";
    } // End del metodo fijarNombre

    /**
     * Estamos redefiniendo el metodo fijarTabla se encarga enlazar a la vista cons_sesiones de la base de datos
     */
    protected function fijarTabla(){
        return "cons_sesiones";
    } // End del metodo fijarTabla

    /**
     * Estamos redefiniendo el metodo fijarId se encarga de conectar con la clave primaria de la tabla cons_sesiones
     */
    protected function fijarId(){
        return "cod_sesion";
    } // End de la funcion fijarId

    /**
     * Estamos redefiniendo el metodo fijarAtributos devuelve un array con todos los campos para el modelo Categoria_Espectaculo
     */
    protected function fijarAtributos(){
        return ["cod_sesion", "cod_espe_sala", "fecha", "hora_inicio", "hora_fin", "borrado"];
    } // End del metodo fijarAtributos

    /**
     * Estamos redefiniendo el metodo fijarDescripcion se encarga de asignarlos una descripción a los campos
     */
    protected function fijarDescripciones(){
        return [
            "cod_sesion" => "Codigo de la Sesion",
            "cod_espe_sala" => "Codigo de Espectaculo_Sala",
            "hora_inicio" => "Hora Inicio",
            "hora_fin" => "Hora Fin",
            "fecha" => "Fecha de Publicacion",
            "borrado" => "Borrado"
        ];
    } // End del metodo fijarDescripcion

    /**
     * Estamos redefiniendo el metodo fijarRestriccion se encarga de establecer restricciones a cada campo definido en el fijarAtributos
     */
    protected function fijarRestricciones(){
        return [
            [
                "ATRI" => "cod_espe_sala, hora_inicio, hora_fin, fecha",
                "TIPO" => "REQUERIDO"
            ],
            [
                "ATRI" => "cod_sesion, cod_espe_sala",
                "TIPO" => "ENTERO"
            ],
            [
                "ATRI" => "cod_espe_sala",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarEspe_Sala"
            ],
            [
                "ATRI" => "hora_inicio, hora_fin",
                "TIPO" => "HORA"
            ],
            [
                "ATRI" => "hora_fin",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarHora_Fin"
            ],
            [
                "ATRI" => "fecha",
                "TIPO" => "FECHA",
                "MENSAJE" => "ERROR!! Formato no Valido dd/mm/yyyy"
            ],
            [
                "ATRI" => "fecha",
                "TIPO" => "FUNCION",
                "FUNCION" => "validarFecha"
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
     * Estamos definiendo el metodo afterCreate para asignarle valores por defecto a los atributos
     */
    protected function afterCreate(){
        $this->fecha = date_format(date_create(), "Y-m-d");
    } // End de la funcion afterCreate

    /**
     * Estamos definiendo le metodo de validar fechar el cual se encarga de validar de que la fecha introducida
     * se encuentre en el intervalo de fecha entre la fecha de lanzamiento y la fecha de finalizacion
     */
    public function validarFecha(){
        // Definimos nuestras variables locales 
        $espe_sala = new espe_sala();

        // Escapamos los caracteres
        $cod_espectaculo = intval($this->cod_espe_sala);

        // Validamos de que el cod_espectaculo sea mayor que 0
        if ($cod_espectaculo > 0){
            // Escapamos la fecha
            $fecha = CGeneral::fechaNormalAMysql($this->fecha);

            // Realizamos la sentencia de busqueda
            $datos = $espe_sala->buscarTodos(["where" => "cod_espe_sala = '$cod_espectaculo'"]);
    
            // Validamos los datos de la busqueda de la sentencia
            if (!$datos){
                $this->setError("fecha", "ERROR!! La fecha que has introducido no es correcta debe encontrarse en el intervalo de tiempo 
                    entre la fecha_lanzamiento y la fecha_finalizacion");
                $this->fecha = $fecha;
            } // End if de la validacion
        } // End if de cod_espectaculo > 0
    } // End de la validacion de fecha

    /**
     * Estamos definiendo la funcion validarEspe_Sala la cual se encarga de validar de que el codigo Espectaculo Sala exista en la base de datos
     */
    public function validarEspe_Sala(){
        // Definimos nuestras variables locales y escapamos los caracteres
        $espe_sala = new Espe_Sala();
        $cod_espe_sala = intval($this->cod_espe_sala);

        // Comprobamos de que el codigo espectaculo sala exista
        if (!$espe_sala->buscarPorId($cod_espe_sala)){
            $this->setError("cod_espe_sala", "ERROR!! El espectaculo en sala seleccionado no existe");
        } // End if de la validacion del codigo espectaculo sala
    } // End de la funcion validarEspe_Sala

    /**
     * Estamos definiendo la funcion validarHora_Fin la cual se encarga de validar de que la hora inicio sea mayor que la hora fin
     */
    public function validarHora_Fin(){
        // Validamos las cadenas
        $hora_inicio = CGeneral::addSlashes($this->hora_inicio);
        $hora_fin = CGeneral::addSlashes($this->hora_fin);

        // Realizamos la validacion de la hora_inicio y la hora_fin
        if ($hora_inicio > $hora_fin){
            $this->setError("hora_inicio", "ERROR!! La hora de inicio es mayor que la hora fin");
        } // End if de la validacion de hora
    } // End de la funcion validarHora_Fin

    /**
     * Estamos definiendo un metodo privado se encarga de comprobar que no exista mas de un espectaculo
     * en la misma fecha y en la misma sala durante ese intervalo de tiempo.
     * 
     * @param boolean $insert ---> True si estamos insertando y False no estamos insertando
     * @return boolean  TRUE ---> Si hay datos   
     *                  FALSE ---> No hay datos
     */
    private function validacionAnadirSesion(bool $insert):bool{
        // Validamos los datos de envio
        $hora_inicio = CGeneral::addSlashes($this->hora_inicio);
        $hora_fin = CGeneral::addSlashes($this->hora_fin);
        $fecha = CGeneral::fechaNormalAMysql($this->fecha);

        // Definimos nuestras variables locales y escapamos los caracteres
        $sesion = new Sesiones();
        $cod_espe_sala = intval($this->cod_espe_sala);
        $cod_sesion = intval($this->cod_sesion);

        // Creamos nuestra sentencia SQL
        $sentencia = "Select * from sesiones where fecha = '$fecha' and 
            (hora_inicio between '$hora_fin' and '$hora_inicio') and (hora_fin between '$hora_fin' and '$hora_inicio')
            or (hora_inicio < '$hora_fin' and hora_fin > '$hora_inicio') and fecha = '$fecha'";

        // Validamos la sentencia y establecemos el return
        if ($insert == true){
            // Creamos la sentencia para obtener los datos
            $filas = $sesion->ejecutarSentencia($sentencia);
        
            // En caso de que no haya filas comprobamos, si no hay filas no hay fallos y se podra insertar
            if ($filas){
                $sql_2 = "Select cod_sala from espe_sala where cod_espe_sala = '$cod_espe_sala'";
                $filas_2 = $sesion->ejecutarSentencia($sql_2);
                $id_sala = intval($filas_2[0]["cod_sala"]);
                $fecha = CGeneral::fechaNormalAMysql($fecha);

                $sql_3 = "Select es.cod_espe_sala from espe_sala es
                            inner join sesiones s on es.cod_espe_sala = s.cod_espe_sala
                    where cod_sala = '$id_sala' and s.fecha > '$fecha'";
                $filas_3 = $sesion->ejecutarSentencia($sql_3);
        
                if ($filas_3){
                    $this->setError("hora_fin", "ERROR!! El intervalo que habeis introducido ya existe un espectaculos");
                    return true;
                } // End if
            } // End if
            return false;
        } // End if 1
        else{

            $sentencia .= " and cod_sesion <> '$cod_sesion'";
            
            // Creamos la sentencia para obtener los datos
            $filas = $sesion->ejecutarSentencia($sentencia);

            // En caso de que no haya filas comprobamos, si no hay filas no hay fallos y se podra insertar
            if ($filas){
                $sql_2 = "Select cod_sala from espe_sala where cod_espe_sala = '$cod_espe_sala'";
                $filas_2 = $sesion->ejecutarSentencia($sql_2);
                $id_sala = intval($filas_2[0]["cod_sala"]);

                $sql_3 = "Select es.cod_espe_sala from espe_sala es
                            inner join sesiones s on es.cod_espe_sala = s.cod_espe_sala
                    where cod_sala = '$id_sala' and s.fecha > '$fecha'";
                $filas_3 = $sesion->ejecutarSentencia($sql_3);
        
                $sw = false;
                if ($filas_3){
                    foreach ($filas_3 as $key => $value) {
                        if ($sw != true){
                            foreach ($filas as $key2 => $value2) {
                                if ($value["cod_espe_sala"] == $value2["cod_espe_sala"]){
                                    $sw = true;
                                    break;
                                }
                            }
                        }
                    }
                } // End if
                if ($sw == true){
                    $this->setError("hora_fin", "ERROR!! El intervalo que habeis introducido ya existe un espectaculos");
                    return true;
                }
            } // End if 2
            return false;
        } // End else 1

    } // End de la validacion

    /**
     * Estamos redefiniendo el metodo fijarInsert se encarga de insertar una Sesion
     */
    protected function fijarSentenciaInsert(){
        // Escapamos los caracteres
        $fecha = CGeneral::fechaNormalAMysql($this->fecha);
        $cod_espe_sala = intval($this->cod_espe_sala);
        $hora_inicio = CGeneral::addSlashes($this->hora_inicio);
        $hora_fin = CGeneral::addSlashes($this->hora_fin);

        // Realizamos el insert Sesiones
        if ($this->validacionAnadirSesion(true) == false){
            return "insert into sesiones (". 
            " cod_espe_sala,  fecha, hora_inicio, hora_fin,  borrado". 
            " ) values ('$cod_espe_sala', '$fecha', '$hora_inicio', '$hora_fin',  false)"; 
        }
    } // End de la funcion fijarSentenciaInsert


    protected function fijarSentenciaUpdate(){
        // Escapamos los caracteres
        $fecha = CGeneral::fechaNormalAMysql($this->fecha);
        $cod_sesion = intval($this->cod_sesion);
        $cod_espe_sala = intval($this->cod_espe_sala);
        $hora_inicio = CGeneral::addSlashes($this->hora_inicio);
        $hora_fin = CGeneral::addSlashes($this->hora_fin);
        $borrado = CGeneral::addSlashes($this->borrado);
        $cod_sesion = intval($this->cod_sesion);

        // Realizamos el insert Sesiones
        if ($this->validacionAnadirSesion(false) == false){
            return "update sesiones set ". 
            " cod_sesion = '$cod_sesion', ". 
            " cod_espe_sala = '$cod_espe_sala', ". 
            " fecha = '$fecha', ".
            " hora_inicio = '$hora_inicio', ".
            " hora_fin = '$hora_fin', ".
            " borrado = '$borrado' ".
            " where cod_sesion = {$cod_sesion}"; ; 
        } // End de la funcion fijarSentenciaUpdate
    }

} // End del modelo Categoria Foro