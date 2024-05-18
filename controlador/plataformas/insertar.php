<?php

require "../../modelo/plataforma.php";

if (
    isset($_POST['nombre'])
    && isset($_POST['descripcion'])
    && isset($_POST['imagen'])
    && isset($_POST['disponible'])
) {
    $plataforma = new Plataforma();
    $plataforma->setNombre($_POST['nombre']);
    $plataforma->setDescripcion($_POST['descripcion']);
    $plataforma->setImagen($_POST['imagen']);
    $plataforma->setDisponible($_POST['disponible']);
    
    echo $plataforma->insertarPlataforma();
}