<?php
/*
 * Autor: Héctor Morales Palma
 * Fecha de Creación: 6 de Noviembre del 2013
 * Objetivo: Controlador para recuperar el password
 */
require_once './Funciones.php';
if($_POST)
{
   $correo = $_POST["correo"];
   
   if(!Utilidades::esEmail($correo))
   {
     redireccionaNoCorreoValidoRecuperaLogin();  
   }
   
   if($correo)
   {
     enviarContrasena($correo);
       
   }
   
}
?>
