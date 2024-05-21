<?php
  require "../../modelo/juego.php";

  if (isset($_GET['id']) && !empty($_GET['id'])) {
      $idJuego = $_GET['id'];
      $juego = new Juego();
      $juego->setId($idJuego);
      $juego = $juego->obtenerJuego();
      $estado = $juego->getDisponible();
      $idsPlataformasSeleccionadas = $juego->obtenerPlataformas();
      $idGenero = $juego->getIdgenero();
  }

  if (
      isset($_POST['id'])
      && isset($_POST['nombre'])
      && isset($_POST['descripcion'])
      && isset($_POST['idsPlataformas'])
      && isset($_POST['portada'])
      && isset($_POST['precio'])
      && isset($_POST['disponible'])
      && isset($_POST['genero'])
  ) {
      $juego = new Juego();

      $juego->setId($_POST['id']);
      $juego->setNombre($_POST['nombre']);
      $juego->setDescripcion($_POST['descripcion']);
      $juego->setPortada($_POST['portada']);
      $juego->setPrecio($_POST['precio']);
      $juego->setDisponible($_POST['disponible']);
      $juego->setIdGenero($_POST['genero']);
      
      echo $juego->actualizarJuego($_POST['id'],$_POST['nombre'],$_POST['descripcion'],$_POST['portada'],$_POST['precio'],$_POST['disponible'],$_POST['genero'],$_POST['idsPlataformas']);
  }


?>