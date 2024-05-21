DELIMITER //

CREATE PROCEDURE sp_crear_plataforma_y_aplicar_descuento(
    IN p_nombre VARCHAR(255),
    IN p_descripcion VARCHAR(255),
    IN p_disponible TINYINT(1),
    IN p_imagen VARCHAR(255)
)
BEGIN
    DECLARE v_id_plataforma INT;

    -- Insertar la nueva plataforma
    INSERT INTO plataformas (nombre, descripcion, disponible, imagen)
    VALUES (p_nombre, p_descripcion, p_disponible, p_imagen);
    
    -- Obtener el ID de la nueva plataforma
    SET v_id_plataforma = LAST_INSERT_ID();

    -- Aplicar el descuento del 30% a todos los juegos de la nueva plataforma
    UPDATE juegos j
    INNER JOIN juegos_plataformas jp ON j.id = jp.id_juego
    SET j.precio = j.precio * 0.7
    WHERE jp.id_plataforma = v_id_plataforma;

    -- Verificar los precios actualizados (esto sería en una consulta aparte desde la aplicación)
    SELECT j.id, j.nombre, j.precio
    FROM juegos j
    INNER JOIN juegos_plataformas jp ON j.id = jp.id_juego
    WHERE jp.id_plataforma = v_id_plataforma;

END //

DELIMITER ;
