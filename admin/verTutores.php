<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();
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
                <h1>Ver Tutores</h1>
                <p>A continuaci&oacute;n se muestran los tutores registrados.</p>
                <?php echo $mensaje; ?>
            </div>

            <a class="btn btn-primary btn-success" href="altaTutores.php">Agregar Tutor</a>
            <!--<a class="btn btn-primary" href="descargaDatos.php?tipo=1" target="_blank">Descargar</a>-->
            <div class="btn-group">
                <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
                    Descargar Lista
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="descargaDatos.php?tipo=1&formato=csv" target="_blank">csv</a></li>
                    <li><a href="descargaDatos.php?tipo=1&formato=xls" target="_blank">xls</a></li>
                </ul>
            </div>
            <?php tablaUsuarios("tutor"); ?>

            

            
                
            <?php include("../template/footer.php"); ?>

            <!-- Modal -->
            <div id="verModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Detalle del Tutor</h3>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-bordered">
                        <?php imprimirVerDatosPersonales("tutores"); ?>
                        <tr>
                            <td colspan="2"><b>DATOS ASOCIADOS</b></td>
                        </tr>
                        <tr>
                            <td>Rol de Tutor:</td>
                            <td><div id="ver_rol_tutor"></div></td>
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
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </div>
            <!--/Ver Modal-->

            <!-- Editar Modal -->
            <div id="editarModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Editar Tutor</h3>
                </div>
                <form method="POST" action="gdaEditaUsuario.php" enctype="multipart/form-data">
                    <div class="modal-body">  
                        <p>Los campos marcados con (*) son obligatorios.</p>
                        <table class="table table-hover table-bordered">
                            <?php imprimeEditarDatosPersonales("tutores"); ?>
                            <tr>
                                <td colspan="2"><b>DATOS ASOCIADOS</b></td>
                            </tr>
                            <tr>
                                <td>Rol de Tutor:</td>
                                <td>
                                    <select name="tutor/id_rol_tutor" id="edita_id_rol_tutor">
                                        <?php comboRolesTutores(); ?>
                                    </select>
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
                        <input type="hidden" name="tipo_usuario" value="tutor"/>                                             
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
