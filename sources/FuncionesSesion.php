<?php
//Control de cambios #&
//Autor:Omar Nava
//Objetivo: ALta cursos
//03-ene-2014

//Control de cambios #7
//Autor:Omar Nava
//Objetivo: Obtener id de datos personales
//03-ene-2014
/**
 * Funcion que hace todo lo necesario para que la sesión sea puesta en marcha, es alcanzable por los demás módulos y recibe el usuario/email y la contraseña
 * @param type $usuarioEmail
 * @param type $pass
 */
function construyeSesion($usuarioEmail, $pass) {
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql = "
        select id_datos_personales as \"idDatosPersonales\", 
               nombre_pila||' '||primer_apellido as nombre,
	tipo_usuario as \"tipoUsuario\",
        contrasena,
        nombre_usuario as user,
        EXTRACT(DAY FROM age(timestamp 'now()',(fecha_bloqueo) )) as \"diasBloqueado\",
	 CASE WHEN status_login IS NULL THEN 'desbloqueado'
              WHEN status_login IS NOT NULL THEN 'bloqueado'
              ELSE 'other'
        END as \"statusLogin\",
         CASE WHEN intentos IS NULL THEN 'contadorOFF'
               WHEN intentos IS NOT NULL THEN 'contadorON'
               ELSE 'other'
        END as \"statusIntentoLogin\",
         CASE  WHEN  extract(min from now() - fecha_bloqueo) <= 10 THEN 'SI'
               ELSE 'NO'
        END as \"intervaloAceptado15MIN\",
        intentos
        from datos_personales
        where nombre_usuario like '" . $usuarioEmail . "' or correo like '" . $usuarioEmail . "'
    ";

    $listaUsuarios = $sql->select("obj");

    if (empty($listaUsuarios)) {
        //No Existen usuarios
        redireccionarLoginFallido();
    } else {
        foreach ($listaUsuarios as $usuario) {
            $contrasenaDesencriptada = nDCrypt($usuario->contrasena);
            if ($contrasenaDesencriptada == $pass) {//Contraseña Correcta
                if ($usuario->statusLogin == 'bloqueado') {
                    if (intval($usuario->diasBloqueado) >= 1) {//Mayor a 24HRS Se desbloquea el Usuario
                        //Actualiza STATUS
                        desbloquearUsuario($usuario->idDatosPersonales);
                        //INICIA SESION
                        verificarIntegridadSG_Moodle($usuarioEmail, $pass);
                        construyeArregloSesion($usuario, $usuario->user, $pass);
                    } else {//Mostrar Mensaje de Bloqueado Usuario
                        redireccionaBloqueoUsuario();
                        break;
                    }
                } else {//Desbloqueado
//                    
                    if ($usuario->statusIntentoLogin === 'contadorON')
                        desbloquearUsuario($usuario->idDatosPersonales);

                    //Verificar la integridad de los datos

                    verificarIntegridadSG_Moodle($usuarioEmail, $pass);
                    construyeArregloSesion($usuario, $usuario->user, $pass);
                }
                break;
            }
            else {//Existe Usuario pero no empata password
                if ($usuario->statusLogin == 'bloqueado') {
                    if (intval($usuario->diasBloqueado) >= '1') {
                        desbloquearUsuario($usuario->idDatosPersonales);
                    }
                    redireccionaBloqueoUsuario();
                    break;
                }

                if ($usuario->statusIntentoLogin == 'contadorON') {
                    if ($usuario->intervaloAceptado15MIN == 'SI') {
                        aumentaContador($usuario->idDatosPersonales);

                        if (intval($usuario->intentos) >= 4) {
                            bloquearUsuario24HRS($usuario->idDatosPersonales);
                            redireccionaBloqueoUsuario();
                            break;
                        }
                    } else {
                        desbloquearUsuario($usuario->idDatosPersonales);
                        iniciarConteoIntentos($usuario->idDatosPersonales);
                    }
                } else {
                    iniciarConteoIntentos($usuario->idDatosPersonales);
                }

                redireccionarLoginFallido();
                break;
            }
        }
    }
}

/**
 * Desbloquea Usuario, Colocando null en tres campos
 * de la tabla de datos_personales 
 * @param type $id_datos_personales
 */
