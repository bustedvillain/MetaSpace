<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
if (isset($_GET["mensaje"]))
$mensaje = html_entity_decode(urldecode($_GET["mensaje"]));
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
                <p>Cat&aacute;logo de</p>
                <h1>Escuelas</h1>
                <!--<p>Cat&aacute;logo de escuelas relacionado a una instituci&oacute;n.</p>-->
                <?php if (isset($_GET["mensaje"]))  echo $mensaje; ?>
            </div>   
            <a class="btn btn-success" href="#myModal" role="button" data-toggle="modal">Agregar</a>
            <table cellpadding="0" cellspacing="0" border="0" class="display tabla">
                <thead>
                    <tr>
                        <th>Acci&oacute;n</th>
                        <th>Instituci&oacute;n</th>
                        <th>Escuela</th>
                    </tr>                    
                </thead>
                <tbody>
                    <?php consultaEscuelas(); ?>
                </tbody>
            </table>

            <!-- Modal -->
            <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Agregar</h3>
                </div>

                <form action="gdaEscuela.php" method="POST">          
                    <div class="modal-body">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td>Instituci&oacute;n:</td>
                                <td><?php comboInstituciones(); ?></td>
                            </tr>
                            <tr>
                                <td>Escuela:</td>
                                <td><input name="escuela" type="text" required/></td>
                            </tr>
                        </table>
                    </div>                
                    <div class="modal-footer">
                        <button class="btn btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
            <!--/Ver Modal-->

            <!-- Editar Modal -->
            <div id="editarModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel">Editar Escuela</h3>
                </div>
                <form action="gdaEditaEscuela.php" method="POST">     
                    <div class="modal-body">                    
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td>Instituci&oacute;n:</td>
                                <td><?php comboInstituciones("idInstitucion"); ?></td>
                            </tr>
                            <tr>
                                <td>Escuela:</td>
                                <td><input type="text" name="escuela" id="editaAtributo"/></td>
                            </tr>  
                            <input type="hidden" name="idEscuela" id="idAtributo"/>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                        <button class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
            <!--/Editar modal-->

            
            
                
            <?php include("../template/footer.php"); ?>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>
        <?php include("../template/scriptsDatatables.php"); ?>
    </body>
</html>
