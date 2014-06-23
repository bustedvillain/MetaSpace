
<html>
    <head>
        <script type='text/javascript' src="../plantilla/core/js/variablesCore.js"></script>  
        <?php
        include "../sources/Funciones.php";
        verificarSesionPlantilla();
        
        /**
         * CHANGE CONTROL 1.1.0
         * AUTOR: JOSE MANUEL NIETO GOMEZ
         * FECHA DE MODIFICACION: 05 DE JUNIO DE 2014
         * OBJETIVO: AGREGAR EVENTO EN APERTURA DE BLOQUES PARA CAMBIAR A PANTALLA COMPLETA
         */
        
        /**
         * CHANGE CONTROL 1.1.0
         * AUTOR: JOSE MANUEL NIETO GOMEZ
         * FECHA DE MODIFICACION: 20 DE JUNIO DE 2014
         * OBJETIVO: AGREGAR CONSULTA PARA VALIDAR EL TIPO DE EJECUCION DE BLOQUES: LIBRE(AUTONOMA), SERIADA
         */
        if (isset($_GET['alumno']) && isset($_GET['idCurso'])) {//Si está creada la variable
            if ($_GET['alumno'] == "si" && isset($_GET['idRelCursoGrupo']) && isset($_GET['idCurso'])) {//si es un alumno
                $idCurso = $_GET['idCurso'];
                $idRelCursoGrupo = $_GET['idRelCursoGrupo'];
                $idAlumno = $_SESSION['idPorTabla'];
                $baseStorage = BASE_STORAGE . "cursos";
                
                $tipoEjecucion = consultaTipoEjecucionCurso($idCurso);
                
                $arrUnidadesMC = arregloIdUnidadesMC($_GET['alumno'], $idRelCursoGrupo, $_GET['idCurso'], $tipoEjecucion);
                
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
                        
                        frontweb = false;
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
<!--        <script>

            var arrIdUnidades = //echo arregloIdUnidadesMC($_GET['alumno'], $idRel);
            var alumno = " //echo $_GET['alumno']; "
        </script>-->
        <link rel="stylesheet" type="text/css" href="../css/jquery.fancybox.css"/>

        <link rel="stylesheet" href="../css/jquery-ui.css" />
        <link rel="stylesheet" href="../css/estilosMapaCurso.css" />


        <script type='text/javascript' src="../js/jquery-1.7.min.js"></script>  
        <script type='text/javascript' src="../js/jquery-ui.min.js"></script>
        <script type='text/javascript' src="../js/jquery.fancybox.js"></script>  
        <script type='text/javascript' src="../js/jqueryMapaCurso.js"></script> 
        <script type='text/javascript' src="../js/jquery.js"></script> 



    </head>
    <body>
        <!--Es el body y éste el <a href="./index.php?idRelCursoGrupo=1&idUnidad=1" class="fancyy">link </a>-->
        <div id="mcPrincipal">

            <a id="mcl1"><img id="mc1" class="mcNumero" /></a>
            <a id="mcl2"><img id="mc2" class="mcNumero" /></a>
            <a id="mcl3"><img id="mc3" class="mcNumero" /></a>
            <a id="mcl4"><img id="mc4" class="mcNumero" /></a>
            <a id="mcl5"><img id="mc5" class="mcNumero" /></a>
            <a id="mcl6"><img id="mc6" class="mcNumero" /></a>
        </div>
        <!--Es el body y éste el <a href="./index.php?idRelCursoGrupo=1&idUnidad=1" class="fancyy">link </a>-->
    </body>
</html>