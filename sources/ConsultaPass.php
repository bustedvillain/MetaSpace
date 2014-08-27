<?php header('Content-Type: text/html; charset=utf-8');
include 'Funciones.php';?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
          <?php

          
          $strEncriptada = nCrypt('áÑa');
          
          
          echo $strEncriptada;
          
          echo "<br>--------------------------------------------<br>";
          echo nDCrypt($strEncriptada);
          
          echo "Moodle:".  passBCrypt("admin");
          
  
  
$sql=new Query("SG");
  

$sql->sql="SELECT dp.id_datos_personales as id,
           dp.nombre_usuario, 
           dp.correo, 
           dp.contrasena,
           dp.tipo_usuario
           from datos_personales as dp
           ";


$resultado= $sql->select("obj");

foreach ($resultado as $valor) {
    echo "<p>".$valor->id.": -$valor->nombre_usuario-|-$valor->correo-|-$valor->nombre-|  pass:".nDCrypt($valor->contrasena)." | $valor->tipo_usuario</p>";
}
?>
        <p>ÑÑÑÑá</p>
    </body>
   
</html>

  
