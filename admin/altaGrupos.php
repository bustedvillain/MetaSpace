<?php include("../sources/Funciones.php");
verificarSesionAdmnistrador(); ?>
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
                <h1>Alta de Grupos</h1>
                <p>Los campos marcados con (*) son obligatorios.</p>
            </div>

            <form action="gdaGrupo.php" method="POST" id="formAltaGrupo">
                <fieldset>
                    <legend>Datos del Grupo:</legend>
                    <div class="input-append">
                        <label>*Nombre del Grupo:</label>
                        <input type="text" id="nombre_grupo" placeholder="Mi Grupo" name="grupo/nombre_grupo" required>
                        <label class="text-error" id="errorGrupo"></label>
                    </div>   

                    <div class="input-append">
                        <label>Clave:</label>
                        <input id="clave_grupo" type="text" name="grupo/clave" placeholder="DFJ-35" required>
                        <label class="text-error" id="errorGrupo2"></label>
                    </div>  

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
                            <!--<label>Patito:</label>-->
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

                    <legend>Alumnos</legend>
                    <p class="text-warning">A continuaci&oacute;n se muestran los alumnos disponibles para agregar en el grupo, seleccione los alumnos que desea vincular y de click en '->', si desea remover alguno de los seleccionados, de click en el nombre y al boton de '<-'.</p>


                    <br/><br/>
                    <div class="divGrupo">
                        <p>Alumnos Disponibles:</p>
                        <select class="grupo" id="select-from" multiple size="15">

                        </select>
                    </div>

                    <div class="botones">
                        <button type="button" class="btn btn-success" id="btn-add">-></button><br>
                        <button type="button" class="btn btn-info" id="btn-remove"><-</button>
                    </div>

                    <div class="divGrupo">
                        <p>Alumnos Seleccionados:</p>
                        <select class="grupo" name="alumnos[]" id="select-to" multiple size="15"></select>                                            
                    </div>

                    <div style="clear: both"></div>

                    <div class="cargando"></div>

                    <div class="alert alert-info"  style="display:none;" id="noAlumnos">
                        <button type="button" class="close" id="btnErrorCoord">&times;</button>
                        <strong>Aviso</strong>,La Escuela/Empresa aún no tiene alumnos relacionados.
                    </div>

                    <input type="hidden" name="grupo/status" value="1">
                    <input type="hidden" id="cargaAlumnos" value="1">
                    <hr>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </fieldset>
            </form>

            

            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
<?php include("../template/bootstrapAssets.php"); ?>
        <script src="../js/jquery.js"></script>


    </body>
</html>
