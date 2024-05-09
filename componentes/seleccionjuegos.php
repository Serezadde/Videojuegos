<div class="form-group">
    <label>Juegos</label>
    <br>
    <select class="form-select" name="idsJuegos[]" multiple aria-label="">
        <?php

        $juegos = new Juego();
        $listadoJuegos = $juegos->obtenerListadoJuegos();

        foreach ($listadoJuegos as $juego) {
            if ($juego->getDisponible() == true) {
                echo "<option value=" . $juego->getId();
                if (isset($idsJuegosSeleccionados)) {
                    foreach ($idsJuegosSeleccionados as $idJuego) {
                        if ($juego->getId() == $idJuego['id']) {
                            echo " selected='selected' ";
                        }
                    }
                }
                echo ">" . $juego->getNombre() . "</option>";
            }
        }

        ?>
    </select>
</div>