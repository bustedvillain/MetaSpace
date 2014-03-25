<?php

//Control de cambios #5
//26-dic-2013
//Modificaciones requeridas por eblue para la plantilla

/**
 * CHANGE CONTROL 0.99.5
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * FECHA: 26 DE DICIEMBRE DEL 2013
 * OBJETIVO: FUNCIONES PARA MANEJAR LA BITACORA DE LA PLANTILLA
 */

/**
 * CHANGE CONTROL #7
 * AUTOR: Omar Nava
 * FECHA: 15/01/2014
 * OBJETIVO: Fix de ordenar series
 */
$series;
$contador = 0;
$contTipoE = 1;
$idActividad = 0;
$idEjercicio = 0;
$idReto = 0;

/**
 * Obtiene el contador que se usa para construir el editar linkeo con series
 * @global int $contador
 */
function getContador() {
    global $contador;
    $contador++;
}
/**
 * Asignaa en las variables globales los nombres de los elementos dependiendo de lo que hay en la bd
 * @global type $idActividad
 * @global type $idEjercicio
 * @global type $idReto
 */
function seteaIdsAER() {
    global $idActividad;
    global $idEjercicio;
    global $idReto;
    require_once 'Query.php';
    $sql = new Query("SG");
    //inicia control de cambios #5
    $sql->sql = "select id_tipo_elemento from tipo_elemento where lower(nombre_tipo) like '%aplico%'";
    //termina control de cambios #5
    $resultado = $sql->select("obj");
    foreach ($resultado as $r) {
        $idActividad = $r->id_tipo_elemento;
        break;
    }
    require_once 'Query.php';
    $sql2 = new Query("SG");
    //inicia control de cambios #5
    $sql2->sql = "select id_tipo_elemento from tipo_elemento where lower(nombre_tipo) like '%practico%'";
    //termina control de cambios #5
    $resultado2 = $sql2->select("obj");
    foreach ($resultado2 as $r) {
        $idEjercicio = $r->id_tipo_elemento;
        break;
    }
    require_once 'Query.php';
    $sql3 = new Query("SG");
    //inicia control de cambios #5
    $sql3->sql = "select id_tipo_elemento from tipo_elemento where lower(nombre_tipo) like '%integro%'";
    //termina control de cambios #5
    $resultado3 = $sql3->select("obj");
    foreach ($resultado3 as $r) {
        $idReto = $r->id_tipo_elemento;
        break;
    }
}
/**
 * Series de una unidad
 * @param type $idUnidad
 * @return type
 */
function arrSeriesBD($idUnidad) {
    $arrSeries = array();
    require_once 'Query.php';
    $sql = new Query("SG");
    //Inicia control de cambios #7
    $sql->sql = "select * from serie_aer where id_unidad = $idUnidad
            order by nivel_serie_aer";
    //termina control de cambios #7
    $series = $sql->select("obj");
    foreach ($series as $serie) {
//        imprimeConsola("AsignareEn pos".$serie->id_serie_aer);
        $arrSeries[$serie->id_serie_aer] = $serie->nivel_serie_aer;
    }
//    echo 'El arr de series es'.var_dump($arrSeries)."<br/>";
    return $arrSeries;
}

/**
 * Función que devuelve el id del curso al que pertenece una unidad
 * @param type $idUnidad
 */
function getIdCursoFromIdUnidad($idUnidad) {
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql = "select c.id_curso as \"idCurso\"
        from cursos c
        join unidades u 
                on c.id_curso = u.id_curso
        where id_unidad = $idUnidad";
    $resultado = $sql->select("obj");
    foreach ($resultado as $res) {
        return $res->idCurso;
        break;
    }
}
/**
 * Retorna la información del progreso de un alumno, el mejo
 * @param type $idRelCursoGrupo
 * @param type $idAlumno
 * @param type $idUnidad
 * @return type
 */
