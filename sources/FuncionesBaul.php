<?php
/**
 * Funcion que genera los cursos Asigandos y la tira de series promediadas
 * @param type $idAlumno
 * @param type $idUnidad
 * @return null
 */
function generarCursoAsignadosBaul($idAlumno, $noUnidad)
{
    if( !is_numeric($idAlumno) || !is_numeric($noUnidad))
        return NULL;
    
    $arrayCursosPILA = @getArraysIdsRelCursoGrupo($idAlumno);
    $arrayCursos     = array_pop($arrayCursosPILA);
    $html            = "";
    foreach ($arrayCursos as $idCurso) 
    {
       
        $idUnidad = obtenerIDUnidad($idCurso, $noUnidad);
         
        $html .= '<div id="baul_generico" style="background-image:url('.BASE_STORAGE.'cursos/'.$idCurso.'/frontweb/baul.gif)">
                            <div class="puntuacion_baul">
                                <div class="estrella"><a></a></div>
                                <div class="estrella"><a></a></div>
                                <div class="estrella"><a></a></div>
                            </div>';
//        echo 'UNIDAD:'.$idUnidad;
        if($idUnidad)
        {
            $html .= obtenerSeriesPromediadas($idUnidad, $idCurso,$idAlumno);
            
        }        
            
    
        $html .= '</div>';
        
        
        
    }
    echo $html;
}
/**
 * Retorna true si un alumno tiene cursos asignados, en caso contrario
 * retorna false
 * @param type $idAlumno
 * @return boolean
 */
function tieneCursosAsignados($idAlumno)
{
    $arrayCursosPILA = @getArraysIdsRelCursoGrupo($idAlumno);
    $arrayCursos     = array_pop($arrayCursosPILA);
    
    //echo count( $arrayCursosPILA );
    if( count( $arrayCursos ) > 0)
        return true;
    return false;
    
}

/**
 * Funcion que retorna ID_UNIDAD 
 * @param type $idCurso
 * @param type $noUnidad
 * @return null
 */
function obtenerIDUnidad($idCurso,$noUnidad)
{
    $sql      = new Query("SG");
    $sql->sql =   "SELECT id_unidad as id
                        FROM unidades
                        WHERE   status = 1 
                        AND     id_curso  = ".$idCurso." 
                        AND     no_unidad = ".$noUnidad."
                        LIMIT 1";
    
    $resultado = $sql->select("arr");
    if($resultado)
    {
        return $resultado[0]['id'];
    }
    return NULL;
}


/**
 * 
 * @param type $idUnidad
 */
function obtenerSeriesPromediadas($idUnidad,$idCurso,$idAlumno)
{
    $arraySeries = obtenerSeries($idUnidad);
    $HTML = "";
    $contador = 1;
    foreach ($arraySeries as $serie) 
    {
        if($contador == 7)
            break;
        
        $imagenCalificacion = obtenerPromedioSerie($serie->id,$idAlumno);
        $divisionCalificacion = explode("|",$imagenCalificacion);
        
        if( $divisionCalificacion[0] == '0'  )
        {
            if( esAlumno())
                $HTML .= '<div  class="item_on"><img src="'.BASE_STORAGE.'cursos/'.$idCurso.'/premios/'.'0'.'.png" width="98" height="98" /></div>';
            else if( esPadre())
                $HTML .= '<div class="item_on"  width="98" height="98"><p style="text-align:center;font-size:17px;font-weight:bold; margin-top:30px;">'.'Por cursar'.'</p></div>';
            
        }
        else
       {
            if( esAlumno())
            {
                if($imagenCalificacion)
                    $HTML .= '<div  class="item_on"><img src="'.BASE_STORAGE.'cursos/'.$idCurso.'/premios/'.$divisionCalificacion[0].'.png" width="98" height="98" /></div>';
                else
                    $HTML .= '<div  class="item_on"><img src="'.BASE_STORAGE.'cursos/'.$idCurso.'/premios/'.'0'.'.png" width="98" height="98" /></div>';
                
            }
            else if( esPadre())
            {
                if($imagenCalificacion)
                    $HTML .= '<div class="item_on"  width="98" height="98"><p style="text-align:center;font-size:17px;font-weight:bold; margin-top:30px;">'.$divisionCalificacion[1].'</p></div>';
                else
                    $HTML .= '<div class="item_on"  width="98" height="98"><p style="text-align:center;font-size:17px;font-weight:bold; margin-top:30px;">'.'Por cursar'.'</p></div>';
                
            }
            
            
       }
       $contador++;
    }
    return $HTML;
}

