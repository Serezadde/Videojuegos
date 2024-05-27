DELIMITER //

CREATE PROCEDURE sp_videojuegos_pedidos_eliminar(
    IN p_id INT
)
BEGIN
    -- Eliminar las relaciones del pedido con los juegos
    DELETE FROM pedidos_juegos WHERE idpedido = p_id;

    -- Eliminar el pedido
    DELETE FROM pedidos WHERE id = p_id;
END //

DELIMITER ;