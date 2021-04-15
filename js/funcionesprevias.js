/**
 * @fileoverview javascript que incorporamos en el head de nuestra página
 * @version      1.0
 * @author       Rafa Campanero <info@rafacampanero.es>
 * @copyright    rafacampanero.es
 */

function rellenarResponsables(id = 0) {
    const responsables = document.getElementById('responsables');
    //Pedimos los usuarios al servidor
    const url = ('php/mostrarUsuarios.php');

    fetch(url)
        .then(response => (response.ok) ? Promise.resolve(response) : Promise.reject(new Error('Failed to load')))

    .then(response => response.json())

    .then(data => {
            allUsersData = data

            for (const user of data) {
                if (user['idUsuario'] == id) {
                    responsables.innerHTML += "<option value=" + user['idUsuario'] + " selected>" + user['idUsuario'] + "-" + user['nombre'] + "" + user['apellido1'] + "</option>";
                } else {
                    responsables.innerHTML += "<option value=" + user['idUsuario'] + ">" + user['idUsuario'] + "-" + user['nombre'] + "" + user['apellido1'] + "</option>";
                }
            }
        })
        .catch((error) => console.log(`Error: ${error.message}`))
}

//Esta función comprueba los mensajes pendientes de leer del usuario con la sesión iniciada.
function comprobarMensajes() {
    //cogemos los objetos necesarios para ver los mensajes
    let messagesNumber = document.getElementById('messages__number');

    //Pedimos los usuarios al servidor
    const url = ('php/comprobarMensajes.php');
    //para enviar los datos creamos un FormData e introducimos los datos a enviar
    var req = new XMLHttpRequest()
    req.open('POST', url, true)
    req.onreadystatechange = function(aEvt) {
        if (req.readyState == 4) {
            if (req.status == 200) {
                mensajes = req.responseText;
                // Comprobamos si hay mensajes pendientes de leer si hay,
                // los metemos en un span y agregamos la clase correspondiente,
                // si no hay mensajes quitamos la clase.
                if (mensajes == 0) {
                    messagesNumber.classList.remove('messages__number');
                    messagesNumber.innerHTML = '';
                } else {
                    messagesNumber.classList.add('messages__number');
                    messagesNumber.innerHTML = mensajes;
                }
            }
        }
    }
    req.send();
}

//Esta funcion muestra las solicitudes del usuario que esten en proceso de aprobación
function mostrarSolicitudesRealizadas() {
    solicitudes = '';
    let realizadas = document.getElementById('realizadas');
    //Pedimos los usuarios al servidor
    const url = ('php/mostrarSolicitudesRealizadas.php');

    let req = new XMLHttpRequest()
    req.open('POST', url, true)

    req.onreadystatechange = function(aEvt) {
        if (req.readyState == 4) {
            if (req.status == 200) {
                solicitudes = JSON.parse(req.responseText);
                if (solicitudes.length > 0) {
                    cadenaHTML = tablaSolicitudes(solicitudes, 1);
                    realizadas.innerHTML = cadenaHTML;
                }
            }
        }
    }
    req.send();
}

//Esta funcion muestra las solicitudes del usuario aprobadas
function mostrarSolicitudesAprobadas() {
    solicitudes = '';
    let aprobadas = document.getElementById('aprobadas');
    //Pedimos los usuarios al servidor
    const url = ('php/mostrarSolicitudesAprobadas.php');

    let req = new XMLHttpRequest()
    req.open('POST', url, true)
    req.onreadystatechange = function(aEvt) {
        if (req.readyState == 4) {
            if (req.status == 200) {
                solicitudes = JSON.parse(req.responseText);
                if (solicitudes.length >= 0) {
                    cadenaHTML = tablaSolicitudes(solicitudes, 1);
                }
                aprobadas.innerHTML = cadenaHTML;
            }
        }
    }
    req.send();
}

