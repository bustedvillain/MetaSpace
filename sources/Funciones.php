<?php
//Control de cambios #3
//Agregar la carga de FUnciones Fechas
//Control de cambios #&
//Autor:Omar Nava
//Objetivo: ALta cursos
//03-ene-2014
//
//Control de cambios #7
//Autor:Omar Nava
//Objetivo: Hacer una funcion para el nobre y cerrar sesion
//03-ene-2014

/**
 * CHANGE CONTROL 2.00
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACION: 19 DE FEBRERO DEL 2014
 * OBJETIVO: FUNCIONES PARA CORROBORAR LA CONEXION CON CIERTAS PAGINAS O IPS
 */
session_start();
require "PHPMailer-master/PHPMailerAutoload.php";
include 'Correo2.php';

include ("FuncionesSesion.php");
include ("FuncionesTutorJunior.php");
include ("FuncionesTutorSenior.php");
include ("FuncionesAdmin.php");
include ("FuncionesPlantilla.php");
include ("FuncionesTutorCoordinador.php");
include ("FuncionesMapaUnidad.php");
include 'FuncionesReportesSesion.php';
include ("FuncionesTutores.php");
include ("FuncionesMapaCurso.php");
include ("FuncionesFrontWeb.php");
include ("FuncionesGeneradorReportes.php");
include ("FuncionesReportes.php");
include ("FuncionesReportes2.php");
include ("FuncionesExcel.php");
include ("FuncionesProfesor.php");
//Inicia control de cambios #3
include ("FuncionesFechas.php");
//Finaliza control de cambios #3
include("Utilidades.php");
include("INI.php");
include ("FuncionesBaul.php");
include ("Funciones.Frontweb.Alumno.php");
include ("Funciones.Frontweb.Tutor.php");
include ("Funciones.Frontweb.Padre.php");
include ("Funciones.Frontweb.Tutor2");
require_once 'Query.php';
/**
 * CHANGE CONTROL 2.00
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACION: 19 DE FEBRERO DEL 2014
 * OBJETIVO: FUNCION PARA CORROBORAR LA CONEXION CON CIERTAS PAGINAS O IPS
 */
include ("Funciones.Checkup.php");


define("VERSION", "v1.0.4");
/*
 * Archivo de funiones generales, hace llamado las funciones de todos los desarrolladores
 */

/**
 * Funcion que limpiar una cadena de texto
 * @param type $var cadena de texto
 * @return type cadena limpia
 */
function __($var) {
    $dato = htmlentities($var, ENT_QUOTES, 'UTF-8');
    $dato = stripslashes($dato);
    return trim($dato);
}

/**
 * Verifica si una cadena corresponde a la expresion regular de un correo
 * @param type $email cadena de texto donde esta el correo electronico
 * @return boolean si es correo retorna verdadero
 */
