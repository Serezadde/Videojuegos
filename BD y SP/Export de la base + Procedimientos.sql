-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-05-2024 a las 20:54:17
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

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

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `cursor_videojuegos_juego_actualizar_5juegos` ()   BEGIN
    DECLARE contador INT DEFAULT 0;
    DECLARE id_juego INT;
    DECLARE nombre_juego VARCHAR(255);
    DECLARE descripcion_juego VARCHAR(255);
    DECLARE portada_juego VARCHAR(255);
    DECLARE precio_juego FLOAT;
    DECLARE id_genero_juego INT;
    DECLARE disponible_juego TINYINT;

    -- Cursor para seleccionar los juegos
    DECLARE juegos_cursor CURSOR FOR
        SELECT id, nombre, descripcion, portada, precio, idgenero, disponible FROM juegos LIMIT 5;

    -- Handler para el final del cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND
        SET contador = 5;

    OPEN juegos_cursor;

    read_loop: LOOP
        FETCH juegos_cursor INTO id_juego, nombre_juego, descripcion_juego, portada_juego, precio_juego, id_genero_juego, disponible_juego;
        
        -- Si no hay más filas, sal del bucle
        IF contador = 5 THEN
            LEAVE read_loop;
        END IF;

        -- Insertar o actualizar el juego dependiendo de si ya existe o no
        -- NOTA: En MySQL/MariaDB, se puede usar INSERT ... ON DUPLICATE KEY UPDATE
