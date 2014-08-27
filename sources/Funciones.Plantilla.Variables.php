<?php

/*
 * Autor: José manuel Nieto Gómez
 * Fecha de Creación: 27 de Agosto de 2014
 * Objetivo: API para almacenar variables de los contenidos por serie.
 */

function setMSValues($idAlumno, $idElemento, $contexto, $variables) {

    //Id serie
    $idSerie = getIdSerie($idElemento);

    if ($idSerie != NULL) {
        //Guardar y validar variables una por una
        $variables = json_decode($variables);
        foreach ($variables as $variable => $valor) {
            
        }
    } else {
        //Error no existe la serie
    }
}

function getMSValues($idAlumno, $idElemento, $contexto, $variables) {
    $query = new Query("SG");

    //Prepara sql
    $whereVariables = "";

    //Si solo tiene una variable no concatemamos cadenas
    if (count($variables) == 1) {
        $whereVariables = "'" . $variables . "'";
    } else {
        //Si tiene mas de una variable concatenamos cadenas
        foreach ($variables as $variable) {
            $whereVariables .= " or  variable = '" . $variable . "'";
        }
    }

    $query->sql = <<<SQL
            SELECT id, variable, valor_variable, id_serie_aer 
            FROM variables_serie_alumno 
            WHERE id_serie_aer = (SELECT id_serie_aer from elemento_aer where id_elemento_aer = $idElemento)
              and id_alumno = $idAlumno
              and contexto = $contexto
              and (variable = $variables)
SQL;
    $resultado = $query->select("obj");

    return $resultado;
}

function getMSValuesJSON($idAlumno, $idElemento, $contexto, $variables) {
    json_encode(getMSValues($idAlumno, $idElemento, $contexto, $variables));
}

/**
 * Metodo que busca una variable relacionada a un alumno en una serie y un contexto
 * Si hay una coincidencia retorna el id de la variable, 
 * Si no existe retorna un nulo
 * @param type $idAlumno
 * @param type $variable
 * @param type $idSerie
 * @param type $contexto
 * @return null
 */
function verificarVariableUsuario($idAlumno, $variable, $idSerie, $contexto) {
    $query = new Query("SG");
    $query->sql = <<<SQL
            SELECT id_variable_serie_alumno 
            FROM variables_serie_alumno
            WHERE variable = '$variable' 
              and id_serie_aer = $idSerie
              and contexto = $contexto
              and id_alumno = $idAumno
SQL;
    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $res) {
            return $res->id_variable_serie_alumno;
        }
    } else {
        return null;
    }
}

function getIdSerie($idElemento) {
    //Identificar el id de la serie
    $query = new Query("SG");
    $query->sql = <<<SQL
            SELECT id_serie_aer from elemento_aer where id_elemento_aer = $idElemento
SQL;
    $resultado = $query->select("obj");
    $idSerie = "";

    if ($resultado) {
        foreach ($resultado as $r) {
            return $idSerie = $r->id_serie_aer;
        }
    } else {
        return null;
    }
}
?>

