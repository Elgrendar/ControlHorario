/**
 * @fileoverview javascript que incorporamos al final de nuestra página antes de cerrar el body
 * @version      1.0
 * @author       Rafa Campanero <info@rafacampanero.es>
 * @copyright    rafacampanero.es
 */

const clock = document.getElementById('clock');
//Definimos la funcion que actualiza el tiempo
const actualizarTiempo = () => {
    let time = new Date()
    clock.innerHTML = time.getHours() + ":" + ('0' + time.getMinutes()).slice(-2) + ":" + ('0' + time.getSeconds()).slice(-2)
}

function leerMensaje(evt) {
    contenedorAsuntoRecibido = document.getElementById("mostrarAsuntoRecibido");
    contenedorDestinatarioRecibido = document.getElementById("mostrarDestinatarioRecibido");
    contenedorMensajeRecibido = document.getElementById("mostrarMensajeRecibido");
    contenedorAsuntoEnviado = document.getElementById("mostrarAsuntoEnviado");
    contenedorDestinatarioEnviado = document.getElementById("mostrarDestinatarioEnviado");
    contenedorMensajeEnviado = document.getElementById("mostrarMensajeEnviado");
    contenedorAsuntoResponder = document.getElementById("mostrarAsuntoResponder");
    contenedorDestinatarioResponder = document.getElementById("mostrarDestinatarioResponder");
    contenedorAsuntoResponderHidden = document.getElementById("mostrarAsuntoResponderhidden");
    contenedorDestinatarioResponderHidden = document.getElementById("mostrarDestinatarioResponderhidden");
    contenedorMensajeResponder = document.getElementById("mostrarMensajeResponder");

    if (evt.target.classList.contains("leer") || evt.target.classList.contains("responder")) {
        // Si ha hecho click sobre un destino, lo leemos
        let id = evt.target.parentElement.parentElement.parentElement.id;
        //Enviamos al servidor el mensaje para que lo mande como leido
        const url = ('php/leerMensaje.php');
        //para enviar los datos creamos un FormData e introducimos los datos a enviar
        let data = new FormData()
        data.append('idMensaje', id);
        var req = new XMLHttpRequest()
        req.open('POST', url, true)
        req.onreadystatechange = function(aEvt) {
            if (req.readyState == 4) {
                if (req.status == 200) {
                    evt.target.setAttribute("src", "./assets/img/iconos/emailabierto.svg");
                    const contenido = JSON.parse(req.responseText);
                    let asunto = contenido['asunto'];
                    let origen = contenido['idOrigen'];
                    let destinatario = contenido['idDestinatario'];
                    let mensaje = contenido['mensaje'];
                    if (evt.target.classList.contains("leer")) {
                        contenedorAsuntoRecibido.innerHTML = asunto;
                        contenedorAsuntoEnviado.innerHTML = asunto;
                        contenedorMensajeRecibido.innerHTML = mensaje;
                        contenedorMensajeEnviado.innerHTML = mensaje;
                        contenedorDestinatarioRecibido.innerHTML = "Enviado por " + origen;
                        contenedorDestinatarioEnviado.innerHTML = "Enviado a " + destinatario;
                    }
                    if (evt.target.classList.contains("responder")) {
                        contenedorAsuntoResponder.innerHTML = "RE: " + asunto;
                        contenedorDestinatarioResponder.innerHTML = "Enviado a " + origen;
                        contenedorAsuntoResponderHidden.value = "RE: " + asunto;
                        contenedorDestinatarioResponderHidden.value = contenido['codigo'];
                    }
                }
            }
        }
        req.send(data);
    }
    if (evt.target.classList.contains("closeModal")) {
        contenedorAsuntoRecibido.innerHTML = "";
        contenedorAsuntoEnviado.innerHTML = "";
        contenedorMensajeRecibido.innerHTML = "";
        contenedorMensajeEnviado.innerHTML = "";
        contenedorDestinatarioRecibido.innerHTML = "Enviado por ";
        contenedorDestinatarioEnviado.innerHTML = "Enviado a ";
    }
}

document.onclick = leerMensaje;
if (typeof comprobarMensajes === 'function') {
    comprobarMensajes();
}