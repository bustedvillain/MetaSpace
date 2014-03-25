 <?php require_once '../sources/Funciones.php';?>
<!--
//   Date             Modified by         Change(s)
//   2013-10-03         ONP                 1.0
-->

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/metasDatatables.php"); ?>
        <?php include("../template/heads.php"); ?>
        <?php verificarSesionSenior()?>
    </head>
    <body>
        <!--Up bar-->
        <?php include("../template/menuSr.php"); ?>
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Cursos Abiertos Asignados</h1>
            </div>
            <ul class="breadcrumb">
                    <li><a href="index.php">Inicio</a> <span class="divider">/</span></li>
                    <li class="active">Cursos</li>
            </ul>
            
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <thead>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>Nombre Curso</th>
                        <th>Nombre Curso Abierto</th>
                        <th>Acci&oacute;n</th>
                        
                    </tr>
                </thead>
                <tbody>
                     <?php generaTablaCursoAsignadosTutor(obtenerIDTabla())?>
                <tfoot>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>Nombre Curso</th>
                        <th>Nombre Curso Abierto</th>
                        <th>Acci&oacute;n</th>
                    </tr>
                </tfoot>
            </table>
            
            
            <!-- *******************************************Modal***************************************** -->
            <div id="verModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                            <td>Categoria:</td>
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
            
            <!-- *******************************************Modal***************************************** -->

            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
        <?php include("../senior/jquerySenior.php"); ?>
        <?php include("../coordinador/jqueryTutor.php"); ?>
    </body>
</html>