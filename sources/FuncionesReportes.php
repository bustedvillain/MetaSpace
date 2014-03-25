<?php

/**
 * Genera un arreglo de usernames de acuerdo a una matriz que s ele mande
 * @param type $matriz
 * @return array
 */
function arrUserNamesFromMatriz($matriz) {
    $arrUsernames = array();
    foreach ($matriz as $a => $b) {
        array_push($arrUsernames, $a);
    }
    return $arrUsernames;
}

/**
 * Función que devuelve la matriz haciendo un matcheo de arrays uno de excel, otro del sistema y un arreglo como ídice
 * @param type $arrUsernames
 * @param type $arrExcel
 * @param type $arrSG
 * @return array
 */
function matrizFromMatcheo($arrUsernames, $arrExcel, $arrSG) {
//    echo '-------';
//    var_dump($arrUsernames);
//    echo '-------';
//    var_dump($arrExcel);
//    echo '-------';
//    var_dump($arrSG);
    $cont = 0;
    $m = array();
    $arrUsernames2 = array();
    foreach ($arrUsernames as $u) {
        if (key_exists($u, $arrSG)) {
            array_push($arrUsernames2, $u);
        }
    }
//    var_dump($arrUsernames2);
    foreach ($arrUsernames2 as $u) {


        foreach ($arrSG[$arrUsernames2[$cont]] as $a => $b) {
//            var_dump($b);
//            echo '***';
//            var_dump($a);
//            if (array_key_exists($u, $a)) {
//                echo 'here';
//            } else {
//                echo 'Error';
//            }
//            $m[$cont + 1][$a] = $arrSG[$b][$a];
            $m[$cont + 1][$a] = $arrSG[$arrUsernames2[$cont]][$a];
        }
//        echo '###############';
        foreach ($arrExcel[$arrUsernames2[$cont]] as $c => $d) {
//            echo 'Username=' . $arrUsernames2[$cont];
            if (key_exists($arrUsernames2[$cont], $arrSG)) {
//                echo 'lo halle';
//                var_dump($c);
//                echo '***';
//                var_dump($d);
                $m[$cont + 1][$c] = $arrExcel[$arrUsernames2[$cont]][$c];
            } else {
//                echo 'no lo vi';
            }
        }
        $cont++;
    }
//    var_dump($m);
    return $m;
}

/**
 * Función que devuelve la matriz haciendo un matcheo de arrays uno de excel, otro del sistema y un arreglo como ídice
 * @param type $arrUsernames
 * @param type $arrExcel
 * @param type $arrSG
 * @return array
 */
function matrizFromDosMatrices($arrUsernames, $arrExcel, $arrSG) {
    $cont = 0;
    $m = array();
    $arrUsernames2 = array();
    foreach ($arrUsernames as $u) {
        if (key_exists($u, $arrSG)) {
            array_push($arrUsernames2, $u);
        }
    }
    foreach ($arrUsernames2 as $u) {
        foreach ($arrSG[$arrUsernames[$cont]] as $a => $b) {
            $m[$u][$a] = $arrSG[$arrUsernames[$cont]][$a];
        }
        foreach ($arrExcel[$arrUsernames[$cont]] as $c => $d) {
            if (key_exists($arrUsernames[$cont], $arrSG)) {
                $m[$u][$c] = $arrExcel[$arrUsernames[$cont]][$c];
            } else {
                
            }
        }
        $cont++;
    }
    return $m;
}

/**
 * Función que transforma un excel en una matriz
 * @global array $arrUsernames 
 * @global type $arrExcel
 * @param type $posUsername Es donde sse encuentra lel username por ejemplo si está en la columna A será 1
 * @param type $posIniQ En caso de que tuvieramos que leer  columas desde una posición hasta la derecha colocar donde iniciamos ejemplo C = 3
 * @param type $posCalif  
 * @param type $posCalifEs donde sse encuentra la  por ejemplo si está en la columna A será 1
 * @param type $ruta
 * @param type $archivo
 * @return array Devuelve la matriz
 */
