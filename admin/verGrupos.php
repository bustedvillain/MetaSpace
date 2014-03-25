<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();
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
                <h1>Ver Grupos</h1>
                <p>A continuaci&oacute;n se muestran grupos registrados.</p>
            </div>

            <ul class="breadcrumb">
                <li class="active">Grupos</li>
            </ul>
            <a href="altaGrupos.php" class="btn btn-success">Agregar</a>
            <table cellpadding="0" cellspacing="0" border="0" class="display tabla">
                <thead>
                    <tr>
                        <th>Acci&oacute;n</th>  
                        <th>Alumnos</th>
                        <th>Grupo</th>
                        <th>Clave</th>
                        <th>Tipo de Grupo</th>
                        <th>Instituci&oacute;n</th>
                        <th>Escuela</th>
                        <th>Empresa</th>                        
                        <th></th>
                    </tr>                    
                </thead>
                <tbody>
                    <?php consultaGruposActivos(); ?>
                </tbody>
            </table>

            

            
                
            <?php include("../template/footer.php"); ?>            

            <!-- Editar Modal -->
            <div id="editarModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Editar Gestor de Contenido</h3>
                </div>
                <form id="frmConfirmar" action="gdaEditaGrupo.php" method="POST">
                    <div class="modal-body">                    
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td>Nombre del Grupo:</td>
                                <td>
                                    <input type="text" id="edita_nombre_grupo" name="grupo/nombre_grupo" required>
                                    <label class="text-error" id="errorGrupo"></label>
                                </td>
                            </tr>
                            <tr>
                                <td>Clave:</td>
                                <td><input type="text" name="grupo/clave" id="edita_clave"></td>
                            </tr>
                            <tr>
                                <td>Tipo de Grupo</td>
                                <td>
                                    <select name="grupo/tipo_grupo" id="tipo_grupo">
                                        <option value="0">Estudiantes</option>
                                        <option value="1">Profesionistas</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="grupo_estudiante">
                                <td>Instituci&oacute;n:</td>
                                <td><?php comboInstituciones("idInstitucion"); ?> </td>
                            </tr>
                            <tr class="grupo_estudiante">
                                <td>Escuela:</td>
                                <td>
                                    <select id="id_escuela" name="grupo/id_escuela" required></select>
                                </td>
                            </tr>
                            <tr class="grupo_profesionista">
                                <td>Empresa</td>
                                <td>
                                    <select name="grupo/id_empresa" id="id_empresa" required>
                                        <?php comboEmpresas(); ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="alert alert-info">                                    
                                        <button type="button" class="close" id="btnErrorCoord">&times;</button>
                                        <strong>Aviso</strong>, Si cambia el grupo de Escuela o Empresa, este ser&aacute; vaciado y deber&aacute; volver a agregar alumnos.
                                    </div>
                                </td>
                            </tr>
                            
                            <input type="hidden" name="id_grupo" id="id_grupo"/>

                        </table>
                    </div>
                    <div class="modal-footer">
                        <div class="cargando"></div>
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                        <button class="btn btn-primary" id="confirmar" type="button">Guardar</button>
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
