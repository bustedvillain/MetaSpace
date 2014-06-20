<?php

//session_start();
//verificaSesionAlumno();

/**
 * CHANGE CONTROL 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACION: 20 DE JUNIO DE 2014
 * OBJETIVO: AGREGAR CONSULTA PARA VALIDAR EL TIPO DE EJECUCION DE BLOQUES: LIBRE(AUTONOMA), SERIADA
 */

/**
 * Devuelve las unidades de un curso que están activas para el alumno
 * @param type $alumno
 * @param type $idRelCursoGrupo
 * @param type $idCurso
 * @param type $idAlumno
 * @return string
 */
function arregloIdUnidadesMC($alumno, $idRelCursoGrupo, $idCurso, $idAlumno, $tipoEjecucion) {
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql = <<<hhh
        select u.id_unidad as "idUnidad", to_char(f.fecha_inicio,'YYYY-MM-DD') as "fechaInicio", to_char(f.fecha_fin,'YYYY-MM-DD') as "fechaFin"
        from unidades u 
        left join fechas_unidades_cursos f
                on f.id_unidad = u.id_unidad
        where u.id_curso = $idCurso
                and u.status = 1
        order by u.no_unidad
hhh;
    $unidades = $sql->select("obj");
    $arrIdUnidades = "[";
    $cont = 0;

    if ($alumno == "no") {
        foreach ($unidades as $uni) {
            if ($cont < 5) {
                $arrIdUnidades = $arrIdUnidades . $uni->idUnidad . ",";
            } else {
                $arrIdUnidades = $arrIdUnidades . $uni->idUnidad;
                break;
            }
            $cont++;
        }
    } else {
        $hoy = date("Y-m-d");
//        echo $hoy;
//        var_dump($unidades);
        $arrResultados [0] = 0;
        $bandera = 1;
        foreach ($unidades as $uni) {

            //ANTIGUO CODIGO
//            if ($bandera == 1) {
//                $arrIdUnidades = $arrIdUnidades . $uni->idUnidad . ",";
//            } else {
//                $arrIdUnidades = $arrIdUnidades . '"-",';
//            }
////            echo '**'.date_create($uni->fechaInicio).'**';
////            echo $uni->fechaInicio . "$$" . $uni->fechaFin;
//            if (@unidadFueCompletada($idRelCursoGrupo, $uni->idUnidad, $idAlumno) && (($uni->fechaInicio == null) || ($uni->fechaInicio) <= $hoy && $uni->fechaFin >= $hoy)) {
//                $bandera = 1;
//            } else {
//                $bandera = 0;
//            }
//            $cont++;
            //CHANGE CONTROL 1.1.0 - NUEVO CODIGO
            if ($tipoEjecucion == 1) { //Si es seriada, lo hace como antes
                if ($bandera == 1) {
                    $arrIdUnidades = $arrIdUnidades . $uni->idUnidad . ",";
                } else {
                    $arrIdUnidades = $arrIdUnidades . '"-",';
                }

                if (@unidadFueCompletada($idRelCursoGrupo, $uni->idUnidad, $idAlumno) && (($uni->fechaInicio == null) || ($uni->fechaInicio) <= $hoy && $uni->fechaFin >= $hoy)) {
                    $bandera = 1;
                } else {
                    $bandera = 0;
                }
            } else { //Si no especifica seriacion siempre hace de forma autonoma solo validando fechas
                if (($uni->fechaInicio == null) || ($uni->fechaInicio) <= $hoy && $uni->fechaFin >= $hoy) {
                    $arrIdUnidades = $arrIdUnidades . $uni->idUnidad . ",";
                } else {
                    $arrIdUnidades = $arrIdUnidades . '"-",';
                }
            }

            $cont++;
        }

        $arrIdUnidades = substr($arrIdUnidades, 0, strlen($arrIdUnidades));

//        foreach ($unidades as $uni) {
//           if ($cont < 5) {
//                if (@unidadFueCompletada($idRelCursoGrupo, $uni->idUnidad, $idAlumno) && ($uni->fechaInicio === null || ((date_create($uni->fechaInicio) <= $hoy) && (date_create($uni->fechaFin) >= $hoy)))) {
////                if (@unidadFueCompletada($idRelCursoGrupo, $uni->idUnidad)) {
//                    $arrIdUnidades = $arrIdUnidades . $uni->idUnidad . ",";
//                } else {
//                    if ($cont == 0) {
//                        $arrIdUnidades = $arrIdUnidades . $uni->idUnidad . ",";
//                    } else {
//                        $arrIdUnidades = $arrIdUnidades . '"-",';
//                    }
//                }
//            } else {
////                if (@unidadFueCompletada($idRelCursoGrupo, $uni->idUnidad)) {
//                if (@unidadFueCompletada($idRelCursoGrupo, $uni->idUnidad, $idAlumno) && ($uni->fechaInicio === null || ((date_create($uni->fechaInicio) <= $hoy) && (date_create($uni->fechaFin) >= $hoy)))) {
//                    $arrIdUnidades = $arrIdUnidades . $uni->idUnidad;
//                } else {
//                    $arrIdUnidades = $arrIdUnidades . '"-"';
//                }
//                break;
//            }
//            $cont++;
//        }
    }
//    if ($cont < 5) {
//        $arrIdUnidades = substr($arrIdUnidades, 0, count($arrIdUnidades) - 1);
//    }

    $arrIdUnidades = $arrIdUnidades . "]";
    return $arrIdUnidades;
}

/**
 * Metodo que devuelve a través de los parámetros si la unidad fue completada por un alumno
 * @param type $idRelCursoGrupo
 * @param type $idUnidad
 */
function unidadFueCompletada($idRelCursoGrupo, $idUnidad, $idAlumno) {
    $bandera = true;
    include_once 'Query.php';
    $sql2 = new Query("SG");
    $sql2->sql = '
            select e.id_elemento_aer, p.estatus_evaluacion as "estatusEvaluacion"
            from rel_curso_grupo rg
            join progreso_alumno p
                    on rg.id_rel_curso_grupo = p.id_rel_curso_grupo
            join elemento_aer e
                    on p.id_elemento_aer = e.id_elemento_aer
            join serie_aer s
                    on e.id_serie_aer = s.id_serie_aer
            where rg.id_rel_curso_grupo = ' . $idRelCursoGrupo . ' and s.id_unidad = ' . $idUnidad . ' and p.id_alumno = ' . $idAlumno . '
        ';
    $elementos = $sql2->select("obj");
//    echo'------------';
    foreach ($elementos as $ele) {
        if ($ele->estatusEvaluacion != 2) {
            $bandera = false;
            return $bandera;
        }
    }
//    echo'**********';
    return $bandera;
}

?>
