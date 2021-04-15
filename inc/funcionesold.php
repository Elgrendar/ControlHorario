<?php
include_once("configuracion.php");

/**
 * Imprime en pantalla los links a incluir en el head de las hojas de estilo y scripts
 */
function getEstilos()
{
    echo '<link rel="stylesheet" href="css/normalize.css">';
    echo '<link rel="stylesheet" href="css/estilo.css">';
    if (!comprobarsesion()) {
        echo '<script src="js/funcionesprevias.js"></script>';
    }
}


/**
 * imprime en pantalla el codigo del Header.
 * @param $programa es el nombre del programa.
 */
function getHeader($programa)
{
    echo '<header><img class="logo" src="./assets/img/sistema/logo.png" alt="Logo control horario" title="Control Horario"/><h1 class="title">' . $programa . '</h1></header>';
}

/**
 * Imprime en pantalla el codigo HTML del nav.
 * 
 */
function getNav()
{
    include_once('./vistas/nav.php');
}

/**
 * Esta funcion devuelve el contenido dependiendo de en que página nos encontremos, verificando si estás autorizado
 * a ver la página.
 */
function getContent($pagina)
{
    if ($pagina == 'principal') {
        echo '<h2 class="section__title">Bienvenido</h2><h3 id="clock" class="clockIn">00:00:00</h3>';
    } else {
        echo '<h2 class="section__title">' . $pagina . '<h3 id="clock" class="clockIn">00:00:00</h3>';
    }

    switch ($pagina) {
        case 'principal':
            include_once('./vistas/principal.php');
            break;
        case 'usuarios':
            if (verificarRol(1) || verificarRol(2)) {
                include_once('./vistas/usuarios.php');
            }
            break;
        case 'mensajes':
            if (verificarRol(4)) {
                include_once('./vistas/mensajes.php');
            }
            break;
        case 'asistencia':
            if (verificarRol(4)) {
                include_once('./vistas/fichar.php');
            }
            break;
        case 'perfil':
            if (verificarRol(4)) {
                include_once('./vistas/perfil.php');
            }
            break;
        case 'informes':
            if (verificarRol(1) || verificarRol((2))) {
                include_once('./vistas/informes.php');
            }
            break;
        case 'alta usuario':
            if (verificarRol(1) || verificarRol((2))) {
                include_once('./vistas/alta-usuario.php');
            }
            break;
        case 'redactar':
            if (verificarRol(4)) {
                include_once('./vistas/redactar.php');
            }
            break;
        case 'fichar':
            if (verificarRol(4)) {
                include_once('./vistas/fichar.php');
            }
            break;
        case 'vacaciones':
            if (verificarRol(4)) {
                include_once('./vistas/vacaciones.php');
            }
            break;
        case 'baja':
            if (verificarRol(4)) {
                include_once('./vistas/baja.php');
            }
            break;
        case 'editar usuario':
            if (verificarRol(1) || verificarRol(2)) {
                if (isset($_GET['id'])) {
                    $id = filter_input(INPUT_GET, 'id');
                }
                include_once('./vistas/editar-usuario.php');
            }
            break;
    }
}

/**
 * imprime en pantalla el codigo del footer y los codigos java script que se agregan al final.
 * @param int $version es el número de versión que corre el actual programa.
 */
function getFooter($version = 0, $pagina = 'login')
{
    include_once('./vistas/footer.php');
    if ($pagina != 'login') {

        echo '<script src="js/funciones.js"></script>';
        echo '<script src="js/nav.js"></script>';
    } else {
        echo '<script src="js/funciones.js"></script>';
    }

    switch ($pagina) {
        case 'usuarios':
            echo '<script src="js/tabla-usuarios.js"></script>';
            break;
        case 'login':
            echo '<script src="js/login.js"></script>';
            break;
        case 'baja':
            echo '<script src="js/tabla-ausencias.js"></script>';
            break;
    }
}

/**
 * Esta funcion nos devuelve true si el usuario que tiene la sesión iniciada supera el nivel de permiso recibido
 * por parametro.
 * @param int $rol Es el nivel de identificación a comprobar 1 Administrador, 2 Responsable RRHH,
 *                  3 Jefe Departamento, 4 Trabajador por defecto coge la restricción más alta. 
 * @return boolean true si el usuario tiene nivel suficiente o false en caso contrario
 */
