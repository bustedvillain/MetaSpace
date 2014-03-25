<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 14/11/2013
 * Recibe el documnto de excel para carga masiva de usuarios
 */
if ($_POST) {
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
                    <div class="progress progress-striped active" id="progress">
                        <div class="bar" style="width:100%;">
                            <p>Validando Datos...</p>
                        </div>
                    </div>
                    <!--<img src="../img/ok.png"/>-->
                </div>
                <legend>Proceso</legend>
                <div class="well" id="procesoMasiva" style="overflow:auto; width:97%; height: 300px">                    
                    <?php
                    imprimeOk("Excel cargado...");
                    $valorProgreso = 1;
                    //var_dump($_FILES);
                    $arreglo = excelToArray($_FILES["excel"], false);
                    //var_dump($arreglo);

                    $tipo_carga = $_POST["tipo_carga"];

                    $process = procesaRegistros($arreglo, $arreglo["nFilas"], $tipo_carga, false);
                    $process["columnas"]=$arreglo["columnas"];
                    
                    imprimeOk("--------------PROCESS--------------");
                    //var_dump($process);
                    imprimeConsola("Carga masiva concluida.");
                    
                    //Guarda en la sesion los datos para estar disponibles
                    //En la siguiente pagina
                    
                    $_SESSION["proceso_alta_masiva"]=$process;
                    
                    //Enviando post
                    echo <<<POST
                        <form action="gdaAltaMasiva2.php" method="post" name="altaMasiva">
                            <input type='hidden' name='tipo_carga' value='$tipo_carga'/>
                        </form>
                        <script language="JavaScript">
                            document.altaMasiva.submit();
                        </script>
POST;
                    flush();
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


