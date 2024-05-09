<!DOCTYPE html>
<html>

<?php
include "../../componentes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="jumbotron">
            <h2>Listado de Pedidos: </h2>
            <br>
            <table class='table'>
                <thead class='table-dark'>
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Unidades</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Operaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include "../../controlador/pedidos/listar.php";
                    ?>
                </tbody>
            </table>

            <br>
            <a href="../../index.php"><button class="btn btn-success">Principal </button></a>
            <a href="insertar.php"><button class="btn btn-primary">Nuevo Pedido</button></a>

        </div>
    </div>
</body>

</html>