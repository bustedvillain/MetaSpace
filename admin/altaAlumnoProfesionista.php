<?php include("../sources/Funciones.php"); 
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
                <h1>Alta de Alumno Profesionista</h1>
                <p>Los campos marcados con (*) son obligatorios.</p>
            </div>

            <form action="gdaUsuario.php" method="POST">
                <fieldset>

                    <?php imprimeFormularioGeneral(); ?>

                    <!--Campos especificos-->
                    <legend>DATOS ASOCIADOS:</legend>
                    <div class='input-append'>
                        <label>Empresa:</label>
                        <select name="alumnos/id_empresa">
                            <?php comboEmpresas();?>
                        </select>
                    </div>
                    <div class='input-append'>
                        <label>*Nivel Educativo:</label>
                        <select id="comboNivelEscolar"  required>
                            <?php comboNivelesEducativos(); ?>
                        </select>
                    </div>
                    <div class='input-append'>
                        <label>*Grado Escolar:</label>
                        <select id="comboGradoEscolares" name="alumnos/id_grado_escolar" required>
                            <?php comboGradoEscolar(); ?>
                        </select>
                    </div>
                    <div class='input-append'>
                        <label>Puesto u Ocupaci&oacute;n:</label>
                        <input type="text" name="alumnos/puesto_ocupacion" required/>
                    </div>
                    
                    <input type="hidden" name="tipo_usuario" value="profesionista"/>
                    <input type="hidden" name="alumnos/tipo_alumno" value="1"/>
                    <input type="hidden" name="alumnos/status" value="1"/>
                    <!--Campos especificos-->
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
