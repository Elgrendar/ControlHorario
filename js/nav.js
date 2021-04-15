/**
 * @fileoverview javascript que incorporamos en la barra de navegación
 * @version      1.0
 * @author       Rafa Campanero <info@rafacampanero.es>
 * @copyright    rafacampanero.es
 */

//Ejecutamos la funcion a intervalos de un segundo
setInterval(actualizarTiempo, 1000);

//Comprobamos los mensajes leidos a intervalos de 10 segundos
comprobarMensajes();
setInterval(comprobarMensajes, 5000);
let menuboton = document.getElementById('menu-boton');
let menu = document.getElementById("main-menu").parentElement;

// Añadimos un evento de redimensión a la ventana y ejecutamos la función
window.addEventListener("resize", function() {
    comprobarWidthScreen();
})


// Añadimos el evento clic al botón del menu y ejecutamos la función
document.getElementById('menu-boton').addEventListener('click', function() {
    comprobarMenu();
})


/**
 * Comprobamos la resolución de la pantalla para mostrar u ocultar el menú responsive y su botón
 */
function comprobarWidthScreen() {
    if (window.screen.width < 748) {
        menuboton.classList.remove("activo");
        menu.classList.add("hidden");
    } else {
        menuboton.classList.add("activo");
        menu.classList.remove("hidden");
    }
}

/**
 * Comprobamos el estado del menu y de su botón y lo cambiamos al estado contrario
 */
function comprobarMenu() {
    if (menuboton.classList.contains("activo")) {
        menuboton.classList.remove("activo");
        menu.classList.add("hidden");

    } else {
        menuboton.classList.add("activo");
        menu.classList.remove("hidden");
    }
}

// Ejecutamos la función al cargar para saber en que pantalla estamos trabajando
comprobarWidthScreen();