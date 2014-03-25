<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();

if ($_GET) {
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
                    <h1>Abrir Curso</h1>
                    <p>A continuaci&oacute;n se muestra un formulario para poder abrir un curso. Los campos marcados con (*) son obligatorios.</p>
                </div>      

                <ul class="breadcrumb">
                    <li><a href="cursos.php">Cursos</a> <span class="divider">/</span></li>
                    <li class="active">Abrir Curso</li>
                </ul>

                <form action="gdaAbrirCurso.php" method="POST" id="formAbrirCurso">
                    <fieldset>
                        <div class="input-append">
                            <h3 class="text-info"><?php echo $_GET["nombre"] ?></h3>
                        </div>
                        <legend>Datos espec&iacute;ficos para el curso abierto</legend>

                        <div class="input-append">
                            <label>*Nombre:</label>
                            <input type="text" placeholder="" name="cursos_abiertos/nombre_curso_abierto" required>
                        </div>
                        <div class="input-append">
                            <label>Descripci&oacute;n:</label>
                            <input type="text" placeholder="" name="cursos_abiertos/descripcion">
                        </div>
                        <div class="input-append">
                            <label>*Fecha de Inicio (dd/mm/yyyy):</label>
                            <input id="fecha_inicio" type="date" placeholder="" required name="cursos_abiertos/fecha_inicio" pattern="\d{1,2}/\d{1,2}/\d{4}">
                        </div>
                        <div class="input-append">
                            <label>*Fecha de Terminaci&oacute;n (dd/mm/yyyy):</label>
                            <input id="fecha_fin" type="date" placeholder="" required name="cursos_abiertos/fecha_fin" pattern="\d{1,2}/\d{1,2}/\d{4}">
                            <label class="text-error" id="errorFecha"></label>
                        </div>
                        
                        
                        <legend>Ajuste de fechas sobre unidades</legend>
                        <p class="text-warning">Seleccione las fechas en las que desea que est&eacute;n disponibles las unidades. Si no desea ingresar fechas, las unidades siempre ser&aacute;n visibles durante el periodo de ejecuci&oacute;n del curso abierto.</p>
                        <p class="text-info">Se asignar&aacute;n fechas s&oacute;lo sobre las unidades del curso que est&eacute;n activas.</p>

                        <?php imprimeTopicosFechas($_GET["id"]) ?>

<!--                        <legend>Coordinadores de Tutores</legend>
                        <p class="text-warning">A continuaci&oacute;n se muestran los Coordinadores de Tutures disponibles para enrolar en el curso, seleccione los tutores que desea enrolar y de click en 'Agregar', si desea remover alguno de los seleccionados, de click en el nombre y al boton de 'Remover'.</p>
                        <p class="text-error">Recuerda que es obligatorio contar con al menos un Coordinador de Tutores por curso.</p>

                        <button type="button" class="btn btn-success" id="btn-add">Agregar</button>
                        <button type="button" class="btn btn-info" id="btn-remove">Remover</button>

                        <br/><br/>-->
<!--                        <div class="divGrupo">
                            <p>Coordinadores de Tutores Disponibles:</p>
                            <select class="grupo" id="select-from" multiple size="15">
                                <?php // comboTutores(3); ?>
                            </select>
                        </div>

                        <div class="divGrupo">
                            <p>Coordinadores de Tutores Seleccionados:</p>
                            <select class="grupo" name="coordinadores[]" id="select-to" multiple size="15"></select>                                            
                        </div>

                        <div style="clear: both"></div>

                        <legend>Tutores Senior</legend>
                        <p class="text-warning">A continuaci&oacute;n se muestran los Tutores Senior disponibles para enrolar en el curso, seleccione los tutores que desea enrolar y de click en 'Agregar', si desea remover alguno de los seleccionados, de click en el nombre y al boton de 'Remover'.</p>

                        <button type="button" class="btn btn-success" id="btn-add2">Agregar</button>
                        <button type="button" class="btn btn-info" id="btn-remove2">Remover</button>

                        <br/><br/>
                        <div class="divGrupo">
                            <p>Tutores Senior Disponibles:</p>
                            <select class="grupo" id="select-from2" multiple size="15">
                                <?php // comboTutores(2); ?>
                            </select>
                        </div>

                        <div class="divGrupo">
                            <p>Tutores Senior Seleccionados:</p>
                            <select class="grupo" name="seniors[]" id="select-to2" multiple size="15"></select>                                            
                        </div>

                        <div style="clear: both"></div>
                        <legend>Tutores Junior</legend>
                        <p class="text-warning">A continuaci&oacute;n se muestran los Tutores Junior disponibles para enrolar en el curso, seleccione los tutores que desea enrolar y de click en 'Agregar', si desea remover alguno de los seleccionados, de click en el nombre y al boton de 'Remover'.</p>

                        <button type="button" class="btn btn-success" id="btn-add3">Agregar</button>
                        <button type="button" class="btn btn-info" id="btn-remove3">Remover</button>

                        <br/><br/>
                        <div class="divGrupo">
                            <p>Tutores Junior Disponibles:</p>
                            <select class="grupo" id="select-from3" multiple size="15">
                                <?php // comboTutores(1); ?>
                            </select>
                        </div>

                        <div class="divGrupo">
                            <p>Tutores Junior Seleccionados:</p>
                            <select class="grupo" name="juniors[]" id="select-to3" multiple size="15"></select>                                            
                        </div>-->

                        <div style="clear: both">
                            <div class="alert alert-error" style="display:none;" id="errorCoord">
                                <button type="button" class="close" id="btnErrorCoord">&times;</button>
                                <strong>Error</strong>, debes escoger al menos un Coordinador de Tutores.
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </fieldset>
                    
                    <input type="hidden" name="cursos_abiertos/id_curso" value="<?php echo $_GET["id"]; ?>">
                    <input type="hidden" name="cursos_abiertos/status" value="1">
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
