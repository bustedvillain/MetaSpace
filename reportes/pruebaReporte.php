<?php
ob_start();
include '../sources/Funciones.php';

//$matriz = matrizSeriesElementos(6,5,313);
//$matriz2 = convierteConsultaMatriz(6);
//$arrUsernames = arrUserNamesFromMatriz($matriz);
//$datosAlumno = matrizFromDosMatrices($arrUsernames, $matriz, $matriz2);


//include '../sources/FuncionesReportes.php';
//include '../sources/FuncionesExcel.php';
//echo '-------------MatrizElementos<br/>';
//$matriz = matrizSeriesElementos(6,5,313);
//var_dump($matriz);
//
//$ba = null;
//foreach($matriz as $a=>$b){
//    echo 'a='.$a."<br/>";
//    foreach($b as $c=>$d){
//        echo 'c='.$c.'b='.$d."<br/>";
//
//    }
//}
//foreach($ba as $c=>$d){
//    echo $c.'++';
//}
//echo '-------------MatrizDatosPerso<br/>';
//$matriz2 = convierteConsultaMatriz(6);
//var_dump($matriz);
//matrizSeriesElementosR(26, 313);

//$arrUsernames = arrUserNamesFromMatriz($matriz);
//var_dump($arrUsernames);
//echo '-------------MatrizFInal<br/>';
//$matrizota = matrizFromDosMatrices($arrUsernames, $matriz, $matriz2);
//var_dump($matrizota);
//
//echo '-------------MATRIZOTA<br/>';
//var_dump(matrizFromMatcheo($arrUsernames, $arrExcel, $matrizota));


//echo'pas='.nDCrypt("QSEAm86y9a@bEsVsR*VY_Es6puxuH=Sm@FW86y9axPM_6Zm*}Vn&amp;ib2");
//echo'pas='.nCrypt("plantilla01");
//$arrUsernames = array('juan','mary','lala','esta');

$arrSG = array();
$arrSG['juan']['nombre'] = "juan";
$arrSG['juan']['ap'] = "tequila";
$arrSG['juan']['am'] = "perez";
$arrSG['mary']['nombre'] = "mary";
$arrSG['mary']['ap'] = "aaaa";
$arrSG['mary']['am'] = "bbbb";
$arrSG['lala']['nombre'] = "lala";
$arrSG['lala']['ap'] = "lele";
$arrSG['lala']['am'] = "lili";
$arrSG['esta']['nombre'] = "hec";
$arrSG['esta']['ap'] = "hecto";
$arrSG['esta']['am'] = "quiere";


$arrTodo = generaArrFromExcel(1, null, 4, ".", "arrExcel.xlsx");

//$arrTodo = generaArrFromDosExcel(1, null, 4, ".", "arrExcel.xlsx", 1, null, 4, ".", "arrExcel.xlsx");
//$arrTodo = getMatrizDeUsuarios();
//descargaXLS("aaaa", $arrTodo);
$arrUsernames = array_pop ($arrTodo); 
$arrExcel = array_pop($arrTodo);
echo '-------------Matris usernames<br/>';
var_dump($arrUsernames);
echo '-------------Matris dedos<br/>';
var_dump($arrExcel);
//$arrExcel = array();
//$arrExcel['juan']['q1']=10;
//$arrExcel['juan']['q2']=11;
//$arrExcel['juan']['q3']=12;
//$arrExcel['mary']['q1']=20;
//$arrExcel['mary']['q2']=21;
//$arrExcel['mary']['q3']=22;
//$arrExcel['lala']['q1']=30;
//$arrExcel['lala']['q2']=31;
//$arrExcel['lala']['q3']=32;
//$arrExcel['esta']['q1']=40;
//$arrExcel['esta']['q2']=41;
//$arrExcel['esta']['q3']=432;
echo '-------------Usernames<br/>';
var_dump($arrUsernames);
//echo '-------------Excel<br/>';
//var_dump($arrExcel);
//echo '-------------SG<br/>';
//var_dump($arrSG);
//echo '-------------Matcheo<br/>';
$matrizDescarga = matrizFromMatcheo($arrUsernames, $arrExcel, $arrSG);
var_dump($matrizDescarga);
//generaExcel("aaaa", $matrizDescarga,"hooy");
//echo '-------------Matricita<br/>';
//$matriz = null;
for($i=1; $i<=count($matrizDescarga); $i++){
    $matrizInd = @matrizIndividualDeMatrizota($matrizDescarga, $i);
    $matriz = $matrizInd;
    //aqui guardas
}
//descargaXLS("sss", $matriz);

//var_dump(@matrizFromMatcheo($arrUsernames, $arrExcel, $arrSG));

//descargaXLS("Hooka", $matrizDescarga);
//@descargarPDF("hooka", $matrizDescarga,"miarcha");
//generaCSV("aaaa", $matrizDescarga, ",");
//ob_end_flush();


//var_dump(convierteConsultaMatrizConNombreCompleto(6));
//echo passBCrypt("ghismx11",PASSWORD_DEFAULT);
?>