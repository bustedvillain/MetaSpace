<?php
//   Date             Modified by         Change(s)
//   2013-10-03         HMP                 1.0

function verificarSesionCoordinador()
{
    if(!esCoordinador())
        header('Location:../');
}



/**
 * @author HMP
 * Obtiene el ID de Rol_Tutor en base al tipo ya sea Senior,Junior, Coordinador 
 * @param $nombre tipo de Tutor
 */
function obtenerIdRolTutor($nombre)
{
    if(!$nombre)
        return null;
    
     $sql = "SELECT id_rol_tutor as id 
             FROM rol_tutor
             WHERE nombre LIKE '".$nombre."' AND
                   status = 1";
    
    $consulta= new Query("SG");
    $consulta->sql=$sql;
    $resultado = $consulta->select("arr");
    
    if($resultado)
    {
        return $resultado[0]['id'];
    }
    return null;
}

/**
 * @author HMP
 * Funcion que hace la consulta de todos los tutores (Junior, Senior) pertenecientes 
 * a un tutor coordinador o senior sea el caso y Privilegio quien lo llame
 * @param type $idTutorCoordinador
 * Representa el Id Numerico del Tutor
 * @param type $rolTutor
 * Parametro para saber que tutor a buscar ya sea Senior ó Junior
 */
function consultaTutoresCursos($idTutorCoordinador,$rolTutor)
{
    
   $rolTutor = obtenerIdRolTutor($rolTutor);
  
   if(!$rolTutor)
        return null;
    
   $sql = "SELECT DISTINCT tu.id_tutor  as \"idTutor\",
            dp.nombre_pila as \"nombrePila\", 
            dp.primer_apellido||' '||dp.segundo_apellido as apellidos,
            cu.nombre_curso as \"nombreCurso\",
            c_a.nombre_curso_abierto as \"nombreCursoAbierto\"
        FROM rel_curso_tutor r_c_t
        JOIN tutor tu
                ON tu.id_tutor = r_c_t.id_tutor
        JOIN rel_curso_grupo r_c_g
                ON r_c_g.id_rel_curso_grupo  = r_c_t.id_rel_curso_grupo 
        JOIN cursos_abiertos c_a
                ON c_a.id_curso_abierto = r_c_g.id_curso_abierto
        JOIN cursos cu
                ON c_a.id_curso = cu.id_curso	 
        JOIN datos_personales dp
                ON dp.id_datos_personales = tu.id_datos_personales
        WHERE tu.status = 1
        AND c_a.status = 1
        AND cu.status  = 1
        AND tu.id_rol_tutor = ".$rolTutor."
        AND c_a.id_curso_abierto  IN (SELECT    rcg.id_curso_abierto
                                                FROM rel_curso_tutor rct
					        JOIN rel_curso_grupo rcg
							ON rct.id_rel_curso_grupo = rcg.id_rel_curso_grupo
                                                 WHERE rct.id_tutor =  ".$idTutorCoordinador.")";
    
    $consultaGrupo= new Query("SG");
    $consultaGrupo->sql=$sql;
    $resultado = $consultaGrupo->select("obj");
    return $resultado;   
    
}


/**
 * @author HMP
 * Funcion que genera la Tabla con los Tutores Senior pertenecientes a un Tutor Coordinador
 * @param type $idTutorCoordinador
 */
function tablaTutoresSenior($idTutorCoordinador)
{
   if(!is_numeric($idTutorCoordinador))
        return NULL;
   
      $arrayTutoresSenior = consultaTutoresCursos($idTutorCoordinador,"Senior");
    
    if($arrayTutoresSenior)
    {
        foreach ($arrayTutoresSenior as $tutor) {
            echo <<<HTML
            <tr>
                <td>
                <a class = "icon-eye-open verPerfil" href = "#verModal" onclick="verPerfilPorTutor($tutor->idTutor);" role = "button" data-toggle = "modal" title = "Ver"></a>
                <a class = "icon-info-sign" href="#actividadTutorModal" onclick="verActividadPorTutor($tutor->idTutor);"  role = "button" data-toggle = "modal" title = "Ver Informacion de Actividad"></a>
                <a class = "icon-tags" href="gruposTutorSenior.php?idTutor=$tutor->idTutor" title = "Ver Grupos de &eacute;ste tutor"></a>
                 </td>
                <td>$tutor->idTutor</td>
                <td>$tutor->nombrePila</td>
                <td>$tutor->apellidos</td> 
                <td>$tutor->nombreCurso</td>
                <td>$tutor->nombreCursoAbierto</td>
            </tr>
HTML;
        } 
        
    }
   
}

