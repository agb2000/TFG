<?php
	header("HTTP/1.1 $numError $mensaje");
	header("Status: $numError $mensaje")
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>ERROR</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width; initial-scale=1.0">

		<link rel="stylesheet" type="text/css" href="/estilos/error.css" />
		<link rel="icon" type="image/png" href="/imagenes/favicon.png" />

		<!-- ESTAMOS ENLZANDO LOS ENLACES CON BOOSTRAP -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" 
			integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" 
			integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
		
	</head>
	<body>
		<main class="contenido">
			<article>
				<h1>ERROR 404 - Página no Encontrada</h1>
				<p><?php echo $mensaje;?></p>
				<p><a href="<?php echo Sistema::app()->generaURL(["inicial"]) ?>">Volver a la Pagina Inicio</a></p>
			</article>
		</main>
	</body>		
</html>
	