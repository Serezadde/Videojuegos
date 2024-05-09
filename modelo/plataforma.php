<?php

require_once "bd.php";
require_once "pedido.php";

class Plataforma
{
    private $db;
    private $id;
    private $nombre;
    private $imagen;
    private $descripcion;
    private $disponible;

    function __construct()
    {
        $bd = new bd();
        $this->db = $bd->conectarBD();
    }

    function obtenerListadoPlataformas()
    {
        try {
            $querySelect = "SELECT * FROM plataformas";
            $listaPlataformas = $this->db->prepare($querySelect);
            $listaPlataformas->execute();

            if ($listaPlataformas) {
                return $listaPlataformas->fetchAll(PDO::FETCH_CLASS, "Plataforma");;
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }

    function insertarPlataforma()
    {
        try {
            $queryInsertar = "INSERT INTO plataformas (nombre, descripcion, disponible, imagen)
                                 VALUES (:nombre, :descripcion, :disponible, :imagen)";
            
            $instanciaDB = $this->db->prepare($queryInsertar);

            $instanciaDB->bindParam(":nombre", $this->nombre);
            $instanciaDB->bindParam(":descripcion", $this->descripcion);
            $instanciaDB->bindParam(":disponible", $this->disponible);
            $instanciaDB->bindParam(":imagen", $this->imagen);

            $instanciaDB->execute();

            if ($instanciaDB) {
                header("Location:listar.php");
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }

    private function obtenerIdPedidoJuegos($idJuego)
    {
        // Se deben eliminar todos los pedidos_juegos del pedido seleccionado
        $querySelect = "SELECT * FROM pedidos_juegos WHERE idjuego=$idJuego";
        $respuestaIdPedido = $this->db->query($querySelect)->fetch();

        if ($respuestaIdPedido) {
            $id = $respuestaIdPedido['idpedido'];
            return $id;
        }
        return null;
    }

    function eliminarPlataforma()
    {
        $this->db->beginTransaction();

        try {

            // Se deben seleccionar todos los juegos con la plataforma seleccionada.
            $querySelectJuegosPlataforma = "SELECT id FROM juegos WHERE plataforma=$this->id";
            $respuestaBorrar = $this->db->query($querySelectJuegosPlataforma);

            if ($respuestaBorrar) {
                $pedido = new Pedido();
                foreach ($respuestaBorrar as $juego) {
                    $idJuego = $juego['id'];

                    $idPedido = $this->obtenerIdPedidoJuegos($idJuego);

                    if ($idPedido != null) {
                        // Se deben eliminar todos los pedidos_juegos con dicha plataforma e idJuego
                        $queryBorrarJuegos = "DELETE FROM pedidos_juegos WHERE idjuego=$idJuego";
                        $queryBorrar = $this->db->query($queryBorrarJuegos);

                        // Se deben eliminar todos los juegos con dicha plataforma
                        $queryBorrarJuegos = "DELETE FROM juegos WHERE id=$idJuego";
                        $queryBorrar = $this->db->query($queryBorrarJuegos);

                        // Se resetean el total de unidades y el precio del pedido a -1
                        $queryBorrar = $pedido->actualizarTotalUnidadesPedido($idPedido);
                        $queryBorrar = $pedido->actualizarPrecioPedido($idPedido);
                    }
                }
            }
            // Se deben eliminar la plataforma seleccionada
            $queryBorrarPlataforma = "DELETE FROM plataformas WHERE id=$this->id";
            $respuestaBorrar = $this->db->query($queryBorrarPlataforma);

            if ($respuestaBorrar) {
                $this->db->commit();
                header("Location:listar.php");
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            echo "Se produjo un error de BD: Aplicamos rollback()";
            $this->db->rollBack();
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }
    function obtenerPlataforma()
    {
        try {
            $querySelect = "SELECT * FROM plataformas WHERE id = :idPlataforma";
            $instanciaDB = $this->db->prepare($querySelect);

            $instanciaDB->bindParam(":idPlataforma", $this->id);

            $instanciaDB->execute();

            if ($instanciaDB) {
                return $instanciaDB->fetchAll(PDO::FETCH_CLASS, "Plataforma")[0];
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }

    function actualizarPlataforma()
    {
        try {
            $queryUpdate = "UPDATE plataformas SET nombre = :nombre, 
                                    descripcion = :descripcion,
                                    imagen = :imagen,
                                    disponible = :disponible WHERE id = :idPlataforma";

            $instanciaDB = $this->db->prepare($queryUpdate);

            $instanciaDB->bindParam(":idPlataforma", $this->id);
            $instanciaDB->bindParam(":nombre", $this->nombre);
            $instanciaDB->bindParam(":descripcion", $this->descripcion);
            $instanciaDB->bindParam(":imagen", $this->imagen);
            $instanciaDB->bindParam(":disponible", $this->disponible);

            $instanciaDB->execute();

            if ($instanciaDB) {
                header("Location:listar.php");
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
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id 
	 * @return self
	 */
	public function setId($id): self {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getNombre() {
		return $this->nombre;
	}

	/**
	 * @param mixed $nombre 
	 * @return self
	 */
	public function setNombre($nombre): self {
		$this->nombre = $nombre;
		return $this;
	}

	/**
	 * @param mixed $descripcion 
	 * @return self
	 */
	public function setDescripcion($descripcion): self {
		$this->descripcion = $descripcion;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDisponible() {
		return $this->disponible;
	}

	/**
	 * @param mixed $disponible 
	 * @return self
	 */
	public function setDisponible($disponible): self {
		$this->disponible = $disponible;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDescripcion() {
		return $this->descripcion;
	}

	/**
	 * @return mixed
	 */
	public function getImagen() {
		return $this->imagen;
	}

	/**
	 * @param mixed $imagen 
	 * @return self
	 */
	public function setImagen($imagen): self {
		$this->imagen = $imagen;
		return $this;
	}
}
