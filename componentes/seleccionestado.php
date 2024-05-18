<div class="form-group">
    <label>Estado</label>
    <br>
    <select class="custom-select" name="disponible" required>
        <option>Elegir Estado...</option>
        <option value="1" <?php
                            if (isset($estado) && $estado == true) {
                                echo " selected='selected' ";
                            }
                            ?>>Activado</option>
        <option value="0" <?php
                            if (isset($estado) && $estado == false) {
                                echo " selected='selected' ";
                            }
                            ?>>Desactivado</option>
    </select>
</div>