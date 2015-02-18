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
        <?php verificarSesionAdmnistrador()?>
    </head>
    <body>
        <!--Up bar-->
       <?php include("../template/menuAdmin.php"); ?>
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Reportes Visualizar</h1>
            </div>
            
            
             <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha Subida</th>
                        <th>Curso Abierto</th>
                        <th>Grupo</th>
                        <th>Tipo Reporte</th>
                        <th>Descripción</th>
                        <th>Alumno</th>
                        <th>XLS</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                  
                <?php generarListadoReportesScorm()
                ?>
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Fecha Subida</th>
                        <th>Curso Abierto</th>
                        <th>Grupo</th>
                        <th>Tipo Reporte</th>
                        <th>Descripción</th>
                        <th>Alumno</th>
                        <th>XLS</th>
                        <th>PDF</th>
                    </tr>
                </tfoot>
            </table>
            
            
            
                
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