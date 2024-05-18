DELIMITER $$

CREATE PROCEDURE sp_videojuegos_juegos_obtener (IN p_id INT)
BEGIN
    SELECT * FROM juegos WHERE id = p_id;
END $$

DELIMITER ;