INSERT INTO juegos (id, nombre, descripcion, portada, precio, idgenero, disponible) 
VALUES (id_juego, nombre_juego, descripcion_juego, portada_juego, precio_juego, id_genero_juego, disponible_juego)
ON DUPLICATE KEY UPDATE 
    nombre = VALUES(nombre), 
    descripcion = VALUES(descripcion), 
    portada = VALUES(portada), 
    precio = VALUES(precio), 
    idgenero = VALUES(idgenero), 
    disponible = VALUES(disponible);

        -- Incrementar el contador
        SET contador = contador + 1;
    END LOOP;

    CLOSE juegos_cursor;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `cursor_videojuegos_juego_calcularPrecioTotal` (IN `selected_ids` VARCHAR(255))   BEGIN
    DECLARE total FLOAT DEFAULT 0;
    DECLARE id_juego INT;
    DECLARE precio_juego FLOAT;

    -- Iterar sobre los IDs seleccionados
    WHILE LENGTH(selected_ids) > 0 DO
        -- Obtener el primer ID de la lista
        SET id_juego = SUBSTRING_INDEX(selected_ids, ',', 1);
        -- Obtener el precio del juego
        SELECT precio INTO precio_juego FROM juegos WHERE id = id_juego;
        -- Agregar el precio al total
        SET total = total + precio_juego;
        -- Eliminar el primer ID de la lista
        SET selected_ids = TRIM(BOTH ',' FROM SUBSTRING(selected_ids, LENGTH(id_juego) + 2));
    END WHILE;

    -- Devolver el precio total
    SELECT total AS precio_total;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_aumentar_precio_juegos` (IN `plataforma_nombre` VARCHAR(255))   BEGIN
    UPDATE juegos j
    JOIN juegos_plataformas jp ON j.id = jp.id_juego
    JOIN plataformas p ON jp.id_plataforma = p.id
    SET j.precio = j.precio * 1.15
    WHERE p.nombre = plataforma_nombre;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_crear_plataforma_y_aplicar_descuento` (IN `p_nombre` VARCHAR(255), IN `p_descripcion` VARCHAR(255), IN `p_disponible` TINYINT(1), IN `p_imagen` VARCHAR(255))   BEGIN
    DECLARE v_id_plataforma INT;

    -- Insertar la nueva plataforma
    INSERT INTO plataformas (nombre, descripcion, disponible, imagen)
    VALUES (p_nombre, p_descripcion, p_disponible, p_imagen);
    
    -- Obtener el ID de la nueva plataforma
    SET v_id_plataforma = LAST_INSERT_ID();

    -- Aplicar el descuento del 30% a todos los juegos de la nueva plataforma
    UPDATE juegos j
    INNER JOIN juegos_plataformas jp ON j.id = jp.id_juego
    SET j.precio = j.precio * 0.7
    WHERE jp.id_plataforma = v_id_plataforma;

    -- Verificar los precios actualizados (esto sería en una consulta aparte desde la aplicación)
    SELECT j.id, j.nombre, j.precio
    FROM juegos j
    INNER JOIN juegos_plataformas jp ON j.id = jp.id_juego
    WHERE jp.id_plataforma = v_id_plataforma;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_genero_editar` (IN `p_id` INT, IN `p_nombre` VARCHAR(255), IN `p_descripcion` VARCHAR(255))   BEGIN
    UPDATE generos
    SET 
        nombre = p_nombre,
        descripcion = p_descripcion
    WHERE 
        id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_genero_eliminar` (IN `idGenero` INT)   BEGIN
    -- Eliminar los pedidos asociados a los juegos que pertenecen al género
    DELETE FROM pedidos_juegos WHERE idjuego IN (SELECT id FROM juegos WHERE idgenero = idGenero);

    -- Eliminar los juegos asociados al género
    DELETE FROM juegos WHERE idgenero = idGenero;

    -- Finalmente, eliminar el género
    DELETE FROM generos WHERE id = idGenero;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_genero_insertar` (IN `nombre` VARCHAR(255), IN `descripcion` VARCHAR(255))   BEGIN
    INSERT INTO generos (nombre, descripcion)
    VALUES (nombre, descripcion);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_genero_obtener_genero` ()   BEGIN
    SELECT * FROM generos
    WHERE id = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_juegos_actualizar_precio_pedido` (IN `idpedido` INT)   BEGIN
    UPDATE pedidos
    SET totalprecio = (SELECT SUM(juegos.precio) 
                       FROM pedidos_juegos 
                       JOIN juegos j ON pedidos_juegos.idjuego = juegos.id
                       WHERE pedidos_juegos.idpedido = idpedido)
    WHERE id = idpedido;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_juegos_actualizar_total_unidades_pedido` (IN `idpedido` INT)   BEGIN
    UPDATE pedidos
    SET totalunidades = (SELECT COUNT(*) FROM pedidos_juegos WHERE idpedido = idpedido)
    WHERE id = idpedido;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_juegos_editar` (IN `p_nombre` VARCHAR(255), IN `p_descripcion` VARCHAR(255), IN `p_portada` VARCHAR(255), IN `p_precio` FLOAT, IN `p_idgenero` INT, IN `p_disponible` TINYINT, IN `p_id` INT)   BEGIN
    UPDATE juegos 
    SET 
        nombre = p_nombre,
        descripcion = p_descripcion,
        portada = p_portada,
        precio = p_precio,
        idgenero = p_idgenero,
        disponible = p_disponible
    WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_juegos_eliminar` (IN `p_id` INT)   BEGIN
    DECLARE v_idpedido INT;
    
    -- Encontrar el id del pedido asociado con el juego
    SELECT idpedido INTO v_idpedido
    FROM pedidos_juegos
    WHERE idjuego = p_id;
    
    -- Eliminar el juego de la tabla pedidos_juegos
    DELETE FROM pedidos_juegos WHERE idjuego = p_id;
    
    -- Eliminar el juego de la tabla juegos
    DELETE FROM juegos WHERE id = p_id;

    -- Actualizar el pedido asociado
    IF v_idpedido IS NOT NULL THEN
        CALL sp_videojuegos_juegos_actualizar_total_unidades_pedido(v_idpedido);
        CALL sp_videojuegos_juegos_actualizar_precio_pedido(v_idpedido);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_juegos_insertar` (IN `p_nombre` VARCHAR(255), IN `p_descripcion` VARCHAR(255), IN `p_portada` VARCHAR(255), IN `p_precio` FLOAT, IN `p_idgenero` INT, IN `p_disponible` TINYINT(1), OUT `p_id` INT)   BEGIN
    INSERT INTO juegos (nombre, descripcion, portada, precio, idgenero, disponible) 
    VALUES (p_nombre, p_descripcion, p_portada, p_precio, p_idgenero, p_disponible);
    
    SET p_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_juegos_obtener` (IN `p_id` INT)   BEGIN
    SELECT * FROM juegos WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_pedidos_actualizar` (IN `p_id` INT, IN `p_descripcion` VARCHAR(255), IN `p_totalprecio` FLOAT, IN `p_totalunidades` FLOAT, IN `p_estado` VARCHAR(255))   BEGIN
    UPDATE pedidos
    SET descripcion = p_descripcion,
        totalprecio = p_totalprecio,
        totalunidades = p_totalunidades,
        estado = p_estado
    WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_pedidos_eliminar` (IN `p_id` INT)   BEGIN
    -- Eliminar las relaciones del pedido con los juegos
    DELETE FROM pedidos_juegos WHERE idpedido = p_id;

    -- Eliminar el pedido
    DELETE FROM pedidos WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_pedidos_insertar` (IN `p_descripcion` VARCHAR(255), IN `p_totalprecio` FLOAT, IN `p_totalunidades` FLOAT, IN `p_estado` VARCHAR(255), OUT `p_id` INT)   BEGIN
    INSERT INTO pedidos (descripcion, totalprecio, totalunidades, estado)
    VALUES (p_descripcion, p_totalprecio, p_totalunidades, p_estado);
    
    SET p_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_pedidos_seleccionar` (IN `p_id` INT)   BEGIN
    SELECT * FROM pedidos
    WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_plataforma_actualizar` (IN `idPlataforma` INT, IN `nombre` VARCHAR(255), IN `descripcion` VARCHAR(255), IN `imagen` VARCHAR(255), IN `disponible` TINYINT(1))   BEGIN
    UPDATE plataformas 
    SET nombre = nombre, descripcion = descripcion, imagen = imagen, disponible = disponible
    WHERE id = idPlataforma;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_plataforma_editar` (IN `nombre` VARCHAR(255), IN `descripcion` VARCHAR(255), IN `imagen` VARCHAR(255), IN `disponible` TINYINT, IN `id` INT)   BEGIN
    