function generaArrFromExcel($posUsername, $posIniQ = null, $posCalif, $ruta = "./", $archivo) {
//    flush();
//    echo 'voy aqu1';
//    flush();

    $arrLetras = devuelveArrayLetrasExcel();
//var_dump($arrLetras);
    //Metas del excel
    error_reporting(E_ALL);
    ini_set('display_errors', FALSE);
    ini_set('display_startup_errors', FALSE);
//    define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
    //require_once dirname(__FILE__) . '/../Reportes/Classes/PHPExcel/IOFactory.php';
    require_once 'PHPExcel/PHPExcel/IOFactory.php';
    date_default_timezone_set('America/Mexico_City');
    //Para saber la longitud máxima de las columnas que se van a leer
//    if (!file_exists($ruta . "/" . $archivo)) {
//        exit("El archivo no existe." . EOL);
//    }
    $objPHPExcel = PHPExcel_IOFactory::load($archivo);
    $cont = 1; //Contador de Filas del excel
    flush();
//    echo 'voy aqui';
    $arrUsernames = array();
    $arrExcel = array();

    if (isset($posIniQ)) {
        $indice = $arrLetras[$posIniQ];
        $indice = $posIniQ;
        $valorNextC = $objPHPExcel->getActiveSheet()->getCell($arrLetras[$indice] . $cont)->getValue();
        while ($valorNextC != null && $valorNextC != "") {
            $indice++;
            $valorNextC = $objPHPExcel->getActiveSheet()->getCell($arrLetras[$indice] . $cont)->getValue();
            flush();
//    echo 'indice vale ='.$indice;
        }
        flush();
//    echo 'indice vale ='.$indice.'<br/>';
        $maxValorCol = $indice;
    }
    $cont = 2;
    $valorNext = $objPHPExcel->getActiveSheet()->getCell($arrLetras[$posUsername] . $cont)->getValue();
    while ($valorNext != null) {
        if (!in_array($objPHPExcel->getActiveSheet()->getCell($arrLetras[$posUsername - 1] . $cont)->getValue(), $arrUsernames)) {
            //echo  $objPHPExcel->getActiveSheet()->getCell($arrLetras[$posUsername - 1] . $cont)->getValue()."";
            array_push($arrUsernames, $objPHPExcel->getActiveSheet()->getCell($arrLetras[$posUsername - 1] . $cont)->getValue());
        }
        if (isset($posIniQ)) {
            for ($i = $posIniQ; $i <= $maxValorCol; $i++) {
                $header = $objPHPExcel->getActiveSheet()->getCell($arrLetras[$i - 1] . "1")->getValue();
//            $arrExcel[$cont - 2][$header] = $objPHPExcel->getActiveSheet()->getCell($arrLetras[$i-1] . $cont)->getValue();
//            $arrExcel[$arrUsernames[$cont - 2]][$header] = $objPHPExcel->getActiveSheet()->getCell($arrLetras[$i - 1] . $cont)->getValue();
                $arrExcel[$objPHPExcel->getActiveSheet()->getCell($arrLetras[$posUsername - 1] . $cont)->getValue()][$header] = $objPHPExcel->getActiveSheet()->getCell($arrLetras[$i - 1] . $cont)->getValue();
            }
        }
        ///Para cargar tambien la calificacion
        $header = $objPHPExcel->getActiveSheet()->getCell($arrLetras[$posCalif - 1] . "1")->getValue();
        $arrExcel[$objPHPExcel->getActiveSheet()->getCell($arrLetras[$posUsername - 1] . $cont)->getValue()][$header] = $objPHPExcel->getActiveSheet()->getCell($arrLetras[$posCalif - 1] . $cont)->getValue();

        $cont++;
        $valorNext = $objPHPExcel->getActiveSheet()->getCell($arrLetras[$posUsername] . $cont)->getValue();
//        echo 'contador vale ='.$cont;
    }
    $arrTodo = array();
    array_push($arrTodo, $arrExcel);
    array_push($arrTodo, $arrUsernames);
    return $arrTodo;
}

