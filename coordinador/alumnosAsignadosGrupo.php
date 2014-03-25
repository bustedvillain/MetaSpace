<?php require_once '../sources/Funciones.php';?>
<!--
//   Date             Modified by         Change(s)
//   2013-10-03         HMP                 1.0
-->



<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/metasDatatables.php"); ?>
        <?php include("../template/heads.php"); ?>
        
        <?php verificarSesionCoordinador()?>
    </head>
    <body>
        <!--Up bar-->
        <?php include("../template/menuCoordinador.php"); ?>
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Alumnos Asignados al Grupo</h1>
            </div>
            <ul class="breadcrumb">
                    <li><a href="index.php">Inicio</a> <span class="divider">/</span></li>
                    <li><a href="cursosAsignados.php">Cursos</a><span class="divider">/</span></li>
                    <li><a href="gruposAsignados.php?idCursoAbierto=<?php echo ($_GET['idCursoAbierto']);?>">Grupos</a><span class="divider">/</span></li>
                    <li class="active">Alumnos</li>
            </ul>
            <h3>Curso Abierto: <b class="text-info"><?php echo getNombreCursoAbierto($_GET['idCursoAbierto']);?></b></h3>
            <h3>Grupo: <b class="text-info"><?php echo getNomreGrupo($_GET['idGrupo']);?></b></h3>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <thead>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>Nombre Usuario</th>
                        <th>Correo</th>
                        <th>Nombre</th>
                        <th>Apellido(s)</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php
                    tablaAlumnos($_GET['idGrupo'])
                    ?>
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>Nombre Usuario</th>
                        <th>Correo</th>
                        <th>Nombre</th>
                        <th>Apellido(s)</th>
                    </tr>
                </tfoot>
            </table>
            <?php //include 'verAlumno.php';?>
            
            
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
                            <td colspan="2"><b>Campos Espec&iacute;ficos</b></td>
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
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="cargando"></div>
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                </div>
            </div>


            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
        <?php include '../jr/jqueryTutorJr.php';?>
        <?php include("../senior/jquerySenior.php"); ?>
    </body>
</html>