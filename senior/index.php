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
            <!--CHANGE CONTROL 1.1.0
            FECHA DE MODIFICACION: 21 DE MAYO DEL 2014
            AUTOR: JOSE MANUEL NIETO GOMEZ
            OBJETIVO: AJUSTES ESTETICOS-->
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Sistema Integral de Administraci&oacute;n <br/> Softmeta-A</h1>
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