function esEmail($email = "") {
    $car = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
    if (strpos($email, '@') !== false && strpos($email, '.') !== false) {
        if (preg_match($car, $email)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Elimina caracteres no permitidos en los correos electronicos
 * @param type $email cadena de texto donde esta el correo electronico
 * @return type el correo electronico limpio
 */
function limpiaEmail($email) {
    $limpio = preg_replace('/[^a-z0-9+_.@-]/i', '', $email);
    return strtolower($limpio);
}
/**
 * Función que codifica email
 * @param type $email
 * @return string
 */
function codificaMail($email = "") {
    $mailCodificado = "";
    for ($i = 0; $i < strlen($email); $i++) {
        if (rand(0, 1) == 0) {
            $mailCodificado .= "&#" . (ord($email[$i])) . ";";
        } else {
            $mailCodificado .= "&#X" . dechex(ord($email[$i])) . ";";
        }
    }
    return $mailCodificado;
}

/**
 * Funcion para guardar un archivo y retornar la ruta donde se guardo este archivo
 * @param type $carpeta
 * @param type $titulo
 * @return string
 */
function guardaArchivo($carpeta, $titulo) {
    include_once ("Documento.inc");
    $archivo = new Documento();
    $archivo->archivo = $_FILES["archivo"]["tmp_name"];
    $archivo->error = $_FILES["archivo"]["errono"];
    $archivo->nombre = $titulo;
//echo $img->nombre;
    $archivo->tamano = $_FILES["archivo"]["size"];
    $archivo->tipo = $_FILES["archivo"]["type"];
    $archivo->titulo = $titulo;
    $archivo->destino = $carpeta;

    if ($archivo->verificaError()) {
        if ($archivo->verificarExtension()) {
//echo "\nextension correcta";
        }
//        if ($archivo->cambiarNombre()) {
//echo "\nNombre cambiado";
//        }
        if ($archivo->copia()) {
//echo "  Archivo subido";
        }
    }

    $ruta = UNIDADES_PATH . "/" . $img->nombre;
    return $ruta;
}

/**
 * Limpia texto eliminando acentos y caracteres especiales
 * @param type $String
 * @return type
 */
Function limpiarTexto($String) {

    $String = ereg_replace("[äáàâãª]", "a", $String);
    $String = ereg_replace("[ÁÀÂÃÄ]", "A", $String);
    $String = ereg_replace("[ÍÌÎÏ]", "I", $String);
    $String = ereg_replace("[íìîï]", "i", $String);
    $String = ereg_replace("[éèêë]", "e", $String);
    $String = ereg_replace("[ÉÈÊË]", "E", $String);
    $String = ereg_replace("[óòôõöº]", "o", $String);
    $String = ereg_replace("[ÓÒÔÕÖ]", "O", $String);
    $String = ereg_replace("[úùûü]", "u", $String);
    $String = ereg_replace("[ÚÙÛÜ]", "U", $String);
//$String = ereg_replace("[^´`¨~]", "", $String);
    $String = str_replace("ç", "c", $String);
    $String = str_replace("Ç", "C", $String);
    $String = str_replace("ñ", "n", $String);
    $String = str_replace("Ñ", "N", $String);
    $String = str_replace("Ý", "Y", $String);
    $String = str_replace("ý", "y", $String);
    $String = str_replace("&aacute;", "a", $String);
    $String = str_replace("&eacute;", "e", $String);
    $String = str_replace("&iacute;", "i", $String);
    $String = str_replace("&oacute;", "o", $String);
    $String = str_replace("&uacute;", "u", $String);
    $String = str_replace("&AACUTE;", "A", $String);
    $String = str_replace("&EACUTE;", "E", $String);
    $String = str_replace("&IACUTE;", "I", $String);
    $String = str_replace("&OACUTE;", "O", $String);
    $String = str_replace("&UACUTE;", "U", $String);

    return $String;
}

define('PASSWORD_BCRYPT', 1);
define('PASSWORD_DEFAULT', PASSWORD_BCRYPT);

/**
 * Devuelve true si la contraseña en el @param $password es = a @param $hash
 * @param type $password
 * @param type $hash
 * @return boolean
 */
function verificaBcrypt($password, $hash) {
    $ret = crypt($password, $hash);
    if (!is_string($ret) || strlen($ret) != strlen($hash) || strlen($ret) <= 13) {
        return false;
    }

    $status = 0;
    for ($i = 0; $i < strlen($ret); $i++) {
        $status |= (ord($ret[$i]) ^ ord($hash[$i]));
    }

    return $status === 0;
}
/**
 * Encrypta a la contraseña en password
 * @param type $password
 * @param type $algo por defecto es PASSWORD_BCRYPT
 * @param array $options
 * @return null|boolean
 */
function passBCrypt($password, $algo = PASSWORD_BCRYPT, array $options = array()) {

    switch ($algo) {
        case PASSWORD_BCRYPT:
// Note that this is a C constant, but not exposed to PHP, so we don't define it here.
            $cost = 10;
            if (isset($options['cost'])) {
                $cost = $options['cost'];
                if ($cost < 4 || $cost > 31) {
                    trigger_error(sprintf("password_hash(): Invalid bcrypt cost parameter specified: %d", $cost), E_USER_WARNING);
                    return null;
                }
            }
            $required_salt_len = 22;
            $hash_format = sprintf("$2y$%02d$", $cost);
            break;
        default:
            trigger_error(sprintf("password_hash(): Unknown password hashing algorithm: %s", $algo), E_USER_WARNING);
            return null;
    }
    if (isset($options['salt'])) {
        switch (gettype($options['salt'])) {
            case 'NULL':
            case 'boolean':
            case 'integer':
            case 'double':
            case 'string':
                $salt = (string) $options['salt'];
                break;
            case 'object':
                if (method_exists($options['salt'], '__tostring')) {
                    $salt = (string) $options['salt'];
                    break;
                }
            case 'array':
            case 'resource':
            default:
                trigger_error('password_hash(): Non-string salt parameter supplied', E_USER_WARNING);
                return null;
        }
        if (strlen($salt) < $required_salt_len) {
            trigger_error(sprintf("password_hash(): Provided salt is too short: %d expecting %d", strlen($salt), $required_salt_len), E_USER_WARNING);
            return null;
        } elseif (0 == preg_match('#^[a-zA-Z0-9./]+$#D', $salt)) {
            $salt = str_replace('+', '.', base64_encode($salt));
        }
    } else {
        $buffer = '';
        $raw_length = (int) ($required_salt_len * 3 / 4 + 1);
        $buffer_valid = false;
        if (function_exists('mcrypt_create_iv')) {
            $buffer = mcrypt_create_iv($raw_length, MCRYPT_DEV_URANDOM);
            if ($buffer) {
                $buffer_valid = true;
            }
        }
        if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes')) {
            $buffer = openssl_random_pseudo_bytes($raw_length);
            if ($buffer) {
                $buffer_valid = true;
            }
        }
        if (!$buffer_valid && file_exists('/dev/urandom')) {
            $f = @fopen('/dev/urandom', 'r');
            if ($f) {
                $read = strlen($buffer);
                while ($read < $raw_length) {
                    $buffer .= fread($f, $raw_length - $read);
                    $read = strlen($buffer);
                }
                fclose($f);
                if ($read >= $raw_length) {
                    $buffer_valid = true;
                }
            }
        }
        if (!$buffer_valid || strlen($buffer) < $raw_length) {
            $bl = strlen($buffer);
            for ($i = 0; $i < $raw_length; $i++) {
                if ($i < $bl) {
                    $buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
                } else {
                    $buffer .= chr(mt_rand(0, 255));
                }
            }
        }
        $salt = str_replace('+', '.', base64_encode($buffer));
    }
    $salt = substr($salt, 0, $required_salt_len);

    $hash = $hash_format . $salt;

    $ret = crypt($password, $hash);

    if (!is_string($ret) || strlen($ret) <= 13) {
        return false;
    }

    return $ret;
}
/**
 * devuelve un string encriptado en el algoritmo nCrypt
 * @param type $string
 * @return string
 */
function nCrypt($string) {
    $string = fixEncoding($string);
    $string = htmlentities($string, ENT_QUOTES, "UTF-8");
    $arrayLetras = arrayLetras();
    $arrayNCrypt = arrayNCrypt();
    $arrTexto = str_split($string, 1);
    $strCrypted = "";
    foreach ($arrTexto as $a) {
        if (in_array($a, $arrayLetras)) {
            $index = array_search($a, $arrayLetras);
            $strCrypted = $strCrypted . $arrayNCrypt[$index];
        } else {
            $strCrypted = $strCrypted . "$!" . $a . "}/";
        }
    }
    return $strCrypted;
}
/**
 * Arregla el encoding
 * @param type $in_str
 * @return type
 */
function fixEncoding($in_str) {
    $cur_encoding = mb_detect_encoding($in_str);
    if ($cur_encoding == "UTF-8" && mb_check_encoding($in_str, "UTF-8"))
        return $in_str;
    else
        return utf8_encode($in_str);
}
/**
 * Devuelve string desencryptado que haya sido encryptado en nCrypt
 * @param type $string
 * @return type
 */
function nDCrypt($string) {
    $arrayLetras = arrayLetras();
    $arrayNCrypt = arrayNCrypt();
    $arrTexto = str_split($string, 5);
    $strDeCrypted = "";
    foreach ($arrTexto as $a) {
        if (in_array($a, $arrayNCrypt)) {
            $index = array_search($a, $arrayNCrypt);
            $strDeCrypted = $strDeCrypted . $arrayLetras[$index];
        } else {
            $strDeCrypted = $strDeCrypted . substr($a, 2, 1);
        }
    }
    return html_entity_decode($strDeCrypted, ENT_QUOTES, "UTF-8");
}
/**
 * Devuelve un arreglo de letras (alfaveto de nCrypt)
 * @return type
 */
function arrayLetras() {
    return array(
        "a", "b", "c", "d", //1
        "e", "f", "g", "h", //2
        "i", "j", "k", "l", //3
        "m", "n", "ñ", //4
        "o", "p", "q", "r", //5
        "s", "t", "u", "v", //6
        "w", "x", "y", "z", //7
        "A", "B", "C", "D", //8
        "E", "F", "G", "H", //9
        "I", "J", "K", "L", //10
        "M", "N", "Ñ", //11
        "O", "P", "Q", "R", //12
        "S", "T", "U", "V", //13
        "W", "X", "Y", "Z", //14
        "1", "2", "3", "4", //15
        "5", "6", "7", "8", //16
        "9", "0", //17
        "á"//18
    );
}
/**
 * Devuelve el alfabeto del Ncrypt (encriptado)
 * @return type
 */
function arrayNCrypt() {
    return arraY(
        "86y9a", ",wx7/", "@bEsV", "5E-h[", //1
        "uxuH=", "Dak8a", "F45c4", "sR*VY", //2
        "dUKp9", "ysmi,", "+vF}N", "Sm@FW", //3
        "%OvRt", "9-59U", "=r4N%", //4
        "6JeU]", "#:.A4", "kxr)s", "556fD", //5
        "xPM_6", "QSEAm", "_Es6p", "zHFL#", //6
        "X-1HL", "xak#G", "JS@5u", "=*[F}", //7
        "d[Q]8", "@UU?y", "Hxz!U", "Q?.-u", //8
        "+G.4s", "wB58)", "XcD)A", "aS1RX", //9
        "7tAzc", "61:y@", "dbG[h", "X%dUJ", //10
        "JfKuy", "q([sy", "-?_4N", //11
        "Wp7L(", "}dbd0", "C7}CV", "Qf[eP", //12
        "MrPZi", "y/2nw", "/x-7c", "@pC2d", //13
        "E<iCy", "9xd]|", "tmn:m", "K450v", //14
        "9PS%i", "7]{W=", "o6#{4", "@+@y1", //15
        "hsvZy", "n6ib2", "Zm*}V", "o2esF", //16
        "5Du3=", "w,AXt", //17
        "!-_45"//18
    );
}
/**
 * Descomprime un zip y luego elimina el zip 
 * @param type $id_unidad
 * @param type $nombreArchivo
 */
function descomprimeZIP($id_unidad, $nombreArchivo) {
    $zip = new ZipArchive();
    echo UNIDADES_PATH . "/" . $nombreArchivo;
    if ($zip->open(UNIDADES_PATH . "/" . $nombreArchivo) === TRUE) {
        $zip->extractTo(UNIDADES_PATH . "/" . $id_unidad);
        $zip->close();
        echo 'ok';
    } else {
        echo 'failed';
    }
//Elimina el .zip
    unlink(UNIDADES_PATH . "/" . $nombreArchivo);
}
/**
 * Devuelve true si una unidad tiene series
 * @param type $idUnidad
 * @return boolean
 */
function unidadEnAlgunaSerie($idUnidad) {

    $query = new Query("SG");
    $query->sql = "select id_serie_aer from serie_aer where id_unidad = $idUnidad";
    $series = $query->select("obj");
    if ($query->numRegistros() > 0) {
        return true;
    } else {
        return false;
    }
}
/**
 * Devuelve los cursos Moodle para el linkeo de series API
 * @param type $idCursoSG
 */
function cursosMoodleParaSerie($idCursoSG) {
//Crea objeto para realizar consultas al sistema de gestion
    $query = new Query("SG");
//Query a Moodle
    $query->sql = "SELECT * from unidades where id_curso=$idCursoSG and status = 1 and url_unidad is not null";
    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $topico) {
            if (!unidadEnAlgunaSerie($topico->id_unidad))
                $boton = '<a class="btn btn-info" data-toggle="modal" href="#verModalLinkeo" onclick="llenaModalLinkeo(\'' . $topico->id_unidad . '\');">Agregar informaci&oacute;n de series';
            else
                $boton = '<a class="btn btn-success" data-toggle="modal" href="#verModalLinkeo" onclick="llenaModalLinkeoEditar(\'' . $topico->id_unidad . '\');">Editar informaci&oacute;n de series';
            echo <<<HTML
            <div class="well">
                <h5>$topico->nombre_unidad </h5> $boton </a> 
            </div>
HTML;
        }
    } else {
        echo "<h3 class='text-warning'>No hay t&oacute;picos en este curso.</h3>";
    }
}
/**
 * Imprime los options del combo de habilidades activas
 * @return string
 */
