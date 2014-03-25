<?php
include("../sources/Funciones.php");
verificarSesionAdmnistrador();

/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 27/11/2013
 * Confirma la insercion de datos con los almacenados en la sesion
 */
if (isset($_SESSION["proceso_alta_masiva"]) && isset($_SESSION["tipo_carga"])) {
//    var_dump($_SESSION["proceso_alta_masiva"]["filtroRegistros"]);
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Sistema de Gesti&oacute;n Integral Softmeta-A</title>
            <script type="text/javascript" src="../js/jquery.js"></script>
            <?php include("../template/heads.php"); ?>
            <script type="text/javascript">
                var altaGestores = 0;
            </script>
        </head>

        <body>
            <!--Up bar-->
            <?php include("../template/menuAdmin.php"); ?>
            <div class="container">

                <!-- Main hero unit for a primary marketing message or call to action -->
                <div class="hero-unit">
                    <h1>Alta Masiva</h1>
                    <div class="progress progress-striped active" id="progress">
                        <div class="bar" style="width:100%;">
                            <p id="mensaje">Insertando Datos, espere un momento...</p>
                        </div>
                    </div>

                </div>
<!--                <div class="smCargando">
                    <div class="alert alert-success"><h4><img src="../img/loading.gif" style="width: 4%;"/>&nbsp;&nbsp;&nbsp;Estamos vinculando con moodle, espere por favor.</h4></div>
                </div>-->

                <?php include("../template/bootstrapAssets.php"); ?>
                <?php
                flush();
                $errores = array();
                $continuar = "";

                //Enrol en moodle
                $usuariosMoodle = array(
                    "alumnos" => "",
                    "juniors" => "",
                    "seniors" => "",
                    "coordinadores" => "",
                    "gestores" => "",
                    "administradores" => ""
                );

                switch ($_SESSION["tipo_carga"]) {
                    case "combo":
                        $i = 0;
                        $rolMoodle = ROL_ALUMNO;
                        $roleid = get_assignable_roles(ROL_ALUMNO);
//                        include "../sources/iframeAseguraLogin.php";

                        foreach ($_SESSION["proceso_alta_masiva"]["inserts"] as $inserts) {
//                                imprimeConsola("-------------Insert-------------");
//                                imprimeConsola("campos: " . $inserts["campos"]);
//                                imprimeConsola("datos: " . $inserts["datos"]);
//                                imprimeConsola("campos esp alumno: " . $inserts["insertsEspecificos"]["campos"]);
//                                imprimeConsola("datos esp alumno: " . $inserts["insertsEspecificos"]["datos"]);
//
//                                imprimeConsola("campos padre: " . $inserts["insertsEspecificos"]["camposPadre"]);
//                                imprimeConsola("datos padre: " . $inserts["insertsEspecificos"]["datosPadre"]);
//                                imprimeConsola("campos especificos padre: " . $inserts["insertsEspecificos"]["camposEspecificosPadre"]);
//                                imprimeConsola("datos especificos padre: " . $inserts["insertsEspecificos"]["datosEspecificosPadre"]);
//
//                                imprimeConsola("camposProfesor: " . $inserts["insertsEspecificos"]["camposProfesor"]);
//                                imprimeConsola("datosProfesor: " . $inserts["insertsEspecificos"]["datosProfesor"]);
//                                imprimeConsola("campos especificos profesor: " . $inserts["insertsEspecificos"]["camposEspecificosProfesor"]);
//                                imprimeConsola("datos especificos profesor: " . $inserts["insertsEspecificos"]["datosEspecificosProfesor"]);
                            //Insertar Padre
                            try {
                                $id_datos_personales1 = insertarDatos("datos_personales", $inserts["insertsEspecificos"]["camposPadre"], $inserts["insertsEspecificos"]["datosPadre"]);
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["insertsEspecificos"]["datosPadre"]);
                            }
                            try {
                                insertarDatos("padres", $inserts["insertsEspecificos"]["camposEspecificosPadre"] . ", id_datos_personales", $inserts["insertsEspecificos"]["datosEspecificosPadre"] . ", $id_datos_personales1");
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["insertsEspecificos"]["datosEspecificosPadre"] . ", $id_datos_personales1");
                            }
                            try {
                                //Insertar Profesor
                                $id_datos_personales2 = insertarDatos("datos_personales", $inserts["insertsEspecificos"]["camposProfesor"], $inserts["insertsEspecificos"]["datosProfesor"]);
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["insertsEspecificos"]["datosProfesor"]);
                            }
                            try {
                                insertarDatos("profesores_aula", $inserts["insertsEspecificos"]["camposEspecificosProfesor"] . ", id_datos_personales", $inserts["insertsEspecificos"]["datosEspecificosProfesor"] . ", $id_datos_personales2");
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["insertsEspecificos"]["datosEspecificosProfesor"] . ", $id_datos_personales2");
                            }
                            //Insertar alumno
                            try {
                                $id_datos_personales3 = insertarDatos("datos_personales", $inserts["campos"], $inserts["datos"]);
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["datos"]);
                            }
                            try {
                                insertarDatos("alumnos", $inserts["insertsEspecificos"]["campos"] . ", id_datos_personales, puesto_ocupacion", $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales3, 'Estudiante'");
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales3");
                            }

                            //Enviar correo
                            correoUsuarioNuevo($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], "Alumno");
                            //Enviar correo
                            correoUsuarioNuevo($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["padre_correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["padre_nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["padre_contrasena"], "Padre de Familia");
                            //Enviar correo
                            correoUsuarioNuevo($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["profesor_correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["profesor_nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["profesor_contrasena"], "Profesor de Aula");

                            //Usuario en Moodle
                            if (!is_numeric($insertMoodle = insertaUsuarioMoodle($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_pila"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["primer_apellido"] . " " . $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["segundo_apellido"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], NULL, true))) {
                                array_push($errores, "Hubo un problema al insertar registro en moodle " . $inserts["datos"]);
                            } else {
                                //Preparando Asignacion de rol global
                                $usuariosMoodle["alumnos"].= "$insertMoodle,";
                            }


                            flush();
                            $i++;
                        }
                        $continuar = "verAlumnosEstudiantes.php";
                        break;
                    case "estudiantes":
                        $i = 0;
                        $rolMoodle = ROL_ALUMNO;
                        $roleid = get_assignable_roles(ROL_ALUMNO);
//                        include "../sources/iframeAseguraLogin.php";
                        foreach ($_SESSION["proceso_alta_masiva"]["inserts"] as $inserts) {
//                imprimeConsola("-------------Insert-------------");
//                imprimeConsola("campos: " . $inserts["campos"]);
//                imprimeConsola("datos: " . $inserts["datos"]);
//                imprimeConsola("campos esp alumno: " . $inserts["insertsEspecificos"]["campos"]);
//                imprimeConsola("datos esp alumno: " . $inserts["insertsEspecificos"]["datos"]);

                            verificaEliminacionUsuario($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"]);
                            try {
                                $id_datos_personales = insertarDatos("datos_personales", $inserts["campos"], $inserts["datos"]);
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["datos"]);
                            }
                            try {
                                insertarDatos("alumnos", $inserts["insertsEspecificos"]["campos"] . ", id_datos_personales, puesto_ocupacion", $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales, 'Estudiante'");
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales");
                            }

                            //Enviar correo
                            correoUsuarioNuevo($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], "Alumno");

                            //Usuario en Moodle
                            if (!is_numeric($insertMoodle = insertaUsuarioMoodle($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_pila"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["primer_apellido"] . " " . $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["segundo_apellido"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], NULL, true))) {
                                array_push($errores, "Hubo un problema al insertar registro en moodle " . $inserts["datos"]);
                            } else {
                                //Preparando Asignacion de rol global
                                $usuariosMoodle["alumnos"].= "$insertMoodle,";
                            }

                            flush();
                            $i++;
                        }
                        $continuar = "verAlumnosEstudiantes.php";
                        break;
                    case "profesionistas":
                        $i = 0;
                        $rolMoodle = ROL_ALUMNO;
                        $roleid = get_assignable_roles(ROL_ALUMNO);

                        include "../sources/iframeAseguraLogin.php";

                        foreach ($_SESSION["proceso_alta_masiva"]["inserts"] as $inserts) {
//                imprimeConsola("-------------Insert-------------");
//                imprimeConsola("campos: " . $inserts["campos"]);
//                imprimeConsola("datos: " . $inserts["datos"]);
//                imprimeConsola("campos esp prof: " . $inserts["insertsEspecificos"]["campos"]);
//                imprimeConsola("datos esp prof: " . $inserts["insertsEspecificos"]["datos"]);
                            verificaEliminacionUsuario($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"]);
                            try {
                                $id_datos_personales = insertarDatos("datos_personales", $inserts["campos"], $inserts["datos"]);
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["campos"], $inserts["datos"]);
                            }
                            try {
                                insertarDatos("alumnos", $inserts["insertsEspecificos"]["campos"] . ", id_datos_personales", $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales");
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales");
                            }
                            //Enviar correo
                            correoUsuarioNuevo($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], "Alumno");

                            //Usuario en Moodle
                            if (!is_numeric($insertMoodle = insertaUsuarioMoodle($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_pila"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["primer_apellido"] . " " . $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["segundo_apellido"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], NULL, true))) {
                                array_push($errores, "Hubo un problema al insertar registro en moodle " . $inserts["datos"]);
                            } else {
                                //Preparando Asignacion de rol global
                                $usuariosMoodle["alumnos"].= "$insertMoodle,";
                            }

                            flush();
                            $i++;
                        }
                        $continuar = "verAlumnosProfesionistas.php";
                        break;
                    case "tutores":
                        $i = 0;
                        $rolMoodle = "tutores";
                        $roleidJunior = get_assignable_roles(ROL_TUTOR_JUNIOR);
                        $roleidSenior = get_assignable_roles(ROL_TUTOR_SENIOR);
                        $roleidCoordinador = get_assignable_roles(ROL_COORDINADOR);

                        include "../sources/iframeAseguraLogin.php";

                        foreach ($_SESSION["proceso_alta_masiva"]["inserts"] as $inserts) {
//                imprimeConsola("-------------Insert-------------");
//                imprimeConsola("campos: " . $inserts["campos"]);
//                imprimeConsola("datos: " . $inserts["datos"]);
//                imprimeConsola("campos esp tutor: " . $inserts["insertsEspecificos"]["campos"]);
//                imprimeConsola("datos esp tutor: " . $inserts["insertsEspecificos"]["datos"]);
                            verificaEliminacionUsuario($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"]);
                            try {
                                $id_datos_personales = insertarDatos("datos_personales", $inserts["campos"], $inserts["datos"]);
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["campos"], $inserts["datos"]);
                            }
                            try {
                                insertarDatos("tutor", $inserts["insertsEspecificos"]["campos"] . ", id_datos_personales", $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales");
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales");
                            }
                            //Enviar correo
                            correoUsuarioNuevo($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], "Tutor");

                            //Usuario en Moodle
                            if (!is_numeric($insertMoodle = insertaUsuarioMoodle($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_pila"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["primer_apellido"] . " " . $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["segundo_apellido"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], NULL, true))) {
                                array_push($errores, "Hubo un problema al insertar registro en moodle " . $inserts["datos"]);
                            } else {
                                switch (strtolower($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"])) {
                                    case strtolower(ROL_TUTOR_JUNIOR):
                                        $usuariosMoodle["juniors"].="$insertMoodle,";
                                        break;
                                    case strtolower(ROL_TUTOR_SENIOR):
                                        $usuariosMoodle["seniors"].="$insertMoodle,";
                                        break;
                                    case strtolower(ROL_COORDINADOR):
                                        $usuariosMoodle["coordinadores"].="$insertMoodle,";
                                        break;
                                }
                            }

                            flush();
                            $i++;
                        }
                        $continuar = "verTutores.php";
                        break;
                    case "profesores":
                        $i = 0;
                        foreach ($_SESSION["proceso_alta_masiva"]["inserts"] as $inserts) {
//                imprimeConsola("-------------Insert-------------");
//                imprimeConsola("campos: " . $inserts["campos"]);
//                imprimeConsola("datos: " . $inserts["datos"]);
//                imprimeConsola("campos esp profesor: " . $inserts["insertsEspecificos"]["campos"]);
//                imprimeConsola("datos esp profesor: " . $inserts["insertsEspecificos"]["datos"]);
                            verificaEliminacionUsuario($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"]);
                            try {
                                $id_datos_personales = insertarDatos("datos_personales", $inserts["campos"], $inserts["datos"]);
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["campos"], $inserts["datos"]);
                            }
                            try {
                                insertarDatos("profesores_aula", $inserts["insertsEspecificos"]["campos"] . ", id_datos_personales", $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales");
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales");
                            }
                            //Enviar correo
                            correoUsuarioNuevo($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], "Profesor de Aula");

                            flush();
                            $i++;
                        }
                        $continuar = "verProfesores.php";
                        break;
                    case "padres":
                        $i = 0;
                        foreach ($_SESSION["proceso_alta_masiva"]["inserts"] as $inserts) {
//                imprimeConsola("-------------Insert-------------");
//                imprimeConsola("campos: " . $inserts["campos"]);
//                imprimeConsola("datos: " . $inserts["datos"]);
//                imprimeConsola("campos esp padre: " . $inserts["insertsEspecificos"]["campos"]);
//                imprimeConsola("datos esp padre: " . $inserts["insertsEspecificos"]["datos"]);
                            verificaEliminacionUsuario($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"]);
                            try {
                                $id_datos_personales = insertarDatos("datos_personales", $inserts["campos"], $inserts["datos"]);
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["campos"], $inserts["datos"]);
                            }
                            try {
                                insertarDatos("padres", $inserts["insertsEspecificos"]["campos"] . ", id_datos_personales", $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales");
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales");
                            }
                            //Enviar correo
                            correoUsuarioNuevo($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], "Padre de Familia");

                            flush();
                            $i++;
                        }
                        $continuar = "verPadres.php";
                        break;
                    case "gestores":
                        $continuar = "verGestores.php";
                        $i = 0;
                        $rolMoodle = ROL_GESTOR;
                        $roleid = get_assignable_roles(ROL_GESTOR);
                        include "../sources/iframeAseguraLogin.php";
                        $totalFrames = count($_SESSION["proceso_alta_masiva"]["inserts"]);
                        $contN = 1;
                        foreach ($_SESSION["proceso_alta_masiva"]["inserts"] as $inserts) {
//                imprimeConsola("-------------Insert-------------");
//                imprimeConsola("campos: " . $inserts["campos"]);
//                imprimeConsola("datos: " . $inserts["datos"]);
//                imprimeConsola("campos esp gestor: " . $inserts["insertsEspecificos"]["campos"]);
//                imprimeConsola("datos esp gestor: " . $inserts["insertsEspecificos"]["datos"]);
                            verificaEliminacionUsuario($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"]);
                            try {
                                $id_datos_personales = insertarDatos("datos_personales", $inserts["campos"], $inserts["datos"]);
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["campos"], $inserts["datos"]);
                            }
                            try {
                                insertarDatos("gestor_contenido", $inserts["insertsEspecificos"]["campos"] . ", id_datos_personales", $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales");
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["insertsEspecificos"]["datos"] . ", $id_datos_personales");
                            }
                            //Enviar correo
                            correoUsuarioNuevo($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], "Gestor de Contenido");

                            //Usuario en Moodle
                            if (!is_numeric($insertMoodle = insertaUsuarioMoodle($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_pila"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["primer_apellido"] . " " . $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["segundo_apellido"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], NULL, true))) {
                                array_push($errores, "Hubo un problema al insertar registro en moodle " . $inserts["datos"]);
                            } else {
                                $usuariosMoodle["gestores"].="$insertMoodle,";
//Inicia modificaiones by Omar
//                                $userid = get_moodle_user($_POST["datos_personales/correo"]);
                                $userid = get_moodle_user($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"]);
//                                $userid = $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"];
                                $idRol = get_assignable_roles($rolMoodle);

                                echo <<<iframe
                                    <iframe id="frameMoodle2" width="1px" height="1px" src="asignarRolesGlobalesMoodle.php?roleid=$idRol&userid=$userid" >ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ADMINISTRAR ROLES EN MOODLE</iframe>
                                            
iframe;
                                echo <<<iframe
                                    <iframe  id="frameUser" width="1px" height="1px" src="" width="1" height="1">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ENROLAR LOS USUARIOS AL CURSO EN MOODLE</iframe>
                                    <script type="text/javascript">  
                                        altaGestores = 1;
                                        $("#iframeMoodle").on('load', function(){
                                            debugConsole("Termino el login");
                                            $("#frameUser").attr("src", "asignarRolesGlobalesMoodle.php?roleid=$idRol&userid=$userid");
                                        });                        
                                    </script>
                           
iframe;
                                $load = "";
                                if($contN === $totalFrames){
                                    $load = <<<html
                                           $("#frameUser").load(function(){
                                              $(".smCargando").hide("slow");
                                              debugConsole("Redireccionando");
                                                alert("Inserción Exitosa.");
                                                window.location.href ='$continuar';
                                              $("#listo").show("fast");  
                                        }); 
                                            
html;
                                }
                                $cargando = "";
                                if($contN === 1){
                                    $cargando = '<div class="smCargando">
                                        <div class="alert alert-success"><h4><img src="../img/loading.gif" style="width: 4%;"/>&nbsp;&nbsp;&nbsp;Estamos vinculando con moodle, espere por favor.</h4></div>
                                     </div>';
                                }else{
                                    $cargando = "";
                                }
                                echo <<<iframe
                                    $cargando
                                     <script type="text/javascript">
                                        
                                        $load;
                                     </script>
iframe;
//Terminan modificaciones by omar
                            }

                            flush();
                            $i++;
                            $contN ++;
                        }
                        
                        break;
                    case "admin":
                        $i = 0;
                        $rolMoodle = ROL_ADMIN;
                        include "../sources/iframeAseguraLogin.php";
                        foreach ($_SESSION["proceso_alta_masiva"]["inserts"] as $inserts) {
//                imprimeConsola("-------------Insert-------------");
//                imprimeConsola("campos: " . $inserts["campos"]);
//                imprimeConsola("datos: " . $inserts["datos"]);
                            verificaEliminacionUsuario($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"]);
                            try {
                                $id_datos_personales = insertarDatos("datos_personales", $inserts["campos"], $inserts["datos"]);
                            } catch (Exception $e) {
                                array_push($errores, "Hubo un problema al insertar registro " . $inserts["campos"], $inserts["datos"]);
                            }
                            //Enviar correo
                            correoUsuarioNuevo($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], "Administrador");

                            //Usuario en Moodle
                            if (!is_numeric($insertMoodle = insertaUsuarioMoodle($_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_usuario"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["contrasena"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["nombre_pila"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["primer_apellido"] . " " . $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["segundo_apellido"], $_SESSION["proceso_alta_masiva"]["filtroRegistros"][$i]["correo"], NULL, true))) {
                                array_push($errores, "Hubo un problema al insertar registro en moodle " . $inserts["datos"]);
                            } else {
                                $usuariosMoodle["administradores"].="$insertMoodle,";
                            }
                            flush();
                            $i++;
                        }
                        $continuar = "verAdministradores.php";
                        break;
                }
                unset($_SESSION["proceso_alta_masiva"]);
                unset($_SESSION["tipo_carga"]);

                //Asignar roles en Moodle
                if (isset($rolMoodle)) {
                    if ($usuariosMoodle["alumnos"] != "") {
                        $userid = $usuariosMoodle["alumnos"];
                        echo <<<iframe
                        <iframe id="frameMoodleMasiva" name="$continuar" src="asignarRolesGlobalesMoodle.php?roleid=$roleid&userid=$userid" style="display:none;">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ADMINISTRAR ROLES EN MOODLE</iframe>
iframe;
                    }
                    if ($usuariosMoodle["juniors"] != "") {
                        $roleid = $roleidJunior;
                        $userid = $usuariosMoodle["juniors"];
                        echo <<<iframe
                        <iframe id="frameMoodleMasiva" name="$continuar" src="asignarRolesGlobalesMoodle.php?roleid=$idRol&userid=$userid" style="display:none;">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ADMINISTRAR ROLES EN MOODLE</iframe>
iframe;
                    }
                    if ($usuariosMoodle["seniors"] != "") {
                        $roleid = $roleidSenior;
                        $userid = $usuariosMoodle["seniors"];
                        echo <<<iframe
                        <iframe id="frameMoodleMasiva" name="$continuar" src="asignarRolesGlobalesMoodle.php?roleid=$idRol&userid=$userid" style="display:none;">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ADMINISTRAR ROLES EN MOODLE</iframe>
iframe;
                    }
                    if ($usuariosMoodle["coordinadores"]) {
                        $roleid = $roleidCoordinador;
                        $userid = $usuariosMoodle["coordinadores"];
                        echo <<<iframe
                        <iframe id="frameMoodleMasiva" name="$continuar" src="asignarRolesGlobalesMoodle.php?roleid=$idRol&userid=$userid" style="display:none;">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ADMINISTRAR ROLES EN MOODLE</iframe>
iframe;
                    }
                    if ($usuariosMoodle["gestores"]) {
                        $userid = $usuariosMoodle["gestores"];
                        echo <<<iframe
                        <iframe id="frameMoodleMasiva" name="$continuar" src="asignarRolesGlobalesMoodle.php?roleid=$roleid&userid=$userid" style="display:none;">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ADMINISTRAR ROLES EN MOODLE</iframe>
iframe;
                    }
                    if ($usuariosMoodle["administradores"]) {
                        echo <<<iframe
                        <iframe id="frameMoodleMasiva" name="$continuar" src="asignarAdministradorMoodle.php?userid=$userid" style="display:none;">ERROR. SU NAVEGADOR NO SOPORTA IFRAMES. NO SE PUDO ADMINISTRAR ROLES EN MOODLE</iframe>
iframe;
                    }
                }

                flush();
                if (count($errores) == 0) {
                    //Si no inserta en moodle se hace el ciclo normal
                    unset($rolMoodle);
                    if (!isset($rolMoodle)) {
                        imprimeOk("Insercion Completa. Redireccionando");
                        echo <<<script
                            <script>
                                if(altaGestores === 0){
                                    debugConsole("Redireccionando");
                                    alert("Inserción Exitosa.");
                                    window.location.href ='$continuar';
                                }
                                
                            </script>
script;
                    } else {
                        echo <<<script
                        <div id="smCargando">
                            <div class="alert alert-success"><h4><img src="../img/loading.gif" style="width: 4%;"/>&nbsp;&nbsp;&nbsp;Estamos vinculando con moodle, espere por favor.</h4></div>
                        </div>
script;
                    }
                } else {
                    imprimeError("Hay errores");
                    foreach ($errores as $error) {
                        imprimeError($error);
                        flush();
                    }
                }
                flush();
                ?>

            </div> <!-- /container -->

            <!-- Le javascript
            ================================================== -->
            <!-- Placed at the end of the document so the pages load faster -->


        </body>
    </html>
    <?php
} else {
    header("Location:altaMasiva.php");
}
?>

