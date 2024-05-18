<?php

require "../../modelo/genero.php";

class ListarGenerosController
{
    public function listarGeneros()
    {
        $genero = new Genero();
        return $genero->obtenerListadoGeneros();
    }
}


