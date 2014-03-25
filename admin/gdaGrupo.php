<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
if ($_POST) {

//    var_dump($_POST);
    $tipoGrupo = $_POST["grupo/tipo_grupo"];

//    echo "<br><br>Tipo de grupo:$tipoGrupo";
    //Verifica que tipo de grupo va a ser para eliminar la otra variable
    if ($tipoGrupo == "0") {
        //Si el tipo de grupo es para estudiantes, elimina la relacion a la empresa
        unset($_POST["grupo/id_empresa"]);
//        echo "<br>id_empresa eliminado";
    } else if ($tipoGrupo == "1") {
        //Si el tipo de grupo es para estudiantes, elimina la relacion a la escuela
        unset($_POST["grupo/id_escuela"]);
//        echo "<br>id_escuela eliminado";
    }
//    echo "<br><br>";
//    var_dump($_POST);

    $info = destripaPost($_POST, "/", "grupo");

//    echo "<br><br>";
//    echo "wtf tipo_grupo:".$_POST["grupo/tipo_grupo"];
//    echo "<br>campos:" . $info["campos"]["grupo"];
//    echo "<br>valores:" . $info["valores"]["grupo"];   

    $idGrupo = insertarDatos("grupo", $info["campos"]["grupo"], $info["valores"]["grupo"]);

    //Agregar alumnos
    if (isset($_POST["alumnos"])) {
        foreach ($_POST["alumnos"] as $idAlumno) {
//            echo "<br>*********************";
//            echo "<br>Alumnos: $idAlumno";            

            $campos = "id_alumno, id_grupo";
            $valores = "$idAlumno, $idGrupo";

            //Insertar 
            insertarDatos("grupo_alumno", $campos, $valores);
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

                <!-- Main hero unit for a primary marketing message or call to action -->
                <div class="hero-unit">
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

