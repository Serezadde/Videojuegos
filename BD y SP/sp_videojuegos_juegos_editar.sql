DELIMITER $$ 

CREATE PROCEDURE sp_videojuegos_juegos_editar(
    IN nombre VARCHAR(255), 
    IN descripcion VARCHAR(255), 
    IN portada VARCHAR(255),
    IN precio float, 
    IN idgenero INT(11),
    IN disponible TINYINT, 
    IN id INT
) 
BEGIN
    
    UPDATE juegos SET nombre=nombre,descripcion=descripcion,portada=portada,precio=precio,idgnero=idgenero,disponible=disponible,id=id
    WHERE juegos.id=id;


END$$ 

DELIMITER ;