<!DOCTYPE html>
<html>
<head>
    <title>Listado de Géneros</title>
</head>
<body>
    <h2>Listado de Géneros</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Incluir el controlador para listar los géneros
            require_once "../../controlador/genero/ListarGenerosController.php";

            // Crear una instancia del controlador
            $listarGenerosController = new ListarGenerosController();

            // Obtener el listado de géneros
            $listaGeneros = $listarGenerosController->listarGeneros();

            // Iterar sobre la lista de géneros y mostrar cada uno en una tabla
            foreach ($listaGeneros as $genero) {
                echo "<tr>";
                echo "<td>" . $genero->getId() . "</td>";
                echo "<td>" . $genero->getNombre() . "</td>";
                echo "<td>" . $genero->getDescripcion() . "</td>";
                echo "<td>";
                echo "<a href='../../vista/genero/formularioEditarGenero.php?id=" . $genero->getId() . "'>Editar</a> | ";
                echo "<a href='../../vista/genero/confirmarBorrarGenero.php?id={$genero->getId()}' class='btn btn-danger'>Borrar</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a href="formularioInsertarGenero.php">Agregar Género</a>
    <br>
    <a href="../../index.php">Menu principal</a>
</body>
</html>
