<?php require_once '../sources/Funciones.php';?>
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
                <h1>Senior Asignados al Grupo</h1>
            </div>
            <ul class="breadcrumb">
                    <li><a href="index.php">Inicio</a> <span class="divider">/</span></li>
                    <li><a href="cursosAsignados.php">Cursos</a><span class="divider">/</span></li>
                    <li><a href="gruposAsignados.php?idCursoAbierto=<?php echo ($_GET['idCursoAbierto']);?>">Grupos</a><span class="divider">/</span></li>
                    <li class="active">Tutores Senior</li>
            </ul>
            <h3>Curso Abierto: <b class="text-info"><?php echo getNombreCursoAbierto($_GET['idCursoAbierto']);?></b></h3>
            <h3>Grupo: <b class="text-info"> <?php echo getNomreGrupo($_GET['idGrupo']);?></b></h3>
            
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <thead>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>ID Tutor</th>
                        <th>Nombre</th>
                        <th>Apellido(s)</th>
                    </tr>
                </thead>
                <tbody>
                   <?php tablaTutoresSeniorGrupo($_GET['idGrupo'])?>
                           
                </tbody>
                <tfoot>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>ID Tutor</th>
                        <th>Nombre</th>
                        <th>Apellido(s)</th>
                    </tr>
                </tfoot>
            </table>
             <?php include("../senior/verActividadTutor.php"); ?>
             <?php //include("../senior/verTutorJr.php"); ?>

            <div id="verModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Detalle del Tutor</h3>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-bordered">
                        <?php imprimirVerDatosPersonales(); ?>
                        <tr>
                            <td colspan="2"><b>Campos Espec&iacute;ficos</b></td>
                        </tr>
                        <tr>
                            <td>Rol de Tutor:</td>
                            <td><div id="ver_rol_tutor"></div></td>
                        </tr>                        
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="cargando"></div>
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </div>
            
            
            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
        <?php include("../senior/jquerySenior.php"); ?>
    </body>
</html>