<?php
require_once "../../modelo/genero.php";

class EditarGeneroController
{
    public function obtenerGenero($id)
    {
        $genero = new Genero();
        $genero->setId($id);
        return $genero->obtenerGenero();
    }

    public function actualizarGenero($id, $nombre, $descripcion)
    {
        $genero = new Genero();
        $genero->setId($id);
        $genero->setNombre($nombre);
        $genero->setDescripcion($descripcion);
        $genero->actualizarGenero();
    }
}
