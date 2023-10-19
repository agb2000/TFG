<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo $titulo; ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">

	<!-- ESTAMOS AÑADIENDO UN CSS GENERAL -->
	<link rel="stylesheet" type="text/css" href="/estilos/general.css">

	<!-- ESTAMOS ENLAZANDO CSS Y UN FAVICON -->
	<link rel="stylesheet" type="text/css" href="/estilos/estilos.css">
	<link rel="icon" type="image/png" href="/imagenes/favicon.png">

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
	<!-- ESTAMOS DEFINIENDO LA CABECERA -->
	<header>
		<article>
			<h3><a href="<?php echo Sistema::app()->generaURL(["inicial"]) ?>">Cinema</a></h3>
		</article>

		<article class="navbar">
			<div class="container-fluid">
				<form class="d-flex" action="/buscador/Index" method="post">
					<input class="form-control me-2" type="text" placeholder="Busqueda de Espectáculos" aria-label="Search" id="buscador" name="buscador">
					<div id="listados"></div>
					<button class="btn btn-outline-success" type="submit">Buscar</button>
				</form>
			</div>
		</article>

		<article id="registros">
			<a title="Ver Compras" id="enlace" href="<?php echo Sistema::app()->generaURL(["cataproducto", "Cesta"]); ?>">
				<img src="/imagenes/aplicacion/carrito.png" alt="Icono de Compras">
			</a>
			<?php if (Sistema::app()->Acceso()->hayUsuario()) { ?>
				<div class="container-fluid">
					<ul class="navbar-nav">
						<li class="nav-item dropdown">
							<button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="nombre_usuario"><?php echo Sistema::app()->Acceso()->getNick(); ?></button>
							<ul class="dropdown-menu dropdown-menu-dark">
								<li><a class="dropdown-item" href="<?php echo Sistema::app()->generaURL(["inicial", "Entradas"]); ?>">Ver Entradas</a></li>
								<li><a class="dropdown-item" href="<?php echo Sistema::app()->generaURL(["inicial", "Compras"]); ?>">Ver Compras</a></li>
								<?php
								if (Sistema::app()->Acceso()->puedePermiso(2)) {
								?>
									<li><a class="dropdown-item" href="<?php echo Sistema::app()->generaURL(["admin", "Index"]) ?>">Administracion</a></li>
								<?php
								}
								?>
								<li>
									<hr class="dropdown-divider">
								</li>
								<li><a class="dropdown-item" href="<?php echo Sistema::app()->generaURL(["registro", "Cerrar"]) ?>">Cerrar Sesion</a></li>
							</ul>
						</li>
					</ul>
				</div>
			<?php
			} // End if
			else {
			?>
				<a href="<?php echo Sistema::app()->generaURL(["registro", "Login"]); ?>" title="Inciar Sesion"><img src="/imagenes/aplicacion/login.png" alt=""></a>
			<?php
			} // End else
			?>
		</article>
	</header>

	<!-- ESTAMOS DEFINIENDO LA ETIQUETA NAV -->
	<nav>
		<ul class="nav justify-content-center">
			<li class="nav-item">
				<a class="nav-link" aria-current="page" href="<?php echo Sistema::app()->generaURL(["inicial"]) ?>">Inicio</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo Sistema::app()->generaURL(["quienes"]) ?>">Quienes Somos</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo Sistema::app()->generaURL(["foro", "Foro_Cinema"]) ?>">Foro de Cinema</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo Sistema::app()->generaURL(["cataproducto", "Productos"]) ?>">Productos</a>
			</li>
		</ul>
	</nav>

	<!-- ESTAMOS DEFINIENDO EL MAIN CONTENIDO -->
	<main>
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
		<?php echo $contenido; ?>
	</main>

	<!-- ESTAMOS DEFINIENDO EL FOOTER -->
	<footer>
		<div class="footer-section-1">
			<div class="footer-section-2">
				<h3>Enlaces</h3>
				<ul>
					<li><a href="<?php echo Sistema::app()->generaURL(["inicial"]); ?>">Inicio</a></li>
					<li><a href="<?php echo Sistema::app()->generaURL(["quienes"]) ?>">Acerca de nosotros</a></li>
					<li><a href="<?php echo Sistema::app()->generaURL(["cataproducto", "Productos"]) ?>">Productos</a></li>
				</ul>
			</div>
			<div class="footer-section-2">
				<h3>Contacto</h3>
				<p>Dirección: Camino Fuente Mora Nº11</p>
				<p>Teléfono: 627618646</p>
				<p>Email: alberto.godoybor@gmail.com</p>
			</div>
		</div>
		<div class="copy">
			<hr>
			<p>© 2023 Cinema!! Realizado por Alberto Godoy Borrego</p>
		</div>
	</footer>
</body>

</html>