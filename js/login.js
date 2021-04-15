/**
 * @fileoverview javascript que incorporamos a la pantalla del login
 * @version      1.0
 * @author       Rafa Campanero <info@rafacampanero.es>
 * @copyright    rafacampanero.es
 */

//Creamos todos los elementos

const loginButton = document.getElementById('loginButton')
const loginForm = document.getElementById('loginForm')
const resultado = document.getElementById('resultado');

//Ejecutamos la funcion a intervalos de un segundo
setInterval(actualizarTiempo, 1000);

//Ponemos a escuchar el click en el formulario del login.
loginForm.addEventListener('submit', (e) => {
    e.preventDefault()
    let email = document.getElementById('email').value
    let pass = document.getElementById('password').value
    let data = new FormData()

    data.append("email", email)
    data.append("pass", pass)

    const url = 'php/login.php'
    var req = new XMLHttpRequest()
    req.open('POST', url, true)
    req.onreadystatechange = function(aEvt) {
        if (req.readyState == 4) {
            if (req.status == 200) {
                r = req.responseText

                if (r === "true") {
                    window.location.href = "welcome.php"
                } else {
                    resultado.innerHTML = "El login no ha sido correcto."
                }
            }
        }
    }
    req.send(data)
})