<?php
session_start();
require_once("../inc/configuracion.php");
require_once("../inc/funciones.php");
if (comprobarsesion()) {
    header("location:../index.php");
}
try {
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $id = filter_input(INPUT_POST, 'id');
    if ($id != 1) {
        if (!borrarImagen($id)) {
            $_SESSION['avisos'][] = [
                "tipo" => "warning",
                "texto" => '<strong>¡Cuidado!</strong> La imagen del usuario no se ha borrado, puede ser debido a que era la imagen standard.'
            ];
        }
        $sql1 = "DELETE FROM usuariorol WHERE idusuario = :id";
        $sql = "DELETE FROM registros WHERE idUsuario = :id";

        $stmt = $con->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt1 = $con->prepare($sql1);
        $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt1->execute() && $stmt->execute()) {
            $sql = "DELETE FROM usuarios WHERE idUsuario = :id";
            $stmt2 = $con->prepare($sql);
            $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
            if ($stmt2->execute()) {
                $_SESSION['avisos'][] = [
                    "tipo" => "success",
                    "texto" => '<strong>¡Correcto!</strong> Usuario Eliminado correctamente.'
                ];
                echo "CORRECTO";
            }
        }
        echo "ERROR";
    } else {
        $_SESSION['avisos'][] = [
            "tipo" => "warning",
            "texto" => '<strong>¡Atención!</strong> Este usuario no puede ser eliminado.'
        ];
    }
} catch (PDOException $e) {
    echo '{"error":{"text":' . $e->getMessage() . '}}';
}