/**
 * Función que transforma un excel en una matriz
 * @global array $arrUsernames 
 * @global type $arrExcel
 * @param type $posUsername Es donde sse encuentra lel username por ejemplo si está en la columna A será 1
 * @param type $posIniQ En caso de que tuvieramos que leer  columas desde una posición hasta la derecha colocar donde iniciamos ejemplo C = 3
 * @param type $posCalif  
 * @param type $posCalifEs donde sse encuentra la  por ejemplo si está en la columna A será 1
 * @param type $ruta
 * @param type $archivo
 * @return array Devuelve la matriz
 */
function generaArrFromDosExcel($posUsername1, $posIniQ1, $posCalif1, $ruta1, $archivo1, $posUsername2, $posIniQ2, $posCalif2, $ruta2, $archivo2) {
//    flush();
//    echo 'voy aqu1';
//    flush();

    $arrLetras = devuelveArrayLetrasExcel();

//Metas del excel
    error_reporting(E_ALL);
    ini_set('display_errors', FALSE);
    ini_set('display_startup_errors', FALSE);
//    define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
//require_once dirname(__FILE__) . '/../Reportes/Classes/PHPExcel/IOFactory.php';
    require_once 'PHPExcel/PHPExcel/IOFactory.php';
    date_default_timezone_set('America/Mexico_City');
//Para saber la longitud máxima de las columnas que se van a leer
//if (!file_exists($ruta1 . "/" . $archivo1)) {
//    exit("El archivo no existe." . EOL);
//}
    $objPHPExcel1 = PHPExcel_IOFactory::load($archivo1);
    $cont = 1; //Contador de Filas del excel
    global $arrUsernames;
    global $arrExcel;
    $arrExcel1 = array();
    $arrExcel2 = array();

    if (isset($posIniQ1)) {
        $indice = $arrLetras[$posIniQ1];
        $indice = $posIniQ1;
        $valorNextC = $objPHPExcel1->getActiveSheet()->getCell($arrLetras[$indice] . $cont)->getValue();
        while ($valorNextC != null && $valorNextC != "") {
            $indice++;
            $valorNextC = $objPHPExcel1->getActiveSheet()->getCell($arrLetras[$indice] . $cont)->getValue();
        }
        $maxValorCol = $indice;
    }
    $cont = 2;
    $valorNext = $objPHPExcel1->getActiveSheet()->getCell($arrLetras[$posUsername1] . $cont)->getValue();
    while ($valorNext != null) {
        if (!in_array($objPHPExcel1->getActiveSheet()->getCell($arrLetras[$posUsername1 - 1] . $cont)->getValue(), $arrUsernames)) {
            array_push($arrUsernames, $objPHPExcel1->getActiveSheet()->getCell($arrLetras[$posUsername1 - 1] . $cont)->getValue());
        }
        if (isset($posIniQ1)) {
            for ($i = $posIniQ1; $i <= $maxValorCol; $i++) {
                $header = $objPHPExcel1->getActiveSheet()->getCell($arrLetras[$i - 1] . "1")->getValue();
                $arrExcel1[$objPHPExcel1->getActiveSheet()->getCell($arrLetras[$posUsername1 - 1] . $cont)->getValue()]["Final"] = $objPHPExcel1->getActiveSheet()->getCell($arrLetras[$i - 1] . $cont)->getValue();
            }
        }
        ///Para cargar tambien la calificacion
        $header = $objPHPExcel1->getActiveSheet()->getCell($arrLetras[$posCalif1 - 1] . "1")->getValue();
        $arrExcel1[$objPHPExcel1->getActiveSheet()->getCell($arrLetras[$posUsername1 - 1] . $cont)->getValue()]["Final"] = $objPHPExcel1->getActiveSheet()->getCell($arrLetras[$posCalif1 - 1] . $cont)->getValue();

        $cont++;
        $valorNext = $objPHPExcel1->getActiveSheet()->getCell($arrLetras[$posUsername1] . $cont)->getValue();


//        echo 'contador vale ='.$cont;
    }
//    echo '-------------useres 1<br/>';
//var_dump($arrUsernames);
////////////////////////////////////
////////////////////////////////////
////////////////////////////////////
//if (!file_exists($ruta1 . "/" . $archivo2)) {
//    exit("El archivo no existe." . EOL);
//}
    $objPHPExcel2 = PHPExcel_IOFactory::load($archivo2);
    $cont = 1; //Contador de Filas del excel



    if (isset($posIniQ2)) {
        $indice = $arrLetras[$posIniQ2];
        $indice = $posIniQ2;
        $valorNextC = $objPHPExcel2->getActiveSheet()->getCell($arrLetras[$indice] . $cont)->getValue();
        while ($valorNextC != null && $valorNextC != "") {
            $indice++;
            $valorNextC = $objPHPExcel2->getActiveSheet()->getCell($arrLetras[$indice] . $cont)->getValue();
        }
        $maxValorCol = $indice;
    }
    $cont = 2;
    $valorNext = $objPHPExcel2->getActiveSheet()->getCell($arrLetras[$posUsername2] . $cont)->getValue();
    while ($valorNext != null) {
        if (!in_array($objPHPExcel2->getActiveSheet()->getCell($arrLetras[$posUsername2 - 1] . $cont)->getValue(), $arrUsernames)) {
            array_push($arrUsernames, $objPHPExcel2->getActiveSheet()->getCell($arrLetras[$posUsername2 - 1] . $cont)->getValue());
        }
        if (isset($posIniQ2)) {
            for ($i = $posIniQ2; $i <= $maxValorCol; $i++) {
                $header = $objPHPExcel2->getActiveSheet()->getCell($arrLetras[$i - 1] . "1")->getValue();
                $arrExcel2[$objPHPExcel2->getActiveSheet()->getCell($arrLetras[$posUsername2 - 1] . $cont)->getValue()]["Diagnostico"] = $objPHPExcel2->getActiveSheet()->getCell($arrLetras[$i - 1] . $cont)->getValue();
            }
        }
        ///Para cargar tambien la calificacion
        $header = $objPHPExcel2->getActiveSheet()->getCell($arrLetras[$posCalif2 - 1] . "1")->getValue();
        $calif1 = $arrExcel1[$objPHPExcel1->getActiveSheet()->getCell($arrLetras[$posUsername2 - 1] . $cont)->getValue()]["Final"];
        $calif2 = $objPHPExcel2->getActiveSheet()->getCell($arrLetras[$posCalif2 - 1] . $cont)->getValue();
        $arrExcel2[$objPHPExcel2->getActiveSheet()->getCell($arrLetras[$posUsername2 - 1] . $cont)->getValue()]["Promedio"] = (($calif1) + ($calif2)) / 2;
        $arrExcel2[$objPHPExcel2->getActiveSheet()->getCell($arrLetras[$posUsername2 - 1] . $cont)->getValue()]["Diagnostico"] = $calif2;


        $cont++;
        $valorNext = $objPHPExcel2->getActiveSheet()->getCell($arrLetras[$posUsername2] . $cont)->getValue();
    }
//    echo '-------------Matriz 1<br/>';
//var_dump($arrExcel1);
//    echo '-------------Matriz 2<br/>';
//var_dump($arrExcel2);
    $arrExcel = matrizFromDosMatrices($arrUsernames, $arrExcel1, $arrExcel2);


    $arrTodo = array();
    array_push($arrTodo, $arrExcel);
    array_push($arrTodo, $arrUsernames);
    return $arrTodo;
}
/**
 * Convierte un entero a un nombre de serie como s01 
 * @param type $int
 * @return type
 */
