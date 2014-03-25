<?php
include("Funciones.php");
if ($_POST) {
    $instruccion = $_POST["ins"];
    switch ($instruccion) {
        case 'tutoresDeAlumno';
            if(isset($_POST["idAlumno"])){
                $idAlumno = $_POST["idAlumno"];
//                echo   'recibiste'.$idAlumno. '--';
                echo optionsComboPadreDeTutoresJSON($idAlumno);
            }else{
                echo 'Sin Alumnos';
            }
            break;
    }
}
?>
