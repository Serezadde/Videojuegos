<?php
require_once "../../modelo/genero.php";

  if (isset($_GET['id']) && !empty($_GET['id'])) {
      $idgenero = $_GET['id'];
      $genero = new Genero();
   

  }

  if (
      isset($_POST['id'])
      && isset($_POST['nombre'])
      && isset($_POST['descripcion'])
      
  ) {
      $genero = new Genero();

      $genero->setId($_POST['id']);
      $genero->setNombre($_POST['nombre']);
      $genero->setDescripcion($_POST['descripcion']);
      
      echo $genero->actualizarGenero();
  }


?>
