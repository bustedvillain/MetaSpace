<?php 
//Control de cambios #6
//Autor:Omar Nava
//Objetivo: Descarga de datos 
//03-ene-2014
include("../sources/Funciones.php");
ob_start();
/*
 * Fecha de creación: 29 de Noviembre del 2013
 * Autor: José Manuel Nieto Gómez
 * Objetivo: Recibe el tipo de usuario que desea descargar datos
 */

if($_GET){
    if(isset ($_GET["formato"])){
        $formato = $_GET["formato"];
    }  else {
        $formato = "csv";
    }
    if($_GET["tipo"] != "grupo"){
        $matriz = getMatrizDeUsuarios($_GET["tipo"]);
    }
    switch($_GET["tipo"]){
        //Alumno Estudiante
        case "0":
            descargarCSV("Alumnos Estudiantes", $matriz, ";", "Alumnos Estudiantes");
            break;
        //Tutores        
        case "1":
            if($formato == "csv"){
                descargarCSV("Tutores", $matriz, ";", "Tutores");
            }else{
                descargaXLS("Tutores", $matriz, "Tutores");
            }
            break;
        //Profesores de aula
        case "2":
            if($formato == "csv"){
                descargarCSV("Profesores de Aula", $matriz , ";", "Profesores de Aula");
            }else{
                descargaXLS("Profesores de Aula", $matriz ,  "Profesores de Aula");
            }
            break;
        //Padres
        case "3":
            if($formato == "csv"){
                descargarCSV("Padres de Familia", $matriz, ";", "Padres de Familia");
            }else{
                descargaXLS("Padres de Familia", $matriz,  "Padres de Familia");
            }
            break;
        //Gestores
        case "4":
            if($formato == "csv"){
                descargarCSV("Gestores de Contenido", $matriz, ";", "Gestores de Contenido");
            }else{
                descargaXLS("Gestores de Contenido", $matriz, "Gestores de Contenido");
            }
            break;
        //Administradores
        case "5":
            if($formato == "csv"){
                descargarCSV("Administradores", $matriz, ";", "Administradores");
            }else{
                descargaXLS("Administradores", $matriz,  "Administradores");
            }
            break;
        //Alumnos Profesionistas
        case "6":
            if($formato == "csv"){
                descargarCSV("Alumnos Profesionistas", $matriz, ";", "Alumnos Profesionistas");
            }else{
                descargaXLS("Alumnos Profesionistas", $matriz, ";", "Alumnos Profesionistas");
            }
            break;
        //inicia control de cambios #6
        case "grupo":
            descargaDatosPorGrupo($_GET["idGrupo"],$_GET["formato"]);
            break;
    }
    
ob_end_flush();     
}

function descargaDatosPorGrupo($idGrupo, $tipo){
    $query = new Query("SG");
    $query->sql="
        SELECT d.id_datos_personales
        FROM grupo_alumno ga
        JOIN alumnos a
            ON a.id_alumno = ga.id_alumno
        JOIN datos_personales d
            ON d.id_datos_personales = a.id_datos_personales
        WHERE id_grupo = $idGrupo
    ";
    $arrIds = $query->select("obj");
    $arrId = array();
    foreach ($arrIds as $a){
        array_push($arrId, $a->id_datos_personales);
    }
    $matrizU = getMatrizDeUsuarios(0, null, $arrId);
//    var_dump($matrizU);
    switch($tipo){
        case "csv":
            descargarCSV("Alumnos", $matrizU, ",");
            break;
        case "xls":
            descargaXLS("Alumnos", $matrizU, "Alumnos");
//            generaExcel("huaaaa", $matrizU);
            break;
    }
}
//termina control de cambios #6
?>
