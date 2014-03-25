<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();

if ($_POST) {
//    var_dump($_POST);
    //Arregla formatos
    $_POST["cursos_abiertos/fecha_inicio"] = preparaFechaBaseDatos($_POST["cursos_abiertos/fecha_inicio"]);
    $_POST["cursos_abiertos/fecha_fin"] = preparaFechaBaseDatos($_POST["cursos_abiertos/fecha_fin"]);
            
    $infoCursoAbierto = destripaPost($_POST, "/", "cursos_abiertos");
    
//    echo "<br><br>";
//    echo "<br>campos:" . $infoCursoAbierto["campos"]["cursos_abiertos"];
//    echo "<br>valores:" . $infoCursoAbierto["valores"]["cursos_abiertos"];

    
    //Insertar curso abierto
    $idCursoAbierto = insertarDatos("cursos_abiertos", $infoCursoAbierto["campos"]["cursos_abiertos"], $infoCursoAbierto["valores"]["cursos_abiertos"]);
    //Ajustar fechas de unidades
    $i = 0;
    foreach ($_POST["fecha_inicio"] as $fecha_inicio) {
        //Arregla formatos
        $fecha_inicio = preparaFechaBaseDatos($fecha_inicio);
        $fecha_fin = preparaFechaBaseDatos( $_POST["fecha_fin"][$i]);
        
        $idUnidad = $_POST["id_unidad"][$i];        

//        echo "<br>-----------------------";
//        echo "<br>id unidad:$idUnidad";
//        echo "<br>Fecha inicio:$fecha_inicio";
//        echo "<br>Fecha fin:$fecha_fin";

        $campos = "id_curso_abierto, id_unidad";
        $valores = "$idCursoAbierto, $idUnidad";

        if ($fecha_inicio != "") {
            $campos = $campos . ", fecha_inicio";
            $valores = $valores . ",'$fecha_inicio'";
        }else{
            $campos = $campos . ", fecha_inicio";
            $f = $_POST["cursos_abiertos/fecha_inicio"];
            $valores = $valores . ",'$f'";
        }

        if ($fecha_fin != "") {
            $campos = $campos . ", fecha_fin";
            $valores = $valores . ", '$fecha_fin'";
        }else{
            $campos = $campos . ", fecha_fin";
            $f = $_POST["cursos_abiertos/fecha_fin"];
            $valores = $valores . ",'$f'";
        }
//        echo "<br>campos:$campos";
//        echo "<br>valores:$valores";
        //Insertar periodo de las unidades
        insertarDatos("fechas_unidades_cursos", $campos, $valores);

        $i++;
    }

    //Insertar coordinadores
//    echo "<br><br>Insertando coordinadores";
//    insertarTutoresCursoAbierto("coordinadores", $_POST, $idCursoAbierto);
//    insertarTutoresCursoAbierto("seniors", $_POST, $idCursoAbierto);
//    insertarTutoresCursoAbierto("juniors", $_POST, $idCursoAbierto);
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
                    <!--<h1>Curso abierto correctamente</h1>-->
                    <h1>Inserci&oacute;n Exitosa</h1>
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