UPDATE plataformas SET nombre = nombre, 
    descripcion = descripcion,
    imagen = imagen,
    disponible = disponible WHERE id =idPlataforma;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_plataforma_eliminar` (IN `idPlataforma` INT)   BEGIN
    START TRANSACTION;

    -- Eliminar asociaciones en pedidos_juegos relacionadas con los juegos de la plataforma
    DELETE pedidos_juegos
    FROM pedidos_juegos
    INNER JOIN juegos_plataformas ON pedidos_juegos.idjuego = juegos_plataformas.id_juego
    WHERE juegos_plataformas.id_plataforma = idPlataforma;
    
    -- Eliminar las asociaciones en juegos_plataformas
    DELETE FROM juegos_plataformas WHERE id_plataforma = idPlataforma;

    -- Eliminar la plataforma de la tabla plataformas
    DELETE FROM plataformas WHERE id = idPlataforma;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_plataforma_insertar` (IN `nombre` VARCHAR(255), IN `descripcion` VARCHAR(255), IN `disponible` TINYINT(1), IN `imagen` VARCHAR(255), OUT `id` INT)   BEGIN
    INSERT INTO plataformas (nombre, descripcion, disponible, imagen) 
    VALUES (nombre, descripcion, disponible, imagen);
    
    SET id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_videojuegos_plataforma_seleccionar` (IN `idPlataforma` INT)   BEGIN
    SELECT * FROM plataformas WHERE id = idPlataforma;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generos`
--

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

--
-- Disparadores `generos`
--
DELIMITER $$
CREATE TRIGGER `trigger_videojuegos_juegos_before_borrar_genero` BEFORE DELETE ON `generos` FOR EACH ROW BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE cur_juego_id INT;
    DECLARE cur CURSOR FOR SELECT id FROM juegos WHERE idgenero = OLD.id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO cur_juego_id;
        IF done THEN
            LEAVE read_loop;
        END IF;
        DELETE FROM pedidos_juegos WHERE idjuego = cur_juego_id;
        DELETE FROM juegos WHERE id = cur_juego_id;
    END LOOP;
    CLOSE cur;
