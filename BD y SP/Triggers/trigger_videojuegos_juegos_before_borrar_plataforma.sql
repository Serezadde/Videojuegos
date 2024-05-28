DELIMITER //

CREATE TRIGGER trigger_videojuegos_juegos_before_borrar_plataforma
BEFORE DELETE ON plataformas
FOR EACH ROW
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE cur_juego_id INT;
    DECLARE cur CURSOR FOR SELECT id_juego FROM juegos_plataformas WHERE id_plataforma = OLD.id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO cur_juego_id;
        IF done THEN
            LEAVE read_loop;
        END IF;
        DELETE FROM pedidos_juegos WHERE idjuego = cur_juego_id;
        DELETE FROM juegos WHERE id = cur_juego_id;
    END LOOP;
    CLOSE cur;
    DELETE FROM juegos_plataformas WHERE id_plataforma = OLD.id;
END //

DELIMITER ;