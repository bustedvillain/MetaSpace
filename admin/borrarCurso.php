<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 6 de Noviembre del 2013
 */
if ($_GET) {
    $id=__($_GET["id"]);

//    editaDatos("cursos", "status=0", "id_curso=$id");
    
    /**
     * CHANGE CONTROL 0.99.8
     * FECHA DE MODIFICACIÓN: 17 DE ENERO DEL 2014
     * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
     * OBJETIVO: ELIMINAR DEFINITIVAMENTE UN CURSO, A SU VEZ SE 
     * MODIFICARON LAS LLAVES FORANEAS PARA ELIMINARA EN CASCADA LAS
     * RELACIONES
     */
    borrarDatos("cursos", "id_curso=$id");
    
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
                    <h1>Curso eliminado correctamente</h1>
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