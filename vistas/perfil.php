<?php
require_once('./inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}
?>

<section class="content">
    <h3 class="nombre"><?php echo $_SESSION['nombre']." ".$_SESSION['apellido1']." ".$_SESSION['apellido2'];?></h3>
    <h4 clas="nombre"><?php echo $_SESSION['dni']?></h4>
    <h4 clas="nombre"><?php echo $_SESSION['email']?></h4>
    <div class="perfil">
        <div>
            <div class="imagen">
                <img src="./assets/img/usuarios/<?php echo $_SESSION['foto'];?>" alt="Imagen usuario" title="<?php echo $_SESSION['nombre']." ".$_SESSION['apellido1']." ".$_SESSION['apellido2'];?>" />
            </div>
        </div>
        <div>
            <div class="datosempresa">
                
                <?php
                $date = new DateTime($_SESSION['f_alta']);
                echo "<p>Alta el ".$date->format('d-m-Y')." tiene una antiguedad de ".antiguedad($_SESSION['f_alta'])."</p>";
                if ($_SESSION['tarjeta'] != 0) {
                    echo '<p>Tarjeta identificativa número ' . $_SESSION['tarjeta'] . '</p>';
                } else {
                    echo '<p>No tiene tarjeta asociada.</p>';
                }
                if ($_SESSION['trabajando'] == 1) {
                    echo '<p>Estado Actual: Trabajando</p>';
                } else {
                    echo '<p>Estado Actual: Ausente</p>';
                }

                if ($_SESSION['idresponsable'] != null) {
                    echo '<p>Encargado: '.obtenerNombre($_SESSION['idresponsable']).'</p>';
                } else {
                    echo '<p>Este trabajador no tiene responsable asignado</p>';
                }
                ?>
            </div>
        </div>
    </div>
</section>