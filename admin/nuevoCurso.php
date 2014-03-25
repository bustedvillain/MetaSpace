<?php include("../sources/Funciones.php");  verificarSesionAdminOGestor();?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/metasDatatables.php"); ?>
        <?php include("../template/heads.php"); ?>
    </head>

    <body>
        <!--Up bar-->
        <?php include("../template/menuAdmin.php"); ?>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Vincular Curso</h1>
                <p>A continuaci&oacute;n se muestran los cursos creados en Moodle, seleccione uno para vincularlo al sistema de gesti&oacute;n.</p>
            </div>  
            
            <ul class="breadcrumb">
                <li class="active">Nuevo Curso</li>
            </ul>
            <p class="text-info">
                *Solamente aparecen los cursos que fueron dados de alta con el formato de "topicos".
            </p>
            
            <table cellpadding="0" cellspacing="0" border="0" class="display tabla">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre del Curso</th>
                        <th>Nombre Corto del Curso</th>
                        <th>Acci&oacute;n</th>
                    </tr>
                </thead>
                <tbody>
                    <?php consultaCursosMoodle(); ?>
                </tbody>
            </table>

            

            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
    </body>
</html>

