<?php

require_once "BorrarGeneroController.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $borrarGeneroController = new BorrarGeneroController();
    $borrarGeneroController->borrarGenero($id);

    // Redireccionar a la página de confirmación
    header("Location: confirmarBorrarGenero.php");
} else {
    echo "ID de género no proporcionado.";
}

?>
