<?php
session_start();
require_once("../inc/configuracion.php");
require_once('../inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}
try {
    if($_POST['idMensaje']){
        $idMensaje=filter_input(INPUT_POST,'idMensaje');
    }
    //establecemos la conexion con la bbdd
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    //Comprobamos los mensajes del usuario con la sesión iniciada y que no ha leido
    $sql = "UPDATE mensajes SET leido=1,fechaLectura = current_timestamp()	 WHERE idMensaje=:idMensaje";
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idMensaje', $idMensaje, PDO::PARAM_INT);
    $stmt->execute();
    $sql = "SELECT * FROM mensajes WHERE idMensaje=:idMensaje";
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idMensaje', $idMensaje, PDO::PARAM_INT);
    $stmt->execute();
    $contenido = $stmt->fetch(PDO::FETCH_ASSOC);
    $contenido['codigo']=$contenido['idOrigen'];
    $contenido['idOrigen']=obtenerNombre($contenido['idOrigen']);
    $contenido['idDestinatario']= obtenerNombre($contenido['idDestinatario']);
    $resultados_json = json_encode($contenido);
    echo $resultados_json;
} catch (PDOException $e) {
    echo '{"error":{"text":' . $e->getMessage() . '}}';
}
