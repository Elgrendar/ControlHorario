<?php
session_start();
require_once("../inc/configuracion.php");
require_once('../inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}

if (isset($_POST)) {
    $comunicado = filter_input(INPUT_POST, 'comunicado');
}

$archivo = subirArchivo("../assets/archivos/justificantes/");

if ($archivo == "ERROR") {
    $_SESSION['avisos'][] = [
        "tipo" => "error",
        "texto" => '<strong>¡Error!</strong> Por alguna causa el justificante no se ha podido subir correctamente.'
    ];
    header("Location:../welcome.php?pagina=baja#dos");
} else {
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $sql = "INSERT INTO documentos(idAusencia, nombre) VALUES (:idAusencia, :nombre)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idAusencia', $comunicado, PDO::PARAM_INT);
    $stmt->bindParam('nombre', $archivo, PDO::PARAM_STR);
    if ($stmt->execute()) {
        $_SESSION['avisos'][] = [
            "tipo" => "success",
            "texto" => '<strong>¡Correcto!</strong> Justificante subido correctamente.'
        ];
        $idOrigen=$_SESSION['idUsuario'];
        $Destinatarios = idRRHH();
        $asunto = "Entrega de justificante";
        $mensaje = "El trabajador ".obtenerNombre($_SESSION['idUsuario'])." ha entregado un justificante.";
        enviarmensaje($idOrigen, $Destinatarios, $asunto, $mensaje);
    } else {
        $_SESSION['avisos'][] = [
            "tipo" => "error",
            "texto" => '<strong>¡Error!</strong> El justificante no se ha podido subir correctamente.'
        ];
    }

    header("Location:../welcome.php?pagina=baja#dos");
}
