<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/heads.php"); ?>
        <style>
            .custom-input-file {
                overflow: hidden;
                position: relative;
                cursor: pointer;
            }

            .custom-input-file .input-file {
                margin: 0;
                padding: 0;
                outline: 0;
                font-size: 10000px;
                border: 10000px solid transparent;
                opacity: 0;
                filter: alpha(opacity=0);
                position: absolute;
                right: -1000px;
                top: -1000px;
                cursor: pointer;
            }
        </style>

    </head>

    <body>
        <!--Up bar-->
        <?php include("../template/menuAdmin.php"); ?>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Alta Masiva de Usuarios</h1>
            </div>

            <form id="cargaMasiva" action="gdaAltaMasiva.php"  method="POST" enctype="multipart/form-data">
                <fieldset>
                    <!--<legend>Alta Masiva:</legend>-->                 
                    <div class="input-append">
                        <label>Tipo:</label>
                        <select name="tipo_carga">
                            <!--<option value="combo">Alumnos + Padres de Familia + Profesores de Aula</option>-->
                            <option value="estudiantes">Alumnos Estudiantes</option>
                            <option value="profesionistas">Alumnos Profesionistas</option>
                            <option value="padres">Padres de Familia</option>
                            <option value="profesores">Profesores de Aula</option>
                            <option value="tutores">Tutores</option>
                            <option value="gestores">Gestores de Contenido</option>
                            <option value="admin">Administradores</option>
                        </select>                       
                    </div>
                    <div class="input-append">
                        <label>Archivo de Excel:</label>                      
                        <div class="custom-input-file"><input type="file" size="1" id="excel" name="excel" required class="input-file" />
                            <button class="btn btn-primary" style="z-index:1000;">Subir archivo</button>
                            <input type="text" id="url-archivo" />
                        </div>
                    </div>
                    <div class="progress progress-striped active" id="progress">
                        <div class="bar" style="width:100%; display:none;">
                            <p>Espere un momento...El tiempo de espera depende del n&uacute;mero de usuarios a registrar.</p>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </fieldset>
            </form>

            <!-- Modal -->
            <div id="error" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Error</h3>
                </div>
                <div class="modal-body">
                    <p>Tipo de extensi&oacute;n para carga masiva no v&aacute;lida. Solo admiten archivos XLS o XLSX.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn primary" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                </div>
            </div>

            

            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>

        <script src="../js/jquery.js"></script>
        <script>
            $(document).ready(function() {
                /* En el change del campo file, cambiamos el val del campo ficticio por el del verdadero */
                $('#excel').change(function() {
                    $('#url-archivo').val($(this).val());
                });
            });
        </script>

    </body>
</html>
