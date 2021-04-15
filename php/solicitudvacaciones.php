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
        $fechainicio = filter_input(INPUT_POST, 'fechainicio');
        $fechafin = filter_input(INPUT_POST, 'fechafin');
        $tipo = filter_input(INPUT_POST, 'tipo');
    }
    if ($fechainicio <= $fechafin) {

        $con = new PDO($conexion, $userBBDD, $passBBDD);

        $sql = "SELECT idResponsable FROM usuarios WHERE idUsuario=" . $_SESSION['idUsuario'];
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $idResponsable = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($idResponsable['idResponsable'] == null) {
            $destinatario = $_SESSION['idUsuario'];
        } else {
            $destinatario = $idResponsable['idResponsable'];
        }
        $sql = "INSERT INTO solicitudes (idusuario, tipo, f_inicio, f_fin) VALUES (:idusuario, :tipo, :f_inicio, :f_fin)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam('idusuario', $_SESSION['idUsuario'], PDO::PARAM_INT);
        $stmt->bindParam('tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam('f_inicio', $fechainicio, PDO::PARAM_STR);
        $stmt->bindParam('f_fin', $fechafin, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['avisos'][] = [
                "tipo" => "success",
                "texto" => "La solicitud se ha generado correctamente y está a la espera de su aprobación."
            ];
            $mensaje = "El usuario " . $_SESSION['nombre'] . " " . $_SESSION['apellido1'] . " " . $_SESSION['apellido2'] . " ha realizado una solicitud de vacaciones que van del " . $fechainicio . " hasta el " . $fechafin . " en concepto de " . $tipo;
            $destinatarios = [intval($destinatario)];
 
            enviarmensaje(1, $destinatarios, "Solicitud realizada por " . $_SESSION['nombre'] . " " . $_SESSION['apellido1'], $mensaje);
        } else {
            $_SESSION['avisos'][] = [
                "tipo" => "error",
                "texto" => "Algo ha ocurrido al generar tu solicitud contacta con el departamento correspondiente."
            ];
        }
    } else {
        $_SESSION['avisos'][] = [
            "tipo" => "error",
            "texto" => "La fecha final es anterior a la fecha comienzo del periodo."
        ];
    }
    header('location:../welcome.php?pagina=vacaciones#dos');
} catch (PDOException $e) {
    echo $e->getMessage();
}
