<?php

// Incluimos la libreria de tcpdf
require_once(RUTA_BASE . "/scripts/tcpdf/tcpdf.php");

/**
 * Estamos definiendo la clase MYPDF que hereda de TCPDF
 */
class MYPDF extends TCPDF{

    /**
     * Estamos definiendo la funcion ColoredTableCompra para realizar una tabla de los productos comprados
     * y mostrarlos en un pdf
     *
     * @param [type] $header ---> Enviamos la cabecera de la tabla datos de Compras Realizadas
     * @param [type] $data ---> Enviamos los datos para mostrarlo en cada celda correspondiente
     */
    public function ColoredTableCompras($header, $data){
        // Establecemos el color de la tabla
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');

        // Establecemos un margen de separación a la izquierda
        $leftMargin = 27;
        $this->SetLeftMargin($leftMargin);

        // Definimos los tamaños de la cabecera de la tabla Compras
        $w = array(120, 35, 40, 60, 100);

        // Recorremos el array de cabecera para establecer el tamaño de cada celda
        for ($i = 0; $i < count($header); ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        } // En del for

        $this->Ln();

        // Establecemos el color de la tabla
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');

        // Mostraremos los datos en cada celda
        $fill = 0;
        foreach ($data as $row) {
            $this->Cell($w[0], 10, $row["nombre_producto"], 1, 0, 'C', $fill);
            $this->Cell($w[1], 10, $row["unidades"], 1, 0, 'C', $fill);
            $this->Cell($w[2], 10, $row["importe_base"] . " €", 1, 0, 'C', $fill);
            $this->Cell($w[3], 10, ($row["importe_base"] * $row["unidades"]) . " €", 1, 0, 'C', $fill);
            $this->Cell($w[4], 10, $row["importe_total"] . " €", 1, 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill;
        } // End del foreach
        $this->Cell(10 * count($data), 0, '', 'T');
    } // End de la funcion ColoredTableCompras

    /**
     * Estamos definiendo la funcion ColoredTableEntradas para realizar una tabla de los datos de las entradas comprados
     * y mostrarlos en un pdf
     *
     * @param [type] $header ---> Enviamos la cabecera de la tabla datos de Entradas Compradas
     * @param [type] $data ---> Enviamos los datos para mostrarlo en cada celda correspondiente
     */
    public function ColoredTableEntradas($header, $data){
        // Establecemos el color de la tabla
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');

        // Establecemos un margen de separación a la izquierda
        $leftMargin = 85;
        $this->SetLeftMargin($leftMargin);

        // Definimos los tamaños de la cabecera
        $w = array(65, 65, 65);

        // Recorremos el array de cabecera para establecer el tamaño de cada celda
        for ($i = 0; $i < count($header); ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        } // End del for

        $this->Ln();

        // Establecemos el color de la tabla
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');

        // Mostraremos los datos en cada celda
        $fill = 0;
        foreach ($data as $key => $row) {
            $this->Cell($w[0], 10, $row["num_asientos"], 1, 0, 'C', $fill);
            $this->Cell($w[1], 10, $row["precio_total"], 1, 0, 'C', $fill);
            $this->Cell($w[2], 10, $row["titulo"], 1, 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill;
        } // End del forach
        $this->Cell(20 * count($data), 0, '', 'T');
    } // End de la funcion ColoredTableEntradas
} // End de la clase MYPDF

/**
 * Estamos definiendo el Controlador de pdf
 */
class pdfControlador extends CControlador{

    /**
     * Estamos definiendo la accion Compra la cual se encarga de mostrar los datos de Compras realizadas en un tabla
     * y se nos descargará en un pDF
     */
    public function accionCompra(){
        // Creamos el documento pdf
        $pdf = new MYPDF("L", PDF_UNIT, "A3", 'UTF-8', false);

        // Establecemos margenes de separacion
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->setFooterMargin(PDF_MARGIN_FOOTER);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Cambiamos la fuente del contenido
        $pdf->setFont('dejavusans', '', 12, '', true);

        // Añadimos una pagina para mostrar los datos
        $pdf->AddPage();

        // Dibujamos las etiquetas de HTML
        $html = CHTML::dibujaEtiqueta("h2", ["style" => "text-align:center;"], "MOSTRAR DATOS DE LA COMPRA") . "<br>" . PHP_EOL;

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        // Definimos el objeto Ventas_Productos
        $ventas_productos = new Ventas_Productos();
        $id = 0;

        // Obtenemos el id
        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
        } // End if de la validacion

        // Realizamos la sentencia para obtener los datos de la compra realizada
        $datos = $ventas_productos->buscarTodos(["where" => "cod_ventas = '$id'"]);

        // Comprobamos si hay datos
        if (!$datos) {
            Sistema::app()->paginaError(404, "No hay datos");
            return;
        }

        // Definimos la cabecera de la tabla Compras Realizadas
        $header = ["Nombre",  "Unidad", "Precio", "Precio_Total", "Precio_Total_Compra"];

        // Llamamos a la funcion ColoredTableCompras
        $pdf->ColoredTableCompras($header, $datos);

        // Descargamos el pdf de Compras Realizadas
        $pdf->Output('Ver Compras.pdf', 'D');
    } // End de la accion pdf Compra

    /**
     * Estamos definiendo la accion Entradas la cual se encarga de mostrar los datos de Entradas realizadas en un tabla
     * y se nos descargará en un pDF
     */
    public function accionEntradas() {
        // Creamos el documento pdf
        $pdf = new MYPDF("L", PDF_UNIT, "A3", 'UTF-8', false);

        // Establecemos margenes de separacion
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->setFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->setFontSubsetting(true);

        // Cambiamos la fuente del contenido
        $pdf->setFont('dejavusans', '', 12, '', true);

        // Añadimos una pagina para mostrar los datos
        $pdf->AddPage();

        // Dibujamos las etiquetas de HTML
        $html = CHTML::dibujaEtiqueta("h2", ["style" => "text-align:center;"], "MOSTRAR DATOS DE LAS ENTRADAS") . "<br>" . PHP_EOL;

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        // Definimos el objeto Reservar para las entradas
        $reservar = new Reservar();
        $id = 0;

        // Obtenemos el id
        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
        } // End if de la validacion

        // Realizamos la sentencia para obtener los datos de las entradas realizada
        $datos = $reservar->buscarTodos(["where" => " cod_reserva = '$id'"]);

        // Comprobamos si hay datos
        if (!$datos) {
            Sistema::app()->paginaError(404, "No hay datos");
            return;
        } // End if de la validacion de datos

        // Definimos la cabecera de los Datos de Entradas
        $header = ["Numero Asiento", "Precio Asiento", "Titulo Espectáculo"];

        // Llamamos a la funcion ColoredTableEntradas
        $pdf->ColoredTableEntradas($header, $datos);

        // Descargamos el pdf de Compras Entradas
        $pdf->Output('Ver Compras Entradas.pdf', 'D');
    } // End de la accion pdf Compra 

