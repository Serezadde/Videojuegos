<?php
    require "../../modelo/juego.php";

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $idJuego = $_GET['id'];
        $juego = new Juego();
        $juego->setId($idJuego);
        echo $juego->eliminarJuego();
    }
    ?>