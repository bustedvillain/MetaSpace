<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de creacion: 25 de Noviembre 2013
 * Objetivo: Borrar un curso
 */
if($_GET){
    $idGrupo=$_GET["id"];
    //editaDatos("grupo", "status=0", "id_grupo=$idGrupo");
    eliminaGrupo($idGrupo);
?>

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

                <!-- Main hero unit for a primary marketing message or call to action -->
                <div class="hero-unit">
                    <h1>Grupo eliminado correctamente</h1>
                    <img src="../img/ok.png"/>
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
    header("Location:index.php");
}
?>
