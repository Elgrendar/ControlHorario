<?php
session_start();
require_once("../inc/configuracion.php");
require_once('../inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}
try {
    //establecemos la conexion con la bbdd
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    //Comprobamos los mensajes del usuario con la sesión iniciada y que no ha leido
    $sql = "SELECT count(*) as total FROM mensajes WHERE idDestinatario=:idDestinatario AND leido=0;";
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idDestinatario', $_SESSION['idUsuario'], PDO::PARAM_INT);
    $stmt->execute();
    $mensajes = $stmt->fetch(PDO::FETCH_ASSOC);
    //Devolvemos el total de los mensajes
    echo $mensajes['total'];
} catch (PDOException $e) {
    echo '{"error":{"text":' . $e->getMessage() . '}}';
}
