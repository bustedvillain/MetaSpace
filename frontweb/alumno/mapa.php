<!--inicia lineas itt-->
<?php
include "../../sources/Funciones.php";
verificarSesionAlumno();

/**
 * CHANGE CONTROL 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACION: 17 DE JUNIO DE 2014
 * OBJETIVO: AGREGAR EVENTO EN APERTURA DE BLOQUES PARA CAMBIAR A PANTALLA COMPLETA
 */
/**
 * CHANGE CONTROL 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACION: 20 DE JUNIO DE 2014
 * OBJETIVO: CAMBIAR LA FORMA EN QUE SE HACE USO DEL MAPA. SE INSERTARÁ UN IFRAME DEL MODULO PRINCIPAL DE MAPA DE RUTA
 */
?>

<!--Fin lineas itt-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Mapa | META Space</title>
        <meta name="lang" content="es" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="shortcut icon" href="/favicon.ico" />        

        <link href="../style/mapa.css" type="text/css" rel="stylesheet"  media="all" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>
        <link href='http://fonts.googleapis.com/css?family=Dosis:400,300,500' rel='stylesheet' type='text/css'/>

        <link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/general.css" type="text/css" rel="stylesheet"  media="all" />
        <link href="../style/alumno.css" type="text/css" rel="stylesheet"  media="all" />

        <!--CODIGO ANTIGUO-->
        <!--Agregadas por Itt para correcto funcionamiento con Base de datos-->
        <!--<script type='text/javascript' src="../../plantilla/core/js/variablesCore.js"></script>-->  
        <?php
//        verificaSesionAlumnoFW();
        if (isset($_GET['alumno']) && isset($_GET['idCurso'])) {//Si está creada la variable
            if ($_GET['alumno'] == "si" && isset($_GET['idRelCursoGrupo']) && isset($_GET['idCurso'])) {//si es un alumno
                $idCurso = $_GET['idCurso'];
                $idRelCursoGrupo = $_GET['idRelCursoGrupo'];
                $idAlumno = $_SESSION['idPorTabla'];
//                $idA = 2;
//                $baseStorage = BASE_STORAGE . "cursos";
//                $arrUnidadesMC = arregloIdUnidadesMC($_GET['alumno'], $idRelCursoGrupo, $_GET['idCurso'], $idAlumno);
//                
                $baseStorage = BASE_STORAGE . "cursos";

                $tipoEjecucion = consultaTipoEjecucionCurso($idCurso);
                
                $arrUnidadesMC = arregloIdUnidadesMC($_GET['alumno'], $idRelCursoGrupo, $_GET['idCurso'], $idAlumno, $tipoEjecucion);
//                var_dump ($arrUnidadesMC);
                echo <<<cabecera
                    <script type='text/javascript'>
                        idRelCursoGrupo = $idRelCursoGrupo;
                        idAlumno = $idAlumno;
                        arrIdUnidades = $arrUnidadesMC;
                        alumno = "si";
                        idCurso=$idCurso;
                        rutaCompMapaCurso = "$baseStorage/" + idCurso + "/mapa";
                        
                        tipoEjecucion = $tipoEjecucion;
                    </script>
cabecera;
            } else if ($_GET['alumno'] == "no" && isset($_GET['idCurso'])) {
                $baseStorage = BASE_STORAGE . "cursos";
                $idCurso = $_GET['idCurso'];
                $arrUnidadesMC = arregloIdUnidadesMC($_GET['alumno'], null, $_GET['idCurso']);
                echo <<<cabecera
                    <script type='text/javascript'>
                        arrIdUnidades = $arrUnidadesMC
                        
                        alumno = "no";
                        idCurso=$idCurso;
                        rutaCompMapaCurso = "$baseStorage/" + idCurso + "/mapa";
                    </script>
cabecera;
            } else {
                header("Location: error.php");
            }
        } else {
            //Si no viene esa variable
        }
        ?>
        <!--/CODIGO ANTIGUO-->
 <!--        <script>
 
             var arrIdUnidades = //echo arregloIdUnidadesMC($_GET['alumno'], $idRel);
             var alumno = " //echo $_GET['alumno']; "
         </script>-->
        <link rel="stylesheet" type="text/css" href="../../css/jquery.fancybox.css"/>

        <link rel="stylesheet" href="../../css/jquery-ui.css" />
        <link rel="stylesheet" href="../../css/estilosMapaCurso2.css" />


        <script type='text/javascript' src="../../js/jquery-1.7.min.js"></script>  
        <script type='text/javascript' src="../../js/jquery-ui.min.js"></script>
        <script type='text/javascript' src="../../js/jquery.fancybox.js"></script>  
        <script type='text/javascript' src="../../js/jqueryMapaCurso.js"></script> 
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

                <div id="alumno_mapa">
                    <h2>Mapa</h2>
                    <div id="mapa" >
                            <!--<img src="../img/alumno/mapa.jpg" width="615" height="409" class="margin10"/>-->
                        <!--ANTIGUO CODIGO-->
                        <div id="mcPrincipal" style="">
                            <input class="fancyy" type ="hidden"/>
                            <a id="mcl1"><img id="mc1" class="mcNumero" /></a>
                            <a id="mcl2"><img id="mc2" class="mcNumero" /></a>
                            <a id="mcl3"><img id="mc3" class="mcNumero" /></a>
                            <a id="mcl4"><img id="mc4" class="mcNumero" /></a>
                            <a id="mcl5"><img id="mc5" class="mcNumero" /></a>
                            <a id="mcl6"><img id="mc6" class="mcNumero" /></a>
                        </div>
                        <!--ANTIGUO CODIGO-->

                        <!--CHANGE CONTROL 1.1.0-->
                        <?php // include("mapaCurso/index.php?alumno=si&idCurso=$idCurso&idRelCursoGrupo=$idRelCursoGrupo"); ?>
                        <!--IFRAME-->
                        <!--<iframe class="fancyy" style="width:100%; height: 100%;" marginheight="0" marginwidth="0" frameborder="0" scrolling="No" src="../../mapaCurso/index.php?alumno=si&idCurso=<?= $idCurso ?>&idRelCursoGrupo=<?= $idRelCursoGrupo ?>"></iframe>-->
                    </div>
                    <div id="calidad_nav">
                        <span>Cuestionarios</span>
                        <ul>
                            <li><a href="">Diagnóstica</a></li>
                            <li><a href="">Autoevaluación</a></li>
                            <li><a href="">Final</a></li>
                            <li><a href="">Calidad</a></li>

                        </ul>

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
