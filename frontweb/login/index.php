<?php
require_once '../../sources/Funciones.php';
verificaCookieTipo();

/**
 * CONTROL DE CAMBIOS 1.1.0
 * FECHA DE MODIFICACIÓN: 22 DE MAYO 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJETIVO: CAMBIO ESTETICO
 */
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!--<meta charset="ISO-8859-1">-->

        <title>Login | META Space</title>
        <meta name="lang" content="es" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="shortcut icon" href="/favicon.ico" />

        <link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/intro.css" type="text/css" rel="stylesheet"  media="all" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>
        <link href="http://fonts.googleapis.com/css?family=Dosis:400,300,500" rel="stylesheet" type="text/css"/>


    </head>


    <body>
        <div id="intro_container">
            <div id="intro_titulo"><h2>Espacio Digital para el Aprendizaje Autónomo Meta Space</h2></div>
            <div id="intro_logo">
                <div id="intro_nav">
                    <div id="intro_nav_nosotros"><a title="Nosotros" href="nosotros.html"></a></div>
                    <div id="intro_nav_servicios"><a title="Servicios" href="servicios.html"></a></div>
                    <div id="intro_nav_contacto"><a title="Contacto" href="contacto.php"></a></div>
                    <div id="intro_nav_login"><span></span></div> 

                </div>
            </div>

            <div id="intro_general">

                <div id="intro_login_form">



                    <?php
                    if (existeSession()) {
                        ?>
                        <h1>Ya has iniciado sesión.
                            <a  href="../../logout.php">Entrar con otro usuario</a>
                        </h1>
                        <?php
                    } else {
                        ?>
                        <div id="intro_reenvio_clave"><a href="recuperar_login.php">¿No puedes entrar?</a></div>
                        <form id="frmContact" class="login"  method="POST" action="../../sources/ControladorLogin.php">
                            <div class="form"><input name="nombre" size="35" maxlength="50" type="text" id="LoginNombre" placeholder="Nombre..." /></div>

                            <div class="form"><input name="clave" size="35" maxlength="100" type="password" id="LoginClave" placeholder="Contraseña..."></div>
                            <!--<div class="form"><textarea name="mensaje" cols="50" rows="3" wrap="soft" id="LoginMensaje" placeholder="Mensaje..."></textarea></div>-->
                            <div class="elem_right"><input name="submit" class="btn_envio" value="Entrar" style="background-color:#ff9000;" type="submit"></div>
                            <div class="recordar"><input name="recordarme" type="checkbox" id="check1">Recordarme</div>

                        </form>
    <?php
}
?>



                </div>
<?php if (isset($_GET["msg"])) mensaje($_GET['msg']); ?>
            </div>
            <div id="footer">
                <div id="legal">
                    <ul style="margin-left: 380px">
                        <li><?php echo VERSION ?></li>    
                        <li><a href="">Derechos en trámite</a></li>
                        <li><a href="">Aviso de Privacidad</a></li>
                    </ul>
                </div>
                <div id="social"><img src="../img/social.gif" width="117" height="50" /></div>
            </div>
        </div>
    </body>
</html>