/**
 * @author HMP
 * Funcion que genera la Tabla con los Tutores Senior pertenecientes a un Tutor Junior
 * @param type $idTutorCoordinador
 */
function tablaTutoresJunior($idTutorCoordinador)
{
   if(!is_numeric($idTutorCoordinador))
        return NULL;
   
      $arrayTutoresJunior = consultaTutoresCursos($idTutorCoordinador,"Junior");
    
    if($arrayTutoresJunior)
    {
        foreach ($arrayTutoresJunior as $tutor) {
            echo <<<HTML
            <tr>
                <td>
                <a class = "icon-eye-open verPerfil" href = "#verModal" onclick="verPerfilPorTutor($tutor->idTutor);" role = "button" data-toggle = "modal" title = "Ver"></a>
                <a class = "icon-info-sign" href="#actividadTutorModal" onclick="verActividadPorTutor($tutor->idTutor);"  role = "button" data-toggle = "modal" title = "Ver Informacion de Actividad"></a>
                <a class = "icon-tags" href="gruposTutorJr.php?idTutor=$tutor->idTutor" title = "Ver Grupos de &eacute;ste tutor"></a>
                    
                </td>
                <td>$tutor->idTutor</td>
                <td>$tutor->nombrePila</td>
                <td>$tutor->apellidos</td>
                <td>$tutor->nombreCurso</td>
                <td>$tutor->nombreCursoAbierto</td>    
            </tr>
HTML;
        } 
        
    }
   
}


/**
 * @deprecated since version number
 * @param type $tipoTutor
 * 1 Jr
 * 2 Senior
 * 3 Coordinador
 * @param type $tipoListado
 * 0 Para grupos de tutores JR
 * 1 Para grupos de tutores Senior
 * 2 Listado de Grupos a si mismo
 */

function generarBreadCrumbTutorGrupos($tipoTutor,$tipoListado,$nombreTutor)
{
    switch ($tipoTutor) {
        case 'Junior':
            break;
        case 'Senior':
            
            break;
        case 'Coordinador':
            
            $retornoHMTL = '<ul class="breadcrumb">
                    <li><a href="index.php">Inicio</a> <span class="divider">/</span></li>';   
            switch ($tipoListado) {
                case '0':
                    $retornoHMTL .= '<li><a href="tutoresJrAsignados.php">Tutores Jr</a> <span class="divider">/</span></li>';
                    $retornoHMTL .= '<li class="active">Grupos</li>';
                    break;
                case '1':
                    $retornoHMTL .= '<li><a href="tutoresSeniorAsignados.php">Tutores Senior</a> <span class="divider">/</span></li>';
                    $retornoHMTL .= '<li class="active">Grupos</li>';
                    break;
                case '2':
                    $retornoHMTL .= '<li class="active">Grupos</li>';
                    break;
                default:
                    break;
            }
            
            $retornoHMTL .= '</ul>'; 
            if($tipoListado != '2')
            {
                $retornoHMTL .= '<h4>Tutor: '.__($nombreTutor).'</h4>';
            }
            
               echo $retornoHMTL;      
            break;
        default:
            break;
    }   
}

/**
 * @deprecated since version number
 * @param type $tipoTutor
 * Junior
 * Senior
 * Coordinador
 * @param type $tipoListado
 * 0 Grupos Asignados a un Tutor JR
 * 1 Alumnos Asignados a un Grupo, perteneciente a Tutor Jr
 */
