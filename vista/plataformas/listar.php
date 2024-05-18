<!DOCTYPE html>
<html>

<?php
include "../../componentes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="jumbotron">
            <h2>Listado de Plataformas: </h2>
            <br>
            <table class='table'>
                <thead class='table-dark'>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Imagen</th>
                        <th>Disponible</th>
                        <th>Operaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include "../../controlador/plataformas/listar.php";
                    ?>
                </tbody>
            </table>

            <br>
            <a href="../../index.php"><button class="btn btn-success">Principal </button></a>
            <a href="insertar.php"><button class="btn btn-primary">Nueva Plataforma</button></a>
        </div>
    </div>
</body>

</html>