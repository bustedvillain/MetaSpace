<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();
if($_GET["id"]){
    

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
                <h1>Ver Grupos Enrolados</h1>
                <p>A continuaci&oacute;n se muestran los grupos enrolados al curso abierto.</p>
            </div>   

            <ul class="breadcrumb">
                <li><a href="cursosAbiertos.php">Cursos Abiertos</a><span class="divider">/</span></li>
                <li class="active">Grupos en el Curso</li>
            </ul>
            
            <h3>Curso Abierto: <b class="text-info"><?php echo getNombreCursoAbierto($_GET["id"]); ?></b></h3>

            <!--<a href="adminTutoresCursoAbierto.php?id=<?php echo $_GET["id"]; ?>" class="btn btn-success">Administrar Tutores</a>-->
            <table cellpadding="0" cellspacing="0" border="0" class="display tabla">
                <thead>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>id</th>
                        <th>Grupo</th>
                        <th>Clave Grupo</th>
                        <th>Instituci&oacute;n</th>
                        <th>Escuela</th>
                        <th>Empresa</th>
                    </tr>                    
                </thead>
                <tbody>
                    <?php consultaGruposCurso($_GET["id"]); ?>
                </tbody>
            </table>


            

            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
    </body>
</html>
    <?php
} else {
    header("Location:cursosAbiertos.php");
}
?>
