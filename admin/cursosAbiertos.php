<?php include("../sources/Funciones.php"); verificarSesionAdminOGestor();?>
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
                <h1>Cursos Abiertos</h1>
                <p>A continuaci&oacute;n se muestran los cursos vinculados con Moodle.</p>
            </div>   

            <ul class="breadcrumb">
                <li class="active">Cursos Abiertos</li>
            </ul>

            <table cellpadding="0" cellspacing="0" border="0" class="display tabla">
                <thead>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Descripci&oacute;n</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Terminaci&oacute;n</th>
                        <th></th>                        
                    </tr>                    
                </thead>
                <tbody>
                    <?php catalogoCursosAbiertos(); ?>
                </tbody>
            </table>

            <!-- Modal -->
            <div id="verModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Ver Curso Abierto</h3>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <td>Nombre del Curso:</td>
                            <td><b class="text-info" id="ver_nombre_curso"></b></td>
                        </tr>
                        <tr>
                            <td>Nombre del Curso Abierto:</td>
                            <td id="ver_nombre_curso_abierto"></td>
                        </tr>
                        <tr>
                            <td>Descripci&oacute;n:</td>
                            <td id="ver_descripcion"></td>
                        </tr>
                        <tr>
                            <td>Fecha de Inicio:</td>
                            <td id="ver_fecha_inicio"></td>
                        </tr>
                        <tr>
                            <td>Fecha de Terminaci&oacute;n:</td>
                            <td id="ver_fecha_fin"></td>
                        </tr>
                        <tbody id="ver_fecha_unidades">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="cargando"></div>  
                    <button class="btn btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                </div>
            </div>
            <!--/Ver Modal-->

            <!-- Editar Modal -->
            <div id="editarModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Editar Curso</h3>
                </div>
                <form id="editarCurso" action="gdaEditarCursoAbierto.php" method="POST">
                    <div class="modal-body">                    
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td>Nombre del Curso:</td>
                                <td><b class="text-info" id="ver_nombre_curso2"></b></td>
                            </tr>
                            <tr>
                                <td>Nombre Curso Abierto:</td>
                                <td><input type="text" name="cursos_abiertos/nombre_curso_abierto" id="edita_nombre_curso_abierto" required></td>
                            </tr>
                            <tr>
                                <td>Descripci&oacute;n:</td>
                                <td><input type="text" name="cursos_abiertos/descripcion" id="edita_descripcion" ></td>
                            </tr>
                            <tr>
                                <td>Fecha Inicio:</td>
                                <td><input type="date" name="cursos_abiertos/fecha_inicio" id="edita_fecha_inicio" required></td>
                            </tr>
                            <tr>
                                <td>Fecha Fin:</td>
                                <td>
                                    <input type="date" name="cursos_abiertos/fecha_fin" id="edita_fecha_fin" required>
                                    <label class="text-error" id="errorFecha"></label>
                                </td>
                            </tr>
                            
                            <tbody id="editar_fechas_unidades">
                                
                            </tbody>
                            <input type="hidden" name="cursos_abiertos/id_curso_abierto" id="id_curso_abierto"/>

                        </table>
                    </div>
                    <div class="modal-footer">
                        <div class="cargando"></div>
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                        <button class="btn btn-primary" type="submit">Guardar</button>
                    </div>
                </form>
            </div>
            <!--/Editar modal-->

            

            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
    </body>
</html>
