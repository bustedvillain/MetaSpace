<?php require_once '../sources/Funciones.php';?>
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
                <h1>Tutores Jr Asignados</h1>
            </div>
             <ul class="breadcrumb">
                <li><a href="index.php">Inicio</a> <span class="divider">/</span></li>
                <li class="active">Tutores Jr</li>
            </ul>
            
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <thead>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>ID Tutor</th>
                        <th>Nombre</th>
                        <th>Apellido(s)</th>
                        <th>Curso</th>
                        <th>Curso Abierto</th>
                    </tr>
                </thead>
                <tbody>
                   <?php tablaTutoresJunior(obtenerIDTabla()) ?>
                           
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>ID Tutor</th>
                        <th>Nombre</th>
                        <th>Apellido(s)</th>
                        <th>Curso</th>
                        <th>Curso Abierto</th>
                    </tr>
                </tfoot>
            </table>
            <?php include("../senior/verActividadTutor.php"); ?>
            <?php include("../senior/verTutorJr.php"); ?>

            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
        <?php include("../senior/jquerySenior.php"); ?>
    </body>
</html>