END
$$
DELIMITER ;

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
(5, 'Sonic', 'El erizo que corre un chingo', 'img/sonic.png', 20, 4, 1),
(6, 'Assassins Creed Valhalla Gold Edition', 'Con Assassin’s Creed Valhalla viajarás al siglo IX d. C. y tomarás el mando del clan nórdico de Eivor, que deja atrás una Noruega sacudida por guerras interminables y con recursos cada vez más escasos para atravesar los hielos del mar del Norte y llegar h', 'img/valhalla.jpg', 99.99, 2, 1),
(7, 'Watch Dogs: Legion', 'En un futuro cercano, Londres se enfrenta al colapso: La gente está oprimida por un estado de vigilancia total, las fuerzas privadas militares controlan las calles, y un sindicato criminal muy poderoso está atacando a los vulnerables. El destino de Londre', 'img/legion.jpg', 69.99, 2, 1),
(8, 'Cyberpunk 2077', 'Cyberpunk 2077 es una historia de acción y aventura en mundo abierto ambientada en Night City, una megalópolis obsesionada con el poder, el glamur y la modificación corporal. Tu personaje es V, un mercenario que persigue un implante único que permite alca', 'img/cyber.jpg', 49.99, 2, 1),
(9, 'Mario y Sonic en los Juegos Olímpicos:Tokyo 2020 Nintendo Switch', 'Los jugadores se unirán a Mario, Sonic y sus amigos en su mayor aventura hasta la fecha en los Juegos Olímpicos de Tokio 2020, en exclusiva para Nintendo Switch. ', 'img/mariosonic.jpg', 54.99, 4, 1),
(10, 'The Legend of Zelda: Links Awakening Nintendo Switch', 'Por culpa de una terrible tormenta, Link naufraga y acaba llegando a la costa de la misteriosa Isla Koholint. Si quiere regresar a casa, el valiente héroe deberá superar mazmorras desafiantes y enfrentarse a monstruos espeluznantes.Esta nueva versión incl', 'img/zelda.jpg', 54.99, 3, 1),
(11, 'The Ladst of Us Parte II', 'Asume las devastadoras consecuencias físicas y emocionales de las acciones de Ellie.Cinco años después de su peligroso viaje a través de unos Estados Unidos devastados, Elliey Joel se han asentado en Jackson, Wyoming. Vivir en una próspera comunidad de su', 'img/lastofus2.jpg', 69.99, 1, 1),
(12, 'Assadssins Creed Valhalla Gold Edition', 'Con Assassin’s Creed Valhalla viajarás al siglo IX d. C. y tomarás el mando del clan nórdico de Eivor, que deja atrás una Noruega sacudida por guerras interminables y con recursos cada vez más escasos para atravesar los hielos del mar del Norte y llegar h', 'img/valhalla.jpg', 99.99, 2, 1),
(13, 'Watdch Dogs: Legion', 'En un futuro cercano, Londres se enfrenta al colapso: La gente está oprimida por un estado de vigilancia total, las fuerzas privadas militares controlan las calles, y un sindicato criminal muy poderoso está atacando a los vulnerables. El destino de Londre', 'img/legion.jpg', 69.99, 2, 1),
(14, 'Cybedrpunk 2077', 'Cyberpunk 2077 es una historia de acción y aventura en mundo abierto ambientada en Night City, una megalópolis obsesionada con el poder, el glamur y la modificación corporal. Tu personaje es V, un mercenario que persigue un implante único que permite alca', 'img/cyber.jpg', 49.99, 2, 1);

