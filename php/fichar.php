<?php
session_start();
require_once("../inc/configuracion.php");
require_once("../inc/funciones.php");
if (comprobarsesion()) {
    header("location:../index.php");
}
try {
    //establecemos la conexion con la bbdd
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    //Verificamos si hemos recibido parametros por $POST
    if (!empty($_POST)) {
    $idUsuario=filter_input(INPUT_POST,'idUsuario');
    $tipo=filter_input(INPUT_POST,'tipo');
    $hora=filter_input(INPUT_POST,'hora');
    
    $extraordinario = filter_input(INPUT_POST,'extraordinario');
    }

    if($extraordinario=="on"){
        $observaciones = filter_input(INPUT_POST,'observaciones');
        if(empty($observaciones)){
            $_SESSION['avisos'][] = [
                "tipo" => "error",
                "texto" => '<strong>¡Error!</strong> Si seleccionas fichaje extraordinario, debes seleccionar una opción.'
            ];
            header('Location: ../welcome.php?pagina=fichar');
            die();
        }
    }else{
        $observaciones="normal";
    }

    $sql ="INSERT INTO registros (idUsuario, tipo, observaciones) VALUES (:idUsuario,:tipo,:observaciones)";

    $stmt = $con->prepare($sql);
    $stmt->bindParam('idUsuario', $idUsuario, PDO::PARAM_INT);
    //$stmt->bindParam('hora', $hora, PDO::PARAM_STR);
    $stmt->bindParam('tipo', $tipo, PDO::PARAM_STR);
    $stmt->bindParam('observaciones', $observaciones, PDO::PARAM_STR);
    $stmt->execute();

    if($stmt->rowCount()>0){
        $_SESSION['avisos'][] = [
            "tipo" => "success",
            "texto" => '<strong>¡Correcto!</strong> Registro satisfactorio.'
        ];
    }else{
        $_SESSION['avisos'][] = [
            "tipo" => "error",
            "texto" => '<strong>¡Atención!</strong> No se ha podido crear el registro correctamente, contacta con el departamento correspondiente.'
        ];
    }

    if($tipo=="entrada"){
        $sql = "UPDATE usuarios SET trabajando=1 WHERE idUsuario=:idUsuario";
        $_SESSION['trabajando']= 1;
    }else{
        $sql = "UPDATE usuarios SET trabajando=0 WHERE idUsuario=:idUsuario";
        $_SESSION['trabajando']= 0;
    }
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();



    header('Location: ../welcome.php');


} catch (PDOException $e) {
    echo '{"error":{"text":' . $e->getMessage() . '}}';
}