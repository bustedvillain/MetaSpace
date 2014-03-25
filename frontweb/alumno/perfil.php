<?php
//Archivo creado para control de cambios #7
include '../../sources/Funciones.php';
verificarSesionAlumno();
if(navegadorActual() == "Internet Explorer"){
    $metodo = "muestraFileI();";
    $estilo = "none";
    $idForm = '';
    $idFile = '';
    $idCmd = '';
}else{
    $metodo = "muestraImagenUp();";
    $estilo = "none";
    $idForm = ' id="frmSubeImagen" ';
    $idFile = ' id="inputSubida" ';
    $idCmd = ' id="btnSube" ';
    
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Perfil | META Space</title>
        <meta name="lang" content="es" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="shortcut icon" href="/favicon.ico" />

        <link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/general.css" type="text/css" rel="stylesheet"  media="all" />

        
        
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>
        <link href='http://fonts.googleapis.com/css?family=Dosis:400,300,500' rel='stylesheet' type='text/css'/>
        <!--<script type="text/javascript" src="../../js/jquery-1.7.min.js"></script>-->
        <script type="text/javascript" src="../../js/jquery-1.10.0.min.js"></script>
        <script type="text/javascript">
            $(document).on("ready", function() {
                inicio();
            });
            function inicio() {
                $("#inputSubida").change(function() {
//                    alert('la subiré');
//                    $("#frmSubeImagen").submit();
                        $("#btnSube").trigger("click");
//                        window.location.href = window.location.href;
                });
            }


            function muestraImagenUp() {
                $("#btn_int_capturar").click(function() {
                    $("#inputSubida").trigger("click");
//                    debugConsole('hubo click');
                });
            }
            function muestraFileI() {

                $("#frmImagen").toggle();
            }
        </script>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div id="nombre" class="fondo_naranja">
                    <h3><span>Alumno: &nbsp;</span><?php nombreLogout(); ?></h3>
                </div>

                <div id="logo"><a href="index.php"><img src="../img/logo_meta.gif" width="347" height="72" alt="Ir a inicio de Meta Space" /></a></div>
                <div class="separador"></div>
                <div id="buscador">
                    <form id="" name=""  action="" enctype="multipart/form-data" method="post">
                        <div id="buscador_alu"><input name="buscador" size="35" maxlength="35" type="text" id="Buscador" placeholder="Buscar..."></div>
                        <div id="lupa"><input name="submit" class="btn_envio" id="btn_envio" value="" type="submit"></div>
                    </form>

                </div>
                <div class="separador"></div>
                <div class="btn_perfil"><a href="perfil.php"><img src="../img/alumno/icono_perfil.png" width="55" height="30" />mi perfil</a></div>
                <div class="separador"></div>
                <div class="btn_perfil"><a href="como_navegar.php"><img src="../img/alumno/icono_navegar.png" width="55" height="30" />como navegar</a></div>
                <div class="separador"></div>
                <div class="btn_perfil"><a href="amigos.php"><img src="../img/alumno/icono_amigos.png" width="55" height="30" />amigos</a></div>
                <!--<div id="foto_perfil" class="fondo_naranja"><img src="<?php echo rutaFotoDeSesion(); ?>" width="77" height="78" class="margin10" /></div>-->
                <div id="foto_perfil" class="fondo_naranja"><img src="<?php echo rutaFotoDeSesion(); ?>" width="77" height="78" class="margin10" /></div>

            </div>

            <div id="central">
                <div id="destacado"><img src="../img/alumno/destacado.gif" width="153" height="486" /></div>
                <div id="lateral_nav">
                    <div id="lateral_nav_aprender"><a title="" href="index.php"></a></div>
                    <div id="lateral_nav_baul"><a title="" href="baul.php"></a></div>
                    <div id="lateral_nav_mi_locker"><a title="" href="mi_locker.php"></a></div>
                    <div id="lateral_nav_biblioteca"><a title="" href="biblioteca.php"></a></div>


                </div>

                <div id="alumno_contenido">
                    <h2>Mi perfil</h2>
                    <?php
                    infoMiperfilAlumno(obtenerIDTabla());
                    ?>
                    <div id="btn_int">
                        <div id="btn_int_regresar"><a href="javascript:history.back()"> </a></div>
                        <div id="btn_int_capturar"><a  onclick="<?php echo $metodo; ?>"></a></div>
                        <!--<div id="btn_int_capturar"><a  onclick=""></a></div>-->

                    </div>
                    <div id="frmImagen" style="display:<?php echo $estilo; ?>; margin-left: 205px;">
                        <form method="post" <?php echo $idForm; ?> action="gdaImagen.php" enctype="multipart/form-data">
                            <input <?php echo $idFile; ?> type="file" name="imagen" required='required' value="Seleccionar imagen" title="Seleccionar imagen"/><br/>
                            <input <?php echo $idCmd; ?> type="submit"  value="Subir" title="Subir"/>
                        </form>

                    </div>
                </div>


            </div>

            <div id="footer">
                <div id="legal">
                    <ul>
                        <li><a href="">Derechos en trámite</a></li>
                        <li><a href="">Aviso de privacidad</a></li>
                    </ul>
                </div>
                <div id="social"><img src="../img/social.gif" width="117" height="50" /></div>
            </div>




        </div>





        <?php include_once '../../sources/ConfiguracionesGlobalesFW.php'; ?>
    </body>
</html>
