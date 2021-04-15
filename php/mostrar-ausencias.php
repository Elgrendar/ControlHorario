<?php
session_start();
require_once("../inc/configuracion.php");
require_once('../inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}


try {
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $sql= "SELECT DISTINCT documentos.idDocumento, usuarios.nombre as nombre, usuarios.apellido1, usuarios.apellido2, solicitudes.idSolicitud, ausencias.idAusencia,ausencias.f_inicio,ausencias.f_fin,documentos.nombre as fichero FROM solicitudes INNER JOIN usuarios ON solicitudes.idusuario=usuarios.idUsuario INNER JOIN ausencias ON (solicitudes.idSolicitud = ausencias.idSolicitud OR ausencias.idSolicitud IS NULL)  INNER JOIN documentos ON documentos.idAusencia= ausencias.idAusencia;";
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idusuario',$_SESSION['idUsuario'],PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resultados_json = json_encode($data);
    echo $resultados_json;

} catch (PDOException $e) {
    echo '{"error":{"text":' . $e->getMessage() . '}}';
}