<?php include("../sources/Funciones.php"); verificarSesionAdmnistrador();?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/heads.php"); ?>        
    </head>

    <body>
        <!--Up bar-->
        <?php include("../template/menuAdmin.php"); ?>

        <div class="container">
            <!--CHANGE CONTROL 1.1.0
            FECHA DE MODIFICACION: 21 DE MAYO DEL 2014
            AUTOR: JOSE MANUEL NIETO GOMEZ
            OBJETIVO: AJUSTES ESTETICOS-->
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Sistema Integral de Administraci&oacute;n  <br/> Softmeta-A</h1>
                <p>Bienvenido al sitio principal para la administraci&oacute;n de Softmeta-A</p>
            </div>
   
            
             
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->
        <?php include  '../sources/iframeAseguraLogin.php';?>
        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>      

    </body>
</html>
