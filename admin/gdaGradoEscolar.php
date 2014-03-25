<?php include("../sources/Funciones.php");
 verificarSesionAdmnistrador();
/* 
 * FECHA DE CREACIÓN: 30 DE DICIEMBRE DEL 2013
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * OBJETIVO: ALMACENAR UN GRADO ESCOALR RELACIONADO A UN NIVEL EDUCATIVO
 */
 
if ($_POST && !empty($_POST["grado_escolar"])) {
    $idNivel=$_POST["id_nivel_escolar"];
    $gradoEscolar=  $_POST["grado_escolar"];    
    
    if (insertaGradoEscolar($gradoEscolar, $idNivel)) {
        //Mensaje saisfactorio de insercion
        $mensaje = <<<MENSAJE
        <h3 class='text-success'>Inserci&oacute;n Exitosa</h3>
        <img src='../img/ok.png' width='50'>    
MENSAJE;
    } else {
        //Mensaje satisfactorio de insercion
        $mensaje = <<<MENSAJE
        <h3 class='text-error'>En el cat&aacute;logo ya existe el grado escolar.</h3>
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
                <meta http-equiv='Refresh' content="0;url=grado_escolar.php?mensaje=$mensaje" />
REDIRECCION;
            ?>
        </head>
        <body>


        </body>
    </html>
    <?php
} else {
    header("Location:grado_escolar.php");
}
?>

