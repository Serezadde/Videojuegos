DELIMITER $$

CREATE PROCEDURE sp_videojuegos_juegos_actualizar (
    IN p_id INT,
    IN p_nombre VARCHAR(255),
    IN p_descripcion VARCHAR(255),
    IN p_portada VARCHAR(255),
    IN p_precio FLOAT,
    IN p_idgenero INT,
    IN p_disponible TINYINT(1)
)
BEGIN
    UPDATE juegos
    SET nombre = p_nombre,
        descripcion = p_descripcion,
        portada = p_portada,
        precio = p_precio,
        idgenero = p_idgenero,
        disponible = p_disponible
    WHERE id = p_id;
END $$

DELIMITER ;
