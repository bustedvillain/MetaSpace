<?php

include '../../sources/Funciones.php';
//var_dump($_POST);
if ($_POST) {
    if (isset($_POST['idDestinatario']) && isset($_POST['mensaje']) && isset($_POST["tipo"])) {
//        if(insertarMensaje(obtenerIdDatosPersonales(), $_POST['idDestinatario'], $_POST['mensaje'])){
        if ($_POST["tipo"] == "alumnos") {
            if (isset($_POST["todos"])) {
                $arrGrupos = obtenerGruposDeTutor(obtenerIDTabla());
                
                foreach ($arrGrupos as $g) {
                    $arrAlumnos = obtenerAlumnos($g->id_grupo);
                    $arrIds = array();
                    foreach ($arrAlumnos as $a) {
                        insertarMensaje(obtenerIdDatosPersonales(), $a->id, $_POST['mensaje']);
//                        array_push($arbrIds, $a->id_datos_personales);
                    }
                }
            } else { //si no son todos
                $arrAlumnos = obtenerAlumnos($_POST['idDestinatario']);
                foreach ($arrAlumnos as $a) {
                    insertarMensaje(obtenerIdDatosPersonales(), $a->id, $_POST['mensaje']);
//                        array_push($arbrIds, $a->id_datos_personales);
                }
            }
        } else {//si es padres
            $arrAlumnos = obtenerAlumnos($_POST['idDestinatario']);
                foreach ($arrAlumnos as $a) {
                    insertarMensaje(obtenerIdDatosPersonales(), padreDeAlumno($a->id), $_POST['mensaje']);
//                        array_push($arbrIds, $a->id_datos_personales);
                }
        }
//        insertarMensaje(obtenerIdDatosPersonales(), $_POST['idDestinatario'], $_POST['mensaje']);
        if (!isset($_POST["location"])) {
            header("Location:mi_locker.php");
        } else {
            $location = $_POST["location"];
            echo <<<hr
            <script type="text/javascript">
                alert("Mensaje enviado");
                window.location='$location';
            </script>
hr;
        }
    } else {
        header("Location:comunicacion.php");
    }
} else {
    header("Location:comunicacion.php");
}
?>