/**
 * Consulta todas las series activas pertenencientes a una Unidad
 * @param type $idUnidad
 * @return type
 */
function obtenerSeries($idUnidad)
{
  $sql      = new Query("SG");
  $sql->sql =   "SELECT id_serie_aer as id 
                FROM serie_aer 
                WHERE id_unidad = ".$idUnidad." and status = 1
                ";
  
  return $sql->select('obj');
}
/**
 * Calcula el promedio de tres elemento A-P-I pertenenecientes a una serie
 * @param type $idSerie
 * @return null
 */
function obtenerPromedioSerie($idSerie,$idAlumno)
{
    $sql      = new Query('SG');
    $sql->sql ="SELECT  avg(pg.calificacion) as promedio,
                    count(*) as noseries,
                    CASE     WHEN (count(*) < 3 ) THEN '0'
                             WHEN (avg(pg.calificacion)<= e_q.rango1 and  avg(pg.calificacion) >=0 ) THEN '1|Insuficiente'
                             WHEN (avg(pg.calificacion)<= e_q.rango2 and  avg(pg.calificacion) >= e_q.rango1 ) THEN '2|Suficiente'
                             WHEN (avg(pg.calificacion) <= e_q.rango3 and  avg(pg.calificacion) >= e_q.rango2 ) THEN '3|Bien'
                             WHEN (avg(pg.calificacion) <= e_q.rango4 and  avg(pg.calificacion) >= e_q.rango3 ) THEN '4|Muy bien'
                                    END as equ
            FROM elemento_aer e_aer
            JOIN progreso_alumno pg
            ON pg.id_elemento_aer = e_aer.id_elemento_aer
            JOIN serie_aer s_aer
            ON s_aer.id_serie_aer = e_aer.id_serie_aer
            JOIN unidades un
            ON un.id_unidad = s_aer.id_unidad
            JOIN equivalencias_numericas e_q
            ON e_q.id_curso = un.id_curso
            WHERE e_aer.status = 1
            AND pg.status = 1
            AND s_aer.status =1
            AND un.status = 1
            AND estatus_evaluacion = 2
            AND e_aer.id_serie_aer =".$idSerie."
             AND pg.id_alumno = ".  $idAlumno."
            GROUP BY e_q.rango1,
                 e_q.rango2,
                 e_q.rango3,
                 e_q.rango4";
    
    
    $resultado = $sql->select('arr');
    
    if($resultado)
    {
        return $resultado[0]['equ']; 
    }
    return NULL;
    
}
/**
 * Genera listado de bloques para baul alumno
 * @param type $noUnidadesTotales
 * @param type $noUnidad
 * @param type $esPadre
 */
function generarBloques($noUnidadesTotales, $noUnidad,$esPadre=NULL)
{
    for ($index = 0; $index < $noUnidadesTotales; $index++)
    {
        $posicionFutura = $index+1;
        if( $posicionFutura == $noUnidad)
            echo '<li><span>Bloque '.$noUnidad.'</span></li>';
        else     
                echo '<li><a href="baul.php?noUnidad='.$posicionFutura.'">Bloque '.$posicionFutura.'</a></li>';
            
    }
}
/**
 * Genera listado de bloques para baul de padre
 * @param type $noUnidadesTotales
 * @param type $noUnidad
 * @param type $idAlumno
 * @param type $idDatosPersonales
 */
function generarBloquesPadre($noUnidadesTotales, $noUnidad,$idAlumno,$idDatosPersonales)
{
    for ($index = 0; $index < $noUnidadesTotales; $index++)
    {
        $posicionFutura = $index+1;
        if( $posicionFutura == $noUnidad)
            echo '<li><span>Bloque '.$noUnidad.'</span></li>';
        else
           echo '<li><a href="familia_cursos.php?noUnidad='.$posicionFutura.'&idAlumno='.$idAlumno.'&idDatosPersonales='.$idDatosPersonales.'">Bloque '.$posicionFutura.'</a></li>';
            
    }
}
?>
