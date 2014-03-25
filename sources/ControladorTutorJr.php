<?php
/*
 * Autor: Héctor Morales Palma
 * Fecha de Creación: 14 de Octubre del 2013
 * Objetivo: Controlador para retornar información en JSON de un alumno en especifico
 */
require_once './Funciones.php';
if($_POST)
{
        $instruccion = $_POST["ins"];
        switch($instruccion)
        {
            case "verPerfilAlumno":
                echo obtenerJsonAlumno($_POST['idAlumno']);
                break;
        }    
}
?>
