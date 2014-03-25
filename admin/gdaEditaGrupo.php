<?php include("../sources/Funciones.php");
 verificarSesionAdmnistrador();
/*
 * Autor: José Manuel Nieto Gómez
 * Fecha de Creación: 08 de Noviembre del 2013
 * Objetivo: Guardar la edición de un grupo
 */

if ($_POST) {
//    var_dump($_POST);

    $tipoGrupo = $_POST["grupo/tipo_grupo"];
    $idGrupo = $_POST["id_grupo"];
    $msj="";
    
    if ($tipoGrupo == "0") {
        //Si el tipo de grupo es para estudiantes, elimina la relacion a la empresa
        $_POST["grupo/id_empresa"]="null";
        $idReferencia = $_POST["grupo/id_escuela"];
//        echo "<br>id_empresa eliminado";
    } else if ($tipoGrupo == "1") {
        //Si el tipo de grupo es para estudiantes, elimina la relacion a la escuela
        $_POST["grupo/id_escuela"]="null";
        $idReferenia= $_POST["grupo/id_empresa"];
//        echo "<br>id_escuela eliminado";
    }
    
//    imprimeConsola("Despues de verificacion");
//    var_dump($_POST);
//    
    if(verificaCambioGrupo($_POST["grupo/tipo_grupo"], $idReferencia, $idGrupo)){
        vaciarRelacionGrupoAlumno($idGrupo);
        $msj="<h2>Se removieron todos los alumnos dentro del grupo, dado que realiz&oacute; un cambio de grupo.</h2> ";
    }    
    
    $sets = destripaPostEdicion($_POST, "/", "grupo");

//        echo "<br><br>";
//        echo "<br>sets:".$sets["grupo"];

    
    
    //Editar curso
    editaDatos("grupo", $sets["grupo"], "id_grupo=$idGrupo");    
    
 
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
                    <li><a href="verGrupos.php">Grupos</a> <span class="divider">/</span></li>
                    <li class="active">Edici&oacute;n de Grupo</li>
                </ul>
                
                <!-- Main hero unit for a primary marketing message or call to action -->
                <div class="hero-unit">
                    <h1>Edici&oacute;n Exitosa</h1>
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