function verificarRol($rol = 1)
{
    switch ($rol) {
        case 1:
            if ($_SESSION['administrador'] == 1) {
                return true;
            }
            break;
        case 2:
            if ($_SESSION['rrhh'] == 1) {
                return true;
            }
            break;
        case 3:
            if ($_SESSION['jefedepartamento'] == 1) {
                return true;
            }
            break;
        case 4:
            if ($_SESSION['trabajador'] == 1) {
                return true;
            }
            break;
        default:
            return false;
    }
}

/**
 * Esta función devuelve todos los usuarios de la BBDD si eres Administrador o RRHH ordenados por apellidos
 */
function mostrarUsuarios()
{
    if (verificarRol(1) || verificarRol(2)) {
        $sql = 'SELECT idUsuario, nombre, apellido1, apellido2, trabajando FROM usuarios WHERE f_baja IS null ORDER BY apellido1, apellido2, nombre';
    }
    return $sql;
}

function mostrarRegistros()
{
    if (verificarRol(1) || verificarRol(2)) {
        $sql = 'SELECT * FROM registros WHERE hora >= "' . getdate()['year'] . '-' . getdate()['mon'] . '-' . getdate()['mday'] . '" ORDER BY idUsuario';
    } else {
        $sql = 'SELECT * FROM registros WHERE idUsuario=' . $_SESSION['id'] . ' ORDER BY hora';
    }
    return $sql;
}

/**
 * Esta función comprueba si existen avisos en la variable de session avisos y los muestra.
 */
function mostrarAvisos()
{
    if (isset($_SESSION['avisos'])) {
        foreach ($_SESSION['avisos'] as $key => $value) {

            print '<div class="alert ' . $value["tipo"] . '">
                        <input type="checkbox" id="alert' . $key . '"/>
                        <label class="close" title="close" for="alert' . $key . '">
                            <img src="./assets/img/iconos/x-mark.svg" class="icon-remove" alt="icono cruz" title="Cerrar"/>
                        </label>
                        <p class="inner">
                            ' . $value['texto'] . '
                        </p>
                    </div>';

            unset($_SESSION['avisos']);
        }
    }
}
/**
 * Esta función comprueba si hemos iniciado sesion y estamos logados o no y devuelve true en caso afirmativo
 * false en caso contrario.
 * @return boolean true si se ha inicidado la sesión y nos hemos identificado o false en caso contrario
 */
function comprobarsesion()
{
    if (!isset($_SESSION['iniciada']) || $_SESSION['iniciada'] != true) {
        return true;
    } else {
        return false;
    }
}
/**
 * Esta función sube los archivos de imagen recibidos por post a la carpeta recibida como parametro
 * Verifica que no tenga un peso superior a 1MB y que sea del tipo jpg,png,gif,jpeg y le pone un nombre aleatorio
 * Ademas nos facilita información en la variable de sessión avisos de los posibles errores o de su finalización.
 * @param string $ruta Donde se guardará el archivo solicitado
 * @return string Devuelve el nombre del archivo si todo ha ido bien o ERROR en caso de que algo haya fallado.
 */
function subirArchivo($ruta)
{
    $directorioSubida = $ruta;
    $max_file_size = "1024000";
    $extensionesValidas = array("jpg", "png", "gif", "jpeg", "pdf");

    $errores = array();
    if (!empty($_FILES['archivo']['name'])) {
        $nombreArchivo = $_FILES['archivo']['name'];
        $filesize = $_FILES['archivo']['size'];
        $directorioTemp = $_FILES['archivo']['tmp_name'];
        $arrayArchivo = pathinfo($nombreArchivo);
        $extension = $arrayArchivo['extension'];

        // Comprobamos la extensión del archivo
        if (!in_array($extension, $extensionesValidas) && $filesize > 0) {
            $errores[] = "La extensión del archivo no es válida o no se ha subido ningún archivo.";
            $_SESSION['avisos'][] = [
                "tipo" => "warning",
                "texto" => "La extensión del archivo no es válida o no se ha subido ningún archivo."
            ];
        }
        // Comprobamos el tamaño del archivo
        if ($filesize > $max_file_size) {
            $errores[] = "El archivo debe de tener un tamaño inferior a 1024000 kb";
            $_SESSION['avisos'][] = [
                "tipo" => "warning",
                "texto" => "La imagen debe de tener un tamaño inferior a 1024000 kb"
            ];
        }

        // Renombramos el nombre del archivo y comprobamos si ya existe esa combinación aleatoria en la carpeta
        do {
            $nombreArchivo = nombreAleatorio(16);
            $nombreCompleto = $directorioSubida . $nombreArchivo . "." . $extension;
        } while (file_exists($nombreCompleto));

        // Desplazamos el archivo si no hay errores

        if (empty($errores)) {
            if (move_uploaded_file($directorioTemp, $nombreCompleto)) {
                $_SESSION['avisos'][] = [
                    "tipo" => "success",
                    "texto" => "El archivo se ha subido correctamente"
                ];
                return $nombreArchivo . "." . $extension;
            } else {
                $errores[] = "La imagen  no se ha subido.";
                $_SESSION['avisos'][] = [
                    "tipo" => "warning",
                    "texto" => "El archivo no ha podido subirse."
                ];
            }
        }
    }

    return "ERROR";
}

