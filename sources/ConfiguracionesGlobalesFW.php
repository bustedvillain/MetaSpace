<?php
/**
 * Documento creado para incluir scripts a toda la fw
 */
if(!esAlumno() && !esCoordinador() && !esSenior() && !esJr()){
    echo '<script src="../../js/jquery-1.7.min.js"></script> ';
}
?>

<script src="../../js/jquery.js"></script> 
<?php
imprimeScriptDeTiempoMaxSesion();
?>
