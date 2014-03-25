<?php
include("../sources/Funciones.php");
//verificarSesionAdmnistrador();
//var_dump($_FILES);
if ($_POST) {
    echo "Guardando cambios...";
//    echo "<br>POST " . $_POST["tipo_usuario"] . "<br>";
    $id_datos_personales = $_POST["id_datos_personales"];
    unset($_POST["id_datos_personales"]);
//    echo "id_datos_personales:$id_datos_personales";
    //Si no desea actualizar la contraseña eliminamos el campo
//    echo "habEditContrasena:".$_POST["habEditContrasena"];
    if ($_POST["habEditContrasena"] != "on") {
        unset($_POST["datos_personales/contrasena"]);
    } else {
//        imprimeConsola("Contrasena:".$_POST["datos_personales/contrasena"]);
        $_POST["datos_personales/contrasena"] = nCrypt($_POST["datos_personales/contrasena"]);
//        imprimeConsola("Contrasena enc:".$_POST["datos_personales/contrasena"]);
    }

    //Darle formato a la fehca
    //Transforma el texto en un formato especial a fecha
    $fechaNacimiento = DateTime::createFromFormat("d/m/Y", $_POST["datos_personales/fecha_nacimiento"]);
    //Guarda la fecha en el formato que necesita PostgreSQL
    $_POST["datos_personales/fecha_nacimiento"] = $fechaNacimiento->format('Y-m-d');


    //Nos dira si la actualizacion se llevo con exito
    $success = false;
    switch ($_POST["tipo_usuario"]) {
        case "estudiante";
            unset($_POST["tipo_usuario"]);
            $success = actualizaUsuario($_POST, "alumnos", $id_datos_personales);
            $redireccion = "verAlumnosEstudiantes.php";
            break;
        case "profesionista":
            unset($_POST["tipo_usuario"]);
            $success = actualizaUsuario($_POST, "alumnos", $id_datos_personales);
            $redireccion = "verAlumnosProfesionistas.php";
            break;
        case "tutor":
            unset($_POST["tipo_usuario"]);
            $success = actualizaUsuario($_POST, "tutor", $id_datos_personales);
            $redireccion = "verTutores.php";
            break;
        case "profesor":
            unset($_POST["tipo_usuario"]);
            $success = actualizaUsuario($_POST, "profesores_aula", $id_datos_personales);
            $redireccion = "verProfesores.php";
            break;
        case "padre":
            unset($_POST["tipo_usuario"]);
            $success = actualizaUsuario($_POST, "padres", $id_datos_personales);
            $redireccion = "verPadres.php";
            break;
        case "gestor":
            unset($_POST["tipo_usuario"]);
            $success = actualizaUsuario($_POST, NULL, $id_datos_personales);
            $redireccion = "verGestores.php";
            break;
        case "admin":
            unset($_POST["tipo_usuario"]);
            $success = actualizaUsuario($_POST, NULL, $id_datos_personales);
            $redireccion = "verAdministradores.php";
            break;
    }
    
    //Fotografia
    if($_FILES["imagen"]["name"] != ""){
        almacenaInsertaImagen(NULL, $id_datos_personales);
    }

    if ($success) {
        $mensaje = <<<MENSAJE
        <h3 class='text-success'>Edici&oacute;n Exitosa.</h3>
        <img src='../img/ok.png' width='50'>    
MENSAJE;
    } else {
        $mensaje = <<<MENSAJE
        <h3 class='text-error'>Hubo un problema al editar los datos del usuario. Probablemente intent&oacute; registrar un nombre de usuario y/o correo ya exitente.</h3>
MENSAJE;
    }
    $mensaje = urlencode(htmlentities($mensaje));
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <?php
            //Redireccion a habilidades
            echo <<<REDIRECCION
                <meta http-equiv='Refresh' content="0;url=$redireccion?mensaje=$mensaje" />
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