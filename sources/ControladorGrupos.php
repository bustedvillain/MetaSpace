<?php
/*
 * Autor: Héctor Morales Palma
 * Fecha de Creación: 10 de Enero de 2014
 * Objetivo: Controlador que retorna información en JSON del alumnos 
 * pertenecientes a un grupo y JSON de padres pertenecientes a un grupo
 */
require 'Funciones.php';
if($_POST){
    
    if($_POST['tipo'] == 'alumno')
    {
        echo regresaJSONGRUPOS($_POST['idGrupo']);
    }
    else if($_POST['tipo'] == 'padre')
    {
        echo regresaJSONPadresGrupo($_POST['idGrupo']);
    }
    
}
?>
