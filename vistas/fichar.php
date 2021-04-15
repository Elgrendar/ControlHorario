<?php
require_once('./inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}
?>
<section class="content">
    <form id="usuario" autocomplete="off" class="form--tickar" method="POST" action="./php/fichar.php">
        <input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $_SESSION['idUsuario']; ?>">
        <div class="form__row">
            <input type="text" name="nombre" id="nombre" value="<?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2']; ?>" class="form__row--input" disabled>
            <select name="tipo" id="tipo" class="form__row--input">
                <option value="entrada" <?php echo trabajando($_SESSION['idUsuario']) ? "" : "selected"; ?>>Entrada</option>
                <option value="salida" <?php echo trabajando($_SESSION['idUsuario']) ? "selected" : ""; ?>>Salida</option>
            </select>
            <input type="datetime-local" value="<?php echo date('Y-m-d') . "T" . date('H:i:s'); ?>" class="form__row--input" disabled>
        </div>
        <input type="checkbox" name="extraordinario" id="extraordinario" class="form__row--input"> Fichaje extraordinario
        <div class="form__row observaciones hidden" id="opciones">
            <label for="observaciones">Motivos</label>
            <br />
            <input type="radio" name="observaciones" value="medicos" class="form__row--input"> Médicos
            <input type="radio" name="observaciones" value="oficiales" class="form__row--input"> Oficiales
            <input type="radio" name="observaciones" value="laborales" class="form__row--input"> Laborales
            <input type="radio" name="observaciones" value="personales" class="form__row--input"> Personales
            <input type="radio" name="observaciones" value="otros" class="form__row--input"> Otros
        </div>
        <div class="form__row">
            <a href='welcome.php' class="form__row--link">Volver</a>
            <button type="reset" class="form__row--button">Limpiar</button>
            <button type="submit" class="form__row--button">Fichar</button>
        </div>
    </form>
</section>
<script type="text/javascript">
    extraordinario = document.getElementById("extraordinario");
    extraordinario.addEventListener('click', function() {
        opciones = document.getElementById("opciones");
        if (extraordinario.checked) {
            console.log(extraordinario + "1");
            opciones.classList.remove("hidden");
        } else {
            console.log(extraordinario + "2");
            opciones.classList.add("hidden");
        }
    });
</script>