function retornaInfoProgreso($idRelCursoGrupo, $idAlumno, $idUnidad) {
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql = "select p.id_progreso_alumno as \"idProgresoAlumno\" , lower(substring(t.nombre_tipo from 1 for 1)) as \"letraElemento\",  
	p.id_elemento_aer as \"idElementoAer\", p.estatus_evaluacion as \"estatusEvaluacion\"
        from progreso_alumno p
            join elemento_aer e	
                    on p.id_elemento_aer = e.id_elemento_aer
            join serie_aer s
                    on e.id_serie_aer = s.id_serie_aer
            join unidades u
                    on u.id_unidad = s.id_unidad
            join tipo_elemento t
                    on e.id_tipo_elemento = t.id_tipo_elemento
        where p.id_rel_curso_grupo = $idRelCursoGrupo and p.id_alumno = $idAlumno and u.id_unidad = $idUnidad
        order by p.id_elemento_aer";
    $progresos = $sql->select("obj");
    $infoProgreso = array();
//    sort($progresos);
//    var_dump($progresos);
    $cont = 0;
    foreach ($progresos as $p) {
        $cont++;
        if ($p->estatusEvaluacion < 2) {
            $infoProgreso['idProgresoAlumno'] = $p->idProgresoAlumno;
            $infoProgreso['letraElemento'] = $p->letraElemento;
            $infoProgreso['idElementoAer'] = $p->idElementoAer;
            return json_encode($p);
//            return ('aqui con '.$p);
            break;
        }
        $infoProgreso['idProgresoAlumno'] = $p->idProgresoAlumno;
        $infoProgreso['letraElemento'] = $p->letraElemento;
        $infoProgreso['idElementoAer'] = $p->idElementoAer;
    }
//    return ('aca con '.$cont . '---'.$infoProgreso);
    return json_encode($infoProgreso);
}

/**
 * Funcion que retorna toda la informacion necesaria para la carga de una unidad HTML5
 * Corrección por: Manuel Nieto
 * Fecha de corrección: 4 de Diciembre 2013
 * Motivo: Ajuste de diseño
 * Diferencias con la función anterior: Ahora retorna la ruta de cada elemento, el id_rel_curso_grupo, dejando 
 * menos carga de procesamiento en javascript (cliente). No concatena la información en un string, se creo
 * una clase con atributos donde viene cada parametro por separado y se agrega a un arreglo
 * para mayor facilidad en su manejo, así como menor procesamiento en el cliente (javascript). 
 * Función que conjunta retornaArrCont con retornaArrCont2, sobrecargando el metodo con $idAlumno = NULL,
 * si este viene nulo entonces retorna la informacion como para una simple previsualizacion de la unidad
 * Si recibe  valor en $idAlumno retorna información sobre el progreso.
 * @param type $idUnidad
 * @param type $idAlumno
 * @return array
 */
function retornaArrCont($idUnidad, $idAlumno = NULL) {
    require_once 'Query.php';
    require_once 'ElemPlantClass.php';

    $sql = new Query("SG");

    if ($idAlumno != NULL) {
        $sql->sql = "select s.id_serie_aer as \"idSerie\", s.nivel_serie_aer as \"nivelSerieAer\", s.status, e.id_elemento_aer as \"idElementoAer\", 
        e.no_elemento_aer as \"noElementoAer\",
	e.status, h.id_habilidad as \"idHabilidad\", h.nombre_habilidad as \"nombreHabilidad\", h.status, t.id_tipo_elemento as \"idTipoElemento\", 
	t.nombre_tipo as \"nombreTipo\", t.status, p.id_progreso_alumno as \"idProgresoAlumno\",
        p.estatus_evaluacion as \"estatusEvaluacion\",  lower(substring(t.nombre_tipo from 1 for 1)) as \"letraElemento\", p.id_rel_curso_grupo, u.url_unidad
        from unidades u
        join serie_aer s
                on u.id_unidad = s.id_unidad
        join elemento_aer e
                on s.id_serie_aer = e.id_serie_aer
        join habilidades h
                on e.id_habilidad = h.id_habilidad
        join tipo_elemento  t
                on e.id_tipo_elemento = t.id_tipo_elemento
        join progreso_alumno p
                on p.id_elemento_aer = e.id_elemento_aer
       where u.id_unidad = $idUnidad and p.id_alumno = $idAlumno
        order by e.id_elemento_aer";
    } else {
        $sql->sql = "select s.id_serie_aer as \"idSerie\", s.nivel_serie_aer as \"nivelSerieAer\", s.status, e.id_elemento_aer as \"idElementoAer\", 
        e.no_elemento_aer as \"noElementoAer\",
	e.status, h.id_habilidad as \"idHabilidad\", h.nombre_habilidad as \"nombreHabilidad\", h.status, t.id_tipo_elemento as \"idTipoElemento\", 
	t.nombre_tipo as \"nombreTipo\", t.status,   lower(substring(t.nombre_tipo from 1 for 1)) as \"letraElemento\"
        from serie_aer s
        join elemento_aer e
                on s.id_serie_aer = e.id_serie_aer
        join habilidades h
                on e.id_habilidad = h.id_habilidad
        join tipo_elemento  t
                on e.id_tipo_elemento = t.id_tipo_elemento
        where s.id_unidad = $idUnidad 
        order by e.id_elemento_aer";
    }
    $arrCont = array();
    $resultado = $sql->select("obj");
//    $cont = 1;
    foreach ($resultado as $r) {
        $detalle = detalle($r->nivelSerieAer, $r->noElementoAer);
//        $fila = $r->idProgresoAlumno . "##" . $r->idElementoAer . "##" . $detalle . "##" . $r->letraElemento . "##" . $r->estatusEvaluacion . "##0";

        $elemento = new ElemPlantClass();
        $elemento->idElemento = $r->idElementoAer;
        //Url_unidad: http://192.168.57.41/storage/unidades/319
        //detalle: s01/c01/
        $elemento->ruta = "/" . $detalle . "/";
        $elemento->tipoElemento = $r->letraElemento;

        if ($idAlumno != NULL) {
            $elemento->idProgreso = $r->idProgresoAlumno;
            $elemento->status_evaluacion = $r->estatusEvaluacion;
            $elemento->idRelCursoGrupo = $r->id_rel_curso_grupo;
        }

        array_push($arrCont, $elemento);
//        $arrCont[$cont] = $fila;
//        $cont++;
//        array_push($arrCont, $fila);
    }
    return json_encode($arrCont);
}
/**
 * Retorna un arreglo con todos los contenidos para la pantilla
 * @param type $idUnidad
 * @return string
 */
