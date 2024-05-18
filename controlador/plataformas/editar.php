<?php

require "../../modelo/plataforma.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idPlataforma = $_GET['id'];
    $plataforma = new Plataforma();
    $plataforma->setId($idPlataforma);
    $plataforma = $plataforma->obtenerPlataforma();
    $estado = $plataforma->getDisponible();
}

if (
    isset($_POST['id'])
    && isset($_POST['nombre'])
    && isset($_POST['descripcion'])
    && isset($_POST['imagen'])
    && isset($_POST['disponible'])
) {
    $plataforma = new Plataforma();
    $plataforma->setId($_POST['id']);
    $plataforma->setNombre($_POST['nombre']);
    $plataforma->setDescripcion($_POST['descripcion']);
    $plataforma->setImagen($_POST['imagen']);
    $plataforma->setDisponible($_POST['disponible']);

    echo $plataforma->actualizarPlataforma();
}
