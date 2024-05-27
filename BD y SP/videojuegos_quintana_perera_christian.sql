USE Videojuegos;

--Procedimentos Almacenados

-- Parte de Juegos

--Actualizar precio de los pedidos

DELIMITER $$

CREATE PROCEDURE sp_videojuegos_juegos_actualizar_precio_pedido (IN idpedido INT)
BEGIN
    UPDATE pedidos
    SET totalprecio = (SELECT SUM(juegos.precio) 
                       FROM pedidos_juegos 
                       JOIN juegos j ON pedidos_juegos.idjuego = juegos.id
                       WHERE pedidos_juegos.idpedido = idpedido)
    WHERE id = idpedido;
END $$

DELIMITER ;

-- Actualizar el total de unidades del pedido

DELIMITER $$

CREATE PROCEDURE sp_videojuegos_juegos_actualizar_total_unidades_pedido (IN idpedido INT)
BEGIN
    UPDATE pedidos
    SET totalunidades = (SELECT COUNT(*) FROM pedidos_juegos WHERE idpedido = idpedido)
    WHERE id = idpedido;
END $$

DELIMITER ;

--Actualizar juegos

DELIMITER $$

CREATE PROCEDURE sp_videojuegos_juegos_editar(
    IN p_nombre VARCHAR(255), 
    IN p_descripcion VARCHAR(255), 
    IN p_portada VARCHAR(255),
    IN p_precio FLOAT, 
    IN p_idgenero INT,
    IN p_disponible TINYINT, 
    IN p_id INT
)
BEGIN
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

DELIMITER ;

--Eliminar juego

DELIMITER //

CREATE PROCEDURE sp_videojuegos_juegos_eliminar (IN p_id INT)
BEGIN
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
END //

DELIMITER ;

--Insertar Juego

DELIMITER //

CREATE PROCEDURE sp_videojuegos_juegos_insertar (
    IN p_nombre VARCHAR(255),
    IN p_descripcion VARCHAR(255),
    IN p_portada VARCHAR(255),
    IN p_precio FLOAT,
    IN p_idgenero INT,
    IN p_disponible TINYINT(1),
    OUT p_id INT
)
BEGIN
    INSERT INTO juegos (nombre, descripcion, portada, precio, idgenero, disponible) 
    VALUES (p_nombre, p_descripcion, p_portada, p_precio, p_idgenero, p_disponible);
    
    SET p_id = LAST_INSERT_ID();
END //

DELIMITER ;

-- Obtener Juego

DELIMITER $$

CREATE PROCEDURE sp_videojuegos_juegos_obtener (IN p_id INT)
BEGIN
    SELECT * FROM juegos WHERE id = p_id;
END $$

DELIMITER ;


-- Genero

-- Actualizar genero

DELIMITER $$

CREATE PROCEDURE sp_videojuegos_genero_editar(
    IN p_id INT,
    IN p_nombre VARCHAR(255),
    IN p_descripcion VARCHAR(255)
)
BEGIN
    UPDATE generos
    SET 
        nombre = p_nombre,
        descripcion = p_descripcion
    WHERE 
        id = p_id;
END$$

DELIMITER ;

--Eliminar genero

DELIMITER $$

CREATE PROCEDURE sp_videojuegos_genero_eliminar(
    IN idGenero INT
)
BEGIN
    -- Eliminar los pedidos asociados a los juegos que pertenecen al género
    DELETE FROM pedidos_juegos WHERE idjuego IN (SELECT id FROM juegos WHERE idgenero = idGenero);

    -- Eliminar los juegos asociados al género
    DELETE FROM juegos WHERE idgenero = idGenero;

    -- Finalmente, eliminar el género
    DELETE FROM generos WHERE id = idGenero;
END$$

DELIMITER ;

-- Insertar genero

DELIMITER $$

