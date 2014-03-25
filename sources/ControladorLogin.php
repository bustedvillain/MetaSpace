<?php

include("./Funciones.php");
if($_POST)
{
    $usuarioCorreo = $_POST["nombre"];
//    $pass =  iconv('ISO-8859-1','UTF-8//TRANSLIT',$_POST["clave"]);
    $pass = ($_POST["clave"]);
    
    if( !($usuarioCorreo) )
    {
        redireccionaUsuarioFalta();
    }else if( !($pass) )
    {
        redireccionaPasswordFalta();
    }else
    {
        if($_POST["recordarme"]){
            $_SESSION["reco"]= 1;
        }
        construyeSesion($usuarioCorreo, $pass);
    }
}

?>
