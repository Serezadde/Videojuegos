<!DOCTYPE html>
<html>

<?php
include "../../componentes/head.php";
?>

<body>
    <div class="container-fluid">
        <div class="jumbotron">
            <h1>Editar Juego: </h1>
            <br>

            <?php
                include "../../controlador/juegos/editar.php";
            ?>
            <div class="container-fluid">
                <form id="editarJuegoForm" target="editar.php" method="post">

                    <div class="form-group">
                        <label>ID</label>
                        <input type="text" class="form-control" name="id" value="<?php echo $juego->getId(); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" name="nombre" value="<?php echo $juego->getNombre(); ?>" required>
                    </div>

                    <div class="form-floating">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" placeholder="" name="descripcion" style="height: 100px"><?php echo $juego->getDescripcion(); ?></textarea>
                    </div>

                    <?php
                    include "../../componentes/seleccionplataformasjuegos.php";
                    ?>

                    <div class="form-group">
                        <label>Portada</label>
                        <input type="text" class="form-control" name="portada" value="<?php echo $juego->getPortada(); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Precio</label>
                        <input type="number" step=0.01 class="form-control" name="precio" value="<?php echo $juego->getPrecio(); ?>" required>
                    </div>

                    <?php
                    include "../../componentes/seleccionestado.php";
                    
                    ?>
                    
                    <?php
     
                    include "../../componentes/selecciongenero.php";
                    ?>

                    <button type="submit" class="btn btn-primary">Editar Juego</button>
                </form>
                <br>
                <a href="listar.php"><button>Volver a juegos</button></a>
            </div>
        </div>
</body>

</html>