<!DOCTYPE html>
<html>

<?php
include "../../componentes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="jumbotron">
            <h2>Crear nueva Plataforma: </h2>
            <br>

            <?php
                include "../../controlador/plataformas/insertar.php";
            ?>
            <form id="insertarPlataformaForm" target="insertar.php" method="post">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>

                <div class="form-floating">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" placeholder="" name="descripcion" style="height: 100px"></textarea>
                </div>

                <div class="form-group">
                    <label>Imagen</label>
                    <input type="text" class="form-control" name="imagen">
                </div>
                
                <?php
                include "../../componentes/seleccionestado.php";
                ?>

                <button type="submit" class="btn btn-primary">Crear Plataforma</button>
            </form>
            <br>
            <a href="listar.php"><button>Volver al Plataformas</button></a>
        </div>
    </div>
</body>

</html>