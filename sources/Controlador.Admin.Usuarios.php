<?php 
//Control de cambios #&
//Autor:Omar Nava
//Objetivo: Left join a tabla de usuarios
//30-dic-2013
include("Funciones.php");
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 22 de Octubre del 2013
 * Realiza consultas retornado resultados en objetos JSON
 */

/**
 * CHANGE CONTROL 0.99.7
 * FECHA: 9 DE ENERO DEL 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJETIVO: FUNCION PARA RETORNA URL IMAGEN
 */
if($_POST){
    //Consulta que desea realizar
    $consulta = $_POST["consulta"];
    $atributo=$_POST["atributo"];
    switch($consulta){
        case "verificaUsuario":
            echo consultaUsuarioJSON($atributo);
            break;
        case "verificaUsuarioEditando":
            echo consultaUsuarioJSON($atributo, $_POST["id_datos_personales"]);
            break;
        case "verificaCorreo":
            echo consultaCorreoJSON($atributo);
            break;
        case "verificaCorreoEditando":
            echo consultaCorreoJSON($atributo, $_POST["id_datos_personales"]);
            break;
        case "consultaDatosPersonales":
            echo consultaDatosPersonalesJSON($atributo);
            break;
        case "getAlumnosEscuela":
            echo getAlumnosEscuelaJSON($atributo);
            break;
        case "getAlumnosEmpresa":
            echo getAlumnosEmpresaJSON($atributo);
            break;
        //inicia control de cambios #6
        case "combosGradoEscolar":
            echo getGradosDeNivelJSON($atributo);
            break;
        case "comboGrupos":
            echo getCursosDeEscuelaJSON($atributo);
            break;
        case "tablaPorGrupo":
            echo getTablaPorGrupo($atributo);
            break;
        //termina control de cambios #6
        /**
         * CHANGE CONTROL 0.99.7
         * FECHA: 9 DE ENERO DEL 2014
         * AUTOR: JOSE MANUEL NIETO GOMEZ
         * OBJETIVO: FUNCION PARA RETORNA URL IMAGEN
         */
        case "urlImagen":
            echo rutaFotoDeIdDatosPersonales($atributo);
            break;
        /**
         * CHANGE CONTROL 0.99.8
         * FECHA DE MODIFICACIÓN: 20 DE ENERO DEL 2014
         * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
         * OBJETIVO: CONTROLADOR QUE CONSULTA LA EXISTENCIA DE UN USUARIO EN MOODLE
         */
        case "verificaUsuarioMoodle":
            echo verificarUsuarioMoodleJSON($atributo);
            break;
        
    }
}

