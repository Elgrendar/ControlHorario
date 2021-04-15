<?php
require_once('./inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}
?>
<section class="content">
    <form id="usuario" autocomplete="off" class="form--user" method="POST" action="./php/alta-usuario.php" enctype="multipart/form-data">
        <div class="form__row">
            <label for="nombre" class="form__row--label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form__row--input" placeholder="Nombre del usuario" required>
            <label for="apellido1" class="form__row--label">Primer Apellido</label>
            <input type="text" name="apellido1" id="apellido1" class="form__row--input" placeholder="1º Apellido" required>
            <label for="apellido2" class="form__row--label">Segundo apellido</label>
            <input type="text" name="apellido2" id="apellido2" class="form__row--input" placeholder="2º Apellido">
        </div>
        <div class="form__row">
            <label for="email" class="form__row--label">Email</label>
            <input type="email" name="email" id="email" class="form__row--input" placeholder="Email del usuario" required>
            <label for="sexo" class="form__row--label">Sexo</label>
            <select name="sexo" id="sexo">
                <option value="Sin definir" selected>Elija opción</option>
                <option value="hombre">Hombre</option>
                <option value="mujer">Mujer</option>
            </select>
            <label for="dni" class="form__row--label">DNI</label>
            <input type="text" name="dni" id="dni" maxlength="9" class="form__row--input" placeholder="DNI con letra, sin guiones">
        </div>
        <div class="form__row">
            <label for="falta" class="form__row--label">Fecha de Alta</label>
            <input type="date" name="falta" id="falta" value="<?php echo date('Y-m-d'); ?>" class="form__row--input" required>
            <label for="fbaja" class="form__row--label">Fecha de Baja</label>
            <input type="date" name="fbaja" id="fbaja" class="form__row--input" disabled>
            <label for="fnacimiento" class="form__row--label">Fecha de Nacimiento</label>
            <input type="date" name="fnacimiento" id="fnacimiento" class="form__row--input">
        </div>
        <div class="form__row">
            <label for="pass" class="form__row--label">Contraseña</label>
            <input type="password" name="pass" id="pass" class="form__row--input" required>
            <label for="pass2" class="form__row--label">Confirmar contraseña</label>
            <input type="password" name="pass2" id="pass2" class="form__row--input" required>
            <label for="tarjeta" class="form__row--label">Nº Tarjeta</label>
            <input type="text" name="tarjeta" id="tarjeta" maxlength="10" class="form__row--input">
        </div>
        <div class="form__row">
            <div>
                <label for="archivo" class="form__row--label">Foto</label><br />
                <input type="file" name="archivo" id="archivo" accept="image/png, .jpeg, .jpg, image/gif">
            </div>
            <div class="form__imagen">
                <img class="form__imagen--user" src="./assets/img/usuarios/sinimagen.png" alt="Usuario" title="Imagen de usuario.">
            </div>
        </div>
        <div class="form__row">
            <label for="responsables" class="form__row--label">Responsable</label>
            <select name="responsables" id="responsables">
                <option value="" selected>Elija opción</option>
                <script>
                    rellenarResponsables()
                </script>
            </select>
        </div>
        <div class="form__row">
            <?php if ($_SESSION['administrador'] == 1) : ?>
                <input type="checkbox" id="administrador" name="administrador">
                <label for="administrador" class="form__row--label">Administrador</label>
            <?php endif; ?>
            <input type="checkbox" id="rrhh" name="rrhh">
            <label for="rrhh" class="form__row--label">RRHH</label>

            <input type="checkbox" id="jefedepartamento" name="jefedepartamento">
            <label for="jefedepartamento" class="form__row--label">J. Departamento</label>

            <input type="checkbox" id="trabajador" name="trabajador" checked>
            <label for="trabajador" class="form__row--label">Activo</label>
        </div>
        <div class="form__row">
            <a href='welcome.php?pagina=usuarios' class="form__row--link">Volver</a>
            <button type="reset" class="form__row--button">Limpiar</button>
            <button type="submit" class="form__row--button">Aceptar</button>
        </div>
    </form>
</section>