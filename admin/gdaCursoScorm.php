<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();

/**
 * CHANGE CONTRO 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACION: 02 DE JUNIO DE 2014
 * OBJETIVO: CAMBIO ESTETICO EN EL MENSAJE DE SALIDA.
 */

if ($_POST) {

    $info = destripaPost($_POST, "/", "cursos, equivalencias_numericas");
    $errores = array();

//    echo "<br><br>";
//    echo "<br>campos:".$info["campos"]["cursos"];
//    echo "<br>valores:".$info["valores"]["cursos"];
    //Insertar curso
    
    if(esGestorContenido()){
        $info["campos"]["cursos"].=", id_gestor_contenido";
        $info["valores"]["cursos"].=", ".obtenerIDTabla();                        
    }
    
    $id_curso = insertarDatos("cursos", $info["campos"]["cursos"], $info["valores"]["cursos"]);

    //Rangos numericos
    insertarDatos("equivalencias_numericas", $info["campos"]["equivalencias_numericas"] . ", id_curso", $info["valores"]["equivalencias_numericas"] . ", $id_curso");

    //Guarda la plantilla del curso
    if (!empty($_FILES["plantilla"]["tmp_name"])) {
        $url_storage = guardaStorage($_FILES["plantilla"]["tmp_name"], $id_curso, "cursos");

        if ($url_storage != "") {
            //Acutaliza url_storage
            actualiza($id_curso, $url_storage, "cursos", "url_storage", "id_curso");
        } else {
            array_push($errores, "El curso no pudo subirse correctamente");
        }
    }

    $i = 0;
    $cursosActivos = 0;
    foreach ($_POST["nombre_unidad"] as $nombre) {

//        echo "<br>------------------------------------------";
//        echo "<br>Topico $i";
//        echo "<br>Nombre unidad:$nombre";
//        echo "<br>Descripcion:" . $_POST["descripcion"][$i];
//        echo "<br>Nombre Archivo:" . $_FILES["contenido"]["name"][$i];
//        echo "<br>Tipo:" . $_FILES["contenido"]["type"][$i];
//        echo "<br>Archivo:".$_FILES["contenido"]["tmp_name"][$i];
        //Insertar unidades
        $no_unidad = $i + 1;
        $nombre_unidad = $nombre;
        $descripcion = $_POST["descripcion"][$i];

        //Si tiene toda la informacion guarda el registro en la base y manda el archivo al repositorio de contenidos
        if (!empty($_FILES["contenido"]["name"][$i]) || !empty($nombre) || !empty($_POST["descripcion"][$i])) {
            $status = 1;
            $cursosActivos++;
//            echo "<br>El contenido sera activo";
        } else {
            $status = 0;
            //$cursosActivos++;
//            echo "<br>El contenido NO  sera activo";
        }

        $campos = "no_unidad, nombre_unidad, descripcion, status, id_curso";
        $valores = "$no_unidad, '$nombre_unidad', '$descripcion', $status, $id_curso";

        //Insertar unidad
        $id_unidad = insertarDatos("unidades", $campos, $valores);

        //SI cargaron un contenido lo guardamos
        if (!empty($_FILES["contenido"]["tmp_name"][$i])) {
            //Guardar zip en el repositorio especifico
            $url_unidad = guardaStorage($_FILES["contenido"]["tmp_name"][$i], $id_unidad, "unidades");

            if ($url_unidad != "") {
                //Acutaliza url_unidad
                actualiza($id_unidad, $url_unidad, "unidades", "url_unidad", "id_unidad");
            } else {
                array_push($errores, "Ocurri&oacute; un error al cargar la unidad $no_unidad - $nombre_unidad, '$descripcion'");
            }
        }

        $i++;
    }

    //Redirecciona al siguiente proceso
//    echo 
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
            <?php include("../template/heads.php"); ?>
        </head>

        <body>
            <!--Up bar-->
            <?php include("../template/menuAdmin.php"); ?>

            <div class="container">

                <!-- Main hero unit for a primary marketing message or call to action -->
                <div class="hero-unit">
                    <h3>Procesando curso...</h3>
                    <div class="progress progress-success progress-striped active">
                        <div class="bar" style="width:100%">
                            <p>Contenidos cargados correctamente...</p>
                        </div>
                    </div>
                    <div class="alert alert-success">
                        <?php echo $cursosActivos; ?> bloques ser&aacute;n activos.
                    </div>
                    <div class="alert alert-error">
                        <?php echo $i - $cursosActivos; ?> bloques ser&aacute;n inactivos.
                    </div>
                    <?php 
                        if(count($errores) > 0){
                            echo '<div class="alert alert-error">';
                            for($i=0; count($errores); $i++){
                                $error = $errores[$i];
                                echo <<<html
                                  <p>$error</p>
html;
                            }
                            
                           echo '</div>';
                        }
                    ?>
                    <p class="text-right">
                        <a href="cursos.php?id=<?php echo $id_curso; ?>&fullname=<?php echo $_POST["cursos/nombre_curso"]; ?>" class="btn btn-primary">Continuar</a>
                    </p>

                </div>


            </div> <!-- /container -->

            <!-- Le javascript
            ================================================== -->
            <!-- Placed at the end of the document so the pages load faster -->
            <?php include("../template/bootstrapAssets.php"); ?>

        </body>
    </html>
    <?php
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) &&
            empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) {
        $displayMaxSize = ini_get('post_max_size');
        $fileMaxSize = ini_get("upload_max_filesize");
        $maxExecutionTime = ini_get("max_execution_time");

        switch (substr($displayMaxSize, -1)) {
            case 'G':
                $displayMaxSize = $displayMaxSize * 1024;
            case 'M':
                $displayMaxSize = $displayMaxSize * 1024;
            case 'K':
                $displayMaxSize = $displayMaxSize * 1024;
        }
        
        switch (substr($fileMaxSize, -1)) {
            case 'G':
                $fileMaxSize = $fileMaxSize * 1024;
            case 'M':
                $fileMaxSize = $fileMaxSize * 1024;
            case 'K':
                $fileMaxSize = $fileMaxSize * 1024;
        }

        $intentadoSubir = round($_SERVER['CONTENT_LENGTH'] / 1024 / 1024);
        $displayMaxSize = round($displayMaxSize / 1024 / 1024);
        $fileMaxSize = round($fileMaxSize / 1024 / 1024);

        $error = <<<error
                <ol class="text-error">
                    <li>
                        Se superó la capacidad máxima en el servidor para cargar múltiples contenidos
                        Se intentaron cargar $intentadoSubir MB, pero lo máximo son $displayMaxSize MB. 
                    </li>
                    <li>
                        Se superó la capacidad máxima en el servidor para cargar archivos de forma individual
                        Se intentaron cargar $intentadoSubir MB, pero lo máximo por archivo son $fileMaxSize MB. 
                    </li>
                    <li>
                        Se superó el tiempo máximo de ejecución en el servidor, el cual es de $maxExecutionTime segundos.
                    </li>
                    <li>Se perdió la conexión durante la carga.</li>                    
                 </ol>  
                
                <p class="text-error">Si usted cree que el problema presentado no coincide con alguna de las razones listadas, comuníquese con el administrador del sistema.<p>
                 
                      
error;
        $error = urlencode(htmlentities($error));
        header("Location:errorCargaCurso.php?error=$error");
    } else {
        header("Location:index.php");
    }
}
?>