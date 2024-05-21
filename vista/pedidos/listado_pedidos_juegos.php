<!DOCTYPE html>
<html>

<?php
include "../../componentes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="jumbotron">
            <h2>Listado de Juegos del Pedido: </h2>
            <br>
            <table class='table'>
                <thead class='table-dark'>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Plataforma</th>
                        <th>Portada</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "../../controlador/pedidos/listado_pedidos_juegos.php";
                    ?>
                </tbody>
            </table>

            <?php
            echo "<h2>Total Unidades: " . $pedido->getTotalunidades() . "<br>";
            echo "Coste Total: " . $pedido->getTotalprecio() . " €<br></h2>";
            ?>

            <br>
            <a href="listar.php"><button class="btn btn-success">Volver a Pedidos </button></a>

        </div>
    </div>
</body>

</html>