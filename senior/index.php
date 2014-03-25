<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/heads.php");?>
        <?php include("../template/metasDatatables.php");
         include("../template/cssDatePicker.php");
         include("../sources/Funciones.php");
         verificarSesionSenior();?>
        
    </head>

    <body>
        <!--Up bar-->
        
        <?php 
        //echo var_dump($_SESSION);
       
        include("../template/menuSr.php");?>
        

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Sistema de Gesti&oacute;n Integral <br/> Softmeta-A</h1>
                <p>Bienvenido Tutor Senior</p>
            </div>

            
            
            

            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
        <?php include("../template/jsDatePicker.php"); ?>
        <?php include("jquerySenior.php"); ?>
        

    </body>
</html>
