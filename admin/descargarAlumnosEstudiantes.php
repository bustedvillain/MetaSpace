<?php
//Control de cambios #6
//Autor:Omar Nava
//Objetivo: Para combos y avances en descarga
//03-ene-2014
include("../sources/Funciones.php");
verificarSesionAdmnistrador();

if (isset($_GET["mensaje"]))
    $mensaje = html_entity_decode(urldecode($_GET["mensaje"]));
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 21 de Octubre del 2013
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
                <h1>Descargar alumnos estudiantes</h1>

            </div>
            <h4>Usa las listas desplegables para filtrar los usuarios y presiona el botón de descargar.</h4>
            <div class='input-append'>
                <label>Institucion:</label>
                <!--combo insituciones name=idInstitucion-->
                <?php comboInstituciones("idInstitucion"); ?>
            </div>
            <div class='input-append'>
                <label>*Escuela:</label>
                <select id="id_escuela" name="alumnos/id_escuela" required></select>
            </div>       
            <div class='input-append'>
                <label>Grupo:</label>
                <select id="comboGrupos"   style="width: 100%" required>

                </select>
            </div>
            <div class='input-append'>
                <label>Formato:</label>
                <select id="formatoDescarga">
                    <option id="xls">xls</option>
                    <option id="csv">csv</option>
                </select>
            </div> 
            <div class='input-append'>
                <label style="color:white">-</label>
                <a class="btn btn-primary" id="btnDescargar" target="_blank">Descargar Datos</a>
            </div> 
            <table cellpadding="0" cellspacing="0" border="0" id="tablaDescarga" class="display tabla">
                <thead>
                <th>Instituci&oacute;n</th>
                <th>Escuela</th>
                <th>Nivel educativo</th>
                <th>Grado escolar</th>
                <th>Grupo</th>
                <th>Nombre de Usuario</th>
                <th>Correo</th>
                <th>Nombre</th>
                <th>Primer Apellido</th>
                <th>Segundo Apellido</th>
                </thead>
                <tbody>
                <!--<div id="contTabla" style="display: none">-->

                <!--</div>-->
                </tbody>
            </table>
            <!--<input type="button" value ="limpia" onclick="limpiaa();"/>-->
            <?php // tablaUsuarios("estudiante"); ?>
            
                
            <?php include("../template/footer.php"); ?>
            


        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
<!--<script type="text/javascript">
                function limpiaa(){
                    var t = "<tr class='odd'><td>una</td><td>una</td><td>una</td><td>una</td><td>una</td><td>una</td><td>una</td><td>una</td><td>una</td><td>una</td></tr>";
                    var u = "<tr class='even'><td>una</td><td>una</td><td>una</td><td>una</td><td>una</td><td>una</td><td>una</td><td>una</td><td>una</td><td>una</td></tr>";
                    $("#tablaDescarga tbody").html(t);
                    $("#tablaDescarga tbody").html($("#tablaDescarga tbody").html()+u);
                }
            </script>-->
    </body>
</html>