function generarBreadCrumb($tipoTutor,$tipoListado,$idGrupo)
{
    switch ($tipoTutor) {
        case 'Junior':// Tutor Junior
            $retornoHMTL = '<ul class="breadcrumb">';
            switch ($tipoListado) 
            {
                case '0'://Grupos Asignados
                    $retornoHMTL .= '<li><a href="index.php">Inicio</a> <span class="divider">/</span></li>';
                    $retornoHMTL .= '<li class="active">Grupos</li>';
                    $retornoHMTL .= '</ul>';
                    break;
                case '1'://Alumnos Asignados a un Grupo
                   $retornoHMTL .= '<li><a href="index.php">Inicio</a> <span class="divider">/</span></li>';
                   $retornoHMTL .= '<li><a href="gruposAsignados.php">Grupos</a> <span class="divider">/</span></li>';
                   $retornoHMTL .= '<li class="active">Alumnos</li>';
                   $retornoHMTL .= '</ul>';
                   $retornoHMTL .= '<h4>Grupo: '.nombreGrupo($idGrupo).'</h4>';
                    break;
                default:
                    break;
            }
            
            echo $retornoHMTL;    
            break;
        case 'Senior'://Tutor 
            
            break;
        case 'Coordinador'://Tutor Coordinador
            
            $retornoHMTL = '<ul class="breadcrumb">
                    <li><a href="index.php">Inicio</a> <span class="divider">/</span></li>';
            
            switch ($tipoListado) {
                case '7':
                    $retornoHMTL .= '<li><a href="tutoresJrAsignados.php">Tutores Jr</a> <span class="divider">/</span></li>';
                    $retornoHMTL .= '<li><a href="gruposAsignados.php?idTutor='.obtenerIDTabla().'&tipoListado='.$tipoListado.'&nombreTutor='.$nombreTutor.'">Grupos</a> <span class="divider">/</span></li>';
                    $retornoHMTL .= '<li class="active">Alumnos</li>';
                    $retornoHMTL .= '</ul>';
                    $retornoHMTL .= '<h4>Tutor: '.__($nombreTutor).'</h4>';
                    $retornoHMTL .= '<h4>Grupo: '.__($nombreGrupo).'</h4>';
                    break;
                case '8':
                    $retornoHMTL .= '<li><a href="tutoresSeniorAsignados.php">Tutores Senior</a> <span class="divider">/</span></li>';
                    $retornoHMTL .= '<li><a href="gruposAsignados.php?idTutor='.obtenerIDTabla().'&tipoListado='.$tipoListado.'&nombreTutor='.$nombreTutor.'">Grupos</a> <span class="divider">/</span></li>';
                    $retornoHMTL .= '<li class="active">Alumnos</li>';
                    $retornoHMTL .= '</ul>';
                    $retornoHMTL .= '<h4>Tutor: '.__($nombreTutor).'</h4>';
                     $retornoHMTL .= '<h4>Grupo: '.__($nombreGrupo).'</h4>';
                    break;
                case '9':
                    $retornoHMTL .= '<li><a href="gruposAsignados.php?idTutor='.obtenerIDTabla().'&tipoListado=2">Grupos</a> <span class="divider">/</span></li>';
                    $retornoHMTL .= '<li class="active">'.$nombreGrupo.'</li>';
                    $retornoHMTL .= '</ul>';
                    $retornoHMTL .= '<h4>Grupo: '.__($nombreGrupo).'</h4>';
                    break;
                case '10':
                    $retornoHMTL .= '<li><a href="gruposAsignados.php?idTutor='.obtenerIDTabla().'&tipoListado=2">Grupos</a> <span class="divider">/</span></li>';
                    $retornoHMTL .= '<li class="active">'.$nombreGrupo.'</li>';
                    $retornoHMTL .= '</ul>';
                    $retornoHMTL .= '<h4>Grupo: '.__($nombreGrupo).'</h4>';
                    break;
                case '11':
                    $retornoHMTL .= '<li class="active">Grupos <span class="divider">/</span></li>';
                    break;
                case '12':
                    $retornoHMTL .= '<li><a>Grupos</a> <span class="divider">/</span></li>';
                    $retornoHMTL .= '<li class="active">Alumnos<span class="divider">/</span></li>';
                    break;
                default:
                    break;
            }
            echo $retornoHMTL;      
            break;
        default:
            echo "NO";
            break;
    }   
}
/**
 * @deprecated since version number
 * @param type $idGrupo
 */
//function nombreGrupo($idGrupo)
//{
//    if(!is_numeric($idGrupo))
//        return null;;
//    
//    
//     $sql = "SELECT nombre_grupo as nombre 
//             FROM grupo
//             WHERE status = 1 and id_grupo=".$idGrupo;
//    
//    $consultaGrupo= new Query("SG");
//    $consultaGrupo->sql=$sql;
//    $resultado = $consultaGrupo->select("arr");
//    
//    if($resultado)
//    {
//        return $resultado[0]['nombre'];
//    }
//    return null;
//}


/**
 * @author HML
 * Devuelve el Nombre del Tutor 
 * @param type $idTutor
 */
