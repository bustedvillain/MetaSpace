<?php
//   Date             Modified by         Change(s)
//   2013-10-31         HMP                 1.0

/**
 * @author HMP
 * Funcion que obtiene y genera contendido HTML todas las series a partir de un idUnidad
 * @param type $idUnidad}
 * @param type $idSerie Representa el Id del curso_grupo que representa el valor especifico de la calificacion del alumno
 * Representa el Id Numerico de la Unidad
 * 
 */
function obtenerNoUnidad($idUnidad)
{
    
    if(!is_numeric($idUnidad))
        return;
  
    $sql=new Query("SG");
    $sql->sql='SELECT no_unidad
                FROM unidades
                WHERE id_unidad ='.$idUnidad.' 
                AND status = 1 ';
    
    $seriesUnidad = $sql->select("arr");
    if($seriesUnidad)
    {
        return $seriesUnidad[0]['no_unidad'];
    }
}

/**
 * @author HMP
 * Funcion que obtiene y genera contendido HTML todas las series a partir de un idUnidad
 * @param type $idUnidad}
 * @param type $idSerie Representa el Id del curso_grupo que representa el valor especifico de la calificacion del alumno
 * Representa el Id Numerico de la Unidad
 * 
 */
function generarSeries($idUnidad, $idRelCursoGrupo,$idAlumno)
{
    
    if(!is_numeric($idUnidad))
        return;
    if(!is_numeric($idRelCursoGrupo))
        return;
    
    $sql=new Query("SG");
    $sql->sql='SELECT id_serie_aer as id,
                      nivel_serie_aer as nivel 
                FROM serie_aer 
                WHERE id_unidad='.$idUnidad.'
                AND status = 1 
                ORDER BY nivel ASC';
    
    $seriesUnidad = $sql->select("obj");
    if($seriesUnidad)
    {
        $html = "";   
        foreach ($seriesUnidad as $serie) 
        {
            $html .= ' <div class="grid_4">';
            $html .= '<div class="letraP">Serie '.$serie->nivel.'</div>';
            $res = generaElementosAER($serie->id, $idRelCursoGrupo,$idAlumno);
            $html .=$res;
            $html .= '</div>';
        }
        echo $html;
    }
}

/**
 * @author HMP
 * Funcion que genera HTML de los elementos AER de una Serie en especifica
 * @param type $idSerie Representa el Id del curso_grupo que representa el valor especifico de la calificacion del alumno
 * Representa el Id Numerico de la Serie AER
 * @param type $idRelCursoGrupo
 * @see La consulta obtiene los elementos AER(Actividad, Ejercicio, Reto) y se ordenan
 * bajo ese orden por 'ORDER BY tp.nombre_tipo ASC'.
 * La consulta se limita a 3 como maximo por 'LIMIT 3;'
 * 
 * En caso de que la consulta no devuelva nada, se genera un elemento AER que no se 
 * muestra en el navegador para respetar el CSS
 */
function generaElementosAER($idSerie, $idRelCursoGrupo,$idAlumno)
{
    $sql=new Query("SG");
    $sql->sql='SELECT aer.id_elemento_aer as "idElemento",
                      lower(substring(tp.nombre_tipo from 1 for 1)) as "tipoElemento"
                FROM elemento_aer aer
                JOIN  tipo_elemento tp
                        ON tp.id_tipo_elemento = aer.id_tipo_elemento
                WHERE aer.id_serie_aer = '.$idSerie.'
                      AND tp.status = 1
                      AND  aer.status = 1 
                
                LIMIT 3;';
    
    $elementoAER= $sql->select("obj");
    
    if( $elementoAER)
    {  
        
        $html=""; 
        foreach ($elementoAER as $elemento)
        {
            $evaluacion = verificarEvaluacionElementoAER($elemento->idElemento, $idRelCursoGrupo,$idAlumno);                        
            $color  = $evaluacion[0];
            $imagen = $evaluacion[1];
            $curso  = $evaluacion[2];
            $html   .='<div class="aer">';                        
            $html   .= '<div class="letra '.$color.'">'.$elemento->tipoElemento.'</div>';
//            $html .=  '<div>
//                        <div class="aro"></div>
//                        <div class="aroBlanco"></div>
//                        <div class="circulo '.$color.'"></div>     
//                       </div>';
            $html .='<div class="trofeo">';
            if($imagen)
                $html .= '<img src="'.BASE_STORAGE.'cursos/'.$curso.'/premios/'.$imagen.'.png"/>';
            else
                $html .= '<span>?</span>';
            
            $html .= '</div>';
            $html .= '</div>';
          }
        return $html;

    }else 
    {
        return '<div class="aer" style="visibility:hidden">
                        <div class="letra colorR">R</div>
                            <div>
                                <div class="aro"></div>
                                <div class="aroBlanco"></div>
                                <div class="circulo"></div>     
                            </div>
                        <div class="trofeo">
                             <span>?</span>
                        </div>
                    </div>';
    }
    return null;
    
}

