-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-06-2023 a las 13:29:51
-- Versión del servidor: 8.0.32
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_final`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acl_roles`
--

CREATE TABLE `acl_roles` (
  `cod_acl_role` int NOT NULL,
  `nombre` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `perm1` tinyint(1) NOT NULL DEFAULT '0',
  `perm2` tinyint(1) NOT NULL DEFAULT '0',
  `perm3` tinyint(1) NOT NULL DEFAULT '0',
  `perm4` tinyint(1) NOT NULL DEFAULT '0',
  `perm5` tinyint(1) NOT NULL DEFAULT '0',
  `perm6` tinyint(1) NOT NULL DEFAULT '0',
  `perm7` tinyint(1) NOT NULL DEFAULT '0',
  `perm8` tinyint(1) NOT NULL DEFAULT '0',
  `perm9` tinyint(1) NOT NULL DEFAULT '0',
  `perm10` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `acl_roles`
--

INSERT INTO `acl_roles` (`cod_acl_role`, `nombre`, `perm1`, `perm2`, `perm3`, `perm4`, `perm5`, `perm6`, `perm7`, `perm8`, `perm9`, `perm10`) VALUES
(1, 'normal', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'administrador', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acl_usuarios`
--

CREATE TABLE `acl_usuarios` (
  `cod_acl_usuario` int NOT NULL,
  `nick` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL DEFAULT '',
  `contrasenia` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `cod_acl_role` int NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

--
-- Volcado de datos para la tabla `acl_usuarios`
--

INSERT INTO `acl_usuarios` (`cod_acl_usuario`, `nick`, `nombre`, `contrasenia`, `cod_acl_role`, `borrado`) VALUES
(1, 'admin', 'Administrador', 'cd3a64d32211f9c88c962f9c7fababb7', 2, 0),
(2, 'agb_2000', 'alberto', 'bb05f1a210d6ca50e9d56cecb37fae78', 2, 0),
(3, 'juan_25', 'juan', 'ee2399c5536ff1aeba755e7c790f0a02', 1, 0),
(4, 'alvaro 25', 'alvaro', '820a7d29d67d02c05a8cea1916132804', 1, 0),
(5, 'agb_2001', 'alberto', 'bb05f1a210d6ca50e9d56cecb37fae78', 1, 0),
(6, 'alvaro_25', 'alvaro', '820a7d29d67d02c05a8cea1916132804', 1, 0),
(7, 'merche', 'mercedes', '63025ab967fb69e146e51ad890c0da57', 1, 0),
(8, 'prueba', 'prueba', '463d35f663ab1d7ff9083b0da3a71b52', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_espectaculo`
--

CREATE TABLE `categoria_espectaculo` (
  `cod_categoria_espectaculo` int NOT NULL,
  `nombre_categoria_espectaculo` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `categoria_espectaculo`
--

INSERT INTO `categoria_espectaculo` (`cod_categoria_espectaculo`, `nombre_categoria_espectaculo`, `borrado`) VALUES
(1, 'musical', 0),
(2, 'teatro', 0),
(3, 'humor', 0),
(4, 'musical 25', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_foro`
--

CREATE TABLE `categoria_foro` (
  `cod_categoria_foro` int NOT NULL,
  `nombre_categoria_foro` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `contador_comentarios` int NOT NULL,
  `fecha_creacion` date NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `categoria_foro`
--

INSERT INTO `categoria_foro` (`cod_categoria_foro`, `nombre_categoria_foro`, `contador_comentarios`, `fecha_creacion`, `borrado`) VALUES
(1, 'hemos creado nuestro primer foro en la aplicacion', 4, '2023-06-03', 0),
(2, 'prueba 2', 4, '2023-06-03', 0),
(3, 'prueba 3', 1, '2023-06-05', 0),
(4, 'hemos creado un foro para saber la opiniones de la gente sobre un espectaculo', 0, '2023-06-15', 0),
(5, 'asfafsfsasaf', 0, '2023-06-17', 1),
(6, 'sdasda', 0, '2023-06-17', 1),
(7, 'safasf', 0, '2023-06-19', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_producto`
--

CREATE TABLE `categoria_producto` (
  `cod_categoria_producto` int NOT NULL,
  `nombre_categoria` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `descripcion_categoria` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `categoria_producto`
--

INSERT INTO `categoria_producto` (`cod_categoria_producto`, `nombre_categoria`, `descripcion_categoria`, `borrado`) VALUES
(1, 'pantalones', 'Hemos añadido la categoría pantalones 25', 0),
(2, 'camiseta 25', 'Hemos añadido la categoría camiseta', 1),
(3, 'camiseta', 'hemos añadido la categoría calcetines', 0),
(4, 'comida', 'Hemos añadido la categoria comida', 0),
(5, 'bebidas', 'Hemos añadido la categoría bebida', 0),
(6, 'Complementos', 'Hemos añadido la categoría complementos', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cod_cliente` int NOT NULL,
  `nombre_cliente` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `apellidos_cliente` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `nick_cliente` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `nif_cliente` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `poblacion` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cod_cliente`, `nombre_cliente`, `apellidos_cliente`, `nick_cliente`, `nif_cliente`, `fecha_nacimiento`, `poblacion`, `borrado`) VALUES
(1, 'alberto', 'godoy borrego', 'agb_2000', '25363534L', '2000-09-06', 'Antequera', 0),
(2, 'juan', 'gonzalez borrego', 'juan_25', '56891425K', '2001-06-06', 'Mollina', 0),
(3, 'alvaro', 'guerrero linares', 'alvaro 25', '14253698P', '2005-01-12', 'Antequera', 0),
(4, 'alberto', 'godoy borrego', 'agb_2001', '10203040L', '2000-09-06', 'Antequera', 0),
(5, 'alvaro', 'gonzalez borrego', 'alvaro_25', '14259678M', '2005-06-10', 'Mollina', 0),
(6, 'mercedes', 'gomez borrego', 'merche', '14859678L', '2005-06-13', 'Antequera', 0),
(7, 'prueba', 'prueba', 'prueba', '36958475K', '1999-06-24', 'Antequera', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `cod_comentarios` int NOT NULL,
  `cod_espectaculo` int NOT NULL,
  `cod_cliente` int NOT NULL,
  `comentario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fecha_publicacion` date NOT NULL,
  `valoracion` int NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`cod_comentarios`, `cod_espectaculo`, `cod_cliente`, `comentario`, `fecha_publicacion`, `valoracion`, `borrado`) VALUES
(4, 2, 1, 'hemos añadido nuestro primer comentario a la espectáculo', '2023-06-10', 1, 0),
(5, 2, 1, 'agfafsfassfafsaafs', '2023-06-10', 4, 0),
(6, 2, 1, 'fsafsafas', '2023-06-10', 4, 0),
(7, 1, 1, 'hemos añadido nuestro primer comentario', '2023-06-16', 3, 1),
(8, 2, 1, 'aasfasf', '2023-06-17', 2, 0),
(9, 1, 1, 'sasafsa', '2023-06-17', 1, 0);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cons_clientes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cons_clientes` (
`apellidos_cliente` varchar(60)
,`borrado` tinyint(1)
,`cod_acl_role` int
,`cod_cliente` int
,`fecha_nacimiento` date
,`nick_cliente` varchar(32)
,`nif_cliente` varchar(9)
,`nombre_cliente` varchar(60)
,`nombre_role` varchar(30)
,`poblacion` varchar(32)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cons_comentarios_espectaculos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cons_comentarios_espectaculos` (
`borrado` tinyint(1)
,`cod_cliente` int
,`cod_comentarios` int
,`cod_espectaculo` int
,`comentario` text
,`fecha_publicacion` date
,`nick_cliente` varchar(32)
,`valoracion` int
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cons_comentarios_foro`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cons_comentarios_foro` (
`borrado` tinyint(1)
,`cod_categoria_foro` int
,`cod_contenido_foro` int
,`contenido_foro` text
,`fecha_publicacion` date
,`nick_cliente` varchar(32)
,`nombre_categoria_foro` varchar(120)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cons_compras`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cons_compras` (
`cod_usuario` int
,`cod_ventas` int
,`fecha` date
,`importe_total` float(5,2)
,`nick_cliente` varchar(32)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cons_compras_productos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cons_compras_productos` (
`cod_producto` int
,`cod_ventas` int
,`cod_ventas_productos` int
,`fecha` date
,`imagen` text
,`importe_base` float(5,2)
,`importe_total` float(5,2)
,`nick_cliente` varchar(32)
,`nombre_producto` varchar(60)
,`unidades` int
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cons_espectaculo_categoria`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cons_espectaculo_categoria` (
`borrado` tinyint(1)
,`cod_categoria_espectaculo` int
,`cod_espectaculos` int
,`duracion` int
,`fecha_finalizacion` date
,`fecha_lanzamiento` date
,`imagen` text
,`nombre_categoria_espectaculo` varchar(120)
,`sinopsis` text
,`titulo` varchar(60)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cons_espe_sala`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cons_espe_sala` (
`borrado` tinyint(1)
,`capacidad_maxima` int
,`cod_espe_sala` int
,`cod_espectaculos` int
,`cod_sala` int
,`nombre_sala` varchar(120)
,`titulo` varchar(60)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cons_participantes_espectaculos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cons_participantes_espectaculos` (
`borrado` tinyint(1)
,`cod_espectaculo` int
,`cod_participantes` int
,`cod_participantes_espectaculos` int
,`duracion` int
,`fecha_finalizacion` date
,`fecha_lanzamiento` date
,`imagen` text
,`nombre_participante` varchar(50)
,`titulo` varchar(60)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cons_productos_categorias`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cons_productos_categorias` (
`borrado` tinyint(1)
,`cod_categoria_producto` int
,`cod_producto` int
,`fecha_alta` date
,`imagen` text
,`nombre_categoria` varchar(60)
,`nombre_producto` varchar(60)
,`precio` float(5,2)
,`unidades` int
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cons_reservas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cons_reservas` (
`cod_cliente` int
,`cod_espectaculos` int
,`cod_reserva` int
,`cod_sesion` int
,`nick_cliente` varchar(32)
,`nombre_sala` varchar(120)
,`num_asientos` int
,`precio_total` float(5,2)
,`titulo` varchar(60)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cons_sesiones`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cons_sesiones` (
`borrado` tinyint(1)
,`cod_espe_sala` int
,`cod_sala` int
,`cod_sesion` int
,`fecha` date
,`hora_fin` time
,`hora_inicio` time
,`nombre_sala` varchar(120)
,`titulo` varchar(60)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenido_foro`
--

CREATE TABLE `contenido_foro` (
  `cod_contenido_foro` int NOT NULL,
  `cod_categoria_foro` int NOT NULL,
  `cod_cliente` int NOT NULL,
  `contenido_foro` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fecha_publicacion` date NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `contenido_foro`
--

INSERT INTO `contenido_foro` (`cod_contenido_foro`, `cod_categoria_foro`, `cod_cliente`, `contenido_foro`, `fecha_publicacion`, `borrado`) VALUES
(5, 1, 2, 'Hemos añadido nuestro primer comentario', '2023-06-04', 0),
(6, 2, 2, 'fsafsafasfasafs', '2023-06-04', 0),
(7, 2, 2, 'fasfsaasffasfas', '2023-06-04', 0),
(8, 2, 2, 'fassfafasfas', '2023-06-04', 1),
(9, 2, 2, 'afsfsaasfafs', '2023-06-04', 0),
(10, 2, 2, 'gfasfsaasgagsgsa', '2023-06-04', 0),
(11, 1, 5, 'prueba', '2023-06-11', 0),
(12, 1, 5, 'afsfsafsaafsfsasfafsafsasaf', '2023-06-11', 0),
(13, 1, 1, 'fasfasafsafasf', '2023-06-12', 0),
(14, 4, 1, 'sadsasad', '2023-06-15', 1),
(15, 3, 1, 'afsasffas', '2023-06-18', 0);

--
-- Disparadores `contenido_foro`
--
DELIMITER $$
CREATE TRIGGER `ai_contenido_foro` AFTER INSERT ON `contenido_foro` FOR EACH ROW UPDATE categoria_foro 
   	SET contador_comentarios = contador_comentarios + 1 
	WHERE cod_categoria_foro = (
        SELECT cf.cod_categoria_foro
			FROM contenido_foro cf 
        	WHERE cf.cod_contenido_foro = (SELECT MAX(cod_contenido_foro) from contenido_foro)
    )
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espectaculos`
--

CREATE TABLE `espectaculos` (
  `cod_espectaculos` int NOT NULL,
  `cod_categoria_espectaculo` int NOT NULL,
  `titulo` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `duracion` int NOT NULL,
  `fecha_lanzamiento` date NOT NULL,
  `fecha_finalizacion` date NOT NULL,
  `sinopsis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `imagen` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `espectaculos`
--

INSERT INTO `espectaculos` (`cod_espectaculos`, `cod_categoria_espectaculo`, `titulo`, `duracion`, `fecha_lanzamiento`, `fecha_finalizacion`, `sinopsis`, `imagen`, `borrado`) VALUES
(1, 2, 'concierto de lola indigo', 100, '2023-06-19', '2023-06-20', 'lola indigo da nombre a la banda femenina que está arrasando en el panorama musical urbano a nivel nacional. liderada por la vocalista mimi doblas, la banda está formada por las bailarinas mónica peña, saydi lubanzadio, laura ruiz y claudia riera. como artista urbana se ha asentado en un estilo que fusiona diferentes géneros musicales como pop, dance, trap, funk y reggaeton. \r\n\r\nla cantante y bailarina miriam doblas muñoz es la principal cara del grupo. y aunque nació en madrid en 1992, la artista se considera originaria de la localidad granadina de huétor tájar, donde fue criada desde bien pequeña. fue profesora de baile y participó en algunos musicales. como bailarina trabajó junto a artistas como chris brown, miguel bosé y enrique iglesias. en 2010 participó en el concurso fama revolution y siete años después se presentó al concurso de talentos operación triunfo, donde inició su carrera musical.  ', 'https://cd1.taquilla.com/data/images/t/aa/lola-indigo__330x275.jpeg', 0),
(2, 1, 'concierto wah madrid', 120, '2023-06-17', '2023-06-29', 'el show más vibrante, rotundo e innovador de los últimos tiempos. música, espectáculo gastronomía como nunca antes lo habías vivido. un espectáculo dividido en 3 actos que tiene como hilo conductor, las mejores canciones de la historia de la música, interpretadas por un espectacular elenco artístico. un show al más puro estilo las vegas y broadway.\r\n\r\nprimer acto: gastronomía viajera y divertida en un espectacular street food market donde probar comida de los 5 continentes, mientras disfrutas de actuaciones en vivo para calentar motores.\r\nsegundo acto: un impactante y emocionante show, con los mejores temas de la historia de la música interpretados en directo por grandes artistas.\r\ntercer acto: un aftershow, donde seguir disfrutando de la música y de la diversión, con dj, actuaciones en vivo y los mejores cócteles de la ciudad.\r\nla vida es sólo una excusa para celebrar. wah es el lugar perfecto para hacerlo.', 'https://wahshow.com/assets/img/og-image-wah.jpg', 0),
(3, 2, 'concierto laponia', 85, '2023-06-18', '2023-06-23', 'mentiras, verdades, ilusiones y la educación que recibimos desde pequeños... \r\nlaponia es una comedia de cristina clemente y marc angelet que convierte una velada de fiesta e ilusión en una batalla entre hermanas, parejas y cuñados. una obra que nos hará pensar y reír a la vez que nos planteará diferentes situaciones éticas y morales que nos podrían pasar a todos. ¿la verdad es tan buena como dicen y la mentira tan mala?\r\nlaponia es el lugar en el que dos familias se encuentran con la intención de celebrar la nochebuena y la llegada de papá noel. todo parece ir bien hasta que una de las criaturas explica al otro que papá noel no existe, y que es un personaje que ha sido creado para que los padres puedan controlar a sus hijos y hacer que se comporten bien.', 'https://cdn.atrapalo.com/common/photo/event/4/8/7/8237/1411677/si_880_267.jpg', 0),
(4, 1, 'woman, más que un musical', 100, '2023-06-17', '2023-06-30', 'como ya indica indica su nombre, woman, más que un musical llega a los teatros enfocándose en ilustrar el poderío histórico de la mujer. se trata de la primera apuesta de la productora de aarón vivancos, productor, director, coreógrafo y compositor musical.\r\n\r\nun show en el que será fácil identificarse con las situaciones que muchas mujeres han experimentado a lo largo de su vida. un cóctel en el que se combinan la danza interpretativa con el flamenco de vanguardia, además de las profundas interpretaciones y efectos visuales, que harán posible este espectáculo con un mensaje tan potente como es la libertad a través del empoderamiento.\r\n\r\ntodo ello estará acompañado por una banda sonora grabada por la orquesta sinfónica de budapest y un elenco de siete bailarinas, un bailarín y una actriz. además, la conocida marca española alvarno ha sido la encargada de diseñar el llamativo vestuario. ', 'https://www.topgrups.com/img/image/fotos/topgrupswebconweb.jpg?w=600&h=400&zc=1&aoe=1&q=80', 0),
(5, 3, 'galder varas', 100, '2023-06-19', '2023-06-22', 'el cómico y guionista galder varas se sube a los escenarios con esto no es un show, un monólogo en el que las bromas y las risas no faltarán. pero, \"si esto no es un show\", te estarás preguntando, ¿de qué se trata entonces? pues probablemente vaya de lo mismo que otros muchos shows de stand up comedy.\r\n\r\nun escenario, un micrófono y un cómico. también puede ser que haya mucha improvisación, chistes y anécdotas diferentes a los que hayas visto de galder varas en redes. lo que sí puedes tener por seguro es que te reirás a carcajadas.', 'https://cd1.taquilla.com/data/images/t/97/galder-varas__330x275.jpg', 0),
(6, 1, 'mamma mía! - el musical', 140, '2023-06-19', '2023-06-29', 'basado en las canciones originales del grupo sueco de pop abba, mamma mía! es un musical que lleva desde 1999, año de su estreno en el teatro prince edward de londres, triunfando por grandes teatros de todo el mundo. inspirado en la película buona sera, mrs. campbell, el argumento narra la divertida historia de una hija a punto de casarse y de su madre, que es visitada por tres antiguos romances que tratan de volver a conquistarla.', 'https://cd1.taquilla.com/data/images/t/8c/mamma-mia-el-musical__330x275.jpg', 0),
(7, 1, 'aladdin, el musical', 165, '2023-06-19', '2023-06-19', 'basado en el libreto de chad beguelin, aladdín es la historia de un joven pobre que se dedica a robar y engañar a los habitantes de ágrabah junto a su incondicional mono abú. en su tarea de sobrevivir, sueña con convertirse en alguien importante.  un día conoce a jafar, un malvado hechicero que le propondrá conseguir todas las riquezas que se pueda imaginar a cambio de que le consigue una vieja lámpara que se encuentra en las profundidades de la cueva de las maravillas. sin embargo, todo cambia cuando conoce a jasmine, la hija del sultán de la que se enamora profundamente y al divertido genio de la lámpara que le concederá tres deseos. ¿uno de ellos? convertirse en un príncipe para así conseguir que jasmine su fije en él.', 'https://cd1.taquilla.com/data/images/t/22/aladdin-el-musical-stage-entertainment__330x275.jpg', 0),
(8, 1, 'cantando bajo la lluvia', 150, '2023-06-21', '2023-07-13', 'cantando bajo la lluvia, el musical nos cuenta una historia de amor ambientada en la época del nacimiento del cine sonoro en la industria de hollywood. una comedia ambientada en la icónica década de los años 20, y repleta de guiños al mundo del cine, de humor y de sensibilidad\r\n\r\nel musical cuenta con un divertido guion de betty comden y adolph green, y con música de nacio herb brown y arthur freed. nos narra de forma irónica el paso del cine mudo al sonoro y las dificultades que tuvieron los profesionales y artistas por adaptarse a las nuevas técnicas. una propuesta que no dejará indiferente a nadie y que los amantes del género disfrutarán como niños.', 'https://cd1.taquilla.com/data/images/t/6e/cantando-bajo-la-lluvia-el-musical__330x275.jpg', 0),
(9, 1, 'el rey león', 185, '2023-06-21', '2023-06-30', 'basada en la película homónima de disney estrenada en 1994, el musical de el rey león cuenta la historia de simba, el heredero del trono del reino de pride lands. los leones mufasa y sarabi gobiernan sobre todos los animales de la sabana africana. cuando estos conciben a su hijo y heredero, simba, las pocas esperanzas de scar, el hermano de mufasa, de convertirse en el nuevo rey se ven truncadas, por lo que tramará un siniestro plan para hacerse con el poder: deshacerse el joven heredero al trono.', 'https://cd1.taquilla.com/data/images/t/bc/el-rey-leon__330x275.jpg', 0),
(10, 2, 'les luthiers', 60, '2023-06-30', '2023-07-13', 'les luthiers es un conocido grupo humorístico y musical, mundialmente conocido por sus espectáculos de humor donde emplean instrumentos que ellos mismos elaboran a partir de materiales útiles de la vida diaria. \r\n\r\nles luthiers han actuado en el teatro colón de buenos aires, el palacio de las bellas artes de méxico, el sodre de montevideo, el lincoln center de nueva york, ifema palacio municipal, el teatro de la maestranza de sevilla, el kursaal de san sebastian y el palacio euskalduna de bilbao entre muchos otros grandes recintos por todo el mundo.', 'https://cd1.taquilla.com/data/images/t/6b/les-luthiers__330x275.jpg', 0),
(11, 2, 'una terapia integral madrid', 10000, '2023-06-19', '2023-06-19', 'cristina clemente y marc angelet son los autores de \'una terapia integral\', la comedia que trata de retratar a una sociedad que parece estar eliminando la religión, pero que a su vez tiene una gran necesidad de creer, tener fe en algo que nos ayude a encontrar sentido a nuestras vidas y a poner orden en ellas. \r\n\r\n¿una corteza poco crujiente? problemas laborales ¿una miga demasiado densa? crisis de pareja ¿un pan soso? una vida sexual insatisfactoria.\r\n\r\ntodos quieren apuntarse a los cursos de hacer pan que imparte toni roca. sus diez años de experiencia y las pocas plazas ofertadas hacen que la gente haga cola para apuntarse. para el maestro, \"somos el pan que hacemos\" y su curso garantiza que, después de unas cuantas sesiones, el alumno será capaz de hacer un pan excelente. ', 'https://cd1.taquilla.com/data/images/t/89/una-terapia-integral__330x275.jpg', 0),
(12, 3, 'miguel lago', 75, '2023-06-19', '2023-06-19', 'el cómico gallego miguel lago lleva al público un nuevo show y esta vez no se anda con rodeos. el humorista ha decidido jugarse \'todo al negro\'. tras haber estado en radio, televisión, teatro y cine, lago ha optado en esta ocasión por un escenario diferente: el caesar palace de las vegas. el lugar dónde han actuado los más grandes artistas del planeta es el próximo destino en el que el cómico ha fijado la mirada. \r\n\r\npero no os penséis que se ha recorrido el océano para llegar hasta allí, como no controla mucho el inglés y al final hay que ser prácticos, ha optado por traerse el icónico casino hasta españa. ahora bien, el lema es el mismo estemos en una punta del globo o en otra: \"lo que pasa en las vegas, se queda en las vegas\".', 'https://cd1.taquilla.com/data/images/t/90/miguel-lago__330x275.jpg', 0),
(13, 3, 'no cruces los brazos', 75, '2023-06-30', '2023-07-13', 'atención! ya está de vuelta david cepo y su nuevo show \"no cruces los brazos\". si estás buscando una noche llena de risas y diversión, ¡no te lo puedes perder! \r\n\r\ndavid cepo es un cómico de nacimiento y una sensación en las redes sociales gracias a su espontaneidad y cercanía con su público. ahora, te ofrece la oportunidad de vivir en persona su espectáculo cargado de humor y carcajadas, donde te convertirás en el protagonista del show.\r\n\r\n¿te lo vas a perder? como bien dice david cepo: \"no cruces los brazos que no te entran los chistes\". ', 'https://cd1.taquilla.com/data/images/t/c8/david-cepo__330x275.jpeg', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espe_sala`
--

CREATE TABLE `espe_sala` (
  `cod_espe_sala` int NOT NULL,
  `cod_espectaculos` int NOT NULL,
  `cod_sala` int NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `espe_sala`
--

INSERT INTO `espe_sala` (`cod_espe_sala`, `cod_espectaculos`, `cod_sala`, `borrado`) VALUES
(1, 1, 1, 0),
(2, 1, 2, 0),
(3, 2, 1, 0),
(4, 2, 2, 0),
(5, 4, 2, 0),
(6, 4, 1, 0),
(7, 5, 2, 0),
(8, 5, 1, 0),
(9, 6, 1, 0),
(10, 6, 3, 0),
(11, 7, 2, 0),
(12, 8, 2, 0),
(13, 8, 3, 0),
(14, 9, 1, 0),
(15, 10, 3, 0),
(16, 10, 2, 0),
(17, 3, 2, 0),
(18, 11, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participantes`
--

CREATE TABLE `participantes` (
  `cod_participante` int NOT NULL,
  `nombre_participante` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `participantes`
--

INSERT INTO `participantes` (`cod_participante`, `nombre_participante`, `borrado`) VALUES
(1, 'lola indigo', 0),
(2, 'alberto', 0),
(3, 'alvaro', 0),
(4, 'juan', 0),
(5, 'mercedes', 0),
(6, 'aarón vivancos', 0),
(7, 'galder varas', 0),
(8, 'aladin', 0),
(9, 'les luthiers', 0),
(10, 'antonio molero', 0),
(11, 'marta poveda', 0),
(12, 'miguel lago', 0),
(13, 'david cepo', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participantes_espectaculos`
--

CREATE TABLE `participantes_espectaculos` (
  `cod_participantes_espectaculos` int NOT NULL,
  `cod_participantes` int NOT NULL,
  `cod_espectaculo` int NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `participantes_espectaculos`
--

INSERT INTO `participantes_espectaculos` (`cod_participantes_espectaculos`, `cod_participantes`, `cod_espectaculo`, `borrado`) VALUES
(1, 1, 1, 0),
(2, 2, 1, 1),
(3, 2, 2, 0),
(4, 3, 3, 0),
(5, 4, 3, 0),
(6, 5, 3, 0),
(7, 6, 4, 0),
(8, 7, 5, 0),
(9, 8, 7, 0),
(10, 5, 6, 0),
(11, 3, 6, 0),
(12, 2, 8, 0),
(13, 2, 9, 0),
(14, 4, 9, 0),
(15, 10, 11, 0),
(16, 11, 11, 0),
(17, 9, 10, 0),
(18, 13, 13, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `cod_producto` int NOT NULL,
  `cod_categoria_producto` int NOT NULL,
  `nombre_producto` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `unidades` int NOT NULL,
  `precio` float(5,2) NOT NULL,
  `fecha_alta` date NOT NULL,
  `imagen` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`cod_producto`, `cod_categoria_producto`, `nombre_producto`, `unidades`, `precio`, `fecha_alta`, `imagen`, `borrado`) VALUES
(1, 3, 'Camiseta Oficial de Espectáculos ', 0, 10.25, '2023-05-16', 'https://kedabe.net/wp-content/uploads/2019/10/camiseta-hombre-cuello-redondo-manga-corta-gris-antracita-frontal.png', 0),
(2, 3, 'Camiseta de Star Wars', 5, 30.45, '2023-05-14', 'https://falabella.scene7.com/is/image/FalabellaCO/gsc_118359825_2026810_1?wid=800&hei=800&qlt=70', 0),
(3, 1, 'Pantalones Star Wars', 0, 45.50, '2023-05-14', 'https://d3ugyf2ht6aenh.cloudfront.net/stores/398/159/products/pantalon-peppers-star-wars-11-5a49fb43e08b513e9216530724794483-640-0.webp', 0),
(4, 4, 'Palomitas', 4, 4.20, '2023-05-14', 'https://ametllerorigen.vtexassets.com/arquivos/ids/168890-800-auto?v=637552197571570000&width=800&height=auto&aspect=true', 0),
(5, 5, 'Coca-Cola', 17, 2.20, '2023-05-14', 'https://i0.wp.com/thefoodway.es/wp-content/uploads/2022/08/COCA-COLA-05.jpg?fit=800%2C800&ssl=1', 0),
(6, 5, 'Fanta', 1, 2.30, '2023-05-14', 'https://a2.soysuper.com/d5a1f3a8be97d2ab825c6ffc8f526fb9.340.340.0.min.wmark.2085b35d.jpg', 0),
(8, 6, 'CD de la banda sonora', 20, 10.50, '2023-06-19', 'https://e7.pngegg.com/pngimages/552/565/png-clipart-aladdin-walt-disney-platinum-and-diamond-editions-blu-ray-disc-dvd-the-walt-disney-company-aladdin-film-lion-king-thumbnail.png', 0),
(9, 4, 'Agua', 50, 1.00, '2023-06-18', 'https://ohffice.es/190059-large_default/pack-24-botellas-agua-font-vella-50-cl.jpg', 0),
(10, 5, 'Sudadera Con Capucha', 10, 20.55, '2023-06-19', 'https://www.multiuniformes.com/productos/imagenes/img_145666_c628d628b5ff902029bf9be230ee1526_1.jpg', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservar`
--

CREATE TABLE `reservar` (
  `cod_reserva` int NOT NULL,
  `cod_sesion` int NOT NULL,
  `cod_cliente` int NOT NULL,
  `num_asientos` int NOT NULL,
  `precio_total` float(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `reservar`
--

INSERT INTO `reservar` (`cod_reserva`, `cod_sesion`, `cod_cliente`, `num_asientos`, `precio_total`) VALUES
(1, 5, 1, 21, 20.00),
(2, 5, 1, 22, 20.00),
(3, 5, 1, 23, 20.00),
(4, 5, 1, 16, 20.00),
(5, 5, 1, 17, 20.00),
(6, 6, 1, 19, 20.00),
(7, 6, 1, 20, 20.00),
(8, 6, 1, 21, 20.00),
(9, 6, 1, 15, 20.00),
(10, 6, 1, 10, 20.00),
(11, 6, 1, 5, 20.00),
(12, 1, 1, 18, 20.00),
(13, 1, 1, 21, 20.00),
(14, 1, 1, 22, 20.00),
(15, 6, 1, 31, 20.00),
(16, 6, 1, 32, 20.00),
(17, 1, 1, 30, 20.00),
(18, 1, 1, 31, 20.00),
(19, 1, 2, 9, 20.00),
(20, 1, 2, 10, 20.00),
(21, 1, 1, 14, 20.00),
(22, 1, 1, 15, 20.00),
(27, 8, 1, 18, 50.75),
(28, 8, 1, 20, 50.75),
(29, 8, 1, 31, 50.75),
(30, 8, 1, 32, 50.75),
(31, 6, 1, 2, 20.00),
(32, 6, 1, 3, 20.00),
(33, 6, 1, 4, 20.00),
(34, 6, 1, 7, 20.00),
(35, 6, 1, 8, 20.00),
(36, 6, 1, 9, 20.00),
(37, 2, 1, 32, 20.00),
(38, 2, 1, 33, 20.00),
(39, 14, 2, 20, 60.78),
(40, 14, 2, 21, 60.78);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sala`
--

CREATE TABLE `sala` (
  `cod_sala` int NOT NULL,
  `nombre_sala` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `capacidad_maxima` int NOT NULL,
  `precio_sala` float(5,2) NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `sala`
--

INSERT INTO `sala` (`cod_sala`, `nombre_sala`, `capacidad_maxima`, `precio_sala`, `borrado`) VALUES
(1, 'sala 1', 40, 20.00, 0),
(2, 'sala 2', 40, 50.75, 0),
(3, 'sala 3', 34, 60.78, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `cod_sesion` int NOT NULL,
  `cod_espe_sala` int NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `sesiones`
--

INSERT INTO `sesiones` (`cod_sesion`, `cod_espe_sala`, `fecha`, `hora_inicio`, `hora_fin`, `borrado`) VALUES
(1, 1, '2023-06-11', '15:00:00', '20:00:00', 0),
(2, 3, '2023-06-13', '16:00:00', '18:00:00', 0),
(3, 2, '2023-06-11', '16:00:00', '21:00:00', 0),
(4, 4, '2023-06-11', '13:00:00', '15:59:00', 0),
(5, 3, '2023-06-17', '11:39:00', '14:42:00', 0),
(6, 3, '2023-06-18', '14:43:00', '17:46:00', 0),
(7, 5, '2023-06-18', '17:00:00', '19:00:00', 0),
(8, 5, '2023-06-19', '15:00:00', '20:00:00', 0),
(9, 6, '2023-06-19', '16:00:00', '19:00:00', 0),
(10, 5, '2023-06-17', '16:00:00', '18:30:00', 0),
(11, 14, '2023-06-19', '10:46:00', '12:47:00', 0),
(12, 8, '2023-06-20', '11:48:00', '13:50:00', 0),
(13, 9, '2023-06-20', '16:48:00', '18:48:00', 0),
(14, 10, '2023-06-25', '08:52:00', '11:00:00', 0),
(15, 11, '2023-06-15', '12:53:00', '15:56:00', 0),
(16, 11, '2023-06-19', '20:00:00', '22:00:00', 0),
(17, 13, '2023-06-21', '15:25:00', '17:28:00', 0),
(18, 12, '2023-06-20', '15:22:00', '19:20:00', 0),
(19, 17, '2023-06-24', '10:40:00', '13:43:00', 0),
(20, 17, '2023-06-21', '10:42:00', '12:44:00', 0),
(21, 15, '2023-06-24', '11:44:00', '13:46:00', 0),
(22, 16, '2023-06-23', '12:45:00', '15:48:00', 0),
(23, 18, '2023-06-22', '18:50:00', '20:52:00', 0),
(24, 15, '2023-06-23', '13:00:00', '15:00:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `cod_ventas` int NOT NULL,
  `cod_usuario` int NOT NULL,
  `fecha` date NOT NULL,
  `importe_total` float(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`cod_ventas`, `cod_usuario`, `fecha`, `importe_total`) VALUES
(1, 1, '2023-06-07', 0.00),
(2, 1, '2023-06-07', 602.75),
(3, 1, '2023-06-10', 211.65),
(4, 1, '2023-06-10', 493.85),
(5, 1, '2023-06-11', 290.25),
(6, 5, '2023-06-11', 47.55),
(7, 1, '2023-06-12', 123.35),
(8, 1, '2023-06-16', 52.30),
(9, 1, '2023-06-17', 37.65),
(10, 1, '2023-06-17', 2.30),
(11, 1, '2023-06-17', 10.25),
(12, 1, '2023-06-17', 4.00),
(13, 1, '2023-06-17', 53.50),
(14, 1, '2023-06-17', 12.45),
(15, 2, '2023-06-17', 71.90),
(16, 1, '2023-06-18', 62.25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_productos`
--

CREATE TABLE `ventas_productos` (
  `cod_ventas_productos` int NOT NULL,
  `cod_producto` int NOT NULL,
  `cod_ventas` int NOT NULL,
  `unidades` int NOT NULL,
  `importe_base` float(5,2) NOT NULL,
  `importe_total` float(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `ventas_productos`
--

INSERT INTO `ventas_productos` (`cod_ventas_productos`, `cod_producto`, `cod_ventas`, `unidades`, `importe_base`, `importe_total`) VALUES
(1, 2, 2, 10, 50.00, 602.75),
(2, 1, 2, 5, 20.55, 602.75),
(3, 1, 3, 3, 20.55, 211.65),
(4, 2, 3, 3, 50.00, 211.65),
(5, 2, 4, 7, 50.00, 493.85),
(6, 1, 4, 7, 20.55, 493.85),
(7, 3, 5, 5, 45.50, 290.25),
(8, 1, 5, 5, 10.25, 290.25),
(9, 6, 5, 5, 2.30, 290.25),
(10, 6, 6, 2, 2.30, 47.55),
(11, 1, 6, 3, 10.25, 47.55),
(12, 5, 6, 5, 2.20, 47.55),
(13, 4, 6, 6, 0.20, 47.55),
(14, 6, 7, 5, 2.30, 123.35),
(15, 2, 7, 3, 30.45, 123.35),
(16, 1, 7, 2, 10.25, 123.35),
(17, 5, 8, 2, 2.20, 52.30),
(18, 6, 8, 3, 2.30, 52.30),
(19, 1, 8, 4, 10.25, 52.30),
(20, 6, 9, 3, 2.30, 37.65),
(21, 1, 9, 3, 10.25, 37.65),
(22, 6, 10, 1, 2.30, 2.30),
(23, 1, 11, 1, 10.25, 10.25),
(24, 4, 12, 20, 0.20, 4.00),
(25, 5, 13, 15, 2.20, 53.50),
(26, 1, 13, 2, 10.25, 53.50),
(27, 5, 14, 1, 2.20, 12.45),
(28, 1, 14, 1, 10.25, 12.45),
(29, 2, 15, 2, 30.45, 71.90),
(30, 5, 15, 5, 2.20, 71.90),
(31, 5, 16, 5, 2.20, 62.25),
(32, 1, 16, 5, 10.25, 62.25);

--
-- Disparadores `ventas_productos`
--
DELIMITER $$
CREATE TRIGGER `ai_ventas_productos` AFTER INSERT ON `ventas_productos` FOR EACH ROW UPDATE producto p
	set p.unidades = p.unidades - new.unidades
    where p.cod_producto = new.cod_producto
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cons_clientes`
--
DROP TABLE IF EXISTS `cons_clientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cons_clientes`  AS SELECT `c`.`cod_cliente` AS `cod_cliente`, `c`.`nombre_cliente` AS `nombre_cliente`, `c`.`apellidos_cliente` AS `apellidos_cliente`, `c`.`nick_cliente` AS `nick_cliente`, `c`.`nif_cliente` AS `nif_cliente`, `c`.`fecha_nacimiento` AS `fecha_nacimiento`, `c`.`poblacion` AS `poblacion`, `aclr`.`nombre` AS `nombre_role`, `aclr`.`cod_acl_role` AS `cod_acl_role`, `c`.`borrado` AS `borrado` FROM ((`acl_usuarios` `aclu` join `cliente` `c` on((`aclu`.`nick` = `c`.`nick_cliente`))) join `acl_roles` `aclr` on((`aclu`.`cod_acl_role` = `aclr`.`cod_acl_role`)))  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cons_comentarios_espectaculos`
--
DROP TABLE IF EXISTS `cons_comentarios_espectaculos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`proyecto_final`@`localhost` SQL SECURITY DEFINER VIEW `cons_comentarios_espectaculos`  AS SELECT `coment`.`cod_comentarios` AS `cod_comentarios`, `coment`.`cod_espectaculo` AS `cod_espectaculo`, `coment`.`cod_cliente` AS `cod_cliente`, `coment`.`comentario` AS `comentario`, `coment`.`fecha_publicacion` AS `fecha_publicacion`, `coment`.`valoracion` AS `valoracion`, `coment`.`borrado` AS `borrado`, `cli`.`nick_cliente` AS `nick_cliente` FROM (`comentarios` `coment` join `cliente` `cli` on((`coment`.`cod_cliente` = `cli`.`cod_cliente`))) ORDER BY `coment`.`cod_espectaculo` ASC  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cons_comentarios_foro`
--
DROP TABLE IF EXISTS `cons_comentarios_foro`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cons_comentarios_foro`  AS SELECT `cf1`.`cod_contenido_foro` AS `cod_contenido_foro`, `c`.`nick_cliente` AS `nick_cliente`, `cf1`.`contenido_foro` AS `contenido_foro`, `cf1`.`fecha_publicacion` AS `fecha_publicacion`, `cf2`.`cod_categoria_foro` AS `cod_categoria_foro`, `cf2`.`nombre_categoria_foro` AS `nombre_categoria_foro`, `cf1`.`borrado` AS `borrado` FROM ((`contenido_foro` `cf1` join `cliente` `c` on((`cf1`.`cod_cliente` = `c`.`cod_cliente`))) join `categoria_foro` `cf2` on((`cf1`.`cod_categoria_foro` = `cf2`.`cod_categoria_foro`))) ORDER BY `cf1`.`cod_contenido_foro` ASC  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cons_compras`
--
DROP TABLE IF EXISTS `cons_compras`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cons_compras`  AS SELECT `v`.`cod_ventas` AS `cod_ventas`, `v`.`cod_usuario` AS `cod_usuario`, `c`.`nick_cliente` AS `nick_cliente`, `v`.`fecha` AS `fecha`, `v`.`importe_total` AS `importe_total` FROM (`ventas` `v` join `cliente` `c` on((`v`.`cod_usuario` = `c`.`cod_cliente`))) ORDER BY `v`.`cod_ventas` ASC  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cons_compras_productos`
--
DROP TABLE IF EXISTS `cons_compras_productos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cons_compras_productos`  AS SELECT `vp`.`cod_ventas_productos` AS `cod_ventas_productos`, `p`.`cod_producto` AS `cod_producto`, `v`.`cod_ventas` AS `cod_ventas`, (select `cli`.`nick_cliente` from (`ventas` `v2` join `cliente` `cli` on((`v2`.`cod_usuario` = `cli`.`cod_cliente`))) where (`v2`.`cod_ventas` = `vp`.`cod_ventas`)) AS `nick_cliente`, `p`.`nombre_producto` AS `nombre_producto`, `p`.`imagen` AS `imagen`, `vp`.`unidades` AS `unidades`, `v`.`fecha` AS `fecha`, `vp`.`importe_base` AS `importe_base`, `vp`.`importe_total` AS `importe_total` FROM ((`ventas_productos` `vp` join `ventas` `v` on((`vp`.`cod_ventas` = `v`.`cod_ventas`))) join `producto` `p` on((`vp`.`cod_producto` = `p`.`cod_producto`))) ORDER BY `vp`.`cod_ventas_productos` ASC  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cons_espectaculo_categoria`
--
DROP TABLE IF EXISTS `cons_espectaculo_categoria`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cons_espectaculo_categoria`  AS SELECT `e`.`cod_espectaculos` AS `cod_espectaculos`, `e`.`cod_categoria_espectaculo` AS `cod_categoria_espectaculo`, `e`.`sinopsis` AS `sinopsis`, `e`.`titulo` AS `titulo`, `e`.`duracion` AS `duracion`, `e`.`fecha_lanzamiento` AS `fecha_lanzamiento`, `e`.`fecha_finalizacion` AS `fecha_finalizacion`, `e`.`imagen` AS `imagen`, `ce`.`nombre_categoria_espectaculo` AS `nombre_categoria_espectaculo`, `e`.`borrado` AS `borrado` FROM (`espectaculos` `e` join `categoria_espectaculo` `ce` on((`e`.`cod_categoria_espectaculo` = `ce`.`cod_categoria_espectaculo`))) ORDER BY `e`.`cod_espectaculos` ASC  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cons_espe_sala`
--
DROP TABLE IF EXISTS `cons_espe_sala`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cons_espe_sala`  AS SELECT `ea`.`cod_espe_sala` AS `cod_espe_sala`, `ea`.`cod_espectaculos` AS `cod_espectaculos`, `s`.`cod_sala` AS `cod_sala`, `e`.`titulo` AS `titulo`, `s`.`nombre_sala` AS `nombre_sala`, `s`.`capacidad_maxima` AS `capacidad_maxima`, `ea`.`borrado` AS `borrado` FROM ((`espe_sala` `ea` join `espectaculos` `e` on((`ea`.`cod_espectaculos` = `e`.`cod_espectaculos`))) join `sala` `s` on((`ea`.`cod_sala` = `s`.`cod_sala`))) ORDER BY `e`.`titulo` ASC  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cons_participantes_espectaculos`
--
DROP TABLE IF EXISTS `cons_participantes_espectaculos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cons_participantes_espectaculos`  AS SELECT `pe`.`cod_participantes_espectaculos` AS `cod_participantes_espectaculos`, `pe`.`cod_participantes` AS `cod_participantes`, `pe`.`cod_espectaculo` AS `cod_espectaculo`, `p`.`nombre_participante` AS `nombre_participante`, `e`.`titulo` AS `titulo`, `e`.`duracion` AS `duracion`, `e`.`fecha_lanzamiento` AS `fecha_lanzamiento`, `e`.`fecha_finalizacion` AS `fecha_finalizacion`, `e`.`imagen` AS `imagen`, `pe`.`borrado` AS `borrado` FROM ((`participantes_espectaculos` `pe` join `espectaculos` `e` on((`pe`.`cod_espectaculo` = `e`.`cod_espectaculos`))) join `participantes` `p` on((`pe`.`cod_participantes` = `p`.`cod_participante`))) ORDER BY `e`.`titulo` ASC  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cons_productos_categorias`
--
DROP TABLE IF EXISTS `cons_productos_categorias`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cons_productos_categorias`  AS SELECT `p`.`cod_producto` AS `cod_producto`, `p`.`cod_categoria_producto` AS `cod_categoria_producto`, `p`.`nombre_producto` AS `nombre_producto`, `p`.`unidades` AS `unidades`, `p`.`precio` AS `precio`, `p`.`fecha_alta` AS `fecha_alta`, `cp`.`nombre_categoria` AS `nombre_categoria`, `p`.`imagen` AS `imagen`, `p`.`borrado` AS `borrado` FROM (`categoria_producto` `cp` join `producto` `p` on((`cp`.`cod_categoria_producto` = `p`.`cod_categoria_producto`))) ORDER BY `p`.`cod_producto` ASC  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cons_reservas`
--
DROP TABLE IF EXISTS `cons_reservas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cons_reservas`  AS SELECT `r`.`cod_reserva` AS `cod_reserva`, `r`.`cod_sesion` AS `cod_sesion`, `r`.`cod_cliente` AS `cod_cliente`, `r`.`num_asientos` AS `num_asientos`, `r`.`precio_total` AS `precio_total`, `c`.`nick_cliente` AS `nick_cliente`, `e`.`cod_espectaculos` AS `cod_espectaculos`, `e`.`titulo` AS `titulo`, (select `cons_sesiones`.`nombre_sala` from `cons_sesiones` where (`cons_sesiones`.`cod_sesion` = `r`.`cod_sesion`)) AS `nombre_sala` FROM ((((`reservar` `r` join `cliente` `c` on((`r`.`cod_cliente` = `c`.`cod_cliente`))) join `sesiones` `s` on((`r`.`cod_sesion` = `s`.`cod_sesion`))) join `espe_sala` `es` on((`s`.`cod_espe_sala` = `es`.`cod_espe_sala`))) join `espectaculos` `e` on((`es`.`cod_espectaculos` = `e`.`cod_espectaculos`))) ORDER BY `r`.`cod_reserva` ASC  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cons_sesiones`
--
DROP TABLE IF EXISTS `cons_sesiones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cons_sesiones`  AS SELECT `s`.`cod_sesion` AS `cod_sesion`, `s`.`cod_espe_sala` AS `cod_espe_sala`, `es`.`cod_sala` AS `cod_sala`, `sal`.`nombre_sala` AS `nombre_sala`, `e`.`titulo` AS `titulo`, `s`.`fecha` AS `fecha`, `s`.`hora_inicio` AS `hora_inicio`, `s`.`hora_fin` AS `hora_fin`, `s`.`borrado` AS `borrado` FROM (((`sesiones` `s` join `espe_sala` `es` on((`s`.`cod_espe_sala` = `es`.`cod_espe_sala`))) join `sala` `sal` on((`es`.`cod_sala` = `sal`.`cod_sala`))) join `espectaculos` `e` on((`es`.`cod_espectaculos` = `e`.`cod_espectaculos`))) ORDER BY `s`.`fecha` ASC  ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acl_roles`
--
ALTER TABLE `acl_roles`
  ADD PRIMARY KEY (`cod_acl_role`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `acl_usuarios`
--
ALTER TABLE `acl_usuarios`
  ADD PRIMARY KEY (`cod_acl_usuario`),
  ADD UNIQUE KEY `uq_acl_roles_1` (`nick`),
  ADD KEY `cod_acl_role` (`cod_acl_role`);

--
-- Indices de la tabla `categoria_espectaculo`
--
ALTER TABLE `categoria_espectaculo`
  ADD PRIMARY KEY (`cod_categoria_espectaculo`),
  ADD UNIQUE KEY `nombre_categoria_espectaculo` (`nombre_categoria_espectaculo`);

--
-- Indices de la tabla `categoria_foro`
--
ALTER TABLE `categoria_foro`
  ADD PRIMARY KEY (`cod_categoria_foro`),
  ADD UNIQUE KEY `uq_nombre_categoria_foro` (`nombre_categoria_foro`);

--
-- Indices de la tabla `categoria_producto`
--
ALTER TABLE `categoria_producto`
  ADD PRIMARY KEY (`cod_categoria_producto`),
  ADD UNIQUE KEY `uq_nombre_categoria` (`nombre_categoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cod_cliente`),
  ADD UNIQUE KEY `uq_nick_cliente` (`nick_cliente`),
  ADD UNIQUE KEY `uq_nif_cliente` (`nif_cliente`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`cod_comentarios`),
  ADD KEY `fk_cod_cliente_comentario` (`cod_cliente`),
  ADD KEY `fk_cod_espectaculo_comentario` (`cod_espectaculo`);

--
-- Indices de la tabla `contenido_foro`
--
ALTER TABLE `contenido_foro`
  ADD PRIMARY KEY (`cod_contenido_foro`),
  ADD KEY `fk_cod_categoria_foro` (`cod_categoria_foro`),
  ADD KEY `fk_cod_cliente_foro` (`cod_cliente`);

--
-- Indices de la tabla `espectaculos`
--
ALTER TABLE `espectaculos`
  ADD PRIMARY KEY (`cod_espectaculos`),
  ADD UNIQUE KEY `titulo` (`titulo`),
  ADD KEY `fk_cod_categoria_espectaculo` (`cod_categoria_espectaculo`);

--
-- Indices de la tabla `espe_sala`
--
ALTER TABLE `espe_sala`
  ADD PRIMARY KEY (`cod_espe_sala`),
  ADD KEY `fk_cod_espectaculos` (`cod_espectaculos`),
  ADD KEY `fk_cod_sala_espe` (`cod_sala`);

--
-- Indices de la tabla `participantes`
--
ALTER TABLE `participantes`
  ADD PRIMARY KEY (`cod_participante`) USING BTREE,
  ADD UNIQUE KEY `uq_nombre_participante` (`nombre_participante`) USING BTREE;

--
-- Indices de la tabla `participantes_espectaculos`
--
ALTER TABLE `participantes_espectaculos`
  ADD PRIMARY KEY (`cod_participantes_espectaculos`),
  ADD KEY `fk_cod_participantes` (`cod_participantes`) USING BTREE,
  ADD KEY `fk_cod_espectaculo` (`cod_espectaculo`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`cod_producto`),
  ADD UNIQUE KEY `uq_nombre_producto` (`nombre_producto`),
  ADD KEY `fk_cod_categoria_producto` (`cod_categoria_producto`);

--
-- Indices de la tabla `reservar`
--
ALTER TABLE `reservar`
  ADD PRIMARY KEY (`cod_reserva`),
  ADD KEY `fk_cod_sesion` (`cod_sesion`),
  ADD KEY `fk_cod_cliente_reservas` (`cod_cliente`);

--
-- Indices de la tabla `sala`
--
ALTER TABLE `sala`
  ADD PRIMARY KEY (`cod_sala`),
  ADD UNIQUE KEY `uq_nombre_sala` (`nombre_sala`) USING BTREE;

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`cod_sesion`),
  ADD KEY `fk_cod_espe_sala` (`cod_espe_sala`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`cod_ventas`),
  ADD KEY `fk_cod_cliente` (`cod_usuario`);

--
-- Indices de la tabla `ventas_productos`
--
ALTER TABLE `ventas_productos`
  ADD PRIMARY KEY (`cod_ventas_productos`),
  ADD KEY `fk_cod_producto` (`cod_producto`),
  ADD KEY `fk_cod_venta` (`cod_ventas`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acl_roles`
--
ALTER TABLE `acl_roles`
  MODIFY `cod_acl_role` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `acl_usuarios`
--
ALTER TABLE `acl_usuarios`
  MODIFY `cod_acl_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `categoria_espectaculo`
--
ALTER TABLE `categoria_espectaculo`
  MODIFY `cod_categoria_espectaculo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `categoria_foro`
--
ALTER TABLE `categoria_foro`
  MODIFY `cod_categoria_foro` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `categoria_producto`
--
ALTER TABLE `categoria_producto`
  MODIFY `cod_categoria_producto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cod_cliente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `cod_comentarios` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `contenido_foro`
--
ALTER TABLE `contenido_foro`
  MODIFY `cod_contenido_foro` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `espectaculos`
--
ALTER TABLE `espectaculos`
  MODIFY `cod_espectaculos` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `espe_sala`
--
ALTER TABLE `espe_sala`
  MODIFY `cod_espe_sala` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `participantes`
--
ALTER TABLE `participantes`
  MODIFY `cod_participante` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `participantes_espectaculos`
--
ALTER TABLE `participantes_espectaculos`
  MODIFY `cod_participantes_espectaculos` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `cod_producto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `reservar`
--
ALTER TABLE `reservar`
  MODIFY `cod_reserva` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `sala`
--
ALTER TABLE `sala`
  MODIFY `cod_sala` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `cod_sesion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `cod_ventas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `ventas_productos`
--
ALTER TABLE `ventas_productos`
  MODIFY `cod_ventas_productos` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `acl_usuarios`
--
ALTER TABLE `acl_usuarios`
  ADD CONSTRAINT `fk_acl_roles_1` FOREIGN KEY (`cod_acl_role`) REFERENCES `acl_roles` (`cod_acl_role`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `fk_cod_cliente_comentario` FOREIGN KEY (`cod_cliente`) REFERENCES `cliente` (`cod_cliente`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cod_espectaculo_comentario` FOREIGN KEY (`cod_espectaculo`) REFERENCES `espectaculos` (`cod_espectaculos`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `contenido_foro`
--
ALTER TABLE `contenido_foro`
  ADD CONSTRAINT `fk_cod_categoria_foro` FOREIGN KEY (`cod_categoria_foro`) REFERENCES `categoria_foro` (`cod_categoria_foro`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cod_cliente_foro` FOREIGN KEY (`cod_cliente`) REFERENCES `cliente` (`cod_cliente`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `espectaculos`
--
ALTER TABLE `espectaculos`
  ADD CONSTRAINT `fk_cod_categoria_espectaculo` FOREIGN KEY (`cod_categoria_espectaculo`) REFERENCES `categoria_espectaculo` (`cod_categoria_espectaculo`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `espe_sala`
--
ALTER TABLE `espe_sala`
  ADD CONSTRAINT `fk_cod_espectaculos` FOREIGN KEY (`cod_espectaculos`) REFERENCES `espectaculos` (`cod_espectaculos`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cod_sala_espe` FOREIGN KEY (`cod_sala`) REFERENCES `sala` (`cod_sala`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `participantes_espectaculos`
--
ALTER TABLE `participantes_espectaculos`
  ADD CONSTRAINT `fk_cod_espectaculo` FOREIGN KEY (`cod_espectaculo`) REFERENCES `espectaculos` (`cod_espectaculos`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cod_participantes` FOREIGN KEY (`cod_participantes`) REFERENCES `participantes` (`cod_participante`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_cod_categoria_producto` FOREIGN KEY (`cod_categoria_producto`) REFERENCES `categoria_producto` (`cod_categoria_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservar`
--
ALTER TABLE `reservar`
  ADD CONSTRAINT `fk_cod_cliente_reservas` FOREIGN KEY (`cod_cliente`) REFERENCES `cliente` (`cod_cliente`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cod_sesion` FOREIGN KEY (`cod_sesion`) REFERENCES `sesiones` (`cod_sesion`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD CONSTRAINT `fk_cod_espe_sala` FOREIGN KEY (`cod_espe_sala`) REFERENCES `espe_sala` (`cod_espe_sala`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_cod_cliente` FOREIGN KEY (`cod_usuario`) REFERENCES `cliente` (`cod_cliente`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas_productos`
--
ALTER TABLE `ventas_productos`
  ADD CONSTRAINT `fk_cod_producto` FOREIGN KEY (`cod_producto`) REFERENCES `producto` (`cod_producto`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cod_venta` FOREIGN KEY (`cod_ventas`) REFERENCES `ventas` (`cod_ventas`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