function nombreTutor($idTutor)
{
    if(!is_numeric($idTutor))
        {return null;}
    
     $sql = "SELECT dp.nombre_pila ||' '||dp.primer_apellido||' '||dp.segundo_apellido as nombre
            FROM tutor tu
            JOIN datos_personales dp
            ON tu.id_datos_personales = dp.id_datos_personales
            WHERE tu.id_tutor = ".$idTutor."
                  AND tu.status = 1";
    
    $consultaTutor = new Query("SG");
    $consultaTutor->sql=$sql;
    $resultado = $consultaTutor->select("arr");
    
    if($resultado)
    {
        return $resultado[0]['nombre'];
    }
    return null;
}


/**
 * @author HML
 * Genera la tabla de tutores JR relacionados a un Grupo 
 * @param type $idGrupo ID grupo
 */
function tablaTutoresJuniorGrupo($idGrupo)
{
       if( !is_numeric($idGrupo))
           return NULL;
   
    $arrayTutoresJunior = cosultaTablaTutor_Grupo($idGrupo,"Junior");
    
    if($arrayTutoresJunior)
    {
            foreach ($arrayTutoresJunior as $tutor) {
            echo <<<HTML
            <tr>
                <td>
                <a class = "icon-eye-open verDatosPersonales"  name="tutor" id="$tutor->id_datos_personales"href = "#verModal"  role = "button" data-toggle = "modal" title = "Ver"></a>
                <a class = "icon-info-sign" href="#actividadTutorModal" onclick="verActividadPorTutor($tutor->idTutor);"  role = "button" data-toggle = "modal" title = "Ver Informacion de Actividad"></a>
                 
                </td>
                <td>$tutor->idTutor</td>
                <td>$tutor->nombrePila</td>
                <td>$tutor->apellidos</td>   
            </tr>
HTML;
        } 
        
        
    }
    
}

/**
 * @author HMP
 * Genera tabla de tutores Senior asigandos a un Grupo
 * @param type $idGrupo ID del grupo
 */
function tablaTutoresSeniorGrupo( $idGrupo)
{
       if(!is_numeric($idGrupo))
           return NULL;
   
    $arrayTutoresSenior = cosultaTablaTutor_Grupo($idGrupo,"Senior");
    
    if($arrayTutoresSenior)
    {
            foreach ($arrayTutoresSenior as $tutor) {
            echo <<<HTML
            <tr>
                <td>
                <a class = "icon-eye-open verDatosPersonales" href = "#verModal" name="tutor" id="$tutor->id_datos_personales" role = "button" data-toggle = "modal" title = "Ver"></a>
                <a class = "icon-info-sign" href="#actividadTutorModal" onclick="verActividadPorTutor($tutor->idTutor);"  role = "button" data-toggle = "modal" title = "Ver Informacion de Actividad"></a>
                </td>
                <td>$tutor->idTutor</td>
                <td>$tutor->nombrePila</td>
                <td>$tutor->apellidos</td>   
            </tr>
HTML;
        } 
    }
    
}

/**
 * Genera el contenido de la tabla que contiene todos los  grupos 
 * relacionados a un curso.
 * @uses function consultaGruposRelacionCursoAbierto($idCursoAbierto,$idTutor)
 * @param type $idCursoAbierto
 */
function tablaGruposTutorCoordinador($idCursoAbierto,$idTutor)
{
  
    if(!is_numeric($idCursoAbierto))
        return NULL;
    
    if(!is_numeric($idTutor))
        return NULL;
    
    
    $arrayGrupos = consultaGruposRelacionCursoAbierto($idCursoAbierto,$idTutor);
    if($arrayGrupos)
    {
        foreach ($arrayGrupos as $grupo) {
            $nombreInstitucion = html_entity_decode(getNombreInstitucion($grupo->idEscuela));
            $nombreEscuela = getNombreEscuela($grupo->idEscuela);
            $nombreEmpresa = html_entity_decode(getNombreEmpresa($grupo->idEmpresa));
            $nAlumnos = getCantidadAlumnosGrupo($grupo->id);
            
            if ($grupo->tipoGrupo == 0) {
                $tipoGrupo = "Escuela";
            } else {
                $tipoGrupo = "Empresa";
            }
            echo <<<HTML
            <tr>
                <td><a class="icon-user icon-black" href="alumnosAsignadosGrupo.php?idGrupo=$grupo->id&idCursoAbierto=$idCursoAbierto" role="button" data-toggle="modal" title="Ver Alumno"></a>
                <a class="icon-book icon-black" href="tutoresJrAsignadosGrupo.php?idGrupo=$grupo->id&idCursoAbierto=$idCursoAbierto" role="button" data-toggle="modal" title="Ver Tutores Junior"></a>
                <a class="icon-bookmark icon-black" href="tutoresSeniorAsignadosGrupo.php?idGrupo=$grupo->id&idCursoAbierto=$idCursoAbierto" role="button" data-toggle="modal" title="Ver Tutores Senior"></a>
                </td>
                <td>$nAlumnos</td>
                <td>$grupo->nombre</td>
                <td>$grupo->clave</td>
                <td>$tipoGrupo</td>
                <td>$nombreInstitucion</td>
                <td>$nombreEscuela</td>
                <td>$nombreEmpresa</td>    
            </tr>
HTML;
        } 
        
    }
}

