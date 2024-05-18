DELIMITER //

CREATE PROCEDURE sp_videojuegos_juegos_actualizar_precio_pedido (IN p_idpedido INT)
BEGIN
    UPDATE pedidos
    SET totalprecio = (SELECT SUM(j.precio) 
                       FROM pedidos_juegos pj
                       JOIN juegos j ON pj.idjuego = j.id
                       WHERE pj.idpedido = p_idpedido)
    WHERE id = p_idpedido;
END //

DELIMITER ;