/**
 * @author HMP
 * Funcion que verifica el estado de la evaluacion del elemento AER
 * @param type $idElementoAER
 * @param type $idRelCursoGrupo Representa el Id del curso_grupo que representa el valor especifico de la calificacion del alumno
 * @return array
 * contiene la url de la Imagen a mostrar y el color para el css
 */
function verificarEvaluacionElementoAER($idElementoAER, $idRelCursoGrupo,$idAlumno)
{
    $sql=new Query("SG");
    $sql->sql='SELECT pg.calificacion as calificacion,
               ca.id_curso as "idCurso",
                CASE 
                 WHEN (pg.calificacion <= e_q.rango1 and  pg.calificacion >=0 and pg.estatus_evaluacion = 2) THEN \'1\'
                 WHEN (pg.calificacion <= e_q.rango2 and  pg.calificacion >= e_q.rango1 and pg.estatus_evaluacion = 2) THEN \'2\'
                 WHEN (pg.calificacion <= e_q.rango3 and  pg.calificacion >= e_q.rango2 and pg.estatus_evaluacion = 2) THEN \'3\'
                 WHEN (pg.calificacion <= e_q.rango4 and  pg.calificacion >= e_q.rango3 and pg.estatus_evaluacion = 2) THEN \'4\'
                        END
                        as "imagen"  
                        ,
                CASE pg.estatus_evaluacion
                 WHEN 0 THEN \'grisC\'
                 WHEN 1 THEN \'grisO\'
                 WHEN 2 THEN \'negro\'
                 ELSE \'other\'
                        END
                        as "colorEstado"     
         FROM progreso_alumno pg
         JOIN rel_curso_grupo r_c_g
                 ON r_c_g.id_rel_curso_grupo = pg.id_rel_curso_grupo
         JOIN cursos_abiertos ca
                 ON ca.id_curso_abierto = r_c_g.id_curso_abierto
         JOIN equivalencias_numericas e_q
                 ON e_q.id_curso = ca.id_curso
         WHERE id_elemento_aer ='.$idElementoAER.' 
               AND pg.id_rel_curso_grupo = '.$idRelCursoGrupo.'
               AND pg.id_alumno = '.$idAlumno.' 
               AND pg.status = 1';
    
  $evaluacion= $sql->select("arr");
  
  if($evaluacion)
  {
      return  array($evaluacion[0]['colorEstado'],
                    $evaluacion[0]['imagen'],
                    $evaluacion[0]['idCurso']);
  }
    
  return null;
    
}
/**
 * @author HMP
 * Funcion que genera el porcentaje del avance de la Unidad
 * Contando todos los elementos aer que tengas el estatus de evaluacion = 2 , el resultado es
 * multiplicado por 100 y dividiendolo entre el total de elementos aer de la Unidad
 * @param type $idUnidad
 * @param type $idRelCursoGrupo Representa el Id del curso_grupo que representa el valor especifico de la calificacion del alumno
 * @return porcentaje de avance
 */
