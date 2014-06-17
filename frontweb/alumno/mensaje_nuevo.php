<?php
include '../../sources/Funciones.php';
verificarSesionAlumno();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Mensaje nuevo | META Space</title>
        <meta name="lang" content="es" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="shortcut icon" href="/favicon.ico" />

        <link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/general.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/alumno.css" type="text/css" rel="stylesheet"  media="all" />

        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>
        <link href='http://fonts.googleapis.com/css?family=Dosis:400,300,500' rel='stylesheet' type='text/css'/>

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
                    <h2 style="color:#ff9000">Mis amigos de <?php echo obtenerGrupoAlumno(obtenerIdDatosPersonales()); ?></h2>
                    <div class="contenido">

                        <div id="intro_contacto_form">

                            <form id="frmContact" class="login" method="POST" action='enviaMensaje.php'>




                                <div class="form_tutor">
                                    <select id="ContactoTutor" required ='required' name='idDestinatario'>
                                    <?php imprimeOptionsDeCompanyerosDeAlumno(obtenerIDTabla()); ?>
                                    </select>
<?php // echo obtenerIDTabla(). "-". gruposDeAlumno(obtenerIDTabla())."-".  companyerosDeAlumno(obtenerIDTabla());     ?>   
                                </div>

                                <div class="form_largo_tutor"><textarea name="mensaje" cols="50" rows="3" wrap="soft" id="LoginMensaje" placeholder="Mensaje..."></textarea></div>
                                <input type="hidden" id="location" name="location" value="mensaje_nuevo.php"/>
                                <div class="form_tutor"><input name="submit" class="btn_envio" value="Enviar" style="background-color:#ff9000;" type="submit"></div>
                            </form>
                        </div>

                    </div>

                    <div id="btn_int">
                        <div id="btn_int_regresar"><a href="javascript:history.back()"> </a></div>


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
