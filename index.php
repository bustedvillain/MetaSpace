<?php
$msj = "";
if($_GET["msg"]){
    $msj = "?msg=".$_GET["msg"];
}
header("location:frontweb/login/index.php".$msj);
?>
