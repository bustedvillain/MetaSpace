<?php
include '../../sources/Funciones.php';

verificarSesionAlumno();

if (isset($_GET['noUnidad']))
    $noUnidad = intval ($_GET['noUnidad']);
else
    $noUnidad = 1; 

$noUnidadesTotales = 6;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Baúl | META Space</title>
        <meta name="lang" content="es" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="shortcut icon" href="/favicon.ico" />

        <link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/general.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/alumno.css" type="text/css" rel="stylesheet"  media="all" />


        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>
        <link href='http://fonts.googleapis.com/css?family=Dosis:400,300,500' rel='stylesheet' type='text/css'/>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div id="nombre" class="fondo_naranja">
                    <h3><span>Alumno: </span><?php nombreLogout(); ?></h3>
                </div>
                <div id="logo"><a href="index.php"><img src="../img/logo_meta2.gif" width="347" height="72" alt="Ir a inicio de Meta Space" /></a></div>
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
                <div id="foto_perfil" class="fondo_naranja"><img src="<?php echo rutaFotoDeSesion(); ?>" width="77" height="78" class="margin10" /></div>
            </div>
            <div id="central">
                <div id="lateral_nav">
                    <div id="lateral_nav_aprender"><a title="" href="index.php"></a></div>
                    <div id="lateral_nav_baul"><span></span></div>
                    <div id="lateral_nav_mi_locker"><a title="" href="mi_locker.php"></a></div>
                    <div id="lateral_nav_biblioteca"><a title="" href="biblioteca.php"></a></div>
                </div>

                <?php
               
                    ?>
                    <div id="baul">
                        <div id="baul_nav">
                            <ul>
                                <?php
                                 if (tieneCursosAsignados(obtenerIDTabla()) || tieneCursosAsignadosScorm(obtenerIDTabla())) {
                                    generarBloques($noUnidadesTotales, $noUnidad);
                                 }
                                ?>

                            </ul>
                        </div>
                        <div id="baul_tab">

                            <?php
                            
                            if(tieneCursosAsignados(obtenerIDTabla()) ){
                                generarCursoAsignadosBaul(obtenerIDTabla(), $noUnidad);
                            }
                            
                             if(tieneCursosAsignadosScorm(obtenerIDTabla())){
                                generarCursoAsignadosBaulScorm(obtenerIDTabla(), $noUnidad);
                            }
                            
                            ?>

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
