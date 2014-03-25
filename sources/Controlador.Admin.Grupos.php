<?php

include("Funciones.php");

/*
 * Autor: José Manuel Nieto Gómez
 * Fecha de Creación: 08 de Noviembre del 2013
 * Objetivo: Controlador par recibir consultas via JSON
 */

if ($_POST) {
    //Consulta que desea realizar
    $consulta = $_POST["consulta"];
    $atributo = $_POST["atributo"];

    switch ($consulta) {
        case "consultaInfoGrupo":
            echo getInfoGrupoJSON($atributo);
            break;
        case "verificaNombreGrupo":
            break;
        case "getGruposPorEscuela":
            echo getGruposPorEscuelaJSON($atributo);
            break;
        case "getGruposSeleccionados":
            echo getGruposSeleccionadosJSON($atributo);
            break;
        case "consultaNombreGrupo":
            echo consultaNombreGrupoJSON($atributo);
            break;
        case "consultaClaveGrupo":
            echo consultaClaveGrupoJSON($atributo);
            break;
        case "consultaNombreGrupoEditando":
            echo consultaNombreGrupoJSON($atributo, $_POST["idGrupo"]);
            break;
        case "eliminaGrupoDeCurso":
            $string = substr($_POST["atributo"], 1, strlen($_POST["atributo"])-2);
            $arrGrupos = explode(",",$string);
            $idCursoAbierto = $_POST["idCursoAbierto"];
            eliminaGrupoDeCurso($arrGrupos, $idCursoAbierto);
            echo 'Grupos eliminados satisfactoriamente';
            break;
    }
}
?>
