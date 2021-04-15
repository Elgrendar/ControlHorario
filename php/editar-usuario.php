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
    //Verificamos si hemos recibido parametros por $POST
    if (!empty($_POST) && empty($_GET)) {
        $id = filter_input(INPUT_POST, 'idUsuario');
        $nombre = filter_input(INPUT_POST, 'nombre');
        $apellido1 = filter_input(INPUT_POST, 'apellido1');
        $apellido2 = filter_input(INPUT_POST, 'apellido2');
        $dni = filter_input(INPUT_POST, 'dni');
        $f_baja = filter_input(INPUT_POST, 'fbaja');
        $sexo = filter_input(INPUT_POST, 'sexo');
        $f_nacimiento = filter_input(INPUT_POST, 'fnacimiento');
        $tarjeta = filter_input(INPUT_POST, 'tarjeta');
        $email = filter_input(INPUT_POST, 'email');
        $password = filter_input(INPUT_POST, 'pass');
        $password2 = filter_input(INPUT_POST, 'pass2');
        $idResponsable = filter_input(INPUT_POST, 'responsables');
        $administrador = isset($_POST['administrador']) ? 1 : 0;
        $rrhh = isset($_POST['rrhh']) ? 1 : 0;
        $jefedepartamento = isset($_POST['jefedepartamento']) ? 1 : 0;
        $trabajador = isset($_POST['trabajador']) ? 1 : 0;

        if ($nombre == null || $apellido1 == null || $email == null || $nombre == "" || $apellido1 == "" || $email == "") {
            $errores[] = "Alguno de los campos obligatorios no se ha introducido.";
            //Introducimos el error en la variable de session para que el usuario pueda verlo.
            $_SESSION['avisos'][] = [
                "tipo" => "error",
                "texto" => '<strong>¡Error!</strong> Alguno de los campos obligatorios no se ha introducido.'
            ];
        } else {
            if ($password == $password2) {
                $password = password_hash($password, PASSWORD_DEFAULT);
            } else {
                //Definimos el error para que no se suba el fichero ni se ejecute el alta.
                $errores[] = "Las contraseñas no coinciden.";
                //Introducimos el error en la variable de session para que el usuario pueda verlo.
                $_SESSION['avisos'][] = [
                    "tipo" => "error",
                    "texto" => '<strong>¡Error!</strong> Las contraseñas no coinciden.'
                ];
            }
        }

        //Si la variable errores está vacia incluimos el usuario en la BBDD en caso contrario nos redirige al formulario de alta.
        if (isset($errores)) {
            header("location:../welcome.php?pagina=editar usuario&id=" . $id);
        } else {
            $foto = subirArchivo("../assets/img/usuarios/");
            if ($foto == "ERROR") {
                $foto = filter_input(INPUT_POST, 'fotousuario');
            }

            $sql = "UPDATE usuarios SET dni=:dni, nombre=:nombre, f_baja=:f_baja, apellido1=:apellido1, apellido2=:apellido2, sexo=:sexo, f_nacimiento=:f_nacimiento, tarjeta=:tarjeta, foto=:foto, email=:email, idResponsable=:idResponsable WHERE idUsuario=$id";


            $stmt = $con->prepare($sql);

            $stmt->bindParam('nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam('apellido1', $apellido1, PDO::PARAM_STR);
            $stmt->bindParam('sexo', $sexo, PDO::PARAM_STR);
            $stmt->bindParam('foto', $foto, PDO::PARAM_STR);
            $stmt->bindParam('email', $email, PDO::PARAM_STR);

            if ($idResponsable == null) {
                $stmt->bindParam('idResponsable', $idResponsable, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam('idResponsable', $idResponsable, PDO::PARAM_STR);
            }
            if ($apellido2 == null) {
                $stmt->bindParam('apellido2', $apellido2, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam('apellido2', $apellido2, PDO::PARAM_STR);
            }
            if ($f_nacimiento == null) {
                $stmt->bindParam('f_nacimiento', $f_nacimiento, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam('f_nacimiento', $f_nacimiento, PDO::PARAM_STR);
            }
            if ($f_baja == null) {
                $stmt->bindParam('f_baja', $f_baja, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam('f_baja', $f_baja, PDO::PARAM_STR);
            }
            if ($dni == null) {
                $stmt->bindParam('dni', $dni, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam('dni', $dni, PDO::PARAM_STR);
            }
            if ($tarjeta == null) {
                $stmt->bindParam('tarjeta', $tarjeta, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam('tarjeta', $tarjeta, PDO::PARAM_STR);
            }
            if ($stmt->execute()) {
                $_SESSION['avisos'][] = [
                    "tipo" => "success",
                    "texto" => '<strong>¡Correcto!</strong> Usuario actualizado satisfactoriamente.'
                ];
                modificarRoles($email, $administrador, $rrhh, $jefedepartamento, $trabajador);
            } else {
                $_SESSION['avisos'][] = [
                    "tipo" => "error",
                    "texto" => '<strong>!Error!</strong> No se ha podido actualizar el usuario, contacte con el administrador del sistema.'
                ];
            }
            header("location:../welcome.php?pagina=usuarios");
        }
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
