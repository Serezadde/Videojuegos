<div class="form-group">
    <label>Estado</label>
    <br>
    <select class="custom-select" name="estado" required>
        <option>Elegir Estado...</option>
        <option value="Procesando" <?php
                                    if (isset($pedido) && !empty($pedido->getEstado()) && $pedido->getEstado() == 'Procesando') {
                                        echo " selected='selected' ";
                                    }
                                    ?>>Procesando</option>
        <option value="Pendiente" <?php
                                    if (isset($pedido) && !empty($pedido->getEstado()) && $pedido->getEstado()== 'Pendiente') {
                                        echo " selected='selected' ";
                                    }
                                    ?>>Pendiente</option>
        <option value="Cancelado" <?php
                                    if (isset($pedido) && !empty($pedido->getEstado()) && $pedido->getEstado() == 'Cancelado') {
                                        echo " selected='selected' ";
                                    }
                                    ?>>Cancelado</option>
    </select>
</div>