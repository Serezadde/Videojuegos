-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-04-2024 a las 17:05:09
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `videojuegos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generos`
--

CREATE DATABASE Videojuegos;
USE Videojuegos;

CREATE TABLE `generos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `generos`
--

INSERT INTO `generos` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Acción', ''),
(2, 'Ciencia Ficción', ''),
(3, 'Histórico', ''),
(4, 'Aventura', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegos`
--

CREATE TABLE `juegos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `portada` varchar(255) NOT NULL,
  `precio` float NOT NULL,
  `idgenero` int(11) NOT NULL,
  `disponible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `juegos`
--

INSERT INTO `juegos` (`id`, `nombre`, `descripcion`, `portada`, `precio`, `idgenero`, `disponible`) VALUES
(5, 'The Last of Us Parte II', 'Asume las devastadoras consecuencias físicas y emocionales de las acciones de Ellie.Cinco años después de su peligroso viaje a través de unos Estados Unidos devastados, Elliey Joel se han asentado en Jackson, Wyoming. Vivir en una próspera comunidad de su', 'img/lastofus2.jpg', 69.99, 1, 1),
(6, 'Assassins Creed Valhalla Gold Edition', 'Con Assassin’s Creed Valhalla viajarás al siglo IX d. C. y tomarás el mando del clan nórdico de Eivor, que deja atrás una Noruega sacudida por guerras interminables y con recursos cada vez más escasos para atravesar los hielos del mar del Norte y llegar h', 'img/valhalla.jpg', 99.99, 2, 1),
(7, 'Watch Dogs: Legion', 'En un futuro cercano, Londres se enfrenta al colapso: La gente está oprimida por un estado de vigilancia total, las fuerzas privadas militares controlan las calles, y un sindicato criminal muy poderoso está atacando a los vulnerables. El destino de Londre', 'img/legion.jpg', 69.99, 2, 1),
(8, 'Cyberpunk 2077', 'Cyberpunk 2077 es una historia de acción y aventura en mundo abierto ambientada en Night City, una megalópolis obsesionada con el poder, el glamur y la modificación corporal. Tu personaje es V, un mercenario que persigue un implante único que permite alca', 'img/cyber.jpg', 49.99, 2, 1),
(9, 'Mario y Sonic en los Juegos Olímpicos:Tokyo 2020 Nintendo Switch', 'Los jugadores se unirán a Mario, Sonic y sus amigos en su mayor aventura hasta la fecha en los Juegos Olímpicos de Tokio 2020, en exclusiva para Nintendo Switch. ', 'img/mariosonic.jpg', 54.99, 4, 1),
(10, 'The Legend of Zelda: Links Awakening Nintendo Switch', 'Por culpa de una terrible tormenta, Link naufraga y acaba llegando a la costa de la misteriosa Isla Koholint. Si quiere regresar a casa, el valiente héroe deberá superar mazmorras desafiantes y enfrentarse a monstruos espeluznantes.Esta nueva versión incl', 'img/zelda.jpg', 54.99, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegos_plataformas`
--

CREATE TABLE `juegos_plataformas` (
  `id` int(11) NOT NULL,
  `id_juego` int(11) DEFAULT NULL,
  `id_plataforma` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `juegos_plataformas`
--

INSERT INTO `juegos_plataformas` (`id`, `id_juego`, `id_plataforma`) VALUES
(1, 6, 12),
(2, 6, 10),
(3, 8, 12),
(8, 5, 9),
(9, 5, 10),
(10, 7, 11),
(11, 7, 12),
(12, 9, 12),
(13, 9, 10),
(14, 10, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `totalprecio` float NOT NULL,
  `totalunidades` float NOT NULL,
  `estado` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `descripcion`, `totalprecio`, `totalunidades`, `estado`) VALUES
(8, 'Pedido con 4 juegos', 289.96, 4, 'Realizado'),
(9, 'Pedido de 2 juegos', 174.97, 3, 'Realizado'),
(10, 'Pedido con 6 juegos', 139.98, 2, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_juegos`
--

CREATE TABLE `pedidos_juegos` (
  `id` int(11) NOT NULL,
  `idpedido` int(11) NOT NULL,
  `idjuego` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos_juegos`
--

INSERT INTO `pedidos_juegos` (`id`, `idpedido`, `idjuego`) VALUES
(46, 10, 5),
(47, 10, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plataformas`
--

CREATE TABLE `plataformas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `plataformas`
--

INSERT INTO `plataformas` (`id`, `nombre`, `descripcion`, `imagen`, `disponible`) VALUES
(9, 'PS4', 'PlayStation 4 es la cuarta videoconsola del modelo PlayStation. Es la segunda consola de Sony en ser diseñada por Mark Cerny y forma parte de las videoconsolas de octava generación. Fue anunciada oficialmente el 20 de febrero de 2013 en el evento PlayStat', NULL, 1),
(10, 'PS5', 'PlayStation 5 es una consola de videojuegos de sobremesa desarrollada por la empresa Sony Interactive Entertainment. Fue anunciada en el año 2019 como la sucesora de la PlayStation 4, la PS5 se lanzó el 12 de noviembre de 2020 en Australia, Japón, Nueva Z', NULL, 1),
(11, 'XBOX', 'Xbox One es la tercera videoconsola de sobremesa de la marca Xbox, producida por Microsoft. Forma parte de las videoconsolas de octava generación, fue presentada por Microsoft el 21 de mayo de 2013. Es la sucesora de la Xbox 360 y la predecesora de la Xbo', NULL, 1),
(12, 'PC', 'Todas las Plataformas PC', NULL, 1),
(15, 'Nintendo Switch', 'Nintendo Switch es la novena consola de videojuegos principal desarrollada por Nintendo. Conocida en el desarrollo por su nombre código «NX», se dio a conocer en octubre de 2016 y fue lanzada mundialmente el 3 de marzo de 2017. Nintendo considera a Switch', NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `juegos`
--
ALTER TABLE `juegos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `idgenero` (`idgenero`);

--
-- Indices de la tabla `juegos_plataformas`
--
ALTER TABLE `juegos_plataformas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_juego` (`id_juego`),
  ADD KEY `fk_id_plataforma` (`id_plataforma`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos_juegos`
--
ALTER TABLE `pedidos_juegos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK1_pedidos_juegos` (`idpedido`),
  ADD KEY `FK2_pedidos_juegos` (`idjuego`);

--
-- Indices de la tabla `plataformas`
--
ALTER TABLE `plataformas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `generos`
--
ALTER TABLE `generos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `juegos`
--
ALTER TABLE `juegos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `juegos_plataformas`
--
ALTER TABLE `juegos_plataformas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pedidos_juegos`
--
ALTER TABLE `pedidos_juegos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `plataformas`
--
ALTER TABLE `plataformas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `juegos`
--
ALTER TABLE `juegos`
  ADD CONSTRAINT `idgeneros_ibfk_1` FOREIGN KEY (`idgenero`) REFERENCES `generos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `juegos_plataformas`
--
ALTER TABLE `juegos_plataformas`
  ADD CONSTRAINT `fk_id_juego` FOREIGN KEY (`id_juego`) REFERENCES `juegos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_plataforma` FOREIGN KEY (`id_plataforma`) REFERENCES `plataformas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos_juegos`
--
ALTER TABLE `pedidos_juegos`
  ADD CONSTRAINT `FK1_pedidos_juegos` FOREIGN KEY (`idpedido`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `FK2_pedidos_juegos` FOREIGN KEY (`idjuego`) REFERENCES `juegos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;