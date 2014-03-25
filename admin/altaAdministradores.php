<?php include("../sources/Funciones.php"); 
verificarSesionAdmnistrador();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/heads.php");?>
        <?php include("../template/cssDatePicker.php"); ?>
    </head>

    <body>
        <!--Up bar-->
        <?php include("../template/menuAdmin.php");?>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Alta de Administradores</h1>
                <p>Los campos marcados con (*) son obligatorios.</p>
            </div>

            <form action="gdaUsuario.php" method="POST">
                <fieldset>

                    <?php imprimeFormularioGeneral(); ?>
                    <input type="hidden" name="tipo_usuario" value="admin">
                    <hr>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </fieldset>
            </form>

            

            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?> 
        <?php include("../template/jsDatePicker.php"); ?>

    </body>
</html>
