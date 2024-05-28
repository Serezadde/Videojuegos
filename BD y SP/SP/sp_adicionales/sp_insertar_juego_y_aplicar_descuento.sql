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


a