/**
 * Funcion
 * @param type $idTutor
 */
//function tablaGruposTutor($idTutorCoordinador)
//{
//  
//    if(!is_numeric($idTutorCoordinador))
//        return NULL;
//    
//    $arrayGrupos = consultaGrupos($idTutorCoordinador);
//    if($arrayGrupos)
//    {
//        foreach ($arrayGrupos as $grupo) {
//            echo <<<HTML
//            <tr>
//                <td><a class="icon-user icon-black" href="alumnosGrupoTutor.php?idGrupo=$grupo->id" role="button" data-toggle="modal" title="Ver Alumno"></a>
//                </td>
//                <td>$grupo->nombre</td>
//                <td>$grupo->clave</td>
//            </tr>
//HTML;
//        } 
//        
//    }
//}


/**
 * @author HMP
 * Genera tabla donde se muestran los grupos asignados a un tutor junior
 * @uses function consultaGrupos($idTutor)
 * @param type $idTutor
 */
function tablaGruposTutorJunior($idTutor)
{
  
    if(!is_numeric($idTutor))
        return NULL;
    
    $arrayGrupos = consultaGrupos($idTutor);
    if($arrayGrupos)
    {
        foreach ($arrayGrupos as $grupo) {
            $nombreInstitucion = html_entity_decode(getNombreInstitucion($grupo->idEscuela));
            $nombreEscuela = getNombreEscuela($grupo->idEscuela);
            $nombreEmpresa = html_entity_decode(getNombreEmpresa($grupo->idEmpresa));
            $nAlumnos = getCantidadAlumnosGrupo($grupo->id);
            
            if ($grupo->tipoGrupo == 0) {
                $tipoGrupo = "Escuela";
            } else {
                $tipoGrupo = "Empresa";
            }
            echo <<<HTML
            <tr>
                <td><a class="icon-user icon-black" href="grupoAlumnoTutorJr.php?idGrupo=$grupo->id&idTutor=$idTutor" role="button" data-toggle="modal" title="Ver Alumno"></a>
                </td>
                <td>$nAlumnos</td>
                <td>$grupo->nombre</td>
                <td>$grupo->clave</td>
                <td>$tipoGrupo</td>
                <td>$nombreInstitucion</td>
                <td>$nombreEscuela</td>
                <td>$nombreEmpresa</td>    
                
            </tr>
HTML;
        } 
        
    }
}

/**
 * @author HMP
 * Genera tabla donde se muestran los grupos asignados a un tutor Senior
 * @uses function consultaGrupos($idTutor)
 * @param type $idTutor
 */
function tablaGruposTutorSenior($idTutor)
{
  
    if(!is_numeric($idTutor))
        return NULL;
    
    $arrayGrupos = consultaGrupos($idTutor);
    if($arrayGrupos)
    {
        foreach ($arrayGrupos as $grupo) {
            $nombreInstitucion = html_entity_decode(getNombreInstitucion($grupo->idEscuela));
            $nombreEscuela = getNombreEscuela($grupo->idEscuela);
            $nombreEmpresa = html_entity_decode(getNombreEmpresa($grupo->idEmpresa));
            $nAlumnos = getCantidadAlumnosGrupo($grupo->id);
            
            if ($grupo->tipoGrupo == 0) {
                $tipoGrupo = "Escuela";
            } else {
                $tipoGrupo = "Empresa";
            }
            
            echo <<<HTML
            <tr>
                <td><a class="icon-user icon-black" href="grupoAlumnoTutorSenior.php?idGrupo=$grupo->id&idTutor=$idTutor" role="button" data-toggle="modal" title="Ver Alumno"></a>
                </td>
                <td>$nAlumnos</td>
                <td>$grupo->nombre</td>
                <td>$grupo->clave</td>
                <td>$tipoGrupo</td>
                <td>$nombreInstitucion</td>
                <td>$nombreEscuela</td>
                <td>$nombreEmpresa</td>
            </tr>
HTML;
        } 
        
    }
}

