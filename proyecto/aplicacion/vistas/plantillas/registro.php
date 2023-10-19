<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo $titulo; ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">

	<!-- ESTAMOS ENLAZANDO CSS Y UN FAVICON -->
	<link rel="stylesheet" type="text/css" href="/estilos/registro.css">
	<link rel="icon" type="image/png" href="/imagenes/favicon.png">

	<!-- ESTAMOS ENLZANDO LOS ENLACES CON BOOSTRAP -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

	<?php
	if (isset($this->textoHead))
		echo $this->textoHead;
	?>
</head>
<body>	

	<header>
		<h3>Cinema</h3>
	</header>

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
                ?>
    </div>

	<main>
		<?php echo $contenido;?>
	</main>
    
</body>
</html>