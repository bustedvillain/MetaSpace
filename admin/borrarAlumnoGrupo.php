<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 24 de Noviembre del 2013
 * Objetivo: Script que recibe el id de de relacion grupo_alumno que se va 
 * a borrar de un grupo, y se verifica los progresos que el alumno
 * pudiera tener en diferentes cursos para borrarlo
 */
if($_GET){
    $idGrupoAlumno=$_GET["id_grupo_alumno"];
    $response = vaciarRelacionGrupoAlumno(NULL, NULL, $idGrupoAlumno);
    
    switch($response){
        case "deshabilitoProgreso":
            $msj = <<<MSJ
                <h3 class='text-warning'>Se detecto que el alumno se encontraba enrolado con este grupo a uno o más cursos abierto, por lo tanto se procedió a eliminar el progreso(calificaciones) que tenía dentro de dichos cursos.</h3>
MSJ;
            break;
        case "boroRelacionSinProgreso":
            $msj = <<<MSJ
                <h3 class="text-info">El alumno aun no tenía progreso dentro de algún curso enrolado.</h3>
MSJ;
            break;
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

                <!-- Main hero unit for a primary marketing message or call to action -->
                <div class="hero-unit">
                    <h1>Alumno Removido del Grupo Correctamente</h1>
                    <?php echo $msj; ?>
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