<?php
//$arr = array();
//$arr[0]['username'] = "hec";
//$arr[1]['username'] = "omar";
//$arr[2]['username'] = "nava";
//$arr[0]['pass'] = "phec";
//$arr[1]['pass'] = "pomar";
//$arr[2]['pass'] = "pnava";
require_once "PHPExcel/PHPExcel/IOFactory.php";
//include_once 'Funciones.php';
//include_once './Funciones.Admin.Grupos.2.php';
//
//$arrRangos = array();
//$arrIds = array();
//
//array_push($arrRangos, "20-40");
//array_push($arrRangos, "1-3");
//array_push($arrIds, 8);
//array_push($arrIds, 9);
//array_push($arrIds, 47);
//array_push($arrIds, 48);
//array_push($arrIds, 49);

//getUsuariosParaSubida(null, $arrRangos, $arrIds);
//$matriz = getMatrizDeUsuarios(5, $arrRangos, $arrIds);
//$matriz = getMatrizDeUsuarios(1);
//generaExcel("Hooja", $matriz, "miarch2");
//@descargarCSV("Usuarios", $arr, ",", "administradores");
//descargarPDF("Usuarios", $matriz, "administradores");

/**
 * FUnción que crea un objeto de excelPhp para ser usado posteriormente
 * @param type $nombreArchivo
 * @param type $nombreHoja
 * @param type $matrizElementos
 * @return \PHPExcel
 */
function objetoExcel($nombreHoja, $matrizElementos) {
//    var_dump($matrizElementos);
    $arrLetras = devuelveArrayLetrasExcel();
    $cont = 2;
    $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
    //para el autosize  
    PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
    $rendererLibrary = 'MPDF54';
    $rendererLibraryPath = dirname(__FILE__) . '/' . $rendererLibrary;

    if (!PHPExcel_Settings::setPdfRenderer(
                    $rendererName, $rendererLibraryPath
            )) {
        die(
                'Hubo un problema al cargar el generador de PDF, contacte a su admisnitrador'
        );
    }
    
    ///
    error_reporting(E_ALL);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');
//    require_once '/PHPExcel/PHPExcel.php';
    $excelEscribe = new PHPExcel();
//    $excelEscribe->setActiveSheetIndex(0);
//    $excelEscribe->getProperties()->setCreator("Maarten Balliauw")
//							 ->setLastModifiedBy("Maarten Balliauw")
//							 ->setTitle("Office 2007 XLSX Test Document")
//							 ->setSubject("Office 2007 XLSX Test Document")
//							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
//							 ->setKeywords("office 2007 openxml php")
//							 ->setCategory("Test result file");
    $arrHeaders = array();
    foreach ($matrizElementos as $nColumna => $v) {
        $arrHeaders = $v;
        $contLetras = 0;
        foreach ($matrizElementos[$nColumna] as $valorCelda) {
            $valorCelda = html_entity_decode($valorCelda, ENT_COMPAT, "UTF-8");
//            $valorCelda = mb_convert_encoding($valorCelda , "UTF-8" , "HTML-ENTITIES");
//            $valorCelda = html_entity_decode($valorCelda, ENT_COMPAT, "cp1251");
//            $valorCelda = html_entity_decode($valorCelda, ENT_QUOTES, "UTF-8");
//            $valorCelda = html_entity_decode($valorCelda, ENT_COMPAT, "ISO-8859-1");
//            $valorCelda = utf8_encode($valorCelda);
//            $valorCelda = utf8_decode($valorCelda);
//            $valorCelda = iconv("UTF-8", "UTF-8", $valorCelda);
//            $valorCelda = html_entity_decode($valorCelda);
//            $valorCelda = utf8_encode($valorCelda);
//            $valorCelda = mb_convert_encoding($valorCelda,'utf-16LE','utf-8');
            $excelEscribe->getActiveSheet()->setCellValue($arrLetras[$contLetras] . $cont, $valorCelda);
            $contLetras++;
        }
        $cont++;
    }
    $contLetras = 0;
    foreach ($arrHeaders as $a => $b) {
        
        $excelEscribe->getActiveSheet()->setCellValue($arrLetras[$contLetras] . 1, "\xEF\xBB\xBF".$a);
        $excelEscribe->getActiveSheet()->getStyle($arrLetras[$contLetras] . 1, $a)->getFont()->setBold(true);
        $excelEscribe->getActiveSheet()->getColumnDimension($arrLetras[$contLetras])->setAutoSize(true);
        $contLetras++;
    }
    //Orientación de la página
    $excelEscribe->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    $excelEscribe->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);
// CambiarNombre a la hoja
//    echo date('H:i:s'), " Rename worksheet", EOL;
    $excelEscribe->getActiveSheet()->setTitle($nombreHoja);
    

    return $excelEscribe;
}

