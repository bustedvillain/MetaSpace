<?php
include '../../sources/Funciones.php';
verificarSesionPadre();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Mensaje para tutor de mi hijo | META Space</title>
        <meta name="lang" content="es" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="shortcut icon" href="/favicon.ico" />

        <link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/general.css" type="text/css" rel="stylesheet"  media="all" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>
        <link href='http://fonts.googleapis.com/css?family=Dosis:400,300,500' rel='stylesheet' type='text/css'/>
        <script type="text/javascript" src="../../js/jquery-1.7.min.js"></script>
        <script type="text/javascript">
            $(document).on("ready", function() {
                inicio();
                $("#ContactoAlumno").change();
            });
            function inicio() {
                $("#ContactoAlumno").change(function() {
                    var idAlumno = $("#ContactoAlumno").val();
                    $.post("../../sources/Controlador.FrontWeb.php", {ins: 'tutoresDeAlumno', idAlumno: idAlumno}, function(respuesta) {
                        debugConsole(respuesta);
                        var datos = jQuery.parseJSON(respuesta);
//                        debugConsole('aca');
                        $("#ContactoTutor").empty();
                        if (datos === null) {
                            $("#ContactoTutor").append("<option>No hay tutores disponibles</option>");
                        } else {
                            for (i = 0; i < datos.length; i++) {
                                $("#ContactoTutor").append("<option value='" + datos[i].idDatosPersonales + "'>" + (datos[i].nombreConRol) + "</option>");
                            }
                        }
                    });
                });
            }
            function muestraFileI() {
                $("#frmImagen").toggle();
            }
        </script>
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
                if (document.getElementById) {
                    var el = document.getElementById(idDiv); //Se define la variable "el" igual a nuestro div			
                    el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
                }
            }

        </script>


    </head>

    <body id="padre">

        <div id="container">
            <div id="header_padre">
                <div id="nombre" class="fondo_verde">
                    <h3><span>Padre: </span><?php nombreLogout(); ?></h3>
                </div>

                <div id="logo"><a href="index.php"><img src="../img/logo_meta.gif" width="347" height="72" alt="Ir a inicio de Meta Space" /></a></div>
                <div class="separador_padre"></div>
                <div id="buscador">
                    <form id="" name=""  action="" enctype="multipart/form-data" method="post">
                        <div id="buscador_padre"><input name="buscador" size="35" maxlength="35" type="text" id="Buscador" placeholder="Buscar..."></div>
                        <div id="lupa"><input name="submit" class="btn_envio" id="btn_envio" value="" type="submit"></div>
                    </form>

                </div>
                <div class="separador_padre"></div>
                <div class="btn_perfil"><a href="perfil.php"><img src="../img/padre/icono_perfil.png" width="55" height="30" />mi perfil</a></div>
                <div class="separador_padre"></div>
                <div class="btn_perfil"><a href="como_navegar.php"><img src="../img/padre/icono_navegar.png" width="55" height="30" />como navegar</a></div>
                <div class="separador_padre"></div>
                <div class="btn_perfil"><a href="reportes.php"><img src="../img/padre/icono_reporte.png" width="55" height="30" />Reportes</a></div>
                <div id="foto_perfil" class="fondo_verde"><img src="<?php echo rutaFotoDeSesion(); ?>" width="77" height="78" class="margin10" /></div>
            </div>

            <div id="central">
                <div id="col_extra">

                    <div id="mensajes">
                        <h4>Mensajes</h4>
                        <p>Nuevo curso para docentes en Edomex.</p>
                        <p>Soluciones para escuelas públicas y privadas.</p>
                        <p>Ampliado el plazo para presentar documentación.</p>
                    </div>

                    <div id="chat">
                        <div id="chat_title">
                            <span id="chat_head">Chat en línea</span>
                            <span id="chat_greenBtn"><a href="#" onClick="muestra_oculta('chat_online')"></a></span>
                        </div>


                        <div id="chat_online" style="display:none;" class="chat_conectados">
                            <p>Martín Garzón - Padre</p>
                            <p>Mengan Sams - Tutor</p>
                        </div>

                        <div id="chat_scroll">
                            <div>
                                <span>Pedro dice...</span>
                                <p>Bien, casi acabándolos, gracias compañero.</p></div>
                            <div><span>Antonio dice...</span>
                                <p>¿Qué tal los exámenes?</p></div>
                            <div>
                                <span>Pedro dice...</span>
                                <p>Dos días por lo menos.</p></div>
                            <div>
                                <span>Antonio dice...</span>
                                <p>¿Llevan mucho tiempo?</p></div>
                            <div>
                                <span>Pedro dice...</span>
                                <p>Bien, casi acabándolos, gracias compañero.</p></div>
                            <div><span>Antonio dice...</span>
                                <p>¿Qué tal los exámenes?</p></div>
                            <div>
                                <span>Pedro dice...</span>
                                <p>Dos días por lo menos.</p></div>
                            <div>
                                <span>Antonio dice...</span>
                                <p>¿Llevan mucho tiempo?</p></div>

                        </div>
                        <div class="chat_input">
                            <input type="text" placeholder="Ingresa aquí tu texto" />
                            <input type="submit" value="enviar" class="chat_padre" />
                        </div>
                    </div>

                </div>

                <div id="lateral_nav">
                    <div id="lateral_pad_familia"><a title="" href="index.php"></a></div>
                    <div id="lateral_pad_educacion"><a title="" href="educacion.php"></a></div>
                    <div id="lateral_pad_comunicacion"><span></span></div>
                    <div id="lateral_pad_biblioteca"><a title="" href="biblioteca.php"></a></div>
                </div>

                <div id="tutor_mensaje">
                    <h2 class="color_verde">Enviar mensaje al tutor de:</h2>
                    <div class="contenido">
                        <div id="intro_contacto_form">
                            <form id="frmContact" class="login" action="enviaMensaje.php" method ="post">
                                <div class="form_tutor">
                                    <select id="ContactoAlumno">
                                    <?php optionsDeHijosDelPadre2(obtenerIDTabla()); ?>
                                    </select>
                                </div>
                                <div class="form_tutor">
                                    <select id="ContactoTutor" name="idDestinatario">

                                    </select>
                                </div>
                                <div class="form_largo_tutor">
                                    <textarea name="mensaje" required="required" cols="50" rows="3" wrap="soft" id="LoginMensaje" placeholder="Mensaje..."></textarea>
                                    <input type="hidden" id="location" name="location" value="mensaje_tutor.php"/>
                                </div>
                                <div class="form_tutor"><input name="submit" class="btn_envio fondo_verde" value="Enviar" type="submit"></div>
                            </form>
                        </div>
                        <div id="padre_btn">
                            <div id="padre_btn_regresar"><a href="javascript:history.back()"> </a></div>

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
                <div id="social"><img src="../img/padre/social.gif" width="117" height="50" /></div>
            </div>
        </div>






<?php include_once '../../sources/ConfiguracionesGlobalesFW.php'; ?>
    </body>
</html>
