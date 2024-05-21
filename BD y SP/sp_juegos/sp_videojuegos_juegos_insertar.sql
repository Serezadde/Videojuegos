DELIMITER //

CREATE PROCEDURE sp_videojuegos_juegos_insertar (
    IN nombre VARCHAR(255),
    IN descripcion VARCHAR(255),
    IN portada VARCHAR(255),
    IN precio FLOAT,
    IN idPlataformas INT,
    IN idGenero INT,
    IN disponible TINYINT(1),
    OUT id INT
)
BEGIN
    INSERT INTO juegos (nombre, descripcion, portada, precio, idPlataformas, disponible) 
    VALUES (nombre, descripcion, portada, precio, idPlataformas, disponible);
    
    SET id = LAST_INSERT_ID();
END //

DELIMITER ;