/**
 * Función que crea un documento en excel a partir de una matriz
 * @param type $nombreHoja Nombre de la hoja de excel
 * @param type $matrizElementos necesariamente debe tener los números del lado izquierdo y los headres del derecho por ejemplo 
 * $arr[0]['username'] = "hec";
 * $arr[1]['username'] = "omar";
 * $arr[2]['username'] = "nava";
 * $arr[0]['pass'] = "phec";
 * $arr[1]['pass'] = "pomar";
 * $arr[2]['pass'] = "pnava";
 * @param type $nombreArchivo Nombre del archivo
 * @param type $ruta Ruta del archivo
 * @return boolean True si se hizo correctamente o false en lo contrario
 */
function generaExcel($nombreHoja, $matrizElementos, $nombreArchivo = "archivo", $ruta = "./") {
    $excelEscribe = @objetoExcel($nombreHoja, $matrizElementos);
    // Save Excel 2007 file
    $objWriter = PHPExcel_IOFactory::createWriter($excelEscribe, 'Excel2007');
    $objWriter->save($ruta . $nombreArchivo . ".xls");
    return true;
}

/**
 * Función que crea un documento en excel a partir de una matriz
 * @param type $nombreHoja Nombre de la hoja de excel
 * @param type $matrizElementos necesariamente debe tener los números del lado izquierdo y los headres del derecho por ejemplo 
 * $arr[0]['username'] = "hec";
 * $arr[1]['username'] = "omar";
 * $arr[2]['username'] = "nava";
 * $arr[0]['pass'] = "phec";
 * $arr[1]['pass'] = "pomar";
 * $arr[2]['pass'] = "pnava";
 * @param type $delimitador puede ser , ; \t
 * @param type $nombreArchivo Nombre del archivo
 * @param type $ruta Ruta del archivo
 * @return boolean True si se hizo correctamente o false en lo contrario
 */
function generaCSV($nombreHoja, $matrizElementos, $delimitador = ",", $nombreArchivo = "archivo", $ruta = "./") {
    $delimitador = ",";
    $excelEscribe = @objetoExcel($nombreHoja, $matrizElementos);
    $objWriter = new PHPExcel_Writer_CSV($excelEscribe);
    $objWriter->setDelimiter($delimitador);
    $objWriter->setEnclosure('');
    $objWriter->setLineEnding("\r\n");
    $objWriter->setSheetIndex(0);

    $objWriter->save($ruta . $nombreArchivo . ".csv");
    return true;
}

/**
 * Función que crea un documento en excel a partir de una matriz
 * @param type $nombreHoja Nombre de la hoja de excel
 * @param type $matrizElementos necesariamente debe tener los números del lado izquierdo y los headres del derecho por ejemplo 
 * $arr[0]['username'] = "hec";
 * $arr[1]['username'] = "omar";
 * $arr[2]['username'] = "nava";
 * $arr[0]['pass'] = "phec";
 * $arr[1]['pass'] = "pomar";
 * $arr[2]['pass'] = "pnava";
 * @param type $delimitador puede ser , ; \t
 * @param type $nombreArchivo Nombre del archivo
 * @param type $ruta Ruta del archivo
 * @return boolean True si se hizo correctamente o false en lo contrario
 */
function generaPDF($nombreHoja, $matrizElementos, $delimitador = ",", $nombreArchivo = "archivo", $ruta = "./") {
    //para PDF
    $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
    $rendererLibrary = 'MPDF54';
    $rendererLibraryPath = dirname(__FILE__) . '/' . $rendererLibrary;

    if (!PHPExcel_Settings::setPdfRenderer(
                    $rendererName, $rendererLibraryPath
            )) {
        die(
                'Hubo un problema al cargar el generador de PDF, contacte a su admisnitrador'
        );
    }
    $excelEscribe = @objetoExcel($nombreHoja, $matrizElementos);
    $objWriter = PHPExcel_IOFactory::createWriter($excelEscribe, 'PDF');
    $objWriter = new PHPExcel_Writer_PDF($excelEscribe);
    $objWriter->setSheetIndex(0);
    $objWriter->save($ruta . $nombreArchivo . ".pdf");
    return true;
}

/**
 * Función que descarga un documento en excel a partir de una matriz
 * @param type $nombreHoja Nombre de la hoja de excel
 * @param type $matrizElementos necesariamente debe tener los números del lado izquierdo y los headres del derecho por ejemplo 
 * $arr[0]['username'] = "hec";
 * $arr[1]['username'] = "omar";
 * $arr[2]['username'] = "nava";
 * $arr[0]['pass'] = "phec";
 * $arr[1]['pass'] = "pomar";
 * $arr[2]['pass'] = "pnava";
 * @param type $nombreArchivo Nombre del archivo
 * @return boolean True si se hizo correctamente o false en lo contrario
 */
function descargaXLS($nombreHoja, $matrizElementos, $nombreArchivo = "archivo") {
    require_once dirname(__FILE__) . '/PHPExcel/PHPExcel.php';



    $excelEscribe = @objetoExcel($nombreHoja, $matrizElementos);
//    var_dump($excelEscribe);
//    var_dump($matrizElementos);
    
    // Save Excel 2007 file
//    $objWriter = PHPExcel_IOFactory::createWriter($excelEscribe, 'Excel2007');
    
//    var_dump($excelEscribe);
    //Headers del descargable
    $excelEscribe->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $nombreArchivo . '.xls"');
    header('Cache-Control: max-age=0');
    // En caso de usar IE 9
    header('Cache-Control: max-age=1');
    // si se sirve a  IE sobre SSL, lo siguiente es necesario
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter = PHPExcel_IOFactory::createWriter($excelEscribe, 'Excel5');
                
    $objWriter->save('php://output');
