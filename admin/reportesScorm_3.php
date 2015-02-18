<?php require_once '../sources/Funciones.php';
$idTutor = null;
if(isset($_GET['idTutor'])){
    $idTutor=$_GET['idTutor'];
}

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
             if($_GET["userReport"]){
                $rep= $scorm->reporteAlumno($_GET["id"],$_GET["userReport"],$_GET["course"]);
                echo $rep;
             }else if($_GET["userReportGest"]){
                $rep= $scorm->reporteAlumnoGestion($_GET["id"],$_GET["userReportGest"],$_GET["course"]);
                echo $rep;
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