function retornaArrCont2($idUnidad) {
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql = "select s.id_serie_aer as \"idSerie\", s.nivel_serie_aer as \"nivelSerieAer\", s.status, e.id_elemento_aer as \"idElementoAer\", 
        e.no_elemento_aer as \"noElementoAer\",
	e.status, h.id_habilidad as \"idHabilidad\", h.nombre_habilidad as \"nombreHabilidad\", h.status, t.id_tipo_elemento as \"idTipoElemento\", 
	t.nombre_tipo as \"nombreTipo\", t.status,   lower(substring(t.nombre_tipo from 1 for 1)) as \"letraElemento\"
        from serie_aer s
        join elemento_aer e
                on s.id_serie_aer = e.id_serie_aer
        join habilidades h
                on e.id_habilidad = h.id_habilidad
        join tipo_elemento  t
                on e.id_tipo_elemento = t.id_tipo_elemento
        where s.id_unidad = $idUnidad 
        order by e.id_elemento_aer";
    $arrCont = array();
    $resultado = $sql->select("obj");
    $cont = 1;
    foreach ($resultado as $r) {
        $detalle = detalle($r->nivelSerieAer, $r->noElementoAer);
        $fila = "0##" . $r->idElementoAer . "##" . $detalle . "##" . $r->letraElemento . "##0" . "##0";
        $arrCont[$cont] = $fila;
        $cont++;
//        array_push($arrCont, $fila);
    }
    return $arrCont;
}
/**
 * Asigna/actualiza la calificación de un elemento de un alumno
 * @param type $idAlumno
 * @param type $idElemento
 * @param type $calificacion
 * @return string
 */
function asignarCalificacion($idAlumno, $idElemento, $calificacion) {
    $anterior = calificacionAnterior($idAlumno, $idElemento, $calificacion);
    if ($anterior->estatus_evaluacion != 2 || $anterior->calificacion < $calificacion) {
        require_once 'Query.php';

        $sql = new Query("SG");
        $Msql = "
        UPDATE progreso_alumno
        SET estatus_evaluacion = 2, calificacion = $calificacion
            WHERE id_alumno = $idAlumno and id_elemento_aer = $idElemento
        ";
        if ($sql->update($Msql)) {
            return "Calificacion registrada correctamente";
        } else {
            return "Errror al Actualizar";
        }
    }
}
/**
 * Devuelve la calificación anterior como objeto de un alumno
 * @param type $idAlumno
 * @param type $idElemento
 * @param type $calificacion
 * @return type
 */
function calificacionAnterior($idAlumno, $idElemento, $calificacion) {
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql = "select *
        from progreso_alumno
        where id_alumno = $idAlumno and id_elemento_aer = $idElemento";
    $arrCont = array();
    $resultado = $sql->select("obj");
    foreach ($resultado as $r) {
        return $r;
    }
}
/**
 * Cambia el status de un elemento 
 * @param type $idAlumno
 * @param type $idElemento
 * @param type $status 1 = iniciado, 2= terminado
 * @param type $fecha
 * @return string
 */
