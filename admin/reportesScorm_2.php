<?php require_once '../sources/Funciones.php';
include '../template/validacionesReportes.php';
/**
 * CHANGE CONTROL 1.1.0
 * FECHA DE MODIFICACION: 22 DE MAYO DE 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJETIVO: CAMBIOS ESTETICOS
 */
?>
<!--
//   Date             Modified by         Change(s)
//   2013-10-03         HMP                 1.0
-->

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/metasDatatables.php"); ?>
        <?php include("../template/heads.php"); ?>
        <?php verificarSesionAdmnistrador()?>
    </head>
    <body>
        <!--Up bar-->
       <?php include("../template/menuAdmin.php"); ?>

        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Generar Reportes SCORM</h1>
                <p>Reportes SCORM</p>
            </div>
             <?php
             require ("classScorm.php");
             $scorm= new Scorm();
             $query = new Query("SG");
             $query->sql="select id_curso_moodle from cursos where id_curso=".$_GET["idCurso"];
             $cursos = $query->select("arr");
             foreach($cursos as $idCurso){
                $cursoMoodle=$idCurso["id_curso_moodle"];
             }
             switch($_GET["tipoReporte"]){
                case 1:
                    $res=$scorm->buscaAlumno($_GET["idGrupo"]);
                    $result="<table cellpadding='0' cellspacing='0' border='0' class='display' id='example'>
                                <thead>
                                    <tr><th>Alumnos</th><th>Generar</th></tr>
                                </thead>
                                <tbody>";
                    foreach($res as $alumno){
                        $result.="<tr>
                                    <form action='reportesScorm_3.php' method = 'GET'>
                                        <td>".$alumno["nombre_pila"]." ".$alumno["primer_apellido"]." ".$alumno["segundo_apellido"]."</td>
                                        <td><button class='btn btn-primary' type='submit'>Generar</button>
                                            <input type='hidden' name='id' value = '".$alumno["correo"]."' />
                                            <input type='hidden' name='userReport' value = '".$alumno["nombre_usuario"]."' />
                                            <input type='hidden' name='course' value = '".$cursoMoodle."' />
                                    </form>
                                </tr>";
                    }
                    $result.="</tbody>
                                <tfoot>
                                    <tr>
                                        <th>Alumnos</th>
                                        <th>Generar</th>
                                    </tr>
                                </tfoot></table>";
                    echo $result;
                    break;
                case 2:
                    $res=$scorm->desempenoGrupal($_GET["idGrupo"]);
                    echo $res;
                    break;
                case 3:
                    $res=$scorm->buscaAlumnoGestion($_GET["idGrupo"]);
                    $result="<table cellpadding='0' cellspacing='0' border='0' class='display' id='example'>
                                <thead>
                                    <tr><th>Alumnos</th><th>Generar</th></tr>
                                </thead>
                                <tbody>";
                    foreach($res as $alumno){
                        $result.="<tr>
                                    <form action='reportesScorm_3.php' method = 'GET'>
                                        <td>".$alumno["nombre_pila"]." ".$alumno["primer_apellido"]." ".$alumno["segundo_apellido"]."</td>
                                        <td><button class='btn btn-primary' type='submit'>Generar</button>
                                            <input type='hidden' name='id' value = '".$alumno["correo"]."' />
                                            <input type='hidden' name='userReportGest' value = '".$alumno["nombre_usuario"]."' />
                                            <input type='hidden' name='course' value = '".$cursoMoodle."' />
                                    </form>
                                </tr>";
                    }
                    $result.="</tbody>
                                <tfoot>
                                    <tr>
                                        <th>Alumnos</th>
                                        <th>Generar</th>
                                    </tr>
                                </tfoot></table>";
                    echo $result;
                    break;
                case 4:
                    $res=$scorm->desempenoGrupalGestion($_GET["idGrupo"]);
                    echo $res;
                    break;
             }
                    ?> 
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
        <?php include '../jr/jqueryTutorJr.php';?>
        <?php include("../senior/jquerySenior.php"); ?>
        
    </body>
</html>