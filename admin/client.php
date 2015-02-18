<?php
// This client for local_wstemplate is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//

/**
 * XMLRPC client for Moodle 2 - local_wstemplate
 *
 * This script does not depend of any Moodle code,
 * and it can be called from a browser.
 *
 * @authorr Jerome Mouneyrac
 */

/// MOODLE ADMINISTRATION SETUP STEPS
// 1- Install the plugin
// 2- Enable web service advance feature (Admin > Advanced features)
// 3- Enable XMLRPC protocol (Admin > Plugins > Web services > Manage protocols)
// 4- Create a token for a specific user and for the service 'My service' (Admin > Plugins > Web services > Manage tokens)
// 5- Run this script directly from your browser: you should see 'Hello, FIRSTNAME'

/// SETUP
require_once '../sources/Funciones.php';
$token = TOKEN_WEBSERVICE_SCORM;
$domainname = DOMAIN_NAME_MOODLE;
$domainfile = DOMAIN_FILE_MOODLE;

/// FUNCTION NAME
$functionname = 'local_scormws_upload';

/// PARAMETERS
$idCurso = (int)$_POST['idCurso'];
//$idCurso = 21;
$idSeccion = (int)$_POST['idSeccion'];
$nombreActividad = $_POST['nombreActividad'];

if(isset($_POST['modulo'])){
    $modulo=$_POST['modulo'];
}else{
    $modulo=0;
}

if(isset($_POST['launch'])){
    $launch=$_POST['launch'];
}else{
    $launch=0;
}
if(isset($_POST['instance'])){
    $instance=$_POST['instance'];
}else{
    $instance=0;
}

/// UPLOAD FILE
$uploaddir = UPLOAD_DIR_MOODLE;
$uploadfile = $uploaddir . basename($_FILES['file']['name']);

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
    $fileurl = $domainfile . basename($_FILES['file']['name']);
} else {
    echo "Error al subir el archivo!\n";
    exit;
}
/// VERIFY SCORM PACKAGE
$za = new ZipArchive(); 
$za->open($uploadfile); 
$isManifest = false;
for( $i = 0; $i < $za->numFiles; $i++ ){ 
    $stat = $za->statIndex( $i ); 
    if(basename($stat['name']) == "imsmanifest.xml")
        $isManifest = true;
}

if(!$isManifest) {
    echo "Error: El paquete no contiene un manifiesto xml.";
    exit;
}

///// XML-RPC CALL
header('Content-Type: text/plain');
$serverurl = $domainname . '/webservice/xmlrpc/server.php'. '?wstoken=' . $token;
require_once('./curl.php');
$curl = new curl;

// PASO 1: Borra las actividades de la secciÃ³n del curso
$post = xmlrpc_encode_request('local_scormws_delete', array($idCurso, $idSeccion));
$resp = xmlrpc_decode($curl->post($serverurl, $post));
echo "xmlrpc_encode_request('local_scormws_delete', array($idCurso, $idSeccion));";
print_r($resp);
echo "Termina Paso 1";

// PASO 2: Sube la nueva actividad a la secciÃ³n del curso
$post = xmlrpc_encode_request($functionname, array($idCurso, $fileurl, $idSeccion, $nombreActividad));
$resp = xmlrpc_decode($curl->post($serverurl, $post));
echo "xmlrpc_encode_request($functionname, array($idCurso, $fileurl, $idSeccion, $nombreActividad));";
print_r($resp);
echo "Termina Paso 2";
?>
