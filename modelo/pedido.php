<?php

require_once "bd.php";
require_once "juego.php";

class Pedido
{
    private $db;
    private $id;
    private $descripcion;
    private $totalprecio;
    private $totalunidades;
    private $estado;

    function __construct()
    {
        $bd = new bd();
        $this->db = $bd->conectarBD();
    }

    function obtenerListadoPedidos()
    {
        try {

            $querySelect = "SELECT * FROM pedidos";
            $instanciaDB = $this->db->prepare($querySelect);

            $instanciaDB->execute();

            if ($instanciaDB) {
                return $instanciaDB->fetchAll(PDO::FETCH_CLASS, "Pedido");
            } else {
                echo "Ocurrió un error inesperado al obtener el Listado de Libros";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }

    private function insertarPedidosJuegosIds($idsJuegos)
    {
        if (!empty($idsJuegos)) {
            foreach ($idsJuegos as $idJuego) {
                $queryInsertar = "INSERT INTO pedidos_juegos (idpedido, idjuego)
                                    VALUES (:idPedido, :idJuego)";

                $instanciaDB = $this->db->prepare($queryInsertar);

                $instanciaDB->bindParam(":idPedido", $this->id);
                $instanciaDB->bindParam(":idJuego", $idJuego);

                $instanciaDB->execute();
            }
            return $instanciaDB;
        }
        return null;
    }

    function insertarPedido($idsJuegos)
    {
        $this->db->beginTransaction();
    
        try {
            // Calculamos las unidades totales.
            $totalUnidades = count($idsJuegos);
    
            // Calculamos el precio final de los juegos elegidos.
            $totalPrecio = $this->obtenerTotalPrecio($idsJuegos);
    
            // Llamamos al procedimiento almacenado para insertar el pedido
            $queryInsertarPedido = "CALL sp_videojuegos_pedidos_insertar(:descripcion, :totalprecio, :totalUnidades, :estado, @id)";
            $instanciaDB = $this->db->prepare($queryInsertarPedido);
            $instanciaDB->bindParam(":descripcion", $this->descripcion);
            $instanciaDB->bindParam(":totalprecio", $totalPrecio);
            $instanciaDB->bindParam(":totalUnidades", $totalUnidades);
            $instanciaDB->bindParam(":estado", $this->estado);
    
            $instanciaDB->execute();
    
            // Obtenemos el id del pedido insertado
            $stmt = $this->db->query("SELECT @id AS id");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $idPedido = $row['id'];
    
            // Insertamos los juegos en la tabla pedidos_juegos
            foreach ($idsJuegos as $idJuego) {
                $queryInsertarPedidoJuego = "INSERT INTO pedidos_juegos (idpedido, idjuego) VALUES (:idpedido, :idjuego)";
                $instanciaDB = $this->db->prepare($queryInsertarPedidoJuego);
                $instanciaDB->bindParam(":idpedido", $idPedido);
                $instanciaDB->bindParam(":idjuego", $idJuego);
                $instanciaDB->execute();
            }
    
            $this->db->commit();
            header("Location:listar.php");
        } catch (Exception $ex) {
            $this->db->rollBack();
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }
    
    

    private function borrarPedidos()
    {
        $queryBorrar = "DELETE FROM pedidos WHERE id = :idPedido";
        $instanciaDB = $this->db->prepare($queryBorrar);
        $instanciaDB->bindParam(":idPedido", $this->id);
        return $instanciaDB->execute();
    }

    function eliminarPedido()
{
        try {
            // Comprobamos si la conexión a la base de datos está establecida
            if (!$this->db) {
                echo "No se pudo establecer una conexión a la base de datos.";
                return null;
            }
    
            // Iniciamos la transacción
            $this->db->beginTransaction();
    
            // Llamamos al procedimiento almacenado 
            $queryBorrarPedido = "CALL sp_videojuegos_pedidos_eliminar(:id)";
            $instanciaDB = $this->db->prepare($queryBorrarPedido);
            $instanciaDB->bindParam(":id", $this->id);
            $instanciaDB->execute();
    
            // Si todo fue exitoso, confirmamos la transacción
            $this->db->commit();
            header("Location: listar.php");
    
        } catch (Exception $ex) {
            // Si hay un error, realizamos rollback solo si hay una transacción activa
            if ($this->db->inTransaction()) {
                $this->db->rollBack(); 
            }
            echo "Se produjo un error: " . $ex->getMessage();
            return null;
        }
    }

    function obtenerPedido()
    {
        try {

            $querySelect = "SELECT * FROM pedidos WHERE id = :idPedido";
            
            $instanciaDB = $this->db->prepare($querySelect);
            $instanciaDB->bindParam(":idPedido", $this->id);
            $instanciaDB->execute();

            if ($instanciaDB) {
                return $instanciaDB->fetchAll(PDO::FETCH_CLASS, "Pedido")[0];
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }

    function obtenerListadoJuegosPedido()
    {
        try {
            $querySelect = "SELECT * FROM juegos WHERE juegos.id IN 
                                (SELECT idjuego FROM pedidos_juegos 
                                    WHERE pedidos_juegos.idpedido = :idPedido)";

            $instanciaDB = $this->db->prepare($querySelect);
            $instanciaDB->bindParam(":idPedido", $this->id);
            $instanciaDB->execute();

            if ($instanciaDB) {
                return $instanciaDB->fetchAll(PDO::FETCH_CLASS, "Juego");
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }

    function obtenerListadoJuegosIds()
    {
        try {

            $idsJuegos = [];

            $querySelect = "SELECT id FROM juegos WHERE juegos.id IN 
                                (SELECT idjuego FROM pedidos_juegos 
                                    WHERE pedidos_juegos.idpedido = :idPedido)";

            $instanciaDB = $this->db->prepare($querySelect);
            $instanciaDB->bindParam(":idPedido", $this->id);
            $instanciaDB->execute();

            if ($instanciaDB) {
                foreach ($instanciaDB as $idJuego) {
                    $idsJuegos[] = $idJuego;
                }
                return $idsJuegos;
            } else {
                echo "Ocurrió un error inesperado";
            }
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
            return null;
        }
    }

    function actualizarPrecioPedido()
    {
        $queryUpdate = "UPDATE pedidos SET totalprecio=-1 WHERE id = :idPedido";

        $instanciaDB = $this->db->prepare($queryUpdate);
        $instanciaDB->bindParam(":idPedido", $this->id);

        return $instanciaDB->execute();
    }

    private function obtenerTotalPrecio($idsJuegos)
    {
        $listadoIdsFiltrados = implode(",", $idsJuegos);

        $querySelect = "SELECT SUM(precio) as totalprecio FROM juegos WHERE id IN ($listadoIdsFiltrados)";
        $respuestaSelect = $this->db->query($querySelect);

        $resultado = $respuestaSelect->fetch();
        return $resultado['totalprecio'];
    }

    function actualizarTotalUnidadesPedido()
    {
        $queryUpdate = "UPDATE pedidos SET totalunidades=-1 WHERE id = :idPedido";
        
        $instanciaDB = $this->db->prepare($queryUpdate);
        $instanciaDB->bindParam(":idPedido", $this->id);
        
        return $instanciaDB->execute();
    }

    private function borrarJuegosPedidos()
    {
        $queryBorrar = "DELETE FROM pedidos_juegos WHERE idpedido = :idPedido";
        $instanciaDB = $this->db->prepare($queryBorrar);
        $instanciaDB->bindParam(":idPedido", $this->id);
        return $instanciaDB->execute();
    }

    function actualizarPedido($idsJuegos)
    {
        $this->db->beginTransaction();
        try {
            // Borramos los Ids de los juegos de la tabla pedidos_juegos
            $respuesta = $this->borrarJuegosPedidos();

            // Actualizamos los nuevos ids de la tabla pedidos_juegos
            $respuesta = $this->insertarPedidosJuegosIds($idsJuegos);

            // Realizamos los calculos de unidades y precios
            $totalUnidades = count($idsJuegos);
            $totalPrecio = $this->obtenerTotalPrecio($idsJuegos);

            // Por ultimo, actualizamos el pedido
            $queryUpdate = "CALL sp_videojuegos_pedidos_actualizar(
                :id,
                                    :descripcion,
                                    :totalprecio,
                                    :totalUnidades,
                                    :estado )";

            $instanciaDB = $this->db->prepare($queryUpdate);
            $instanciaDB->bindParam(":id", $this->id);
            $instanciaDB->bindParam(":descripcion", $this->descripcion);
            $instanciaDB->bindParam(":totalprecio", $totalPrecio);
            $instanciaDB->bindParam(":totalUnidades", $totalUnidades);
            $instanciaDB->bindParam(":estado", $this->estado);

            $respuesta = $instanciaDB->execute();

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

    function realizarPedido()
    {
        $this->db->beginTransaction();
        try {

            $respuesta = $this->borrarJuegosPedidos();
        
            $queryUpdate = "UPDATE pedidos SET estado='Realizado' WHERE id = :idPedido";
        
            $instanciaDB = $this->db->prepare($queryUpdate);
            $instanciaDB->bindParam(":idPedido", $this->id);
            $respuesta = $instanciaDB->execute();

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
    public function getTotalprecio()
    {
        return $this->totalprecio;
    }

    /**
     * @param mixed $totalprecio 
     * @return self
     */
    public function setTotalprecio($totalprecio): self
    {
        $this->totalprecio = $totalprecio;
        return $this;
    }

    /**
     * @param mixed $totalunidades 
     * @return self
     */
    public function setTotalunidades($totalunidades): self
    {
        $this->totalunidades = $totalunidades;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado 
     * @return self
     */
    public function setEstado($estado): self
    {
        $this->estado = $estado;
        return $this;
    }

	/**
	 * @return mixed
	 */
	public function getTotalunidades() {
		return $this->totalunidades;
	}
}