function porcentajeAvance($idUnidad, $idRelCursoGrupo,$idAlumno)
{
    
    if(!is_numeric($idUnidad))
        return;
    if(!is_numeric($idRelCursoGrupo))
        return;
    
    
    
    $sql=new Query("SG");
    $sql->sql='SELECT (            (
                                    (SELECT count(aer.id_elemento_aer) 
                                            FROM  elemento_aer aer 
                                            JOIN  progreso_alumno pg
                                                    ON pg.id_elemento_aer = aer.id_elemento_aer
                                            WHERE aer.id_serie_aer IN (SELECT id_serie_aer FROM serie_aer WHERE  id_unidad = '.$idUnidad.')
                                                  AND pg.estatus_evaluacion = 2 
                                                  AND pg.id_rel_curso_grupo ='.$idRelCursoGrupo.'
                                                  AND pg.id_alumno ='.$idAlumno.'
                                                  AND aer.status = 1 
                                                  AND pg.status =1) * 100 
                                     ) 
                                              / (count(id_elemento_aer))
                       ) 
                                    as "porcentajeAvance"
                                    FROM  elemento_aer 
                                    WHERE id_serie_aer IN (SELECT id_serie_aer FROM serie_aer WHERE  id_unidad ='.$idUnidad.') 
                                          AND elemento_aer.status = 1';
    
    $porcentaje = $sql->select("arr");
    
    if($porcentaje)
    {
        return $porcentaje[0]['porcentajeAvance'];
    }
    
}
/**
 * @author HMP
 * Funcion que genera la imagen a mostrar al sacar el promedio de todos los elementos aer en progreso
 * alumno con status_evaluacion = 2 y comparando con la tabla de equivalencias para la asignacion de trofeo
 * @param type $idUnidad
 * @param type $idRelCursoGrupo Representa el Id del curso_grupo que representa el valor especifico de la calificacion del alumno
 * @return imagen a mostar o signo [?] sea el caso
 */
function obtenerImagenPromedioGeneral($idUnidad, $idRelCursoGrupo,$idAlumno)
{
    if(!is_numeric($idUnidad))
        return;
    if(!is_numeric($idRelCursoGrupo))
        return;
    
    $sql=new Query("SG");
    $sql->sql='SELECT 
                        CASE 
                        WHEN (avg(pg.calificacion) <= e_q.rango1 and  avg(pg.calificacion) >=0 ) THEN \'1\'
                        WHEN (avg(pg.calificacion) <= e_q.rango2 and  avg(pg.calificacion) >= e_q.rango1 ) THEN \'2\'
                        WHEN (avg(pg.calificacion) <= e_q.rango3 and  avg(pg.calificacion) >= e_q.rango2 ) THEN \'3\'
                        WHEN (avg(pg.calificacion) <= e_q.rango4 and  avg(pg.calificacion) >= e_q.rango3 ) THEN \'4\'
                               END
                               as "imagen",
                        cu.id_curso as "idCurso"
                        FROM  elemento_aer aer 
                        JOIN  progreso_alumno pg
                                ON pg.id_elemento_aer = aer.id_elemento_aer
                        JOIN rel_curso_grupo r_c_g
                                ON r_c_g.id_rel_curso_grupo = pg.id_rel_curso_grupo
                        JOIN cursos_abiertos c_a
                                ON c_a.id_curso_abierto = r_c_g.id_curso_abierto
                        JOIN cursos cu
                                ON cu.id_curso = c_a.id_curso
                        JOIN equivalencias_numericas e_q
                                ON e_q.id_curso = cu.id_curso
                        WHERE aer.id_serie_aer IN (SELECT id_serie_aer FROM serie_aer WHERE serie_aer.status = 1 AND id_unidad = '.$idUnidad.')
                              AND pg.estatus_evaluacion = 2
                              AND pg.id_rel_curso_grupo ='.$idRelCursoGrupo.'
                              AND pg.id_alumno ='.$idAlumno.' 
                              AND pg.status = 1
                              AND aer.status = 1
                        GROUP BY pg.id_rel_curso_grupo,
                                 e_q.rango1,
                                 e_q.rango2,
                                 e_q.rango3,
                                 e_q.rango4,
                                 cu.id_curso';
    
    $promedio = $sql->select("arr");

    if($promedio)
        return '<img src="'.BASE_STORAGE.'cursos/'.$promedio[0]['idCurso'].'/premios/'.$promedio[0]['imagen'].'.png"/>';
    else
        return '<span>?</span>';
    
    
}
?>
