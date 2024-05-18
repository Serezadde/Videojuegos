<div class="form-group">
    <label>Genero</label>
    <br>
    <select class="custom-select" name="genero" required>
        <option>Elegir Genero...</option>
        <option value="1" <?php
                            if (isset($genero) ) {
                                echo " selected='selected' ";
                            }
                            ?>>Acción</option>
        <option value="2" <?php
                            if (isset($genero) ) {
                                echo " selected='selected' ";
                            }
                            ?>>Ciencia Ficción</option>
        <option value="3" <?php
                            if (isset($genero) ) {
                                echo " selected='selected' ";
                            }
                            ?>>Historico</option>
        <option value="4" <?php
                            if (isset($genero) ) {
                                echo " selected='selected' ";
                            }
                            ?>>Aventura</option>
    </select>
</div>