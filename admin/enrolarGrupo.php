<?php include("../sources/Funciones.php"); verificarSesionAdminOGestor();

/**
 * CHANGE CONTROL 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACIÓN: 21 DE MAYO DE 2014
 * OBJETIVO: CAMBIOS ESTETICOS
 */

if(!isset($_GET["id"])){
    header("cursosAbiertos.php");
}

$cursoAbierto = consultaCursoAbierto($_GET["id"]);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/heads.php"); ?>        
        
    </head>
    <body>
        <!--Up bar-->
        <?php include("../template/menuAdmin.php"); ?>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Asignaci&oacute;n de Grupos</h1>
                <p>Seleccione los grupos que desea asignar al curso. Posteriormente asigne Tutores.</p>
            </div>
            
            <ul class="breadcrumb">
                <li><a href="cursosAbiertos.php">Cursos Publicados</a> <span class="divider">/</span></li>
                <li class="active">Asignar Grupos</li>
            </ul>
            <input type="hidden" id="enrolar" value=""/>
            <form action="gdaEnrolarGrupo.php" id="frmRelCursoGrupo" method="POST">
                <input type="hidden" name="idCursoAbierto" value ="<?php echo $_GET["id"]?>"/>
                <input type="hidden" id ="cambio" name="cambio" value ="0"/>
                <fieldset>
                    <legend>Curso a Asignar</legend>
                    
                    <p><b>Curso Publicado:</b>  <?php echo $cursoAbierto["nombre_curso_abierto"]; ?>  </p>
                    <p><b>Descipci&oacute;n:</b> <?php echo $cursoAbierto["descripcion"]; ?></p>

                    <legend>Grupos</legend>
                    <div class="input-append">
                        <label>Tipo de Grupo:</label>
                        <select name="grupo/tipo_grupo" id="tipo_grupo">
                            <option value="0">Estudiantes</option>
                            <option value="1">Profesionistas</option>
                        </select>
                    </div> 
                    <div class='input-append'>
                        <div class="grupo_estudiante">
                            <label>Instituci&oacute;n:</label>
                            <?php comboInstituciones("idInstitucion"); ?>
                        </div>
                    </div>
                    <div class='input-append' class="grupo_estudiante">
                        <div class="grupo_estudiante">
                            <label>Escuela:</label>
                            <select id="id_escuela" name="grupo/id_escuela" required></select>
                        </div>
                    </div>

                    <div class='input-append'>
                        <div class="grupo_profesionista" style="display:none;">
                            <label>Empresa:</label>
                            <select name="grupo/id_empresa" id="id_empresa">
                                <?php comboEmpresas(); ?>
                            </select>
                        </div>
                    </div>
                    <p>A continuaci&oacute;n se muestran los grupos disponibles para asignar en el curso. </p>
                    <p>Seleccione los grupos disponibles y dé click en 'Agregar' para asignarlos. Si desea remover grupos asignados, seleccionelos y dé click en 'Quitar'.</p>
                    
                    <br/><br/>
                    <div class="divGrupo">
                        <p>Grupos Disponibles:</p>
                        <select class="grupo" name="selectfrom" id="select-from" multiple size="15">
<!--                            <option value="">Grupo 456 Primaria Diaz Ordaz</option>
                            <option value="">Grupo 897 Primaria Benito Juarez</option>-->
                        </select>
                    </div>

                    <div class="botones">
                        <button type="button" class="btn btn-success" id="btn-add">Agregar</button><br>
                        <button type="button" class="btn btn-info" id="btn-remove">Quitar</button>
                    </div>
                    <div class="divGrupo">
                        <p>Grupos Asignados:</p>
                        <select class="grupo2" name="grupos[]" id="select-to" multiple size="15"></select>                                            
                    </div>

                    <div style="clear: both"></div>
                    <div class="cargando"></div>
                    
                    <div class="alert alert-info"  style="display:none;" id="noAlumnos">
                        <button type="button" class="close" id="btnErrorCoord">&times;</button>
                        <strong>Aviso</strong>, La Escuela/Empresa a&uacute;n no tiene grupos relacionados .
                    </div>
                    <input type="hidden" name="grupo/status" value="1">
                    <input type="hidden" id="cargaAlumnos" value="1">
                    <button class="btn btn-primary">Guardar</button>
                </fieldset>
            </form>

            

            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <script src="../js/jquery.js"></script>
        <script type="text/javascript">
            llenaSelectGruposSeleccionados(<?php echo $_GET["id"]?>);
        </script>
    </body>
</html>
