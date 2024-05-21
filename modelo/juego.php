<?php

require_once "bd.php";

class Juego
{
    private $db;
    private $id;
    private $nombre;
    private $descripcion;
    private $plataformas;
    private $portada;
    private $precio;
    private $disponible;
    private $idGenero;

    function __construct()
    {
        $bd = new bd();
        $this->db = $bd->conectarBD();
    }

    private function obtenerRegistrosJuegos($filtro = null, $orden = null)
    {
        if ($filtro && $orden) {
            $querySelect = "SELECT juegos.id, juegos.nombre, juegos.descripcion, juegos.portada, juegos.precio, juegos.disponible 
            FROM juegos ORDER BY $filtro $orden";
        } else {
            $querySelect = "SELECT juegos.id, juegos.nombre, juegos.descripcion, juegos.portada, juegos.precio, juegos.disponible 
            FROM juegos";
        }

        $listaJuegos = $this->db->prepare($querySelect);

        $listaJuegos->execute();

        return $listaJuegos->fetchAll(PDO::FETCH_CLASS, "Juego");
    }

    private function obtenerNombresPlataformas($idJuego)
    {
        $querySelect = "SELECT DISTINCT nombre FROM plataformas WHERE plataformas.id IN        
            (SELECT juegos_plataformas.id_plataforma FROM juegos_plataformas WHERE id_juego = :idJuego)";

        $listaPlataformas = $this->db->prepare($querySelect);

        $listaPlataformas->bindParam(":idJuego", $idJuego);

        $listaPlataformas->execute();

        $nombrePlataformas = "";

        foreach ($listaPlataformas as $nombrePlataforma) {
            $nombrePlataformas = $nombrePlataformas . '[' . $nombrePlataforma["nombre"] . ']';
        }

        return $nombrePlataformas;
    }

