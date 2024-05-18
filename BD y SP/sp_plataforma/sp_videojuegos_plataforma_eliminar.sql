DELIMITER $$

CREATE PROCEDURE sp_videojuegos_plataforma_eliminar(
    IN idPlataforma INT
)
BEGIN
    START TRANSACTION;

    -- Eliminar asociaciones en pedidos_juegos relacionadas con los juegos de la plataforma
    DELETE pedidos_juegos
    FROM pedidos_juegos
    INNER JOIN juegos_plataformas ON pedidos_juegos.idjuego = juegos_plataformas.id_juego
    WHERE juegos_plataformas.id_plataforma = idPlataforma;
    
    -- Eliminar los juegos asociados a la plataforma y sus asociaciones en juegos_plataformas
    DELETE FROM juegos_plataformas WHERE id_plataforma = idPlataforma;
    DELETE FROM juegos WHERE id IN (SELECT id_juego FROM juegos_plataformas WHERE id_plataforma = idPlataforma);

    -- Eliminar la plataforma de la tabla plataformas
    DELETE FROM plataformas WHERE id = idPlataforma;

    COMMIT;
END $$

DELIMITER ;

