<?php
require_once('./inc/funciones.php');
if (comprobarsesion()) {
    header("location:../index.php");
}
?>
<section class="content">
    <form id="emitirinforme" autocomplete="off" class="form--user" method="POST" target="_blank" action="./php/informes.php">
        <div class="form__row">
            <label for="diario">Informe diario</label>
            <input type="radio" name="informe" id="diario" value="diario" class="form__row--input" checked>
            <label for="mensual">Informe mensual</label>
            <input type="radio" name="informe" id="mensual" class="form__row--input" value="mensual">
        </div>
        <div class="form__row">
        <input type="date" name="dia" id="dia" class="form__row--input" value="<?php echo date("Y-m-d", strtotime(date("Y-m-d"))); ?>" max="<?php echo date("Y-m-d", strtotime(date("Y-m-d"))); ?>">
        <input type="month" name="mes" id="mes" class="form__row--input hidden"  value="<?php echo date("Y-m", strtotime(date("Y-m"))); ?>" max="<?php echo date("Y-m", strtotime(date("Y-m"))); ?>">
        </div>
        <div class="form__row">
            <a href='welcome.php' class="form__row--link">Volver</a>
            <button type="submit" class="form__row--button">Emitir</button>
        </div>
    </form>
</section>
<script type="text/javascript">
const calendariomes=document.getElementById("mes");
const calendariodia = document.getElementById("dia");
const mes=document.getElementById("mensual");
const dia = document.getElementById("diario");
mes.addEventListener('click', function(){
    calendariodia.classList.add("hidden");
    calendariomes.classList.remove("hidden");
});
dia.addEventListener('click', function(){
    calendariomes.classList.add("hidden");
    calendariodia.classList.remove("hidden");
});
</script>