<?php 
//Control de cambios #6
//Autor:Omar Nava
//Objetivo: Edicion de cursos
//03-ene-2014
include("../sources/Funciones.php"); verificarSesionAdminOGestor();?>
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
                <h1>Cat&aacute;logo de Cursos</h1>
                <p>A continuaci&oacute;n se muestran los cursos vinculados con Moodle.</p>
            </div>  

            <ul class="breadcrumb">
                <li class="active">Cursos</li>
            </ul>

            <p class="text-warning">IMPORTANTE: La edici&oacute;n de los cursos es posible siempre y cuando no existan alumnos/grupos enrolados a este en un curso abierto.</p>
            
            <table cellpadding="0" cellspacing="0" border="0" class="display tabla">
                <thead>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>id</th>
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Nombre corto</th>
                        <th></th>                        
                    </tr>                    
                </thead>
                <tbody>
                    <?php catalogoCursos(); ?>
                </tbody>
            </table>            


            <!-- Modal -->
            <div id="verModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Ver Curso</h3>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <td>Curso (Moodle):</td>
                            <td><b class="text-info" id="ver_curso_moodle"></b></td>
                        </tr>
                        <tr>
                            <td>Gestor del Curso:</td>
                            <td id="ver_gestor"></td>
                        </tr>
                        <tr>
                            <td>Clave del curso:</td>
                            <td id="ver_clave_curso"></td>
                        </tr>
                        <tr>
                            <td>Nombre del Curso:</td>
                            <td id="ver_nombre_curso"></td>
                        </tr>
                        <tr>
                            <td>Nombre corto:</td>
                            <td id="ver_nombre_corto"></td>
                        </tr>
                        <tr>
                            <td>Categor&iacute;a:</td>
                            <td id="ver_categoria"></td>
                        </tr>
                        <tr>
                            <td>Asignatura:</td>
                            <td id="ver_asignatura"></td>
                        </tr>
                        <tr>
                            <td>Nivel Escolar:</td>
                            <td id="ver_nivel_escolar"></td>
                        </tr>
                        <tr>
                            <td>Nivel Escolar:</td>
                            <td id="ver_grado_escolar"></td>
                        </tr>
                        <tr>
                            <td>Acci&oacute;n:</td>
                            <td id="probar_curso"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Contenido del Curso</b></td>
                        </tr>

                        <tbody id="ver_topicos">

                        </tbody>                        

                    </table>
                </div>
                <div class="modal-footer">
                    <div class="cargando"></div>  
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                </div>
            </div>
            <!--/Ver Modal-->

            <!-- Editar Modal -->
            <div id="editarModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Editar Curso</h3>
                </div>
                <form id="editarCurso" action="gdaEditarCurso.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">                    
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td>Curso:</td>
                                <td><b class="text-info" id="ver_curso_moodle2"></b></td>
                            </tr>
                            <tr>
                                <td>Clave del curso:</td>
                                <td><input type="text" name="cursos/clave_curso" value="" id="edita_clave_curso" required></td>
                            </tr>
                            <tr>
                                <td>Nombre del Curso:</td>
                                <td><input type="text" name="cursos/nombre_curso" value="" id="edita_nombre_curso" required></td>
                            </tr>
                            <tr>
                                <td>Nombre corto:</td>
                                <td><input type="text" name="cursos/nombre_corto" id="edita_nombre_corto" required></td>
                            </tr>
                            <tr>
                                <td>Categor&iacute;a:</td>
                                <td>
                                    <select name="cursos/id_categoria" id="edita_categoria" required>
                                        <?php comboCategorias(); ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Asignatura:</td>
                                <td>
                                    <select name="cursos/id_asignatura" id="edita_asignatura" required>
                                        <?php comboAsignaturas(); ?>
                                    </select>
                                </td>
                            </tr>
                            <!--inicia control de cambios #6-->
                            <tr>
                                <td>Nivel Escolar:</td>
                                <td>
<!--                                    <select id="comboNivelEscolar" id="edita_nivel_escolar">-->
                                    <select id="comboNivelEscolar"  required>
                                        <?php comboNivelesEducativos(); ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Grado Escolar:</td>
                                <td>
                                    <!--<select name="cursos/id_grado_escolar" id="comboGradoEscolares" id="edita_nivel_escolar">-->
                                    <select name="cursos/id_grado_escolar" id="comboGradoEscolares" required>
                                        <?php comboGradoEscolar(); ?>
                                    </select>
                                </td>
                            </tr>
                            <!--termina control de cambios #6-->
                            <tr>
                                <td>Iconograf&iacute;a del curso:</td>
                                <td><input type="file" name="plantilla" class="contenido"/></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Contenido del Curso</b></td>
                            </tr>
                            <tbody id="edita_contenido">

                            </tbody>
                            <input type="hidden" name="cursos/id_curso" id="id_curso" value=""/>

                        </table>
                    </div>
                    <div class="modal-footer">
                        <div class="cargando"></div>  
                        <button id="cancelarUPFile" class="btn" data-dismiss="modal" aria-hidden="true" onclick="location.reload();">Cancelar</button>
                        <button class="btn btn-primary" >Guardar</button>
                    </div>
                </form>
            </div>
            <!--/Editar modal-->

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


            

            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>

    </body>
</html>
