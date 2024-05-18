<!DOCTYPE html>
<html>
<head>
    <title>Insertar Género</title>
</head>
<body>
    <h2>Insertar Género</h2>
    <form action="" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea><br>
        <button type="submit" name="insertar">Insertar</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insertar"])) {
        require_once "../../controlador/genero/InsertarGeneroController.php";
        
        $nombre = $_POST["nombre"];
        $descripcion = $_POST["descripcion"];

        $insertarGeneroController = new InsertarGeneroController();
        $insertarGeneroController->insertarGenero($nombre, $descripcion);

        // Redireccionar a la lista de géneros
        header("Location: listarGeneros.php");
    }
    ?>
</body>
</html>
