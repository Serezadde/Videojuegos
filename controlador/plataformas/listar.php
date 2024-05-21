<?php
require "../../modelo/plataforma.php";

$plataformas = new Plataforma();
$listadoPlataformas = $plataformas->obtenerListadoPlataformas();

foreach ($listadoPlataformas as $plataforma) {
    echo "<tr>
            <th>" . $plataforma->getId() . "</th>
            <th>" . $plataforma->getNombre() . "</th>
            <th>" . $plataforma->getDescripcion() . "</th>
            <th><img src=../../" . $plataforma->getImagen() . " width='70' height='100'></th>
            <th>" . ($plataforma->getDisponible() ? "Activado" : "Desactivado") . "</th>
            <th>
                <a href=editar.php?id=" . $plataforma->getId()  . "><button>Editar</button></a>
                <a href=../../vista/plataformas/borrar.php?id=" . $plataforma->getId()  . "><button>Borrar</button></a>
            </th>
        </tr>";
}

