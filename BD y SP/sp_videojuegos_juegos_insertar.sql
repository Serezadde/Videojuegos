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