
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>


        <!--<link rel="stylesheet" type="text/css" href="../css/jquery.mobile-1.3.2.css"/>-->
<!--        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script> -->


        <script type="text/javascript" src="core/js/variablesCore.js" ></script>


        <?php
        include_once '../sources/Funciones.php';
        verificarSesionPlantillaFW();
        if ($_GET) {
//            var_dump($_GET);
//            if(isset($_GET["idIndiceNuevo"])){
//                echo '<script type="text/javascript">idIndiceNuevo=' . $_GET["idIndiceNuevo"] . '</script>';
//            }
            if (isset($_GET["alumno"]) && isset($_GET['idUnidad'])) {// && isset($_GET["idUnidad"])){
                $idUnidad = $_GET['idUnidad'];
                $idCurso = getIdCursoFromIdUnidad($idUnidad);
                $baseStorage = BASE_STORAGE;
                $templatePath = $baseStorage.'cursos/" + idCurso + "/templates';
                $unidadesPath = $baseStorage.'unidades/" + idUnidad';
                $navegador = navegadorActual();
                echo '
                    <script type="text/javascript">
                        
                        var idElementoCalif = -1;
                        volumenGral = 50;
                        idUnidad='.$idUnidad.';
                        idCurso = '.$idCurso.';
                        tipo_elemento = "i";
                        
                        template_path = "'.$templatePath.'";
                        unidades_path = "'.$unidadesPath.';
                        serie_path = unidades_path +  "/" + serie;
                        serie_path2 = serie_path;
                        idUnidad='.$idUnidad.';
                        navegador = "'.$navegador.'";
                    </script>
';
                imprimeHead($idUnidad);
                if ($_GET["alumno"] == "no") {
                    echo '<script type="text/javascript">alumno="no";</script>';
                } else {
                    $idAlumno = obtenerIDTabla();
//                    $idAlumno = $_GET['idAlumno'];
                    echo '<script type="text/javascript">alumno="si"; idAlumno=' . $idAlumno . '</script>';
                    if (isset($_GET['idUnidad'])) {
                        $idUnidad = $_GET['idUnidad'];
                        echo '<script type="text/javascript"> idUnidad='.$idUnidad.';</script>';
                    }
                    if (isset($_GET['idRelCursoGrupo'])) {
                        $idRelCursoGrupo = $_GET['idRelCursoGrupo'];
                        echo '<script type="text/javascript">idRelCursoGrupo=' . $idRelCursoGrupo . ';</script>';
                    }
                }
            }
        }
        ?>

        <link rel="stylesheet" type="text/css" href="core/css/jquery.fancybox.css"/>

        <link rel="stylesheet" href="core/css/jquery-ui.css" />
        <!--<script type='text/javascript' src="core/js/jquery-1.7"></script>-->  
        <script type='text/javascript' src="core/js/jquery-1.7.min.js"></script>  
        <!--<script type="text/javascript" src="../js/jquery.js"></script>-->
        <!--<script type='text/javascript' src="core/js/jquery-1.10.1.min.js"></script>--> 
        <script type='text/javascript' src="core/js/jquery-ui.min.js"></script>
        <script type='text/javascript' src="core/js/jquery.ui.touch-punch.min.js"></script>
        <script type='text/javascript' src="core/js/jquery.fancybox.js"></script>  
        <script type='text/javascript' src="core/js/audioplayer.js"></script>  


        <script type="text/javascript" src="core/js/corePlantilla.js" ></script>
        <link rel="stylesheet" type="text/css" href="core/css/estiloPlantilla.css"/>

    </head>
    <body onload="">
        <a id="yt" class="fancyCal" style='display: none'></a>
        <div id="smLoadingPlantilla">
            <div id="contTituloLoading"> <label id="tituloLoading">Cargando Plantilla...</label> <label id="loadingPorcentaje"></div>
            <div id="contImgLoading"><img id="imagenLoading" src="./core/css/images/loading.gif"/></div>
        </div>
        <div id="smPrincipal" style="opacity: -1">
            <div id="smMensaje" style="display: none; position:absolute; background-color: white; z-index:6000"></div>
            <div id="contenido" style="overflow: hidden">
                <iframe id="frameCont">
                </iframe>

                <!--                <audio preload="none" autoplay loop-->
            </div>
            <div id="recurso" style="padding: 0px; margin-top:-8%; margin-left:-5.2%;">

                <!--                <div id="audioRecurso" >
                                    <audio preload="auto" controls>
                                        <source src="instrucciones.wav" />
                                        <source src="instrucciones.mp3" />
                                        <source src="instrucciones.ogg" />
                                    </audio>
                                </div>-->
                Cerrar
            </div>
            <div id="smTop">
                <a id="btnMapa" class="fancyRecurso"><img id ="mapa"  class="smBoton smBotonT"  alt="Mapa" title="Mapa"></a>
                <a id="btnInstrucciones" class="fancyInstrucciones" href="#" title="Instrucciones"><img id ="instrucciones"  class="smBoton smBotonT" alt="Instrucciones" title="Instrucciones"/></a>
                <a id="btnTutor" class="fancyRecurso" href="#" title="Informacion para el Tutor"><img id ="tutor"  class="smBoton smBotonT" alt="Tutor" title="Informacion para el Tutor"/></a>
                <img id ="titulo"   alt="Titulo" title="Titulo"/>
                <img id ="botonSalir"  class="smBoton smBotonT"  alt="Salir" title="Salir" />

            </div>
            <div id="smLeft">
                <a class="btnRecurso fancyRecurso" name="ayuda" href="#" title="Ayuda"><img id="ayuda"  class="smBotonL" alt="Ayuda" title="Ayuda"/></a>
                <a class="btnRecurso fancyRecurso" name="temario" href="#" title="Temario"><img id="temario"  class="smBotonL" alt ="Temario" title="Temario"/></a>
                <a class="btnRecurso fancyRecurso" name="glosario" href="#" title="Glosario"><img id="glosario"  class="smBotonL" alt="Glosario" title="Glosario"/></a>
                <a class="btnRecurso fancyRecurso" name="bibliografia" href="#" title="Bibliograf&iacute;a"><img id="bibliografia"  class="smBotonL" alt="Bibliograf&iacute;a" title="Bibliograf&iacute;a"/></a>
                <!--<img id="creditos"  class="smBotonL" alt ="Cr&eacute;ditos" title="Cr&eacute;ditos"/>-->
            </div>

            <div id="smBottom">
                <a id ="btnReiniciar" title="Reiniciar" href="#"><img style="z-index: 1000" id ="botonReiniciar" class="smBoton botones-bottom" alt="Reiniciar" title="Reiniciar"/></a>
                <div id ="smBottomCenter">
                    <img id ="botonAnterior" class="smBoton botones-bottom" alt="Anterior" title="Anterior"/>
                    <img id ="botonPlay" class="smBoton" alt="Play" title="Reproducir"/>
                    <img id ="botonSiguiente" class="smBoton botones-bottom" alt="Siguiente" title="Siguiente"/>
                </div>
                <img id ="botonVolumen" class="smBoton botones-bottom" alt="Volumen" title="Volumen"/>

            </div>
            <div id="sliderVolumen"></div>
        </div>
<script src="../js/jquery.js"></script> 
<?php
imprimeScriptDeTiempoMaxSesion();
?>

    </body>
</html>