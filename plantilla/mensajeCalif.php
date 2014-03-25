<?php
include '../sources/Funciones.php';
if (!$_GET || !isset($_GET["idCurso"]) || !isset($_GET["calificacion"])) {
    echo'
        <script type="text/javascript">
            window.close;
        </script>
        ';
}
$baseStorage = BASE_STORAGE;
$idCurso = $_GET["idCurso"];
$idElemento = $_GET["idElemento"];

//$numeroUnidad = numeroUnidadDeElemento($idElemento);
$nUni = numeroUnidadDeElemento($idElemento);
//debugConsole("EL ide unidad que recibi en calif es='.$nUni.'");
//$nUni = 1;
//echo'
//        <script type="text/javascript">
//            debugConsole("EL ide elemento que recibi en calif es='.$idElemento.'");
//            
//        </script>
//';
$calificacion = $_GET["calificacion"];
$imagenPremio = obtenerImagenPremio($idCurso, $calificacion);
//$tipoElemento = $_GET["tipoElemento"];
//$colorBorde = "#FFF"; //funcion del color de donde sea
$rutaCursos = $baseStorage."cursos/".$idCurso."/frontweb/";
$rutaPremios = $baseStorage."cursos/".$idCurso."/premios/";
$colorBorde = obtenerColor(STORAGE_PATH."cursos/".$idCurso."/frontweb/colores.ini");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Volver a intentarlo | META Space</title>
        <meta name="lang" content="es" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="shortcut icon" href="/favicon.ico" />

        <link href="imgCalif/reset.css" type="text/css" rel="stylesheet"  media="all" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Dosis:400,300,500' rel='stylesheet' type='text/css'>
        <style>
            #alumno_bloque {
                width: 825px;
                height: 430px;
                float: left;
                margin: 0px 10px 0px 40px;
                -moz-border-radius: 8px; /* Firefox */
                -webkit-border-radius: 8px; /* Google Chrome y Safari */
                border-radius: 8px; /* CSS3 (Opera 10.5, IE 9 y est&aacute;ndar a ser soportado por todos los futuros navegadores) */
            }

            #alumno_bloque h2 {
                font-family: 'Dosis', Arial, serif;
                font-weight: 500;
                font-size: 22px;
                width: 100%;
                height: 60px;
                line-height: 60px;
                padding: 0px 30px 0px;
                color: #fff;
                position:relative;
            }

            #alumno_bloque small {
                font-family: 'Dosis', Arial, serif;
                font-weight: 500;
                font-size: 28px;
                width: 100%;
                height: 60px;
                line-height: 60px;
                padding: 0px 0px 0px 390px;
                color: #fff;
            }

            #alumno_bloque_control {
                width: 773px;
                height: 121px;
                float: left;
                position: relative;
            }

            #alumno_bloque_control h3 {
                font-family: 'Dosis', Arial, serif;
                font-weight: 500;
                font-size: 38px;
                height: 42px;
                line-height: 42px;
                text-align: center;
                padding: 0px 30px 0px;
                color: #347ff2;
            }
            .contenido_largo {
                width: 790px;
                height: 340px;
                float: left;
                -moz-border-radius: 8px; /* Firefox */
                -webkit-border-radius: 8px; /* Google Chrome y Safari */
                border-radius: 8px; /* CSS3 (Opera 10.5, IE 9 y est&aacute;ndar a ser soportado por todos los futuros navegadores) */
                background-color: #fff;
                margin: 0px 17px;
            }
        </style>
    </head>
    <body>
        <div id="alumno_bloque" style="background: <?php echo $colorBorde; ?>; margin: 0px;">
            <h2>BLOQUE <?php echo $nUni; ?><img src="<?php echo $rutaCursos; ?>bloque.gif" class="elem_absolute" style="top: 0px; right: 80px;" width="57" height="57" /></h2>
            <div class="contenido_largo">
                <p class="margin10"><strong>Has ganado:</strong></p>
                <div class="elem_left" style="margin-left: 260px;"><img src="<?php echo $rutaPremios.$imagenPremio; ?>" width="240" height="180" /></div>
                <!--<div class="elem_left"><img src="../img/alumno/bloque_estrellas.jpg" width="99" height="216" /></div>-->
                <div id="alumno_bloque_control">
                    <h3>¿Quieres volver a intentarlo?</h3>
                    <div class="elem_absolute" style="top: 45px; left: 280px;"><a href="" onclick="parent.window.frameCont.location.reload(); parent.cierraCalif();"><img src="<?php echo $rutaCursos; ?>reintentar.jpg" width="87" height="72" /></a></div>
                    <div class="elem_absolute" style="top: 45px; left: 400px;"><a href="" onclick="parent.avanceDesdeCalif()"><img src="<?php echo $rutaCursos; ?>salir.jpg" width="87" height="72" /></a></div>
                </div>
            </div>

        </div>
        <!--<a href="#" onclick="parent.unaprueba();">has algo</a>-->
    </body>
</html>