function comboHabilidades() {

    $query = new Query("SG");

    $query->sql = "SELECT * from habilidades where status = 1";
    $habilidades = $query->select("obj");
    $var = "";
    if ($habilidades) {
        foreach ($habilidades as $hab) {
//            echo<<<hab
//        <option value = "$hab->id_habilidad">$hab->nombre_habilidad</option>
//hab;  

            $ns = ($hab->nombre_habilidad);
            $var = $var . "<option value = '$hab->id_habilidad'>$ns</option>";
        }
    }
    return $var;
}
/**
 * Imprime un combo de habilidades dejando seleccionada la que se pida
 * @param type $idSelected
 * @return string
 */
function comboHabilidadesConSelected($idSelected) {

    $query = new Query("SG");

    $query->sql = "SELECT * from habilidades where status = 1";
    $habilidades = $query->select("obj");
    $var = "";
    if ($habilidades) {
        foreach ($habilidades as $hab) {
//            echo<<<hab
//        <option value = "$hab->id_habilidad">$hab->nombre_habilidad</option>
//hab;
            $ns = html_entity_decode($hab->nombre_habilidad);
            if ($idSelected == $hab->id_habilidad) {
                $var = $var . "<option value = '$hab->id_habilidad' selected='selected'>$ns</option>";
            } else {
                $var = $var . "<option value = '$hab->id_habilidad'>$ns</option>";
            }
        }
    }
    return $var;
}
/**
 * Imprime los options de los tipos de elementos
 * @return string
 */