////    
    return true;
}

/**
 * Función que descarga un documento en csv a partir de una matriz
 * @param type $nombreHoja Nombre de la hoja de excel
 * @param type $matrizElementos necesariamente debe tener los números del lado izquierdo y los headres del derecho por ejemplo 
 * $arr[0]['username'] = "hec";
 * $arr[1]['username'] = "omar";
 * $arr[2]['username'] = "nava";
 * $arr[0]['pass'] = "phec";
 * $arr[1]['pass'] = "pomar";
 * $arr[2]['pass'] = "pnava";
 * @param type $delimitador puede ser , ; \t
 * @param type $nombreArchivo Nombre del archivo
 * @param type $ruta Ruta del archivo * @return boolean True si se hizo correctamente o false en lo contrario
 */
function descargarCSV($nombreHoja, $matrizElementos, $delimitador = ",", $nombreArchivo = "archivo") {
    $delimitador = ",";
    $excelEscribe = @objetoExcel($nombreHoja, $matrizElementos);
    $objWriter = new PHPExcel_Writer_CSV($excelEscribe);
    $objWriter->setDelimiter($delimitador);
    $objWriter->setEnclosure('');
    $objWriter->setLineEnding("\r\n");
    $objWriter->setSheetIndex(0);
//    $objWriter = "\xFF\xFE" . $objWriter;
mb_http_output( "UTF-8" );
    //Headers del descargable
//    header('Content-Type: application/vnd.ms-excel');
    header('Content-type: application/x-msdownload; charset=utf-8');
//    header('Content-type: application/x-msdownload; charset=utf-16');
    header('Content-Disposition: attachment;filename="' . $nombreArchivo . '.csv"');
    header('Cache-Control: max-age=0');
    // En caso de usar IE 9
    header('Cache-Control: max-age=1');
    // si se sirve a  IE sobre SSL, lo siguiente es necesario
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter->save('php://output');
    return true;
}

/**
 * Función que descarga un documento en PDF a partir de una matriz
 * @param type $nombreHoja Nombre de la hoja de excel
 * @param type $matrizElementos necesariamente debe tener los números del lado izquierdo y los headres del derecho por ejemplo 
 * $arr[0]['username'] = "hec";
 * $arr[1]['username'] = "omar";
 * $arr[2]['username'] = "nava";
 * $arr[0]['pass'] = "phec";
 * $arr[1]['pass'] = "pomar";
 * $arr[2]['pass'] = "pnava";
 * @param type $delimitador puede ser , ; \t
 * @param type $nombreArchivo Nombre del archivo
 * @param type $ruta Ruta del archivo * @return boolean True si se hizo correctamente o false en lo contrario
 */
function descargarPDF($nombreHoja, $matrizElementos,  $nombreArchivo = "archivo") {
    //para PDF
    $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
    $rendererLibrary = 'MPDF54';
    $rendererLibraryPath = dirname(__FILE__) . '/' . $rendererLibrary;

    if (!PHPExcel_Settings::setPdfRenderer(
                    $rendererName, $rendererLibraryPath
            )) {
        die(
                'Hubo un problema al cargar el generador de PDF, contacte a su admisnitrador'
        );
    }
    $excelEscribe = @objetoExcel($nombreHoja, $matrizElementos);
    $objWriter = PHPExcel_IOFactory::createWriter($excelEscribe, 'PDF');
    $objWriter = new PHPExcel_Writer_PDF($excelEscribe);
    $objWriter->setSheetIndex(0);

    //Headers del descargable
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment;filename="' . $nombreArchivo . '.pdf"');
    header('Cache-Control: max-age=0');
    // En caso de usar IE 9
    header('Cache-Control: max-age=1');
    // si se sirve a  IE sobre SSL, lo siguiente es necesario
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter->save('php://output');
    return true;
}
/**
 * Función que devuelve el array de las letras en excel para que la generación de 
 * @return type
 */
function devuelveArrayLetrasExcel() {
    $abc = array(
        "A", "B", "C", "D", "E", "F",
        "G", "H", "I", "J", "K", "L",
        "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X",
        "Y", "Z"
    );
    $arrNuevo = array();
    foreach($abc as $a){
        array_push($arrNuevo, $a);
    }
    foreach($abc as $a){
        foreach ($abc as $b){
            array_push($arrNuevo, $a.$b);
        }
    }
    return $arrNuevo;
}
/**
 * Función que devuelve los headers para que funcione el excel
 */
function headersExcel() {
    error_reporting(E_ALL);
    ini_set('display_errors', FALSE);
    ini_set('display_startup_errors', FALSE);
    define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
    require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';
}
//ob_end_flush();
?>