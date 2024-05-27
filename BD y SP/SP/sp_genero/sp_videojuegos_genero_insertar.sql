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
