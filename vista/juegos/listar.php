<!DOCTYPE html>
<html>

<?php
include "../../componentes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="jumbotron">
            <h2>Listado de Juegos: </h2>
            <br>
            <a href="listar.php?filtro=Nombre&orden=asc"><button>Nombre (A a Z)</button></a>
            <a href="listar.php?filtro=Nombre&orden=desc"><button> Nombre (Z a A)</button></a>
            <a href="listar.php?filtro=Precio&orden=asc"><button> Precio (Menor a Mayor)</button></a>
            <a href="listar.php?filtro=Precio&orden=desc"><button> Precio (Mayor a Menor)</button></a>
           
            <table class='table'>
                <thead class='table-dark'>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Plataformas</th>
                        <th>Portada</th>
                        <th>Precio</th>
                        <th>Disponible</th>
                        <th>Operaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        include "../../controlador/juegos/listar.php";
                    ?>
                </tbody>
            </table>

            <br>
            <a href="../../index.php"><button class="btn btn-success">Principal </button></a>
            <a href="insertar.php"><button class="btn btn-primary">Nuevo Juego</button></a>

        </div>
    </div>
</body>

</html>