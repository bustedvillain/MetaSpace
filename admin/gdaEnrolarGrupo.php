<?php
include '../sources/Funciones.php';
verificarSesionAdminOGestor();
/**
 * CHANGE CONTROL 0.99.7
 * FECHA: 8 DE ENERO DEL 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJETIVO: ENROLAR ALUMNOS EN CURSO/GRUPO DE MOODLE
 */

/**
 * CHANGE CONTROL 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACIÓN: 21 DE MAYO DE 2014
 * OBJETIVO: CAMBIOS ESTETICOS
 */

if ($_POST) {
//    var_dump($_POST);
    if ($_POST["cambio"] == 0) {//!isset($_POST["grupos"]) || !isset($_POST["idCursoAbierto"])){
        redireccionaPHP("./index.php");
    }
    $errores = array();
    $idCursoAbierto = $_POST["idCursoAbierto"];
//    var_dump($_POST);
    $arr = "{";
    $nombres = "";
    $arrElementos = getIdElementosAERdeCursoAbierto($idCursoAbierto);
    $arrExistentes = getGruposdeCursoAbierto($idCursoAbierto);
    $arrEliminar = array();
    $arrRecibidos = $_POST["grupos"];

    //Info para grupos / enrol moodle
    $infoGrupos = array();

    foreach ($arrRecibidos as $idGrupo) {
        $arrIdAlumnos = getArrIdsAlumno($idGrupo);
        $idRelCursoGrupo = vinculaGrupoAlumnoCursoAbierto($idGrupo, $idCursoAbierto, $arrElementos, $arrIdAlumnos);
//        imprimeConsola("id rel curso grupo:$idRelCursoGrupo");
        /**
         * CHANGE CONTROL 0.99.7
         * FECHA: 8 DE ENERO DEL 2014
         * AUTOR: JOSE MANUEL NIETO GOMEZ
         * OBJETIVO: ENROLAR ALUMNOS EN CURSO/GRUPO DE MOODLE
         */
        //Enrolar en Moodle y prepara informacion de enrolamiento
        $infoGrupo = crearGrupoMoodle($idCursoAbierto, $idGrupo, $arrIdAlumnos, ROL_ALUMNO);
        array_push($infoGrupos, $infoGrupo);

        if (is_numeric($infoGrupo["groupid"])) {
            actualiza($idRelCursoGrupo, $infoGrupo["groupid"], "rel_curso_grupo", "id_grupo_moodle", "id_rel_curso_grupo");
        } else {
            $grupo = consultaUnGrupo($idGrupo);
            $nombreGrupo = $grupo->nombre_grupo;
            array_push($errores, "Ocurri&oacute; un error al enrolar el grupo en Moodle: $nombreGrupo");
        }
    }

    if (isset($arrExistentes)) {
        foreach ($arrExistentes as $idGrupo) {
            if (!in_array($idGrupo->id_grupo, $arrRecibidos)) {
//            echo 'id='.$idGrupo->id_grupo.'<br/>';
                array_push($arrEliminar, $idGrupo->id_grupo);
                $nombres = $nombres . getNomreGrupo($idGrupo->id_grupo) . ", ";
                $arr = $arr . $idGrupo->id_grupo . ",";
            }
        }
    }
    if ($arr != "{") {
        $arr = substr($arr, 0, strlen($arr) - 1);
        $arr = $arr . "}";
    } else {
        $arr = $arr . "}";
    }
} else {
    //redireccionaPHP("../signin.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
        <?php include("../template/heads.php"); ?>
        <script src="../assets/js/jquery.js"></script>
        <script type="text/javascript" src="../js/jquery.js"></script>
        <script type="text/javascript">

//            var arrGrupos = jQuery.parseJSON(<?php //echo $_POST["grupos"]       ?>);
            var arrGrupos = ('<?php echo $arr; ?>');
            var idCursoAbierto = <?php echo $idCursoAbierto; ?>;
            debugConsole("Arr=" + arrGrupos);
//            alert(arrGrupos)
            function eliminaG()
            {

                $.post("../sources/Controlador.Admin.Grupos.php", {consulta: 'eliminaGrupoDeCurso', atributo: arrGrupos, idCursoAbierto: idCursoAbierto}, function(respuesta) {
                    if ($("#smCargando").length) {
                        $("#smCargando").css("display", "none");
                    }
                    $("#eliminar").css("display", "none");
                    alert(respuesta);
                });
            }
        </script> 
    </head>

    <body>
        <!--Up bar-->
        <?php include("../template/menuAdmin.php"); ?>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1 style="display:none" class="listo">Grupos Asignados Correctamente</h1>
                <img style="display:none" class="listo" src="../img/ok.png"/>
                <?php
                if (isset($_POST["grupos"]) || isset($_POST["idCursoAbierto"])) {
                    if (!empty($arrEliminar)) {
                        echo <<<elimi
                             <div id = "eliminar">
                             <h4> En el paso anterior usted removi&oacute; a los grupos: <p class="text-warning"> $nombres  </p>de &eacute;ste curso, si quiere confirmar la eliminaci&oacute;n pulse el bot&oacute;n <button type="button" class="btn btn-danger" onclick='eliminaG();' id="btn-add">Elminiar</button>  <button  id="listo2" type="button" class="btn btn-success" onclick='window.location = "cursosAbiertos.php";' id="btn-add">Cancelar eliminaci&oacute;n</button></h4>
                                    </div>
elimi;
                    }
                    if ($arrRecibidos != "") {
                        echo <<<html
                         
                            <h4 style="display:none" class="listo"> Para asignar tutores a los grupos presione el bot&oacute;n. <button type="button" class="btn btn-success" onclick='window.location = "enrolarTutores.php?id_curso_abierto=$idCursoAbierto";' id="btn-add">Continuar</button>  </h4>
                            
html;
                    }
                } else {
                    echo <<<html
                            <h4> Lo sentimos, su petici&oacute;n no puede ser procesada.  </h4>
html;
                }

                //Errores
                if (count($errores) > 0) {
                    echo '<div class="alert alert-error">';
                    for ($i = 0; count($errores); $i++) {
                        $error = $errores[$i];
                        echo <<<html
                                  <p>$error</p>
html;
                    }

                    echo '</div>';
                }
                ?>


            </div>
            <!--            
                        CHANGE CONTROL 0.99.7
                        FECHA DE MODIFICACION: 16 DE ENERO DEL 2014
                        AUTOR: JOSE MANUEL NIETO GOMEZ
                        OBJETIVO: INSCRIBIR ALUMNOS EN CURSO MEDIANTE IFRAME, ASI COMO
                                  AGREGARLOS A UN GRUPO ESPECIFICO
            -->
            <!--Enrolar alumnos en moodle mediante iFrames-->
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
                            <iframe width="1" height="1" id="frameGrupos$contador" src="" >ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ENROLAR LOS USUARIOS AL CURSO EN MOODLE</iframe>
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
                            <iframe width="1" height="1" id="frameGrupos$contador" src="" >ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ENROLAR LOS USUARIOS AL CURSO EN MOODLE</iframe>
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
                        <iframe width="1" height="1" id='frameGrupos$contador' src='' >ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO AGREGAR LOS USUARIOS AL GRUPO</iframe>
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
//                $c = $contador - 3;
                echo <<<iframe
                     <div class="smCargando">
                        <div class="alert alert-success"><h4><img src="../img/loading.gif" style="width: 4%;"/>&nbsp;&nbsp;&nbsp;Asignando Grupos, espere por favor.</h4></div>
                     </div>
                     <script type="text/javascript">
                        $("#frameGrupos$contador").load(function(){
                              $(".smCargando").hide("slow");  
                              $(".listo").show("fast");                                 
                        });
                     </script>
iframe;
            }
            ?>



        </div> <!-- /container -->
        
        <?php include("../template/footer.php"); ?>   

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <?php include("../template/bootstrapAssets.php"); ?>


    </body>
</html>