    /**
     * Estamos definiendo la accion VerEntradas la cual se encarga de mostrar los datos de compras de Entradas en un tabla
     * y se nos descargará en un pDF
     */
    public function accionVerEntradas(){
        // Creamos el documento pdf
        $pdf = new MYPDF("L", PDF_UNIT, "A3", 'UTF-8', false);

        // Establecemos margenes de separacion
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->setFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->setFontSubsetting(true);

        // Cambiamos la fuente del contenido
        $pdf->setFont('dejavusans', '', 12, '', true);

        // Añadimos una pagina para mostrar los datos
        $pdf->AddPage();

        // Dibujamos las etiquetas de HTML
        $html = CHTML::dibujaEtiqueta("h2", ["style" => "text-align:center;"], "MOSTRAR DATOS DE LAS ENTRADAS") . "<br>" . PHP_EOL;

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        // Definimos nuestras variables locales
        $datos = [];
        $array_datos = [];

        // Comprobamos de que haya de que estemos recibiendo los datos de las entradas compradas al instante
        if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            // Comprobamos la obtencion de los datps
            if (isset($_GET["datos"])) {
                $datos = $_GET["datos"];
            } // End if
            else{
                Sistema::app()->paginaError(404, "No hay datos");
                return;
            } // End else
        } // End del REQUEST_METHOD GET

        // Transformamos los datos de Entradas en un array
        $datos = json_decode($datos, true);

        // Recorremos los datos para eliminar una posicion del array y utilizar la funcion ColoredTableEntradas
        foreach($datos as $key => $value){
            $array_datos[$key] = $value[0];
        } // End del foreach

        // Definimos la cabecera
        $header = ["Numero Asiento", "Precio Asiento", "Titulo Espectáculo"];

        // Llamamos a la funcion ColoredTableEntradas
        $pdf->ColoredTableEntradas($header, $array_datos);

         // Descargamos el pdf de Entradas
        $pdf->Output('Entradas.pdf', 'D');
    } // End de la accionVerEntradas

} // En de la clase pdf Controlador
