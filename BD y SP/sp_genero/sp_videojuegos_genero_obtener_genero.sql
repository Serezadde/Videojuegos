DELIMITER $$

CREATE PROCEDURE sp_videojuegos_genero_obtener_genero(
    IN id INT
)
BEGIN
    SELECT * FROM generos
    WHERE id = id;
END$$

DELIMITER ;
