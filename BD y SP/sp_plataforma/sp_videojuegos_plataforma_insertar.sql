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
