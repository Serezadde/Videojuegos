<<<<<<< HEAD
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
=======
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
>>>>>>> 130258f030f09aa059495cf1b1e026f3099215af
