<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo $titulo; ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">

	<!-- ESTAMOS ENLAZANDO CSS Y UN FAVICON -->
	<link rel="stylesheet" type="text/css" href="/estilos/quienes_somos.css">
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
		<div id="imagen">
			<h3><a href="<?php echo Sistema::app()->generaURL(["inicial"]) ?>">Cinema</a></h3>
		</div>

		<div id="registros">
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
		</div>
	</header>

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

	<main>
		<figure>
			<img src="/imagenes/aplicacion/show.jpeg" alt="Espectaculo">
		</figure>

		<aside>
			<article>
				<h3>¿Qué es Cinema?</h3>
				<p>Hola me llamo Alberto, tengo 23 años. Es una empresa de espectáculos que se encarga
					de mostrar espectáculos y reservas de entradas con una tienda propia de alimentos. 

					Nuestra funcion es satisfacer las necesidades de nuestros clientes dedicada a la programación y 
					organización de eventos, nuestro universo es la realizacion de eventos para que las personas disfruten
					de los espectaculos y se sienten seguros y conformes.

					Con toda una vida de experiencia en el sector del espectáculo hemos colaborado con los ayuntamientos, 
					comisiones de fiestas, empresas privadas, sociedades, particulares , teatros, etc... siempre aportando 
					nuestra experiencia , dando a la empresa y al artista el mayor apoyo como empresa de espectáculos.

					Somos la nueva solución en ticketing donde puedes tener acceso a los eventos más importantes de Malaga generando 
					una experiencia de usuario única para facilitar tu proceso de compra y venta de tickets.

					<br>

					<span>Tuve que buscarme la vida y mi propia oportunidad. Pero lo logré. No te sientes 
						y esperes a que lleguen las oportunidades. Levántate y hazlas
					</span>
				</p>
			</article>
		</aside>

		<section>
			<article>
				<h3>Funciones</h3>
				<ul>
					<li>Servicio de Compras / Ventas de Productos</li>
					<li>Venta de Productos de Espectáculos</li>
					<li>Proporcionar soporte y liberar carga a los miembros del staff y direccion deportiva</li>
				</ul>
			</article>

			<article>
				<h3>Consejos para Convertirte en un Empresario de Espectaculos</h3>
				<ol>
					<li>Educarte y desarrollar tus Habilidades
						<ul>
							<li>Aprender todo lo posible sobre los Espectáculos</li>
							<li>Mostrar Confianza y Seguridad a los Clientes</li>
						</ul>
					</li>
					<li>Obtener experiencia
						<ul>
							<li>Mejorar dotes de comunicación</li>
						</ul>
					</li>
					<li>Alcanzar un nivel profesional
						<ul>
							<li>Perfeccionar la información y mostrarla de una manera entendible</li>
						</ul>
					</li>
				</ol>
			</article>
		</section>
	</main>
</body>

</html>