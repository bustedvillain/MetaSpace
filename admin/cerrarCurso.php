<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();

/*
 * CHANGE CONTROL 0.99.9 
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE CREACION: 23 DE ENERO DEL 2014
 * OBJETIVO: ELIMINAR UN CURSO ABIERTO
 */

/**
 * CHANGE CONTROL 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACIÓN: 21 DE MAYO DE 2014
 * OBJETIVO: CAMBIOS ESTETICOS
 */

if ($_GET) {
    $id = __($_GET["id"]);
    borrarDatos("cursos_abiertos", "id_curso_abierto=$id");
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
                    <h1>Curso cerrado correctamente</h1>
                    <img src="../img/ok.png"/><br>
                    <a href="cursosAbiertos.php" class="btn btn-success">Regresar</a>
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
