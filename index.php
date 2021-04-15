<?php
include("inc/funciones.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control Horario</title>
    <?php
    getEstilos();
    ?>
</head>

<body>
    <img class="logo" src="./assets/img/sistema/logo.png" alt="Logo control horario" title="Control Horario"/>
    <h1 class="title">CONTROL HORARIO</h1>
    <form class="form" name="loginForm" id="loginForm" autocomplete="off">
        <div class="form__row">
            <label for="email" class="form__row--label">E-MAIL</label>
            <input type="text" class="form__row--input" id="email" name="email" placeholder="Introduce tu usuario.">
        </div>
        <div class="form__row">
            <label for="password" class="form__row--label">CONTRASEÑA</label>
            <INPUT type="password" class="form__row--input" id="password" name="password" placeholder="Introduce tu contraseña.">
        </div>
        <div class="form__row">
            <button type="submit" class="form__row--button" name="loginButton" id="loginButton">Entrar</button>
            <button type="reset" class="form__row--button">Limpiar</button>
        </div>
        <div id="resultado"></div>
    </form>
    <div id="clock" class="clock"></div>
    <?php
    getFooter();
    ?>
</body>

</html>