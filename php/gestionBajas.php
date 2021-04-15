<?php
session_start();
require_once("../inc/configuracion.php");
require_once('../inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}

if (isset($_POST)) {
    $fecha_inicio = filter_input(INPUT_POST, 'fechainicio');
    $fecha_fin = filter_input(INPUT_POST, 'fechafin');
    $solicitud = filter_input(INPUT_POST, 'solicitud');
    $causa = filter_input(INPUT_POST, 'causa');
}


$con = new PDO($conexion, $userBBDD, $passBBDD);
if ($solicitud == 0) {
    $sql = "INSERT INTO ausencias(idUsuario, f_inicio, f_fin, causa) VALUES (:idUsuario, :f_inicio, :f_fin, :causa)";
} else {
    $sql = "INSERT INTO ausencias(idUsuario, idSolicitud, f_inicio, f_fin, causa) VALUES (:idUsuario, :idSolicitud, :f_inicio, :f_fin, :causa)";
}
$stmt = $con->prepare($sql);
$stmt->bindParam('idUsuario', $_SESSION['idUsuario'], PDO::PARAM_INT);
$stmt->bindParam('f_inicio', $fecha_inicio, PDO::PARAM_STR);
$stmt->bindParam('f_fin', $fecha_fin, PDo::PARAM_STR);
$stmt->bindParam('causa', $causa, PDO::PARAM_STR);
if ($solicitud != 0) {
    $stmt->bindParam('idSolicitud', $solicitud, PDO::PARAM_INT);
}
if ($stmt->execute()) {
    $_SESSION['avisos'][] = [
        "tipo" => "success",
        "texto" => '<strong>¡Correcto!</strong> Comunicado realizado correctamente.'
    ];
} else {
    $_SESSION['avisos'][] = [
        "tipo" => "error",
        "texto" => '<strong>¡Error!</strong> Por alguna causa el comunicado no se ha podido dar de alta correctamente.'
    ];
}

header("Location:../welcome.php?pagina=baja#uno");
