<?php include '../../sources/Funciones.php';
verificaSesionTutor();
if(!isset($_GET["pagina"])){
    $pagina = 1;
}else{
    $pagina =$_GET["pagina"];
}
if(!isset($_GET["tipo"])){
    $tipo = "recibidos";
}else{
    $tipo = $_GET["tipo"];
}
$idTutor = obtenerIDTabla();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Revisar Mensajes |  Tutor | META Space</title>
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
            });
            function inicio() {
            }
            function enviaForm(form) {

                $("#"+form).submit();
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
                if (document.getElementById) {
                    var el = document.getElementById(idDiv); //Se define la variable "el" igual a nuestro div			
                    el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
                }
            }
        </script>


    </head>



    <body id="tutor">

        <div id="container">
            <div id="header_tutor">
                <div id="nombre" class="fondo_azul">
                    <h3><span>Tutor:</span><?php nombreLogout(); ?></h3>
                </div>

                <div id="logo"><a href="index.php"><img src="../img/logo_meta.gif" width="347" height="72" alt="Ir a inicio de Meta Space" /></a></div>
                <div class="separador_tutor"></div>
                <div id="buscador">
                    <form id="" name=""  action="" enctype="multipart/form-data" method="post">
                        <div id="buscador_tutor"><input name="buscador" size="35" maxlength="35" type="text" id="Buscador" placeholder="Buscar..."></div>
                        <div id="lupa"><input name="submit" class="btn_envio" id="btn_envio" value="" type="submit"></div>
                    </form>

                </div>
                <div class="separador_tutor"></div>
                <div class="btn_perfil"><a href="perfil.php"><img src="../img/tutor/icono_perfil.png" width="55" height="30" />mi perfil</a></div>
                <div class="separador_tutor"></div>
                <div class="btn_perfil"><a href="como_navegar.php"><img src="../img/tutor/icono_navegar.png" width="55" height="30" />como navegar</a></div>
                <div class="separador_tutor"></div>
                <div class="btn_perfil"><a href="grupos.php"><img src="../img/tutor/icono_grupo.png" width="55" height="30" />grupos</a></div>
                <div id="foto_perfil" class="fondo_azul"><img src="<?php echo rutaFotoDeSesion(); ?>" width="77" height="78" class="margin10" /></div>
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
                        </div>
                    </div>

                </div>

                <div id="lateral_nav">
                    <div id="lateral_nav_cursos"><a title="" href="cursos.php"></a></div>
                    <div id="lateral_nav_reportes"><a title="" href="reportes.php"></a></div>
                    <div id="lateral_nav_comunicacion"><span></span></div>
                    <div id="lateral_nav_tutor_biblio"><a title="" href="biblioteca.php"></a></div>
                </div>

                <div id="tutor_mensaje">
                    <h2>Revisar mensajes</h2>

                    <div id="tabs" class="comunic_tabs">
                        <span class="comunic_tabs1" ><a href="mensaje_revisar.php?tipo=recibidos">Mensajes recibidos</a></span>
                        <span class="comunic_tabs2" ><a href="mensaje_revisar.php?tipo=enviados"> Mensajes enviados</a></span>
                    </div>

                    <div id="msg_recib" class="contenido_msg_recib">

                        <ul class="msgs">
                            <li>Pase el mouse sobre el mensaje <?php echo substr($tipo,0,  strlen($tipo)-1); ?> para abrirlo</li>
                            <?php mensajesAlTutorDeTutores($idTutor, $pagina, $tipo) ?>
<!--                            <li><a href="#"><div class="msg_head"><span class="alignLeft">Antonio Marquez Padre - 5° 32-B</span><span class="alignRight">12/12/12</span></div>
                                    <span class="alignLeft">Clases de M&uacute;sica</span></a>
                                <ul class="msgs">
                                    <li>
                                        <div id="msg_body">                       
                                            <div class="msg_text" style="text-align:justify">En este espacio se muestra todo el texto del mensaje, para que el usuario pueda leerlo. No hay un número de caracteres límite. Cuando el texto rebasa la capacidad del espacio se muestra la barra de scroll vertical para poder desplazarse por el texto.</div>
                                            <div>
                                                <textarea id="msg_cuerpo" cols="60" rows="3" wrap="soft" placeholder="Mensaje..." style="text-align:justify"> </textarea>
                                                <span class="horizontal_btn2"> <a href="#">Responder</a></span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>-->
                            <?php paginacionTutorDelTutor($idTutor, $tipo) ?>
                        </ul>
                        
                    </div>


