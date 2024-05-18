<!DOCTYPE html>
<html>

<?php
include "../../componentes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="jumbotron">
            <h2>Crear nuevo Juego: </h2>
            <br>

            <?php
                include "../../controlador/juegos/insertar.php";
            ?>
            <form id="insertarJuegoForm" target="insertar.php" method="post">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>

                <div class="form-floating">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" placeholder="" name="descripcion" style="height: 100px"></textarea>
                </div>

                <?php
                include "../../componentes/seleccionplataformasjuegos.php";
                ?>

                <div class="form-group">
                    <label>Portada</label>
                    <input type="text" class="form-control" name="portada" required>
                </div>

                <div class="form-group">
                    <label>Precio</label>
                    <input type="number" step=0.01 class="form-control" name="precio" required>
                </div>

                <?php
                include "../../componentes/seleccionestado.php";
                include "../../componentes/selecciongenero.php";
                ?>

                <button type="submit" class="btn btn-primary">Crear Juego</button>
            </form>
            <br>
            <a href="../../vista/juegos/listar.php"><button>Volver a Juegos</button></a>
        </div>
    </div>
</body>

</html>