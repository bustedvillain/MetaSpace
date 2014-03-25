<?php

/*
 * Fecha  de creación: 29 de Noviembre del 2013
 * Autor: José Manuel Nieto Gómez
 * Objetivo: Prepara la matriz de progreso de alumno
 */

/**
 * @deprecated 
 * Función que concatena las filas y columnas de los reportes de Gestión Educativa
 * @param type $arregloExcel
 * @param type $nFilas
 * @param type $debug
 * @return array
 */
function preparaReporteGestion($arregloExcel, $nFilas, $debug = NULL) {
    //($debug != NULL) ? imprimeConsola("prepara datos personles") : "";

    $arregloProcess = array();
    $campoRelAlumno = "Nombrecompletodelusuario";

    for ($i = 1; $i < $nFilas; $i++) {
//        var_dump($arregloExcel["arreglo"][$i]);       
        if (array_key_exists($arregloExcel["arreglo"][$i][$campoRelAlumno], $arregloProcess)) {
            //Curso
            array_push($arregloProcess[$arregloExcel["arreglo"][$i][$campoRelAlumno]], $arregloExcel["arreglo"][$i]["Curso"]);
            array_push($arregloProcess[$arregloExcel["arreglo"][$i][$campoRelAlumno]], $arregloExcel["arreglo"][$i]["Hora"]);
            array_push($arregloProcess[$arregloExcel["arreglo"][$i][$campoRelAlumno]], $arregloExcel["arreglo"][$i]["DirecciónIP"]);
            array_push($arregloProcess[$arregloExcel["arreglo"][$i][$campoRelAlumno]], $arregloExcel["arreglo"][$i]["Acción"]);
            array_push($arregloProcess[$arregloExcel["arreglo"][$i][$campoRelAlumno]], $arregloExcel["arreglo"][$i]["Información"]);
        } else {
            $arregloProcess[$arregloExcel["arreglo"][$i][$campoRelAlumno]] = array();
            array_push($arregloProcess[$arregloExcel["arreglo"][$i][$campoRelAlumno]], $arregloExcel["arreglo"][$i]["Curso"]);
            array_push($arregloProcess[$arregloExcel["arreglo"][$i][$campoRelAlumno]], $arregloExcel["arreglo"][$i]["Hora"]);
            array_push($arregloProcess[$arregloExcel["arreglo"][$i][$campoRelAlumno]], $arregloExcel["arreglo"][$i]["DirecciónIP"]);
            array_push($arregloProcess[$arregloExcel["arreglo"][$i][$campoRelAlumno]], $arregloExcel["arreglo"][$i]["Acción"]);
            array_push($arregloProcess[$arregloExcel["arreglo"][$i][$campoRelAlumno]], $arregloExcel["arreglo"][$i]["Información"]);
        }
    }

    return $arregloProcess;
}

/**
 * CHANGE CONTROL 0.99.7
 * FECHA DE MODIFICACIÓN: 13 DE ENERO DEL 2014
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * OBJETIVO: FUNCION QUE GENERA LAS URL PARA GENERAR REPORTES
 */

/**
 * Funcion que genera las urls para ver los reportes dentro de un curso
 * @param type $idCursoAbierto
 * @return type
 */