    function obtenerListadoJuegos($filtro = null, $orden = null)
    {
        try {

            $listaJuegos = $this->obtenerRegistrosJuegos($filtro, $orden);

            if ($listaJuegos) {

                foreach ($listaJuegos as &$juego) {
                    $juego->plataformas = $this->obtenerNombresPlataformas($juego->id);
                }

                return $listaJuegos;
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }

    private function insertarRegistroJuego($nombre, $descripcion, $portada, $precio, $disponible, $idgenero)
    {
        $query = "CALL sp_videojuegos_juegos_insertar(:nombre, :descripcion, :portada, :precio, :disponible, :idgenero, @id)";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $statement->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $statement->bindParam(':portada', $portada, PDO::PARAM_STR);
        $statement->bindParam(':precio', $precio, PDO::PARAM_STR);
                $statement->bindParam(':disponible', $disponible, PDO::PARAM_INT);
        $statement->bindParam(':idgenero', $idgenero, PDO::PARAM_INT);
        $statement->execute();

        // Obtener el ID generado por el procedimiento almacenado
        $statement = $this->db->query("SELECT @id AS id");
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->id = $result['id'];

        return $this->id;
    }


    private function insertarJuegosPlataformas($idsPlataforma)
    {
        $respuesta = null;
        foreach ($idsPlataforma as $idPlataforma) {
            $query = "INSERT INTO juegos_plataformas (id_juego, id_plataforma) VALUES (:id_juego, :id_plataforma)";
            $statement = $this->db->prepare($query);
            $statement->bindParam(':id_juego', $this->id, PDO::PARAM_INT);
            $statement->bindParam(':id_plataforma', $idPlataforma, PDO::PARAM_INT);
            $respuesta = $statement->execute();
        }
        return $respuesta;
    }

  
    public function insertarJuego($nombre, $descripcion, $idsPlataforma, $portada, $precio, $disponible, $idgenero)
    {
        $this->db->beginTransaction();
        try {
            $respuestaInsertar = $this->insertarRegistroJuego($nombre, $descripcion, $portada, $precio, $disponible, $idgenero );
            $respuestaInsertar = $this->insertarJuegosPlataformas($idsPlataforma);

            if ($respuestaInsertar) {
                $this->db->commit();
                header("Location:listar.php");
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            $this->db->rollback();
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }

    private function eliminarJuegosPedidos()
    {
        $queryDeleteJuegosPedidos = "DELETE FROM pedidos_juegos WHERE idjuego= :idJuego";
        $instanciaDB = $this->db->prepare($queryDeleteJuegosPedidos);

        $instanciaDB->bindParam(":idJuego", $this->id);

        return $instanciaDB->execute();
    }

    private function eliminarJuegoSeleccionado()
    {
        $queryDeleteJuegos = "DELETE FROM juegos WHERE id= :idJuego";
        $instanciaDB = $this->db->prepare($queryDeleteJuegos);

        $instanciaDB->bindParam(":idJuego", $this->id);

        return $instanciaDB->execute();
    }
    public function obtenerNombre() {
        // Obtener el nombre del juego antes de eliminarlo
        $query = "SELECT nombre FROM juegos WHERE id = :idJuego";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":idJuego", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $this->nombre = $result['nombre'];
            return $this->nombre;
        } else {
            return null;
        }
    }

    public function eliminarJuego() {
        $this->db->beginTransaction();
        try {
            // Llamada al procedimiento almacenado para eliminar el juego y actualizar el pedido asociado
            $queryEliminarJuego = "CALL sp_videojuegos_juegos_eliminar(:idJuego)";
            $stmt = $this->db->prepare($queryEliminarJuego);
            $stmt->bindParam(":idJuego", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            $this->db->commit();
            return true; // Devuelve verdadero si la operación es exitosa
        } catch (Exception $ex) {
            $this->db->rollBack();
            echo "Ocurrió un error: " . $ex->getMessage();
            return false; // Devuelve falso si ocurre un error
        }
    }


    function obtenerJuego()
    {
        try {
            $querySelect = "CALL sp_videojuegos_juegos_obtener(:idJuego)";

            $instanciaDB = $this->db->prepare($querySelect);

            $instanciaDB->bindParam(":idJuego", $this->id);

            $instanciaDB->execute();

            if ($instanciaDB) {
                return $instanciaDB->fetchAll(PDO::FETCH_CLASS, "Juego")[0];
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }

    private function actualizarRegistroJuego($id, $nombre, $descripcion, $portada, $precio, $idgenero, $disponible)
    {
        $query = "CALL sp_videojuegos_juegos_actualizar(:id, :nombre, :descripcion, :portada, :precio, :idgenero, :disponible)";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $statement->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $statement->bindParam(':portada', $portada, PDO::PARAM_STR);
        $statement->bindParam(':precio', $precio, PDO::PARAM_STR);
        $statement->bindParam(':idgenero', $idgenero, PDO::PARAM_INT);
        $statement->bindParam(':disponible', $disponible, PDO::PARAM_INT);
        return $statement->execute();
    }

    private function eliminarJuegosPlataformas(){
        $queryDeletePlataformas = "DELETE FROM juegos_plataformas WHERE id_juego= :idJuego";
        $instanciaDB = $this->db->prepare($queryDeletePlataformas);

        $instanciaDB->bindParam(":idJuego", $this->id);

        return $instanciaDB->execute();
    }

    public function actualizarJuego($id, $nombre, $descripcion, $portada, $precio, $idgenero, $disponible, $idsPlataformas)
    {
        $this->db->beginTransaction();
        try {
            $respuesta = $this->actualizarRegistroJuego($id, $nombre, $descripcion, $portada, $precio, $idgenero, $disponible);
            $respuesta = $this->eliminarJuegosPlataformas();
            $respuesta = $this->insertarJuegosPlataformas($idsPlataformas);

            if ($respuesta) {
                $this->db->commit();
                header("Location:listar.php");
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            $this->db->rollBack();
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }

    function obtenerPlataformas(){
     
        try {
            $querySelect = "SELECT DISTINCT id_plataforma FROM juegos_plataformas WHERE id_juego = :idJuego";

            $instanciaDB = $this->db->prepare($querySelect);

            $instanciaDB->bindParam(":idJuego", $this->id);

            $instanciaDB->execute();

            if ($instanciaDB) {
                return $instanciaDB->fetchAll();
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
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

    /**
     * @return mixed
     */
    public function getPlataformas()
    {
        return $this->plataformas;
    }

    /**
     * @param mixed $plataforma 
     * @return self
     */
    public function setPlataformas($plataformas): self
    {
        $this->plataformas = $plataformas;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPortada()
    {
        return $this->portada;
    }

    /**
     * @param mixed $portada 
     * @return self
     */
    public function setPortada($portada): self
    {
        $this->portada = $portada;
        return $this;
    }

    /**
     * @param mixed $precio 
     * @return self
     */
    public function setPrecio($precio): self
    {
        $this->precio = $precio;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * @return mixed
     */
    public function getDisponible()
    {
        return $this->disponible;
    }

    /**
     * @param mixed $disponible 
     * @return self
     */
    public function setDisponible($disponible): self
    {
        $this->disponible = $disponible;
        return $this;
    }

    /**
     * @return mixed
     */
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
    public function getIdGenero() {
        return $this->idGenero;
    }

    // Método para establecer el ID del género del juego
    public function setIdGenero($idGenero) {
        $this->idGenero = $idGenero;
    }
}