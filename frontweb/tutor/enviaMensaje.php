<?php
include '../../sources/Funciones.php';
//var_dump($_POST);
if ($_POST) {
    if (isset($_POST['idDestinatario']) && isset($_POST['mensaje'])) {
//        if(insertarMensaje(obtenerIdDatosPersonales(), $_POST['idDestinatario'], $_POST['mensaje'])){
        insertarMensaje(obtenerIdDatosPersonales(), $_POST['idDestinatario'], $_POST['mensaje']);
        if(!isset($_POST["location"])){
            header("Location:mi_locker.php");
        }else{
            $location =$_POST["location"];
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
