<?php
require_once('./inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}
?>
<section class="content">
    <div id="tabs">
        <span class="diana" id="uno"></span>
        <a href="#uno" class="tab-e">Bandeja de Entrada</a>
        <span class="diana" id="dos"></span>
        <a href="#dos" class="tab-e">Elementos Enviados</a>
        <span class="diana" id="tres"></span>
        <a href="#tres" class="tab-e">Redactar Mensaje</a>
        <div id="panel1">
            <div id="bandejadeentrada">
                <table id="tablaentrada">
                    <thead>
                        <tr>
                            <td>Recibido el...</td>
                            <td>Enviado por...</td>
                            <td>Asunto</td>
                            <td>Opciones</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $mensajes = mostrarMensajes(1);
                        foreach ($mensajes as $mensaje) {
                            if ($mensaje['leido'] == 0) {
                                $estado = "noleido";
                            } else {
                                $estado = "";
                            }
                            echo '<tr id="' . $mensaje["idMensaje"] . '" class="' . $estado . '"><td>' . $mensaje["fechaEnvio"] . '</td><td>' . obtenerNombre($mensaje["idOrigen"]) . '</td><td>' . $mensaje["asunto"] . '</td><td><a href="#responder"><img src="./assets/img/iconos/responder.svg" class="icono responder" alt="Responder Mensaje" Title="Responder Mensaje"></a> <a href="#recibido">';
                            if ($mensaje['leido'] == 0) {
                                echo '<img src="./assets/img/iconos/emailcerrado.svg" class="icono leer" alt="Leer mensaje" Title="Leer Mensaje">';
                            } else {
                                echo '<img src="./assets/img/iconos/emailabierto.svg" class="icono leer" alt="Leer mensaje leido" Title="Leer mensaje leido">';
                            }
                            echo '</a></td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="panel2">
            <div id="enviados">
                <table id="tablaentrada">
                    <thead>
                        <tr>
                            <td>Enviado el...</td>
                            <td>Enviado a...</td>
                            <td>Asunto</td>
                            <td>Opciones</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $mensajes = mostrarMensajes(2);
                        foreach ($mensajes as $mensaje) {
                            if ($mensaje['leido'] == 0) {
                                $estado = "noleido";
                            } else {
                                $estado = "";
                            }
                            echo '<tr id="' . $mensaje["idMensaje"] . '" class="' . $estado . '"><td>' . $mensaje["fechaEnvio"] . '</td><td>' . obtenerNombre($mensaje["idDestinatario"]) . '</td><td>' . $mensaje["asunto"] . '</td><td><a href="#enviado"><img src="./assets/img/iconos/emailabierto.svg" class="icono leer" alt="Leer mensaje leido" Title="Leer mensaje leido"></a></td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="panel3">
            <div id="redactar2">
                <a href='welcome.php?pagina=mensajes#redactar' class="form__row--link">Mensaje Nuevo</a>

            </div>
        </div>
    </div>
    <div id="recibido" class="modalmask">
        <div class="modalbox-small rotate">
            <a href="#uno" title="Close" class="closeModal">X</a>
            <h2 id="mostrarAsuntoRecibido"></h2>
            <p id="mostrarDestinatarioRecibido"></p>
            <p id="mostrarMensajeRecibido"></p>
        </div>
    </div>
    <div id="enviado" class="modalmask">
        <div class="modalbox-small rotate">
            <a href="#dos" title="Close" class="closeModal">X</a>
            <h2 id="mostrarAsuntoEnviado"></h2>
            <p id="mostrarDestinatarioEnviado"></p>
            <p id="mostrarMensajeEnviado"></p>
        </div>
    </div>
    <div id="redactar" class="modalmask">
        <div class="modalbox-big rotate">
            <a href="#tres" title="Close" class="closeModal">X</a>
            <form id="mensajenuevo" autocomplete="off" class="form--user" method="POST" action="./php/enviarmensaje.php">
                <div class="form-row">
                    <label for="destinatario">Destinatario</label>
                    <select name="destinatario" id="destinatario" required>
                        <option value="">Elija destinatario...</option>
                        <?php echo rellenarDestinatarios(); ?>
                    </select>
                </div>
                <div class="form-row">
                    <label for="asunto">Asunto</label>
                    <input type="text" id="asunto" name="asunto" maxlength="50" required>
                </div>
                <div class="form-row">
                    <label for="textomensaje">Mensaje</label>
                </div>
                <div class="form-row">
                    <textarea id="mensajetexto" name="mensajetexto" class="textarea--big" placeholder="Escribe tu mensaje..."></textarea>
                </div>
                <div class="form-row">
                    <a href='welcome.php?pagina=mensajes#uno' class="form__row--link">Volver</a>
                    <button type="reset" class="form__row--button">Limpiar</button>
                    <button type="submit" class="form__row--button">Enviar</button>
                </div>
            </form>
        </div>
    </div>
    <div id="responder" class="modalmask">
        <div class="modalbox-small rotate">
            <a href="#dos" title="Close" class="closeModal">X</a>
            <form id="respondermensaje" autocomplete="off" class="form--user" method="POST" action="./php/enviarmensaje.php">
                <input type="hidden" id="mostrarAsuntoResponderhidden" name="asunto"></input>
                <input type="hidden" id="mostrarDestinatarioResponderhidden" name="destinatario"></input>
                <h2 id="mostrarAsuntoResponder"></h2>
                <p id="mostrarDestinatarioResponder"></p>
                <textarea id="mostrarMensajeResponder" name="mensajetexto" class="textarea--big" placeholder="Escribe tu respuesta..."></textarea>
                <a href='welcome.php?pagina=mensajes#uno' class="form__row--link">Volver</a>
                <button type="reset" class="form__row--button">Limpiar</button>
                <button type="submit" class="form__row--button">Enviar</button>
            </form>
        </div>
    </div>
</section>