function asignarStatusElemento($idAlumno, $idElemento, $status, $fecha) {
    require_once 'Query.php';
    $sqlFecha = "";

    switch ($status) {
        case 1:
            $sqlFecha = " ,fecha_inicio = '$fecha' ";
            break;
        case 2:
            $sqlFecha = " ,fecha_fin = '$fecha' ";
            break;
    }
    $sql = new Query("SG");
    $Msql = "
        UPDATE progreso_alumno
        SET estatus_evaluacion = $status $sqlFecha
            WHERE id_alumno = $idAlumno and id_elemento_aer = $idElemento
        ";
    if ($sql->update($Msql)) {
        return "Estatus cambiado correctamente el estatus era:" . $status;
    } else {
        return "Errror al Actualizar Status";
    }
}
/**
 * Devuelve una ruta de un elemento para que sea leída por la plantilla
 * @param type $nivelSerieAer
 * @param type $noElementoAer
 * @return type
 */
function detalle($nivelSerieAer, $noElementoAer) {
    $nuevaSerie = "";
    $nuevoElemento = "";
    if ($nivelSerieAer < 10) {
        $nuevaSerie = "s0" . $nivelSerieAer;
    } else {
        $nuevaSerie = "s" . $nivelSerieAer;
    }
    if ($noElementoAer < 10) {
        $nuevoElemento = "c0" . $noElementoAer;
    } else {
        $nuevoElemento = "c" . $noElementoAer;
    }
    return $nuevaSerie . "/" . $nuevoElemento;
}
/**
 * Devuelve un arreglo con el html del linkeo de series
 * @global int $contador
 * @global int $contTipoE
 * @param type $idUnidad
 * @return array
 */
function arregloSeriesContenidosEditar($idUnidad) {
    global $contador;
    global $contTipoE;
    seteaIdsAER();
    $pilaCompleta = array();

    $serieActual = 0;
    $estado = 0;
    $arrSeries = arrSeriesBD($idUnidad);
//    sort($arrSeries);
//    echo ' '.var_dump($arrSeries)."---".count($arrSeries);
    //array_push($pilaCompleta, var_dump($arrSeries));
    $var = '<input type="hidden" id="operacion" value="editar"/><input type="hidden" name="idUnidad" id = "idUnidad" value="' . $idUnidad . '"/>';
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql = "select * from elemento_aer order by id_serie_aer";
    $contenidos = $sql->select("obj");
//    var_dump($contenidos);
    foreach ($contenidos as $contenido) {
        //array_push($pilaCompleta, var_dump($contenido));
        if (array_key_exists($contenido->id_serie_aer, $arrSeries)) {

//        if (in_array($contenido->id_serie_aer, $arrSeries)) {
//            array_push($pilaCompleta, "serie actual--".$serieActual);
//            array_push($pilaCompleta, "busca--".$contenido->id_serie_aer);
//            imprimeConsola("Se probó:".$arrSeries[$contenido->id_serie_aer-1]."y actual era".$serieActual);
            if ($arrSeries[$contenido->id_serie_aer] != $serieActual) {
//            if ($contenido->id_serie_aer != $serieActual) {
                //imprime como serie
                if ($estado == 1) {
                    $var = "</tbody></table>";
                    array_push($pilaCompleta, $var);
                }
                $estado = 1;
                $serieActual = $arrSeries[$contenido->id_serie_aer];
//                $serieActual = $contenido->id_serie_aer;
                ///
                getContador();
                $var = $var . '<h4>Serie: ' . $serieActual . '</h4>
                <input type="hidden" name="' . $contador . '" class = "serie" value="s0' . $serieActual . '"/>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Contenido</th>
                            <th>Habilidad</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>';
                array_push($pilaCompleta, $var);
            }
            if (array_key_exists($contenido->id_serie_aer, $arrSeries)) {
//            if (in_array($contenido->id_serie_aer, $arrSeries)) {
                //imprime como cont
                getContador();
                $var = '<tr>
                            <td>' . $contenido->no_elemento_aer . '<input type="hidden" name="' . $contador . '" class = "contenido" value="c0' . $contenido->no_elemento_aer . '"/></td>';
                //getContador();
                $var = $var . '</td>
                            <td>';
                getContador();
                $var = $var . '
                                <select name="' . $contador . '" class="ch">
                                    ' . comboHabilidadesConSelected($contenido->id_habilidad) . '
                                </select>
                            </td>
                            ';
                getContador();
                $var = $var . '
                        <td>
                            <input type="hidden" name="' . $contador . tipoElemento() . '
                                
                        </td>
                    </tr>';
//                $var = $var . '
//                            <td>
//                                <select name="' . $contador . '" class="ct">
//                                    ' . comboTipoElemento() . '
//                                </select>
//                            </td>
//                        </tr>';
                array_push($pilaCompleta, $var);
                //$serieActual = $contenido->id_serie_aer;
            }
        }
    }
    $contador = 0;
    return $pilaCompleta;
}
/**
 * Función que crea el arreglo del html del modal de linkeo de series cuando se hace por primera vez
 * @global int $contador
 * @global int $contTipoE
 * @param type $idUnidad
 * @return array
 */
