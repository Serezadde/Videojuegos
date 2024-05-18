DELIMITER //

CREATE PROCEDURE sp_videojuegos_juegos_actualizar_total_unidades_pedido (IN p_idpedido INT)
BEGIN
    UPDATE pedidos
    SET totalunidades = (SELECT COUNT(*) FROM pedidos_juegos WHERE idpedido = p_idpedido)
    WHERE id = p_idpedido;
END //

DELIMITER ;
