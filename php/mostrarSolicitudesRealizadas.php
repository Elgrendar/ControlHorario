<?php
session_start();
require_once("../inc/configuracion.php");
require_once('../inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}


try {
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $sql= "SELECT * FROM solicitudes WHERE idusuario=:idusuario AND (aprobado_departamento=0 OR aprobado_rrhh=0) AND NOT(aprobado_departamento=-1 OR aprobado_rrhh=-1)";
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
