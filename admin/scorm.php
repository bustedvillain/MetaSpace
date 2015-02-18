<?php

/**
 * CHANGE CONTROL 1.0
 * AUTOR: 
 * FECHA DE CREACIÓN: Octubre 2014
 * OBJETIVO: CAMBIOS ESTETICOS
 */
require ("classScorm.php");
$scorm= new Scorm();
if(isset($_POST['tipoReporte'])){
    switch($_POST['tipoReporte']){
        case 1:
            $res=$scorm->buscaAlumno($_POST['search']);
            $result="<table><tr><th>Alumnos</th><th></th></tr>";
            foreach($res as $alumno){
                $result="<tr><td>".$alumno["nombre_pila"]." ".$alumno["primer_apellido"]." ".$alumno["segundo_apellido"]."</td><td><button id='".$alumno["correo"]."' name='".$alumno["nombre_usuario"]."' class='userReport'>Ver</button></td></tr>";
            }
            $result.="</table>";
            echo $result;
            break;
        case 2:
            $grupo=$scorm->desempenoGrupal($_POST["grupo"]);
            echo $grupo;
            break;
        case 3:
            $res=$scorm->buscaAlumnoGestion($_POST['search']);
            $result="<table><tr><th>Alumnos</th><th></th></tr>";
            foreach($res as $alumno){
                $result="<tr><td>".$alumno["nombre_pila"]." ".$alumno["primer_apellido"]." ".$alumno["segundo_apellido"]."</td><td><button id='".$alumno["correo"]."' name='".$alumno["nombre_usuario"]."' class='userReportGest'>Ver</button></td></tr>";
            }
            $result.="</table>";
            echo $result;
            break;
        case 4:
            $grupoGest=$scorm->desempenoGrupalGestion($_POST["grupo"]);
            echo $grupoGest;
            break;
        default:
            return "ningún parametro enviado";
            break;
    }
}

if(isset($_POST["userCourse"])){
    $res=$scorm->muestraCursos($_POST["id"],$_POST["userCourse"]);
    $result="<table><tr><th>Cursos</th><th></th></tr>";
    foreach($res as $curso){
        $result.="<tr><td>".$curso["fullname"]."</td><td><button id='".$curso["id"]."' name='Curso' class='courseUser'>Ver</button></td></tr>";
    }
    $result.="
        <input type='hidden' name='user' id='user' value='".$_POST["userCourse"]."'/>
        <input type='hidden' name='id' id='id' value='".$_POST["id"]."'/>
    </table>";
    echo $result;
}


if(isset($_POST["userReport"])){
    $rep= $scorm->reporteAlumno($_POST["id"],$_POST["userReport"],$_POST["course"]);
    echo $rep;
}

if(isset($_POST["userReportGest"])){
    $rep= $scorm->reporteAlumnoGestion($_POST["id"],$_POST["user"]);
}

?>