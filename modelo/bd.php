<?php
class bd
{
    private $cadenaConexion = "mysql:dbname=videojuegos;host=localhost;port=3306";
    private $usuario = "root";
    private $clave = "";

    function conectarBD()
    {
        try {
            return new PDO($this->cadenaConexion, $this->usuario, $this->clave);
        } catch (PDOException $e) {
            echo "Error ocurrio un problema en Base de datos: " . $e->getMessage();
            return null;
        }
    }
}
