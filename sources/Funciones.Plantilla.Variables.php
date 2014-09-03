<?php

/*
 * Autor: José manuel Nieto Gómez
 * Fecha de Creación: 27 de Agosto de 2014
 * Objetivo: API para almacenar variables de los contenidos por serie.
 */

/**
 * Funcion que almacena variables de alumno/serie/contexto en la base de datos
 * @param type $idAlumno
 * @param type $idElemento
 * @param type $contexto en el que se ejecuta el contenido: 1(asignacion de cursos), 2(ruta propia), 3(serie indepediente)
 * @param type $variables
 * @return boolean retorna bandera si se guardo satisfactoriamente o si ocurrio algun error
 */
function setMSValues($idAlumno, $idElemento, $contexto, $variables) {
    
    //Id serie
    if (($idSerie = getIdSerie($idElemento)) != NULL) {
        //Guardar y validar variables una por una
        foreach ($variables as $variable => $valor) {
            //Verificar si se hace insert o update
            if (($idVariable = verificarVariableUsuario($idAlumno, $variable, $idSerie, $contexto)) != NULL) {
                //Actualizar valor de variable 
                $query = new Query("SG");
                $query->sql = "UPDATE variables_serie_alumno set valor_variable='$valor' where id_variable_serie_alumno = $idVariable";
                $query->update($query->sql);                
            } else {
                //Insertar nueva variable
                $query = new Query("SG");
                $query->insert("variables_serie_alumno", "variable, valor_variable, contexto, id_serie_aer, id_alumno", "'$variable', '$valor', $contexto, $idSerie, $idAlumno");                
            }
        }
        return true;
    } else {
        echo "Error no existe serie";
        //Error no existe la serie
        return false;
    }
}

/**
 * Almacena variables de alumnos/series/contexto y lo retorna la respuesta en un objeto JSON
 * @param type $idAlumno
 * @param type $idElemento
 * @param type $contexto
 * @param type $variables
 * @return type
 */
function setMSValuesJSON($idAlumno, $idElemento, $contexto, $variables) {
    return json_encode(setMSValues($idAlumno, $idElemento, $contexto, $variables));
}

/**
 * Metodo que consulta las variables solicitadas en un arreglo relacionadas
 * aun Alumno/Serie/Contexto
 * @param type $idAlumno
 * @param type $idElemento
 * @param type $contexto en donde se este ejecutando la serie: 1(asignacion de curso), 2(ruta propia), 3(serie independiente)
 * @param type $variables
 * @return type
 */
function getMSValues($idAlumno, $idElemento, $contexto, $variables) {
    $query = new Query("SG");

    //Prepara sql
    $whereVariables = "";
    
    //Bandera para identificar primer variable
    $primer = true;

    foreach ($variables as $variable) {
        if ($primer === true) {
            $whereVariables .= " variable = '" . $variable . "'";
            $primer = false;
        } else {
            $whereVariables .= " or  variable = '" . $variable . "'";
        }
    }

    $query->sql = <<<SQL
            SELECT id_variable_serie_alumno, variable, valor_variable, id_serie_aer 
            FROM variables_serie_alumno 
            WHERE id_serie_aer = (SELECT id_serie_aer from elemento_aer where id_elemento_aer = $idElemento)
              and id_alumno = $idAlumno
              and contexto = $contexto
              and ($whereVariables)
SQL;
    
    $resultado = $query->select("obj");
    
    $devArreglo = array();
    foreach($resultado as $res){
        $devArreglo[$res->variable] = $res-> valor_variable;
    }

    return $devArreglo;
}

/**
 * Metodo que consulta las variables solicitadas en un arreglo relacionadas
 * aun Alumno/Serie/Contexto. Retorna las variables en un objeto JSON
 * @param type $idAlumno
 * @param type $idElemento
 * @param type $contexto
 * @param type $variables
 * @return type
 */
function getMSValuesJSON($idAlumno, $idElemento, $contexto, $variables) {
    return json_encode(getMSValues($idAlumno, $idElemento, $contexto, $variables));
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
              and id_alumno = $idAlumno
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


/**
 * Obtiene el Id de una serie a partir de un elemento
 * @param type $idElemento
 * @return null
 */
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

/**
 * Funcion que elimina todas las variables en un ALUMNO/SERIE/CONTEXTO
 * @param type $idAlumno
 * @param type $idElemento
 * @param type $contexto
 * @return boolean
 */
function resetMSValues($idAlumno, $idElemento, $contexto) {
    
    $idSerie = getIdSerie($idElemento);
    $query = new Query("SG");    
    $resultado = $query->delete("variables_serie_alumno", "id_alumno = $idAlumno and id_serie_aer = $idSerie and contexto = $contexto");
    
    return true;
}

/**
 * Funcion que elmina todas las variables en un ALUMNO/SERIE/CONTEXTO y retorna respuesta en un valor JSON
 * @param type $idAlumno
 * @param type $idElemento
 * @param type $contexto
 * @return type
 */
function resetMSValuesJSON($idAlumno, $idElemento, $contexto){
    return json_encode(resetMSValues($idAlumno, $idElemento, $contexto));
}
?>

