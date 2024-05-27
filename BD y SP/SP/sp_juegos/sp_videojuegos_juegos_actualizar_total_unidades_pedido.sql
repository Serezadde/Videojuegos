DELIMITER $$

CREATE PROCEDURE sp_videojuegos_juegos_actualizar_total_unidades_pedido (IN idpedido INT)
BEGIN
    UPDATE pedidos
    SET totalunidades = (SELECT COUNT(*) FROM pedidos_juegos WHERE idpedido = idpedido)
    WHERE id = idpedido;
END $$

DELIMITER ;
