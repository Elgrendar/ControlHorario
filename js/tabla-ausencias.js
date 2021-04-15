/**
 * @fileoverview javascript que usamos en la tabla de ausencias
 * @version      1.0
 * @author       Rafa Campanero <info@rafacampanero.es>
 * @copyright    rafacampanero.es
 */

const tablaAusencias = document.getElementById('tablaAusencias');
const tablaCampos = ['Trabajador', 'Solicitud', 'Ausencia', 'Inicio', 'Fin', 'Documentación'];

//Mostramos las ausencias en la tabla recogidas del servidor por json.
function MostrarAusencias() {
    //Borramos lo que contiene la tablaUsuarios
    tablaAusencias.innerHTML = '';
    let cadenahtml = '';
    //Agregamos el encabezado a la tabla a la cadena

    tablaAusencias.innerHTML = cadenahtml;

    cadenahtml += '<thead><tr>';

    tablaCampos.forEach(function(campo) {
        cadenahtml += '<th>' + campo + '</th>';
    });
    cadenahtml += '</tr></thead>';

    //Pedimos los usuarios al servidor
    const url = ('php/mostrar-ausencias.php');

    fetch(url)
        .then(response => (response.ok) ? Promise.resolve(response) : Promise.reject(new Error('Failed to load')))

    .then(response => response.json())

    .then(data => {
            allData = data
                //Creamos el tbody y lo incorporamos
            cadenahtml += '<tbody>';
            for (const ausencia of data) {

                cadenahtml += '<tr id="' + ausencia['idUsuario'] + '">';

                //celda del nombre
                if (ausencia['apellido2'] === null) {
                    cadenahtml += '<td>' + ausencia['nombre'] + ' ' + ausencia['apellido1'] + '</td>';
                } else {
                    cadenahtml += '<td>' + ausencia['nombre'] + ' ' + ausencia['apellido1'] + ' ' + ausencia['apellido2'] + '</td>';
                }

                //Celda de la solicitud
                cadenahtml += '<td>' + ausencia['idSolicitud'] + '</td>';

                //Celda del Ausencia
                cadenahtml += '<td>' + ausencia['idAusencia'] + '</td>';

                //Celda de fecha inicio
                cadenahtml += '<td>' + ausencia['f_inicio'] + '</td>';

                //Celda fecha Fin
                cadenahtml += '<td>' + ausencia['f_fin'] + '</td>';

                //Celda Documentación
                cadenahtml += '<td><a href="../assets/archivos/justificantes/' + ausencia['fichero'] + '" target="_blank"><img src="../assets/img/iconos/view.svg" class="icono" alt="Visualizar Justificante" title="Visualizar Justificante"</a></td>';

                //incluimos el tr en el tbody
                cadenahtml += '</tr>';
            }
            cadenahtml += '</tbody>';
            console.log("Hola");
            tablaAusencias.innerHTML = cadenahtml;

        })
        .catch((error) => console.log(`Error: ${error.message}`))
}

MostrarAusencias();