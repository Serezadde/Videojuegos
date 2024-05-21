<div class="form-group">
    <label>Plataformas</label>
    <br>
    <select class="form-select" name="idPlataformas" multiple aria-label="">
        <?php

        include_once "../../modelo/plataforma.php";
        
        $plataforma = new Plataforma();
        $listadoPlataformas=  $plataforma->obtenerListadoPlataformas();

        foreach ($listadoPlataformas as $plataforma) {
            if ($plataforma->getDisponible() == true) {
                echo "<option value=" . $plataforma->getId();
                if (isset($idsPlataformasSeleccionadas)) {
                    foreach ($idsPlataformasSeleccionadas as $idPlataforma) {
                        if ($plataforma->getId() == $idPlataforma['id_plataforma']) {
                            echo " selected='selected' ";
                        }
                    }
                }
                echo ">" . $plataforma->getNombre() . "</option>";
            }
        }

        ?>
    </select>
</div>