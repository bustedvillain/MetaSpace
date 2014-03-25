<?php include("../sources/Funciones.php"); verificarSesionAdmnistrador();?>
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
                <h1>Prueba Manolo</h1>
                <p>A continuaci&oacute;n se presenta un formulario para dar de alta usuarios de forma masiva.</p>
            </div>

            <form id="cargaMasiva" action="gdaPruebaManolo.php"  method="POST" enctype="multipart/form-data">
                <fieldset>
                    <legend>Prueba:</legend>
                    <div class="input-append">
                        <label>Archivo de Excel:</label>
                        <input id="excel" name="excel" type="file" required>                        
                    </div>
                    <div class="progress progress-striped active" id="progress">
                        <div class="bar" style="width:100%; display:none;">
                            <p>Espere un momento...El tiempo de espera depende del n&uacute;mero de usuarios a registrar.</p>
                        </div>
                    </div>
                    <hr><button type="submit" class="btn btn-primary">Guardar</button>
                </fieldset>
            </form>

            <!-- Modal -->
            <div id="error" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Error</h3>
                </div>
                <div class="modal-body">
                    <p>Tipo de extensi&oacute;n para carga masiva no v&aacute;lida. Solo admiten archivos XLS o XLSX.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn primary" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </div>

            <hr>

            <footer>
                <p>&copy; eBlue 2013</p>
            </footer>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>

        <script src="../js/jquery.js"></script>

    </body>
</html>
