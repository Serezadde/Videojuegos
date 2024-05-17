DELIMITER $$ 

CREATE PROCEDURE sp_videojuegos_plataforma_insertar(
    IN nombre VARCHAR(255), 
    IN descripcion VARCHAR(255), 
    IN imagen VARCHAR(255), 
    IN disponible TINYINT, 
    OUT id INT
) 
BEGIN
    INSERT INTO plataformas (nombre, descripcion, imagen, disponible) VALUES (nombre, descripcion, imagen, disponible);
      SELECT MAX(plataformas.id) INTO @id FROM plataformas;


END$$ 

DELIMITER ;