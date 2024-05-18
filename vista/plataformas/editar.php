<!DOCTYPE html>
<html>

<?php
include "../../componentes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="jumbotron">
            <h2>Editar Plataforma: </h2>
            <br>

            <?php
                include "../../controlador/plataformas/editar.php";
            ?>
            <div class="container-fluid">
                <form id="editarPlataformaForm" target="editar.php" method="post">

                    <div class="form-group">
                        <label>ID</label>
                        <input type="text" class="form-control" name="id" value="<?php echo $plataforma->getId(); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" name="nombre" value="<?php echo $plataforma->getNombre(); ?>" required>
                    </div>

                    <div class="form-floating">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" placeholder="" name="descripcion" style="height: 100px"><?php echo $plataforma->getDescripcion(); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Imagen</label>
                        <input type="text" class="form-control" name="imagen" value="<?php echo $plataforma != null ? $plataforma->getImagen() : ''; ?>" required>
                    </div>

                    <?php
                    include "../../componentes/seleccionestado.php";
                    ?>

                    <button type="submit" class="btn btn-primary">Editar Plataforma</button>
                </form>
                <br>
                <a href="listar.php"><button>Volver a plataformas</button></a>
            </div>
        </div>
</body>

</html>