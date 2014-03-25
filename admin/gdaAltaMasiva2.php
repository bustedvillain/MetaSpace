<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 27/11/2013
 * Recibe post de la pagina anterio con los datos listos para insertar
 * En esta pagina muestra los errores que se encontraron y pide confirmacion
 * para insertar los datos.
 */
if ($_POST) {

    $proceso = $_SESSION["proceso_alta_masiva"];

    $tipo_carga = $_POST["tipo_carga"];
    $_SESSION["tipo_carga"] = $tipo_carga;
//    var_dump($proceso);
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
                    <h1>Alta Masiva</h1>
                </div>
                <legend>Se insertar&aacute;n los siguientes registros</legend>

                <div style="width: 100%; height: 500px; overflow: auto;" >
                    <table class="table table-bordered table-hover">
                        <thead>
                        <th>#</th>
                        <?php
                        $columnas = array();
                        foreach ($proceso["columnas"] as $columna) {
                            array_push($columnas, $columna);
                            echo <<<encabezado
                                    <th>$columna</th>
encabezado;
                        }
                        ?>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($proceso["filtroRegistros"] as $registro) {
//                                var_dump($registro);
                                echo "<tr>";
                                echo "<td>$i</td>";
//                                $iColumna = 0;
//                                foreach ($registro as $campo => $valor) {
//                                    if ($columnas[$iColumna] == $campo) {
//                                        echo "<td>$valor</td>";
//                                    } else {
//                                        echo "<td></td>";
//                                    }
//                                    $iColumna++;
//                                }
//                                imprimeConsola("");
                                foreach ($proceso["columnas"] as $columna) {
//                                    var_dump($columna);
                                    if (isset($registro[$columna])) {
                                        echo "<td>" . $registro[$columna] . "</td>";
                                    } else {
                                        echo "<td></td>";
                                    }
                                }
                                echo "</tr>";
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    if ($i == 1) {
                        imprimeConsola("No se insertar&aacute; ning&uacute;n usuario");
                    }
                    ?>
                </div>
                <br>
                <?php
                if (count($proceso["filtroRegistros"]) > 0) {
                    echo <<<boton
                            <a href="gdaAltaMasiva3.php" class="btn btn-success" style="float:right;" id="confirmAltaMasiva">Confirmar</a> 
                            <a href="altaMasiva.php" class="btn btn-primary" style="float:right;">Regresar</a>
                            
boton;
                } else {
                    echo <<<boton
                            <a href="altaMasiva.php" class="btn btn-primary" style="float:right;">Regresar</a>
boton;
                }
                ?>

                <div class="progress progress-striped active" id="progress" style="display:none;">
                    <div class="bar" style="width:100%;">
                        <p id="mensaje">Insertando Datos, espere un momento...</p>
                    </div>
                </div>

                <legend>Errores Detectados</legend>
                <p class="text-info"><b>Se hace referencia a los registros (filas) del archivo de excel</b></p>
                <div class="well" id="procesoMasiva" style="overflow:auto; width:97%; height: 300px">                    
                    <?php
                    foreach ($proceso["errores"] as $error) {
                        imprimeError($error);
                    }
                    ?>
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
    header("Location:altaMasiva.php");
}
?>

