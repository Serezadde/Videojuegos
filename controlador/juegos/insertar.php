<?php
require "../../modelo/juego.php";

if (
    isset($_POST['nombre'])
    && isset($_POST['descripcion'])
    && isset($_POST['idsPlataforma'])
    && isset($_POST['portada'])
    && isset($_POST['precio'])
    && isset($_POST['idgenero']) // Asegúrate de recibir el ID del género
    && isset($_POST['disponible'])
) {
    $juego = new Juego();
    echo $juego->insertarJuego(
        $_POST['nombre'],
        $_POST['descripcion'],
        $_POST['idsPlataformas'],
        $_POST['portada'],
        $_POST['precio'],
        $_POST['idgenero'], // Pasamos el ID del género
        $_POST['disponible']
    );
}
?>