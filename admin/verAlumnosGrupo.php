<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 12 de Noviembre del 2013
 */

$idGrupo = $_GET["id"];

$infoGrupo = getInfoGrupo($idGrupo);
$nombreGrupo = $infoGrupo["nombre_grupo"];

if ($infoGrupo["tipo_grupo"] == "0") {
    $tipo_usuario="estudiante";
    $nombreInstitucion = (getNombreInstitucion($infoGrupo["id_escuela"]));
    $nombreEscuela = limpiarTexto(getNombreEscuela($infoGrupo["id_escuela"]));
    $idLugar = $infoGrupo["id_escuela"];

    $datosGrupo = <<<datos
                <p><b>Instituci&oacute;n:</b> $nombreInstitucion</p>
                <p><b>Escuela:</b> $nombreEscuela</p>
                <p><b>Grupo:</b> $nombreGrupo</p>
datos;
} else if ($infoGrupo["tipo_grupo"] == "1") {
    $tipo_usuario="profesionista";
    $nombreEmpresa = (getNombreEmpresa($infoGrupo["id_empresa"]));
    $idLugar = $infoGrupo["id_empresa"];
    $datosGrupo = <<<datos
                <p><b>Empresa:</b> $nombreEmpresa</p>
                <p><b>Grupo:</b> $nombreGrupo</p>
datos;
}
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
                <h1>Ver Alumnos en Grupo</h1>
                <p>A continuaci&oacute;n se muestran los a alumnos vinculados al grupo.</p>                
            </div>
            
            <ul class="breadcrumb">
                <li><a href="verGrupos.php">Grupos</a> <span class="divider">/</span></li>
                <li class="active">Alumnos en Grupo</li>
            </ul>

            <legend>Datos del Grupo</legend>
            <?php echo $datosGrupo; ?> 

            <table cellpadding="0" cellspacing="0" border="0" class="display tabla">
                <thead>
                    <tr>
                        <th>Acci&oacute;n</th>  
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Primer Apellido</th>
                        <th>Segundo Apellido </th>                        
                    </tr>                    
                </thead>
                <tbody>
                    <?php consultaAlumnosGrupo($idGrupo, $tipo_usuario); ?>
                </tbody>
            </table>
            
            

            
                
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
                            <td colspan="2"><b>Campos Espec&iacute;ficos</b></td>
                        </tr>
                        <tr class="grupo_estudiante">
                            <td>Matricula</td>
                            <td ><div id="ver_matricula"></div></td>
                        </tr>
                        <tr class="grupo_estudiante">
                            <td>Padre</td>
                            <td ><div id="ver_padre"></div></td>
                        </tr>
                        <tr class="grupo_estudiante">
                            <td>Profesor de Aula</td>
                            <td ><div id="ver_profesor_aula"></div></td>
                        </tr>
                        <tr class="grupo_estudiante">
                            <td>Nivel Educativo</td>
                            <td ><div id="ver_nivel_educativo"></div></td>
                        </tr>
                        <tr class="grupo_estudiante">
                            <td>Grado Escolar</td>
                            <td ><div id="ver_grado_escolar"></div></td>
                        </tr>
                        <tr class="grupo_estudiante">
                            <td>Instituci&oacute;n</td>
                            <td ><div id="ver_institucion"></div></td>
                        </tr>
                        <tr class="grupo_estudiante">
                            <td>Escuela</td>
                            <td ><div id="ver_escuela"></div></td>
                        </tr>
                        <tr class="grupo_profesionista">
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
            
            <input type="hidden" id="verAlumnosGrupo" value="1">


        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>

    </body>
</html>