function nombreSerieFromInt($int) {
    if ($int < 10) {
        return "s0" . $int;
    } else {
        return "s" . $int;
    }
}
/**
 * Convierte un entero a un nombre de elemento como c02
 * @param type $int
 * @return string
 */
function nombreElementoFromInt($int) {
    switch ($int) {
        case 1:
            return "a";
        case 2:
            return "p";
        case 3:
            return "i";
    }
}
/**
 * Devuelve un objeto de query con los idSeries de una unidad
 * @param type $idUnidad
 * @return type
 */
function idsSerieDeUnidad($idUnidad) {
    $query = new Query("SG");
    $query->sql = <<<SQL
        select id_serie_aer 
        from serie_aer
        where id_unidad = $idUnidad
            order by nivel_serie_aer
SQL;
    $resultado = $query->select("obj");
    return $resultado;
}
/**
 * Devuelve un objeto de query con los id Alumnos de un relCursoGrupo
 * @param type $idRelCursoGrupo
 * @return type
 */
function idsAlumnoDeIdRelCursoGrupo($idRelCursoGrupo) {
    $query = new Query("SG");
    $query->sql = <<<SQL
        select id_alumno 
        from progreso_alumno
        where id_rel_curso_grupo = $idRelCursoGrupo
SQL;
    $resultado = $query->select("obj");
    return $resultado;
}
/**
 * En la variable global de la matriz crea la matriz de elementos a través de un id de unidad y idRelCursoGrupo
 * @global type $matriz
 * @param type $idRelCursoGrupo
 * @param type $idUnidad
 */
