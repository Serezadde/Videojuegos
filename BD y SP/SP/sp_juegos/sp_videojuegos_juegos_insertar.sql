DELIMITER //

CREATE PROCEDURE sp_videojuegos_juegos_insertar (
    IN nombre VARCHAR(255),
    IN descripcion VARCHAR(255),
    IN portada VARCHAR(255),
    IN precio FLOAT,
    IN idGenero INT,
    IN disponible TINYINT(1),
    IN idPlataforma INT,
    OUT p_id INT
)
BEGIN
    -- Insertar nuevo juego en la tabla juegos
    INSERT INTO juegos (nombre, descripcion, portada, precio, idgenero, disponible) 
    VALUES (nombre, descripcion, portada, precio, idGenero, disponible);

    -- Obtener el último ID insertado
    SET p_id = LAST_INSERT_ID();
    
    -- Asociar el nuevo juego con la plataforma
    INSERT INTO juegos_plataformas (id_juego, id_plataforma) 
    VALUES (p_id, idPlataforma);
END //

DELIMITER ;
