<?php
/*
 * Autor: Héctor Morales Palma
 * Fecha de Creación: 14 de Enero de 2014
 * Objetivo: Controlador para recibe y guarda en la Configuración de SG
 */
require_once './Funciones.php';
if($_POST)
{   
    
    guardar($_POST);
//    header('Location:../admin/configuracion.php');
    echo <<<hr
            <script type="text/javascript">
                alert("Configuración aplicada");
                window.location='../admin/configuracion.php';
            </script>
hr;
}
?>
