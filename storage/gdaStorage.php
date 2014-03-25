<?php

include("sources/Funciones.php");
if ($_POST && $_FILES) {
//    var_dump($_POST);
//    echo "<br>";
//    var_dump($_FILES);
//    $id_unidad = $_POST["id_unidad"];


    $nombreCarpeta = $_POST["carpeta"];
    $destino = $_POST["destino"] . "/" . $nombreCarpeta;


    if ($_POST["actualiza"] != NULL) {
        if (eliminarDir($destino)) {
//            echo "Carpeta eliminada correctamente";
        } else {
//            echo "No se pudo eliminar la carpeta";
        }
    }

    //Verifica que exista el directorio donde se quiere guardar
    if (!file_exists($_POST["destino"])) {
        if (mkdir($_POST["destino"], 0777)) {
//        echo "<br>Directorio Creado";
        } else {
        echo "<br>Error al crear directorio para el destino";
        }
    }
    //Crea directorio
    if (mkdir($destino, 0777)) {
//        echo "<br>Directorio Creado";
    } else {
        echo "<br>Error al crear directorio";
    }
    //Modifica permisos
    if (chmod($destino, 0777)) {
//        echo "<br>Destino modificado";
    } else {
        echo "<br>Error al modificar directorio";
    }

    //Copia zip
    $destinoArchivo = $destino . "/" . $_FILES["archivo"]["name"];
//    echo "<br>Destino:$destino";
    if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $destinoArchivo)) {
//        echo "<br>Archivo copiado correctamente";
        //Descomprime Zip
        descomprimeZIP(realpath($destinoArchivo), $destino);
    } else {
        echo "<br>Error al copiar archivo";
    }



    echo $destino;
} else {
    exit("Error, no hay post & files");
}
?>