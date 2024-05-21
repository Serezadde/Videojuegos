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