/**
 * Esta funcion devuelve un usuario en un resultado de una consulta MySQL con los datos del usuario
 * a excepción de la contraseña que es borrada antes de devolverlo
 * @param int $id Identificador del usuario que queremos recuperar
 * @return mixed El usuario solicitado
 */
function recuperarDatosUsuario($id)
{

    global $conexion, $userBBDD, $passBBDD;
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $sql = "SELECT * FROM usuarios WHERE idUsuario=?;";
    try {
        $stmt = $con->prepare($sql);
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $usuario['password'] = '';
        return $usuario;
    } catch (PDOException $e) {
        $_SESSION['avisos'][] = [
            "tipo" => "error",
            "texto" => $e->getMessage()
        ];
    }
}

/**
 * Esta funcion nos devuelve los permisos del usuario recibido como parametro 
 * y nos lo devuelve en un array por orden ascendente [administrador,rrhh,jefedepartamento,trabajador]
 * @param int $id Identificador del usuario del cual consultamos los permisos.
 * @return array ordenado ascendentemente[administrador,rrhh,jefedepartamento,trabajador]
 */
function recuperarRoles($id)
{
    global $conexion, $userBBDD, $passBBDD;
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $sql = "SELECT * FROM usuariorol WHERE idusuario=?;";
    try {
        $stmt = $con->prepare($sql);
        $stmt->execute([$id]);
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($roles as $rol) {
            $permisos[] = $rol['permitido'];
        }
        return $permisos;
    } catch (PDOException $e) {
        $_SESSION['avisos'][] = [
            "tipo" => "error",
            "texto" => $e->getMessage()
        ];
    }
}

/**
 * Generar nombre para archivos aleatorios recibiendo como parametro la longitud de caracteres solicitados,
 * si no recibe parametro la longitud se establece en 16
 * @param int $longitud Longitud deseada de caracteres a devolver en una cadena aleatoria por defecto toma 16 caracteres
 * @return string la cadena generada aleatoriamenteo
 * */
function nombreAleatorio(int $longitud = 16)
{
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($permitted_chars), 0, $longitud);
}
/**
 * Esta función devuelve true si el email existe en la bbdd y no en caso contrario
 * @param string $email Con el email a comprobar en la bbdd
 * @return boolean true si existe en la bbdd o false en caso contrario
 */
function comprobarEmail($email)
{
    global $conexion, $userBBDD, $passBBDD;
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $sql = "SELECT count('idUsuario') FROM usuarios WHERE email='$email';";
    try {
        if ($result = $con->query($sql)) {
            if ($result->fetchColumn() > 0) {
                return true;
            } else {
                return false;
            }
        }
    } catch (PDOException $e) {
        $_SESSION['avisos'][] = [
            "tipo" => "error",
            "texto" => $e->getMessage()
        ];
    }
}

/**
 * Esta funcion inserta los valores de los roles de usuario o los actualiza si ya los tiene definidos.
 * @param string $email  del usuario a actualizar o insertar
 * @param boolean $administrador 0 no permitido 1 permitido el rol.
 * @param boolean $rrhh 0 no permitido 1 permitido el rol.
 * @param boolean $jefedepartamento 0 no permitido 1 permitido el rol.
 * @param boolean $trabajador 0 no permitido 1 permitido el rol.
 */
