<?php 
//Control de cambios #&
//Autor:Omar Nava
//Objetivo: Combos nivel/grados
//03-ene-2014

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
    </head>

    <body>
        <!--Up bar-->
        <?php include("../template/menuAdmin.php"); ?>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Alta de Alumno Estudiante</h1>
                <p>Los campos marcados con (*) son obligatorios.</p>
            </div>

            <form action="gdaUsuario.php" method="POST" enctype="multipart/form-data">
                <fieldset>

                    <?php imprimeFormularioGeneral(); ?>

                    <!--Campos especificos-->
                    <legend>DATOS ASOCIADOS:</legend>
                    <input type="hidden" name="alumnos/puesto_ocupacion" value="Estudiante">
                    
                    <div class='input-append'>
                        <label>Matrícula:</label>
                        <input name="alumnos/matricula" type='text' placeholder='alu_09283748'>
                    </div>
                    <div class='input-append'>
                        <label>Institución:</label>
                        <!--combo insituciones name=idInstitucion-->
                        <?php comboInstituciones("idInstitucion"); ?>
                    </div>
                    <div class='input-append'>
                        <label>*Escuela:</label>
                        <select id="id_escuela" name="alumnos/id_escuela" required></select>
                    </div>           
                    <!--inicia control de cambios #6-->
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
                    <!--termina control de cambios #6-->
                    <div class='input-append'>
                        <label>Profesor de Aula:</label>
                        <select name="alumnos/id_profesor_aula">
                            <?php comboProfesoresAula(); ?>
                        </select>
                    </div>
                    <div class='input-append'>
                        <label>Padre:</label>
                        <select name="alumnos/id_padre">
                            <?php comboPadres(); ?>
                        </select>
                    </div>     
                    
                    <!--IMAGEN-->
                    <legend>IMAGEN DE PERFIL:</legend>                    
                    <div class="input-append">
                        <div class="custom-input-file"><input type="file" size="1" id="imagen" name="imagen" class="input-file" />
                            <button class="btn btn-primary" style="z-index:1000;">Subir archivo</button>
                            <input type="text" id="url-archivo" />
                        </div>
                    </div>
                    
                    <input type="hidden" name="tipo_usuario" value="estudiante"/>
                    <input type="hidden" name="alumnos/tipo_alumno" value="0"/>
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
        <?php include("../template/scriptsDatatables.php"); ?>       

    </body>
</html>
