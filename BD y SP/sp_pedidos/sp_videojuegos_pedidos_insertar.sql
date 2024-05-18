DELIMITER //

CREATE PROCEDURE sp_videojuegos_pedidos_insertar(
    IN p_descripcion VARCHAR(255),
    IN p_totalprecio FLOAT,
    IN p_totalunidades FLOAT,
    IN p_estado VARCHAR(255),
    OUT p_id INT
)
BEGIN
    INSERT INTO pedidos (descripcion, totalprecio, totalunidades, estado)
    VALUES (p_descripcion, p_totalprecio, p_totalunidades, p_estado);
    
    SET p_id = LAST_INSERT_ID();
END //

DELIMITER ;
