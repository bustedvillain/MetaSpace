<?php

/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 08 de Octubre del 2013
 * Objetivo: Funciones relacionadas al catalogo de asignatura
 */

/**
 * Inserta una asignatura a la base de datos del sistema de gestion
 * Retorna un true si se inserto correctamente
 * Retorna false si ya existia esta asignatura en la BD.
 * @param type $asignatura
 * @return boolean
 */
function insertaAsignatura($asignatura) {
    //Insert a SG 
    if (!reactivarAsignatura($asignatura)) {
        if (validaInsercionAsignatura($asignatura)) {
            //Crea objeto para realizar consultas de sistema de gestion
            $query = new Query("SG");
            $query->insert("asignaturas", "nombre_asignatura, status", "'$asignatura', 1");
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
 * Busca una asignatura y si la encuentra la activa * 
 * @param type $asignatura
 * @return boolean True si reactivo la habildiad, false si no encontro esa asignatura
 */
function reactivarAsignatura($asignatura) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "SELECT id_asignatura, nombre_asignatura from asignaturas where nombre_asignatura='$asignatura' and status=0";

    $asignaturas = $query->select("obj");

    if ($asignaturas) {
        foreach ($asignaturas as $asignatura) {
            $query->sql = "UPDATE asignaturas set status=1 where id_asignatura=" . $asignatura->id_asignatura;
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
 * Funcion que valida si ya existe una asignatura en la base de datos
 * Si esta ya existe retornara un falso
 * Si no existe retornara un true
 * @param type $asignatura
 * @return boolean
 */
function validaInsercionAsignatura($asignatura, $idAsignatura = NULL) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    //Si especifica un idHabildiad excluirlo en la busqueda
    if ($idAsignatura) {
        $query->sql = "SELECT nombre_asignatura from asignaturas where nombre_asignatura='$asignatura' and id_asignatura != $idAsignatura and status=1";
    } else {
        $query->sql = "SELECT nombre_asignatura from asignaturas where nombre_asignatura='$asignatura' and status=1";
    }
    $asignatura = $query->select("obj");

    if ($asignatura) {
        foreach ($asignatura as $asignatura) {
            return false;
        }
    } else {
        return true;
    }
}

/**
 * Consulta catalogo de asignatura
 */
function consultaAsignaturas() {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "SELECT id_asignatura, nombre_asignatura from asignaturas where status=1";
    $asignatura = $query->select("obj");

    if ($asignatura) {
        foreach ($asignatura as $asignatura) {
            echo <<<HTML
                <tr>
                    <td>
                        <a class="icon-edit editaAsignatura" id="$asignatura->id_asignatura" href="#editarModal" role="button" data-toggle="modal"></a>
                        <a class="icon-trash" href="borrarAsignatura.php?id=$asignatura->id_asignatura" onClick="return confirm('¿Está seguro?');"></a>
                    </td>
                    <td>$asignatura->nombre_asignatura</td>
                </tr>
HTML;
        }
    }
}

/**
 * Busca una asignatura y la retorna en un objeto JSON
 * @param type $idAsignatura
 * @return type
 */
function buscaAsignaturaJSON($idAsignatura) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "SELECT id_asignatura, nombre_asignatura from asignaturas where id_asignatura=$idAsignatura and status=1";
    $asignatura = $query->select("obj");

    if ($asignatura) {
        foreach ($asignatura as $asignatura) {
            return json_encode($asignatura);
        }
    }
}

/**
 * Actualiza el nombre de una asignatura a un id en especifico
 * @param type $idAsignatura
 * @param type $asignatura
 * @return boolean
 */
function actualizaAsignatura($idAsignatura, $asignatura) {
    //Valida si existe otra asignatura con este nombre
    if (validaInsercionAsignatura($asignatura, $idAsignatura)) {
        //Crea objeto para realizar consultas de sistema de gestion
        $query = new Query("SG");
        //Query to SG
        $query->sql = "UPDATE asignaturas set nombre_asignatura='$asignatura' where id_asignatura=$idAsignatura";
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
 * Borra una asignatura, acutaliando el status a 0
 * @param type $idAsignatura
 * @return boolean
 */
function borraAsignatura($idAsignatura) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "UPDATE asignaturas set status=0 where id_asignatura=$idAsignatura";
    if ($query->update($query->sql)) {
        return true;
    } else {
        return false;
    }
}
?>
