<?php
require_once('./inc/funciones.php');
if (comprobarsesion()) : ?>
    <footer>
        <div>Programado por: <a href="https://rafacampanero.es" target="_blank">Rafael Campanero</a></div>
    </footer>
<?php else : ?>
    <footer>
        <div>Programado por: <a href="https://rafacampanero.es" target="_blank">Rafael Campanero</a> <span>V. <?php echo $version; ?></span></div>
    </footer>
<?php endif; ?>