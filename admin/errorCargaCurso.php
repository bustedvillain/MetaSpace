<?php include("../sources/Funciones.php"); verificarSesionAdminOGestor();?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/heads.php");?>
    </head>

    <body>
        <!--Up bar-->
        <?php include("../template/menuAdmin.php");?>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1 class="text-error">Error</h1>
                <h2 class="text-error">Ocurri&oacute; un error durante la carga de contenidos en el curso. La causa pudo haber sido una de las siguientes:</h2>
            </div>
            
            <?php echo html_entity_decode(urldecode($_GET["error"])); ?>


        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>

    </body>
</html>
