<?php

/**
 * Estamos definiendo el Controlador de Categorira Foro
 */
class categoriaForoControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para el crud de Categoría Foro.
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->plantilla = "dashboard";
        $this->accionDefecto = "Index";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de dibujar el crud de Categorias Foro y realizariamos un paginador.
     */
    public function accionIndex(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

        // Comprobamos si tiene el permiso 2
        if (!Sistema::app()->Acceso()->puedePermiso(2)){
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina ");
            return;
        } // End if si tiene permiso 2
        
        // Definimos nuestras variables locales
        $categoria_foro = new Categoria_Foro();

        // Estamos definiendo el funcionamiento del paginador de Categoria Foro
        $registros = intval($categoria_foro->buscarTodosNRegistros());

        $tamPagina = 4;

        if (isset($_GET["reg_pag"]))
            $tamPagina = intval($_GET["reg_pag"]);

        $numPaginas = ceil($registros / $tamPagina);
        $pag = 1;

        if (isset($_GET["pag"])){
            $pag=intval($_GET["pag"]);
        }

        if ($pag > $numPaginas)
            $pag = $numPaginas;

        $inicio = $tamPagina * ($pag-1);
        if ($inicio<0)
            $inicio=0;

        $opciones["limit"] = "$inicio, $tamPagina";    

        // Estamos definiendo el funcionamiento del Paginador 
        $filas = $categoria_foro->buscarTodos($opciones);

        // En caso de que haya fila guardaremos las acciones de ver/modificar/borrar Categoria_Foro
        if ($filas){
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de ver los datos de categoria foro
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/24x24/ver.png"),
                    Sistema::app()->generaURL(
                        ["categoriaForo", "Ver"],
                        ["id" => $filas[$key]["cod_categoria_foro"]]
                    )
                );

                // Estamos definiendo el boton de modificar datos de categoria Foro
                $cadena .= CHTML::link(
                    CHTML::imagen("/imagenes/24x24/modificar.png"),
                    Sistema::app()->generaURL(
                        ["categoriaForo", "Modificar"],
                        ["id" => $filas[$key]["cod_categoria_foro"]]
                    )
                );

                // Estamos definiendo el boton de borrar Categoria Foro
                $cadena .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png"), 
                Sistema::app()->generaURL(["categoriaForo", "Borrar"],
                    ["id" => $filas[$key]["cod_categoria_foro"]]), 
                    ["class" => "borrar", "title" => "Borrar Categoria Foro ".$filas[$key]["cod_categoria_foro"]]);
                
                // Guardamos las operaciones del crud
                $filas[$key]["operaciones"] = $cadena;     

                // Hacemos un reemplazo para el que cuando salgo un 0 devuelva no y cuando salga un 1 devuelva un si
                if ($filas[$key]["borrado"] == 0){
                    $filas[$key]["borrado"] = str_replace("0", "No", $filas[$key]["borrado"]);
                } // End if 1
                else{
                    $filas[$key]["borrado"] = str_replace("1", "Si", $filas[$key]["borrado"]);
                } // End else 1

                // Cambiamos el formato de la fecha
                $filas[$key]["fecha_creacion"] = CGeneral::fechaMysqlANormal($filas[$key]["fecha_creacion"]);
            } // End foreach

        } // End if filas

        // Definimos la cabecera para la tabla de Categoria Foro
        $cabecera = [
            [
                "CAMPO" => "nombre_categoria_foro",
                "ETIQUETA" => "Nombre de la Categoria Foro"
            ],
            [
                "CAMPO" => "fecha_creacion",
                "ETIQUETA" => "Fecha de Creacion"
            ],
            [
                "CAMPO" => "contador_comentarios",
                "ETIQUETA" => "Numero de Comentarios"
            ],
            [
                "CAMPO" => "borrado",
                "ETIQUETA" => "¿Esta Borrado?"
            ],
            [
                "CAMPO" => "operaciones",
                "ETIQUETA" => "Acciones"
            ]
        ];

        // Definimos nuestro paginador
        $opcPaginador = array(
            "URL" => Sistema::app()->generaURL(array("categoriaForo", "Index")),
            "TOTAL_REGISTROS" => $registros,
            "PAGINA_ACTUAL" => $pag,
            "REGISTROS_PAGINA" => $tamPagina,
            "TAMANIOS_PAGINA" => array(
                2 => "2",
                5 => "5",
                8 => "8",
                10 => "10",
                20 => "20",
                30 => "30"
            ),
            "MOSTRAR_TAMANIOS" => true,
            "PAGINAS_MOSTRADAS" => 4,
        );

        // Llamamos a la vista index de Categoria Foro
        $this->dibujaVista("index", ["filas" => $filas, "cabecera" => $cabecera, "paginador" => $opcPaginador], "Categoria Foro");
        exit;
    } // End de la accion Index

    /**
     * Definiriamos la accion de CrearCategoriaForo para poder crear un modelo de Tipo Categoria Foro
     */
    public function accionCrearCategoriaForo(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

        // Comprobamos si tiene el permiso 2
        if (!Sistema::app()->Acceso()->puedePermiso(2)){
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina ");
            return;
        } // End if si tiene permiso 2

        // Definimos nuestras variables locales
        $tema_foro = new Categoria_Foro();
        $nombre = $tema_foro->getNombre();

        // Llamamos al boton del formulario de crear categoria
        if (isset($_POST["crear_categoria_foro"])){
            // Guardamos los valores
            $tema_foro->setValores($_POST[$nombre]);
            $tema_foro->fecha_creacion = CGeneral::fechaMysqlANormal($tema_foro->fecha_creacion);

            // Guardamos y validamos los valores para cada atributo del objeto CategoriaForo
            if ($tema_foro->validar()){
                if ($tema_foro->guardar()){
                    Sistema::app()->irAPagina(["categoriaForo"]);
                    return;
                } // End if 2
                else{
                    $tema_foro->fecha_creacion = CGeneral::fechaNormalAMysql($tema_foro->fecha_creacion);
                    $this->dibujaVista("crearCategoria", ["tema_foro" => $tema_foro], "Crear Categoria Foro");
                    exit;
                } // End else 2
            } // End if 1 
            else{
                $tema_foro->fecha_creacion = CGeneral::fechaNormalAMysql($tema_foro->fecha_creacion);
                $this->dibujaVista("crearCategoria", ["tema_foro" => $tema_foro], "Crear Categoria Foro");
                exit;
            } // End else 1
        } // End del boton formulario

        // Llamamos a la vista de Crear Categoria Foro
        $this->dibujaVista("crearCategoria", ["tema_foro" => $tema_foro], "Crear Categoria Foro");
        exit;
    } // End de la accion de Crear Categoria Foro

    /**
     * Estamos definiendo la accion de Ver para mostrar todos los datos de un modelo de Tipo Categoria Foro en específico
     */
    public function accionVer(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

        // Comprobamos si tiene el permiso 2
        if (!Sistema::app()->Acceso()->puedePermiso(2)){
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina ");
            return;
        } // End if si tiene permiso 2

        // Definimos nuestras variables locales
        $tema_foro = new Categoria_Foro();
        $id = 0;

        // Obtenemos el id de ese modelo
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End de la obtencion del ID

        // Buscamos por ID
        $filas = $tema_foro->buscarPorId($id);

        // Comprobariamos si ese modelo existe o no
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con esa Categoria Foro no existe");
            return;
        } // End if

        // Llamamos al ver Datos del modelo Categoria Espectaculo
        $this->dibujaVista("verDatos", ["tema_foro" => $tema_foro], "Ver Categoria Foro");
        exit;
    } // End de la accion Ver

    /**
     * Estamos definiendo la accion de Modificar Categoria Foro de un modelo en específico
     */
    public function accionModificar(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

        // Comprobamos si tiene el permiso 2
        if (!Sistema::app()->Acceso()->puedePermiso(2)){
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina ");
            return;
        } // End if si tiene permiso 2

        // Definimos nuestras variables locales
        $tema_foro = new Categoria_Foro();
        $nombre = $tema_foro->getNombre();
        $id = 0;

        // Obtenemos el id del modelo en especifico
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End de la obtencion el ID

        // Buscamos por el id de la Categoria Foro
        $filas = $tema_foro->buscarPorId($id);

        // Comprobamos de que el id de ese modela exista
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos con esa Categoria Foro");
            return;
        } // End if de la valicaion

        // Llamamos al formulario para modificar los datos
        if (isset($_POST["actualizar"])){
            // Le asignamos los valores y el cod de categoria espectaculo
            $tema_foro->setValores($_POST[$nombre]);
            $tema_foro->cod_categoria_espectaculo = $id;
            $tema_foro->fecha_creacion = CGeneral::fechaMysqlANormal($tema_foro->fecha_creacion);
            
            if ($tema_foro->validar()){
                if ($tema_foro->guardar()){
                    Sistema::app()->irAPagina(["categoriaForo"]);
                    return;
                } // End if 2
                else{
                    $tema_foro->fecha_creacion = CGeneral::fechaNormalAMysql($tema_foro->fecha_creacion);
                    $this->dibujaVista("modificarDatos", ["tema_foro" => $tema_foro], "Modificar Categoria Foro");
                    exit;
                } // End else 2
            } // End if 1
            else{
                $tema_foro->fecha_creacion = CGeneral::fechaNormalAMysql($tema_foro->fecha_creacion);
                $this->dibujaVista("modificarDatos", ["tema_foro" => $tema_foro], "Modificar Categoria Foro");
                exit;
            } // End else 1

        } // End if del boton actualizar

        // Llamamos a la vista de modificar Datos de ese Modelo
        $this->dibujaVista("modificarDatos", ["tema_foro" => $tema_foro], "Modificar Categoria Foro");
        exit;
    } // End de la accion Modificar

    /**
     * Estamos definiendo la accion de Borrar Categoria Foro de un modelo en específico
     */
    public function accionBorrar(){
        // Comprobamos si hay un usuario registrado
        if (!Sistema::app()->Acceso()->hayUsuario()){
            Sistema::app()->irAPagina(array("registro", "Login"));
            Sistema::app()->obtener_url();
            return;
        } // End if si hay usuario

        // Comprobamos si tiene el permiso 2
        if (!Sistema::app()->Acceso()->puedePermiso(2)){
            Sistema::app()->paginaError(404, "ERROR!! No tienes permisos para poder acceder a esta pagina");
            return;
        } // End if si tiene permiso 2

        // Definimos variables locales
        $tema_foro = new Categoria_Foro();
        $id = 0;

        // Comprobamos de que exista
        if (isset($_GET["id"])){
            $id = intval($_GET["id"]);
        } // End if 

        // Buscamos por id
        $filas = $tema_foro->buscarPorId($id);

        // Comprobamos de que no haya fallos
        if (!$filas){
            Sistema::app()->paginaError(404, "ERROR!! No se ha encontrado datos de esa Categoria Foro");
            return;
        }// End if

        // Realizamos la sentencia de borrado logico
        $sentencia = "update categoria_foro set borrado = true where cod_categoria_foro = '$id'";

        // Comprobamos si se ha realizado
        if (!$tema_foro->ejecutarSentencia($sentencia)) {
            Sistema::app()->paginaError(404, "ERROR!! Se ha producido un error en la base de datos");
            return;
        } // End if

        // Redireccionamos a la accion Consultar Categoria
        Sistema::app()->irAPagina(["categoriaForo", "Index"]);
        return;
    } // End de la accion Borrar

} // End de la clase Controlador Categoria Foro