CREATE PROCEDURE sp_videojuegos_genero_insertar(
    IN nombre VARCHAR(255),
    IN descripcion VARCHAR(255)
)
BEGIN
    INSERT INTO generos (nombre, descripcion)
    VALUES (nombre, descripcion);
END$$

DELIMITER ;

-- Obtener genero

DELIMITER $$

CREATE PROCEDURE sp_videojuegos_genero_obtener_genero(
)
BEGIN
    SELECT * FROM generos
    WHERE id = id;
END$$

DELIMITER ;

--Plataformas

-- Actualizar plataforma

DELIMITER $$

CREATE PROCEDURE sp_videojuegos_plataforma_actualizar(
    IN idPlataforma INT,
    IN nombre VARCHAR(255),
    IN descripcion VARCHAR(255),
    IN imagen VARCHAR(255),
    IN disponible TINYINT(1)
)
BEGIN
    UPDATE plataformas 
    SET nombre = nombre, descripcion = descripcion, imagen = imagen, disponible = disponible
    WHERE id = idPlataforma;
END $$

DELIMITER ;

--Editar plataforma

DELIMITER $$ 

CREATE PROCEDURE sp_videojuegos_plataforma_editar(
    IN nombre VARCHAR(255), 
    IN descripcion VARCHAR(255), 
    IN imagen VARCHAR(255),
    IN disponible TINYINT, 
    IN id INT
) 
BEGIN
    
UPDATE plataformas SET nombre = nombre, 
    descripcion = descripcion,
    imagen = imagen,
    disponible = disponible WHERE id =idPlataforma;


END$$ 

DELIMITER ;

--Eliminar plataforma

DELIMITER $$

CREATE PROCEDURE sp_videojuegos_plataforma_eliminar(
    IN idPlataforma INT
)
BEGIN
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
END $$

DELIMITER ;

-- Insertar plataforma

DELIMITER $$

CREATE PROCEDURE sp_videojuegos_plataforma_insertar(
    IN nombre VARCHAR(255),
    IN descripcion VARCHAR(255),
    IN disponible TINYINT(1),
    IN imagen VARCHAR(255),
    OUT id INT
)
BEGIN
    INSERT INTO plataformas (nombre, descripcion, disponible, imagen) 
    VALUES (nombre, descripcion, disponible, imagen);
    
    SET id = LAST_INSERT_ID();
END $$

DELIMITER ;

-- Obtener plataforma

DELIMITER $$

CREATE PROCEDURE sp_videojuegos_plataforma_seleccionar(
    IN idPlataforma INT
)
BEGIN
    SELECT * FROM plataformas WHERE id = idPlataforma;
END $$

DELIMITER ;

--Pedidos

--Actualizar pedidos
DELIMITER //

CREATE PROCEDURE sp_videojuegos_pedidos_actualizar(
    IN p_id INT,
    IN p_descripcion VARCHAR(255),
    IN p_totalprecio FLOAT,
    IN p_totalunidades FLOAT,
    IN p_estado VARCHAR(255)
)
BEGIN
    UPDATE pedidos
    SET descripcion = p_descripcion,
        totalprecio = p_totalprecio,
        totalunidades = p_totalunidades,
        estado = p_estado
    WHERE id = p_id;
END //

DELIMITER ;

--Eliminar pedidos

DELIMITER //

CREATE PROCEDURE sp_videojuegos_pedidos_eliminar(
    IN p_id INT
)
BEGIN
    -- Eliminar las relaciones del pedido con los juegos
    DELETE FROM pedidos_juegos WHERE idpedido = p_id;

    -- Eliminar el pedido
    DELETE FROM pedidos WHERE id = p_id;
END //

DELIMITER ;

--Insertar pedidos

DELIMITER //

CREATE PROCEDURE sp_videojuegos_pedidos_insertar(
    IN p_descripcion VARCHAR(255),
    IN p_totalprecio FLOAT,
    IN p_totalunidades FLOAT,
    IN p_estado VARCHAR(255),
    OUT p_id INT
)
BEGIN
    INSERT INTO pedidos (descripcion, totalprecio, totalunidades, estado)
    VALUES (p_descripcion, p_totalprecio, p_totalunidades, p_estado);
    
    SET p_id = LAST_INSERT_ID();