function generaUrls($idCursoAbierto){
    $idCursoMoodle = consultaIdMoodle($idCursoAbierto);
    
    //Evaluacion Diagnostica
    if(($idModulo = get_moodle_quiz($idCursoMoodle, EVALUACION_DIAGNOSTICA)) != false){
        $urlEDI = IP_SERVER_PUBLIC . "moodle/mod/quiz/report.php?id=$idModulo&mode=overview";
    }else{
        $urlEDI = false;
    }
    
    //Evaluacion Final
    if(($idModulo = get_moodle_quiz($idCursoMoodle, EVALUACION_FINAL)) != false){
        $urlEFI = IP_SERVER_PUBLIC . "moodle/mod/quiz/report.php?id=$idModulo&mode=overview";
    }else{
        $urlEFI = false;
    }
    
    //Evaluacion de Desempeño
    if(($idModulo = get_moodle_quiz($idCursoMoodle, EVALUACION_DESEMPEÑO)) != false){
        $urlEDE = IP_SERVER_PUBLIC . "moodle/mod/quiz/report.php?id=$idModulo&mode=overview";
    }else{
        $urlEDE = false;
    }
    
    //Autoevaluacion
    if(($idModulo = get_moodle_quiz($idCursoMoodle, AUTOEVALUACION)) != false){
        $urlAE = IP_SERVER_PUBLIC . "moodle/mod/quiz/report.php?id=$idModulo&mode=overview";
    }else{
        $urlAE = false;
    }
    
    return array(
        "urlEDI" => $urlEDI,
        "urlEFI" => $urlEFI,
        "urlEDE" => $urlEDE,
        "urlAUE" => $urlAE,
        "aprov"  => false   
            );
}

/**
 * Funcion que busca un examen en un curso y retorna el id del modulo
 * Si returna un false es que no encontró nada
 * @param type $id_moodle_course
 * @param type $quiz
 * @return boolean
 */
function get_moodle_quiz($id_moodle_course, $quiz){
    $quiz = strtolower($quiz);
    $query = new Query("MOD");
    $query->sql = <<<sql
         SELECT m.id
         FROM mdl_quiz q, mdl_course_modules m
         WHERE q.id = m.instance 
           and q.course = $id_moodle_course
           and m.module = 16
           and lower(q.name) like '%$quiz%'
sql;
    
    $resultSet = $query->select();
    
    if($resultSet){
        foreach($resultSet as $result){
            return $result->id;
        }
    }else{
        return false;
    }
}

/**
 * Función que genera listado de reportes en la FRONTWEB del tutor
 * @param type $idCursoAbierto
 * @return null
 */
function generarLINKSReportes($idCursoAbierto)
{
    if( !is_numeric($idCursoAbierto) )
        return NULL;
    
    $arrayArreglosURL = generaUrls($idCursoAbierto);
    
    
    $HTML = "";
    foreach ($arrayArreglosURL as $key => $url) 
    {
        $nombreReporte =  matchReporteEstandarNombre($key);
        if($url == false)
        {
            $urlSinReporte = "#";
            if($nombreReporte == "Aprovechamiento")
                $mensaje = "Este reporte se genera descargado el reporte  Final y Diagnostico";
            else
                $mensaje = "Este Reporte no se encuentra en Moodle";
                
            $HTML .= '<li><a href="'.$urlSinReporte.'" onclick="alert(\''.$mensaje.'\')">'.$nombreReporte.'</a></li>';
        }
        else
        {
            $HTML .= '<li><a href="'.$url.'" target="_blank">'.$nombreReporte.'</a></li>';
        }
    }
    echo $HTML;
}
/**
 * Función que retorna el nombre Completo del reporte en base al nombre corto
 * @param type $nombreEstandar
 * @return string
 */
function matchReporteEstandarNombre($nombreEstandar)
{
    
    switch($nombreEstandar)
    {
        case "urlEDI":
            return 'Evaluaci&oacute;n diagnóstica';
        case "urlEFI":
            return 'Evaluaci&oacute;n final';
        case "urlEDE":
            return 'Evaluaci&oacute;n de desempe&ntilde;o';
        case "urlAUE":
            return 'Autoevaluaci&oacute;n';
        case "aprov":
            return 'Aprovechamiento';
        return "NO FOUND";
    }
}
/**
 * Función que genera carpeta de url en cada  niveles de tutores
 * ya sea Junior., Senior, Coordinador
 * @return type
 */
function generarLINKveReportes()
{
    if(esJr())
        return $carpeta ="jr";
    else if(esSenior())
        return $carpeta ="senior";
    else if(esCoordinador())
        return $carpeta ="coordinador";
}

?>
