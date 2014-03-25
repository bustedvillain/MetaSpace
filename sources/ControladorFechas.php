<?php
//Control de cambios #3
//Se crea el archivo con el case fecha Servidor

//Inicia control de cambios #3
include 'Funciones.php';
//var_dump($_POST);
if ($_POST) {
    
    if (!isset($_POST["ins"])) {
        header("Location: ../");
    }
    $ins = $_POST["ins"];

    switch ($ins) {
        case 'fechaServidor':
            $dias = 0;
            $meses = 0;
            $anyos = 0;
            if (isset($_POST["dias"])) {
                $dias = $_POST["dias"];
            }
            if (isset($_POST["meses"])) {
                $meses = $_POST["meses"];
            }
            if (isset($_POST["anyos"])) {
                $anyos = $_POST["anyos"];
            }
            echo fechaServidor($dias, $meses, $anyos);
            break;
    }
}
//Finaliza control de cambios #3
?>
