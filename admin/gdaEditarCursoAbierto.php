<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();

if ($_POST) {
//    var_dump($_POST);
//    echo "<br><br>";
//    var_dump($_FILES);

    $id_curso_abierto = $_POST["cursos_abiertos/id_curso_abierto"];
    unset($_POST["cursos_abiertos/id_curso_abierto"]);

//    echo "<br><br>";
//    echo "id_curso_abierto:$id_curso_abierto";

    $sets = destripaPostEdicion($_POST, "/", "cursos_abiertos");

//    echo "<br><br>";
//    echo "<br>sets:" . $sets["cursos_abiertos"];

    //Editar curso
    editaDatos("cursos_abiertos", $sets["cursos_abiertos"], "id_curso_abierto=$id_curso_abierto");
    //Ajustar fechas de unidades
    $i = 0;
    foreach ($_POST["fecha_inicio"] as $fecha_inicio) {
        $idUnidad = $_POST["id_unidad"][$i];
        $fecha_fin = $_POST["fecha_fin"][$i];
        $id_fechas_unidades_cursos = $_POST["id_fechas_unidades_cursos"][$i];

//        echo "<br>-----------------------";
//        echo "<br>id_fechas_unidades_cursos:$id_fechas_unidades_cursos";
//        echo "<br>id unidad:$idUnidad";
//        echo "<br>Fecha inicio:$fecha_inicio";
//        echo "<br>Fecha fin:$fecha_fin";

        $sets = "";

        if ($fecha_inicio != "") {
            $sets = $sets . "fecha_inicio='$fecha_inicio'";
        }

        if ($fecha_fin != "") {
            $sets = $sets . ", fecha_fin='$fecha_fin'";
        }
//        echo "<br>sets:$sets";

        if ($sets != "") {
            //Insertar periodo de las unidades
            editaDatos("fechas_unidades_cursos", $sets, "id_fecha_unidades_curso=$id_fechas_unidades_cursos");
        }
        $i++;
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