function comboTipoElemento() {

    $query = new Query("SG");

    $query->sql = "SELECT * from tipo_elemento where status = 1";
    $tiposE = $query->select("obj");
    $var = "";
    if ($tiposE) {
        foreach ($tiposE as $te) {
            $var = $var . "<option value = '$te->id_tipo_elemento'>$te->nombre_tipo</option>";
//            echo<<<hab
//        <option value = "$te->id_tipo_elemento">$te->nombre_tipo</option>
//hab;
        }
    } else {
        echo <<<html
            <option value="">No hay tipos de elementos</option>
html;
    }
    return $var;
}

/**
 * Genera un combo box con los padres registrados en la base de datos
 */
function comboPadres() {
    $query = new Query("SG");

    $query->sql = "SELECT  p.id_padre, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido FROM padres p, datos_personales dp where p.id_datos_personales=dp.id_datos_personales and p.status = 1";
    $padres = $query->select("obj");

    echo <<<combo
        <option value="">Seleccione un Padre</option>
combo;
    if ($padres) {

        foreach ($padres as $padre) {
            echo <<<HTML
                <option value="$padre->id_padre">$padre->nombre_pila $padre->primer_apellido $padre->segundo_apellido</option>
HTML;
        }
    }
}

/**
 * Function que genera opciones de un combo de los
 * profesores de aula en la base de datos
 */