/**
 * @author HMP 
 * Consulta Todos los tutores especificados por $tipo ['Junior'.'Senior']
 * relacionados a un grupo.
 * @param type $idTutor
 */

function cosultaTablaTutor_Grupo($idGrupo,$tipo)
{
   $sql = "SELECT DISTINCT  r_c_t.id_tutor as \"idTutor\",
                            dp.nombre_pila as \"nombrePila\",
                            dp.primer_apellido || ' '||dp.segundo_apellido as apellidos,
                            dp.id_datos_personales as id_datos_personales
           FROM rel_curso_tutor r_c_t
           JOIN rel_curso_grupo r_c_g
                   ON r_c_t.id_rel_curso_grupo =  r_c_g.id_rel_curso_grupo
           JOIN tutor tu
                   ON tu.id_tutor = r_c_t.id_tutor
           JOIN datos_personales dp
                   ON dp.id_datos_personales = tu.id_datos_personales
           JOIN rol_tutor r_t
                   ON r_t.id_rol_tutor = tu.id_rol_tutor
           WHERE tu.status = 1
           AND r_c_g.id_grupo = ".$idGrupo."
           AND r_t.nombre = '".$tipo."'";
    
    $consultaGrupo= new Query("SG");
    $consultaGrupo->sql=$sql;
    $resultado = $consultaGrupo->select("obj");
    return $resultado;  
}


/**
 * @author HMP 
 * Busca todos los cursos abiertos relacionados a un tutor
 * (este puede ser Junior, Senior, Coordinador)
 * @param type $idTutor
 * @return type $resultado Array con formato [nombreCurso,nombreCursoAbierto,idCursoAbierto]
 */
function consultaCursosAsignadosTutor($idTutor)
{
    $sql = "SELECT  DISTINCT r_c_g.id_curso_abierto as \"idCursoAbierto\", 
                    cu.nombre_curso \"nombreCurso\",
                    c_a.nombre_curso_abierto \"nombreCursoAbierto\",
                    r_c_g.id_curso_abierto \"idCursoAbierto\",
                    cu.id_curso as \"idCurso\"
            FROM rel_curso_tutor r_c_t
            JOIN rel_curso_grupo r_c_g
                    ON r_c_g.id_rel_curso_grupo = r_c_t.id_rel_curso_grupo 
            JOIN cursos_abiertos c_a
                    ON c_a.id_curso_abierto = r_c_g.id_curso_abierto 
            JOIN cursos cu
                    ON cu.id_curso = c_a.id_curso
            WHERE cu.status = 1
            AND c_a.status = 1
            AND r_c_t.id_tutor = ".$idTutor;
    
    
    $consultaCursos= new Query("SG");
    $consultaCursos->sql=$sql;
    $resultado = $consultaCursos->select("obj");
    return $resultado;  
}

/**
 * @author HMP
 * Genera Tabla donde se muestran todos los cursos asignados al Tutor 
 * @uses function  consultaCursosAsignadosTutor($idTutor)
 * @param type $idTutor
 * @return type $resultado Array con formato [nombreCurso,nombreCursoAbierto,idCursoAbierto]
 */
function generaTablaCursoAsignadosTutor($idTutor)
{
    if(!is_numeric($idTutor))
        return;
   
    $cursos = consultaCursosAsignadosTutor($idTutor);
    
    if($cursos)
    {
        foreach ($cursos as $curso) {
            echo <<<HTML
            <tr>
                <td>
                    <a class="icon-eye-open verCursoVistaTutor" href="#verModal2" role="button" data-toggle="modal" title="Ver Información del Curso" name="$curso->idCurso"></a>
                    <a class="icon-tags" href="gruposAsignados.php?idCursoAbierto=$curso->idCursoAbierto" role="button" data-toggle="modal" title="Ver Grupos"></a>
                </td>
                <td>$curso->nombreCurso</td>
                <td>$curso->nombreCursoAbierto</td>
                <td>
                    <a class="icon-play-circle" href="../mapaCurso/index.php?alumno=no&idCurso=$curso->idCurso" role="button" data-toggle="modal" title="Probar Curso"></a>
                </td>
            </tr>
HTML;
        } 
        
    }
}



?>
