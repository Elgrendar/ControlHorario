<?php
session_start();
require_once("../inc/configuracion.php");
require_once('../inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}

try {
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    
/*
* Si el usuario con sesion iniciada es administrador o RRHH vera todas las solicitudes pendientes de aprobación
* si el usuario es jefe de departamento verá todas las solicitudes pendientes de las personas a su cargo.
*/

if($_SESSION['administrador'] == 1 || $_SESSION['rrhh'] == 1){
    $sql= "SELECT * FROM solicitudes INNER JOIN usuarios on solicitudes.idusuario=usuarios.idUsuario WHERE aprobado_departamento=0 OR aprobado_rrhh=0";
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idusuario',$_SESSION['idUsuario'],PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $resultados_json = json_encode($data);
    echo $resultados_json;

}elseif( $_SESSION['jefedepartamento'] == 1){
    
    $sql= "SELECT * FROM solicitudes INNER JOIN usuarios on solicitudes.idusuario=usuarios.idUsuario WHERE aprobado_departamento=0 AND (usuarios.idResponsable=:idUsuario OR (solicitudes.idusuario=".$_SESSION['idUsuario']." AND usuarios.idResponsable IS NULL));";
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idUsuario',$_SESSION['idUsuario'],PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resultados_json = json_encode($data);
    echo $resultados_json;
}




    

} catch (PDOException $e) {
    echo '{"error":{"text":' . $e->getMessage() . '}}';
}