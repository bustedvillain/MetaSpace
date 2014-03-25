<?php require_once '../sources/Funciones.php';
$idTutor = null;
if(isset($_GET['idTutor'])){
    $idTutor=$_GET['idTutor'];
}
?>
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
        <?php verificarSesionCoordinador()?>
    </head>
    <body>
        <!--Up bar-->
       <?php include("../template/menuCoordinador.php"); ?>
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Reportes (Paso 1 de 2)</h1>
            </div>
             <?php
            tablaParaReportes(obtenerIDTabla());
                    ?> 
                    
            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
        <?php include '../jr/jqueryTutorJr.php';?>
        <?php include("../senior/jquerySenior.php"); ?>
        
        <!--<script type="text/javascript" src="../js/jqueryReportes.js"></script>-->
    </body>
</html>