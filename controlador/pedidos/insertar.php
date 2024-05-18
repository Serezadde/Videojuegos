<?php
 require "../../modelo/pedido.php";

 if (
     isset($_POST['descripcion'])
     && isset($_POST['idsJuegos'])
     && isset($_POST['estado'])
 ) {
     $pedido = new Pedido();
     $pedido->setDescripcion($_POST['descripcion']);
     $pedido->setEstado($_POST['estado']);
     echo $pedido->insertarPedido($_POST['idsJuegos']);
 }


?>