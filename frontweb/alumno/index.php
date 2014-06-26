<?php
//Control de cambios #7
//Autor:Omar Nava
//Objetivo: Hacer una funcion para el nombre y cerrar sesion
//03-ene-2014
include '../../sources/Funciones.php';
verificarSesionAlumno();
$idAlumno = $_SESSION['idPorTabla'];
$arrAmbos = @getArraysIdsRelCursoGrupo($idAlumno);
$arrIdsCurso = array_pop($arrAmbos);
$arrIdsRelCursoGrupo = array_pop($arrAmbos);
$rutaCursos = BASE_STORAGE . "cursos/";
$cantidad = count($arrIdsCurso);
//var_dump($arrIdsCurso);
//echo 'cant='.$cantidad;
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Vista Alumno | META Space</title>
        <meta name="lang" content="es" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="shortcut icon" href="/favicon.ico" />




        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>
        <link href='http://fonts.googleapis.com/css?family=Dosis:400,300,500' rel='stylesheet' type='text/css'/>
        <script type="text/javascript" src="../../js/jquery-1.7.min.js"></script>
        <script type="text/javascript">
            $(document).on("ready", inicio);
            function inicio() {
//                alert('jajaja');
                eventosCajasImg();
//                $('#modulo_letras').css("background-image", "url(../img/alumno/modulo_letras.gif)")
            }
            function eventosCajasImg() {
                $('.cajaImg').mouseenter(function(event) {
//                    var src= $(this).attr('src');
//                    $(this).attr('src', cambiaImagenBoton($(this).attr('src'),"_hover"));
//                      $(this).css("background","#FFF");
                    var nuevoNombre = cambiaImagenBoton($(this).css('background-image'), "b");
                    debugConsole('Nuevonombre=' + nuevoNombre);
                    $(this).css("background-image", nuevoNombre);
//                    debugConsole('entro' + $(this).css("background-image"));
                });
                $('.cajaImg').mouseleave(function() {
                    var nuevoNombre = cambiaImagenBoton($(this).css('background-image'), "a");
                    debugConsole('Nuevonombre=' + nuevoNombre);
                    $(this).css("background-image", nuevoNombre);
                })

            }
            function cambiaImagenBoton(nombreImagen, letra)
            {
                debugConsole('Nimagen=' + nombreImagen);
                //nombreImagen=template_path+"/"+tipo_elemento+"/"+nombreImagen;
                var extensionImagen = nombreImagen.substr(nombreImagen.length - 5, nombreImagen.length - 1);
                debugConsole('ext=' + extensionImagen);
                var nombreSinExt = nombreImagen.substr(0, nombreImagen.length - 6);
                var nuevoNombre = nombreSinExt + letra + extensionImagen;
//    trazaEnConsola('nombreSine'+nombreSinExt,5);
//                trazaEnConsola('Cambiando imagen' + nuevoNombre, 5);
                return nuevoNombre;
                //return imagenes_template_path + "/next-a.png";
                //$(this).attr("src", nuevoNombre);
            }
        </script>
        <script language="JavaScript">

            function muestra_oculta(id) {
                if (document.getElementById) { //se obtiene el id
                    var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
                    el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
                }
            }

        </script>
        <link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/general.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/alumno.css" type="text/css" rel="stylesheet"  media="all" />

        <style>
            #buscador_alu input  {
                -moz-border-radius: 10px; /* Firefox */
                -webkit-border-radius: 10px; /* Google Chrome y Safari */
                border-radius: 10px; /* CSS3 (Opera 10.5, IE 9 y est&aacute;ndar a ser soportado por todos los futuros navegadores) */
                width: 255px;
                height: 50px;
                align: top;
                border: 8px solid #acaab8;
                /*background-color: #fff;*/	
                background-color: #fff;
                font-family: Dosis, Arial, serif;
                font-weight: 20;
                font-size: 17px;
                padding-left: 5px;
                /*padding-top: -50px;*/
                /*margin-top: -60px;*/
                margin: 10px 5px;

                line-height: 50px;
            }
            #buscador {
                width: 268px;
                height: 62px;
                float:left;
                line-height: 62px;
            }

            #lupa input[type="submit"] {
                position:absolute;
                top: 38px;
                right: 418px;
                width: 35px;
                height: 34px;
                background-image:url(../img/lupa.png);
                background-color: #fff;
                outline:0px;
                border: 0px solid blue;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div id="nombre" class="fondo_naranja">
                    <!--inicia control de cambios #7-->
                    <h3><span>Alumno:</span><?php nombreLogout(); ?></h3>
                    <!--terminac ontrol de cambios #7-->
                </div>

                <div id="logo"><a href="index.php"><img src="../img/logo_meta22.gif" width="347" height="72" alt="Ir a inicio de Meta Space" /></a></div>
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
                <div id="destacado"><img src="../img/alumno/destacado.gif" width="153" height="486" /></div>
                <div id="lateral_nav">
                    <div id="lateral_nav_aprender"><span></span></div>
                    <div id="lateral_nav_baul"><a title="" href="baul.php"></a></div>
                    <div id="lateral_nav_mi_locker"><a title="" href="mi_locker.php"></a></div>
                    <div id="lateral_nav_biblioteca"><a title="" href="biblioteca.php"></a></div>


                </div>
                <?php
                $arrayEventos = imprimeCajas($arrIdsCurso, $arrIdsRelCursoGrupo, $rutaCursos, $cantidad);
                ?>
                <script language="JavaScript">
                    window.onload = function() {/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
<?php
///Imprimiendo los valores de eso
foreach ($arrayEventos as $a) {
    echo "muestra_oculta('$a');";
}
?>
                    }
                </script>
                <!--                <div class="modulo_izq">
                                    <div class="puntuacion">
                                        <div class="estrella"><a></a></div>
                                        <div class="estrella"><a></a></div>
                                        <div class="estrella"><a></a></div>
                                    </div>
                                    <div id="modulo_letras" class="cajaImg" style="background-image:url(../img/alumno/modulo_letras-a.gif)">
                                        <a href="#" onClick="muestra_oculta('contenido_a_mostrar_letras')" title=""></a>
                                    </div>
                                    <div id="contenido_a_mostrar_letras" ><a href="#" onClick="muestra_oculta('contenido_a_mostrar_letras')" title=""><img src="../img/alumno/contenido_a_mostrar_letras.gif" width="280" height="270" /></a>
                                        <span class="contenido_enlace"><a href="mapa.php"></a></span>
                
                                    </div>
                                </div>
                
                                <div class="modulo_decha">
                                    <div class="puntuacion">
                                        <div class="estrella"><a></a></div>
                                        <div class="estrella"><a></a></div>
                                        <div class="estrella"><a></a></div>
                                    </div>
                                    <div id="modulo_mates" class="cajaImg" style="background-image:url(../img/alumno/modulo_mates-a.gif)">
                                        <a href="#" onClick="muestra_oculta('contenido_a_mostrar_mates')" title=""></a>
                                    </div>
                                    <div id="contenido_a_mostrar_mates" style="display:none;"><a href="#" onClick="muestra_oculta('contenido_a_mostrar_mates')" title=""><img src="../img/alumno/contenido_a_mostrar_mates.gif" width="280" height="270" /></a>
                                        <span class="contenido_enlace"><a href="mapa.php"></a></span>
                                    </div>
                                </div>
                               
                                <div class="modulo_izq">
                                    <div class="puntuacion">
                                        <div class="estrella"><a></a></div>
                                        <div class="estrella"><a></a></div>
                                        <div class="estrella"><a></a></div>
                                    </div>
                                    <div id="modulo_ciencias" class="cajaImg" style="background-image:url(../img/alumno/modulo_ciencias-a.gif)">
                                        <a href="#" onClick="muestra_oculta('contenido_a_mostrar_ciencias')" title=""></a>
                                    </div>
                                    <div id="contenido_a_mostrar_ciencias" class="cajaImg" style="display:none;"><a href="#" onClick="muestra_oculta('contenido_a_mostrar_ciencias')" title=""><img src="../img/alumno/contenido_a_mostrar_ciencias.gif" width="280" height="270" /></a>
                                        <span class="contenido_enlace"><a href="mapa.php"></a></span>
                                    </div>
                                </div>
                
                                <div class="modulo_decha" >
                                    <div class="puntuacion">
                                        <div class="estrella"><a></a></div>
                                        <div class="estrella"><a></a></div>
                                        <div class="estrella"><a></a></div>
                                    </div>
                                    <div id="modulo_ciencias" class="cajaImg" style="background-image:url(../img/alumno/modulo_artes-a.gif)">
                                        <a href="#" onClick="muestra_oculta('contenido_a_mostrar_artes')" title=""></a>
                                    </div>
                                    <div id="contenido_a_mostrar_artes" style="display:none;"><a href="#" onClick="muestra_oculta('contenido_a_mostrar_artes')" title=""><img src="../img/alumno/contenido_a_mostrar_artes.gif" width="280" height="270" /></a>
                                        <span class="contenido_enlace"><a href="mapa.php"></a></span>
                                    </div>
                                </div>
                            </div>-->
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
