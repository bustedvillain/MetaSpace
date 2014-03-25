<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();

if ($_POST) {
//    var_dump($_POST);

    $idGrupo = $_POST["id_grupo"];
    vaciarRelacionGrupoAlumno($idGrupo);
    //Agregar alumnos
    if (isset($_POST["alumnos"])) {
        foreach ($_POST["alumnos"] as $idAlumno) {
//            echo "<br>*********************";
//            echo "<br>Alumnos: $idAlumno";            

            insertarGrupoAlumno($idAlumno, $idGrupo);
            
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
                    <li><a href="verGrupos.php">Grupos</a> <span class="divider">/</span></li>
                    <li><a href="adminAlumnosGrupo.php?id=<?php echo $idGrupo; ?>&tipo_grupo=<?php echo $_POST["tipo_grupo"]; ?>">Administrar Alumnos</a> <span class="divider">/</span></li>
                    <li class="active">Administraci&oacute;n completa</li>
                </ul>
                <!-- Main hero unit for a primary marketing message or call to action -->
                <div class="hero-unit">
                    <h1>Administraci&oacute;n de Grupo completa</h1>
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

