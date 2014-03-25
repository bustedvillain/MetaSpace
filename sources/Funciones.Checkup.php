<?php
/* 
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE CREACIÓN: 19 DE FEBRERO DEL 2014
 * OBJETIVO: FUNCIONES PARA CORROBORAR LA DISPONIBILDIAD DE LA APLICACION
 */

/**
 * Funcion para realizar pruebas sobre la disponibilidad de la aplicación
 */
//checkUp();
function checkUp(){
    //Variables globales 100
    if(($errorno = variablesGlobales()) == 0){
        //Conexion a SGI-DB 200
        if(($errorno = conexionBaseDatosSGIDB()) == 0){
             //Conexion a Moodle-DB 300
            if(($errorno = conexionMoodleDB()) == 0){
                //Conexion con webservices 400
                if($errorno = conexionWebServicesMoodle() == 0){
                    //Chequeo de ip publica SGI 500
    
                    //Chequeo de ip publica Moodle 600
    
                    //Disponibilidad del storage 700
                }
            }else{
                
            }
        }else{
            return $errorno;
        }
    }else{
        return $errorno;
    }        
}

/**
 * Funcion que verifica la conexión con una url
 * @param type $url
 * @return boolean
 */
function url_exists($url) {
    if (!$fp = curl_init($url)) return false;
    return true;
}

/**
 * Funcion que verifica que las variables globales que necesita el sistema esten correctamente
 * definidas
 * Si retorna 0 no hubo error
 * Si retorna algun otro numero, es el codigo del error
 * @return int
 */
function variablesGlobales(){
    if(!varOk(DB_NAME_MOD)){
        return 100;
    }
    if(!varOk(USER_MOD)){
        return 101;
    }
    if(!varOk(PASS_MOD)){
        return 102;
    }
    if(!varOk(HOST_MOD)){
        return 103;
    }
    if(!varOk(PORT_MOD)){
        return 104;
    }
    if(!varOk(DB_NAME_SG)){
        return 105;
    }
    if(!varOk(USER_SG)){
        return 106;
    }
    if(!varOk(PASS_SG)){
        return 107;
    }
    if(!varOk(HOST_SG)){
        return 108;
    }
    if(!varOk(PORT_SG)){
        return 109;
    }
    if(!varOk(IP_SERVER_PUBLIC)){
        return 110;
    }
    if(!varOk(UNIDADES_PATH)){
        return 111;
    }
    if(!varOk(STORAGE_PATH)){
        return 112;
    }
    if(!varOk(MENSAJE_DESBLOQUEO)){
        return 113;
    }
    if(!varOk(RUTA_MOODLE)){
        return 114;
    }
    if(!varOk(BASE_STORAGE)){
        return 115;
    }
    if(!varOk(CUENTA_CORREO)){
        return 116;
    }
    if(!varOk(TOKEN_WEBSERVICE)){
        return 117;
    }
    if(!varOk(SERVER_URL)){
        return 118;
    }
    if(!varOk(ROL_ALUMNO)){
        return 119;
    }
    if(!varOk(ROL_TUTOR_JUNIOR)){
        return 120;
    }
    if(!varOk(ROL_TUTOR_SENIOR)){
        return 121;
    }
    if(!varOk(ROL_COORDINADOR)){
        return 122;
    }
    if(!varOk(ROL_GESTOR)){
        return 123;
    }
    if(!varOk(ROL_ADMIN)){
        return 124;
    }
    if(!varOk(EVALUACION_DIAGNOSTICA)){
        return 125;
    }
    if(!varOk(EVALUACION_FINAL)){
        return 126;
    }
    if(!varOk(EVALUACION_DESEMPEÑO)){
        return 127;
    }
    if(!varOk(AUTOEVALUACION)){
        return 128;
    }    
    return 0;
}

/**
 * Funcion que verifica que una variable este definida correctamente
 * @param type $var
 * @return boolean
 */
function varOk($var){
    if(isset($var) && $var != NULL && $var != "" && !empty($var)){
        return true;
    }else{
        return false;
    }
}

/**
 * Funcion que verifica la disponibilidad del webservice con Moodle
 * @return int
 */
function conexionWebServicesMoodle(){
    try {
        $client = new SoapClient(SERVER_URL);
        return 0;
    } catch (SoapFault $e) {
        return 400;
    }
}

//function conexionMoodleDB(){
//    
//}
//
//function conexionBaseDatosSGIDB(){
//    
//}

function codigoErrores($errorno){
    switch($errorno){
        //Errores de variables globales
        case 100:
            return "El nombre de la base de datos de moodle no esta definido. (DB_NAME_MOD)";
        case 101:
            return "El usuario de la base de datos de moodle no esta definido. (USER_MOD)";
        case 102:
            return "La contraseña de la base de datos de moodle no esta definida. (PASS_MOD)";
        case 103:
            return "El host de la base de datos de moodle no esta definido. (HOST_MOD)";
        case 104:
            return "El puerto de la base de datos de moodle no esta definido. (POST_MOD)";
        case 105:
            return "El nombre de la base de datos del sistema de gestion no esta definido. (DB_NAME_SG)";
        case 106:
            return "El usuario de la base de datos del sistema de gestion no esta definido. (USER_SG)";
        case 107:
            return "La contraseña de la base de datos del sistema de gestion no esta definida. (PASS_SG)";
        case 108:
            return "El host de la base de datos del sistema de gestion no esta definido. (HOST_SG)";
        case 109:
            return "El puerto de la base de datos del sistema de gestion no esta definido. (POST_SG)";
        case 110:
            return "La ip publica del servidor no esta definida. (IP_SERVER_PUBLIC)";
        case 111:
            return "La ruta para leer las unidades de los cursos no esta definida. (UNIDADES_PATH)";
        case 112:
            return "La ruta para el almacenamiento del sistema no esta definida. (STORAGE_PATH)";
        case 113:
            return "El mensaje de desbloqueo no ha sido definido. (MENSAJE_BLOQUEO)";
        case 114:
            return "La direccion web para acceder a moodle no esta definida. (RUTA_MOODLE)";
        case 115:
            return "La direccion web para acceder a la carpeta de almacenamiento del sistema de gestion no esta definida. (BASE_STORAGE)";
        case 116:
            return "La dirección de correo de registro de usuarios no esta definida. (CUENTA_CORREO)";
        case 117:
            return "El token del webservice para la comunicación con Moodle no esta definida. (TOKEN_WEBSERVICE)";
        case 118:
            return "La";
        case 119:
            return "";
        case 120:
            return "";
        case 121:
            return "";
        case 122:
            return "";
        case 123:
            return "";
        case 124:
            return "";
        //Errores de conexion a base de datos SGI
            
        //Errores a conexion de base de datos Moodle
            
        //Errores de web service
            
        //Errores ip publica sgi
            
        //Erores ip publica moodle
            
        //Errores de storage
        
    }
}


?>