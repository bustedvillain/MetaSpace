<?php require_once '../sources/Funciones.php';?>
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
        
        <?php verificarSesionCoordinador()?>
    </head>
    <body>
        <!--Up bar-->
        <?php include("../template/menuCoordinador.php"); ?>
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Alumnos Asignados al Grupo</h1>
            </div>
            <ul class="breadcrumb">
                    <li><a href="index.php">Inicio</a> <span class="divider">/</span></li>
                    <li><a href="tutoresSeniorAsignados.php">Tutores Senior</a> <span class="divider">/</span></li>
                    <li><a href="gruposTutorSenior.php?idTutor=<?php echo ($_GET['idTutor']);?>">Grupos</a> <span class="divider">/</span></li>
                    <li class="active">Alumnos</li>
            </ul>
            <h3>Tutor: <b class="text-info"><?php echo nombreTutor($_GET['idTutor']);?></b></h3>
            <h3>Grupo: <b class="text-info"><?php echo getNomreGrupo($_GET['idGrupo']);?></b></h3>
               
            
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <thead>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>Nombre Usuario</th>
                        <th>Correo</th>
                        <th>Nombre</th>
                        <th>Apellido(s)</th>
                        
                    </tr>
                </thead>
                <tbody>
                    
                    <?php
                    tablaAlumnos($_GET['idGrupo'])
                    ?>
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>Nombre Usuario</th>
                        <th>Correo</th>
                        <th>Nombre</th>
                        <th>Apellido(s)</th>
                    </tr>
                </tfoot>
            </table>
            <?php include 'verAlumno.php';?>



            
                
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