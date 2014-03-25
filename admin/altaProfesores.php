<?php 
//Control de cambios #6
//Autor:Omar Nava
//Objetivo: Alta profesores
//03-ene-2014
include("../sources/Funciones.php"); verificarSesionAdmnistrador();?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/heads.php"); ?>
        <?php include("../template/cssDatePicker.php"); ?>
    </head>

    <body>
        <!--Up bar-->
        <?php include("../template/menuAdmin.php"); ?>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Alta de Profesores de Aula</h1>
                <p>Los campos marcados con (*) son obligatorios.</p>
            </div>

            <form action="gdaUsuario.php" method="POST">
                <fieldset>

                    <?php imprimeFormularioGeneral(); ?>
                    
                    <!--Campos especificos-->
                    <legend>DATOS ASOCIADOS:</legend>
                    <div class="input-append">
                        <label>*Puesto u Ocupaci&oacute;n:</label>
                        <input type="text" placeholder="Profesor" name="profesores_aula/puesto_ocupacion" required>
                    </div> 
                    <div class='input-append'>
                        <label>*Institución:</label>
                        <!--combo insituciones name=idInstitucion-->
                        <?php comboInstituciones("idInstitucion"); ?>
                    </div>
                    <div class='input-append'>
                        <label>*Escuela:</label>
                        <select id="id_escuela" name="profesores_aula/id_escuela" required></select>
                    </div>
                    <div class='input-append'>
                        <!--inicia control de cambios #6-->
                        <label>*Nivel Educativo en que imparte clase:</label>
                        <select id="comboNivelEscolar" required>
                            <?php comboNivelesEducativos(); ?>
                        </select>
                    </div>
                    <div class="input-append">
                        <label>*Grado escolar en que imparte clase:</label>
                        <select id="comboGradoEscolares" name="profesores_aula/id_grado_escolar_enrolado" required>
                            <?php comboGradoEscolar(); ?>
                        </select>
                        <!--termina control de cambios #6-->
                    </div> 
                    <!--Campos especificos-->
                    <input type="hidden" name="profesores_aula/status" value="1"/>
                    <input type="hidden" name="tipo_usuario" value="profesor"/>
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
        <?php include("../template/jsDatePicker.php"); ?>

    </body>
</html>
