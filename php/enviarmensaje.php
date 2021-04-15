<?php
session_start();
require_once("../inc/configuracion.php");
require_once('../inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}

try {
    //Verificamos si hemos recibido parametros por $POST
    if (!empty($_POST)) {
        $idDestinatario = filter_input(INPUT_POST, 'destinatario');
        $asunto = filter_input(INPUT_POST, 'asunto');
        $mensaje = filter_input(INPUT_POST, 'mensajetexto');
        $idOrigen = $_SESSION['idUsuario'];
    }

    if (empty($idDestinatario) or empty($asunto) or empty($mensaje)) {
        $_SESSION['avisos'][] = [
            "tipo" => "error",
            "texto" => "Alguno de los parametros requeridos no se ha rellenado."
        ];
    } else {
        enviarmensaje($idOrigen, [$idDestinatario], $asunto, $mensaje);
        $_SESSION['avisos'][] = [
            "tipo" => "success",
            "texto" => "Mensaje enviado correctamente."
        ];
    }

    header("Location:../welcome.php?pagina=mensajes#dos");
} catch (PDOException $e) {
    echo $e->getMessage();
}