function comboProfesoresAula() {
    $query = new Query("SG");

    $query->sql = "SELECT  p.id_profesor_aula, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido FROM profesores_aula p, datos_personales dp where p.id_datos_personales=dp.id_datos_personales and p.status = 1";
    $profesores = $query->select("obj");

    echo <<<combo
        <option value="">Seleccione un Profesor de Aula</option>
combo;
    if ($profesores) {
        foreach ($profesores as $profesor) {
            echo <<<HTML
                <option value="$profesor->id_profesor_aula">$profesor->nombre_pila $profesor->primer_apellido $profesor->segundo_apellido</option>
HTML;
        }
    }
}

/**
 * Funcion que genera un combo con las empresas registradas
 * en la base de datos
 */
function comboEmpresas() {
    $query = new Query("SG");

    $query->sql = "SELECT  id_empresa, nombre_empresa FROM empresa where status = 1";
    $empresas = $query->select("obj");

    if ($empresas) {
        foreach ($empresas as $empresa) {
            echo <<<HTML
                <option value="$empresa->id_empresa">$empresa->nombre_empresa</option>
HTML;
        }
    } else {
        echo <<<html
            <option value=''>No hay empresas disponibles</option>
html;
    }
}

/**
 * Funcion que recibe un post y de todos los atributos genera un arreglo para los campos
 * y para los valores
 * @param type $_POST El post del formulario
 * @param type $delimitador delimitador para cuando se trabaja con varias tablas
 * @param type $tablas las tablas a las cuales se desea dividir los campos y valores, son las tablas dividido por comas
 * @return array ("campos" => $campos, "valores" => $valores), dependiendo de si hay varias tablas $campos y $valores seran arreglos
 */
function destripaPost($POST, $delimitador = NULL, $tablas = NULL) {

    if (isset($delimitador) && isset($tablas)) {
        $campos = array();
        $valores = array();
    } else {
        $campos = "";
        $valores = "";
    }

    if (isset($tablas)) {
        $tablas = explode(",", str_replace(" ", "", $tablas));
    }

    foreach ($POST as $campo => $valor) {
        if (isset($delimitador) && isset($tablas)) {
            $referencia = explode($delimitador, $campo);
            $refTabla = $referencia[0];
            $col = $referencia[1];

            if ($col != "" && $valor != "") {
                $valor = __($valor);
                //Guarda los datos
                foreach ($tablas as $t) {
                    if ($refTabla == $t) {
                        $campos["$t"] = $campos["$t"] . ", " . $col;
                        $valores["$t"] = $valores["$t"] . ", " . "'$valor'";
                    }
                }
            }
        } else {
            $campos = $campos . ", " . $campo;
            $valores = $valores . ", " . "'$valor'";
        }
    }

//LImpiar primeras comas
    if (isset($delimitador) && isset($tablas)) {
//Limpia la primera coma
        foreach ($tablas as $t) {
            $campos["$t"] = substr($campos["$t"], 1);
            $valores["$t"] = substr($valores["$t"], 1);
        }
    } else {
        $campos = substr($campos, 1);
        $valores = substr($valores, 1);
    }

    return array("campos" => $campos, "valores" => $valores);
}

function destripaPostEdicion($POST, $delimitador = NULL, $tablas = NULL) {
    $sets = array();

    if (isset($tablas)) {
        $tablas = explode(",", str_replace(" ", "", $tablas));
    }

    foreach ($POST as $campo => $valor) {
        if (isset($delimitador) && isset($tablas)) {
            $referencia = explode($delimitador, $campo);
            $refTabla = $referencia[0];
            $col = $referencia[1];

            if ($col != "" && $valor != "") {
                if ($col != "contrasena") {
                    $valor = __($valor);
                }

                //Guarda los datos
                foreach ($tablas as $t) {
                    if ($refTabla == $t) {
                        if ($valor == "null") {
                            $sets["$t"] = $sets["$t"] . ", $col = $valor";
                        } else {
                            $sets["$t"] = $sets["$t"] . ", $col = '$valor'";
                        }
                    }
                }
            }
        } else {
            $sets = $sets . ", $col='$valor'";
        }
    }

//LImpiar primeras comas
    if (isset($delimitador) && isset($tablas)) {
//Limpia la primera coma
        foreach ($tablas as $t) {
            $sets["$t"] = substr($sets["$t"], 1);
        }
    } else {
        $sets = substr($sets, 1);
    }

    return $sets;
}
/**
 * Imprime los options del combo de roles de un tutor
 */
