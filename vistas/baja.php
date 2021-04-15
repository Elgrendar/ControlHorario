<?php
require_once('./inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}
?>
<section class="content">
    <div id="tabs">
        <span class="diana" id="uno"></span>
        <a href="#uno" class="tab-e">Comunicado de Bajas/Ausencias</a>
        <span class="diana" id="dos"></span>
        <a href="#dos" class="tab-e">Entrega Documentación</a>
        <?php if ($_SESSION['administrador'] == 1 || $_SESSION['rrhh'] == 1) : ?>
            <span class="diana" id="tres"></span>
            <a href="#tres" class="tab-e">Gestión de Bajas/Ausencias</a>
        <?php endif; ?>
        <div id="panel1">
            <div>
                <h2>Comunicado de Bajas/Ausencias</h2>
                <form id="bajas" autocomplete="off" class="form--user" method="POST" action="../php/gestionBajas.php" enctype="multipart/form-data">
                    <div class="form__row">
                        <label for="solicitud">Seleccion</label>
                        <select name="solicitud" id="solicitud" class="form__row--input">
                            <option value="0" selected>Elige solicitud...</option>
                            <?php echo mostrarSolicitudes(); ?>
                        </select>
                    </div>
                    <div class="form__row">
                        <label for="fechainicio">Fecha de Inicio:</label>
                    </div>
                    <div class="form__row">
                        <input type="date" name="fechainicio" id="fechainicio" value="<?php echo date("Y-m-d", strtotime(date("Y-m-d") . '+ 5 days')); ?>" min="<?php echo date("Y-m-d", strtotime(date("Y-m-d") . '+ 1 days')); ?>" class="form__row--input" required>
                    </div>
                    <div class="form__row">
                        <label for="fechafin">Fecha de Fin:</label>
                    </div>
                    <div class="form__row">
                        <input type="date" name="fechafin" id="fechafin" value="<?php echo date("Y-m-d", strtotime(date("Y-m-d") . '+ 5 days')); ?>" min="<?php echo date("Y-m-d", strtotime(date("Y-m-d") . '+ 1 days')); ?>" class="form__row--input" required>
                    </div>
                    <div class="form__row">
                        <label for="causa">Causa:</label>
                        <input type="text" name="causa" id="causa" placeholder="Introduce el motivo" maxlength="15" class="form__row--input" required>
                    </div>
                    <div class="form__row">
                        <a href='welcome.php' class="form__row--link">Volver</a>
                        <button type="reset" class="form__row--button">Limpiar</button>
                        <button type="submit" class="form__row--button">Comunicar</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="panel2">
            <div>
                <h2>Entrega de Documentación</h2>
                <form id="documentaciones" autocomplete="off" class="form--user" method="POST" action="../php/entregaDocumentacion.php" enctype="multipart/form-data">
                    <input type="hidden" name="idUsuario" value="<?php echo $_SESSION['idUsuario']; ?>" class="form__row--input">
                    <div class="form__row">
                        <label for="comunicado">Seleccion</label>
                        <select name="comunicado" id="comunicado" class="form__row--input">
                            <option value="0">Elige Comunicado...</option>
                            <?php echo mostrarComunicado(); ?>
                        </select>
                    </div>
                    <div class="form__row">
                        <label for="archivo">Justificante:</label>
                        <input type="file" name="archivo" id="archivo" class="form__row--input" required>
                    </div>
                    <div class="form__row">
                        <a href='welcome.php' class="form__row--link">Volver</a>
                        <button type="reset" class="form__row--button">Limpiar</button>
                        <button type="submit" class="form__row--button">Adjuntar</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($_SESSION['administrador'] == 1 || $_SESSION['rrhh'] == 1) : ?>
            <div id="panel3">
                <div>
                    <h2>Gestión de Bajas/Ausencias</h2>
                    <table id="tablaAusencias">
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>