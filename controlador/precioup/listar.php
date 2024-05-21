<?php
                    require_once "../../modelo/juego.php";

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
                               
                            </tr>";
                    }
                    ?>