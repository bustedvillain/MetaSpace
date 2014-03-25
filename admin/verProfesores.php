<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
if (isset($_GET["mensaje"]))
$mensaje = html_entity_decode(urldecode($_GET["mensaje"]));

/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 21 de Octubre del 2013
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/metasDatatables.php"); ?>
        <?php include("../template/heads.php"); ?>
    </head>

    <body>
        <!--Up bar-->
        <?php include("../template/menuAdmin.php"); ?>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Ver Profesores de Aula</h1>
                <p>A continuaci&oacute;n se muestran los usuarios registrados.</p>
                <?php echo $mensaje; ?>
            </div>

            <a class="btn btn-primary btn-success" href="altaProfesores.php">Agregar</a>
            <!--<a class="btn btn-primary" href="descargaDatos.php?tipo=2" target="_blank">Descargar Datos</a>-->
            <div class="btn-group">
                <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
                    Descargar
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="descargaDatos.php?tipo=2&formato=csv" target="_blank">csv</a></li>
                    <li><a href="descargaDatos.php?tipo=2&formato=xls" target="_blank">xls</a></li>
                </ul>
            </div>
            <?php tablaUsuarios("profesor"); ?>

            

            
                
            <?php include("../template/footer.php"); ?>

            <!-- Modal -->
            <div id="verModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Detalle del Profesor de Aula</h3>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-bordered">
                        <?php imprimirVerDatosPersonales(); ?>
                        <tr>
                            <td colspan="2"><b>Campos Espec&iacute;ficos</b></td>
                        </tr>
                        <tr>
                            <td>Puesto u Ocupaci&oacute;n:</td>
                            <td ><div id="ver_puesto_ocupacion"></div></td>
                        </tr>
                        <tr>
                            <td>Instituci&oacute;n:</td>
                            <td ><div id="ver_institucion"></div></td>
                        </tr>
                        <tr>
                            <td>Escuela:</td>
                            <td ><div id="ver_escuela"></div></td>
                        </tr>
                        <tr>
                            <td>Nivel Educativo Enrolado</td>
                            <td ><div id="ver_nivel_educativo"></div></td>
                        </tr>                        
                        <tr>
                            <td>Grado Escolar Enrolado:</td>
                            <td ><div id="ver_grado_escolar"></div></td>
                        </tr>
                        <tr>
                            <td>Alumnos Relacionados:</td>
                            <td>
                                <ul id="ver_alumnos"></ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="cargando"></div>
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </div>
            <!--/Ver Modal-->

            <!-- Editar Modal -->
            <div id="editarModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Editar Profesor de Aula</h3>
                </div>
                <form method="POST" action="gdaEditaUsuario.php">
                    <div class="modal-body">                    
                        <table class="table table-hover table-bordered">
                            <?php imprimeEditarDatosPersonales(); ?>
                            <tr>
                                <td colspan="2"><b>Campos Espec&iacute;ficos</b></td>
                            </tr>
                            <tr>
                                <td>Puesto u Ocupaci&oacute;n:</td>
                                <td><input type="text" name="profesores_aula/puesto_ocupacion" id="edita_puesto_ocupacion"/></td>
                            </tr>
                            <tr>
                                <td>Instituci&oacute;n:</td>
                                <td><?php comboInstituciones("idInstitucion"); ?></td>
                            </tr>
                            <tr>
                                <td>Escuela:</td>
                                <td>
                                    <select id="id_escuela" name="profesores_aula/id_escuela" required></select>
                                </td>
                            </tr>
<!--                            <tr>
                                <td>Grado Escolar Enrolado:</td>
                                <td>
                                    <select name="profesores_aula/id_grado_escolar_enrolado" id="edita_id_grado_escolar_enrolado">
                                        <?php comboGradoEscolar(); ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Nivel Educativo Enrolado</td>
                                <td>
                                    <select name="profesores_aula/id_nivel" id="edita_id_nivel">
                                        <?php comboNivelesEducativos(); ?>
                                    </select>
                                </td>
                            </tr>-->
                            <tr>
                                <td>Nivel Educativo Enrolado</td>
                                <td >
                                    <select id="comboNivelEscolar">
                                        <?php comboNivelesEducativos(); ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Grado Escolar Enrolado</td>
                                <td>
                                    <select id="comboGradoEscolares" name="profesores_aula/id_grado_escolar_enrolado">
                                        <?php comboGradoEscolar(); ?>
                                    </select> 
                                </td>
                            </tr>
                            
                        </table>
                        <input type="hidden" name="id_datos_personales" id="id_datos_personales" value=""/>
                        <input type="hidden" name="tipo_usuario" value="profesor"/>                                             
                    </div>
                    <div class="modal-footer">
                        <div class="cargando"></div>
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                        <button class="btn btn-primary" type="submit">Guardar</button>
                    </div>
                </form>
            </div>
            <!--/Editar modal-->

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>

    </body>
</html>
