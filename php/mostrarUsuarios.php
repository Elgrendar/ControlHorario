<?php
session_start();
require_once("../inc/configuracion.php");
require_once('../inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}


try {
    $con = new PDO($conexion, $userBBDD, $passBBDD);

    $stmt = $con->prepare(mostrarUsuarios());
    $stmt->execute();
    $count = $stmt->rowCount();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resultados_json = json_encode($data);
    echo $resultados_json;

} catch (PDOException $e) {
    echo '{"error":{"text":' . $e->getMessage() . '}}';
}
