<?php
//Archivo creado para control de cambios #7
include '../../sources/Funciones.php';
verificarSesionAlumno();
//    var_dump($_FILES);
//if(isset($_POST["idDatosPersonales"]) && isset($_POST["imagen"])){
if(isset($_FILES["imagen"])){
    
//    var_dump($_FILES);
//    var_dump($_POST);
//    echo 'ruta es='.$ruta;
//    $idDatosPersonales = $_POST["idDatosPersonales"];
    almacenaInsertaImagen("perfil.php");
}
else{
    echo 'ni llego';
//    header("Location:perfil.php");
}


?>
