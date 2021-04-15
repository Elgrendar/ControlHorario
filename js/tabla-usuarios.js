/**
 * @fileoverview javascript que usamos en la tabla de usuarios
 * @version      1.0
 * @author       Rafa Campanero <info@rafacampanero.es>
 * @copyright    rafacampanero.es
 */

const tablaUsuarios = document.getElementById('tablaUsuarios');
const tablaCampos = ['Seleccionado', 'Nombre', 'Apellidos', 'Estado', 'Opciones'];
const agregarUsuario = document.getElementById('agregar_usuario');
const formulario = document.getElementById('usuario');



//Ponemos el evento de escucha en todos los check de los usuarios mostrados en la tabla
function activarChecks() {
    let checkbox = document.getElementsByName("usuariosCheck");
    checkbox.forEach(usuario => {
        usuario.addEventListener('click', function() {
            if (usuario.checked == 1) {
                desmarcarTodosCheck(checkbox);
                usuario.checked = 1;
                mostrarOpciones(usuario);
            } else {
                mostrarOpciones(usuario);
            }
        })
    });
}

//Ponemos el evento de escucha en todos los botones de borrar usuarios mostrados en la tabla
function activarBotonesBorrar() {
    let botones = document.getElementsByClassName('baja')

    Array.from(botones).forEach(function(boton) {
        boton.addEventListener('click', function(e) {
            e.preventDefault();
            id = parseInt(boton.id.replace('baja-', ''));
            if (confirm("¿Seguro que quieres borrar este usuario?Esto borrará todos los registros del usuario en la BBDD ")) {
                borrarUsuario(id);
            } else {
                let checkbox = document.getElementsByName("usuariosCheck");
                desmarcarTodosCheck(checkbox);
            }

        })
    });
}

//Poner todos los check como no marcados recibidos en el array 
function desmarcarTodosCheck(checks) {
    checks.forEach(check => {
        check.checked = 0;
        mostrarOpciones(check);
    })

}

//Poner todos los check como marcados recibidos en el array 
function marcarTodosCheck(checks) {
    checks.forEach(check => {
        check.checked = 1;
    })
}

// Esta opcion muestra la opción de dar de baja si el check del usuario está marcado o
// editar y modificar si está desmarcado el usuario recibido como parametro.
function mostrarOpciones(usuario) {
    opciones = usuario.parentElement.parentElement.lastElementChild.children;
    if (usuario.checked == 1) {
        for (let opcion of opciones) {
            if (opcion.classList.contains('baja')) {
                opcion.classList.remove('hidden');
            } else {
                opcion.classList.add('hidden');
            }
        }
    } else {
        for (let opcion of opciones) {
            if (opcion.classList.contains('editar')) {
                opcion.classList.remove('hidden');
            } else {
                opcion.classList.add('hidden');
            }
        }
    }
}

//Mostramos los usuarios en la tabla recogidos del servidor por json.
function MostrarUsuarios() {
    //Borramos lo que contiene la tablaUsuarios
    tablaUsuarios.innerHTML = '';
    let cadenahtml = '';
    //Agregamos el encabezado a la tabla a la cadena

    tablaUsuarios.innerHTML = cadenahtml;

    cadenahtml += '<thead><tr>';

    tablaCampos.forEach(function(campo) {
        cadenahtml += '<th>' + campo + '</th>';
    });
    cadenahtml += '</tr></thead>';

    //Pedimos los usuarios al servidor
    const url = ('php/mostrarUsuarios.php');

    fetch(url)
        .then(response => (response.ok) ? Promise.resolve(response) : Promise.reject(new Error('Failed to load')))

    .then(response => response.json())

    .then(data => {
            allUsersData = data
                //Creamos el tbody y lo incorporamos
            cadenahtml += '<tbody>';
            for (const user of data) {

                cadenahtml += '<tr id="' + user['idUsuario'] + '">';
                cadenahtml += '<td>';
                cadenahtml += '<input type="checkbox" name="usuariosCheck"';
                cadenahtml += '</td>';

                //celda del nombre
                cadenahtml += '<td>' + user['nombre'] + '</td>';


                //Celda del Apellido
                cadenahtml += '<td>';
                if (user['apellido2'] == null) {
                    cadenahtml += user['apellido1'];
                } else {
                    cadenahtml += user['apellido1'] + " " + user['apellido2'];
                }
                cadenahtml += '</td>';

                //Celda del Estado
                cadenahtml += '<td><div ';
                if (user['trabajando'] == 1) {
                    cadenahtml += 'class="estado trabajando">';
                } else if (user['trabajando'] == 0) {
                    cadenahtml += 'class="estado notrabajando">';
                }
                cadenahtml += '</div></td>';

                //Celda de Opciones
                cadenahtml += '<td><a href="welcome.php?pagina=editar usuario&id=' + user['idUsuario'] + '" id="editar-' + user['idUsuario'] + '" class="editar opcion">';
                cadenahtml += '<img src="./assets/img/iconos/edit.svg" alt="Editar usuario" title="Editar usuario" class="icono"></a>';

                cadenahtml += '<a href="#" id="baja-' + user['idUsuario'] + '" class="baja opcion hidden">';
                cadenahtml += '<img src="./assets/img/iconos/trash.svg" alt="Borrar usuario" title="Borrar usuario" class="icono"></a></td>';

                //incluimos el tr en el tbody
                cadenahtml += '</tr>';
            }
            cadenahtml += '</tbody>';
            tablaUsuarios.innerHTML = cadenahtml;
            //Despues de creada la tabla activamos los checks
            activarChecks();
            //También activamos los eventos en los botones de borrar usuarios
            activarBotonesBorrar();
        })
        .catch((error) => console.log(`Error: ${error.message}`))
}

//Esta funcion borra el usuario recibido por parametro identificado por su id y en caso correcto 
//redirige a la página de usuarios.
function borrarUsuario(id) {
    //Pedimos los usuarios al servidor
    const url = ('php/BorrarUsuario.php');
    //para enviar los datos creamos un FormData e introducimos los datos a enviar
    let data = new FormData()
    data.append('id', id);
    var req = new XMLHttpRequest()
    req.open('POST', url, true)
    req.onreadystatechange = function(aEvt) {
        if (req.readyState == 4) {
            if (req.status == 200) {
                window.location = "../welcome.php?pagina=usuarios";
            }
        }
    }
    req.send(data);
}
//rellenamos la tabla de los usuarios;
MostrarUsuarios();