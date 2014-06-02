<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();
/**
 * CHANGE CONTROL 0.99.7
 * FECHA: 8 DE ENERO DEL 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJEVITO: ENROLAR TUTORES EN CURSO/GRUPO DE MOODLE
 */
if ($_POST["id_curso_abierto"]) {
//    var_dump($_POST);
    $idCursoAbierto = $_POST["id_curso_abierto"];
//    imprimeConsola("id_curso_abierto:".$idCursoAbierto);
//    imprimeConsola("id grupos:".$_POST["id_grupos"]);
    $id_grupos = explode(";", $_POST["id_grupos"]);

    //Info para grupos / enrol moodle
    $infoGrupos = array();

    //Recorremos los grupos
    foreach ($id_grupos as $idGrupo) {
        if ($idGrupo != "") {
            $idRelacionCursoGrupo = consultaRelCursoGrupo($idCursoAbierto, $idGrupo);
            vaciaRelacionesCursoTutor($idRelacionCursoGrupo);
            $idsTutoresCoord = insertarTutoresCursoAbierto("coordinadores", $_POST, $idRelacionCursoGrupo, $idGrupo, true);
            $idsTutoresSenior = insertarTutoresCursoAbierto("seniors", $_POST, $idRelacionCursoGrupo, $idGrupo);
            $idsTutoresJuniors = insertarTutoresCursoAbierto("juniors", $_POST, $idRelacionCursoGrupo, $idGrupo);

            /**
             * CHANGE CONTROL 0.99.7
             * FECHA: 8 DE ENERO DEL 2014
             * AUTOR: JOSE MANUEL NIETO GOMEZ
             * OBJEVITO: ENROLAR TUTORES EN CURSO/GRUPO DE MOODLE
             */
            //Insercion en moodle
            $idGrupoMoodle = get_id_moodle_group($idRelacionCursoGrupo);

            //Enrolar en Moodle y prepara informacion de enrolamiento
            $infoGrupo = crearGrupoMoodle($idCursoAbierto, $idGrupo, $idsTutoresCoord, ROL_COORDINADOR, $idGrupoMoodle);
            array_push($infoGrupos, $infoGrupo);
            
            $infoGrupo = crearGrupoMoodle($idCursoAbierto, $idGrupo, $idsTutoresSenior, ROL_TUTOR_SENIOR, $idGrupoMoodle);
            array_push($infoGrupos, $infoGrupo);
            
            $infoGrupo = crearGrupoMoodle($idCursoAbierto, $idGrupo, $idsTutoresJuniors, ROL_TUTOR_JUNIOR, $idGrupoMoodle);
            array_push($infoGrupos, $infoGrupo);
               
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
            <?php include("../template/heads.php"); ?>
            <script src="../assets/js/jquery.js"></script>
            <script type="text/javascript" src="../js/jquery.js"></script>
        </head>

        <body>
            <!--Up bar-->
            <?php include("../template/menuAdmin.php"); ?>

            <div class="container">

                <!-- Main hero unit for a primary marketing message or call to action -->
                <ul class="breadcrumb">
                    <li><a href="cursosAbiertos.php">Cursos Publicados</a> <span class="divider">/</span></li>
                    <li><a href="enrolarGrupo.php?id=<?=$idCursoAbierto?>">Asignar Grupos</a> <span class="divider">/</span></li>
                    <li><a href="enrolarTutores.php.php?id=$idCursoAbierto">Asignar Tutores</a> <span class="divider">/</span></li>
                    <li class="active">Tutores asignados</li>
                </ul>
                <div class="hero-unit" id="listo" style="display:none;">
                    <h1>Curso Publicado Correctamente</h1>
                    <p>Los usuarios pueden iniciar el curso</p>
                    <img src="../img/ok.png"/>
                </div>
<!--                <div id="smCargando">
                    <div class="alert alert-success"><h4><img src="../img/loading.gif" style="width: 4%;"/>&nbsp;&nbsp;&nbsp;Estamos vinculando con moodle, espere por favor.</h4></div>
                </div>-->
                <!--            
                        CHANGE CONTROL 0.99.7
                        FECHA DE MODIFICACION: 16 DE ENERO DEL 2014
                        AUTOR: JOSE MANUEL NIETO GOMEZ
                        OBJETIVO: INSCRIBIR TUTORES EN CURSO MEDIANTE IFRAME, ASI COMO
                                  AGREGARLOS A UN GRUPO ESPECIFICO
            -->
            <!--Enrolar tutores en moodle mediante iFrames-->
            <?php
            include "../sources/iframeAseguraLogin.php";
            $contador = 1;
            $login = false;
            foreach ($infoGrupos as $infoGrupo) {
                $enrolid = $infoGrupo["enrolid"];
                $rolId = $infoGrupo["rolid"];
                $userid = $infoGrupo["userid"];
                $groupid = $infoGrupo["groupid"];
                
                
                
                if ($userid != "") {
                    //Inscribir usuarios en moodle

                    if ($login === false) {
                        echo <<<iframe
                            <iframe style="" id="frameGrupos$contador" src="" width="1" height="1">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ENROLAR LOS USUARIOS AL CURSO EN MOODLE</iframe>
                            <script type="text/javascript">                                
                                $("#iframeMoodle").on('load', function(){
                                    debugConsole("Termino el login");
                                    $("#frameGrupos$contador").attr("src", "enrolarMoodle.php?enrolid=$enrolid&rolid=$rolId&userid=$userid");
                                });                        
                            </script>
                           
iframe;
                        $login = true;
                    } else {
                        $contadorAux = $contador - 1;
                        echo <<<iframe
                            <iframe style="" id="frameGrupos$contador" src="" width="1" height="1">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ENROLAR LOS USUARIOS AL CURSO EN MOODLE</iframe>
                            <script type="text/javascript">
                                debugConsole("Termino el frameGrupos$contadorAux");
                                $("#frameGrupos$contadorAux").load(function(){
                                    $("#frameGrupos$contador").attr("src","enrolarMoodle.php?enrolid=$enrolid&rolid=$rolId&userid=$userid");
                                });                        
                            </script>
                           
iframe;
                    }

                    $contadorAux = $contador;
                    $contador++;
                    //Agregar usuarios al grupo
                    echo <<<iframe
                        <iframe style="" id='frameGrupos$contador' src='' width="1" height="1">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO AGREGAR LOS USUARIOS AL GRUPO</iframe>
                        <script type="text/javascript">
                            $("#frameGrupos$contadorAux").load(function(){
                                debugConsole("Termino el frameGrupos$contadorAux");
                                $("#frameGrupos$contador").attr("src", "agregarGrupoMoodle.php?groupid=$groupid&userid=$userid");
                            });                        
                        </script>
iframe;
                    $contador++;
                }
                
            }

            if (count($infoGrupos) > 0) {
                $contador--;
//                $c = $contador -2;
                echo <<<iframe
                     <div class="smCargando">
                        <div class="alert alert-success"><h4><img src="../img/loading.gif" style="width: 4%;"/>&nbsp;&nbsp;&nbsp;Asignando Tutores, espere por favor.</h4></div>
                     </div>
                     <script type="text/javascript">
                        $("#frameGrupos$contador").load(function(){
                              $(".smCargando").hide("slow");  
                              $("#listo").show("fast");  
                        });
                     </script>
iframe;
            }
//                //Inscribir usuarios en moodle
//                echo <<<iframe
//                        <iframe id="frameMoodle1" src="enrolarMoodle.php?enrolid=$enrolid&rolid=$rolId&userid=$userid" style="display:none;">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ENROLAR LOS USUARIOS AL CURSO EN MOODLE</iframe>
//iframe;
//
//                //Agregar usuarios al grupo
//                echo <<<iframe
//                        <iframe id="frameMoodle2" src="agregarGrupoMoodle.php?groupid=$groupid&userid=$userid" style="display:none;">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO AGREGAR LOS USUARIOS AL GRUPO</iframe>
//iframe;
//            }
            ?>
            </div> <!-- /container -->
            
            <!-- Le javascript
            ================================================== -->
            <!-- Placed at the end of the document so the pages load faster -->
            <?php include("../template/bootstrapAssets.php"); ?>

        </body>
    </html>
    <?php
} else {
    header("Location:index.php");
}
?>