function matrizSeriesElementosR($idRelCursoGrupo, $idUnidad) {
//    echo 'here';
    flush();
    $arrSeries = idsSerieDeUnidad($idUnidad);
//    echo'ArrSeries='.var_dump($arrSeries);
    $arrAlumnos = idsAlumnoDeIdRelCursoGrupo($idRelCursoGrupo);
//    echo'Iniciar foreach';
    flush();
    foreach ($arrAlumnos as $a) {
        llenaParteSerie($a->id_alumno, $arrSeries, $idRelCursoGrupo, $idUnidad);
//        echo'Fin primera llena parte serie';
        flush();
    }
    global $matriz;
    var_dump($matriz);
}
/**
 * Llena las parte de series en la matriz
 * @param type $idAlumno
 * @param type $arrSeries
 * @param type $idRelCursoGrupo
 * @param type $idUnidad
 */
function llenaParteSerie($idAlumno, $arrSeries, $idRelCursoGrupo, $idUnidad) {
    foreach ($arrSeries as $s) {
//        echo'Foreach de llena parte serie';
        flush();
        llenaParteElemento($idAlumno, $s->id_serie_aer, $idRelCursoGrupo, $idUnidad);
//        echo'Terminó un llena parte';
        flush();
    }
}
/**
 * Llena la parte de elementos en la matriz
 * @global type $matriz
 * @param type $idAlumno
 * @param type $idSerie
 * @param type $idRelCursoGrupo
 * @param type $idUnidad
 */
