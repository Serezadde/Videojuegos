<?php
require "../../modelo/pedido.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idPedido = $_GET['id'];
    $pedido = new Pedido();
    $pedido->setId($idPedido);
    echo $pedido->realizarPedido();
}

?>