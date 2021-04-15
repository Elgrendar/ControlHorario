<?php
session_start();
require_once("../inc/configuracion.php");
require_once('../inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}

if (isset($_GET['idSolicitud']) && isset($_GET['opcion'])) {
    $idSolicitud = filter_input(INPUT_GET, 'idSolicitud');
    $opcion = filter_input(INPUT_GET, 'opcion');
}


//Esta solicitud se ejecutará si aún la solicitud está todavía sin aprobar por el jefe de departamento
try {
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $sql = "SELECT * FROM solicitudes WHERE idSolicitud=:idSolicitud AND aprobado_departamento=0";
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idSolicitud', $idSolicitud, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->rowCount();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($count == 1) {
        $idUsuario = $data[0]['idusuario'];
        $finicio = $data[0]['f_inicio'];
        $ffin = $data[0]['f_fin'];
        $tipo = $data[0]['tipo'];

        //Ahora averiguamos quien es el responsable del trabajador que ha realizado la solicitud
        $sql = "SELECT idResponsable FROM usuarios WHERE idUsuario=:idUsuario";
        $stmt = $con->prepare($sql);
        $stmt->bindParam('idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->rowCount();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($count == 1) {
            //Verificamos que el usuario actual es el jefe de departamento o es null
            if ($data[0]['idResponsable'] == $_SESSION['id'] || $data[0]['idResponsable'] == null) {
                //actualizamos como jefe de departamento la opción de aprobado/denegado de departamento
                if ($opcion == 1) {
                    //"aprobar";
                    $sql = "UPDATE solicitudes SET aprobado_departamento=1 WHERE idSolicitud=:idSolicitud;";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam('idSolicitud', $idSolicitud, PDO::PARAM_INT);
                    $stmt->execute();
                    $mensaje = "Su solicitud de " . $tipo . " con comienzo el " . $finicio . " y finalización el " . $ffin . " ha sido aprobada por su jefe del departamento y ha pasado al Responsable de RRHH.";
                    $_SESSION['avisos'][] = [
                        "tipo" => "success",
                        "texto" => '<strong>¡Correcto!</strong> Petición aprobada correctamente.'
                    ];
                    enviarmensaje($_SESSION['id'], [$idUsuario], "Mensaje automático de solicitud Aprobada", $mensaje);
                    notificarRRHH($idSolicitud, $data[0]['idResponsable'], "aprobado");
                } else {
                    //"denegar";
                    $_SESSION['avisos'][] = [
                        "tipo" => "success",
                        "texto" => '<strong>¡Correcto!</strong> Petición denegada correctamente.'
                    ];
                    $sql = "UPDATE solicitudes SET aprobado_departamento=-1 WHERE idSolicitud=:idSolicitud;";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam('idSolicitud', $idSolicitud, PDO::PARAM_INT);
                    $stmt->execute();
                    $mensaje = "Su solicitud de " . $tipo . " con comienzo el " . $finicio . " y finalización el " . $ffin . " ha sido rechazada, contacte con su Responsable de departamento para más información.";
                    enviarmensaje($_SESSION['id'], [$idUsuario], "Mensaje automático de solicitud Rechazada", $mensaje);
                    notificarRRHH($idSolicitud, $data[0]['idResponsable'], "rechazada");
                }
            } else {
                $_SESSION['avisos'][] = [
                    "tipo" => "warning",
                    "texto" => '<strong>¡Atención!</strong> Esta petición no está aprobada por el jefe de Departamento, no puedes gestionarla aún.'
                ];
            }
        } else {
            $_SESSION['avisos'][] = [
                "tipo" => "warning",
                "texto" => '<strong>¡Atención!</strong> Esta petición no está aprobada por el jefe de Departamento, no puedes gestionarla aún.'
            ];
        }
    } else {
        //Esta opción se ejecutará si la solicitud ya está aprobada por el jefe de departamento aun así lo verificamos
        $sql = "SELECT * FROM solicitudes WHERE idSolicitud=:idSolicitud AND (aprobado_departamento=1 OR aprobado_departamento=-1)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam('idSolicitud', $idSolicitud, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->rowCount();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($count == 1) {
            $idUsuario = $data[0]['idusuario'];
            $finicio = $data[0]['f_inicio'];
            $ffin = $data[0]['f_fin'];
            $tipo = $data[0]['tipo'];

            if ($_SESSION['rrhh'] == 1) {
                if ($opcion == 1) {
                    //"aprobar";
                    $sql = "UPDATE solicitudes SET aprobado_rrhh=1 WHERE idSolicitud=:idSolicitud;";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam('idSolicitud', $idSolicitud, PDO::PARAM_INT);
                    $stmt->execute();
                    $mensaje = "Su solicitud de " . $tipo . " con comienzo el " . $finicio . " y finalización el " . $ffin . " ha sido aprobada por su jefe del departamento y RRHH. ¡Disfruta del permiso!";
                    enviarmensaje($_SESSION['id'], [$idUsuario], "Mensaje automático de solicitud Aprobada", $mensaje);
                    $_SESSION['avisos'][] = [
                        "tipo" => "success",
                        "texto" => '<strong>¡Correcto!</strong> Petición aprobada correctamente.'
                    ];
                } else {
                    //"denegar";
                    $sql = "UPDATE solicitudes SET aprobado_rrhh=-1 WHERE idSolicitud=:idSolicitud;";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam('idSolicitud', $idSolicitud, PDO::PARAM_INT);
                    $stmt->execute();
                    $mensaje = "Su solicitud de " . $tipo . " con comienzo el " . $finicio . " y finalización el " . $ffin . " ha sido rechazada, contacte con el Responsable de Recursos Humanos para más información.";
                    enviarmensaje($_SESSION['id'], [$idUsuario], "Mensaje automático de solicitud Rechazada", $mensaje);
                    $_SESSION['avisos'][] = [
                        "tipo" => "success",
                        "texto" => '<strong>¡Correcto!</strong> Petición denegada correctamente.'
                    ];
                }
            }
        } else {
            $_SESSION['avisos'][] = [
                "tipo" => "warning",
                "texto" => '<strong>¡Atención!</strong> Esta petición no está aprobada por el jefe de Departamento, no puedes gestionarla aún.'
            ];
        }
    }
    header("Location: ../welcome.php?pagina=vacaciones#cuatro");
} catch (PDOException $e) {
    echo '{"error":{"text":' . $e->getMessage() . '}}';
}
