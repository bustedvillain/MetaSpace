
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/metasDatatables.php"); ?>
        <?php include("../template/heads.php"); ?>
        
        <!--Librerías de funcionamiento-->
        <?php include("../sources/Funciones.php"); ?>
        <?php verificarSesionSenior()?>

    </head>

    <body>
        <!--Up bar-->
        <?php include("../template/menuSr.php"); ?>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Tutores Junior Asignados</h1>
                
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
                    </tr>
                </thead>
                <tbody>
                    <?php tablaTutoresJr();?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>ID Tutor</th>
                        <th>Nombre</th>
                        <th>Apellido(s)</th>

                    </tr>
                </tfoot>
            </table>
            <?php include("verTutorJr.php"); ?>
            <?php include("verActividadTutor.php"); ?>
            <?php include("editarAlumno.php"); ?>



            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
        <?php include("jquerySenior.php"); ?>

    </body>
</html>
