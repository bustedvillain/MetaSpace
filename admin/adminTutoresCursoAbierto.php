<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();
if ($_GET["id"]) {
    if (!validarGruposCurso($_GET["id"]) === true) {
        header("Location:verTutoresCurso.php?id=" . $_GET["id"]);
    }
    $idCursoAbierto = $_GET["id"];
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
                    <h1>Administrar Tutores</h1>
                    <p>A continuaci&oacute;n se muestran los tutores relacionados al curso abierto.</p>
                </div>      

                <ul class="breadcrumb">
                    <li><a href="cursosAbiertos.php">Cursos Abiertos</a><span class="divider">/</span></li>
                    <li><a href="verTutoresCurso.php?id=<?php echo $_GET["id"]; ?>">Tutores en el Curso</a><span class="divider">/</span></li>
                    <li class="active">Administrar Tutores</li>
                </ul>

                <h3>Curso Abierto: <b class="text-info"><?php echo getNombreCursoAbierto($_GET["id"]); ?></b></h3>

                <form action="gdaAdminTutoresCurso.php" method="POST" id="formEnrolarTutores">                   

                    <fieldset>
                        <legend>Coordinadores de Tutores</legend>
                        <p class="text-warning">A continuaci&oacute;n se muestran los Coordinadores de Tutures disponibles para enrolar en los grupos, seleccione los tutores que desea enrolar y de click en '->', si desea remover alguno de los seleccionados, de click en el nombre y al boton de '<-'.</p>
                        <p class="text-error">Recuerda que es obligatorio contar con al menos un Coordinador de Tutores por curso.</p>

                        <br/><br/>
                        <div class="divGrupo">
                            <p>Coordinadores de Tutores Disponibles:</p>
                            <select class="grupo" id="select-from" multiple size="15">
                                <?php comboTutores(3, "disponibles", $_GET["id"]); ?>
                            </select>
                        </div>
                        <div class="botones">
                            <button type="button" class="btn btn-success" id="btn-add">-></button><br>
                            <button type="button" class="btn btn-info" id="btn-remove"><-</button>
                        </div>
                        <div class="divGrupo">
                            <p>Coordinadores de Tutores Seleccionados:</p>
                            <select class="grupo" name="coordinadores[]" id="select-to" multiple size="15">
                                <?php comboTutores(3, "seleccionados", $_GET["id"]); ?>
                            </select>                                            
                        </div>

                        <div style="clear: both"></div>

                        <div class="accordion" id="accordion2">                            
                            <?php imprimeGruposCursoAbierto($idCursoAbierto, true); ?>

                        </div>

                        <input type="hidden" name="id_curso_abierto" value="<?php echo $idCursoAbierto; ?>"/>

                        <div style="clear: both">
                            <div class="alert alert-error" style="display:none;" id="errorCoord">
                                <button type="button" class="close" id="btnErrorCoord">&times;</button>
                                <strong>Error</strong>, debes escoger al menos un Coordinador de Tutores.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </fieldset>    
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