function arregloSeriesContenidos($idUnidad) {
    global $contador;
    global $contTipoE;
    seteaIdsAER();
    $arrSeries = array();
    $pilaCompleta = array();
    $dir = UNIDADES_PATH . "/$idUnidad";
    $directorioSeries = opendir($dir);
    while ($serie = readdir($directorioSeries)) {
        if ($serie != '.' && $serie != '..' && esNombreDeSerie($serie))
            array_push($arrSeries, $serie);
    }
    closedir($directorioSeries);
    $var = '<input type="hidden" id="operacion" value="nuevo"/><input type="hidden" name="idUnidad" id = "idUnidad" value="' . $idUnidad . '"/>';
    //Inicia control de cambios #7
    sort($arrSeries);
    //termina control de cambios #7
//    var_dump($arrSeries);
    foreach ($arrSeries as $serie) {
        getContador();
        $var = $var . '<h4>Serie: ' . $serie . '</h4>
            <input type="hidden" name="' . $contador . '" class = "serie" value="' . $serie . '"/>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Contenido</th>
                        <th>Habilidad</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>';
        array_push($pilaCompleta, $var);
        $dir = UNIDADES_PATH . "/$idUnidad/$serie";
        $carpetaSerie = opendir($dir);
        $contTipoE = 1;
        $conts = array();
        while ($contenidoo = readdir($carpetaSerie)) {
            array_push($conts, $contenidoo);
        }
        sort($conts);
        foreach ($conts as $contenido) {
            if (esNombreDeContenido($contenido)) {
                getContador();
                $var = '<tr>
                        <td>' . $contenido . '<input type="hidden" name="' . $contador . '" class = "contenido" value="' . $contenido . '"/></td>';
                //getContador();
                $var = $var . '</td>
                        <td>';
                getContador();
                $var = $var . '
                            <select name="' . $contador . '" class="ch">
                                ' . comboHabilidades() . '
                            </select>
                        </td>
                        ';
                getContador();
                $var = $var . '
                        <td>
                            <input type="hidden" name="' . $contador . tipoElemento() . '
                                
                        </td>
                    </tr>';
//                $var = $var . '
//                        <td>
//                            <select name="' . $contador . '" class="ct">
//                                ' . comboTipoElemento() . '
//                            </select>
//                        </td>
//                    </tr>';
                array_push($pilaCompleta, $var);
            }
        }
        $var = "</tbody></table>";
        array_push($pilaCompleta, $var);
    }
    $contador = 0;
    return $pilaCompleta;
}
/**
 * Identifica si se trata de una serie o de un contenido
 * @param type $cadena
 * @param type $tipo
 * @return boolean
 */
function matcheaCadena($cadena, $tipo) {

    switch ($tipo) {
        case 'serie':
            $patron = "/^s[0-9][0-9]/";
            if (preg_match($patron, $cadena))
                return true;
            else
                return false;
            break;
        case 'contenido':
            $patron = "/^c[0-9][0-9]/";
            if (preg_match($patron, $cadena))
                return true;
            else
                return false;
            break;
    }
}
/**
 * Devuelve los 3 primeros caracteres de una cadena
 * @param type $cadena
 * @return type
 */
function tresPrimerosCaracteres($cadena) {
    return substr($cadena, 0, 3);
}
/**
 * De acuerdo a un elemento devuelve el tipo de éste
 * @global int $contTipoE
 * @global type $idActividad
 * @global type $idEjercicio
 * @global type $idReto
 * @return type
 */
function tipoElemento() {
    global $contTipoE;
    global $idActividad;
    global $idEjercicio;
    global $idReto;

//inicia control de cambios #5
    switch ($contTipoE) {
        case 1:
            $contTipoE++;
            return '" value = ' . $idActividad . '>  Aplico';
            break;
        case 2:
            $contTipoE++;
            return '" value = ' . $idEjercicio . '>  Practico';
            break;
        case 3:
            $contTipoE = 1;
            return '" value = ' . $idReto . '>  Integro';
            break;
    }
//termina control de cambios #5
}
/**
 * Convierte el valor a enteros de una serie o contenido
 * @param type $var
 * @return type
 */
function nombreSerieOContenidoToInt($var) {
    return intval(substr($var, 1, 2));
}
/**
 * Iserta contenido en la bd
 * @param type $contenido
 * @param type $idSerie
 * @param type $idTipo
 * @param type $idHabilidad
 * @return string
 */
