<!DOCTYPE html>
<html>
<head>
    <title>Editar Género</title>
</head>
<body>
    <h2>Editar Género</h2>
    <?php
    require_once "../../controlador/genero/EditarGeneroController.php";

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
        $idGenero = $_GET["id"];
        $editarGeneroController = new EditarGeneroController();
        $genero = $editarGeneroController->obtenerGenero($idGenero);

        if ($genero) {
    ?>
<form action="" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($genero->getId()); ?>">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($genero->getNombre()); ?>" required><br>
    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($genero->getDescripcion()); ?></textarea><br>
    <button type="submit" name="editar">Guardar Cambios</button>
</form>
    <?php
        } else {
            echo "No se encontró el género.";
        }
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar"])) {
        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $descripcion = $_POST["descripcion"];

        $editarGeneroController = new EditarGeneroController();
        $editarGeneroController->actualizarGenero($id, $nombre, $descripcion);

        echo "<p>Género actualizado correctamente.</p>";
        // Opcionalmente, puedes redireccionar automáticamente después de un breve retraso
        // header("refresh:3;url=listarGeneros.php");
    } else {
        echo "ID de género no proporcionado.";
    }
    ?>
</body>
</html>

