 <?php require_once '../sources/Funciones.php';?>
<!--
//   Date             Modified by         Change(s)
//   2013-10-03         ONP               1.0
-->

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/metasDatatables.php"); ?>
        <?php include("../template/heads.php"); ?>
       
        <?php verificarSesionSenior()?>
        
    </head>
    <body>
        <!--Up bar-->
        <?php include("../template/menuSr.php"); ?>
        <div class="container">
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Grupos Asignados</h1>
            </div>
            <ul class="breadcrumb">
                    <li><a href="index.php">Inicio</a> <span class="divider">/</span></li>
                    <li><a href="cursosAsignados.php">Cursos</a> <span class="divider">/</span></li>
                    <li class="active">Grupos</li>
            </ul>
            <h3>Curso: <b class="text-info"><?php echo getNombreCursoAbierto($_GET['idCursoAbierto']);?></b></h3>
            
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <thead>
                    <tr>
                        <th>Acci&oacute;n</th>  
                        <th>Alumnos</th>
                        <th>Grupo</th>
                        <th>Clave</th>
                        <th>Tipo de Grupo</th>
                        <th>Instituci&oacute;n</th>
                        <th>Escuela</th>
                        <th>Empresa</th>  
                    </tr>
                </thead>
                <tbody>
                    
                   <?php tablaGruposTutorSeniorAsignadosTutor($_GET['idCursoAbierto'],  
                                                     obtenerIDTabla())?>                            
                </tbody>
                <tfoot>
                    <tr>
                        <th>Acci&oacute;n</th>  
                        <th>Alumnos</th>
                        <th>Grupo</th>
                        <th>Clave</th>
                        <th>Tipo de Grupo</th>
                        <th>Instituci&oacute;n</th>
                        <th>Escuela</th>
                        <th>Empresa</th>  
                    </tr>
                </tfoot>
            </table>



            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
        <?php include("../senior/jquerySenior.php"); ?>
    </body>
</html>