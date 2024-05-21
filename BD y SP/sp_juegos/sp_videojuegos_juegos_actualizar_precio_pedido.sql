DELIMITER $$

CREATE PROCEDURE sp_videojuegos_juegos_actualizar_precio_pedido (IN idpedido INT)
BEGIN
    UPDATE pedidos
    SET totalprecio = (SELECT SUM(juegos.precio) 
                       FROM pedidos_juegos 
                       JOIN juegos j ON pedidos_juegos.idjuego = juegos.id
                       WHERE pedidos_juegos.idpedido = idpedido)
    WHERE id = idpedido;
END $$

DELIMITER ;
