<?php
include '../../sources/Funciones.php';
verificaSesionTutor();
if (isset($_GET["alumno"]) && isset($_GET["idCurso"])) {
    $alumno = $_GET["alumno"];
    $idCurso = $_GET["idCurso"];
    $rutaMapa = "../../mapaCurso/index.php?alumno=" . $alumno . "&idCurso=" . $idCurso;
}

/**
 * CHANGE CONTROL 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACION: 23 DE JUNIO DE 2014
 * OBJETIVO: CAMBIAR LA FORMA EN QUE SE HACE USO DEL MAPA. SE INSERTARÁ UN IFRAME DEL MODULO PRINCIPAL DE MAPA DE RUTA
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Mapa de ruta Tutor | META Space</title>
        <meta name="lang" content="es" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="shortcut icon" href="/favicon.ico" />

        <link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/general.css" type="text/css" rel="stylesheet"  media="all" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>
        <link href='http://fonts.googleapis.com/css?family=Dosis:400,300,500' rel='stylesheet' type='text/css'/>

        <script language="JavaScript">

            // Función para mostrar la lista desplegable de tutores
            function muestra_oculta(idDiv) {
                if (document.getElementById) {
                    var el = document.getElementById(idDiv); //Se define la variable "el" igual a nuestro div			
                    el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
                }
            }
        </script>
        <!--<script src="../../js/jquery.js"></script>--> 
        <script type='text/javascript' src="../../js/jquery-1.7.min.js"></script>  
        <?php include_once '../../sources/ConfiguracionesGlobalesFW.php'; ?>
        <script type='text/javascript' src="../../plantilla/core/js/variablesCore.js"></script>  
        <?php
//        verificaSesionAlumnoFW();
        if (isset($_GET['alumno']) && isset($_GET['idCurso'])) {//Si está creada la variable
            if ($_GET['alumno'] == "si" && isset($_GET['idRelCursoGrupo']) && isset($_GET['idCurso'])) {//si es un alumno
                $idCurso = $_GET['idCurso'];
                $idRelCursoGrupo = $_GET['idRelCursoGrupo'];
                $idAlumno = $_SESSION['idPorTabla'];
                $idA = 2;
                $baseStorage = BASE_STORAGE . "cursos";
                $arrUnidadesMC = arregloIdUnidadesMC($_GET['alumno'], $idRelCursoGrupo, $_GET['idCurso'], $idAlumno);
//                var_dump ($arrUnidadesMC);
                echo <<<cabecera
                    <script type='text/javascript'>
                debugConsole('siii');
                        idRelCursoGrupo = $idRelCursoGrupo;
                        idAlumno = $idAlumno;
                        arrIdUnidades = $arrUnidadesMC;
                        alumno = "sia";
                        idCurso=$idCurso;
                        rutaCompMapaCurso = "$baseStorage/" + idCurso + "/mapa";
                    </script>
cabecera;
            } else if ($_GET['alumno'] == "no" && isset($_GET['idCurso'])) {
                $baseStorage = BASE_STORAGE . "cursos";
                $idCurso = $_GET['idCurso'];
                $arrUnidadesMC = arregloIdUnidadesMC($_GET['alumno'], null, $_GET['idCurso'], null, "0");
                echo <<<cabecera
                    <script type='text/javascript'>
                        arrIdUnidades = $arrUnidadesMC
                        
                        alumno = "no";
                        idCurso=$idCurso;
                        rutaCompMapaCurso = "$baseStorage/" + idCurso + "/mapa";
                        
                        tipoEjecucion = 0;
                        frontweb = true;
                    </script>
cabecera;
            } else {
                header("Location: error.php");
            }
        } else {
            //Si no viene esa variable
        }
        ?>
 <!--        <script>
 
             var arrIdUnidades = //echo arregloIdUnidadesMC($_GET['alumno'], $idRel);
             var alumno = " //echo $_GET['alumno']; "
         </script>-->
        <link rel="stylesheet" type="text/css" href="../../css/jquery.fancybox.css"/>

        <link rel="stylesheet" href="../../css/jquery-ui.css" />
        <link rel="stylesheet" href="../../css/estilosMapaCurso2.css" />

<!--<script type='text/javascript' src="../../plantilla/core/js/variablesCore.js"></script>--> 
        <script type='text/javascript' src="../../js/jquery-1.7.min.js"></script>  
        <script type='text/javascript' src="../../js/jquery-ui.min.js"></script>
        <script type='text/javascript' src="../../js/jquery.fancybox.js"></script>  
        <script type='text/javascript' src="../../js/jqueryMapaCurso.js"></script> 
        
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
                    <div id="lateral_nav_comunicacion"><a title="" href="comunicacion.php"></a></div>
                    <div id="lateral_nav_tutor_biblio"><a title="" href="biblioteca.php"></a></div>


                </div>







                <div id="tutor_contenido_gris"style="height: 530px">
                    <h2>Mapa de ruta</h2>
                    <!--                    <div class="contenido">
                                            <iframe src="<?php //echo $rutaMapa;  ?>" style="width: 100%; height: 100%;"></iframe>
                                        </div>-->
                    <div class="contenido" style="height: 329px">
                        <div id="mapa" style="width: 85%">
                                                    <!--<img src="../img/alumno/mapa.jpg" width="615" height="409" class="margin10"/>-->
                            <div id="mcPrincipal" style="margin-left: 40px;">

                                <a id="mcl1"  ><img id="mc1" class="mcNumero" /></a>
                                <a id="mcl2"  ><img id="mc2" class="mcNumero" /></a>
                                <a id="mcl3"  ><img id="mc3" class="mcNumero" /></a>
                                <a id="mcl4"  ><img id="mc4" class="mcNumero" /></a>
                                <a id="mcl5"  ><img id="mc5" class="mcNumero" /></a>
                                <a id="mcl6"  ><img id="mc6" class="mcNumero" /></a>
                            </div>
                        </div>
                    </div>
                    <div id="tutor_btn">
                        <div id="tutor_btn_regresar"><a href="javascript:history.back()"> </a></div>
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
                <div id="social"><img src="../img/tutor/social.gif" width="117" height="50" /></div>
            </div>
        </div>







    </body>
</html>
