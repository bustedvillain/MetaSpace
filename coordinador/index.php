<?php require_once '../sources/Funciones.php';?>
<!--
//   Date             Modified by         Change(s)
//   2013-10-03         HMP                 1.0
-->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/heads.php"); ?>
        <?php include("../template/metasDatatables.php"); ?>
        
        <?php verificarSesionCoordinador();?>
    </head>

    <body>
        <!--Up bar-->
         <?php include("../template/menuCoordinador.php"); ?>
        <!--CHANGE CONTROL 1.1.0
        FECHA DE MODIFICACION: 21 DE MAYO DEL 2014
        AUTOR: JOSE MANUEL NIETO GOMEZ
        OBJETIVO: AJUSTES ESTETICOS-->
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Sistema Integral de Administraci&oacute;n <br/> Softmeta-A</h1>
                <p>Bienvenido Coordinador de Tutores</p>
            </div>
            
                
            <?php include("../template/footer.php"); ?>
      
        </div> <!-- /container -->
        <?php include("../senior/verTutorJr.php"); ?>
        
        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../senior/jquerySenior.php"); ?>
    </body>
</html>