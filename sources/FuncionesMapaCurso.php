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
    
    /**
     * Change control 1.1.1
     * Autor: José Manuel Nieto Gömez
     * Fecha de modificación: 21/11/14
     * Objetivo: La carga de unidaes de curso, no hacía distinción por curso abierto, y se ocacionaba problema
     * cuando habian varias pubicaciones del mismo curso
     */
    if ($alumno == "no") {
        $sql->sql = <<<hhh
        select u.id_unidad as "idUnidad"
        from unidades u 
        where u.id_curso = $idCurso
                and u.status = 1
        order by u.no_unidad
hhh;
    } else {
        //Corrección de consulta
        $sql->sql = <<<hhh
        select u.id_unidad as "idUnidad", to_char(f.fecha_inicio,'YYYY-MM-DD') as "fechaInicio", to_char(f.fecha_fin,'YYYY-MM-DD') as "fechaFin"
        from unidades u 
        join fechas_unidades_cursos f
                on f.id_unidad = u.id_unidad
        join rel_curso_grupo r
		on f.id_curso_abierto = r.id_curso_abierto
        where r.id_rel_curso_grupo = $idRelCursoGrupo
                and u.status = 1
	order by u.no_unidad
hhh;
    }

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


/**
 * Devuelve las unidades de un curso sorm que estÃ¡n activas para el alumno
 * @param type $alumno
 * @param type $idRelCursoGrupo
 * @param type $idCurso
 * @param type $idAlumno
 * @return string
 */
