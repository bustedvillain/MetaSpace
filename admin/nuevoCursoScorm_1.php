<?php
//Control de cambios #&
//Autor:Omar Nava
//Objetivo: Fix 6.1 Alta de Cursos
//03-ene-2014
//Control de cambios #8
//Autor:Omar Nava
//Objetivo: Cambiar etiqueta de plantilla
//03-ene-2014
/**
 * CHANGE CONTROL 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACIÓN: 21 DE MAYO DE 2014
 * OBJETIVO: CAMBIOS ESTETICOS
 */
/**
 * CHANGE CONTROL 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACIÓN: 20 DE JUNIO DE 2014
 * OBJETIVO: AGREGAR CAMPO AL FORMULARIO PARA ELEGIR EL TIPO DE EJECUCIÓN DEL CURSO
 */
include("../sources/Funciones.php");
verificarSesionAdminOGestor();

if (isset($_GET["id"])) {
    $idCurso = $_GET["id"];
    $fullname = $_GET["fullname"];
    $shortname = $_GET["shortname"];
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
                    <h1>Vinculaci&oacute;n</h1>
                    <p>Vinculaci&oacute;n de curso con el sistema. Los campos marcados con (*) son obligatorios.</p>
                </div> 

                <ul class="breadcrumb">
                    <li><a href="nuevoCurso.php">Nuevo Curso</a> <span class="divider">/</span></li>
                    <li class="active">Vinculaci&oacute;n</li>
                </ul>

                <form id="nuevoCurso" action="gdaCursoScorm.php" enctype="multipart/form-data" method="POST">
                    <fieldset>
                        <legend>Curso</legend>
                        <div class="input-append">
                            <h3 class="text-info"><?php echo $fullname; ?></h3>
                        </div>                    
                        <legend>Datos para el Sistema Integral de Administraci&oacute;n</legend>
                        <div class="input-append">
                            <label>*Nombre del Curso:</label>
                            <input type="text" name="cursos/nombre_curso" id="nombre_curso" placeholder="" value="<?php echo $fullname; ?>"required>
                            <label class="text-error" id="errorNombreCurso"></label>
                        </div>
                        <div class="input-append">
                            <label>*Clave del curso:</label>
                            <input type="text" name="cursos/clave_curso" id="clave_curso" placeholder="" required>
                            <label class="text-error" id="errorClaveCurso"></label>
                        </div>
                        <div class="input-append">
                            <label>*Nombre corto:</label>
                            <input type="text" name="cursos/nombre_corto" placeholder="" id="nombre_corto" value="<?php echo $shortname; ?>" required>
                            <label class="text-error" id="errorNombreCorto"></label>
                        </div>
                        <div class="input-append">
                            <label>Categor&iacute;a:</label>
                            <select name="cursos/id_categoria">
                                <?php comboCategorias(); ?>
                            </select>
                        </div>
                        <div class="input-append">
                            <label>Asignatura:</label>
                            <select name="cursos/id_asignatura" required>
                                <?php comboAsignaturas(); ?>
                            </select>
                        </div>
                        <!--inicia control de cambios #6-->
                        <div class="input-append">
                            <label>*Nivel Escolar:</label>
                            <select id="comboNivelEscolar" required>
                                <?php comboNivelesEducativos(); ?>
                            </select>
                        </div>

                        <div class="input-append">
                            <label>*Grado Escolar:</label>
                            <select id="comboGradoEscolares" name="cursos/id_grado_escolar" required>
                                <?php comboGradoEscolar(); ?>
                            </select>
                        </div>
                        <!--termina control de cambios #6-->
                        <div class="input-append">
                            <!--inicia control de cambios #8-->
                            <label>Iconograf&iacute;a del curso:</label>
                            <!--termina control de cambios #8-->
                            <p class="text-info">Paquete que contine informaci&oacute;n como: Color del curso, imagenes del curso, imagenes de los premios dentro del curso. Estos deben estar sujetos a los est&aacute;ndares establecdos.</p>
                            <input type="file" name="plantilla" accept="application/zip" required class="contenido"/>
                        </div>

                        <!--Control de cambios 1.1.0-->
                        <div class="input-append">
                            <label>Tipo de ejecuci&oacute;n:</label>
                            <select name="cursos/tipo_ejecucion" required>
                                <option value="0">Aut&oacute;noma/Libre</option>
                                <option value="1">Seriaci&oacute;n de bloques</option>
                            </select>
                        </div>
                        <!--Control de cambios 1.1.0-->
                        <legend>Equivalencias Num&eacute;ricas</legend>
                        <p class="text-info">Asigne las equivalencias num&eacute;ricas relacionadas a las calificaciones/premios. 'Insuficiente' hace referencia al m&aacute;s bajo; desde 1 hasta el valor que usted especifique. El rango 'Muy Bien' es la calificaci&oacute;n m&aacute;xima.</p>
                        <div class="input-append">
                            <label>Insuficiente:</label>
                            <b>De</b><input type="text" value="1" disabled/> a <input type="text" name="equivalencias_numericas/rango1" required id="rango1" value=""/>
                        </div>
                        <br>
                        <div class="input-append">
                            <label>Suficiente:</label>
                            De <input type="text" value="" disabled id="repRango1"/> a <input type="text" name="equivalencias_numericas/rango2" required id="rango2" value=""/>
                            <label class="text-error" id="errorRango2"></label>
                        </div>
                        <br>
                        <div class="input-append">
                            <label>Bien:</label>
                            De <input type="text" value="" disabled id="repRango2"/> a <input type="text" name="equivalencias_numericas/rango3" required id="rango3" value=""/>
                            <label class="text-error" id="errorRango3"></label>
                        </div>
                        <br>
                        <div class="input-append">
                            <label>Muy Bien:</label>
                            De <input type="text" value="" disabled id="repRango3"/> a <input type="text" name="equivalencias_numericas/rango4" readonly id="rango4" value="100"/>
                            <label class="text-error" id="errorRango4"></label>
                        </div>
                        <legend>Contenido del Curso</legend><br />
                        <?php consultaTopicosCursoScormMoodle($idCurso); ?>
                        <!---->
                        <div class="progress progress-striped active" id="progress">
                            <div class="bar" style="width:100%; display:none;">
                                <p>Espere un momento...El tiempo de espera depende en el tama&ntilde;o de los contenidos seleccionados.</p>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </fieldset>   
                    <input type="hidden" id="courseId" name="courseId" value="<?php echo $idCurso; ?>"/>
                    <input type="hidden" name="status" value="1"/>                    
                    <input type="hidden" name="cursos/id_curso_moodle" value="<?php echo $idCurso; ?>"/>
                    <input type="hidden" name="cursos/status" value="1"/>                    
                </form>
                <!-- Modal -->
                <div id="error" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Error</h3>
                    </div>
                    <div class="modal-body">
                        <p>Tipo de extensi&oacute;n para contenidos no v&aacute;lido. Solo admiten archivos ZIP.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn primary" data-dismiss="modal" aria-hidden="true">Close</button>
                    </div>
                </div>

                <!--
                    Change Control: 1.1.0
                    Autor: Jose Manuel Nieto Gomez
                    Fecha de Modificacion: 18/06/2014
                    Objetivo: Implementacion de validacion de empaquetados
                -->

                <!-- Modal -->
                <div id="validacionZips" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Validaci&oacute;n de Empaquetado</h3>
                    </div>
                    <div class="modal-body">
                        <div class="progress progress-striped active" id="progress_val_zip">
                            <div class="bar" style="width:1%;" id="validandoZip">
                                <p id="mensaje_progreso_validacion">Validando empaquetado...</p>
                            </div>
                        </div>

                        <div style="display:none;" id="resultadosValidacion">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td><b>Tipo de validaci&oacute;n:</b></td>
                                    <td id="tipo_validacion"></td>
                                </tr>
                                <tr>
                                    <td><b>Resultado de la validaci&oacute;n:</b></td>
                                    <td id="resutado_validacion"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><b>Errores</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" id="errores_validacion"></td>
                                </tr>
                                <tr class="observaciones">
                                    <td colspan="2"><b>Observaciones</b></td>
                                </tr>
                                <tr class="observaciones">
                                    <td colspan="2">Nota: Las siguientes observaciones corresponden a archivos/carpetas que no requieren incluirse obligatoriamente en el empaquetado, pero se le notifican para su revisión.</td>
                                </tr>
                                <tr class="observaciones">
                                    <td colspan="2" id="warnings_validacion"></td>
                                </tr>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn primary" data-dismiss="modal" aria-hidden="true">Aceptar</button>
                    </div>
                </div>





                <?php include("../template/footer.php"); ?>

            </div> <!-- /container -->

            <!-- Le javascript
            ================================================== -->
            <!-- Placed at the end of the document so the pages load faster -->
            <?php include("../template/bootstrapAssets.php"); ?>

            <link href="http://hayageek.github.io/jQuery-Upload-File/uploadfile.min.css" rel="stylesheet"/>
            <script src="http://hayageek.github.io/jQuery-Upload-File/jquery.uploadfile.min.js"></script>
            <script type="text/javascript" src="../js/jqueryAdmin.js"></script>
            <script type="text/javascript" src="../js/jqueryScorm.js"></script>

            <!--Validacion de zips-->
            <script type="text/javascript" src="../js/zip.js"></script>
            <script type="text/javascript" src="../js/workUnzip.js"></script>
            <script type="text/javascript" src="../js/validarPaquetes.js"></script>
            <!--Validacion de zips-->

        </body>
    </html>
    <?php
} else {
    header("Location:nuevoCurso.php");
}
?>