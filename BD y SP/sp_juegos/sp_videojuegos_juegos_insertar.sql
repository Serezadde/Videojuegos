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
