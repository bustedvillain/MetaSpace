<?php
include("../sources/Funciones.php");
verificarSesionAdminOGestor();

if ($_POST) {
//    var_dump($_POST);
//    echo "<br><br>";
//    var_dump($_FILES);
    
    $id_curso = $_POST["cursos/id_curso"];
    unset($_POST["cursos/id_curso"]);
    
//    echo "<br><br>";
//    echo "id_curso:$id_curso";
    
    $sets = destripaPostEdicion($_POST, "/", "cursos");

//    echo "<br><br>";
//    echo "<br>sets:".$sets["cursos"];

    //Editar curso
    editaDatos("cursos", $sets["cursos"], "id_curso=$id_curso");
    
    //Verifica si se va a actualizar la plantilla
    if($_FILES["plantilla"]["tmp_name"]){
    $url_storage = guardaStorage($_FILES["plantilla"]["tmp_name"], $id_curso, "cursos", true);

    //Acutaliza url_storage
    actualiza($id_curso, $url_storage, "cursos", "url_storage", "id_curso");
    
    }
    
    $i = 0;
    $cursosActivos = 0;
    foreach ($_POST["nombre_unidad"] as $nombre) {

//        echo "<br>------------------------------------------";
//        echo "<br>Unidad $i";
//        echo "<br>ID_UNIDAD:".$_POST["id_unidad"][$i];
//        echo "<br>Nombre unidad:$nombre";
//        echo "<br>Descripcion:" . $_POST["descripcion"][$i];
//        echo "<br>Status:" . $_POST["status"][$i];
//        echo "<br>Archivo:" . $_FILES["contenido"]["name"][$i];
//        echo "<br>Tipo:" . $_FILES["contenido"]["type"][$i];

        //Editar unidades
        $id_unidad= $_POST["id_unidad"][$i];
        $nombre_unidad = $nombre;
        $descripcion = $_POST["descripcion"][$i];
        $status= $_POST["status"][$i];
        
        if($status==1){
            $cursosActivos++;
        }
        
        $sets= <<<SQL
                nombre_unidad = '$nombre_unidad',
                descripcion = '$descripcion',
                status = '$status'
SQL;
        //Editar unidad
       editaDatos("unidades", $sets, "id_unidad=$id_unidad");

        //SI cargaron un contenido lo actualizamos
        if (!empty($_FILES["contenido"]["tmp_name"][$i])) {
            $url_unidad = guardaStorage($_FILES["contenido"]["tmp_name"][$i], $id_unidad, "unidades", true);
            
            //Acutaliza url_unidad
            actualiza($id_unidad, $url_unidad, "unidades", "url_unidad", "id_unidad");
            
        }

        $i++;
    }

 
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
                            <p>Unidades editadas correctamente...</p>
                        </div>
                    </div>
                    <div class="alert alert-success">
                        <?php echo $cursosActivos; ?> unidades ser&aacute;n activas.
                    </div>
                    <div class="alert alert-error">
                        <?php echo $i-$cursosActivos; ?> unidades ser&aacute;n inactivas.
                    </div>
                    <p class="text-right">
                        <a href="nuevoCurso_2.php?id=<?php echo $id_curso; ?>&fullname=<?php echo $_POST["cursos/nombre_curso"]; ?>" class="btn btn-primary">Continuar</a>
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

        switch (substr($displayMaxSize, -1)) {
            case 'G':
                $displayMaxSize = $displayMaxSize * 1024;
            case 'M':
                $displayMaxSize = $displayMaxSize * 1024;
            case 'K':
                $displayMaxSize = $displayMaxSize * 1024;
        }
        
        $intentadoSubir=$_SERVER['CONTENT_LENGTH']/1024/1024;
        $displayMaxSize= $displayMaxSize/1024/1024;
            
        $error = <<<error
                Lo sentimos, has superado la capacidad maxima en el servidor para cargar contenidos
                Intentaste subir $intentadoSubir MB, pero lo maximo son $displayMaxSize MB.
error;
        
       header("Location:errorCargaCurso.php?error=$error");  
    }else{
        imprimeConsola("Error desconocido");
        header("Location:index.php");
    }    
}