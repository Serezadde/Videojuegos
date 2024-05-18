DELIMITER $$

CREATE PROCEDURE sp_videojuegos_plataforma_seleccionar(
    IN idPlataforma INT
)
BEGIN
    SELECT * FROM plataformas WHERE id = idPlataforma;
END $$

DELIMITER ;