--
-- Disparadores `juegos`
--
DELIMITER $$
CREATE TRIGGER `trigger_videojuegos_juegos_before_juego_update` BEFORE UPDATE ON `juegos` FOR EACH ROW BEGIN
    INSERT INTO juegos_historicos (
        id_juego,
        nombre_juego,
        descripcion_juego,
        portada_juego,
        precio_juego,
        id_genero,
        juego_disponible,
        operacion,
        registro_fecha_hora
    ) VALUES (
        OLD.id,
        OLD.nombre,
        OLD.descripcion,
        OLD.portada,
        OLD.precio,
        OLD.idgenero,
        OLD.disponible,
        'UPDATE',
        NOW()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegos_historicos`
--

CREATE TABLE `juegos_historicos` (
  `id` int(11) NOT NULL,
  `id_juego` int(11) NOT NULL,
  `nombre_juego` varchar(255) NOT NULL,
  `descripcion_juego` text NOT NULL,
  `portada_juego` varchar(255) NOT NULL,
  `precio_juego` float NOT NULL,
  `id_genero` int(11) NOT NULL,
  `juego_disponible` tinyint(1) NOT NULL,
  `operacion` varchar(255) NOT NULL,
  `registro_fecha_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `juegos_historicos`
--

INSERT INTO `juegos_historicos` (`id`, `id_juego`, `nombre_juego`, `descripcion_juego`, `portada_juego`, `precio_juego`, `id_genero`, `juego_disponible`, `operacion`, `registro_fecha_hora`) VALUES
(0, 5, 'The Last of Us Parte II', 'Asume las devastadoras consecuencias físicas y emocionales de las acciones de Ellie.Cinco años después de su peligroso viaje a través de unos Estados Unidos devastados, Elliey Joel se han asentado en Jackson, Wyoming. Vivir en una próspera comunidad de su', 'img/lastofus2.jpg', 69.99, 1, 1, 'UPDATE', '2024-05-21 14:32:20');

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
(9, 'PS4', 'PlayStation 4 es la cuarta videoconsola del modelo PlayStation. Es la segunda consola de Sony en ser diseñada por Mark Cerny y forma parte de las videoconsolas de octava generación. Fue anunciada oficialmente el 20 de febrero de 2013 en el evento PlayStat', 'img/consolas/play4.jpg', 1),
(10, 'PS5', 'PlayStation 5 es una consola de videojuegos de sobremesa desarrollada por la empresa Sony Interactive Entertainment. Fue anunciada en el año 2019 como la sucesora de la PlayStation 4, la PS5 se lanzó el 12 de noviembre de 2020 en Australia, Japón, Nueva Z', 'img/consolas/play5.jpg', 1),
(11, 'XBOX', 'Xbox One es la tercera videoconsola de sobremesa de la marca Xbox, producida por Microsoft. Forma parte de las videoconsolas de octava generación, fue presentada por Microsoft el 21 de mayo de 2013. Es la sucesora de la Xbox 360 y la predecesora de la Xbo', 'img/consolas/xbox.jpg', 1),
(12, 'PC', 'Todas las Plataformas PC', 'img/consolas/pc.jpg', 1),
(15, 'Nintendo Switch', 'Nintendo Switch es la novena consola de videojuegos principal desarrollada por Nintendo. Conocida en el desarrollo por su nombre código «NX», se dio a conocer en octubre de 2016 y fue lanzada mundialmente el 3 de marzo de 2017. Nintendo considera a Switch', 'img/consolas/nintendo.jpg', 1);

--
-- Disparadores `plataformas`
--
DELIMITER $$
CREATE TRIGGER `trigger_videojuegos_juegos_before_borrar_plataforma` BEFORE DELETE ON `plataformas` FOR EACH ROW BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE cur_juego_id INT;
    DECLARE cur CURSOR FOR SELECT id_juego FROM juegos_plataformas WHERE id_plataforma = OLD.id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO cur_juego_id;
        IF done THEN
            LEAVE read_loop;
        END IF;
        DELETE FROM pedidos_juegos WHERE idjuego = cur_juego_id;
        DELETE FROM juegos WHERE id = cur_juego_id;
    END LOOP;
    CLOSE cur;
    
    -- Eliminar las asociaciones en juegos_plataformas después de los juegos
    DELETE FROM juegos_plataformas WHERE id_plataforma = OLD.id;
END
$$
DELIMITER ;

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
  ADD KEY `nombre` (`nombre`),
  ADD KEY `idgenero` (`idgenero`);

--
-- Indices de la tabla `juegos_historicos`
--
ALTER TABLE `juegos_historicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_juego_historicos` (`id_juego`),
  ADD KEY `fk_id_genero_historicos` (`id_genero`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
-- Filtros para la tabla `juegos_historicos`
--
ALTER TABLE `juegos_historicos`
  ADD CONSTRAINT `fk_id_genero_historicos` FOREIGN KEY (`id_genero`) REFERENCES `generos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_juego_historicos` FOREIGN KEY (`id_juego`) REFERENCES `juegos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