function comboRolesTutores() {
    $query = new Query("SG");

    $query->sql = "SELECT  id_rol_tutor, nombre FROM rol_tutor where status = 1";
    $roles = $query->select("obj");

    if ($roles) {
        foreach ($roles as $rol) {
            echo <<<HTML
                <option value="$rol->id_rol_tutor">$rol->nombre</option>
HTML;
        }
    } else {
        echo <<<html
            <option value="">No hay roles</option>
html;
    }
}

/**
 * Funcion que recibe un archivo y lo guarda en una ubicacion en especifico
 * @param type $FILE
 * @param type $carpeta
 * @param type $destino
 * @param type $actualiza
 * @return boolean
 */
function guardaStorage($FILE, $carpeta, $destino, $actualiza = NULL) {

//    echo "<br>Archivo:";
//    var_dump($FILE);
//    echo "<br>id_unidad:$id_unidad";
//set POST variables
    $base = BASE_STORAGE;
    $url = $base . "gdaStorage.php";

    $servArchivos = fopen($url, "r");

    if ($servArchivos !== false) {
//        imprimeConsola("Enviando archivos a: $url");
//    echo " url:$url";
        try {
//open connection        
            $ch = curl_init();

//set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "loquesea");
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);

            $post = array(
                "archivo" => "@" . $FILE,
                "carpeta" => $carpeta,
                "destino" => $destino,
                "actualiza" => $actualiza
            );
//            imprimeConsola("POST:");
//            var_dump($post);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $response = curl_exec($ch);
//            echo "<br>RESPONSE:<br>";
//            var_dump($response);
//            echo "<br>$response";
            return $base . $response;
        } catch (Exception $e) {
            imprimeError($e->getMessage());
        }
    } else {
        imprimeError("No se puede abrir el script de guardado");
        return false;
    }
}

/**
 * Envio de un post
 * @param String $url
 * @param array $params
 * @param type $debug
 * @return type
 */
function post($url, $params, $debug = NULL) {
    try {
        ($debug != NULL) ? imprimeConsola("Enviando post") : "";
        //open connection        
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "loquesea");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);

//            imprimeConsola("POST:");
//            var_dump($post);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $response = curl_exec($ch);
//            echo "<br>RESPONSE:<br>";
//            var_dump($response);
//            echo "<br>$response";
        ($debug != NULL) ? imprimeConsola("Ejecuta post ") : "";
        if (($debug != NULL)) {
            var_dump($response);
        }
    } catch (Exception $e) {
        imprimeError($e->getMessage());
    }
}

/**
 * Redirect with POST data.
 *
 * @param string $url URL.
 * @param array $post_data POST data. Example: array('foo' => 'var', 'id' => 123)
 * @param array $headers Optional. Extra headers to send.
 */
