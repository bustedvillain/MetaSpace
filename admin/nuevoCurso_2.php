<?php 
//Control de cambios #5
//26-dic-2013
//Modificaciones requeridas por eblue para la plantilla (aer-api)
    include("../sources/Funciones.php");
    verificarSesionAdminOGestor();
    
    if(isset($_GET["id"])){
        $idCurso=$_GET["id"]; 
        $fullname=$_GET["fullname"];
        $shortname=$_GET["shortname"];
?>
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
                <h1>Vinculaci&oacute;n</h1>
                <p>Vinculaci&oacute;n de curso de Moodle con el sistema de gesti&oacute;n. Los campos marcados con (*) son obligatorios.</p>
            </div> 
            
            <ul class="breadcrumb">
                <li><a href="nuevoCurso.php">Nuevo Curso</a> <span class="divider">/</span></li>
                <li class="active">Vinculaci&oacute;n</li>
            </ul>
            
            <form action="gdaCurso.php">
                <fieldset>
                    <legend>Curso de Moodle</legend>
                    <div class="input-append">
                        <h3 class="text-info"><?php echo $fullname; ?></h3>
                    </div>
                    <!--//inicia control de cambios #5-->
                    <legend>Relaci&oacute;n unidades-series API</legend>
                    <!--//Termina control de cambios #5-->
                    <div class="input-append">
                        
                    </div>
                    <?php cursosMoodleParaSerie($idCurso); ?>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </fieldset>   
                <input type="hidden" name="idCurso" value="<?php echo $idCurso; ?>"/>
            </form>
            
            
            
            <?php include("verModalLinkeo.php"); ?>
            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../sources/MetasJquerys.php"); ?>

    </body>
</html>
<?php 
    }else{
        header("Location:nuevoCurso.php");
    }
?>

