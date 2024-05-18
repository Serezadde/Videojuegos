<?php

require_once "../../modelo/genero.php";

class InsertarGeneroController
{
    public function insertarGenero($nombre, $descripcion)
    {
        $genero = new Genero();
        $genero->setNombre($nombre);
        $genero->setDescripcion($descripcion);
        $genero->insertarGenero();
    }
}


