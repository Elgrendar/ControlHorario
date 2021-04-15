<?php
session_start();

if (!isset($_SESSION['iniciada']) || $_SESSION['iniciada'] != true) {
    header("location:index.php");
}
include("inc/funciones.php");
include("inc/configuracion.php");
if(isset($_GET['pagina'])){
    $pagina=filter_input(INPUT_GET,'pagina');
}else{
    $pagina='principal';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $programa; ?></title>
    <?php
    getEstilos();
    ?>
</head>

<body>
    <?php
    getHeader($programa);
    getNav($pagina);
    getContent($pagina);
    getFooter($version,$pagina);
    ?>
</body>

</html>