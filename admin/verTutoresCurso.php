<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();
if($_GET["id"]){
    

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
                <h1>Tutores en el Curso</h1>
                <p>A continuaci&oacute;n se muestran los tutores enrolados en el curso.</p>
            </div>   

            <ul class="breadcrumb">
                <li><a href="cursosAbiertos.php">Cursos Abiertos</a><span class="divider">/</span></li>
                <li class="active">Tutores en el Curso</li>
            </ul>
            
            <h3>Curso Abierto: <b class="text-info"><?php echo getNombreCursoAbierto($_GET["id"]); ?></b></h3>
            
            <?php if(validarGruposCurso($_GET["id"])===true){ ?>
            <a href="enrolarTutores.php?id_curso_abierto=<?php echo $_GET["id"]; ?>&admin=yes" class="btn btn-success">Administrar Tutores</a>
            <?php } ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display tabla">
                <thead>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>id</th>
                        <th>Grupo</th>
                        <th>Rol</th>
                        <th>Nombre</th>                                               
                    </tr>                    
                </thead>
                <tbody>
                    <?php consultaTutoresCursoAbierto($_GET["id"]); ?>
                </tbody>
            </table>

            <!-- Modal -->
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
    <?php
} else {
    header("Location:cursosAbiertos.php");
}
?>
