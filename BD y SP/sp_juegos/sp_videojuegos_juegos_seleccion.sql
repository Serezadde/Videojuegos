DELIMITER $$ 

CREATE PROCEDURE sp_videojuegos_juegos_seleccion()
BEGIN

    SELECT * FROM juegos;

END$$ 

DELIMITER ;