function almacenamientoContenido($contenido, $idSerie, $idTipo, $idHabilidad) {
    $contenido = nombreSerieOContenidoToInt($contenido);
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->insert("elemento_aer", "no_elemento_aer, status, id_serie_aer, id_tipo_elemento, id_habilidad", "$contenido, 1, $idSerie, $idTipo, $idHabilidad ");
    return 'hice insert';
}
/**
 * Edita un contenido en la bd
 * @param type $contenido
 * @param type $idSerie
 * @param type $idTipo
 * @param type $idHabilidad
 * @return type
 */
function editaContenido($contenido, $idSerie, $idTipo, $idHabilidad) {
    $contenido = nombreSerieOContenidoToInt($contenido);
    require_once 'Query.php';
    $sql = new Query("SG");
    $Ssql = "UPDATE elemento_aer set id_tipo_elemento = $idTipo, id_habilidad = $idHabilidad where no_elemento_aer = $contenido and id_serie_aer = $idSerie";
    //$sql->sql="UPDATE elemento_aer set id_tipo_elemento = $idTipo, id_habilidad = $idHabilidad";// where no_elemento_aer = $contenido and id_serie_aer = $idSerie";
//    $sql->insert("elemento_aer", "no_elemento_aer, status, id_serie_aer, id_tipo_elemento, id_habilidad", "$contenido, 1, $idSerie, $idTipo, $idHabilidad ");
    $sql->update($Ssql);
    return "hice update**cont-$contenido**idSerie-$idSerie**idTipo-$idTipo**idhab-$idHabilidad";
}
/**
 * Inserta una serie de acuerdo a lo que reciba y a su id unidad
 * @param type $serie
 * @param type $idUnidad
 * @return type
 */
function almacenamientoSerie($serie, $idUnidad) {
    $serie = nombreSerieOContenidoToInt($serie);
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->insert("serie_aer", "nivel_serie_aer, status, id_unidad", "$serie, 1, $idUnidad");
    return $sql->ultimoID("serie_aer");
}
/**
 * devuelve el id de una serie de acuerdo a un nombre
 * @param type $serie
 * @param type $idUnidad
 * @return type
 */
function idSerie($serie, $idUnidad) {
    $serie = nombreSerieOContenidoToInt($serie);
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql = "select id_serie_aer from serie_aer where nivel_serie_aer = $serie and id_unidad = $idUnidad";
    $resultado = $sql->select("obj");
    foreach ($resultado as $r) {
        return $r->id_serie_aer;
    }
}
/**
 * Crea un arreglo de series de acuerdo al id de una unidad
 * @param type $unidad
 */
function llenaArr($unidad) {
    //include_once 'variablesServidor.php';
    //$unidad = "0001";
    //$unidades_path = "../unidades";
    $dir = UNIDADES_PATH . "/" . $unidad;
    //$directorioSeries = opendir($dir);
    $directorioSeries = opendir($dir);
    $cont = 0;
    while ($directorioUnidad = readdir($directorioSeries)) {
        //echo $directorioUnidad;
        $series[$cont] = $directorioUnidad;
        $cont++;
    }

    closedir($directorioSeries);
}
/**
 * Imprime los css y js de unos contenidos
 * @param type $unidad
 */
function imprimeHead($unidad) {
    //include_once 'variablesServidor.php';
    //$unidad = "0001";
    //$unidades_path = "../unidades";
    $dir = UNIDADES_PATH . "/" . $unidad;
    //$directorioSeries = opendir($dir);
    $directorioSeries = opendir($dir);
    $cont = 0;
    while ($directorioUnidad = readdir($directorioSeries)) {
        //echo $directorioUnidad;
        $series[$cont] = $directorioUnidad;
        $cont++;
    }

    closedir($directorioSeries);
    //llenaArr();
    foreach ($series as $serie) {
        if ($serie != '.' && $serie != '..' && esNombreDeSerie($serie))
        //if (esNombreDeSerie((string)$serie))
            echo '<!--Inicia la carga de informacion de serie ' . $serie . '-->' . "\n" . "\t";
        $dirS = $dir . "/" . $serie;
        $directorioSerie = opendir($dirS);
        while ($subdirSerie = readdir($directorioSerie)) {
            if ($subdirSerie == "css") {
                $directorioCSS = opendir($dirS . "/css");
                while ($archivoCSS = readdir($directorioCSS)) {
                    if ($archivoCSS != '.' && $archivoCSS != '..')
                        echo '<link rel="stylesheet" type="text/css" href="../../storage/unidades/' . $unidad . '/' . $serie . '/css/' . $archivoCSS . '"/>' . "\n" . "\t";
//                        echo '<link rel="stylesheet" type="text/css" href="unidades/' . $unidad . '/' . $serie . '/css/' . $archivoCSS . '"/>' . "\n" . "\t";
                }
            }
            if ($subdirSerie == "js") {
                $directorioJS = opendir($dirS . "/js");
                while ($archivoJS = readdir($directorioJS)) {
                    if ($archivoJS != '.' && $archivoJS != '..')
                        echo '<script type="text/javascript" charset="utf-8" src="../../storage/unidades/' . $unidad . '/' . $serie . '/js/' . $archivoJS . '"></script> ' . "\n" . "\t";
                }
            }
        }
    }
}
/**
 * Genera un arreglo de contenidos a través de un id de unidad
 * @param type $unidad
 */
