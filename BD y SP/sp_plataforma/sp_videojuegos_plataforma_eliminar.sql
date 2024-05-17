DELIMITER $$ 

CREATE PROCEDURE sp_videojuegos_plataforma_eliminar(
    IN id INT
) 
BEGIN

    DELETE FROM plataformas WHERE plataformas.id=id;

END$$ 

DELIMITER ;