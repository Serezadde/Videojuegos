DELIMITER $$ 

CREATE PROCEDURE sp_videojuegos_juegos_eliminar(
    IN id INT
) 
BEGIN

    DELETE FROM videojuegos WHERE videojuegos.id=id;

END$$ 

DELIMITER ;