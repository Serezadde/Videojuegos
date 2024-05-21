<?php
require_once "../../modelo/plataforma.php";

if (isset($_POST['nombre'])) {
    $plataforma = new Plataforma();
    $plataforma->setNombre($_POST['nombre']);
    
    $resultado = $plataforma->aumentarPrecioJuegos();
    
    echo $resultado;
}
