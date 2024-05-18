<?php
require_once "bd.php";

class Genero
{
    private $db;
    private $id;
    private $nombre;
    private $descripcion;

    function __construct()
    {
        $bd = new bd();
        $this->db = $bd->conectarBD();
    }

    function insertarGenero()
    {
        try {
            $queryInsertar = "CALL sp_videojuegos_genero_insertar(:nombre, :descripcion)";
            
            $instanciaDB = $this->db->prepare($queryInsertar);

            $instanciaDB->bindParam(":nombre", $this->nombre);
            $instanciaDB->bindParam(":descripcion", $this->descripcion);

            $instanciaDB->execute();

            if ($instanciaDB) {
                header("Location:listarGeneros.php");
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }

    public function actualizarGenero()
    {
        try {
            $queryUpdate = "CALL sp_videojuegos_genero_editar(:id, :nombre, :descripcion)";
            $instanciaDB = $this->db->prepare($queryUpdate);
            $instanciaDB->bindParam(":id", $this->id, PDO::PARAM_INT);
            $instanciaDB->bindParam(":nombre", $this->nombre, PDO::PARAM_STR);
            $instanciaDB->bindParam(":descripcion", $this->descripcion, PDO::PARAM_STR);
            $instanciaDB->execute();
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
        }
    }

    public function eliminarGenero()
    {
        try {
            $queryDelete = "CALL sp_videojuegos_genero_eliminar(:id)";
            $instanciaDB = $this->db->prepare($queryDelete);
            $instanciaDB->bindParam(":id", $this->id, PDO::PARAM_INT);
            $instanciaDB->execute();
            if ($instanciaDB) {
                header("Location: ../../vista/genero/listarGeneros.php");
            } 
            
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
        }
    }

    function obtenerListadoGeneros()
    {
        try {
            $querySelect = "SELECT * FROM generos";
            $listaGeneros = $this->db->prepare($querySelect);
            $listaGeneros->execute();

            if ($listaGeneros) {
                return $listaGeneros->fetchAll(PDO::FETCH_CLASS, "Genero");
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }
    function obtenerGenero()
    {
        try {
            $querySelect = "CALL sp_videojuegos_genero_obtener_genero(:id)";
            $instanciaDB = $this->db->prepare($querySelect);

            $instanciaDB->bindParam(":idGenero", $this->id);

            $instanciaDB->execute();

            if ($instanciaDB) {
                return $instanciaDB->fetchAll(PDO::FETCH_CLASS, "Genero")[0];
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }
 
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id 
     * @return self
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre 
     * @return self
     */
    public function setNombre($nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion 
     * @return self
     */
    public function setDescripcion($descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }
}
