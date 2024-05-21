<?php
                    require "../../modelo/juego.php";

                    $juegos = new Juego();

                    if(isset($_GET['filtro']) && isset($_GET['orden'])){
                        $listadoJuegos = $juegos->obtenerListadoJuegos($_GET['filtro'], $_GET['orden']);
                    }else{
                        $listadoJuegos = $juegos->obtenerListadoJuegos();
                    }

                    foreach ($listadoJuegos as $juego) {
                        echo "<tr>
                                <th>" . $juego->getId() . "</th>
                                <th>" . $juego->getNombre(). "</th>
                                <th>" . $juego->getDescripcion() . "</th>
                                <th>" . $juego->getPlataformas() . "</th>
                                <th><img src=../../" . $juego->getPortada() . " width='70' height='100'></th>
                                <th>" . $juego->getPrecio() . "</th>
                                <th>" . ($juego->getDisponible() ? "Activado" : "Desactivado") . "</th>
                                <th>
                                    <a href=editar.php?id=" . $juego->getId() . "><button>Editar</button></a>
                                    <a href=listar.php?id=" . $juego->getId() . "><button>Borrar</button></a>
                                </th>
                            </tr>";
                    }
                    ?>