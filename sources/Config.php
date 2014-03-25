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
 * Servidor de Producción
 */
//Configuracion de Moodle
define("DB_NAME_MOD","softmeta");
define("USER_MOD","softmeta");
define("PASS_MOD","s0ftm3t6-.");
define("HOST_MOD","201.149.45.116");
define("PORT_MOD","5432");


//Configuracion de Sistema de Gestion
define("DB_NAME_SG","softmeta_sgi");
define("USER_SG","softmeta");
define("PASS_SG","s0ftm3t6-.");
define("HOST_SG","201.149.45.116");
define("PORT_SG","5432");

define ("IP_SERVER_PUBLIC",'http://201.149.45.116/');


//Confuración de la plantilla
define("UNIDADES_PATH", "/var/www/html/storage/unidades");
define("STORAGE_PATH", "/var/www/html/storage/");
define("MENSAJE_DESBLOQUEO","Tu cuenta ha sido bloqueada durante las siguientes 24 horas. Si deseas desbloquearla antes de éste lapso comunícate con el administrador al correo uncorreo@unservidor.com");
//define("UNIDADES_PATH", "C:/wamp/www/eblue/softmeta/storage/unidades");
//define("UNIDADES_PATH", "../../storage/unidades");

//define ("IP_SERVER_PUBLIC",'http://192.168.57.41');


define("RUTA_MOODLE",IP_SERVER_PUBLIC. 'moodle/login/index.php');

define("BASE_STORAGE", IP_SERVER_PUBLIC. "storage/");

define("CUENTA_CORREO", "registro@metaspace.mx");

//define("TOKEN_WEBSERVICE", "40e0c625c0dc4e15dc9d58cb243d681b");
define("TOKEN_WEBSERVICE", obtenerToken());

define("SERVER_URL", IP_SERVER_PUBLIC. "moodle/webservice/soap/server.php?wsdl=1&wstoken=".TOKEN_WEBSERVICE);
  
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

?>
