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