END //

DELIMITER ;

--Obtener pedidos

DELIMITER //

CREATE PROCEDURE sp_videojuegos_pedidos_seleccionar(
    IN p_id INT
)
BEGIN
    SELECT * FROM pedidos
    WHERE id = p_id;
END //

DELIMITER ;

-- SP del ejercicio 2

-- Aumentar precio

DELIMITER //

CREATE PROCEDURE sp_aumentar_precio_juegos(
    IN plataforma_nombre VARCHAR(255)
)
BEGIN
    UPDATE juegos j
    JOIN juegos_plataformas jp ON j.id = jp.id_juego
    JOIN plataformas p ON jp.id_plataforma = p.id
    SET j.precio = j.precio * 1.15
    WHERE p.nombre = plataforma_nombre;
END //

DELIMITER ;

-- Descuentos

DELIMITER //

CREATE PROCEDURE sp_crear_plataforma_y_aplicar_descuento(
    IN p_nombre VARCHAR(255),
    IN p_descripcion VARCHAR(255),
    IN p_disponible TINYINT(1),
    IN p_imagen VARCHAR(255)
)
BEGIN
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

END //

DELIMITER ;

-- Trigger

--Before borrar genero

DELIMITER //
CREATE TRIGGER trigger_videojuegos_juegos_before_borrar_genero
BEFORE DELETE ON generos
FOR EACH ROW
BEGIN
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
END //

DELIMITER ;
--Before borrar plataforma

DELIMITER //

CREATE TRIGGER trigger_videojuegos_juegos_before_borrar_plataforma
BEFORE DELETE ON plataformas
FOR EACH ROW
BEGIN
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
END //

DELIMITER ;

--before actualizar juegos_historicos

DELIMITER //

CREATE TRIGGER trigger_videojuegos_juegos_before_juego_update
BEFORE UPDATE ON juegos
FOR EACH ROW
BEGIN
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
END //

DELIMITER ;

--Cursores

--Actualizar 5 juegos

DELIMITER $$

CREATE PROCEDURE cursor_videojuegos_juego_actualizar_5juegos()
BEGIN
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

DELIMITER ;



-- Calcular precio total

DELIMITER //

CREATE PROCEDURE cursor_videojuegos_juego_calcularPrecioTotal(IN selected_ids VARCHAR(255))
BEGIN
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
END //

DELIMITER ;




DELIMITER //

CREATE PROCEDURE sp_videojuegos_juegos_insertar_y_aplicar_descuento.sql (
    IN p_nombre VARCHAR(255),
    IN p_descripcion VARCHAR(255),
    IN p_portada VARCHAR(255),
    IN p_precio FLOAT,
    IN p_idGenero INT,
    IN p_disponible TINYINT(1),
    IN p_idPlataforma INT
)
BEGIN
    -- Declarar variable para almacenar el último ID insertado
    DECLARE p_id INT;

    -- Insertar nuevo juego en la tabla juegos
    INSERT INTO juegos (nombre, descripcion, portada, precio, idgenero, disponible) 
    VALUES (p_nombre, p_descripcion, p_portada, p_precio, p_idGenero, p_disponible);

    -- Obtener el último ID insertado
    SET p_id = LAST_INSERT_ID();

    -- Insertar en juegos_plataformas
    INSERT INTO juegos_plataformas (id_juego, id_plataforma) 
    VALUES (p_id, p_idPlataforma);

    -- Aplicar el descuento del 30% al precio del juego
    UPDATE juegos j
    INNER JOIN juegos_plataformas jp ON j.id = jp.id_juego
    SET j.precio = j.precio * 0.7
    WHERE jp.id_plataforma = p_idPlataforma AND j.id = p_id;

END //

DELIMITER ;


