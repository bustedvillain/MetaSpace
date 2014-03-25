<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 14/10/2013
 * Script que recibe una habilidad y la guarda en la base de datos
 * Esquema del sistema de gestion integral
 */
if ($_POST && !empty($_POST["escuela"]) && !empty($_POST["idInstitucion"]) && !empty($_POST["idEscuela"])) {
    $idInstitucion=$_POST["idInstitucion"];
    $escuela= ($_POST["escuela"]);
    $idEscuela= $_POST["idEscuela"];
    
    if (actualizaEscuela($idInstitucion, $escuela, $idEscuela)) {
        //Mensaje satisfactorio de insercion
        $mensaje = <<<MENSAJE
        <h3 class='text-success'>Edici&oacute;n Exitosa</h3>
        <img src='../img/ok.png' width='50'>    
MENSAJE;
    } else {
        //Mensaje satisfactorio de insercion
        $mensaje = <<<MENSAJE
        <h3 class='text-error'>En el cat&aacute;logo ya existe la escuela. Verifica el Nombre.</h3>
MENSAJE;
    }
    $mensaje = urlencode(htmlentities($mensaje));
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <title></title>
            <?php
            //Redireccion a habilidades
            echo <<<REDIRECCION
                <meta http-equiv='Refresh' content="0;url=escuelas.php?mensaje=$mensaje" />
REDIRECCION;
            ?>
        </head>
        <body>


        </body>
    </html>
    <?php
} else {
    header("Location:habilidades.php");
}
?>


