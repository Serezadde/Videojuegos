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
    private $idgenero;

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
        $querySelect = "CALL sp_videojuegos_juegos_obtener_nombre_plataforma()";

        $listaPlataformas = $this->db->prepare($querySelect);

        $listaPlataformas->bindParam(":idJuego", $idJuego);

        $listaPlataformas->execute();

        $nombrePlataformas = "";

        foreach ($listaPlataformas as $nombrePlataforma) {
            $nombrePlataformas = $nombrePlataformas . '[' . $nombrePlataforma["nombre"] . ']';
        }

        return $nombrePlataformas;
    }

    function obtenerListadoJuegos()
    {
        try {
            $querySelect = "CALL sp_videojuegos_juegos_seleccion();";
            $listaJuegos = $this->db->prepare($querySelect);


            $listaJuegos->execute();
            if ($listaJuegos) {

                foreach ($listaJuegos as $juego) {
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

    private function insertarRegistroJuego($nombre, $descripcion, $portada, $precio, $disponible)
    {
        $queryInsertar = "INSERT INTO juegos (nombre, descripcion, portada, precio, disponible)
        VALUES ('$nombre', '$descripcion', '$portada', '$precio', '$disponible')";

        $respuestaInsertar = $this->db->query($queryInsertar);

        // asignamos el id de juego que acabamos de crear.
        $this->id = $this->db->lastInsertId();

        return $respuestaInsertar;
    }
    private function insertarJuegosPlataformas($idsPlataformas, $idJuego)
    {
        try {
            foreach ($idsPlataformas as $idPlataforma) {
                // Llamada al procedimiento almacenado
                $query = "CALL sp_videojuegos_insertar_juego_plataforma(:juego_id, :plataforma_id)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(":juego_id", $idJuego, PDO::PARAM_INT);
                $stmt->bindParam(":plataforma_id", $idPlataforma, PDO::PARAM_INT);
                $stmt->execute();
            }
            return true; // Si todo va bien
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return false;
        }
    }

    function insertarJuego($nombre, $descripcion, $idsPlataformas, $portada, $precio, $disponible, $idgenero)
    {
        $this->db->beginTransaction();
        try {
            // Llamada al procedimiento almacenado
            $queryInsertar = "CALL sp_videojuegos_juegos_insertar(:nombre, :descripcion, :portada, :precio, :disponible, :genero, @id)";
            $respuestaInsertar = $this->db->prepare($queryInsertar);
    
            $respuestaInsertar->bindParam(":nombre", $nombre);
            $respuestaInsertar->bindParam(":descripcion", $descripcion);
            $respuestaInsertar->bindParam(":portada", $portada);
            $respuestaInsertar->bindParam(":precio", $precio);
            $respuestaInsertar->bindParam(":disponible", $disponible);
            $respuestaInsertar->bindParam(":genero", $idgenero);
            $respuestaInsertar->execute();
    
            // Obtener el valor del ID insertado
            $id = $this->db->query("SELECT @id")->fetch(PDO::FETCH_ASSOC)['@id'];
    
            // Insertar en la tabla intermedia juegos_plataformas
            $respuestaInsertarPlataformas = $this->insertarJuegosPlataformas($idsPlataformas, $id);
    
            if ($respuestaInsertar && $respuestaInsertarPlataformas) {
                $this->db->commit();
                header("Location: listar.php");
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

    function eliminarJuego()
    {
        $this->db->beginTransaction();
        try {
            $respuestaBorrar = $this->eliminarJuegosPedidos();
            $respuestaBorrar = $this->eliminarJuegosPlataformas();
            $respuestaBorrar = $this->eliminarJuegoSeleccionado();

            if ($respuestaBorrar) {
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

    function obtenerJuego($idJuego)
    {
        try {
            // Llamada al procedimiento almacenado
            $queryInsert = "CALL sp_videojuegos_obtener_juego_por_id(:juego_id)";
            $stmt = $this->db->prepare($queryInsert);
            $stmt->bindParam(":juego_id", $idJuego, PDO::PARAM_INT);
            $stmt->execute();
    
            // Obtener los resultados
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($resultado) {
                // Aquí puedes manipular los resultados si es necesario
                return $resultado;
            } else {
                echo "El juego no fue encontrado";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }
    

    private function actualizarRegistroJuego()
    {
        $queryUpdate = "UPDATE juegos SET nombre= :nombre, 
                            descripcion= :descripcion,
                            portada= :portada,
                            precio= :precio,
                            disponible= :disponible WHERE id = :idJuego";

        $instanciaDB = $this->db->prepare($queryUpdate);

        $instanciaDB->bindParam(":idJuego", $this->id);
        $instanciaDB->bindParam(":nombre", $this->nombre);
        $instanciaDB->bindParam(":descripcion", $this->descripcion);
        $instanciaDB->bindParam(":precio", $this->precio);
        $instanciaDB->bindParam(":portada", $this->portada);
        $instanciaDB->bindParam(":disponible", $this->disponible);

        return $instanciaDB->execute();

    }

    private function eliminarJuegosPlataformas(){
        $queryDeletePlataformas = "DELETE FROM juegos_plataformas WHERE id_juego= :idJuego";
        $instanciaDB = $this->db->prepare($queryDeletePlataformas);

        $instanciaDB->bindParam(":idJuego", $this->id);

        return $instanciaDB->execute();
    }

    function actualizarJuego($idsPlataformas)
    {
        $this->db->beginTransaction();
        try {
            

            $respuesta = $this->actualizarRegistroJuego();
            $respuesta = $this->eliminarJuegosPlataformas();
           /*

            $respuesta = $this->insertarJuegosPlataformas($idsPlataformas);
*/
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
    public function getIdgenero()
    {
        return $this->idgenero;
    }

    public function setIdgenero($idgenero): self
    {
        $this->idgenero = $idgenero;
        return $this;
    }
}