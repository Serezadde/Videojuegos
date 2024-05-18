<?php

require "../../modelo/juego.php";
require "../../modelo/pedido.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idPedido = $_GET['id'];
    $pedidos = new Pedido();
    $pedidos->setId($idPedido);
    $pedido = $pedidos->obtenerPedido();
    $idsJuegosSeleccionados = $pedidos->obtenerListadoJuegosIds();
}

if (
    isset($_POST['id'])
    && isset($_POST['descripcion'])
    && isset($_POST['idsJuegos'])
    && isset($_POST['estado'])
) {
    $pedido = new Pedido();
    
    $pedido->setId($_POST['id']);
    $pedido->setDescripcion($_POST['descripcion']);
    $pedido->setEstado($_POST['estado']);

    echo $pedido->actualizarPedido($_POST['idsJuegos']);
}