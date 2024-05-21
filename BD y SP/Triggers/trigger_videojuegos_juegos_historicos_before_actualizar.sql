DELIMITER //

CREATE TRIGGER trigger_videojuegos_juegos_historicos_before_actualizar
BEFORE UPDATE ON juegos
FOR EACH ROW
BEGIN
    INSERT INTO juegos_historicos (
        id_juego,
        nombre_juego,
        descripcion_juego,
        portada_juego,
        precio_juego,
        id_genero,
        juego_disponible,
        operacion,
        registro_fecha_hora
    ) VALUES (
        OLD.id,
        OLD.nombre,
        OLD.descripcion,
        OLD.portada,
        OLD.precio,
        OLD.idgenero,
        OLD.disponible,
        'UPDATE',
        NOW()
    );
END //

DELIMITER ;