function redirect_post($url, array $data, array $headers = null) {
    $params = array(
        'http' => array(
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    if (!is_null($headers)) {
        $params['http']['header'] = '';
        foreach ($headers as $k => $v) {
            $params['http']['header'] .= "$k: $v\n";
        }
    }
    $ctx = stream_context_create($params);
    $fp = @fopen($url, 'rb', false, $ctx);
    if ($fp) {
        echo @stream_get_contents($fp);
        die();
    } else {
        // Error
        throw new Exception("Error loading '$url', $php_errormsg");
    }
}

//Inicia control de cambios #3
/**
 * Verifica en caso de que exista una cookie para construir la sesión o iniciar normalmente
 * @return boolean
 */
function verificaCookieTipo() {
    if (isset($_COOKIE["smTipo"])) {
        $tipo = -1;
        switch ($_COOKIE["smTipo"]) {
            case "alumno":
                $tipo = 0;
                break;
            case "Junior":
                $tipo = 1;
                break;
            case "Senior":
                $tipo = 1;
                break;
            case "Coordinador":
                $tipo = 1;
                break;
            case "profesorAula":
                $tipo = 2;
                break;
            case "padre":
                $tipo = 3;
                break;
            case "gestorContenidos":
                $tipo = 4;
                break;
            case "administrador":
                $tipo = 5;
                break;
        }
        llenaSesionAuth($_COOKIE["smIdDatosPersonales"]);
        llenaArregloSesion($tipo, $_COOKIE["smNombre"], $_COOKIE["smIdDatosPersonales"], $_COOKIE["smIdPorTabla"], date("H:i:s"));
//        header("Location:../../admin/index.php");
        return true;
    } else {
//        header("Location:../../index.php");
        return false;
    }
}

//Inicia control de cambios #6
/**
 * Redirecciona de manera diferente si hay cookies o no creadas
 * @param type $rutaLarga
 * @param type $rutaCorta
 * @param type $prefijo
 */
function locationConCookie($rutaLarga, $rutaCorta, $prefijo = "") {
    if (isset($_COOKIE["smTipo"])) {
        header('Location:' . $prefijo . $rutaCorta);
    } else {
        header('Location:' . $prefijo . $rutaLarga . $rutaCorta);
    }
}

/**
 * Devuelve una ruta para redireccionar dependiendo si existe o no cookie
 * @param type $rutaLarga
 * @param type $rutaCorta
 * @param type $prefijo
 * @return type
 */
function rutaConCookie($rutaLarga, $rutaCorta, $prefijo = "") {
    if (isset($_COOKIE["smTipo"])) {
        return $prefijo . $rutaCorta;
    } else {
        return $prefijo . $rutaLarga . $rutaCorta;
    }
}

//termina control de cambios #6
/**
 * Crea los registros en el arreglo de sesión para hacer log in en moodle
 * @param type $idDatosPersonales
 */
function llenaSesionAuth($idDatosPersonales) {
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql = "
                select *
                from datos_personales
                where id_datos_personales = " . $idDatosPersonales . "
            ";
    $listaUsuarios = $sql->select("obj");
    if (!empty($listaUsuarios)) {
        foreach ($listaUsuarios as $us) {
            $_SESSION['userMail'] = $us->nombre_usuario;
            $_SESSION['pass'] = nDCrypt($us->contrasena);
        }
    }
}

//Finaliza control de cambios #3
/**
 * Determina si existe una sesión valida creada
 * @return boolean
 */
function existeSession() {
    if (esAlumno() || esJr() || esSenior() ||
            esCoordinador() || esProfesorAula() || esPadre() || esGestorContenido() || esAdministrador())
        return true;
    else
        return false;
}

/**
 * Función que hace uso de la funcion mail para el envio de correo.
 * NOTA: Es posible enviar correo por SMTP via GMAIL, es necesario
 * descomentar linea en esta funcion y el Alta de Usuarios, para hace uso
 * de esta función
 * @param type $nombre
 * @param type $correo
 * @param type $comentario
 * @return boolean
 */
function enviarMailComentario($nombre, $correo, $comentario) {
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "X-Priority: 3\r\n";
    $headers .= "X-Mailer: PHP" . phpversion() . "\r\n";
    $headers .= "From: MetaSpace";

    $mensaje = <<<correo
 <html>
    <body style="font-family: Arial; font-size: 12px">   
    <h4>Mensaje recibido desde "Contacto" MetaSpace</h4>
    <h4>$nombre</h4>
    <h4>$correo<br/></h4>
    <p>$comentario</p>
    </body>
    </html>
correo;
    $titulo = 'Mensaje recibido desde "Contacto" MetaSpace';

//    $correo = new Correo(obtenerValorCorreoContacto(), $titulo, $mensaje);
//    $correo->enviar();
    $res = mail(obtenerValorCorreoContacto(), $titulo, $mensaje, $headers);
    if ($res)
        return true;
    else
        return false;
}
/**
 * Devuelve el correo de contacto actual en variables.ini
 * @return type
 */
function obtenerValorCorreoContacto() {
    $arrayConfig = parse_ini_file("Variables.ini");
    return $arrayConfig['correoContacto'];
}

/**
 * Obtener la inactividad en nuestro sistema
 * @return type
 */
function obtenerValorInactividadAdministrador() {
    $arrayConfig = parse_ini_file("Variables.ini");
    return $arrayConfig['inactividadAdministrador'];
}

/**
 * Obtener la inactividad en nuestro sistema
 * @return type
 */
function obtenerValorInactividadGestor() {
    $arrayConfig = parse_ini_file("Variables.ini");
    return $arrayConfig['inactividadGestor'];
}

/**
 * Obtener la inactividad en nuestro sistema
 * @return type
 */
function obtenerValorInactividadTutores() {
    $arrayConfig = parse_ini_file("Variables.ini");
    return $arrayConfig['inactividadTutores'];
}

/**
 * Obtener la inactividad en nuestro sistema
 * @return type
 */
function obtenerValorInactividadPadre() {
    $arrayConfig = parse_ini_file("Variables.ini");
    return $arrayConfig['inactividadPadre'];
}

/**
 * Obtener la inactividad en nuestro sistema
 * @return type
 */
function obtenerValorInactividadAlumno() {
    $arrayConfig = parse_ini_file("Variables.ini");
    return $arrayConfig['inactividadAlumno'];
}

/**
 * Obtener la inactividad en nuestro sistema
 * @return type
 */
function obtenerToken() {
    $arrayConfig = parse_ini_file("Variables.ini");
    return $arrayConfig['tokenMoodle'];
}
/**
 * Obtiene el color en el archivo pedido
 * @param type $ruta
 * @return type
 */
function obtenerColor($ruta) {
    $arrayConfig = parse_ini_file($ruta);
    return $arrayConfig['colorCalif'];
}

/**
 * Función que guarda todo el contenido de $_POST en el archivo Variables.ini
 * @param type $POST
 */
function guardar($POST) {
    $array = array($POST);

    INI::write("Variables.ini", $array);
}
/**
 * Devuelve el navegador actual
 * @return type
 */
function getBrowser() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }

    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }

    return array(
        'userAgent' => $u_agent,
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        'pattern' => $pattern
    );


    ////Ejemplo de llamado $yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
}

