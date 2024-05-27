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