function arregloIdUnidadesMCScorm($alumno, $idRelCursoGrupo, $idCurso, $idAlumno, $tipoEjecucion) {
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql="select id_curso_moodle from cursos where id_curso=$idCurso";
    $cursos = $sql->select("arr");
    foreach($cursos as $cur){
        $cursoSco=$cur["id_curso_moodle"];
    }
    
    $sqlMoodle = new Query("MOD");
    $arrIdUnidades = "[";
    if ($alumno == "no") {
        $sqlMoodle->sql="select launch,m.id from mdl_grade_items gi
                    inner join mdl_scorm s on s.course=gi.courseid
                    inner join mdl_course_modules m on m.course=s.course
                    inner join mdl_course_sections cs on cs.course= m.course
                    where courseid= $cursoSco and itemtype='mod' and gi.iteminstance= s.id and m.section= cs.id
                    and m.instance=s.id and gi.itemname not like '' and s.name not like ''";
        $unidades = $sqlMoodle->select("obj");
        $cont = 0;
        foreach ($unidades as $uni) {
            if ($cont < 5) {
                $arrIdUnidades = $arrIdUnidades . $uni->id . ",";
            } else {
                $arrIdUnidades = $arrIdUnidades . $uni->id;
                break;
            }
            $cont++;
        }
        
        
    } else {
        $sql->sql="SELECT ga.id_grupo_alumno, dp.id_datos_personales, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, a.id_alumno,dp.nombre_usuario,dp.correo
                FROM alumnos a, datos_personales dp, grupo_alumno ga
                WHERE a.id_datos_personales = dp.id_datos_personales
                  and a.id_alumno = ga.id_alumno
                  and a.status=1
                  and ga.id_alumno = $idAlumno";
        $user= $sql->select("arr");
        $username="";
        $mail="";
        foreach($user as $usuario){
            $username= $usuario["nombre_usuario"];
            $mail= $usuario["correo"];
        }              
        $sqlMoodle->sql="select launch,m.id from mdl_grade_items gi
                                        inner join mdl_scorm s on s.course=gi.courseid
                                        inner join mdl_course_modules m on m.course=s.course
                                        inner join mdl_course_sections cs on cs.course= m.course
                                        where courseid= $cursoSco and itemtype='mod' and gi.iteminstance= s.id and m.section= cs.id
                                        and m.instance=s.id and gi.itemname not like '' and s.name not like '' order by m.id";
        $cursos = $sqlMoodle->select("arr");
        $resultado="<div style='clear:both; width:100%'><table style='width:100%'><caption><h3>DESEMPEÃ‘O</h3></caption><tr><th>Bloque</th><th>Nombre</th><th>DescripciÃ³n</th><th>CalificaciÃ³n</th><th>Estatus</th></tr>";
        if($cursos){
            $contador=0;
            $inhabilitado=0;
            $queryScorm= new Query("MOD");
            foreach($cursos as $scorm){
                $contador++;
                $itemId=$scorm["id"];
                $launch=$scorm["launch"];
                $queryScorm->sql="select distinct m.id,finalgrade,t.element,t.value,s.name,s.intro,launch from mdl_grade_items gi 
                                    inner join mdl_grade_grades gg on gg.itemid=gi.id 
                                    inner join mdl_scorm s on s.id= gi.iteminstance 
                                    inner join mdl_course_modules m on m.course=s.course
                                    inner join mdl_scorm_scoes_track t on t.scormid= s.id 
                                    inner join mdl_course c on c.id = gi.courseid 
                                    inner join mdl_enrol e on e.courseid= c.id 
                                    inner join mdl_user_enrolments ue on ue.enrolid=e.id 
                                    inner join mdl_user u on u.id= ue.userid 
                                    and t.userid=u.id
                                    and u.username='$username' and u.email='$mail' and m.id=$itemId and launch =$launch
                                    and t.element like '%cmi.core.lesson%' and gg.userid=u.id";
                //echo  $queryScorm->sql."<br>";
                $ResultSco=$queryScorm->select("arr");
                if($ResultSco){
                    foreach($ResultSco as $cal){
                        switch($cal["value"]){
                            case "incomplete":
                                $bandera=1;
                                break;
                            case "completed":
                                $bandera=1;
                                break;
                            case "not attempted":
                                $bandera=1;
                                break;
                        }
                    }
                }else{
                    $queryScorm->sql="select distinct m.id,t.element,t.value,s.name,s.intro,launch from mdl_grade_items gi 
                                        inner join mdl_scorm s on s.id= gi.iteminstance 
                                        inner join mdl_course_modules m on m.course=s.course
                                        inner join mdl_scorm_scoes_track t on t.scormid= s.id 
                                        inner join mdl_course c on c.id = gi.courseid 
                                        inner join mdl_enrol e on e.courseid= c.id 
                                        inner join mdl_user_enrolments ue on ue.enrolid=e.id 
                                        inner join mdl_user u on u.id= ue.userid 
                                        and t.userid=u.id
                                        and u.username='$username' and u.email='$mail' and m.id=$itemId and launch =$launch
                                        and t.element like '%cmi.core.lesson%'";
                    //echo  $queryScorm->sql."<br>";
                    $ResultSco=$queryScorm->select("arr");
                    if($ResultSco){
                        foreach($ResultSco as $cal){
                            switch($cal["value"]){
                                case "incomplete":
                                    $bandera=1;
                                    break;
                                case "completed":
                                    $bandera=1;
                                    break;
                                case "not attempted":
                                    $bandera=1;
                                    break;
                            }
                        }
                    }else{
                        $inhabilitado++;
                        if($inhabilitado<2){
                            $bandera=1;                            
                        }else{
                            $bandera=0;
                        }
                    }                    
                }
                
                
                $sql->sql = "select u.id_unidad as \"idUnidad\", to_char(f.fecha_inicio,'YYYY-MM-DD') as \"fechaInicio\", to_char(f.fecha_fin,'YYYY-MM-DD') as \"fechaFin\"
                from unidades u 
                join fechas_unidades_cursos f
                        on f.id_unidad = u.id_unidad
                join rel_curso_grupo r
        		on f.id_curso_abierto = r.id_curso_abierto
                where r.id_rel_curso_grupo = $idRelCursoGrupo
                        and u.status = 1 and u.no_unidad= $contador
        	       order by u.no_unidad";    
                $unidades = $sql->select("obj");  
                $hoy = date("Y-m-d");
                if($unidades){
                    foreach ($unidades as $uni) {
                        if (($uni->fechaInicio == null) || ($uni->fechaInicio) <= $hoy && $uni->fechaFin >= $hoy) {
                            if ($tipoEjecucion == 1) { 
                                if ($bandera == 1) {
                                    $arrIdUnidades = $arrIdUnidades . $itemId . ",";
                                } else {
                                    $arrIdUnidades = $arrIdUnidades . '"-",';
                                }
                            }else{
                                $arrIdUnidades = $arrIdUnidades . $itemId . ",";
                            }                            
                        } else {
                            $arrIdUnidades = $arrIdUnidades . '"-",';
                        }
                    }
                }
                
                
                
                /*if ($tipoEjecucion == 1) { 
                    if ($bandera == 1) {
                        $arrIdUnidades = $arrIdUnidades . $itemId . ",";
                    } else {
                        $arrIdUnidades = $arrIdUnidades . '"-",';
                    }
                }else{
                    $sql->sql = "select u.id_unidad as \"idUnidad\", to_char(f.fecha_inicio,'YYYY-MM-DD') as \"fechaInicio\", to_char(f.fecha_fin,'YYYY-MM-DD') as \"fechaFin\"
                    from unidades u 
                    join fechas_unidades_cursos f
                            on f.id_unidad = u.id_unidad
                    join rel_curso_grupo r
            		on f.id_curso_abierto = r.id_curso_abierto
                    where r.id_rel_curso_grupo = $idRelCursoGrupo
                            and u.status = 1 and u.no_unidad= $contador
            	       order by u.no_unidad";    
                    $unidades = $sql->select("obj");   
                    $hoy = date("Y-m-d");
                    if($unidades){
                        foreach ($unidades as $uni) {
                            if (($uni->fechaInicio == null) || ($uni->fechaInicio) <= $hoy && $uni->fechaFin >= $hoy) {
                                $arrIdUnidades = $arrIdUnidades . $itemId . ",";
                            } else {
                                $arrIdUnidades = $arrIdUnidades . '"-",';
                            }
                        }
                    }
                }*/
            }
        }
    $arrIdUnidades = substr($arrIdUnidades, 0, -1);
    }
    $arrIdUnidades = $arrIdUnidades . "]";
    return $arrIdUnidades;
}


?>
