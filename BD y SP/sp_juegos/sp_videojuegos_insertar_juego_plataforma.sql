DELIMITER $$ 

CREATE PROCEDURE sp_videojuegos_insertar_juego_plataforma(
    IN juego_id INT,
    IN plataforma_id INT
) 
BEGIN
    INSERT INTO juegos_plataformas (id_juego, id_plataforma) VALUES (juego_id, plataforma_id);
END$$ 

DELIMITER ;
