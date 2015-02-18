<?php
//   Date             Modified by         Change(s)
//   2013-09-24         HMP                 1.0
//$server=0 (localhost), $server=1 (remoto)

//if($server==0){
//	include("Config.server.php");
//}
//if($server==1){
//	include("Config.remote.server.php");
//}

//if($_SERVER["SERVER_NAME"] != "localhost")#en linea
//	include("Config.server.php");
//else
//	include("Config.local.php");

//   Date             Modified by         Change(s)
//   2013-09-24         JMNG                 1.0

/**
 * Configuracion de base de datos
 */
//Configuracion de Moodle
define("DB_NAME_MOD","softmeta");
define("USER_MOD","softmeta");
define("PASS_MOD","ems20140818");
define("HOST_MOD","200.66.87.58");
//define("DB_NAME_MOD","moodle");
//define("USER_MOD","postgres");
//define("PASS_MOD","912303");
//define("HOST_MOD","localhost");
define("PORT_MOD","5432");


//Configuracion de Sistema de Gestion
define("DB_NAME_SG","softmeta_sgi");
define("USER_SG","softmeta");
define("PASS_SG","ems20140818");
define("HOST_SG","200.66.87.58");
define("PORT_SG","5432");

define ("IP_SERVER_PUBLIC",'http://200.66.87.58/');
//define ("IP_SERVER_PUBLIC",'http://localhost/');

//Confuración de la plantilla
define("UNIDADES_PATH", "/var/www/html/storage/unidades");
define("STORAGE_PATH", "/var/www/html/storage/");
define("MENSAJE_DESBLOQUEO","Tu cuenta ha sido bloqueada durante las siguientes 24 horas. Si deseas desbloquearla antes de éste lapso comunícate con el administrador al correo uncorreo@unservidor.com");
//define("UNIDADES_PATH", "C:/wamp/www/eblue/softmeta/storage/unidades");
//define("UNIDADES_PATH", "../../storage/unidades");

define("RUTA_MOODLE",IP_SERVER_PUBLIC. 'moodle/login/index.php');
//define("RUTA_MOODLE",'http://localhost:80/moodle/login/index.php');

define("BASE_STORAGE", IP_SERVER_PUBLIC. "storage/");

define("CUENTA_CORREO", "registro@metaspace.mx");

define("TOKEN_WEBSERVICE", obtenerToken());

define("SERVER_URL", IP_SERVER_PUBLIC. "moodle/webservice/soap/server.php?wsdl=1&wstoken=".TOKEN_WEBSERVICE);
//define("SERVER_URL", "http://localhost:80/moodle/webservice/soap/server.php?wsdl=1&wstoken=".TOKEN_WEBSERVICE);
  
//Roles
define("ROL_ALUMNO", "alumno");
define("ROL_TUTOR_JUNIOR", "junior");
define("ROL_TUTOR_SENIOR", "senior");
define("ROL_COORDINADOR", "coordinador");
define("ROL_GESTOR", "gestor");
define("ROL_ADMIN", "manager");

//NOMBRES DE EXAMENES/ REPORTES EN MOODLE
define("EVALUACION_DIAGNOSTICA", "EDI");
define("EVALUACION_FINAL", "EFI");
define("EVALUACION_DESEMPEÑO", "EDE");
define("AUTOEVALUACION", "AUE");

//Version del software
define("VERSION", "v1.2.0");

//Biblioteca Virtual
define("BASE_URL_BIBLIOTECA", "http://200.66.87.58/BibliotecaVirtual");
define("TOKEN_BIBLIOTECA", "bf391c8b7d72b6d6");
define("URL_BIBLIOTECA_ALUMNO", BASE_URL_BIBLIOTECA . "/middleware/");
define("URL_BIBLIOTECA_ADMIN", BASE_URL_BIBLIOTECA . "/dashboard/?token=".TOKEN_BIBLIOTECA);

//Webservice Scorm moodle

//token webservice
define("TOKEN_WEBSERVICE_SCORM", obtenerToken());
//domain name moodle
define("DOMAIN_NAME_MOODLE", IP_SERVER_PUBLIC . "moodle");
//define("DOMAIN_NAME_MOODLE", "http://localhost/" . "moodle");
define("DOMAIN_FILE_MOODLE", DOMAIN_NAME_MOODLE ."/upfiles/");
define("PLAYER_SCORM", DOMAIN_NAME_MOODLE. "/mod/scorm/player.php");
define("UPLOAD_DIR_MOODLE","/var/www/html/moodle/upfiles/");
define("META_DOMAIN", IP_SERVER_PUBLIC . "MetaSpace/");

?>
