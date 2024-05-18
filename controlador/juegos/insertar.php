<?php
     require "../../modelo/juego.php";

     if (
         isset($_POST['nombre'])
         && isset($_POST['descripcion'])
         && isset($_POST['idsPlataformas'])
         && isset($_POST['portada'])
         && isset($_POST['precio'])
         && isset($_POST['disponible'])
         && isset($_POST['genero'])
     ) {
         $juego = new Juego();
         echo $juego->insertarJuego(
             $_POST['nombre'],
             $_POST['descripcion'],
             $_POST['idsPlataformas'],
             $_POST['portada'],
             $_POST['precio'],
             $_POST['disponible'],
             $_POST['genero']
         );
     }


?>