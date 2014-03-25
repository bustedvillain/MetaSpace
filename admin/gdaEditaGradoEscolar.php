<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 03/01/2014
 * Script para editar un grado escolar
 * Esquema del sistema de gestion integral
 */
if ($_POST && !empty($_POST["atributo"]) && !empty($_POST["id_nivel_escolar"]) && !empty($_POST["idAtributo"])) {
    $idNivel=$_POST["id_nivel_escolar"];
    $gradoEscolar=  ($_POST["atributo"]);
    $idGrado= $_POST["idAtributo"];
    
    if (actualizaGradoEscolar($idGrado, $gradoEscolar, $idNivel)) {
        //Mensaje satisfactorio de insercion
        $mensaje = <<<MENSAJE
        <h3 class='text-success'>Edici&oacute;n Exitosa</h3>
        <img src='../img/ok.png' width='50'>    
MENSAJE;
    } else {
        //Mensaje satisfactorio de insercion
        $mensaje = <<<MENSAJE
        <h3 class='text-error'>En el cat&aacute;logo ya existe el grado escolar. Verifica el Nombre.</h3>
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


