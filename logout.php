<?php
require_once './sources/Funciones.php';

if(esJr() || esCoordinador() || esSenior())
    detener_accesoTutor();

if($_SESSION["idDatosPersonales"])
{
    registraSalidaSitio($_SESSION["idDatosPersonales"]);
}
//session_unset($_COOKIE);
//unset($_COOKIE);
setcookie ("smNombre", "", time() - 3600, "/");
setcookie ("smTipo", "", time() - 3600, "/");
setcookie ("smIdDatosPersonales", "", time() - 3600, "/");
setcookie ("smIdPorTabla", "", time() - 3600, "/");
session_unset($_SESSION);
session_destroy();
header('Location:index.php');
?>
