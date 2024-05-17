DELIMITER $$ 

CREATE PROCEDURE sp_videojuegos_obtener_juego_por_id(
    IN juego_id INT
) 
BEGIN
    SELECT * FROM juegos WHERE id = juego_id;
END$$ 

DELIMITER ;
