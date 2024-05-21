DELIMITER //

CREATE PROCEDURE sp_aumentar_precio_juegos(
    IN plataforma_nombre VARCHAR(255)
)
BEGIN
    UPDATE juegos j
    JOIN juegos_plataformas jp ON j.id = jp.id_juego
    JOIN plataformas p ON jp.id_plataforma = p.id
    SET j.precio = j.precio * 1.15
    WHERE p.nombre = plataforma_nombre;
END //

DELIMITER ;