function arregloContenidos($unidad) {
    //include_once 'variablesServidor.php';
    //$unidad = "0001";
    //$unidades_path = "../unidades";
    $dir = UNIDADES_PATH . "/" . $unidad;
    //$directorioSeries = opendir($dir);
    $directorioSeries = opendir($dir);
    $cont = 0;
    while ($directorioUnidad = readdir($directorioSeries)) {
        //echo $directorioUnidad;
        $series[$cont] = $directorioUnidad;
        $cont++;
    }

    closedir($directorioSeries);
    //llenaArr();
    $lista = "";
    foreach ($series as $serie) {
        //echo $serie;

        if ($serie != '.' && $serie != '..' && esNombreDeSerie($serie)) {
            //if (esNombreDeSerie($serie)) {
            $dirS = $dir . "/" . $serie;
            $directorioSerie = opendir($dirS);
            while ($archivo = readdir($directorioSerie)) {
                if ($archivo != '.' && $archivo != '..' && estaPermitido(extension($archivo))) {
                    $lista = $lista . "'" . $serie . "/" . $archivo . "'" . ",";
                }
            }
        }
    }
    echo '
    <script type="text/javascript">
        var arregloCont = [' . $lista . '];
        arregloCont.sort();
    </script>
    ';
}
/**
 * Obtiene la extensión de un string
 * @param type $filename
 * @return type
 */
function extension($filename) {
    return substr(strrchr($filename, '.'), 1);
}

/**
 * Funcion que valida que el nombre de la carpeta sea uno permitido
 * Autor:José Manuel Nieto Gómez
 * Fecha de modificación: 4 de Diciembre del 2013
 * Motivo del cambio: Nuevo diseño en la estructura de los contenidos
 * Cambio de la version anterior: Ahora no valida la extension, valida un nombre de carpeta
 * @param type $nombreCarpeta
 * @return boolean
 */
function estaPermitido($nombreCarpeta) {
    $extPermitidas = array('html', 'htm', 'php');
    if (in_array($ext, $extPermitidas))
        return true;
    else
        return false;
}
/**
 * Retorna true si se trata de un nombre de serie correcto
 * @param type $serieName
 * @return boolean
 */
function esNombreDeSerie($serieName) {
//    echo 'EL nombre es' .$serieName ;
//    if(strlen($serieName)===3)
//    echo 'La long es'. strlen($serieName) ;
//    if($serieName[0] === 's' || $serieName[0] === 'S')
//    echo 'En 0'.$serieName[0];
//    if(is_int((int)$serieName[1]))
//    echo 'en 1'.$serieName[1];
//    if(is_int((int)$serieName[2]))
//    echo 'en 2'.$serieName[2].'<br/>';
    if (strlen($serieName) === 3 && ($serieName[0] === 's' || $serieName[0] === 'S') && is_int((int) $serieName[1]) && is_int((int) $serieName[2])) {
        return true;
    } else {
        return false;
    }
}

/**
 * Funcion que valua si es permitido el nombre de la carpeta para ser contenido
 * @param type $contnidoName
 * @return boolean
 */
function esNombreDeContenido($serieName) {
    if (strlen($serieName) === 3 && ($serieName[0] === 'c' || $serieName[0] === 'C') && is_int((int) $serieName[1]) && is_int((int) $serieName[2])) {
        return true;
    } else {
        return false;
    }
}
/**
 * Verifica si es la sesión de plantilla 
 * deprecated
 */
function verificarSesionPlantilla() {
    if (!esAlumno() && !esJr() && !esSenior() && !esCoordinador() && !esGestorContenido() && !esAdministrador()) {
        header("Location:../?msg=accessDenied");
    }
}
/**
 * Verifica si es la sesión fw de la plantolla
 */
function verificarSesionPlantillaFW() {
    if (!esAlumno() && !esJr() && !esSenior() && !esCoordinador() && !esGestorContenido() && !esAdministrador()) {
        header("Location:../../?msg=accessDenied");
    }
}
/**
 * Verifica si un alumno está en la fe
 */
