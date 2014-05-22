<?php

/* * 
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 04/10/2013
 * Consutas a moodle: $query = new Query("MOD");
 * Consultas a SGI: $query = new Query("SG");
 * Objetivo: Incluir los scripts relacionados a las funciones del administradofr
 */

//Cursos
include("Funciones.Admin.Cursos.php");
//Funciones para catalogos
include("Funciones.Admin.Catalogos.php");
//Funcion para usuarios
include("Funciones.Admin.Usuarios.php");
////Funciones para grupos
include("Funciones.Admin.Grupos.php");
include("Funciones.Admin.Grupos.2.php");
//Alta masiva
include("Funciones.Admin.AltaMasiva.php");


/**
 * Si no hay sesión del administrador se redirecciona a login
 */
function verificarSesionAdmnistrador(){
    if(!esAdministrador()){
        header("Location:../?msg=accessDenied");
    }        
}
/**
 * Si no hay sesión del gestor se redirecciona a login
 */
function verificarSesionGestor(){
    if(!esGestorContenido()){
        header("Location:../?msg=accessDenied");
    }
}
/**
 * Si no hay sesión del administrador o gestor se redirecciona a login
 */
function verificarSesionAdminOGestor(){
    if(!esGestorContenido() && !esAdministrador()){
        header("Location:../?msg=accessDenied");
    }
}
/**
 * Si no hay sesión del alumno se redirecciona a login
 */
function verificarSesionAlumno(){
    if(!esAlumno()){
        header("Location:../../?msg=accessDenied");
    }
}
/**
 * Si no hay sesión del padre se redirecciona a login
 */
function verificarSesionPadre(){
    if(!esPadre()){
        header("Location:../../?msg=accessDenied");
    }
}
/**
 * Si no hay sesión del padre se redirecciona a login
 */
function verificaSesionTutor(){
    if(!esJr() && !esSenior() && !esCoordinador()){
        header("Location:../../?msg=accessDenied");
    }
}
?>