function modificarRoles($email, $administrador, $rrhh, $jefedepartamento, $trabajador)
{
    global $conexion, $userBBDD, $passBBDD;
    $sql = "SELECT idUsuario FROM usuarios WHERE email='$email'";
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    foreach ($con->query($sql) as $row) {
        $idUsuario = $row['idUsuario'];
    }
    $sql = "SELECT count(*) FROM usuariorol WHERE idUsuario='$idUsuario';";

    if ($result = $con->query($sql)) {
        //Comprobamos si el usuario ya tiene sus permisos para crearlos o actualizarlos
        if ($result->fetchColumn() > 0) {
            $sql = "UPDATE usuariorol SET permitido=? WHERE idUsuario=? AND idRol=?;";
            $stmt = $con->prepare($sql);
            $stmt->execute([$administrador, $idUsuario, 1]);
            $stmt->execute([$rrhh, $idUsuario, 2]);
            $stmt->execute([$jefedepartamento, $idUsuario, 3]);
            $stmt->execute([$trabajador, $idUsuario, 4]);
        } else {
            $sql = "INSERT INTO usuariorol (idusuario, idRol, permitido) VALUES (?,?,?)";
            $stmt = $con->prepare($sql);
            $stmt->execute([$idUsuario, 1, $administrador]);
            $stmt->execute([$idUsuario, 2, $rrhh]);
            $stmt->execute([$idUsuario, 3, $jefedepartamento]);
            $stmt->execute([$idUsuario, 4, $trabajador]);
        }
    }
}
/**
 * Esta función borrar el archivo de imagen de un usuario si es distinta de la imagen por defecto
 * el nombre de la imagen por defecto es sinimagen.png
 * @param int $id Es el id del usuario a borrar la imagen.
 * @return boolean true en caso de borrar el archivo correctamente false en caso contrario.
 */
function borrarImagen($id)
{
    global $conexion, $userBBDD, $passBBDD;
    $sql = "SELECT foto FROM usuarios WHERE idUsuario=:id";
    $con = new PDO($conexion, $userBBDD, $passBBDD);

    $stmt = $con->prepare($sql);
    $stmt->bindParam('id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $imagen = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($imagen[0]['foto'] != 'sinimagen.png') {
        return unlink("../assets/img/usuarios/" . $imagen[0]['foto']);
    } else {
        return false;
    }
}

/**
 * Esta funcion comprueba si un trabajador está trabajando 
 * @param int $id Es el id del usuario consultado
 * @return boolean true si el trabajador está trabajando o false en caso contrario
 */
function trabajando($id)
{
    global $conexion, $userBBDD, $passBBDD;
    $sql = "SELECT trabajando FROM usuarios WHERE idUsuario=:id";
    $con = new PDO($conexion, $userBBDD, $passBBDD);

    $stmt = $con->prepare($sql);
    $stmt->bindParam('id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $imagen = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($imagen[0]['trabajando'] == 1) {
        return true;
    } else {
        return false;
    }
}


/**
 * Esta función crea en la BBDD los mensajes enviados a sus distintos destinatarios
 * @param int $idOrigen es la id del emisor del mensaje
 * @param array $Destinatarios Con los destinatarios del mensaje
 * @param string $asunto es el asunto del mensaje tiene un máximo de 50 caracteres
 * @param string $mensaje es el contenido del mensaje, solo contiene caracteres.
 */

function enviarmensaje($idOrigen, $Destinatarios, $asunto, $mensaje)
{
    global $conexion, $userBBDD, $passBBDD;
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    foreach ($Destinatarios as $destinatario) {

        $sql = "INSERT INTO mensajes(idOrigen, idDestinatario, asunto, mensaje) VALUES (:idOrigen, :idDestinatario, :asunto, :mensaje)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam('idOrigen', $idOrigen, PDO::PARAM_INT);
        $stmt->bindParam('idDestinatario', $destinatario, PDO::PARAM_INT);
        $stmt->bindParam('asunto', $asunto, PDO::PARAM_STR);
        $stmt->bindParam('mensaje', $mensaje, PDO::PARAM_STR);
        echo $destinatario;
        $stmt->execute();
    }
}


function notificarRRHH($idSolicitud, $idResponsable, $estado)
{
    $data = idRRHH();
    $count = count($data);
    if ($count > 0) {
        if ($estado == "aprobado") {
            $asunto = "Mensaje automático de aprobación de solicitud.";
            $mensaje = "La solicitud con código $idSolicitud ha sido aprobado por el responsable del departamento y está a la espera de la aprobación de RRHH";
        } else {
            $asunto = "Mensaje automático de denegación de solicitud.";
            $mensaje = "La solicitud con código $idSolicitud ha sido rechazada por el responsable del departamento.";
        }
        foreach ($data as $destinatario) {
            enviarmensaje($idResponsable, [$destinatario['idusuario']], $asunto, $mensaje);
        }
    }
}

