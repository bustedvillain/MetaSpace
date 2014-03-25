<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 7 de Noviembre del 2013
 */
if ($_GET["idRelacion"]) {
    $idRelacion = __($_GET["idRelacion"]);
    $idCursoAbierto= $_GET["id"];
    $mensaje = "";

    if (borrarRelacionTutorCursoAbierto($idRelacion)) {
        $mensaje = <<<MSJ
             <h1>Tutor removido del curso satisfactoriamente</h1>
             <img src="../img/ok.png"/> 
MSJ;
    } else {
        $mensaje = <<<MSJ
             <h1 class="text-error">El tutor no pudo ser removido del curso</h1>
             <h2 class="text-warning">Verifique que haya al menos un Coordinador de tutores en el grupo.</h2> 
MSJ;
    }
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
                <ul class="breadcrumb">
                    <li><a href="cursosAbiertos.php">Cursos Abiertos</a><span class="divider">/</span></li>
                    <li><a href="verTutoresCurso.php?id=<?php echo $idCursoAbierto; ?>">Tutores en el Curso</a><span class="divider">/</span></li>
                    <li class="active">Remover Tutor de Curso</li>
                </ul>
                <!-- Main hero unit for a primary marketing message or call to action -->
                <div class="hero-unit">
                    <?php echo $mensaje; ?>
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