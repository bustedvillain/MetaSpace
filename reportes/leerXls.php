<?php
//Librerias necesarias para funcionamiento
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';
//Fin Librerias


if (!file_exists("l1.xlsx")) {
	exit("Please run 05featuredemo.php first." . EOL);
}
$objPHPExcel = PHPExcel_IOFactory::load("l1.xlsx");
$cont = 2;
$arrUsernames = array();
$arrExcel = array();
$arrSG = array();

$valorNext = $objPHPExcel->getActiveSheet()->getCell('A'.$cont)->getValue();
while($valorNext != null)
{
    $valorCelda = $objPHPExcel->getActiveSheet()->getCell('A'.$cont)->getValue();
    array_push($arrUsernames, $valorCelda);
    $arrExcel[$valorCelda]['username'] = $objPHPExcel->getActiveSheet()->getCell('A'.$cont)->getValue();
    $arrExcel[$valorCelda]['calificacion'] = $objPHPExcel->getActiveSheet()->getCell('B'.$cont)->getValue();
    $cont ++;
    $valorNext = $objPHPExcel->getActiveSheet()->getCell('A'.$cont)->getValue();
}
//hago la consulta y la devuelvo
$arrSG['Hector'] = 'morales';
$arrSG['me'] = 'morales1';
$arrSG['ama'] = 'morales2';
$arrSG['jaja'] = 'morales3';
$arrSG['jeje'] = 'morales4';
$arrSG['jiji'] = 'morales5';


$excelEscribe = new PHPExcel();
$docNuevo = "matcheo.xlsx";
$cont = 1;
$excelEscribe->getActiveSheet()->setCellValue('A' . $cont, 'Username');
$excelEscribe->getActiveSheet()->setCellValue('B' . $cont, 'Apellido');
$excelEscribe->getActiveSheet()->setCellValue('C' . $cont, 'Calif');
$cont ++;
foreach($arrUsernames as $a)
{
    $excelEscribe->getActiveSheet()->setCellValue('A' . $cont, $arrExcel[$a]['username']);
    $excelEscribe->getActiveSheet()->setCellValue('B' . $cont, $arrSG[$a]);
    $excelEscribe->getActiveSheet()->setCellValue('C' . $cont, $arrExcel[$a]['calificacion']);
    $cont ++;
}

// Set page orientation and size
$excelEscribe->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$excelEscribe->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);
// Rename worksheet
echo date('H:i:s') , " Rename worksheet" , EOL;
$excelEscribe->getActiveSheet()->setTitle('Reporte');
// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($excelEscribe, 'Excel2007');
//$objWriter->save($docNuevo);


//$objWriter3 = new PHPExcel_Writer_PDF($excelEscribe);
//$objWriter3->save("05featuredemo.pdf");



//$excelEscribe->setActiveSheetIndex(0);
//// Redirect output to a clientâ€™s web browser (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="miraaaaaa.xls"');
//header('Cache-Control: max-age=0');
//
//$objWriter2 = new PHPExcel_Writer_Excel2007($excelEscribe);
//$objWriter2->setOffice2003Compatibility(true);
//$objWriter2->save('php://output');
//exit;


$objWriter = new PHPExcel_Writer_CSV($excelEscribe);
$objWriter->setDelimiter(',');
$objWriter->setEnclosure('');
$objWriter->setLineEnding("\r\n");
$objWriter->setSheetIndex(0);

//$objWriter->save("05featuredemo.csv");


//para PDF
$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
$rendererLibrary = 'MPDF54';
$rendererLibraryPath = dirname(__FILE__).'/../sources/' . $rendererLibrary;

if (!PHPExcel_Settings::setPdfRenderer(
    $rendererName,
    $rendererLibraryPath
    )) {
    die(
        'Please set the $rendererName and $rendererLibraryPath values' .
        PHP_EOL .
        ' as appropriate for your directory structure'
    );
}


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF'); 
$objWriter = new PHPExcel_Writer_PDF($excelEscribe);
$objWriter->save("pdf-test.pdf");
//$pdfWriter = new PHPExcel_Writer_PDF($excelEscribe);
//$pdfWriter->save("primera.pdf");
?>
