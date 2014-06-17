<?php
include '../../sources/Funciones.php';
verificarSesionAlumno();
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
} else {
    $pagina = 1;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Revisar Mensajes de Padre |  Alumno | META Space</title>
        <meta name="lang" content="es" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="shortcut icon" href="/favicon.ico" />

        <link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/general.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/alumno.css" type="text/css" rel="stylesheet"  media="all" />

        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>
        <link href='http://fonts.googleapis.com/css?family=Dosis:400,300,500' rel='stylesheet' type='text/css'/>
        <script type="text/javascript" src="../../js/jquery-1.7.min.js"></script>
        <script type="text/javascript">
            $(document).on("ready", function() {
                inicio();
            });
            function inicio() {
            }
            function enviaForm(form) {

                $("#" + form).submit();
            }
        </script>
        <style>

            .comunic_tabs1 a, .comunic_tabs2 a{
                color: #ff9000;
            }

            .contenido_tabs a:hover, .comunic_tabs1 a:hover, .comunic_tabs2 a:hover{
                color: #fff;
                background-color:#ff9000;	
            }

            .msgs li a:hover{
                background:#ff9000;
                -moz-transition: background 0.3s ease-in;
                -webkit-transition: background 0.3s ease-in;
                -o-transition: background 0.3s ease-in;
            }

            .msgs ul li a{
                background:#fff;
                border: 2px solid #ff9000;
                color: #ff9000;
            }

            .msgs ul li ul {
                background: #fff;
            }

            .msgs ul li a:hover{
                background: none repeat scroll 0 0 #ff9000;
                color: #fff;
                -moz-transition: color 0.4s ease;
                -webkit-transition: color 0.4s ease;
                -o-transition: color 0.4s ease;
            }


            .horizontal_btn2 a:hover, .horizontal_btn2 a:active  {

                border: 2px solid #ff9000;
            }


            .horizontal_btn2 a, .horizontal_btn2 a:visited  {
                height: 10%;
                line-height: 10%;
                display:block;
                margin: 0px 0px;
                font-family: 'Dosis', Arial, serif;
                font-size: 12px;
                font-weight: bold;
                text-align:center;
                text-decoration:none;
                color: #ff9000;
                background-color:#FFF;	
                -moz-border-radius: 6px; /* Firefox */
                -webkit-border-radius: 6px; /* Google Chrome y Safari */
                border-radius: 6px; /* CSS3 (Opera 10.5, IE 9 y est&aacute;ndar a ser soportado por todos los futuros navegadores) */
                border: 2px solid #ff9000;
            }

            .horizontal_btn2 a:hover, .horizontal_btn2 a:active  {
                color: #fff;
                background-color:#04b7f9;	
                border: 2px solid  #ff9000;
            }
        </style>

        <script language="JavaScript">
            function muestra_oculta_mates(id) {
                if (document.getElementById) { //se obtiene el id
                    var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
                    el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
                }
            }
            window.onload = function() {/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
                muestra_oculta_mates('contenido_a_mostrar_mates');/* "contenido_a_mostrar" es el nombre que le dimos al DIV */
            }



            function muestra_oculta_ciencias(id) {
                if (document.getElementById) { //se obtiene el id
                    var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
                    el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
                }
            }
            window.onload = function() {/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
                muestra_oculta_ciencias('contenido_a_mostrar_ciencias');/* "contenido_a_mostrar" es el nombre que le dimos al DIV */
            }

            function muestra_oculta_artes(id) {
                if (document.getElementById) { //se obtiene el id
                    var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
                    el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
                }
            }
            window.onload = function() {/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
                muestra_oculta_artes('contenido_a_mostrar_artes');/* "contenido_a_mostrar" es el nombre que le dimos al DIV */
            }

            function muestra_oculta_letras(id) {
                if (document.getElementById) { //se obtiene el id
                    var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
                    el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
                }
            }
            window.onload = function() {/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
                muestra_oculta_letras('contenido_a_mostrar_letras');/* "contenido_a_mostrar" es el nombre que le dimos al DIV */
            }

            // Función para mostrar la lista desplegable de tutores
            function muestra_oculta_dif_el(id_el1, id_el2) {
                if (document.getElementById(idCheck).checked) {
                    var el = document.getElementById(idDiv); //Se define la variable "el" igual a nuestro div
                    //el.style.display = "block";
                    el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
                }
            }

            // Función para mostrar la lista desplegable de tutores
            function muestra_oculta(id) {
                if (document.getElementById) {
                    var el = document.getElementById(id); //Se define la variable "el" igual a nuestro div
                    //el.style.display = "block";
                    el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
                }
            }

            // Función para mostrar la lista desplegable de tutores
            function muestra_oculta_tabs(id_tab1, id_tab2) {
                if (document.getElementById(id_tab1)) {
                    var el = document.getElementById(id_tab1); //Se define la variable "el" igual a nuestro div
                    //el.style.display = "block";
                    el.style.display = 'none' //damos un atributo display:none que oculta el div
                }
                if (document.getElementById(id_tab2)) {
                    var el = document.getElementById(id_tab2); //Se define la variable "el" igual a nuestro div
                    //el.style.display = "block";
                    el.style.display = 'block'
                }
            }

            // Función para mostrar la lista desplegable de tutores
            function muestra_oculta(idDiv) {
                alert(document.getElementById(idDiv));
                if (document.getElementById) {
                    var el = document.getElementById(idDiv); //Se define la variable "el" igual a nuestro div	
                    /*alert(el.style.display);
                     if (el.style.display == 'none'){
                     alert(el);
                     el.style.display = 'block';
                     }*/
                    el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
                }
            }
        </script>


    </head>



    <body>

        <div id="container">
            <div id="header">
                <div id="nombre" class="fondo_naranja">
                    <h3><span>Alumno: </span><?php nombreLogout(); ?></h3>
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
                <div id="foto_perfil" class="fondo_naranja"><img src="<?php echo rutaFotoDeSesion(); ?>" width="77" height="78" class="margin10" /></div>

            </div>

            <div id="central">


                <div id="lateral_nav">
                    <div id="lateral_nav_aprender"><a title="" href="index.php"></a></div>
                    <div id="lateral_nav_baul"><a title="" href="baul.php"></a></div>
                    <div id="lateral_nav_mi_locker"><a title="" href="mi_locker.php"></a></div>
                    <div id="lateral_nav_biblioteca"><a title="" href="biblioteca.php"></a></div>


                </div>

                <div id="tutor_mensaje">
                    <h2 style="color:#ff9000">Revisar mensajes de Padre</h2>

                    <div id="tabs" class="comunic_tabs">
                        <span class="comunic_tabs1" onclick="muestra_oculta_tabs('msg_env', 'msg_recib')"><a href="#">Mensajes recibidos</a></span>

                    </div>

                    <div id="msg_recib" class="contenido_msg_recib">

                        <ul class="msgs">
                            <?php
                            if (cantidadPaginas(padreDeAlumno(obtenerIdDatosPersonales())) > 0) {
                                echo '<li>Pase el mouse sobre el mensaje para abrirlo</li>';
                            } else {
                                echo '<li>No hay mensajes disponibles.</li>';
                            }

                            mensajesAlAlumnoDelPadre($pagina);
                            ?>
<!--                            <li><a href="#"><div class="msg_head"><span class="alignLeft">Antonio Marquez Padre - 5° 32-B</span><span class="alignRight">12/12/12</span></div>
                                    <span class="alignLeft">Clases de M&uacute;sica</span></a>
                                <ul class="msgs">
                                    <li>
                                        <div id="msg_body">                       
                                            <div class="msg_text" style="text-align:justify">En este espacio se muestra todo el texto del mensaje, para que el usuario pueda leerlo. No hay un número de caracteres límite. Cuando el texto rebasa la capacidad del espacio se muestra la barra de scroll vertical para poder desplazarse por el texto.
                                            </div>
                                            <div>
                                                <textarea id="msg_cuerpo" cols="60" rows="3" wrap="soft" placeholder="Mensaje..." style="text-align:justify"> </textarea>
                                                <span class="horizontal_btn2"> <a href="#">Responder</a></span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>-->


                        </ul>

                        <div id="btn_int"><?php paginacionAlumnoDelPadre(); ?>

                            <div id="btn_int_regresar"><a href="javascript:history.back()"> </a></div>


                        </div>

                    </div>



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







<?php include_once '../../sources/ConfiguracionesGlobalesFW.php'; ?>
    </body>
</html>
