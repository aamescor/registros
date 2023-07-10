

<div class="form-container">
    <div class="ingresar-datos">
        <h2>Ingresar Datos</h2>
    </div>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-row">
            <label for="cedula">Cédula:</label>
            <input type="text" name="cedula" id="cedula" pattern="[0-9]+" required value="<?php echo $cedula; ?>">
        </div>
        <div class="form-row">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required value="<?php echo $nombre; ?>">
        </div>
        <div class="form-row">
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" required value="<?php echo $apellidos; ?>">
        </div>
        <div class="form-row">
            <label for="lider">Líder:</label>
            <input type="text" name="lider" id="lider" required value="<?php echo $lider; ?>">
        </div>
        <div class="form-row">
            <label for="barrio">Barrio:</label>
            <input type="text" name="barrio" id="barrio" required value="<?php echo $barrio; ?>">
        </div>
        <div class="form-row">
            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" id="direccion" required value="<?php echo $direccion; ?>">
        </div>
        <div class="form-row">
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" id="telefono" pattern="[0-9]+" value="<?php echo $telefono; ?>">
        </div>
        <div class="form-row">
            <label for="puesto">Puesto:</label>
            <input type="text" name="puesto" id="puesto" required value="<?php echo $puesto; ?>">
        </div>
        <div class="form-row">
            <label for="mesa">Mesa:</label>
            <input type="text" name="mesa" id="mesa" pattern="[0-9]+" required value="<?php echo $mesa; ?>">
        </div>
        <div class="form-row">
            <input type="submit" value="Agregar">
        </div>
        
    </form>

</div>

</div>