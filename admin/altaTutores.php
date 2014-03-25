<?php include("../sources/Funciones.php"); 

/**
 * CHANGE CONTROL 0.99.7
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA: 8 DE ENERO DEL 2014
 * OBJETIVO: SUBIR FOTO POR USUARIO
 */
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
                <h1>Alta de Tutores</h1>
                <p>Los campos marcados con (*) son obligatorios.</p>
            </div>

            <form action="gdaUsuario.php" method="POST" enctype="multipart/form-data">
                <fieldset>

                    <?php imprimeFormularioGeneral("tutores"); ?>
                    
                    <!--Campos especificos-->
                    <legend>Campos espec&iacute;ficos:</legend>      
                    <div class="input-append">
                        <label>Rol del Tutor:</label>
                        <select name="tutor/id_rol_tutor">
                        <?php comboRolesTutores(); ?>
                        </select>
                    </div> 
                    
                    <!--IMAGEN-->
                    <legend>IMAGEN DE PERFIL:</legend>                    
                    <div class="input-append">
                        <div class="custom-input-file"><input type="file" size="1" name="imagen" id="imagen" class="input-file" />
                            <button class="btn btn-primary" style="z-index:1000;">Subir archivo</button>
                            <input type="text" id="url-archivo" />
                        </div>
                    </div>
                    
                    <input type="hidden" name="tutor/status" value="1">
                    <input type="hidden" name="tipo_usuario" value="tutor"/>                   
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
