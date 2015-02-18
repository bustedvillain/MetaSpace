<!--inicia lineas itt-->
<script type='text/javascript' src="../plantilla/core/js/variablesCore.js"></script>  
<?php
    include "../sources/Funciones.php";
    include "../sources/iframeAseguraLogin.php";
    //verificarSesionAlumno();
    //verificarSesionPlantilla();
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
                $baseStorage = BASE_STORAGE . "cursos";
                $tipoEjecucion = consultaTipoEjecucionCurso($idCurso);
                $arrUnidadesMC = arregloIdUnidadesMCScorm($_GET['alumno'], $idRelCursoGrupo, $_GET['idCurso'], $idAlumno, $tipoEjecucion);
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
                        
                        frontweb = true;
                    </script>
cabecera;
            } else if ($_GET['alumno'] == "no" && isset($_GET['idCurso'])) {
                $baseStorage = BASE_STORAGE . "cursos";
                $idCurso = $_GET['idCurso'];
                $arrUnidadesMC = arregloIdUnidadesMCScorm($_GET['alumno'], null, $_GET['idCurso'], null, "0");
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
        <!--/CODIGO ANTIGUO-->
 <!--        <script>
 
             var arrIdUnidades = //echo arregloIdUnidadesMC($_GET['alumno'], $idRel);
             var alumno = " //echo $_GET['alumno']; "
         </script>-->
        <link rel="stylesheet" type="text/css" href="../css/jquery.fancybox.css"/>

        <link rel="stylesheet" href="../css/jquery-ui.css" />
        <link rel="stylesheet" href="../css/estilosMapaCurso2.css" />


        <script type='text/javascript' src="../js/jquery-1.7.min.js"></script>  
        <script type='text/javascript' src="../js/jquery-ui.min.js"></script>
        <script type='text/javascript' src="../js/jquery.fancybox.js"></script>  
        <script type='text/javascript' src="../js/jqueryMapaCursoSco.js"></script> 
        <script type='text/javascript' src="../js/jquery.js"></script> 
    </head>



    <body>
        <div id="mcPrincipal" style="">
            <?php 
            $ipPublica= IP_SERVER_PUBLIC."moodle/mod/scorm/player.php";
        $query = new Query("SG");
        $query->sql="select id_curso_moodle from cursos where id_curso=$idCurso";
        $cursos = $query->select("arr");
        foreach($cursos as $cur){
            $cursoSco=$cur["id_curso_moodle"];
        }
        $queryMoodle = new Query("MOD");
        for($i=1;$i<=6;$i++){
            $scoid=0;
            $cm=0;
            $queryMoodle->sql="select launch,m.id from mdl_grade_items gi
                        inner join mdl_scorm s on s.course=gi.courseid
                        inner join mdl_course_modules m on m.course=s.course
                        inner join mdl_course_sections cs on cs.course= m.course
                        where courseid= $cursoSco and itemtype='mod' and cs.section = $i  and gi.iteminstance= s.id and m.section= cs.id
                        and m.instance=s.id and gi.itemname not like '' and s.name not like ''";
            $cursos = $queryMoodle->select("arr");
            if($cursos){
                foreach($cursos as $scorm){
                    $scoid=$scorm["launch"];
                    $cm=$scorm["id"];
                }
        
                echo<<<primera
                        <form action="$ipPublica" metod="post" name="abrirSco$i" id="scormviewform" target="_blank">
                            <div style="display:none">
                                <input id="b" type="radio" value="browse" name="mode">
                                <input id="n" type="radio" checked="checked" value="normal" name="mode">
                            </div>
                            <input type="hidden" name="display" value="popup" />
                            <input type="hidden" name="currentorg" value="BAN_ADM1B3" />
                            <input type="hidden" name="scoid" value="$scoid" />
                            <input type="hidden" name="cm" value="$cm" />
                            <a id="mcl$i" href="javascript:document.abrirSco$i.submit();" class="scolink"><img id="mc$i" class="mcNumero" /></a>
                        </form>
primera;
            }    
        }
            ?>
        </div>
    </body>
</html>
