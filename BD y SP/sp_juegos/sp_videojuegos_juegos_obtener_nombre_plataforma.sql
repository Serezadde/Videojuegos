DELIMITER $$ 

CREATE PROCEDURE sp_videojuegos_juegos_obtener_nombre_plataforma(
    IN id INT
)
BEGIN

    RETURN (SELECT DISTINCT nombre FROM plataformas WHERE plataformas.id IN        
            (SELECT juegos_plataformas.id_plataforma FROM juegos_plataformas WHERE id_juego = :idJuego));

END$$ 

DELIMITER ;