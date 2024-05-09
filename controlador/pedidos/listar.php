<?php
require "../../modelo/pedido.php";

$pedido = new Pedido();
$listadoPedidos = $pedido->obtenerListadoPedidos();

foreach ($listadoPedidos as $pedido) {
    echo "<tr>
                                <th>" . $pedido->getId() . "</th>
                                <th>" . $pedido->getDescripcion() . "</th>
                                <th>" . $pedido->getTotalunidades() . "</th>
                                <th>" . $pedido->getTotalprecio() . "</th>
                                <th>" . $pedido->getEstado(). "</th>
                                <th>";
    switch ($pedido->getEstado()) {
        case 'Realizado':
            echo "<font color='red'>Pedido Realizado</font>";
            break;
        case 'Cancelado':
            echo "<a href=listado_pedidos_juegos.php?id=" . $pedido->getId() . "><button>Lista Juegos</button></a>
                                        <a href=editar.php?id=" . $pedido->getId() . "><button>Editar</button></a>
                                        <a href=borrar.php?id=" . $pedido->getId() . "><button>Borrar</button></a>";
            break;
        case 'Procesando':
        case 'Pendiente':
            echo "<a href=listado_pedidos_juegos.php?id=" . $pedido->getId() . "><button>Lista Juegos</button></a>
                <a href=editar.php?id=" . $pedido->getId() . "><button>Editar</button></a>
                <a href=borrar.php?id=" . $pedido->getId() . "><button>Borrar</button></a>
                <a href=realizar_pedido.php?id=" . $pedido->getId() . "><button>Realizar Pedido</button></a>";
            break;
        default:
            break;
    }
    echo "</th>
        </tr>";
}
?>