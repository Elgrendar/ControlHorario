<?php
session_start();
require_once("../inc/configuracion.php");
require_once("../inc/funciones.php");
if (comprobarsesion()) {
    header("location:../index.php");
}

if (!empty($_POST)) {
    $informe= filter_input(INPUT_POST,'informe');
    $mes= filter_input(INPUT_POST,'mes');
    $dia= filter_input(INPUT_POST,'dia');
    $email= filter_input(INPUT_POST,'email');
    $pdf= filter_input(INPUT_POST,'pdf');
}

if($informe=="diario"){
    header("location:./informes/diario.php?fecha=".$dia."&email=".$email."&pdf=".$pdf);
}elseif($informe=="mensual"){
    header("location:./informes/mensual.php?fecha=".$mes."&email=".$email."&pdf=".$pdf);
}