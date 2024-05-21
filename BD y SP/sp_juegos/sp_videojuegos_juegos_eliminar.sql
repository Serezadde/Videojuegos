DELIMITER //

CREATE PROCEDURE sp_videojuegos_juegos_eliminar (IN p_id INT)
BEGIN
    DECLARE v_idpedido INT;
    
    -- Encontrar el id del pedido asociado con el juego
    SELECT idpedido INTO v_idpedido
    FROM pedidos_juegos
    WHERE idjuego = p_id;
    
    -- Eliminar el juego de la tabla pedidos_juegos
    DELETE FROM pedidos_juegos WHERE idjuego = p_id;
    
    -- Eliminar el juego de la tabla juegos
    DELETE FROM juegos WHERE id = p_id;

    -- Actualizar el pedido asociado
    IF v_idpedido IS NOT NULL THEN
        CALL sp_videojuegos_juegos_actualizar_total_unidades_pedido(v_idpedido);
        CALL sp_videojuegos_juegos_actualizar_precio_pedido(v_idpedido);
    END IF;
END //

DELIMITER ;