DELIMITER $$

CREATE PROCEDURE sp_videojuegos_genero_obtener_genero()

BEGIN
    SELECT * FROM generos
    WHERE id = id;
END$$

DELIMITER ;