function desbloquearUsuario($id_datos_personales) {
    if (!is_numeric($id_datos_personales))
        return false;

    $sql = new Query("SG");
    $sql->update("UPDATE  datos_personales SET fecha_bloqueo = NULL, 
                       status_login = null,
                       intentos = NULL 
                       WHERE id_datos_personales=" . $id_datos_personales);
    return true;
}

/**
 * Desbloquea Usuario, Colocando null en tres campos
 * de la tabla de datos_personales 
 * @param type $id_datos_personales
 */
function iniciarConteoIntentos($id_datos_personales) {
    if (!is_numeric($id_datos_personales))
        return false;

    $sql = new Query("SG");
    $sql->update("UPDATE  datos_personales SET fecha_bloqueo = now(),
                          intentos = 1 
                          WHERE id_datos_personales=" . $id_datos_personales);
    return true;
}

/**
 * Bloquea Usuario 24HRS colocando status_login = 1
 * en la tabla de datos_personales 
 * @param type $id_datos_personales
 */
function bloquearUsuario24HRS($id_datos_personales) {
    if (!is_numeric($id_datos_personales))
        return false;

    $sql = new Query("SG");
    $sql->update("UPDATE datos_personales SET status_login = 1 
                  WHERE id_datos_personales=" . $id_datos_personales);
    return true;
}

/**
 * Aumenta el contador de intentos fallidos en el campo intentos 
 * de la tabla de datos_personales 
 * @param type $id_datos_personales
 */
function aumentaContador($id_datos_personales) {
    if (!is_numeric($id_datos_personales))
        return false;

    $sql = new Query("SG");
    $sql->update("UPDATE datos_personales SET intentos=intentos+1 
                  WHERE id_datos_personales=" . $id_datos_personales);
    return true;
}

/**
 * Función que es llamada por la de construyeSesion, no se recomienda ser usada manualmente a menos que sea necesario recibe el objeto usuario 
 * @param type $usuario
 */
function construyeArregloSesion($usuario, $usuarioMail, $contrasena) {


    $_SESSION["userMail"] = $usuarioMail;
    $_SESSION["pass"] = $contrasena;


//    echo '<script> alert("aaa'.$usuario->nombre.'");</script>';
    require_once 'Query.php';
    $sql = new Query("SG");
    switch ($usuario->tipoUsuario) {
        case 0://es un alumno
            $sql->sql = "
                select id_alumno  as \"idAlumno\"
                from alumnos
                where status = 1 AND id_datos_personales = " . $usuario->idDatosPersonales . "
            ";
            $listaUsuarios = $sql->select("obj");
            if (empty($listaUsuarios)) {
                redireccionarLoginFallido();
                exit();
            } else {
                foreach ($listaUsuarios as $usuario2) {
                    $idPorTabla = $usuario2->idAlumno;
                }
            }
            llenaArregloSesion($usuario->tipoUsuario, $usuario->nombre, $usuario->idDatosPersonales, $idPorTabla, date("H:i:s"));
            break;
        case 1://es un tutor
            $sql->sql = "
                select id_tutor  as \"idTutor\"
                from tutor
                where status = 1 and id_datos_personales = " . $usuario->idDatosPersonales . "
            ";
            $listaUsuarios = $sql->select("obj");
            if (empty($listaUsuarios)) {
                redireccionarLoginFallido();
                exit();
            } else {
                foreach ($listaUsuarios as $usuario2) {
                    $idPorTabla = $usuario2->idTutor;
                }
            }
            llenaArregloSesion($usuario->tipoUsuario, $usuario->nombre, $usuario->idDatosPersonales, $idPorTabla, date("H:i:s"));

            break;
        case 2://es un profesor de aula
            redireccionaNoPermisos();//No tiene permisos, lo redireccionamos
//            $sql->sql = "
//                select id_profesor_aula  as \"idProfesorAula\"
//                from profesores_aula
//                where status = 1 and id_datos_personales = " . $usuario->idDatosPersonales . "
//            ";
//            $listaUsuarios = $sql->select("obj");
//            if (empty($listaUsuarios)) {
//                redireccionarLoginFallido();
//                exit();
//            } else {
//                foreach ($listaUsuarios as $usuario2) {
//                    $idPorTabla = $usuario2->idProfesorAula;
//                }
//            }
////            var_dump($idPorTabla);
////            exit();
//            llenaArregloSesion($usuario->tipoUsuario, $usuario->nombre, $usuario->idDatosPersonales, $idPorTabla, date("H:i:s"));
            break;
        case 3://es un padre
            $sql->sql = "
                select id_padre  as \"idPadre\"
                from padres
                where status = 1 and id_datos_personales = " . $usuario->idDatosPersonales . "
            ";
            $listaUsuarios = $sql->select("obj");
            if (empty($listaUsuarios)) {
                redireccionarLoginFallido();
                exit();
            } else {
                foreach ($listaUsuarios as $usuario2) {
                    $idPorTabla = $usuario2->idPadre;
                }
            }
            llenaArregloSesion($usuario->tipoUsuario, $usuario->nombre, $usuario->idDatosPersonales, $idPorTabla, date("H:i:s"));
            break;
        case 4://es un gestor de contenidos
            $sql->sql = "
                select id_gestor_contenido  as \"idGestorContenido\"
                from gestor_contenido
                where status = 1 and id_datos_personales = " . $usuario->idDatosPersonales . "
            ";
            $listaUsuarios = $sql->select("obj");
            if (empty($listaUsuarios)) {
                redireccionarLoginFallido();
                exit();
            } else {
                foreach ($listaUsuarios as $usuario2) {
                    $idPorTabla = $usuario2->idGestorContenido;
                }
            }
            llenaArregloSesion($usuario->tipoUsuario, $usuario->nombre, $usuario->idDatosPersonales, $idPorTabla, date("H:i:s"));
            break;
        case 5://es administrador
            
            @llenaArregloSesion($usuario->tipoUsuario, $usuario->nombre, $usuario->idDatosPersonales, $usuario->idDatosPersonales, date("H:i:s"));
            break;
    }
}

/**
 * Funcion que es usada de manera automática por el método de construyeArregloSesion no es recomendale usarla manualmente a menos que sea necesario
 * @param type $tipo
 * @param type $nombre
 * @param type $idDatosPersonales
 * @param type $idPorTabla
 * @param type $horaActiva
 */
function llenaArregloSesion($tipo, $nombre, $idDatosPersonales, $idPorTabla, $horaActiva) {

    $_SESSION["nombre"] = $nombre;
    $_SESSION["idDatosPersonales"] = $idDatosPersonales;
    $_SESSION["idPorTabla"] = $idPorTabla;
    $_SESSION["horaActiva"] = $horaActiva;

    registraEntradaSitio($idDatosPersonales);
    
    //Inicia control de cambios #3
    if ($_SESSION["reco"] == 1) {
        setcookie("smNombre", $_SESSION["nombre"], time() + (60 * 60 * 24 * 30), "/");
        $_SESSION['aa'] = $_COOKIE["smNombre"];
        setcookie("smIdDatosPersonales", $_SESSION["idDatosPersonales"], time() + (60 * 60 * 24 * 30), "/");
        setcookie("smIdPorTabla", $_SESSION["idPorTabla"], time() + (60 * 60 * 24 * 30), "/");
//    setcookie("smHoraActiva",$_SESSION["horaActiva"]);
    }

    //Finaliza control de cambios #3
    $path = "../";
    switch ($tipo) {
        case 0; //es alumno
            $_SESSION["tipo"] = "alumno";
            //Inicia control de cambios #3
            if ($_SESSION["reco"] == 1) {
                setcookie("smTipo", $_SESSION["tipo"], time() + (60 * 60 * 24 * 30), "/");
            }
            locationConCookie("frontweb/", "alumno/index.php", $path);
            //Finaliza control de cambios #3

            break;
        case 1: //es un tutor
            $listaTutor = selectTutor($idPorTabla, null);
            foreach ($listaTutor as $tutor) {
                $_SESSION["tipo"] = $tutor->rol;
                //Inicia control de cambios #3
                if ($_SESSION["reco"] == 1) {
                    setcookie("smTipo", $_SESSION["tipo"], time() + (60 * 60 * 24 * 30), "/");
                }
                //Finaliza control de cambios #3
            }
            //Registrar acceso de hora inicio del tutor
            iniciar_accesoTutor($_SESSION["idPorTabla"]);

            switch ($_SESSION["tipo"]) {
                case 'Junior':
                    if (isset($_COOKIE["smTipo"])) {
//                        header('Location:' . $path . '../jr/index.php');
                        header('Location:' . $path . '../frontweb/tutor/index.php');
                    } else {
//                        header('Location:' . $path . 'jr/index.php');
                        header('Location:' . $path . 'frontweb/tutor/index.php');
                    }
                    break;
                case 'Senior':
                    if (isset($_COOKIE["smTipo"])) {
//                        header('Location:' . $path . '../senior/index.php');
                        header('Location:' . $path . '../frontweb/tutor/index.php');
                    } else {
//                        header('Location:' . $path . 'senior/index.php');
                        header('Location:' . $path . 'frontweb/tutor/index.php');
                    }
                    break;
                case 'Coordinador':
                    if (isset($_COOKIE["smTipo"])) {
//                        header('Location:' . $path . '../coordinador/index.php');
                        header('Location:' . $path . '../frontweb/tutor/index.php');
                    } else {
//                        header('Location:' . $path . 'coordinador/index.php');
                        header('Location:' . $path . 'frontweb/tutor/index.php');
                    }
                    break;
            }
            break;
        case 2:
            $_SESSION["tipo"] = "profesorAula";
            //Inicia control de cambios #3
            if ($_SESSION["reco"] == 1) {
                setcookie("smTipo", $_SESSION["tipo"], time() + (60 * 60 * 24 * 30), "/");
            }
            //Finaliza control de cambios #3
            break;
        case 3:
            $_SESSION["tipo"] = "padre";
            //Inicia control de cambios #3
            if ($_SESSION["reco"] == 1) {
                setcookie("smTipo", $_SESSION["tipo"], time() + (60 * 60 * 24 * 30), "/");
            }
            if (isset($_COOKIE["smTipo"])) {
//                header('Location:' . $path . '../padre/index.php');
                header('Location:' . $path . '../frontweb/padre/index.php');
            } else {
//                header('Location:' . $path . 'padre/index.php');
                header('Location:' . $path . 'frontweb/padre/index.php');
            }
            //Finaliza control de cambios #3
            break;
        case 4:
            $_SESSION["tipo"] = "gestorContenidos";
            //Inicia control de cambios #3
            if ($_SESSION["reco"] == 1) {
                setcookie("smTipo", $_SESSION["tipo"], time() + (60 * 60 * 24 * 30), "/");
            }
            if (isset($_COOKIE["smTipo"])) {
                header('Location:' . $path . '../gestor/index.php');
            } else {
                header('Location:' . $path . 'gestor/index.php');
            }
            //Finaliza control de cambios #3
            break;
        case 5:
            $_SESSION["tipo"] = "administrador";
            //Inicia control de cambios #3
            if ($_SESSION["reco"] == 1) {
                setcookie("smTipo", $_SESSION["tipo"], time() + (60 * 60 * 24 * 30), "/");
            }
            //Finaliza control de cambios #3
            if (isset($_COOKIE["smTipo"])) {
                header('Location:' . $path . '../admin/index.php');
            } else {
                header('Location:' . $path . 'admin/index.php');
            }

            break;
    }
}

/**
 * Funcion que es usada de manera automática por el método de llenaArregloSesion no es recomendale usarla manualmente a menos que sea necesario
 * @param type $idPorTabla
 * @see El parametro idPorTabla es el ID de la tabla Tutor 
 */
function iniciar_accesoTutor($idPorTabla) {

    $tabla = "log_acceso_tutor";
    $valoresInsert = 'now(),' . $idPorTabla;

    $insertLogTutor = new Query("SG");
    $insertLogTutor->insert("log_acceso_tutor", "fecha_entrada,id_tutor", $valoresInsert);

    $ultimoID = $insertLogTutor->ultimoID($tabla);

    if ($ultimoID)
        $_SESSION["idTutorSesion"] = $ultimoID;
}

/**
 * Funcion que registra la hora salida del Tutor
 * @param type $idPorTabla
 * @see El parametro idPorTabla es el ID de la tabla Tutor 
 */
function detener_accesoTutor() {
    if (isset($_SESSION["idTutorSesion"])) {
        //Actualizar la sesion del Tutor
        $SQL = 'UPDATE log_acceso_tutor SET fecha_salida=now() 
              WHERE id_acceso_tutor = ' . $_SESSION['idTutorSesion'];
        $updateTutor = new Query("SG");
        $updateTutor->update($SQL);
    }
}

/**
 * Funcion que devuelve el ID de la Tabla de la Sesion
 * @return ID de la Tabla del usuario 
 */
function obtenerIDTabla() {
    return $_SESSION["idPorTabla"];
}
//inicia control de cambios #7
/**
 * Funcion que devuelve el tipo de usuario que ha entrado al sistema
 * ya sea Senior, Junior, etc
 * @return Rol del Usuario 
 */
function obtenerIdDatosPersonales() {
    return $_SESSION["idDatosPersonales"];
}
//termina control de cambios #7
/**
 * Funcion que devuelve el idDatosPersonales de usuario que ha entrado al sistema
 * ya sea Senior, Junior, etc
 * @return Rol del Usuario 
 */
function obtenerTipo() {
    return $_SESSION["tipo"];
}

////Métodos que validan tipo de sesion
/**
 * Devuelve true si está activa la sesión como alumno
 * @return boolean
 */
function esAlumno() {
    if (isset($_SESSION["tipo"]))
        if ($_SESSION["tipo"] == 'alumno')
            return true;
        else
            return false;
}

/**
 * Devuelve true si está activa la sesión como tutor Junior
 * @return boolean
 */
function esJr() {
    if (isset($_SESSION["tipo"]))
        if ($_SESSION["tipo"] == "Junior")
            return true;
        else
            return false;
}

/**
 * Devuelve true si está activa la sesión como tutor Senior
 * @return boolean
 */
function esSenior() {
    if (isset($_SESSION["tipo"]))
        if ($_SESSION["tipo"] == "Senior")
            return true;
        else
            return false;
}

/**
 * Devuelve true si está activa la sesión como Coordinador de tutores
 * @return boolean
 */
function esCoordinador() {
    if (isset($_SESSION["tipo"]))
        if ($_SESSION["tipo"] == "Coordinador")
            return true;
        else
            return false;
}

/**
 * Devuelve true si está activa la sesión como Profesor de aula
 * @return boolean
 */
function esProfesorAula() {
    if (isset($_SESSION["tipo"]))
        if ($_SESSION["tipo"] == "profesorAula")
            return true;
        else
            return false;
}

/**
 * Devuelve true si está activa la sesión como Padre
 * @return boolean
 */
function esPadre() {
    if (isset($_SESSION["tipo"]))
        if ($_SESSION["tipo"] == "padre")
            return true;
        else
            return false;
}

/**
 * Devuelve true si está activa la sesión como Gestor de Contenido
 * @return boolean
 */
function esGestorContenido() {
    if (isset($_SESSION["tipo"]))
        if ($_SESSION["tipo"] == "gestorContenidos")
            return true;
        else
            return false;
}

/**
 * Devuelve true si está activa la sesión como Administrador
 * @return boolean
 */
function esAdministrador() {
    if (isset($_SESSION["tipo"]))
        if ($_SESSION["tipo"] == "administrador")
            return true;
        else
            return false;
}

/**
 * Redirecciona al index indicado que el usuario y el password son incorrectos
 * No bloquea usuario, porque no existe
 */
function redireccionarLoginFallido() {
    header('Location:../frontweb/login/index.php?msg=errorLogin');
}

/**
 * Redirecciona al index indicado que el usuario esta bloqueado
 */
function redireccionaBloqueoUsuario() {
    header('Location:../frontweb/login/index.php?msg=block');
}

/**
 * Redirecciona al index indicado que el campo usuario falta
 */
function redireccionaUsuarioFalta() {
    header('Location:../frontweb/login/index.php?msg=userNone');
}

/**
 * Redirecciona al index indicado que el campo password falta
 */
function redireccionaPasswordFalta() {
    header('Location:../frontweb/login/index.php?msg=passNone');
}

/**
 * Redirecciona al contacto.php indicado que el campo nombre falta
 */
function redireccionaNombreFalta() {
    header('Location:../frontweb/login/contacto.php?msg=nombreNone');
}

/**
 * Redirecciona al contacto.php indicado que el campo correo falta
 */
function redireccionaCorreoFalta() {
    header('Location:../frontweb/login/contacto.php?msg=correoNone');
}

/**
 * Redirecciona al contacto.php indicado que el campo correo falta
 */
function redireccionaCorreoIncorrecto() {
    header('Location:../frontweb/login/contacto.php?msg=correoError');
}

/**
 * Redirecciona al contacto.php indicado que el campo mensaje falta
 */
function redireccionaMensajeFalta() {
    header('Location:../frontweb/login/contacto.php?msg=mensajeNone');
}

/**
 * Redirecciona al index indicado que el campo password falta
 */
function redireccionaNoUsuarioSGMoodle() {
    header('Location:../frontweb/login/index.php?msg=userNOTMoodle');
    exit();
}
/**
 * Redirecciona recuperar_login.php indicando que el correo no es valido
 */
function redireccionaNoCorreoValidoRecuperaLogin() {
    header('Location:../frontweb/login/recuperar_login.php?msg=correoError');
    exit();
}
/**
 * Redirecciona a index indicando que no tiene permisos
 */
function redireccionaNoPermisos() {
    header('Location:../frontweb/login/index.php?msg=noPermission');
    exit();
}

/**
 * Muestra el mensaje indicado por
 * @param String $msg tipo de mensajes
 */
function mensaje($msg) {
    //Tipo de Mensaje
    switch ($msg) {
        case 'errorLogin' :
            echo 'El usuario o la contraseña son incorrectos.';
            break;
        case 'userNotFound';
            echo 'No se encontro el usuario asociado a este correo';
            break;
        case 'userFound';
            echo 'La contraseña se ha enviado a este correo';
            break;
        case 'block':
            echo 'Tu cuenta ha sido bloqueada durante las siguientes 24 horas. Si deseas desbloquearla antes de éste lapso comunícate con el administrador al correo uncorreo@unservidor.com';
            break;
        case 'userNone':
            echo "Ingresa tu usuario o correo";
            break;
        case 'passNone':
            echo "Ingresa tu contraseña";
            break;
        case 'userNOTMoodle':
            echo "No te encuentras vinculado a Moodle";
            break;
        case 'accessDenied':
            echo "No tiene permiso para acceder a esta sección. Inicie Sesión.";
            break;
        case 'nombreNone':
            echo "Ingresa tu Nombre";
            break;
        case 'correoNone':
            echo "Ingresa tu Correo";
            break;
        case 'mensajeNone':
            echo "Ingresa tu Mensaje";
            break;
        case 'mensajeEnviado':
            echo "Gracias por tu comentario.";
            break;
        case 'correoError':
            echo "El formato de correo es incorrecto";
        case 'noPermission':
            echo "No tienes permisos para ingresar al sistema";
    }
}

/**
 * Revisa en Tabla Datos Personales la existencia del correo
 * @param String $correo correo del usuario
 * @return array $resultadoConsulta
 *
 */
function verificarExistenciaUsuario($correo) {
    $sql = new Query("SG");
    $sql->sql = "SELECT correo,
                        contrasena
                 FROM datos_personales
                 WHERE correo LIKE '" . $correo . "'
                 LIMIT 1";
    return $sql->select("arr");
}

/**
 * Enviar contraseña de usuario a correo
 * @param String $correo correo del usuario
 * @see  En caso de que el usuario No exista manda mensaje redirecciona al index 
 * indicando que el usuario no existe
 */
function enviarContrasena($correo) {
    $usuarioPassword = verificarExistenciaUsuario($correo);
    if ($usuarioPassword) {
        enviarCorreoPassword($usuarioPassword[0]['correo'], nDCrypt($usuarioPassword[0]['contrasena']));
        header('Location:../frontweb/login/recuperar_login.php?msg=userFound');
    } else {
        header('Location:../frontweb/login/recuperar_login.php?msg=userNotFound');
    }
}

/**
 * Enviar contraseña a correo Especificado
 * @param String $correo correo del usuario
 *        String $contrasena del usuario
 */
function enviarCorreoPassword($correo, $contrasena) {
    
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "X-Priority: 3\r\n";
    $headers .= "X-Mailer: PHP". phpversion() ."\r\n" ;
    $headers .= "Reply-To: " . CUENTA_CORREO . "\r\n";
    $headers .= "From: MetaSpace";
    
    $mensaje = <<<correo
    <html>
    <head>
    <title>Bienvenido a MetaSpace</title>
    </head>
    <body>   
    <h1>MetaSpace</h1>
    <p>Metaspace te hace envio de tu contraseña</p>
    <ul>
        <li><b>Correo:</b> $correo</li>
        <li><b>Contrase&ntilde;a:</b> $contrasena</li>
    </ul>
            
    <p>Recuerda que para acceder puedes usar tu correo o nombre de usuario</p>
    
    Atentamente <br/>
    <b>MetaSpace.</b>
    </body>
    </html>
correo;
    $titulo = 'Recuperación de Contraseña';


    $res = mail($correo, $titulo, $mensaje, $headers);
    if ($res)
        return true;
    else
        return false;
}

function actualizaPasswordMoodle($id, $contrasenaBCrypt) {
    $sql = new Query("MOD");
    $sql->update("UPDATE mdl_user SET password='$contrasenaBCrypt' where id=" . $id);
    return true;
}

/**
 * @param  $usuario Usuario de SG
 *         $correo  Correo de SG
 *         $contrasena Contraseña Plana que el usuario escribe al entrar al SG
 * @return 1 El Usuario,correo y contrasena de SG son iguales en Moodle
 *         2 El Usuario y correo de SG no existen en Moodle
 *         3 El Usuario y correo de SG son iguales en Moodle, pero la contrasena no. 
 *          [Se actualiza para que coincidan en abmbos sistemas ]
 */
function verificaExistenciaUsuarioMoodle($usuarioCorreo, $contrasena) {
    //Verificar si en Moodle la esistencia de correos es unico
    $sql = new Query("MOD");
    $sql->sql = "SELECT id,
                        password
                 FROM mdl_user
                 WHERE username like '" . $usuarioCorreo . "' or email like '" . $usuarioCorreo . "'
                 AND deleted = 0 LIMIT 1";
    $resultado = $sql->select("arr");

    if ($resultado) {
        // echo (verificaBcrypt($contrasena, $resultado[0]['password']))? 'true':'FALSE';
        if (verificaBcrypt($contrasena, $resultado[0]['password']))
            return 1; //El Usuario,correo y contrasena de SG son iguales en Moodle
        else {
            //actualizaPasswordMoodle($resultado[0]['id'], passBCrypt(nDCrypt($res[0]['contrasena']), PASSWORD_DEFAULT));
            actualizaPasswordMoodle($resultado[0]['id'], passBCrypt($contrasena, PASSWORD_DEFAULT));
            return 3; //El Usuario y correo de SG son iguales en Moodle, pero la contrasena no.
        }
    }
    else
        return 2; //El Usuario y correo de SG no existen en Moodle
}
/**
 * Función que verifica la Integridad de password de SIAA con Moodle
 * Llama a verificaExistencia UsuarioMoodle y toma las acciones.
 * @param type $usuarioCorreo
 * @param type $contrasena
 */
function verificarIntegridadSG_Moodle($usuarioCorreo, $contrasena) {
    switch (verificaExistenciaUsuarioMoodle($usuarioCorreo, $contrasena)) {
        case 2:

            //redireccionaNoUsuarioSGMoodle();
            break;
    }
}

/**
 * Funcion que registra ENTRADA
 * @param type $idDatosPersonales
 */
function registraEntradaSitio($idDatosPersonales) {
    $query = new Query("SG");
    $query->insert("bitacora_acceso", "fecha, tipo, id_datos_personales", "now(), 0, $idDatosPersonales");
    return true;
}

/**
 * Función que registra la salida de la FRONTWEB, generando un tiempo de permanencia de acuerdo a la ultima entrada detectada
 * @param type $idDatosPersonales
 */
function registraSalidaSitio($idDatosPersonales) {
    //Retorna
    $ultimaEntrada = consultaUltimaEntradaSitio($idDatosPersonales);
    if($ultimaEntrada)
    {
        $query = new Query("SG");
        $query->insert("bitacora_acceso", "fecha, tipo, id_datos_personales,permanencia", "now(), 1, $idDatosPersonales, now()-'$ultimaEntrada'");
        return true;
    }
    return false;
    
}
/**
 * Funcion que consulta la ultima entrada registarda por un registro de datos_personales
 * @param type $idDatosPersonales
 * @return null
 */
function consultaUltimaEntradaSitio($idDatosPersonales) {
    $query = new Query("SG");
    $query->sql = "select fecha from bitacora_acceso where tipo=0 and id_datos_personales = $idDatosPersonales order by fecha desc LIMIT 1";
    $resultSet = $query->select('arr');

    if ($resultSet) {
        return $resultSet[0]['fecha'];
    } else {
        return null;
    }
}
?>