function llenaParteElemento($idAlumno, $idSerie, $idRelCursoGrupo, $idUnidad) {
    $query = new Query("SG");
    $query->sql = <<<SQL
        select p.id_elemento_aer as "idElemento", e.no_elemento_aer as "noElemento", s.id_serie_aer as "idSerie", s.nivel_serie_aer as "nivelSerie",
                p.calificacion as "calificacion", p.id_alumno as "idAlumno", d.correo, lower(substr(te.nombre_tipo,0,2)) as "tipo"
        from progreso_alumno p
        left join elemento_aer e
                on e.id_elemento_aer = p.id_elemento_aer
        left join serie_aer s
                on s.id_serie_aer = e.id_serie_aer
        left join alumnos a
                on p.id_alumno = a.id_alumno
        left join datos_personales d
                on d.id_datos_personales = a.id_datos_personales
        left join tipo_elemento te
                on e.id_tipo_elemento = te.id_tipo_elemento
        where p.id_rel_curso_grupo = $idRelCursoGrupo and s.id_unidad = $idUnidad and p.id_alumno = $idAlumno and s.id_serie_aer = $idSerie

        order by p.id_progreso_alumno, s.nivel_serie_aer 
SQL;
    $resultado = $query->select("obj");
    global $matriz;
    $cont = 0;
    $acum = 0;
    $nombreSerie = "";
    foreach ($resultado as $e) {
        if (isset($e->calificacion)) {
            $calif = $e->calificacion;
        } else {
            $calif = 1;
        }
//        echo 'Pondre en['.$e->correo.']['.$e->tipo.']el valor='.$calif.'<br/>';
        flush();
        ob_flush();
        $nombreSerie = nombreSerieFromInt($e->nivelSerie);
        $matriz[$e->correo][$nombreSerie . "-" . $e->tipo] = $calif;

        $cont++;
        $acum = $acum + $calif;
    }
    $matriz[$e->correo][$nombreSerie] = round($acum / $cont, 2);
//    echo '****';
}
/**
 * Genera la matriz de elementos para generar el reporte de un grupo
 * @param type $idGrupo
 * @param type $idCursoAbierto
 * @param type $idUnidad
 * @return int
 */
function matrizSeriesElementos($idGrupo, $idCursoAbierto, $idUnidad) {
    $idRelCursoGrupo = getidRelCursoGrupo2($idCursoAbierto, $idGrupo);
    $query = new Query("SG");
    $query->sql = <<<SQL
        select p.id_progreso_alumno
               ,p.id_elemento_aer as "idElemento", e.no_elemento_aer as "noElemento", s.id_serie_aer as "idSerie", s.nivel_serie_aer as "nivelSerie",
                p.calificacion as "calificacion", 
                (SELECT count(*)
			FROM bitacora_progreso
			WHERE tipo = 1
			AND id_progreso_alumno = p.id_progreso_alumno ) as intentos,
                p.id_alumno as "idAlumno", d.correo, lower(substr(te.nombre_tipo,0,2)) as "tipo"
        from progreso_alumno p
        left join elemento_aer e
                on e.id_elemento_aer = p.id_elemento_aer
        left join serie_aer s
                on s.id_serie_aer = e.id_serie_aer
        left join alumnos a
                on p.id_alumno = a.id_alumno
        left join datos_personales d
                on d.id_datos_personales = a.id_datos_personales
        left join tipo_elemento te
                on e.id_tipo_elemento = te.id_tipo_elemento

        where p.id_rel_curso_grupo = $idRelCursoGrupo and s.id_unidad = $idUnidad
        order by p.id_progreso_alumno, s.nivel_serie_aer
SQL;
    $resultado = $query->select("obj");
    $matriz = array();
    $contElem = 1;
    $acumElem = 0;
    $serieActual = -1; //serie que o existe
    $serieAnt = -1; //serie que o existe
    $idAlumnoAct = 0;
    $nombreS = "";
    $nombreAnt = "";
    $correoAnt = "";
    if ($resultado) {

        foreach ($resultado as $e) {

            if ($e->idAlumno != $idAlumnoAct) {
//                secho 'curre' . $e->idAlumno . "act" . $idAlumnoAct . "<br/>";
                if ($idAlumnoAct == 0) {
                    $idAlumnoAct = $e->idAlumno;
                } else {

                    $promedio = round($acumElem / $contElem, 2);
                    if ($promedio == 0) {
                        $promedio = 1;
                    }
                    $matriz[$correoAnt]["Promedio-" . $nombreAnt] = $promedio;
                    $serieActual = -1;
                    $idAlumnoAct = $e->idAlumno;

//                    $idAlumnoAct == 0;
                }
            }
//            echo 'Actual='.$serieActual."leido=".$e->idSerie;
//            var_dump($e);
            if ($e->idSerie != $serieActual) {
                if ($serieActual == -1) {
                    $serieActual = $e->idSerie;
                    $nombreS = nombreSerieFromInt(intval($e->nivelSerie));
                    $contElem = 1;
                    $acumElem = 0;
                } else {
                    $promedio = round($acumElem / $contElem, 2);
                    $nombreS = nombreSerieFromInt(intval($e->nivelSerie));
                    if ($promedio == 0) {
                        $promedio = 1;
                    }
                    $matriz[$e->correo]["Promedio-" . $nombreAnt] = $promedio;
                    $serieActual = $e->idSerie;
                    $contElem = 1;
                    $acumElem = 0;
                }
            }

            $nombreE = nombreElementoFromInt(intval($e->noElemento));
//            echo $nombre.'++<br/>';
            $calif = $e->calificacion;
            if (isset($calif)) {
                $matriz[$e->correo][$nombreS . "-" . $nombreE] = $calif;
                $matriz[$e->correo]['intento: ' . $nombreS . "-" . $nombreE] = 0;
            } else {
                $matriz[$e->correo][$nombreS . "-" . $nombreE] = 1;
                $matriz[$e->correo]['intento:' . $nombreS . "-" . $nombreE] = 0;
            }


            $contElem++;
            $acumElem = $acumElem + $e->calificacion;
            $serieAnt = $e->nivelSerie;
            $nombreAnt = nombreSerieFromInt(intval($e->nivelSerie));
            $correoAnt = $e->correo;
        }
    }
    $matriz[$e->correo]["Promedio-" . $nombreAnt] = $promedio;
    return $matriz;
}
/**
 * Hace una matriz pequeña de una matriz grande
 * @param type $matrizota
 * @param type $pos
 * @return boolean
 */
