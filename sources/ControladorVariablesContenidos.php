<?php

include_once 'Funciones.php';
if ($_POST) {
    $funcion = $_POST["funcion"];
    switch ($funcion) {
        case "getMSValues":
            echo getMSValuesJSON($_POST["idAlumno"], $_POST["idElemento"], $_POST["contexto"], $_POST["variables"]);
            break;
        case "setMSValues":
            
            break;
    }
}
?>
