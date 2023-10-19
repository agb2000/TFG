<?php

/**
 * Estamos definiendo el Controlador de Venta
 */
class ventaControlador extends CControlador{

    /**
     * Estamos definiendo el constructor para el crud de Venta.
     * 
     * Llamariamos a la plantilla ---> Para establecerle la plantilla de administrador
     * Llamaria a la accionDefecto ---> Para establecer una accion si por defecto si llamamos solo al constructor
     */
    public function __construct(){
        $this->plantilla = "dashboard";
        $this->accionDefecto = "Index";
    } // End del constructor

    /**
     * Estamos definiendo la accion Index la cual se encarga de dibujar el crud de Venta y realizariamos un paginador.
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
        $venta = new Ventas();

        // Estamos definiendo el funcionamiento del paginador de Venta
        $registros = intval($venta->buscarTodosNRegistros());

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
        $filas = $venta->buscarTodos($opciones);

        // En caso de que haya fila guardaremos las acciones de descarga datos de Venta
        if ($filas){
            foreach ($filas as $key => $value) {
                // Estamos haciendo el boton de descargar en un PDF de los datos de Ventas
                $cadena = CHTML::link(
                    CHTML::imagen("/imagenes/descargar.png"),
                    Sistema::app()->generaURL(
                        ["pdf", "Compra"],
                        ["id" => $filas[$key]["cod_ventas"]]
                    )
                );
                
                // Guardamos las operaciones del crud
                $filas[$key]["operaciones"] = $cadena;     

                // Cambiamos el formato de la fecha
                $filas[$key]["fecha"] = CGeneral::fechaMysqlANormal($filas[$key]["fecha"]);
            } // End foreach
        } // End if filas

        // Definimos la cabecera para la tabla de Ventas
        $cabecera = [
            [
                "CAMPO" => "nick_cliente",
                "ETIQUETA" => "Nick Cliente"
            ],
            [
                "CAMPO" => "fecha",
                "ETIQUETA" => "Fecha de Compra"
            ],
            [
                "CAMPO" => "importe_total",
                "ETIQUETA" => "Importe Total"
            ],
            [
                "CAMPO" => "operaciones",
                "ETIQUETA" => "Acciones"
            ]
        ];

        // Definimos nuestro paginador
        $opcPaginador = array(
            "URL" => Sistema::app()->generaURL(array("venta", "Index")),
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

        // Llamamos a la vista index de Venta
        $this->dibujaVista("index", ["filas" => $filas, "cabecera" => $cabecera, "paginador" => $opcPaginador], "Ventas");
        exit;
    } // End de la accion Index

} // End de la clase venta Controlador