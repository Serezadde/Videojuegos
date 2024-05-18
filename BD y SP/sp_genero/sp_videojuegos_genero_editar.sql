DELIMITER $$

CREATE PROCEDURE sp_videojuegos_genero_editar(
    IN id INT,
    IN nombre VARCHAR(255),
    IN descripcion VARCHAR(255)
)
BEGIN
    UPDATE generos
    SET 
        nombre = nombre,
        descripcion = descripcion
    WHERE 
        id = id;
END$$

DELIMITER ;

