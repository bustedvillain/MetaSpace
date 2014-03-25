<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
if ($_GET) {

    $idGrupo = $_GET["id"];
    $tipo_grupo = $_GET["tipo_grupo"];

    $infoGrupo = getInfoGrupo($idGrupo);
    $nombreGrupo = $infoGrupo["nombre_grupo"];

    if ($tipo_grupo == "Escuela") {
        $nombreInstitucion = getNombreInstitucion($infoGrupo["id_escuela"]);
        $nombreEscuela = getNombreEscuela($infoGrupo["id_escuela"]);
        $idLugar = $infoGrupo["id_escuela"];

        $datosGrupo = <<<datos
                <p><b>Instituci&oacute;n:</b> $nombreInstitucion</p>
                <p><b>Escuela:</b> $nombreEscuela</p>
                <p><b>Grupo:</b> $nombreGrupo</p>
datos;
    } else if ($tipo_grupo == "Empresa") {
        $nombreEmpresa = html_entity_decode(getNombreEmpresa($infoGrupo["id_empresa"]));
        $idLugar = $infoGrupo["id_empresa"];
        $datosGrupo = <<<datos
                <p><b>Empresa:</b> $nombreEmpresa</p>
                <p><b>Grupo:</b> $nombreGrupo</p>
datos;
    }

//    echo "<br>idLugar:$idLugar<br>";
//    var_dump($infoGrupo);
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
                    <h1>Administrar Alumnos</h1>
                    <p>Seleccione los alumnos que desea agregar al grupo </p>
                </div> 

                <ul class="breadcrumb">
                    <li><a href="verGrupos.php">Grupos</a> <span class="divider">/</span></li>
                    <li class="active">Administrar Alumnos</li>
                </ul>

                <form action="gdaAdminAlunosGrupo.php" method="POST" id="formAltaGrupo">
                    <fieldset>
                        <legend>Datos del Grupo</legend>
                        <?php echo $datosGrupo; ?>                       

                        <legend>Alumnos</legend>
                        <p>A continuaci&oacute;n se muestran los alumnos disponibles para agregar en el grupo, seleccione los alumnos que desee y de click en '->', si desea remover alguno de los seleccionados, de click en el alumno y al boton de '<-'.</p>

                        
                        <br/><br/>
                        <div class="divGrupo">
                            <p>Alumnos Disponibles:</p>
                            <select class="grupo" name="selectfrom" id="select-from" multiple size="15">
                                <?php comboAlumnos($tipo_grupo, $idLugar, "disponibles", $idGrupo) ?>
                            </select>
                        </div>
                        <div class="botones">
                            <button type="button" class="btn btn-success" id="btn-add">-></button>
                            <button type="button" class="btn btn-info" id="btn-remove"><-</button>
                        </div>
                        <div class="divGrupo">
                            <p>Alumnos Seleccionados:</p>
                            <select class="grupo" name="alumnos[]" id="select-to" multiple size="15">
                                <?php comboAlumnos($tipo_grupo, $idLugar, "seleccionados", $idGrupo) ?>
                            </select>                                            
                        </div>



                        <div style="clear: both"></div>
                        <button class="btn btn-primary">Guardar</button>
                    </fieldset>

                    <input type="hidden" name="id_grupo" value="<?php echo $infoGrupo["id_grupo"]; ?>"/>
                    <input type="hidden" name="tipo_grupo" value="<?php echo $tipo_grupo; ?>"/>

                </form>

                

                
                    
                <?php include("../template/footer.php"); ?>

            </div> <!-- /container -->

            <!-- Le javascript
            ================================================== -->
            <!-- Placed at the end of the document so the pages load faster -->
            <?php include("../template/bootstrapAssets.php"); ?>
            <script src="../js/jquery.js"></script>

        </body>
    </html>
    <?php
} else {
    header("Location:index.php");
}
?>