function matrizIndividualDeMatrizota($matrizota, $pos) {
    $matrizita = array();
    if ($pos != 0) {
        foreach ($matrizota[$pos] as $b => $a) {
            $matrizita[1][$b] = $a;
        }
        return $matrizita;
    } else {
        return false;
    }
}
/**
 * Devuelve un objeto de query ocn las unidades que pertenecen a un curso
 * @param type $idCurso
 * @return type
 */
function unidadesDeCurso($idCurso) {
    $query = new Query("SG");
    $query->sql = <<<SQL
       select id_unidad as "idUnidad", no_unidad as "noUnidad"
        from unidades u
        where id_curso = $idCurso
        order by no_unidad
        
SQL;
    $resultado = $query->select("obj");
    return $resultado;
}
/**
 * Imprime select con options de las unidades de un curso
 * @param type $idCurso
 */
function selectUnidadesDeCurso($idCurso) {
    $arr = unidadesDeCurso($idCurso);
    echo '<select name = "idUnidad">';
    foreach ($arr as $a) {
        echo "<option value='$a->idUnidad'>$a->noUnidad</option>";
    }
    echo '<select name = "idUnidad">';
}
/**
 * Crea el objeto para la tabla de reportes
 * @param type $idTutor
 * @return type
 */
function objParaTablaReportes($idTutor) {
    $predicado = "";
    if (isset($idTutor)) {
        $from = "from rel_curso_tutor rct
                    left join rel_curso_grupo rcg
                            on rct.id_rel_curso_grupo = rcg.id_rel_curso_grupo";

        $predicado = " AND rct.id_tutor = " . $idTutor;
    } else {
        $from = "FROM rel_curso_grupo rcg";
    }

    $query = new Query("SG");
    $query->sql = <<<SQL
        select i.nombre_institucion as "institucion", es.nombre_escuela as "escuela", em.nombre_empresa as "empresa", c.nombre_curso as "curso", c.id_curso as "idCurso", ca.id_curso_abierto as "idCursoAbierto",
                ca.nombre_curso_abierto as "cursoAbierto", g.id_grupo as "idGrupo", g.nombre_grupo as "grupo"
              $from
        left join grupo g
                on rcg.id_grupo = g.id_grupo
        left join escuelas es
                on es.id_escuela = g.id_escuela
        left join instituciones i
                on i.id_institucion = es.id_institucion
        left join empresa em
                on em.id_empresa = g.id_empresa
        left join cursos_abiertos ca
                on rcg.id_curso_abierto = ca.id_curso_abierto
        left join cursos c
                on ca.id_curso = c.id_curso
        
        WHERE g.status=1
        AND es.status=1
        AND i.status=1
        AND (em.status=1
        OR em.status is null)
        AND ca.status=1
        AND c.status=1
        $predicado
                
        GROUP by i.nombre_institucion,
        es.nombre_escuela,
        em.nombre_empresa,
        c.nombre_curso,
        c.id_curso,
        ca.id_curso_abierto,
        ca.nombre_curso_abierto,
        g.id_grupo,
        g.nombre_grupo
        
SQL;
    $resultado = $query->select("obj");
    return $resultado;
}
/**
 * Crea la tabla para la subida de reportes
 * @param type $idTutor
 */
