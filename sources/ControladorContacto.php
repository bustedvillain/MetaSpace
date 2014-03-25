<?php
/*
 * Autor: Héctor Morales Palma
 * Fecha de Creación: 11 de Diciemnbre de 2013
 * Objetivo: Validación de formulario contacto y envio de correo.
 */
require_once './Funciones.php';
if($_POST)
{
    
   $correo   = $_POST['correo'];
   $nombre   = $_POST['nombre'];
   $mensaje  = $_POST['mensaje'];
   
    if( !($nombre) )
    {
        redireccionaNombreFalta();
    }else if( !($correo) )
    {
        redireccionaCorreoFalta();
    }else if( !Utilidades::esEmail($correo))
    {
     redireccionaCorreoIncorrecto();   
    }
    else if( !($mensaje) )
    {
        redireccionaMensajeFalta();
    }else
    {
           (enviarMailComentario($nombre, $correo, $mensaje));
           
            header('Location:../frontweb/login/contacto.php?msg=mensajeEnviado');
           
    }
   
   
}
?>
