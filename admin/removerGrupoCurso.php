<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 22 de Noviembre del 2013
 */
if ($_GET["id_rel_curso_grupo"]) {
    $idRelacion = __($_GET["id_rel_curso_grupo"]);
    $idCursoAbierto= $_GET["id_curso_abierto"];
    $mensaje = "";

    if (borrarRelacionGrupoCursoAbierto($idRelacion)) {
        $mensaje = <<<MSJ
             <h1>Grupo removido del curso satisfactoriamente</h1>
             <h2 class="text-warning">El progreso de los alumnos y las relaciones de los tutores al grupo tambien fueron eliminados.</h2>
             <img src="../img/ok.png"/> 
MSJ;
    } else {
        $mensaje = <<<MSJ
             <h1 class="text-error">El grupo no pudo ser removido del curso</h1>            
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
                    <li><a href="verGruposCurso.php?id=<?php echo $idCursoAbierto; ?>">Grupos en el Curso</a><span class="divider">/</span></li>
                    <li class="active">Remover Grupo de Curso</li>
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