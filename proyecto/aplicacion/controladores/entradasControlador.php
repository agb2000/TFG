<?php

/**
 * Estamos definiendo el Controlador de Entradas
 */
class entradasControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para Entradas donde realizaremos la compra de entradas
     * la informacion de los espectaculos y donde el usuario podra añadir y ver los comentarios
     * del espectaculo
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->accionDefecto = 'Entradas';
        $this->plantilla = "entradas";
    } // End del constructor

    /**
     * Estamos definiendo la accionEntradas la cual se encarga de obtener el numero de butacas en total al cual pertence 
     * la sesion. Mostraremos el numero de butacas de la sala
     */
    public function accionEntradas(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

        // Definimos nuestras variables locales
        $id = 0;
        $sesion = new Sesiones();
        $espe_sala = new Espe_Sala();

        // Sacamos el id del sesion
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if de la obtencion del id sesion

        // Buscamos el id sesion
        $filas = $sesion->buscarPorId($id);

        // En caso de que no hayamos encontrado esa sesion
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado la sesion");
            return;
        } // End if de filas

        // Obtenemos el cod_espe_sala y lo parseamos
        $cod_espe_sala = intval($sesion->cod_espe_sala);

        // Obtenemos el espe_correspiente
        $filas = $espe_sala->buscarPorId($cod_espe_sala);

        // Validamos de que ese espe_sala exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado el espectaculo asociado a la sala");
            return;
        } // End if de filas
        
        // Llamamos a la vista donde enviaremos la capacidad maxima de la sesion donde se encuentra ese espe_sala
        $this->dibujaVista("butacas", ["capacidad_maxima" => intval($espe_sala->capacidad_maxima)], "Butacas");
        exit;
    } // End de la accion Entradas

    /**
     * Estamos definiendo la accion Comprar Entradas la cual se encarga de realizar la compra de Entradas de las butacas
     * seleccionada por el cliente
     */
    public function accionComprar_Entradas(){
        // Definimos nuestras variables locales
        $datos = null;
        $errores = [];
        $array_Reservar = [];
        $array_enviar = [];

        // Comprobamos el envio del POST    
        if ($_SERVER["REQUEST_METHOD"] == 'POST'){
            // Comprobamos de que estamos enviando el $_POST[datos]
            if (isset($_POST["datos"])){
                // Asignamos el POST a una variable
                $datos = $_POST["datos"];

                // Comprobamos de que el array de datos no este vacio
                if (count($datos) > 0){
                    // En caso de que haya datos en el array lo recorremos y se lo asignamos al modelo RESERVAR
                    foreach($datos as $key => $value){
                        // Asignamos los valores al modelo Reservar que se encarga de Reservar Entradas
                        $reservar = new Reservar();
                        $reservar->cod_sesion = intval($_POST["id"]);
                        $reservar->cod_cliente = CGeneral::addSlashes(Sistema::app()->Acceso()->getNick());
                        $reservar->num_asientos = intval($value);
        
                        // Una vez que hayamos asignado los valores lo validamos para saber si tenemos algun error y lo guardamos en un array
                        if ($reservar->validar() == false){
                            $errores[] = $reservar->getErrores();
                        } // End if 1
                        else{
                            $array_Reservar[] = $reservar;
                        } // End else
                    } // End foreach
        
                    // En caso de que no haya errores 
                    if (!$errores){

                        // Recorremos el array de reservas para comprobar para insertalo en la base de datos
                        foreach($array_Reservar as $key => $value){
                            // Guardamos la reserva y sacamos el codigo de reserva de cada una de las compras de entradas
                            $value->guardar();
                            $sentencia = "Select MAX(cod_reserva) as codigo from reservar";
                            $array_enviar[] = $reservar->ejecutarSentencia($sentencia);
                        } // End foreach

                        // Mostraremos en caso verdadero el enviaremos la url para que se nos redirecciona a la pagina resumen y enviamos los codigos de reserva
                        echo json_encode(["correcto" => true, 
                            "url" => Sistema::app()->generaURL(["entradas", "Resumen"]), "datos" => $array_enviar], JSON_PRETTY_PRINT);
                        return;
                    } // End if errores reservar
                    else{
                        // En casso de que haya errores lo enviaremos
                        echo json_encode(["correcto" => false, "datos" => $errores], JSON_PRETTY_PRINT);
                        return;
                    } // End else errores reservar
                } // End if 3
                else{
                    echo json_encode(["correcto" => false, "datos" => "ERROR!! No has seleccionado ninguna butaca"], JSON_PRETTY_PRINT);
                    return;
                } // End else 3
            } // End if $_POST[datos] 2
        } // End if del SERVER POST 1
    } // End de la accion Prueba

    /**
     * Estamos definiendo la accion Resumen la cual se encarga de llamar a la vista resumen
     */
    public function accionResumen(){
        // Llamamos a la vista resumen
        $this->dibujaVista("resumen", [], "Resumen de entradas");
        exit;
    } // End de la accion Resumen

    /**
     * Estamos definiendo la accionMostrarEntradas la cual se encarga de mostrar la información de las entradas
     * enviadas a traves el codigo de la entrada que enviamos
     */
    public function accionMostrarEntradas(){
        // Definimos nuestras variables locales
        $array_datos = [];

        // Comprobamos el envio del POST    
        if ($_SERVER["REQUEST_METHOD"] == 'POST'){
            // Comprobamos de que exista los datos del codigo de entradas
            if (isset($_POST["datos"])){
                // Recorremos el $_POST[datos] y obtenemos la informacion de las entradas
                foreach ($_POST["datos"] as $key => $value) {
                    $reservar = new Reservar();
                    $codigo = intval($value[0]["codigo"]);
                    $array_datos[] = $reservar->buscarTodos(["where" => "cod_reserva = '$codigo'"]);
                } // End foreach

                // Mostramos de que se haya enviado
                echo json_encode(true, JSON_PRETTY_PRINT);
                return;
            } // End if
        } // End del REQUEST_METHOD POST
    } // End de la accion Obtener Datos

    /**
     * Estamos definiendo la accion ObtenerDatos la cual se encarga de Obtener Datos los datos de compras de las entradas
     */
    public function accionObtenerDatos(){
        // Definimos nuestras variables locales
        $array_entradas = [];

        // Comprobamos de que exista la variable SuperGlobal $_COOKIE[datos]
        if (isset($_COOKIE["datos"])){
            // Lo guardamos en una variables
            $array_id = $_COOKIE["datos"];

            // Lo parseamos para obtener los datos en un array
            $array_id = json_decode($array_id, true);

            // Recorremos el array de datos con el codigo de reserva
            foreach ($array_id as $key => $value) {
                // Obtenemos los datos de las entradas y lo guardamos en un array
                $reservar = new Reservar();
                $id = intval($value[0]["codigo"]);
                $array_entradas[] = $reservar->buscarTodos(["where" => "cod_reserva = '$id'"]);
            } // End foreach
    
            // Mostraremos los datos de las entradas
            echo json_encode(["datos" => $array_entradas], JSON_PRETTY_PRINT);
            return;
        } // End if de la obtencion de $_COOKIE[datos]
    } // End de la accion Obtener Datos

    /**
     * Estamos definiendo la accion de Obtener Butacas lo que hace es comprobar si el numero de butacas que recibimos
     * esta comprado o no 
     */
    public function accionObtenerButacas(){
        // Definimos nuestras variables locales
        $reservar = new Reservar();
        $id = 0;
        $num_butacas = 0;

        // Comprobamos el envio del POST    
        if ($_SERVER["REQUEST_METHOD"] == 'POST'){
            // Comprobamos si le enviamos los datos
            if (isset($_POST["id"])){
                $id = intval($_POST["id"]);
            } // End if de la validacion

            if (isset($_POST["num_butaca"])){
                $num_butacas = intval($_POST["num_butaca"]);
            } // End if de validacion

            // Realizamos la sentencia SQL
            $filas = $reservar->buscarTodos(["where" => "cod_sesion = '$id'"]);

            // Hacemos la comprobacion de que exista el codigo de sesion
            if (!$filas){
                echo json_encode(["correcto" => false]);
                return;
            } // End if de filas

            // Comprobamos de que ese butaca en esa sesion esta comprada o no
            $registro = $reservar->buscarTodos(["where" => "cod_sesion = '$id' and num_asientos = '$num_butacas'"]);

            // Enviamos los datos de que correcto TRUE o false
            if ($registro){
                echo json_encode(["correcto" => true]);
                return;
            } // End if 1
            else{
                echo json_encode(["correcto" => false]);
                return;
            } // End else 1
        } // End if del $_SERVER[REQUEST_METHOD] POST
    } // End de la accion Obtener Butacas
} // End de la clase Controlador Entradas