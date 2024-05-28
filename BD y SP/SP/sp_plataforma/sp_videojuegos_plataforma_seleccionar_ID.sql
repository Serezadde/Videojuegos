DELIMITER $$

CREATE PROCEDURE sp_videojuegos_plataforma_seleccionar_ID(
    IN idPlataforma INT
)
BEGIN
    SELECT * FROM plataformas WHERE id = idPlataforma;
END $$

DELIMITER ;
