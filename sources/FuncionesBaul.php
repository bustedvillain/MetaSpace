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
 * Funcion que genera los cursos Asigandos y la tira de series promediadas
 * @param type $idAlumno
 * @param type $idUnidad
 * @return null
 */
function generarCursoAsignadosBaulScorm($idAlumno, $noUnidad)
{
    if( !is_numeric($idAlumno) || !is_numeric($noUnidad))
        return NULL;
    
    $arrayCursosPILA = @getArraysIdsRelCursoGrupoScorm($idAlumno);
    $arrayCursos     = array_pop($arrayCursosPILA);
    $arrayCursos=array_unique($arrayCursos);
    //print_r($arrayCursos);
    $html            = "";
    foreach ($arrayCursos as $idCurso) 
    {
       
        //$idUnidad = obtenerIDUnidad($idCurso, $noUnidad);
         
        $html .= '<div id="baul_generico" style="background-image:url('.BASE_STORAGE.'cursos/'.$idCurso.'/frontweb/baul.gif)">
                            <div class="puntuacion_baul">
                                <div class="estrella"><a></a></div>
                                <div class="estrella"><a></a></div>
                                <div class="estrella"><a></a></div>
                            </div>';
//        echo 'UNIDAD:'.$idUnidad;
        if($noUnidad)
        {
            $html .= obtenerSeriesPromediadasScorm($noUnidad, $idCurso,$idAlumno);
            
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
    $arrayCursosPILA = getArraysIdsRelCursoGrupo($idAlumno);
    $arrayCursos     = array_pop($arrayCursosPILA);
    
    //echo count( $arrayCursosPILA );
    if( count( $arrayCursos ) > 0)
        return true;
    return false;
    
}

/**
 * Retorna true si un alumno tiene cursos scorm asignados, en caso contrario
 * retorna false
 * @param type $idAlumno
 * @return boolean
 */
function tieneCursosAsignadosScorm($idAlumno)
{
    $arrayCursosPILA = @getArraysIdsRelCursoGrupoScorm($idAlumno);
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
 * Obtiene la seriaciÃ³n y promedios del curso Scorm
 * @param type $idUnidad, idCurso,idAlumno
 */
function obtenerSeriesPromediadasScorm($idUnidad,$idCurso,$idAlumno)
{
    $modulo= $idUnidad;
    $HTML = "";
    $contador = 1;
    //foreach ($arraySeries as $serie) 
    //{
        if($contador == 7)
            break;
        
        $imagenCalificacion = obtenerPromedioScorm($modulo,$idAlumno,$idCurso);
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
       //$contador++;
    //}
    return $HTML;
}


/**
 * Calcula el promedio Scrom por bloque
 * @param type $idSerie
 * @return null
 */
function obtenerPromedioScorm($modulo,$idAlumno,$idCurso)
{
     $query = new Query("SG");
    $queryMoodle = new Query("MOD");
     $query->sql="select id_curso_moodle from cursos where id_curso=$idCurso";
     $cursos = $query->select("arr");
     foreach($cursos as $cur){
        $cursoMoodle=$cur["id_curso_moodle"];
     }

    $query->sql="SELECT dp.nombre_usuario,dp.correo
        FROM alumnos a, datos_personales dp, grupo_alumno ga
        WHERE a.id_datos_personales = dp.id_datos_personales
          and a.id_alumno = ga.id_alumno
          and a.status=1
          and a.id_alumno = $idAlumno";
    $al = $query->select("arr");
    $username="";
    $mail="";
     foreach($al as $datos){
        $username=$datos["nombre_usuario"];
        $mail=$datos["correo"];
     }
     
    $queryMoodle->sql="select launch,m.id from mdl_grade_items gi
                                    inner join mdl_scorm s on s.course=gi.courseid
                                    inner join mdl_course_modules m on m.course=s.course
                                    inner join mdl_course_sections cs on cs.course= m.course
                                    where courseid= $cursoMoodle and itemtype='mod' and gi.iteminstance= s.id and m.section= cs.id
                                    and m.instance=s.id and cs.section=$modulo and gi.itemname not like '' and s.name not like ''";
    $curso = $queryMoodle->select("arr");
    foreach($curso as $course){
        $queryMoodle2 = new Query("MOD");
        $itemId=$course["id"];
        $launch=$course["launch"];
        $queryMoodle2->sql="select distinct m.id,finalgrade,t.element,t.value,s.name,s.intro,launch from mdl_grade_items gi 
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
        //echo $queryMoodle->sql;
        $ResultSco=$queryMoodle2->select("arr");
        $calificacion=0;
                if($ResultSco){
                    foreach($ResultSco as $cal){
                        $calificacion=$cal["finalgrade"];
                        
                    }
                }else{
                    $calificacion=0;
                }     
        }
        
        $query->sql="select rango1,rango2,rango3,rango4 from equivalencias_numericas where id_curso=$idCurso";
        //echo $query->sql;
        $rangos=$query->select("arr");
        $resultado="";
        foreach($rangos as $rango){
            if($calificacion<=$rango["rango1"] && $calificacion>=0){
                $resultado="1|Insuficiente";
            }elseif($calificacion<=$rango["rango2"] && $calificacion>$rango["rango1"]){
                $resultado="2|Suficiente";
            }elseif($calificacion<=$rango["rango3"] && $calificacion>$rango["rango2"]){
                $resultado="3|Bien";
            }elseif($calificacion<=$rango["rango4"] && $calificacion>$rango["rango3"]){
                $resultado="4|Muy bien";
            }
        }
        return $resultado;
        
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
