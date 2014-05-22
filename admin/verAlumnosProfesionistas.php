<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
if (isset($_GET["mensaje"]))
$mensaje = html_entity_decode(urldecode($_GET["mensaje"]));

/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 21 de Octubre del 2013
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
                <h1>Ver Usuarios</h1>
                <p>A continuaci&oacute;n se muestran los usuarios registrados.</p>
                <?php echo $mensaje; ?>
            </div>

            <a class="btn btn-primary btn-success" href="altaAlumnoProfesionista.php">Agregar Usuario</a>
            <!--<a class="btn btn-primary" href="descargaDatos.php?tipo=6" target="_blank">Descargar Datos</a>-->
            <div class="btn-group">
                <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
                    Descargar Lista
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="descargaDatos.php?tipo=6&formato=csv" target="_blank">csv</a></li>
                    <li><a href="descargaDatos.php?tipo=6&formato=xls" target="_blank">xls</a></li>
                </ul>
            </div>
            <?php tablaUsuarios("profesionista"); ?>

            

            
                
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
                            <td>Empresa</td>
                            <td ><div id="ver_empresa"></div></td>
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
                <form method="POST" action="gdaEditaUsuario.php">
                    <div class="modal-body">   
                        <p>Los campos marcados con (*) son obligatorios.</p>
                        <table class="table table-hover table-bordered">
                            <?php imprimeEditarDatosPersonales(); ?>
                            <tr>
                                <td colspan="2"><b>DATOS ASOCIADOS</b></td>
                            </tr>
                            <tr>
                                <td>Empresa:</td>
                                <td>
                                    <select name="alumnos/id_empresa" id="edita_id_empresa">
                                        <?php comboEmpresas(); ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="id_datos_personales" id="id_datos_personales" value=""/>
                        <input type="hidden" name="tipo_usuario" value="profesionista"/>
                    </div>
                    <div class="modal-footer">
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
