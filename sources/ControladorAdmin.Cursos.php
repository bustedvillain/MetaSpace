<?php include("Funciones.php");
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 1 de Noviembre del 2013
 * Realiza consultas retornado resultados en objetos JSON
 */
if($_POST){
    //Consulta que desea realizar
    $consulta = $_POST["consulta"];
    $atributo=$_POST["atributo"];
    switch($consulta){
        case "validaNombreCurso":
            echo validarNombreCursoJSON($atributo);
            break;        
        case "validaClaveCurso":
            echo validarClaveCursoJSON($atributo);
            break;
        case "validaNombreCorto":
            echo validarNombreCortoJSON($atributo);
            break;  
        case "consultaInfoCurso":
            echo consultaInfoCursoJSON($atributo);
            break;
        case "consultaInfoCursoAbierto":
            echo consultaCursoAbiertoJSON($atributo);
    }
    
}
?>
