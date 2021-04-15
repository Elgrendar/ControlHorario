<?php
require_once('./inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}
?>

<section class="content">
    <a href="welcome.php?pagina=alta usuario" id="agregar_usuario">
        <img src="./assets/img/iconos/add.svg" alt="Añadir usuario" title="Añadir usuario" class="icono confondo" />
    </a>
    <?php mostrarUsuarios();?>
    <table id="tablaUsuarios">
    </table>
</section>