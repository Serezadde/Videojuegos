<?php

require_once "../../modelo/pedido.php";
require_once "../../modelo/juego.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $idPedido = $_GET['id'];

    $pedidos = new Pedido();
    $pedidos->setId($idPedido);

    $listadoJuegos = $pedidos->obtenerListadoJuegosPedido();
    $pedido = $pedidos->obtenerPedido();

    foreach ($listadoJuegos as $juego) {
        echo "<tr>
            <th>" . $juego->getId() . "</th>
            <th>" . $juego->getNombre() . "</th>
            <th>" . $juego->getDescripcion() . "</th>
            <th>" . $juego->getPlataforma() . "</th>
            <th><img src=../../" . $juego->getPortada() . " width='70' height='100'></th>
            <th>" . $juego->getPrecio() . "</th>
        </tr>";
    }
}