function idRRHH()
{
    global $conexion, $userBBDD, $passBBDD;
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $sql = "SELECT idusuario FROM usuariorol WHERE idRol=2 AND permitido=1";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

function rellenarDestinatarios()
{
    $cadena = "";
    global $conexion, $userBBDD, $passBBDD;
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $sql = "SELECT idUsuario, nombre, apellido1, apellido2 FROM usuarios WHERE idUsuario!=:idUsuario AND f_baja IS NULL ORDER BY apellido1, apellido2, nombre";
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idUsuario', $_SESSION['idUsuario'], PDO::PARAM_INT);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($usuarios as $usuario) {
        $cadena .= '<option value="' . $usuario["idUsuario"] . '">' . $usuario['apellido1'] . " " . $usuario['apellido2'] . ", " . $usuario['nombre'] . '</option>';
    }
    return $cadena;
}


/**
 * Esta funcion nos devuelve los mensajes del usuario con sessión iniciada.
 * @param int $tipo Nos indica si quiere los mensajes recibidos valor 1 o los enviados valor 2 
 * @return array Nos devuelve los valores resultantes de la consulta
 */
function mostrarMensajes($tipo)
{
    $idUsuario = $_SESSION['idUsuario'];
    switch ($tipo) {
        case 1:
            $sql = "SELECT idMensaje, fechaEnvio, leido, idOrigen, asunto FROM mensajes WHERE idDestinatario=:idUsuario ORDER BY fechaEnvio DESC";
            break;
        case 2:
            $sql = "SELECT idMensaje, fechaEnvio, leido, idDestinatario, asunto FROM mensajes WHERE idOrigen=:idUsuario ORDER BY fechaEnvio DESC";
            break;
    }
    global $conexion, $userBBDD, $passBBDD;
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

function obtenerNombre($idUsuario)
{
    global $conexion, $userBBDD, $passBBDD;
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $sql = "SELECT nombre, apellido1, apellido2 FROM usuarios WHERE idUsuario=:idUsuario";
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $nombre = $data['nombre'] . " " . $data['apellido1'];
    $nombre .= ($data['apellido2'] != null) ? " " . $data['apellido2'] : "";
    return $nombre;
}

function mostrarSolicitudes()
{
    $cadena = '';
    global $conexion, $userBBDD, $passBBDD;
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $sql = "SELECT idSolicitud, tipo, f_inicio FROM solicitudes WHERE idusuario=:idUsuario AND tipo='Otros'";
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idUsuario', $_SESSION['idUsuario'], PDO::PARAM_INT);
    $stmt->execute();
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($solicitudes as $solicitud) {
        $cadena .= '<option value="' . $solicitud['idSolicitud'] . '">Solicitud del ' . date_create($solicitud['f_inicio'])->format('d-m-Y') . '</option>';
    }
    return $cadena;
}

function mostrarComunicado()
{
    $cadena = '';
    global $conexion, $userBBDD, $passBBDD;
    $con = new PDO($conexion, $userBBDD, $passBBDD);
    $sql = "SELECT idAusencia, causa, f_inicio FROM ausencias WHERE idusuario=:idUsuario";
    $stmt = $con->prepare($sql);
    $stmt->bindParam('idUsuario', $_SESSION['idUsuario'], PDO::PARAM_INT);
    $stmt->execute();
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($solicitudes as $solicitud) {
        $cadena .= '<option value="' . $solicitud['idAusencia'] . '">Solicitud del ' . date_create($solicitud['f_inicio'])->format('d-m-Y') . ' con el concepto de "' . $solicitud['causa'] . '"</option>';
    }
    return $cadena;
}

function antiguedad($fechaAlta)
{

    $fechaAlta = new DateTime($fechaAlta);
    $fechaActual = new DateTime("now");
    $diff = $fechaAlta->diff($fechaActual);
    $str = '';
    $str .= ($diff->invert == 1) ? ' - ' : '';
    if ($diff->y > 0) {
        // años
        $str .= ($diff->y > 1) ? $diff->y . ' Años ' : $diff->y . ' Año ';
    }
    if ($diff->m > 0) {
        // meses
        $str .= ($diff->m > 1) ? $diff->m . ' Meses ' : $diff->m . ' Mes ';
    }
    if ($diff->d > 0) {
        // dias
        $str .= ($diff->d > 1) ? $diff->d . ' Días ' : $diff->d . ' Día ';
    }
    /*if ($diff->h > 0) {
        // horas
        $str .= ($diff->h > 1) ? $diff->h . ' Horas ' : $diff->h . ' Hora ';
    }
    if ($diff->i > 0) {
        // minutos
        $str .= ($diff->i > 1) ? $diff->i . ' Minutos ' : $diff->i . ' Minuto ';
    }
    if ($diff->s > 0) {
        // segundos
        $str .= ($diff->s > 1) ? $diff->s . ' Segundos ' : $diff->s . ' Segundos ';
    }*/

    return $str;
}
