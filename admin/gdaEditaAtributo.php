<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 08/10/2013
 * Script que recibe una habilidad y la edita
 * Esquema del sistema de gestion integral
 */
if ($_POST && !empty($_POST["tipoAtributo"])) {
    $tipoAtributo = $_POST["tipoAtributo"];
    $atributo = $_POST["atributo"];
    $idAtributo = $_POST["idAtributo"];

    switch ($tipoAtributo) {
        case "Habilidad":
            $nombre_atributo = "nombre_habilidad";
            $id_nombre = "id_habilidad";
            $tabla = "habilidades";
            break;
        case "Asignatura":
            $nombre_atributo = "nombre_asignatura";
            $id_nombre = "id_asignatura";
            $tabla = "asignaturas";
            break;
        case "Categoria":
            $nombre_atributo = "nombre_categoria";
            $id_nombre = "id_categoria";
            $tabla = "categorias";
            break;
        case "Institucion":
            $nombre_atributo = "nombre_institucion";
            $id_nombre = "id_institucion";
            $tabla = "instituciones";
            break;
        case "Empresa":
            $nombre_atributo = "nombre_empresa";
            $id_nombre = "id_empresa";
            $tabla = "empresa";
            break;
        case "Nivel_educativo":
            $nombre_atributo = "nombre";
            $id_nombre = "id_nivel";
            $tabla = "nivel_escolar";
            break;
        case "Grado_escolar":
            $nombre_atributo = "nombre_grado";
            $id_nombre = "id_grado_escolar";
            $tabla = "grado_escolar";
            $idNivel = $_POST["id_nivel_escolar"];
            break;
        case "Nacionalidad":
            $nombre_atributo = "nacionalidad";
            $id_nombre = "id_nacionalidad";
            $tabla = "nacionalidad";
            break;
    }

    if (actualizaAtributo($idAtributo, $atributo, $tabla, $nombre_atributo, $id_nombre)) {
        //Mensaje satisfactorio de insercion
//        $mensaje = <<<MENSAJE
//        <h3 class='text-success'>$tipoAtributo editado(a) correctamente</h3>
//        <img src='../img/ok.png' width='50'>    
//MENSAJE;
        $mensaje = <<<MENSAJE
        <h3 class='text-success'>Edici&oacute;n Exitosa</h3>
        <img src='../img/ok.png' width='50'>    
MENSAJE;
    } else {
        //Mensaje satisfactorio de insercion
        $mensaje = <<<MENSAJE
        <h3 class='text-error'>Hubo un problema al editar $tipoAtributo. Probablemente ésta ya se encuentra registrado(a).</h3>
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


