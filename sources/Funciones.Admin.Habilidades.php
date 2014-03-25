<?php

/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 08 de Octubre del 2013
 * Objetivo: Funciones sobre Catalogos de Habilidades
 * Consutas a moodle: $query = new Query("MOD");
 * Consultas a SGI: $query = new Query("SG");
 */

/**
 * Inserta una habilidad a la base de datos del sistema de gestion
 * Retorna un true si se inserto correctamente
 * Retorna false si ya existia esta habilidad en la BD.
 * @param type $habilidad
 * @return boolean
 */
function insertaHabilidad($habilidad) {
    //Insert a SG 
    if (!reactivarHabilidad($habilidad)) {
        if (validaInsercionHabilidad($habilidad)) {
            //Crea objeto para realizar consultas de sistema de gestion
            $query = new Query("SG");
            $query->insert("habilidades", "nombre_habilidad, status", "'$habilidad', 1");
//            exit("INSERT NORMAL");
            return true;
        } else {
//            exit( "Error al insertar, duplicidad de datos");
            return false;
        }
    } else {
        return true;
    }
}

/**
 * Busca una habilidad y si la encuentra la activa * 
 * @param type $habilidad
 * @return boolean True si reactivo la habildiad, false si no encontro esa habilidad
 */
function reactivarHabilidad($habilidad) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "SELECT id_habilidad, nombre_habilidad from habilidades where nombre_habilidad='$habilidad' and status=0";

    $habilidades = $query->select("obj");

    if ($habilidades) {
        foreach ($habilidades as $habilidad) {
            $query->sql = "UPDATE habilidades set status=1 where id_habilidad=" . $habilidad->id_habilidad;
            if ($query->update($query->sql)) {
//               exit("HICE UPDATE!");
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}

/**
 * Funcion que valida si ya existe una habilidad en la base de datos
 * Si esta ya existe retornara un falso
 * Si no existe retornara un true
 * @param type $habilidad
 * @return boolean
 */
function validaInsercionHabilidad($habilidad, $idHabilidad = NULL) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    //Si especifica un idHabildiad excluirlo en la busqueda
    if ($idHabilidad) {
        $query->sql = "SELECT nombre_habilidad from habilidades where nombre_habilidad='$habilidad' and id_habilidad != $idHabilidad and status=1";
    } else {
        $query->sql = "SELECT nombre_habilidad from habilidades where nombre_habilidad='$habilidad' and status=1";
    }
    $habilidades = $query->select("obj");

    if ($habilidades) {
        foreach ($habilidades as $habilidad) {
            return false;
        }
    } else {
        return true;
    }
}

/**
 * Consulta catalogo de habilidades
 */
function consultaHabilidades() {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "SELECT id_habilidad, nombre_habilidad from habilidades where status=1";
    $habilidades = $query->select("obj");

    if ($habilidades) {
        foreach ($habilidades as $habilidad) {
            echo <<<HTML
                <tr>
                    <td>
                        <a class="icon-edit editaHabilidad" id="$habilidad->id_habilidad" href="#editarModal" role="button" data-toggle="modal"></a>
                        <a class="icon-trash" href="borrarHabilidad.php?id=$habilidad->id_habilidad" onClick="return confirm('¿Está seguro?');"></a>
                    </td>
                    <td>$habilidad->nombre_habilidad</td>
                </tr>
HTML;
        }
    }
}

/**
 * Busca una habilidad y la retorna en un objeto JSON
 * @param type $idHabilidad
 * @return type
 */
function buscaHabilidadJSON($idHabilidad) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "SELECT id_habilidad, nombre_habilidad from habilidades where id_habilidad=$idHabilidad and status=1";
    $habilidades = $query->select("obj");

    if ($habilidades) {
        foreach ($habilidades as $habilidad) {
            return json_encode($habilidad);
        }
    }
}

/**
 * Actualiza el nombre de una habilidad a un id en especifico
 * @param type $idHabilidad
 * @param type $habilidad
 * @return boolean
 */
function actualizaHabilidad($idHabilidad, $habilidad) {
    //Valida si existe otra habilidad con este nombre
    if (validaInsercionHabilidad($habilidad, $idHabilidad)) {
        //Crea objeto para realizar consultas de sistema de gestion
        $query = new Query("SG");
        //Query to SG
        $query->sql = "UPDATE habilidades set nombre_habilidad='$habilidad' where id_habilidad=$idHabilidad";
        if ($query->update($query->sql)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Borra una habilidad, acutaliando el status a 0
 * @param type $idHabilidad
 * @return boolean
 */
function borraHabilidad($idHabilidad) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "UPDATE habilidades set status=0 where id_habilidad=$idHabilidad";
    if ($query->update($query->sql)) {
        return true;
    } else {
        return false;
    }
}



?>
