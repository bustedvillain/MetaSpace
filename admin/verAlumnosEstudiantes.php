<?php
//Control de cambios #&
//Autor:Omar Nava
//Objetivo: Combos nivel/grados
//03-ene-2014
include("../sources/Funciones.php");
verificarSesionAdmnistrador();

if (isset($_GET["mensaje"]))
    $mensaje = html_entity_decode(urldecode($_GET["mensaje"]));
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 21 de Octubre del 2013
 */

/**
 * CHANGE CONTROL 0.99.7
 * FECHA DE MODIFICACION: 9 DE ENERO DEL 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJETIVO: MOSTRAR FOTO DE PERFIL AL VER USUARIO
 */

/**
 * CHANGE CONTROL 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACIÓN: 21 DE MAYO DE 2014
 * OBJETIVO: CAMBIOS ESTETICOS
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
                <h1>Ver Alumnos Estudiantes</h1>
                <p>A continuaci&oacute;n se muestran los usuarios registrados.</p>
                <?php echo $mensaje; ?>
            </div>
            
            <a class="btn btn-primary btn-success" href="altaAlumnoEstudiante.php">Agregar Alumno</a>
            <!--<a class="btn btn-primary" href="descargaDatos.php?tipo=0" target="_blank">Descargar Datos</a>-->
            <a class="btn btn-primary" href="./descargarAlumnosEstudiantes.php">Descargar Lista</a>
            
            <?php tablaUsuarios("estudiante"); ?>           
                
            <?php include("../template/footer.php"); ?>

            <!-- Ver Modal -->
            <div id="verModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Detalle del Alumno</h3>
                </div>
                <div class="modal-body">                    
                    <table class="table table-hover table-bordered">
                        <?php imprimirVerDatosPersonales(); ?>
                        <tr>
                            <td colspan="2"><b>DATOS ASOCIADOS</b></td>
                        </tr>
                        <tr>
                            <td>Matricula</td>
                            <td ><div id="ver_matricula"></div></td>
                        </tr>
                        <tr>
                            <td>Padre</td>
                            <td ><div id="ver_padre"></div></td>
                        </tr>
                        <tr>
                            <td>Profesor de Aula</td>
                            <td ><div id="ver_profesor_aula"></div></td>
                        </tr>
                        <tr>
                            <td>Nivel Educativo</td>
                            <td ><div id="ver_nivel_educativo"></div></td>
                        </tr>
                        <tr>
                            <td>Grado Escolar</td>
                            <td ><div id="ver_grado_escolar"></div></td>
                        </tr>
                        <tr>
                            <td>Instituci&oacute;n</td>
                            <td ><div id="ver_institucion"></div></td>
                        </tr>
                        <tr>
                            <td>Escuela</td>
                            <td ><div id="ver_escuela"></div></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>IMAGEN DE PERFIL</b></td>
                        </tr>
                        <tr>
                            <td>Imagen</td>
                            <td><div id="ver_imagen"></div></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="cargando"></div>
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                </div>
            </div>

            <!-- Editar Modal -->
            <div id="editarModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Editar Alumno</h3>                    
                </div>
                <form method="POST" action="gdaEditaUsuario.php" enctype="multipart/form-data">
                    <div class="modal-body">        
                        <p>Los campos marcados con (*) son obligatorios.</p>
                        <table class="table table-hover table-bordered">                        
                            <?php imprimeEditarDatosPersonales(); ?>
                            <tr>
                                <td colspan="2"><b>DATOS ASOCIADOS</b></td>
                            </tr>
                            <tr>
                                <td>Matr&iacute;cula</td>
                                <td><input name="alumnos/matricula" type='text' id="edita_matricula"></td>
                            </tr>
                            <tr>
                                <td>Padre</td>
                                <td>
                                    <select name="alumnos/id_padre" id="edita_id_padre">
                                        <?php comboPadres(); ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Profesor de Aula</td>
                                <td>
                                    <select name="alumnos/id_profesor_aula" id='edita_id_profesor_aula'>
                                        <?php comboProfesoresAula(); ?>
                                    </select> 
                                </td>
                            </tr>
                            <!--inicia control de cambios #6-->
                            <tr>
                                <td>Nivel Educativo</td>
                                <td >
                                    <select id="comboNivelEscolar">
                                        <?php comboNivelesEducativos(); ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Grado Escolar</td>
                                <td>
                                    <select id="comboGradoEscolares" name="alumnos/id_grado_escolar" required>
                                        <?php comboGradoEscolar(); ?>
                                    </select> 
                                </td>
                            </tr>
                            <!--termina control de cambios #6-->
                            <tr>
                                <td>Instituci&oacute;n</td>
                                <td >
                                    <?php comboInstituciones("idInstitucion"); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Escuela</td>
                                <td>
                                    <select id="id_escuela" name="alumnos/id_escuela" required></select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>IMAGEN DE PERFIL</b></td>
                            </tr>
                            <tr>
                                <td>Imagen</td>
                                <td>
                                    <div id="ver_imagen"></div>
                                    <div class="custom-input-file"><input type="file" size="1" name="imagen" id="imagen" class="input-file" />
                                        <button class="btn btn-primary" style="z-index:1000;">Subir archivo</button>
                                        <input type="text" id="url-archivo" />
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="id_datos_personales" id="id_datos_personales" value=""/>
                        <input type="hidden" name="tipo_usuario" value="estudiante"/>                                             
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
