<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $titulo; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">

    <!-- ESTAMOS ENLAZANDO CSS Y UN FAVICON -->
    <link rel="stylesheet" type="text/css" href="/estilos/dashboard.css" />
    <link rel="icon" type="image/png" href="/imagenes/favicon.png" />

    <!-- ESTAMOS ENLZANDO LOS ENLACES CON BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    <!-- ESTAMOS ENLAZANDO LOS ENLACES CON JQUERY -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <?php
    if (isset($this->textoHead))
        echo $this->textoHead;
    ?>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin/index">Adminsitracion de Datos</a>
            
            <ul class="nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><?php echo Sistema::app()->Acceso()->getNick(); ?></a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo Sistema::app()->generaURL(["inicial", "Index"]); ?>">Inicio</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?php echo Sistema::app()->generaURL(["registro", "Cerrar"]) ?>">Cerrar Sesion</a></li>
                    </ul>
                </li>
            </ul>

            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Adminsitacion De Datos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body" id="enlaces">
                    <ul>
                        <li><a href="<?php echo Sistema::app()->generaURL(["categoriaProducto"]); ?>">Categoria Productos</a></li>
                        <li><a href="<?php echo Sistema::app()->generaURL(["producto"]); ?>">Productos</a></li>
                        <li><a href="<?php echo Sistema::app()->generaURL(["categoriaEspectaculo"]); ?>">Categoria Espectaculos</a></li>
                        <li><a href="<?php echo Sistema::app()->generaURL(["espectaculos"]); ?>">Espectaculo</a></li>
                        <li><a href="<?php echo Sistema::app()->generaURL(["participantes"]); ?>">Actores</a></li>
                        <li><a href="<?php echo Sistema::app()->generaURL(["partiEspe"]); ?>">Part_Espe</a></li>
                        <li><a href="<?php echo Sistema::app()->generaURL(["sala"]); ?>">Sala</a></li>
                        <li><a href="<?php echo Sistema::app()->generaURL(["espesala"]); ?>">Espe_Sala</a></li>
                        <li><a href="<?php echo Sistema::app()->generaURL(["sesiones"]); ?>">Sesiones</a></li>
                        <li><a href="<?php echo Sistema::app()->generaURL(["categoriaForo"]); ?>">Categoria Foro</a></li>
                        <li><a href="<?php echo Sistema::app()->generaURL(["cliente"]); ?>">Usuario</a></li>
                        <li><a href="<?php echo Sistema::app()->generaURL(["venta"]); ?>">Ventas</a></li>
                        <li><a href="<?php echo Sistema::app()->generaURL(["reservar"]); ?>">Entradas</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <main>

        <!-- DEFINIMOS EL CONTENIDO DE LA ADMINISTRACION DATOS -->
        <article id="contenido">
            <!-- DEFINIMOS NUESTRA BARRA DE UBICACION -->
            <div id="barraubi">
                <?php
                $sw = true;
                if (isset($this->barraubi)) {
                    foreach ($this->barraubi as $value) {
                        if ($sw)
                            $sw = false;
                        else
                            echo "&gt;&gt;";
                        echo CHTML::link($value["texto"], $value["enlace"]);
                    } // End foreach
                } // End if de barra_menu
                ?></div>

            <!-- MOSTRAREMOS EL CONTENIDO DE NUESTRAS PAGINAS -->
            <?php echo $contenido; ?>
        </article>
    </main>
</body>

</html>