<!--                    <div id="msg_env" class="contenido_msg_env">

                        <ul class="msgs">
                            <li>Pase el mouse sobre un mensaje enviado para verlo</li>
                            <li><a href="#"><div class="msg_head"><span class="alignLeft">Antonio Marquez Padre - 5° 32-B</span><span class="alignRight">12/12/12</span></div>
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
                            </li>
                            <li><a href="#"><div class="msg_head"><span class="alignLeft">Antonio Marquez Padre - 5° 32-B</span><span class="alignRight">12/12/12</span></div>
                                    <span class="alignLeft">Clases de M&uacute;sica</span></a>
                                <ul class="msgs">
                                    <li>
                                        <div id="msg_body">                       
                                            <div class="msg_text" style="text-align:justify">En este espacio se muestra todo el texto del mensaje, para que el usuario pueda leerlo. No hay un número de caracteres límite. Cuando el texto rebasa la capacidad del espacio se muestra la barra de scroll vertical para poder desplazarse por el texto.</div>
                                            <div>
                                                <textarea id="msg_cuerpo" cols="60" rows="3" wrap="soft" placeholder="Mensaje..." style="text-align:justify"> </textarea>
                                                <span class="horizontal_btn2"> <a href="#">Responder</a></span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="#"><div class="msg_head"><span class="alignLeft">Antonio Marquez Padre - 5° 32-B</span><span class="alignRight">12/12/12</span></div>
                                    <span class="alignLeft">Clases de M&uacute;sica</span></a>
                                <ul class="msgs">
                                    <li>
                                        <div id="msg_body">                       
                                            <div class="msg_text" style="text-align:justify">En este espacio se muestra todo el texto del mensaje, para que el usuario pueda leerlo. No hay un número de caracteres límite. Cuando el texto rebasa la capacidad del espacio se muestra la barra de scroll vertical para poder desplazarse por el texto.</div>
                                            <div>
                                                <textarea id="msg_cuerpo" cols="60" rows="3" wrap="soft" placeholder="Mensaje..." style="text-align:justify"> </textarea>
                                                <span class="horizontal_btn2"> <a href="#">Responder</a></span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="#"><div class="msg_head"><span class="alignLeft">Antonio Marquez Padre - 5° 32-B</span><span class="alignRight">12/12/12</span></div>
                                    <span class="alignLeft">Clases de M&uacute;sica</span></a>
                                <ul class="msgs">
                                    <li>
                                        <div id="msg_body">                       
                                            <div class="msg_text" style="text-align:justify">En este espacio se muestra todo el texto del mensaje, para que el usuario pueda leerlo. No hay un número de caracteres límite. Cuando el texto rebasa la capacidad del espacio se muestra la barra de scroll vertical para poder desplazarse por el texto.</div>
                                            <div>
                                                <textarea id="msg_cuerpo" cols="60" rows="3" wrap="soft" placeholder="Mensaje..." style="text-align:justify"> </textarea>
                                                <span class="horizontal_btn2"> <a href="#">Responder</a></span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                    </div>-->

                </div>


                
                <div id="footer">
                    <div id="legal">
                        <ul>
                            <li><a href="">Derechos en trámite</a></li>
                            <li><a href="">Aviso de privacidad</a></li>
                        </ul>
                    </div>
                    <div id="social"><img src="../img/tutor/social.gif" width="117" height="50" /></div>
                </div>
            </div>






<?php include_once '../../sources/ConfiguracionesGlobalesFW.php'; ?>
    </body>
</html>
