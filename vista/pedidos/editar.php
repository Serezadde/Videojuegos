<!DOCTYPE html>
<html>

<?php
include "../../componentes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="jumbotron">
            <h2>Editar Pedido: </h2>
            <br>

            <?php
                include "../../controlador/pedidos/editar.php";
            ?>
            <div class="container-fluid">
                <form id="editarPedidoForm" target="editar.php" method="post">

                    <div class="form-group">
                        <label>ID</label>
                        <input type="text" class="form-control" name="id" value="<?php echo $pedido->getId(); ?>" readonly>
                    </div>

                    <div class="form-floating">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" placeholder="" name="descripcion" style="height: 100px"><?php echo $pedido->getDescripcion(); ?></textarea>
                    </div>

                    <?php
                    include "../../componentes/seleccionjuegos.php";
                    ?>

                    <?php
                    include "../../componentes/seleccionestadopedido.php";
                    ?>

                    <button type="submit" class="btn btn-primary">Editar Pedido</button>
                </form>
                <br>
                <a href="listar.php"><button>Volver a pedidos</button></a>
            </div>
        </div>
</body>

</html>