DELIMITER $$

CREATE PROCEDURE sp_videojuegos_genero_eliminar(
    IN idGenero INT
)
BEGIN
    -- Eliminar los pedidos asociados a los juegos que pertenecen al género
    DELETE FROM pedidos_juegos WHERE idjuego IN (SELECT id FROM juegos WHERE idgenero = idGenero);

    -- Eliminar los juegos asociados al género
    DELETE FROM juegos WHERE idgenero = idGenero;

    -- Finalmente, eliminar el género
    DELETE FROM generos WHERE id = idGenero;
END$$

DELIMITER ;

/*
DELIMITER //

CREATE PROCEDURE eliminarGenero(
    IN genero_id INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    DELETE FROM generos WHERE id = genero_id;

    COMMIT;
END//

DELIMITER ;
*/