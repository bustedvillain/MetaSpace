<?php require_once '../../sources/Funciones.php'; ?>
<html>
    <head>
        <title>Mapa Unidad</title>
        <link type="text/css" rel="stylesheet" href="mapaUnidad.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
    </head>
    <body>
        <div id="mapaUnidad">     
            <div  class="row gris">
                <div id="titulo" class="grid_12">
                    <h1>Mapa de Bloque <?php echo obtenerNoUnidad($_GET['idUnidad']); ?></h1>
                </div>
            </div>
            <br/>
            <div class="row">

                <?php
                $idUnidad = $_GET['idUnidad'];
                $idRelCursoGrupo = $_GET['idRelCursoGrupo'];
                $idAlumno = $_GET['idAlumno'];

                generarSeries($idUnidad, $idRelCursoGrupo, $idAlumno);

                $porcentajeAvance = porcentajeAvance($idUnidad, $idRelCursoGrupo, $idAlumno);
                ?>
            </div>
            <div  class="row">
                <div class="grid_12">
                    <div  class=" fondoTrofeo gris">
                        <div  class="progresoMapa">
                            <div class="loaderFondo">
                                <div class="porcentajeCorrecto" style="width: <?php echo $porcentajeAvance ?>%"></div>
                            </div>
                        </div>
                        <div  id="porcentaje">
                            <div><?php echo $porcentajeAvance ?>%</div>
                        </div>
                        <div class="cajaPromedio">
                            <div class="textoPromedio">PROMEDIO</div>
                        </div>
                        <div class="cajaPromedioImagen">
                            <div id="trofeoPromedio">
                                <!--<img src="TROFEO ORO-A.png"/>-->
<?php echo obtenerImagenPromedioGeneral($idUnidad, $idRelCursoGrupo, $idAlumno) ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>



        </div>
        <script>
            //De acuerdo a la posicion actual, busca el id del contenido y lo colorea de color naranja
            document.getElementById(parent.getIndiceActual()+"-a").style.color= "#ff9000";
            document.getElementById(parent.getIndiceActual()+"-b").style.backgroundColor = "#ff9000";
            document.getElementById(parent.getIndiceActual()+"-b").style.color= "white";
            
        </script>
    </body>

</html>