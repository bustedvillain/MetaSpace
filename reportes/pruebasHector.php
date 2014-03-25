<?php
require '../sources/Funciones.php';
  
  
$sql = new Query('SG');
$sql->sql="SELECT correo,id_datos_personales from datos_personales LIMIT 1";
$resultado= $sql->select("arr");  
 

$resultCount = count($resultado);

echo var_dump($resultado);

echo "<br>";
echo "+++++++++++++++++++++++++++++++++++++++";
echo "<br>";

$arrayForMatcheo =  array();
for($i=0;$i<$resultCount;$i++)
{
    echo $resultado[$i]['id_datos_personales'];
    echo "<br>";
    
    $arrayForMatcheo[ $resultado[$i]['correo'] ]['correo']=$resultado[$i]['id_datos_personales'];
}

echo var_dump($arrayForMatcheo);





?>
