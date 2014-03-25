<?php
include("../sources/Funciones.php");
verificarSesionSenior();
if ($_POST) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
            <?php include("../template/heads.php"); ?>
        </head>

        <body>
            <!--Up bar-->
            <?php include("../template/menuSr.php"); ?>

            <div class="container">

                <!-- Main hero unit for a primary marketing message or call to action -->
                <div class="hero-unit">
                    <h1>Reportes</h1>
                    <div class="progress progress-striped active" id="progress">
                        <div class="bar" style="width:100%;">
                            <p>Validando Datos...</p>
                        </div>
                    </div>
                    <!--<img src="../img/ok.png"/>-->
                </div>
                <legend>Proceso</legend>
                <div class="well" id="procesoMasiva" style="overflow:auto; width:97%; height: 300px">                    
                  <?php 
                         $fechas = array($_POST['fechaInicio'],$_POST['fechaFin']);
                        echo procesoReporte($_FILES["excel"],
                                            $_POST['tipo_reporte'],
                                            $_POST['idGrupo'],
                                            $_POST['idCursoAbierto'],
                                            $_POST['idUnidad'],
                                            $_FILES["excel2"],
                                            $_POST['ignorarFILE'],
                                            $fechas);
                  ?>
                </div>

                


            </div> <!-- /container -->

            <!-- Le javascript
            ================================================== -->
            <!-- Placed at the end of the document so the pages load faster -->
            <?php include("../template/bootstrapAssets.php"); ?>

        </body>
    </html>
    <?php
} else {
    header("Location:reportes_1.php");
}
?>