DELIMITER //

CREATE PROCEDURE sp_videojuegos_pedidos_seleccionar(
    IN p_id INT
)
BEGIN
    SELECT * FROM pedidos
    WHERE id = p_id;
END //

DELIMITER ;