//Esta funcion muestra las solicitudes pendientes de aprobar por el jefe de departamento o RRHH
function mostrarSolicitudesPendientes() {
    solicitudes = '';
    let pendientes = document.getElementById('pendientes');
    //Pedimos los usuarios al servidor
    const url = ('php/mostrarSolicitudesPendientes.php');

    let req = new XMLHttpRequest()
    req.open('POST', url, true)
    req.onreadystatechange = function(aEvt) {
        if (req.readyState == 4) {
            if (req.status == 200) {
                solicitudes = JSON.parse(req.responseText);
                if (solicitudes.length > 0) {
                    cadenaHTML = tablaSolicitudes(solicitudes, 2);
                }
                pendientes.innerHTML = cadenaHTML;
            }
        }
    }
    req.send();
}

// Compone la tabla de las solicitudes de vacaciones recibiendo el array como parametro
//Devuelve una cadena con el código HTML de la tabla el tipo 1 devuelve la tabla básica
// El tipo 2 devuelve la tabla con opciones avanzadas.
function tablaSolicitudes(solicitudes, tipo) {
    cadena = '<table><thead><tr><th>Código</th>';
    if (tipo == 2) {
        cadena += '<th>Trabajador</th>';
    }
    cadena += '<th>Tipo</th><th>Inicio</th><th>Fin</th><th>DPTO.</th><th>RRHH</th>';
    if (tipo == 2) {
        cadena += '<th>Opciones</th>';
    }

    cadena += '</tr></thead><tbody>';
    solicitudes.forEach(solicitud => {
        cadena += '<tr><td>' + solicitud['idSolicitud'] + '</td>';
        if (tipo == 2) {
            cadena += '<td>' + solicitud['nombre'] + ' ' + solicitud['apellido1'] + ' ';
            if (solicitud['apellido2'] != null) {
                cadena += solicitud['apellido2'];
            }
            cadena += '</td>';
        }
        cadena += '<td>' + solicitud['tipo'] + '</td><td>' + solicitud['f_inicio'] + '</td><td>' + solicitud['f_fin'] + '</td><td>';
        cadena += '<div ';
        if (solicitud['aprobado_departamento'] == 1) {
            cadena += 'class="estado trabajando">';
        } else if (solicitud['aprobado_departamento'] == 0) {
            cadena += 'class="estado notrabajando">';
        } else if (solicitud['aprobado_departamento'] == -1) {
            cadena += 'class="estado denegado">';
        }
        cadena += '</div>';
        cadena += '</td><td>';
        cadena += '<div ';
        if (solicitud['aprobado_rrhh'] == 1) {
            cadena += 'class="estado trabajando">';
        } else if (solicitud['aprobado_rrhh'] == 0) {
            cadena += 'class="estado notrabajando">';
        } else if (solicitud['aprobado_departamento'] == -1) {
            cadena += 'class="estado denegado">';
        }
        cadena += '</div>';
        cadena += '</td>';
        if (tipo == 2) {
            if (solicitud['aprobado_departamento'] >= 0) {
                cadena += '<td><a href="./php/gestionSolicitudes.php?idSolicitud=' + solicitud['idSolicitud'] + '&opcion=1" id="aprobar-' + solicitud['idSolicitud'] + '" class="aprobar opcion">';
                cadena += '<img src="./assets/img/iconos/marca-de-verificacion.svg" alt="Aprobar solicitud" title="Aprobar solicitud" class="icono"></a>';
            } else {
                cadena += '<td>';
            }
            cadena += '<a href="./php/gestionSolicitudes.php?idSolicitud=' + solicitud['idSolicitud'] + '&opcion=2" href="welcome.php?pagina=vacaciones#cuatro" name="denegar" id="denegar-' + solicitud['idSolicitud'] + '" class="denegar opcion">';
            cadena += '<img src="./assets/img/iconos/x-markred.svg" alt="Denegar solicitud" title="Denegar solicitud" class="icono"></a></td>';
        }
        cadena += '</tr>';
    });
    cadena += '</tbody></table>';
    return cadena;
}