<!DOCTYPE html>
<html>

<?php
include "../../componentes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="jumbotron">
            <h1>Editar Genero: </h1>
            <br>

            <?php
                include "../../controlador/genero/EditarGeneroController.php";
            ?>
            <div class="container-fluid">
                <form id="editarGeneroForm" target="editar.php" method="post">

                    <div class="form-group">
                        <label>ID</label>
                        <input type="text" class="form-control" name="id" value="<?php echo $Genero->getId(); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" name="nombre" value="<?php echo $Genero->getNombre(); ?>" required>
                    </div>

                    <div class="form-floating">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" placeholder="" name="descripcion" style="height: 100px"><?php echo $Genero->getDescripcion(); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Editar Genero</button>
                </form>
                <br>
                
            </div>
        </div>
        <a href="listar.php"><button>Volver a Generos</button></a>
</body>

</html>