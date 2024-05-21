DELIMITER //

CREATE PROCEDURE sp_videojuegos_pedidos_actualizar(
    IN p_id INT,
    IN p_descripcion VARCHAR(255),
    IN p_totalprecio FLOAT,
    IN p_totalunidades FLOAT,
    IN p_estado VARCHAR(255)
)
BEGIN
    UPDATE pedidos
    SET descripcion = p_descripcion,
        totalprecio = p_totalprecio,
        totalunidades = p_totalunidades,
        estado = p_estado
    WHERE id = p_id;
END //

DELIMITER ;
