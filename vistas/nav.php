<?php
require_once('./inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}
mostrarAvisos();
?>
<nav>
    <div class="nav__left"><?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido1']; ?></div>
    <div class="nav__center">
        <ul class="main-menu" id="main-menu">
            <?php if ($_SESSION['administrador'] == 1 || $_SESSION['rrhh'] == 1) : ?>
                <li class="main-menu__item">
                    <a class="main-menu__link" href="welcome.php?pagina=usuarios">Usuarios</a>
                </li>
                <li class="main-menu__item">
                    <a class="main-menu__link" href="welcome.php?pagina=informes">Informes</a>
                </li>
            <?php endif; ?>

            <li class="main-menu__item">
                <a class="main-menu__link" href="welcome.php?pagina=mensajes#uno">Mensajes</a>
                <ul class="submenu">
                    <li class="submenu__item">
                        <a class="submenu__link" href="welcome.php?pagina=mensajes#uno">Bandeja de Entrada</a>
                    </li>
                    <li class="submenu__item">
                        <a class="submenu__link" href="welcome.php?pagina=mensajes#tres">Redactar</a>
                    </li>
                </ul>
            </li>
            <li class="main-menu__item">
                <a class="main-menu__link" href="welcome.php?pagina=asistencia">Asistencia</a>
                <ul class="submenu">
                    <li class="submenu__item">
                        <a class="submenu__link" href="welcome.php?pagina=fichar">Fichar</a>
                    </li>
                    <li class="submenu__item">
                        <a class="submenu__link" href="welcome.php?pagina=vacaciones#uno">Solicitudes</a>
                    </li>
                    <li class="submenu__item">
                        <a class="submenu__link" href="welcome.php?pagina=baja#uno">Bajas/Ausencias</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="nav__rigth">
        <a class="nav__link" href="welcome.php?pagina=perfil" title="Perfil"><img src="./assets/img/usuarios/<?php echo $_SESSION['foto']; ?>" class="nav__link--image" alt="Perfil" title="Perfil" /></a>
        <a class="nav__link" href="welcome.php?pagina=mensajes#uno"><img src="./assets/img/iconos/message.svg" class="nav__link--image" alt="Mensajes" title="Mensajes" /></i><span class="messages__number" id="messages__number"></span></a>
        <a class="nav__link" href="./php/logout.php" title="Salir"><img src="./assets/img/iconos/logout.svg" class="nav__link--image" alt="Salir" title="Salir" /></a>
    </div>
    <div class="menu-movil">
        <a id="menu-boton" href="#!">
            <span></span>
        </a>
    </div>
</nav>