function tablaParaReportes($idTutor) {
    echo <<<html
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Instituci&oacute;n</th>
                    <th>Escuela</th>
                    <th>Empresa</th>
                    <th>Curso</th>
                    <th>Curso Abierto</th>
                    <th>Grupo</th>
                    <th>Tipo Reporte</th>
                    <th>Subir</th>
                </tr>
            </thead>
            <tbody>
html;
    trParaReportes($idTutor);
    echo <<<html
            </tbody>
            <tfoot>
                <tr>
                    <th>Instituci&oacute;n</th>
                    <th>Escuela</th>
                    <th>Empresa</th>
                    <th>Curso</th>
                    <th>Curso Abierto</th>
                    <th>Grupo</th>
                    <th>Tipo Reporte</th>
                    <th>Subir</th>
                </tr>
            </tfoot>
        </table>
html;
}
/**
 * tr para la tabla de reportes de un tutor
 * @param type $idTutor
 */
function trParaReportes($idTutor) {
    $arrayDatos = objParaTablaReportes($idTutor);
    if ($arrayDatos) {
        foreach ($arrayDatos as $grupo) {
            $institucion = ($grupo->institucion);
            $escuela = ($grupo->escuela);
            $empresa = ($grupo->empresa);
            $curso = ($grupo->curso);
            $idGrupo = $grupo->idGrupo;
            $idCurso = ($grupo->idCurso);
            $idCursoAbierto = ($grupo->idCursoAbierto);
            $cursoAbierto = ($grupo->cursoAbierto);
            $grupo = ($grupo->grupo);




//            if ($grupo->tipoGrupo == 0) {
//                $tipoGrupo = "Escuela";
//            } else {
//                $tipoGrupo = "Empresa";
//            }


            echo <<<HTML
            <tr>
            <form action="reportes_2.php" method = "GET">
                <td>$institucion</td>
                <td>$escuela</td>
                <td>$empresa</td>
                <td>$curso <input type="hidden" name="idCurso" value = "$idCurso" /></td>
                <td>$cursoAbierto <input type="hidden" name="idCursoAbierto" value = "$idCursoAbierto" /></td>
                <td>$grupo <input type="hidden" name="idGrupo" value = "$idGrupo" /></td>
                <td>
                    <select name="tipoReporte" id="tipo_reporte">
HTML;
            echo generaListadoTipoReportes();
            echo <<<HTML
                    </select>
                </td>
                <td><button class="btn btn-primary" type="submit" >Subir/Generar Reporte</button></td>  
            </form>
            </tr>
HTML;
        }
    }
}
?>