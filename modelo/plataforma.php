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
            $queryInsertar = "CALL sp_videojuegos_plataforma_insertar(:nombre, :descripcion, :disponible, :imagen, @id)";
            
            $instanciaDB = $this->db->prepare($queryInsertar);
<<<<<<< HEAD
    
=======

>>>>>>> 130258f030f09aa059495cf1b1e026f3099215af
            $instanciaDB->bindParam(":nombre", $this->nombre);
            $instanciaDB->bindParam(":descripcion", $this->descripcion);
            $instanciaDB->bindParam(":disponible", $this->disponible);
            $instanciaDB->bindParam(":imagen", $this->imagen);
<<<<<<< HEAD
    
            $instanciaDB->execute();
    
=======

            $instanciaDB->execute();

>>>>>>> 130258f030f09aa059495cf1b1e026f3099215af
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
<<<<<<< HEAD
    

    private function obtenerIdPedidoJuegos($idJuego)
    {
        $querySelect = "SELECT * FROM pedidos_juegos WHERE idjuego = :idJuego";
        $respuestaIdPedido = $this->db->prepare($querySelect);
        $respuestaIdPedido->bindParam(":idJuego", $idJuego);
        $respuestaIdPedido->execute();
    
        if ($respuestaIdPedido) {
            $idPedido = $respuestaIdPedido->fetch(PDO::FETCH_ASSOC)['idpedido'];
            return $idPedido;
        }
        return null;
    }
    

    function eliminarPlataforma()
{
    try {
        // Comprobamos si la conexión a la base de datos está establecida
        if (!$this->db) {
            echo "No se pudo establecer una conexión a la base de datos.";
            return null;
        }

        // Iniciamos la transacción
        $this->db->beginTransaction();

        // Seleccionamos todos los juegos asociados a la plataforma
        $querySelectJuegosPlataforma = "SELECT id_juego FROM juegos_plataformas WHERE id_plataforma = :idPlataforma";
        $stmtSelectJuegosPlataforma = $this->db->prepare($querySelectJuegosPlataforma);
        $stmtSelectJuegosPlataforma->bindParam(":idPlataforma", $this->id);
        $stmtSelectJuegosPlataforma->execute();
        $juegos = $stmtSelectJuegosPlataforma->fetchAll(PDO::FETCH_ASSOC);

        // Instanciamos un objeto Pedido
        $pedido = new Pedido();

        // Eliminamos los pedidos_juegos y los juegos asociados a la plataforma
        foreach ($juegos as $juego) {
            $idJuego = $juego['id_juego'];

            $idPedido = $this->obtenerIdPedidoJuegos($idJuego);

            if ($idPedido != null) {
                // Eliminamos los pedidos_juegos con dicha plataforma e idJuego
                $queryBorrarPedidosJuegos = "DELETE FROM pedidos_juegos WHERE idjuego = :idJuego";
                $stmtBorrarPedidosJuegos = $this->db->prepare($queryBorrarPedidosJuegos);
                $stmtBorrarPedidosJuegos->bindParam(":idJuego", $idJuego);
                $stmtBorrarPedidosJuegos->execute();

                // Eliminamos los juegos con dicha plataforma
                $queryBorrarJuego = "DELETE FROM juegos WHERE id = :idJuego";
                $stmtBorrarJuego = $this->db->prepare($queryBorrarJuego);
                $stmtBorrarJuego->bindParam(":idJuego", $idJuego);
                $stmtBorrarJuego->execute();

                // Reseteamos el total de unidades y el precio del pedido a -1
                $pedido->actualizarTotalUnidadesPedido($idPedido);
                $pedido->actualizarPrecioPedido($idPedido);
            }
        }

        // Llamamos al procedimiento almacenado para eliminar la plataforma
        $queryBorrarPlataforma = "CALL sp_videojuegos_plataforma_eliminar(:idPlataforma)";
        $stmtBorrarPlataforma = $this->db->prepare($queryBorrarPlataforma);
        $stmtBorrarPlataforma->bindParam(":idPlataforma", $this->id);
        $stmtBorrarPlataforma->execute();

        // Si todo fue exitoso, confirmamos la transacción
        $this->db->commit();
        header("Location: borrar.php");

    } catch (Exception $ex) {
        // Si hay un error, realizamos rollback solo si hay una transacción activa
        if ($this->db->inTransaction()) {
            $this->db->rollBack(); 
        }
        echo "Se produjo un error: " . $ex->getMessage();
        return null;
    }
}

    
    

    function obtenerPlataforma()
    {
        try {
            $querySelect = "CALL sp_videojuegos_plataforma_seleccionar(:idPlataforma)";
            $instanciaDB = $this->db->prepare($querySelect);
    
            $instanciaDB->bindParam(":idPlataforma", $this->id);
    
            $instanciaDB->execute();
    
=======

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
            $queryBorrarPlataforma = "CALL sp_videojuegos_plataforma_eliminar()";
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

>>>>>>> 130258f030f09aa059495cf1b1e026f3099215af
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
<<<<<<< HEAD
    
=======
>>>>>>> 130258f030f09aa059495cf1b1e026f3099215af

    function actualizarPlataforma()
    {
        try {
<<<<<<< HEAD
            $queryUpdate = "CALL sp_videojuegos_plataforma_actualizar(:idPlataforma, :nombre, :descripcion, :imagen, :disponible)";
    
            $instanciaDB = $this->db->prepare($queryUpdate);
    
=======
            $queryUpdate = "";

            $instanciaDB = $this->db->prepare($queryUpdate);

>>>>>>> 130258f030f09aa059495cf1b1e026f3099215af
            $instanciaDB->bindParam(":idPlataforma", $this->id);
            $instanciaDB->bindParam(":nombre", $this->nombre);
            $instanciaDB->bindParam(":descripcion", $this->descripcion);
            $instanciaDB->bindParam(":imagen", $this->imagen);
            $instanciaDB->bindParam(":disponible", $this->disponible);
<<<<<<< HEAD
    
            $instanciaDB->execute();
    
=======

            $instanciaDB->execute();

>>>>>>> 130258f030f09aa059495cf1b1e026f3099215af
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
<<<<<<< HEAD
    
=======
>>>>>>> 130258f030f09aa059495cf1b1e026f3099215af

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
