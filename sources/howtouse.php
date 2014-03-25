<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

    <?php

//require_once('./FirePHPCore/FirePHP.class.php');
//ob_start();
// 
//    
require_once 'Funciones.php';
//
//
//$sql=new Query("SG");
//#
////echo $sql->siguienteID("reportes");
//$sqlC="SELECT nombre_pila ||' '||primer_apellido||' '||segundo_apellido as gato
//            FROM grupo_alumno g_p
//            JOIN alumnos al
//                    ON g_p.id_alumno = al.id_alumno
//            JOIN datos_personales d_p
//                    ON d_p.id_datos_personales = al.id_datos_personales
//            WHERE g_p.id_grupo = 6
//    ";
//$sql->sql=$sqlC;
//
//$resultado = $sql->select("obj");
//foreach ($resultado as $value) {
//    echo entidadesToAcentos($value->gato);
//    echo "<br>";
//}
//echo "-----------------<br>";
//echo uniqid();



//var_dump(matrizSeriesElementos(6,5,313));

//MOD para Consultas al Moodle
//SG para Consultas al Sistema de Gestion

//$sql->insert("test2",
//             "campo",
//             "7");


//$sql->update("UPDATE test2 SET campo=1111 where campo=1");


//$sql->delete("test2","campo = 6");


//$sql->sql="SELECT * from pg_am";
//$resultado= $sql->select("obj");
////
//echo var_dump($resultado);
//echo "<br>--------------------<br>";
//echo var_dump($resultado->numRegistros());

//foreach ($resultado as $res)
//{
//    echo $res->campo;
//    echo "<br/>";    
//}
//echo "----------------------";
////echo "<br/>";
////
//
//
//$resultado2= $sql->select("arr");
//
//
//echo $resultado2[2]['campo'];
        
//        $firephp = FirePHP::getInstance(true);
// 
//        $var = array('i'=>10, 'j'=>40);
//
//        $firephp->log($var, 'Iterators');


//if (isset($_COOKIE["user"]))
//  echo "Welcome " . $_COOKIE["user"] . "!<br>";
//else{
//  echo "Welcome guest!<br>";
//  setcookie("user", "Alex Porter", time()+60*3);
//  
//}



 
// $ret = enviarCorreoPassword2('rehoscript@gmail.com', "hola que ace");
//  echo ($ret? "TRUE" : "FALSE");
 

//$config_array  = parse_ini_file('Variables.ini'); 
////var_dump($config_array);
//$gato = array(array(
//    'A'  => "1",
//    'B'  => "2",
//    'C'  => "3",
//));
//
//var_dump( INI::read("Variables.ini"));
//
//INI::write("Variables.ini", $gato);
//
//var_dump( INI::read("Variables.ini"));

//var_dump( matrizSeriesElementos(1,1,1) );

//$array1 = array(1,2,3,4,5,6,7,8,9,0);
//
//$array2 = consultaDatosBitacoraProgreso(6,7);
//$result = array_merge($array1, $array2);

//$array1 = array("color" => "red", 2, 4);
//$array2 = array("a", "b", "color" => "green", "shape" => "trapezoid", 4);
//$result = array_merge($array1, $array2);
//
////var_dump($result);
//
//@descargaXLS("s", $result);

//obtenerPromedioSerie(3);

//
//echo tieneCursosAsignados(230);



//
//if(intval($_SERVER['CONTENT_LENGTH'])>0 && count($_POST)===0){
//    throw new Exception('PHP discarded POST data because of request exceeding post_max_size.');
//}
//
//echo $_SERVER['CONTENT_LENGTH'];


//$array =(convierteConsultaMatriz(19));
//
//foreach ($array as $key => $value) {
//    
//    echo "FILA:";
//    foreach ($value as $value2) 
//    {
//        $value2 = html_entity_decode($value2,ENT_QUOTES, "UTF-8");
//        echo $value2;
//    }
//    echo "<br>";
//} 


echo nCrypt("Admin01.!");
echo "<br>";
echo nCrypt("guismx1");
?>