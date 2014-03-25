<?php
/**
 * CHANGE CONTROL 0.99.7
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA: 8 DE ENERO DEL 2014
 * OBJETIVO: SUBIR FOTO POR USUARIO
 */
include("../sources/Funciones.php");
verificarSesionAdmnistrador();

//var_dump($_POST);
if ($_POST) {

//Encriptar contraseña
    $_POST["datos_personales/contrasena"] = nCrypt(ucfirst(Utilidades::generaPwd(6)) . "1As$");
//Darle formato a la fehca
//Transforma el texto en un formato especial a fecha
    $fechaNacimiento = DateTime::createFromFormat("d/m/Y", $_POST["datos_personales/fecha_nacimiento"]);
//Guarda la fecha en el formato que necesita PostgreSQL
    $_POST["datos_personales/fecha_nacimiento"] = $fechaNacimiento->format('Y-m-d');

//Verificar usuario inactivo existente con datos similares al nuevo
    verificaEliminacionUsuario($_POST["datos_personales/correo"], $_POST["datos_personales/nombre_usuario"]);

//Rol de usuario en Moodle
    $rolMoodle = "";

    switch ($_POST["tipo_usuario"]) {
        case "estudiante";
            unset($_POST["tipo_usuario"]);
//Valida los ids de padre y profesor

            $info = destripaPost($_POST, "/", "datos_personales, alumnos");
//Inserta
//            var_dump($info);
            $id_datos_personales = insertarDatos("datos_personales", $info["campos"]["datos_personales"] . ", tipo_usuario", $info["valores"]["datos_personales"] . ", 0");
            insertarDatos("alumnos", $info["campos"]["alumnos"] . ", id_datos_personales", $info["valores"]["alumnos"] . ", $id_datos_personales");
            $rolMoodle = ROL_ALUMNO;
            break;
        case "profesionista":
            unset($_POST["tipo_usuario"]);
            $info = destripaPost($_POST, "/", "datos_personales, alumnos");
//Inserta
            $id_datos_personales = insertarDatos("datos_personales", $info["campos"]["datos_personales"] . ", tipo_usuario", $info["valores"]["datos_personales"] . ", 0");
            insertarDatos("alumnos", $info["campos"]["alumnos"] . ", id_datos_personales", $info["valores"]["alumnos"] . ", $id_datos_personales");
            $rolMoodle = ROL_ALUMNO;
            break;
        case "tutor":
            unset($_POST["tipo_usuario"]);
            $info = destripaPost($_POST, "/", "datos_personales, tutor");
//Inserta
            $id_datos_personales = insertarDatos("datos_personales", $info["campos"]["datos_personales"] . ", tipo_usuario", $info["valores"]["datos_personales"] . ", 1");
            insertarDatos("tutor", $info["campos"]["tutor"] . ", id_datos_personales", $info["valores"]["tutor"] . ", $id_datos_personales");

            switch ($_POST["tutor/id_rol_tutor"]) {
//Junior
                case "1":
                    $rolMoodle = ROL_TUTOR_JUNIOR;
                    break;
//Senior
                case "2":
                    $rolMoodle = ROL_TUTOR_SENIOR;
                    break;
//Coordinador
                case "3":
                    $rolMoodle = ROL_COORDINADOR;
                    break;
            }
            break;
        case "profesor":
            unset($_POST["tipo_usuario"]);
            $info = destripaPost($_POST, "/", "datos_personales, profesores_aula");
//Inserta
            $id_datos_personales = insertarDatos("datos_personales", $info["campos"]["datos_personales"] . ", tipo_usuario", $info["valores"]["datos_personales"] . ", 2");
            insertarDatos("profesores_aula", $info["campos"]["profesores_aula"] . ", id_datos_personales", $info["valores"]["profesores_aula"] . ", $id_datos_personales");
            break;
        case "padre":
            unset($_POST["tipo_usuario"]);
            $info = destripaPost($_POST, "/", "datos_personales, padres");
//Inserta
            $id_datos_personales = insertarDatos("datos_personales", $info["campos"]["datos_personales"] . ", tipo_usuario", $info["valores"]["datos_personales"] . ", 3");
            insertarDatos("padres", $info["campos"]["padres"] . ", id_datos_personales", $info["valores"]["padres"] . ", $id_datos_personales");
            break;
        case "gestor":
            unset($_POST["tipo_usuario"]);
            $info = destripaPost($_POST, "/", "datos_personales, gestor_contenido");
//Inserta
            $id_datos_personales = insertarDatos("datos_personales", $info["campos"]["datos_personales"] . ", tipo_usuario", $info["valores"]["datos_personales"] . ", 4");
            insertarDatos("gestor_contenido", $info["campos"]["gestor_contenido"] . ", id_datos_personales", $info["valores"]["gestor_contenido"] . ", $id_datos_personales");
            $rolMoodle = ROL_GESTOR;
            break;
        case "admin":
            unset($_POST["tipo_usuario"]);
            $info = destripaPost($_POST, "/", "datos_personales");
//Inserta
            $id_datos_personales = insertarDatos("datos_personales", $info["campos"]["datos_personales"] . ", tipo_usuario", $info["valores"]["datos_personales"] . ", 5");
            $rolMoodle = ROL_ADMIN;
            break;
    }

//Fotografia
    if ($_FILES["imagen"]["name"] != "") {
        almacenaInsertaImagen(NULL, $id_datos_personales);
    }

    try {
        @correoUsuarioNuevo($_POST["datos_personales/correo"], $_POST["datos_personales/nombre_usuario"], nDCrypt($_POST["datos_personales/contrasena"]));
    } catch (Exception $e) {
        echo "Error al enviar correo de confirmacion";
    }

//Usuario con rol en moodle
    if ($rolMoodle != "") {
        if (($insertMoodle = insertaUsuarioMoodle($_POST["datos_personales/nombre_usuario"], nDCrypt($_POST["datos_personales/contrasena"]), $_POST["datos_personales/nombre_pila"], $_POST["datos_personales/primer_apellido"] . " " . $_POST["datos_personales/segundo_apellido"], $_POST["datos_personales/correo"], $rolMoodle)) != true) {
            $msjErrorMoodle = "<p class='text-error'>Hubo un error al insertar el usuario en Moodle: $insertMoodle</p>";
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
            <?php include("../template/heads.php"); ?>
            <script src="../assets/js/jquery.js"></script>
        </head>

        <body>
            <!--Up bar-->
            <?php include("../template/menuAdmin.php"); ?>

            <div class="container">

                <!-- Main hero unit for a primary marketing message or call to action -->
                <!-- Main hero unit for a primary marketing message or call to action -->
                <div class="hero-unit">
                    <h1>Inserci&oacute;n Exitosa</h1>
                    <h3>Se ha enviado un correo de confirmaci&oacute;n a la cuenta indicada con los datos de acceso. En algunas ocasiones el correo puede llegar a la bandeja de "Correo no deseado" o "Spam"</h3>
                    <img src="../img/ok.png"/>

                </div>  
                <?php
                if (isset($msjErrorMoodle)) {
                    echo $msjErrorMoodle;
                }

                //Asigna rol en moodle
                include "../sources/iframeAseguraLogin.php";

                if (isset($_POST["datos_personales/correo"])) {
                    if ($rolMoodle != "") {

                        $userid = get_moodle_user($_POST["datos_personales/correo"]);
                        if ($rolMoodle == ROL_ADMIN) {
                            //Asignar administrador
//                                echo <<<iframe
//                        <iframe id="frameMoodle1" src="asignarAdministradorMoodle.php?userid=$userid" >ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ADMINISTRAR ROLES EN MOODLE</iframe>
//iframe;
                            echo <<<iframe
                            <iframe  id="frameUser" src="" width="1" height="1">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ENROLAR LOS USUARIOS AL CURSO EN MOODLE</iframe>
                            <script type="text/javascript">                                
                                $("#iframeMoodle").on('load', function(){
                                    debugConsole("Termino el login");
                                    $("#frameUser").attr("src", "asignarAdministradorMoodle.php?userid=$userid");
                                });                        
                            </script>
                           
iframe;
                        } else {
                            //Asignar rol
                            $userid = get_moodle_user($_POST["datos_personales/correo"]);
                            $idRol = get_assignable_roles($rolMoodle);

//                                echo <<<iframe
//                        <iframe id="frameMoodle2" src="asignarRolesGlobalesMoodle.php?roleid=$idRol&userid=$userid" >ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ADMINISTRAR ROLES EN MOODLE</iframe>
//iframe;
                            echo <<<iframe
                    <iframe  id="frameUser" src="" width="1" height="1">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ENROLAR LOS USUARIOS AL CURSO EN MOODLE</iframe>
                    <script type="text/javascript">                                
                        $("#iframeMoodle").on('load', function(){
                            debugConsole("Termino el login");
                            $("#frameUser").attr("src", "asignarRolesGlobalesMoodle.php?roleid=$idRol&userid=$userid");
                        });                        
                    </script>
                           
iframe;
                            echo <<<iframe
                    <div class="smCargando">
                        <div class="alert alert-success"><h4><img src="../img/loading.gif" style="width: 4%;"/>&nbsp;&nbsp;&nbsp;Estamos vinculando con moodle, espere por favor.</h4></div>
                     </div>
                     <script type="text/javascript">
                        $("#frameUser").load(function(){
                              $(".smCargando").hide("slow");  
                              $("#listo").show("fast");  
                        });
                     </script>
iframe;
                        }
                    }
                }
                ?>
                

                

                
                    
                <?php include("../template/footer.php"); ?>

            </div> <!-- /container -->

            <!-- Le javascript
            ================================================== -->
            <!-- Placed at the end of the document so the pages load faster -->
    <?php include("../template/bootstrapAssets.php"); ?>

        </body>
    </html>

    <?php
} else {
    header("Location:index.php");
}
?>