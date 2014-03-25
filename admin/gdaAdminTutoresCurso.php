<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 14/11/2013
 * Guarda los tutores dentro de un curso
 */
if ($_POST) {
//    var_dump($_POST);
    $idCursoAbierto=$_POST["id_curso_abierto"];
    $id_grupos = explode(";", $_POST["id_grupos"]);    
    
    foreach ($id_grupos as $idGrupo) {
        if ($idGrupo != "") {
//            imprimeConsola("insertando tutores para el grupo:$idGrupo");
            $idRelacionCursoGrupo = consultaRelCursoGrupo($idCursoAbierto, $idGrupo);
            vaciaRelacionesCursoTutor($idRelacionCursoGrupo);
            insertarTutoresCursoAbierto("coordinadores", $_POST, $idRelacionCursoGrupo, $idGrupo, true);
            insertarTutoresCursoAbierto("seniors", $_POST, $idRelacionCursoGrupo, $idGrupo);
            insertarTutoresCursoAbierto("juniors", $_POST, $idRelacionCursoGrupo, $idGrupo);
        }
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
                    <li><a href="adminTutoresCursoAbierto.php?id=<?php echo $idCursoAbierto; ?>">Administrar Tutores</a><span class="divider">/</span></li>
                    <li class="active">Modificar Tutores</li>
                </ul>
                
                <!-- Main hero unit for a primary marketing message or call to action -->
                <div class="hero-unit">
                    <!--<h1>Tutores modificados correctamente</h1>-->
                    <h1>Edici&oacute;n Exitosa</h1>
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

