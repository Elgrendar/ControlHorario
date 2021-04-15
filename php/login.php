<?php
session_start();
require_once("../inc/configuracion.php");

try {
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $email = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'pass');

    $stmt = $con->prepare("SELECT * FROM usuarios WHERE email=:email AND (f_baja IS null OR f_baja > CURRENT_DATE())");
    $stmt->bindParam("email", $email, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($count > 0) {
        $hash_password = $data[0]['password'];

        if (password_verify($password, $hash_password)) {
            $_SESSION['iniciada'] = true;
            $_SESSION['nombre'] = $data[0]['nombre'];
            $_SESSION['apellido1'] = $data[0]['apellido1'];
            $_SESSION['apellido2'] = $data[0]['apellido2'];
            $_SESSION['foto'] = $data[0]['foto'];
            $_SESSION['idUsuario'] = $data[0]['idUsuario'];
            $_SESSION['dni'] = $data[0]['dni'];
            $_SESSION['email'] = $data[0]['email'];
            $_SESSION['f_alta'] = $data[0]['f_alta'];
            $_SESSION['idresponsable'] = $data[0]['idResponsable'];
            $_SESSION['trabajando']= $data[0]['trabajando'];
            if ($data[0]['tarjeta'] == null) {
                $_SESSION['tarjeta'] = 0;
            } else {
                $_SESSION['tarjeta'] = $data[0]['tarjeta'];
            }

            //Agregar todos los parametros de sesion de usuario antes de esta linea.
            $_SESSION['id'] = $data[0]['idUsuario'];
            $stmt = $con->prepare("SELECT * FROM usuariorol WHERE idUsuario=:id");
            $stmt->bindParam('id', $_SESSION['id'], PDO::PARAM_STR);
            $stmt->execute();
            $countRol = $stmt->rowCount();
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($countRol > 0) {
                $_SESSION['administrador'] = $roles[0]['permitido'];
                $_SESSION['rrhh'] = $roles[1]['permitido'];
                $_SESSION['jefedepartamento'] = $roles[2]['permitido'];
                $_SESSION['trabajador'] = $roles[3]['permitido'];
            } else {
                $_SESSION['administrador'] = false;
                $_SESSION['rrhh'] = false;
                $_SESSION['jefeDepartamento'] = false;
                $_SESSION['trabajador'] = false;
            }
            $roles = null;
            $countRol = null;
            $count = null;
            $data = null;
            $stmt = null;
            $con = null;
            echo "true";
        } else {
            echo "false";
        }
    }
} catch (PDOException $e) {
    echo '{"error":{"text":' . $e->getMessage() . '}}';
}
