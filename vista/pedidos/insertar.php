<!DOCTYPE html>
<html>

<?php
include "../../componentes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="jumbotron">
            <h2>Crear nuevo Pedido: </h2>
            <br>
     
            <?php
                  include "../../controlador/pedidos/insertar.php";
            ?>
            <form id="insertarPedidoForm" target="insertar.php" method="post">

                <div class="form-floating">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" placeholder="" name="descripcion" style="height: 100px"></textarea>
                </div>

                <?php
                include "../../componentes/seleccionjuegos.php";
                ?>

                <?php
                include "../../componentes/seleccionestadopedido.php";
                ?>

                <button type="submit" class="btn btn-primary">Crear Pedido</button>
            </form>
            <br>
            <a href="listar.php"><button>Volver a Pedidos</button></a>
        </div>
    </div>
</body>

</html>