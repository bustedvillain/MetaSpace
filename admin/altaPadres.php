<?php 
/**
 * CHANGE CONTROL 0.99.7
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA: 8 DE ENERO DEL 2014
 * OBJETIVO: SUBIR FOTO POR USUARIO
 */
include("../sources/Funciones.php"); 
verificarSesionAdmnistrador();?>

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
                <h1>Alta de Padre de Familia</h1>
                <p>Los campos marcados con (*) son obligatorios.</p>
            </div>

            <form action="gdaUsuario.php" method="POST" enctype="multipart/form-data">
                <fieldset>

                    <?php imprimeFormularioGeneral(); ?>

                    <!--Campos especificos-->
                    <legend>DATOS ASOCIADOS:</legend>
                    <div class='input-append'>
                        <label>*Nivel Educativo:</label>
                        <select id="comboNivelEscolar" required>
                            <?php comboNivelesEducativos(); ?>
                        </select>
                    </div>
                    <div class='input-append'>
                        <label>*Grado Escolar:</label>
                        <select id="comboGradoEscolares" name="padres/id_ultimo_grado_escolar" required>
                            <?php comboGradoEscolar(); ?>
                        </select>
                    </div>
                    <!--Campos especificos-->
                    
                    <!--IMAGEN-->
                    <legend>IMAGEN DE PERFIL:</legend>                    
                    <div class="input-append">
                        <div class="custom-input-file"><input type="file" size="1" id="imagen" name="imagen" class="input-file" />
                            <button class="btn btn-primary" style="z-index:1000;">Subir archivo</button>
                            <input type="text" id="url-archivo" />
                        </div>
                    </div>
                    
                    <input type="hidden" name="padres/status" value="1">
                    <input type="hidden" name="tipo_usuario" value="padre"/>

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
