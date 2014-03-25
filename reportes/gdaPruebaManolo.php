<?php

include("../sources/Funciones.php");
ob_start();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$arreglo = @excelToArray($_FILES["excel"], NULL, 3);
//var_dump($arreglo);

$arrExcel = @preparaReporteGestion($arreglo,  $arreglo["nFilas"], true);

//imprimeConsola("Asi queda");

//var_dump($procesaReporte);
$arrSG = array();
$arrSG['0']['nombre'] = "juan";
$arrSG['0']['ap'] = "tequila";
$arrSG['0']['am'] = "perez";
$arrSG['1']['nombre'] = "mary";
$arrSG['1']['ap'] = "aaaa";
$arrSG['1']['am'] = "bbbb";
//$arrSG['Maria Belen Grotz Arslanian']['nombre'] = "lala";
//$arrSG['Maria Belen Grotz Arslanian']['ap'] = "lele";
//$arrSG['Maria Belen Grotz Arslanian']['am'] = "lili";
//$arrSG['Mariela Sanchez']['nombre'] = "hec";
//$arrSG['Mariela Sanchez']['ap'] = "hecto";
//$arrSG['Mariela Sanchez']['am'] = "quiere";
//var_dump(devuelveArrayLetrasExcel());
//$arrUsernames= @arrUserNamesFromMatriz($arrSG);
//var_dump($arrSG);
//$matriz = matrizFromMatcheo($arrUsernames, $procesaReporte, $arrSG);
//descargaXLS("aaaa", $arrSG);
//descargaXLS("aaaa", $arrSG);
generaExcel("unaaa", $arrSG);
//var_dump($matriz);
//generaCSV("aaaaaa", $arrSG);
//descargaXLS("hajajaja", $matriz);
//imprimeConsola("Recorriendo en foreach");
//
//foreach($procesaReporte as $alumno => $campos){
//    imprimeConsola($alumno);
//    foreach($campos as $c){
//        echo $c . "    ";
//    }
//}
ob_end_flush();
?>