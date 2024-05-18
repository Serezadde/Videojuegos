<<<<<<< HEAD
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
=======
DELIMITER $$ 

CREATE PROCEDURE sp_videojuegos_juegos_insertar(
    IN nombre VARCHAR(255), 
    IN descripcion VARCHAR(255), 
    IN portada VARCHAR(255),
    IN precio float, 
    IN disponible TINYINT, 
    IN idgenero INT(11),
    OUT id INT
) 
BEGIN
    
    INSERT INTO juegos(nombre,descripcion,portada,precio,disponible,idgenero) VALUES (nombre,descripcion,portada,precio,disponible,idgenero);
    SELECT MAX(juegos.id) INTO @id FROM juegos;


END$$ 

DELIMITER ;
>>>>>>> b4ee6e143ab1f51b1bfff0a9911c13df74bc8fd6
