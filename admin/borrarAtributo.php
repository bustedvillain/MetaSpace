<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 08/10/2013
 * Script que recibe una habilidad borra
 * Esquema del sistema de gestion integral
 */
if ($_GET) {
    $tipoAtributo = $_GET["tipoAtributo"];
    $idAtributo = $_GET["id"];
    
    switch ($tipoAtributo) {
        case "Habilidad":
            $id_nombre = "id_habilidad";
            $tabla = "habilidades";
            break;
        case "Asignatura":
            $id_nombre = "id_asignatura";
            $tabla = "asignaturas";
            break;
        case "Categoria":
            $id_nombre = "id_categoria";
            $tabla = "categorias";
            break;
        case "Institucion":
            $id_nombre = "id_institucion";
            $tabla = "instituciones";
            break;
        case "Empresa":
            $id_nombre = "id_empresa";
            $tabla = "empresa";
            break;
        case "NivelEducativo":
            $id_nombre = "id_nivel";
            $tabla = "nivel_escolar";
            break;
        case "GradoEscolar":
            $id_nombre = "id_grado_escolar";
            $tabla = "grado_escolar";
            break;
        case "Nacionalidad":
            $id_nombre = "id_nacionalidad";
            $tabla = "nacionalidad";
            break;
    }
    
    if (borraAtributo($idAtributo, $tabla, $id_nombre)) {        
        //Mensaje satisfactorio de insercion
        $mensaje = <<<MENSAJE
        <h3 class='text-success'>$tipoAtributo eliminado(a) correctamente</h3>
        <img src='../img/ok.png' width='50'>    
MENSAJE;
    } else {
        //Mensaje satisfactorio de insercion
        $mensaje = <<<MENSAJE
        <h3 class='text-error'>Hubo un problema al eliminar $tipoAtributo.</h3>
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
                <meta http-equiv='Refresh' content="0;url=$tabla.php?mensaje=$mensaje" />
REDIRECCION;
            ?>
        </head>
        <body>


        </body>
    </html>
    <?php
} else {
    header("Location:index.php");
}
?>


