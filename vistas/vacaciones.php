<?php
require_once('./inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}
?>
<section class="content">
    <div id="tabs">
        <span class="diana" id="uno"></span>
        <a href="#uno" class="tab-e">Nueva Solicitud</a>
        <span class="diana" id="dos"></span>
        <a href="#dos" class="tab-e">Solicitudes Realizadas</a>
        <span class="diana" id="tres"></span>
        <a href="#tres" class="tab-e">Solicitudes Aprobadas</a>
        <?php if ($_SESSION['administrador'] == 1 || $_SESSION['rrhh'] == 1 || $_SESSION['jefedepartamento'] == 1) : ?>
            <span class="diana" id="cuatro"></span>
            <a href="#cuatro" class="tab-e">Solicitudes pendientes</a>
        <?php endif; ?>
        <div id="panel1">
            <div>
                <span class="aviso">Recuerda que las fechas son inclusivas.</span>
                <form id="usuario" autocomplete="off" class="form--tickar" method="POST" action="./php/solicitudvacaciones.php">
                    <div class="form__row">
                        <label for="fechainicio">Fecha de Inicio:</label>
                    </div>
                    <div class="form__row">
                        <input type="date" name="fechainicio" id="fechainicio" value="<?php echo date("Y-m-d", strtotime(date("Y-m-d") . '+ 5 days')); ?>" min="<?php echo date("Y-m-d", strtotime(date("Y-m-d") . '+ 1 days')); ?>" class="form__row--input">
                    </div>
                    <div class="form__row">
                        <label for="fechafin">Fecha de Fin:</label>
                    </div>
                    <div class="form__row">
                        <input type="date" name="fechafin" id="fechafin" value="<?php echo date("Y-m-d", strtotime(date("Y-m-d") . '+ 5 days')); ?>" min="<?php echo date("Y-m-d", strtotime(date("Y-m-d") . '+ 1 days')); ?>" class="form__row--input">
                    </div>
                    <div class="form__row">
                        <label for="tipo">Tipo:</label>
                    </div>
                    <div class="form__row">
                        <select name="tipo" id="tipo" class="form__row--input">
                            <option value="Vacaciones" selected>Vacaciones</option>
                            <option value="Compensacion">Compensacion</option>
                            <option value="Asuntos Propios">Asuntos Propios</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                    <div class="form__row">
                        <a href='welcome.php' class="form__row--link">Volver</a>
                        <button type="reset" class="form__row--button">Limpiar</button>
                        <button type="submit" class="form__row--button">Solicitar</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="panel2">
            <div id="realizadas">
                No existen solicitudes realizadas.
            </div>
        </div>

        <div id="panel3">
            <div id="aprobadas">
                No existen solicitudes aprobadas
            </div>
        </div>
        <?php if ($_SESSION['administrador'] == 1 || $_SESSION['rrhh'] == 1 || $_SESSION['jefedepartamento'] == 1) : ?>
            <div id="panel4">
                <div id="pendientes">
                    No existen solicitudes solicitudes pendientes
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script  type="text/javascript">
        mostrarSolicitudesRealizadas();
        mostrarSolicitudesAprobadas();
        <?php if ($_SESSION['administrador'] == 1 || $_SESSION['rrhh'] == 1 || $_SESSION['jefedepartamento'] == 1) : ?>
        mostrarSolicitudesPendientes();
        <?php endif; ?>
    </script>
</section>