/**
 * Funcion que valida la fortaleza de una contraseña
 * Valida que tenga 1 numero, 8 caracteres
 * Devuelve true si cumple con la fortaleza y false si no pasa la prueba
 * @param type $contrasena
 * @return boolean
 */
function validaFortaleza($contrasena) {
    $contrasena = $contrasena . "";
    echo "Validar:";
    var_dump($contrasena);
    $longitud = 0;
    $numero = false;
    $longitud = strlen($contrasena);
    echo "Longitud: $longitud; ";

    for ($i = 0; $i < strlen($contrasena); $i++) {
        if (is_numeric($contrasena[$i])) {
            echo "Hay un numero; ";
            $numero = true;
        }
    }

    if ($longitud >= 8 && $numero === true) {
        return true;
    } else {
        return false;
    }
}

//Inicia control de cambios #7
function nombreLogout() {
    $html = '  <b><a href="../../logout.php"> Cerrar Sesi&oacute;n</a></b>';
    if (isset($_SESSION['nombre']))
        echo $_SESSION['nombre'] . $html;
}

//Termina control de cambios #7
//function validaContrasena($contrasena){
//    if(ctype_)
//}

/**
 * Funcion que guarda una imagen
 * NOTA: El name del file donde se sube la imagen debe llamarse "imagen"
 * A excepcion de que se mejore el metodo
 * @param type $carpeta
 * @param type $titulo
 * @return string
 */
function guardaImagen($titulo) {
    include_once ("Documento.inc");
    $img = new Documento();
    $img->archivo = $_FILES["imagen"]["tmp_name"];
    $img->error = $_FILES["imagen"]["error"];
    $img->nombre = $titulo;
    //echo $img->nombre;
    $img->tamano = $_FILES["imagen"]["size"];
    $img->tipo = $_FILES["imagen"]["type"];
    $img->titulo = $titulo;
    $img->destino = STORAGE_PATH . "fotos";

    if ($img->verificaError()) {
//        if ($img->verificarExtension()) {
//            //echo "\nextension correcta";
//        } else {
//            return false;
//        }
//        if ($img->cambiarNombre()) {
//            //echo "\nNombre cambiado";
//        }
        $ruta = $img->copia();
        if ($ruta != "") {
            return $ruta;
            //echo "  Archivo subido";
        } else {
            return false;
        }
    } else {
        return false;
    }

//    $ruta = $img->destino . "/" . $img->nombre;
//    return $ruta;
}

/**
 * Retorna el tiempo de sesio que tiene configurado por sesion
 * @return type
 */
function retornaTiempoDeSesionPorSesion() {
    switch (obtenerTipo()) {
        case "administrador":
            return obtenerValorInactividadAdministrador();
            break;
        case "gestorContenidos":
            return obtenerValorInactividadGestor();
            break;
        case "Junior":
        case "Senior":
        case "Coordinador":
            return obtenerValorInactividadTutores();
            break;
        case "alumno":
            return obtenerValorInactividadAlumno();
            break;
        case "padre":
            return obtenerValorInactividadPadre();
            break;
    }
}
/**
 * Imprime un script y lo ejecuta para saber el tiempo máximo por sesión
 */
function imprimeScriptDeTiempoMaxSesion() {
    $tiempo = retornaTiempoDeSesionPorSesion();
    echo <<<html
    <script type="text/javascript">
        tiempoMaxSesion = $tiempo;
        fw = true;
    </script>
html;
}

/**
 * Funcion que recibe una fecha y la retorna en el formato adecuado
 * para insertarla en la base de datos PostgreSql
 * @param type $fecha
 * @return boolean
 */
function preparaFechaBaseDatos($fecha) {    
    if (preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $fecha, $matches)) {
        //dd/mm/yyyy
        return $matches[3]."-".$matches[2]."-".$matches[1];
    } else if (preg_match("/([0-9]{2})\-([0-9]{2})\-([0-9]{4})/", $fecha, $matches)) {
        //dd-mm-yyyy
        return $matches[3]."-".$matches[2]."-".$matches[1];
    } else if (preg_match("/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/", $fecha, $matches)) {
        //yyyy-mm-dd
        return $fecha;
    } else if (preg_match("/([0-9]{4})\/([0-9]{2})\/([0-9]{2})/", $fecha, $matches)) {
        //yyyy/mm/dd
        return $matches[1]."-".$matches[2]."-".$matches[3];
    } else {
        return false;
    }
}

?>