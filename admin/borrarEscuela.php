<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();

/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 14/10/2013
 * Script que recibe una habilidad y la guarda en la base de datos
 * Esquema del sistema de gestion integral
 */
if ($_GET) {
    $idEscuela= $_GET["id"];
    
    if (borraAtributo($idEscuela, "escuelas", "id_escuela")) {
        //Mensaje satisfactorio de insercion
        //Inicia change control #8
        $mensaje = <<<MENSAJE
        <h3 class='text-success'>Eliminaci&oacute;n satisfactoria.</h3>
        <img src='../img/ok.png' width='50'>    
MENSAJE;
        //Termina change control #8
    } else {
        //Mensaje satisfactorio de insercion
        $mensaje = <<<MENSAJE
        <h3 class='text-error'>Oops hubo un problema al eliminar la escuela.</h3>
MENSAJE;
    }
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
    header("Location:escuelas.php");
}
?>


