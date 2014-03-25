<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();

/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 16 de Diciembre del 2013
 */
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
                <h1>Configuración</h1>
                <p>Configuraci&oacute;n general del sistema</p>
            </div>

            
            <legend>Configuración del Sistema:</legend>
            <form action="../sources/ControladorConfiguracion.php" method="POST">
                
                <div class='input-append'>
                        <label>Correo Electr&oacute;nico de Contacto:</label>
                        <input name="correoContacto" required="" type='text' placeholder='' value="<?php echo obtenerValorCorreoContacto();?>">
                </div>
                <div class='input-append'>
                        <label>WebService Token de Moodle:</label>
                        <input name="tokenMoodle" required="" type='text' placeholder='' value="<?php echo obtenerToken();?>">
                </div><br/>
<!--                <button type="submit" class="btn btn-primary">Guardar Correo</button>
            </form>-->
            <legend>Tiempos de inactividad (Minutos)</legend>
            <!--<form action="../sources/ControladorConfiguracion.php" method="POST">-->
                
                <div class='input-append'>
                        <label>Administrador:</label>
                        <input name="inactividadAdministrador" required="" type='text' placeholder='' value="<?php echo obtenerValorInactividadAdministrador();?>">
                </div>
                <div class='input-append'>
                        <label>Gestor de contenidos:</label>
                        <input name="inactividadGestor" required="" type='text' placeholder='' value="<?php echo obtenerValorInactividadGestor();?>">
                </div>
                <div class='input-append'>
                        <label>Tutor:</label>
                        <input name="inactividadTutores" required="" type='text' placeholder='' value="<?php echo obtenerValorInactividadTutores();?>">
                </div>
                <div class='input-append'>
                        <label>Alumno:</label>
                        <input name="inactividadAlumno" required="" type='text' placeholder='' value="<?php echo obtenerValorInactividadAlumno();?>">
                </div>
                <div class='input-append'>
                        <label>Padre:</label>
                        <input name="inactividadPadre" required="" type='text' placeholder='' value="<?php echo obtenerValorInactividadPadre();?>">
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
            
            

            
                
            <?php include("../template/footer.php"); ?>


            <!--/Ver Modal-->



        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
<?php include("../template/scriptsDatatables.php"); ?>

    </body>
</html>