function verificaSesionAlumnoFW() {
    if (!esAlumno()) {
        header("Location:../../?msg=accessDenied");
    }
}
/**
 * Verifica si es la sesión del alumno
 */
function verificaSesionAlumno() {
    if (!esAlumno()) {
        header("Location:../?msg=accessDenied");
    }
}

/**
 * CHANGE CONTROL 0.99.5
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * FECHA: 26 DE DICIEMBRE DEL 2013
 * OBJETIVO: FUNCIONES PARA MANEJAR LA BITACORA DE LA PLANTILLA
 */

/**
 * Funcion que registra una entrada a la plantilla de contenidos
 * @param type $idProgresoAlumno
 */
function registrarEntradaPlantilla($idProgresoAlumno) {
    $query = new Query("SG");
    $query->insert("bitacora_progreso", "fecha, tipo, id_progreso_alumno", "now(), 0, $idProgresoAlumno ");
    return "ok";
}

/**
 * Funcion que registra un intento (registro de calificacion)
 * @param type $idProgresoAlumno
 * @param type $calificacion
 */
function registraIntentoPlantilla($idProgresoAlumno, $calificacion) {
    $query = new Query("SG");
    $query->insert("bitacora_progreso", "fecha, tipo, id_progreso_alumno, calificacion", "now(), 1, $idProgresoAlumno, $calificacion");
    return "ok";
}

/**
 * Función que registra la salida de la plantilla, generando un tiempo de permanencia de acuerdo a la ultima entrada detectada
 * @param type $idProgresoAlumno
 */
function registraSalidaPlantilla($idProgresoAlumno) {
    //Retorna
    $ultimaEntrada = consultaUltimaEntrada($idProgresoAlumno);
    $query = new Query("SG");
    $query->insert("bitacora_progreso", "fecha, tipo, id_progreso_alumno, permanencia", "now(), 2, $idProgresoAlumno, now()-'$ultimaEntrada'");
    return "ok";
}

/**
 * Funcion que consulta la ultima entrada registarda por un registro de alumno
 * @param type $idProgresoAlumno
 * @return null
 */
function consultaUltimaEntrada($idProgresoAlumno) {
    $query = new Query("SG");
    $query->sql = "select fecha from bitacora_progreso where tipo=0 and id_progreso_alumno=$idProgresoAlumno order by fecha desc";
    $resultSet = $query->select();

    if ($resultSet) {
        foreach ($resultSet as $result) {
            return $result->fecha;
        }
    } else {
        return null;
    }
}
/**
 * Devuelve un string con el navegador actual
 * @return type
 */
function navegadorActual() {
    $ua = getBrowser();
    $yourbrowser = "Your browser: " . $ua['name'];
//    print_r($yourbrowser);
    return $ua['name'];
}
/**
 * Funcion que obtiene la imagen correspondiente a la calificacion obtenida
 * @param type $idElemento
 * @param type $calificacion
 */
function obtenerImagenPremio($idCurso, $calificacion){
    $sql = new Query("SG");
    $sql->sql = "
        select * from equivalencias_numericas where id_curso = $idCurso
        ";
    $resultado = $sql->select("obj");
    $eqs = null;
    foreach ($resultado as $r) {
        $eqs = $r;
        break;
    }
    if($calificacion <= $eqs->rango1 && $calificacion >= 1){//si está en el rango 1
        return "1.png";
    }elseif($eqs->rango1 < $calificacion && $eqs->rango2 >= $calificacion){//si está en el rango 2
        return "2.png";
    }elseif($eqs->rango2 < $calificacion && $eqs->rango3 >= $calificacion){//si está en el rango 3
        return "3.png";
    }elseif($eqs->rango3 < $calificacion && $eqs->rango4 >= $calificacion){//si está en el rango 4
        return "4.png";
    }else{
        return "ninguna";
    }
}
/**
 * Devuelve el nivel de la unidad de un elemento
 * @param type $idElemento
 * @return type
 */
function numeroUnidadDeElemento($idElemento){
    $sql = new Query("SG");
    $sql->sql = "
        select distinct(no_unidad)
        from elemento_aer e
        join serie_aer s
                on s.id_serie_aer = e.id_serie_aer
        join unidades u
                on u.id_unidad = s.id_unidad
        where e.id_elemento_aer = $idElemento
        ";
    $resultado = $sql->select("obj");
    $var = -1;
    foreach ($resultado as $r) {
        $var = $r->no_unidad;
//        return $r;
        break;
    }
    return $var;
//    return $r;
}
?>
