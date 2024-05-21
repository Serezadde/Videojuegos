<?php

require "../../modelo/plataforma.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idPlataforma = $_GET['id'];
    $plataforma = new Plataforma();
    $plataforma->setId($idPlataforma);
    $plataforma->eliminarPlataforma();
}
