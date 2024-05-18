<?php

require_once "../../modelo/genero.php";

class BorrarGeneroController
{
    public function eliminarGenero($id)
    {
        try {
            $genero = new Genero();
            $genero->setId($id);
            $genero->eliminarGenero();
            header("Location: ../../vista/genero/listarGeneros.php");
            exit(); // Asegura que el script se detenga después de redirigir
        } catch (Exception $ex) {
            echo "Ocurrió un error: " . $ex->getMessage();
        }
    }
}

// Verificar si se ha enviado un ID para eliminar el género
if (isset($_GET["id"])) {
    $idGenero = $_GET["id"];
    
    // Crear una instancia del controlador y llamar a la función para eliminar el género
    $borrarGeneroController = new BorrarGeneroController();
    $borrarGeneroController->eliminarGenero($idGenero);
} else {
    echo "ID de género no proporcionado.";
}

?>

