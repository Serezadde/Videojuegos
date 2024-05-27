DELIMITER $$

CREATE PROCEDURE sp_videojuegos_juegos_obtener (IN id INT)
BEGIN
    SELECT * FROM juegos WHERE id = id;
END $$

DELIMITER ;
