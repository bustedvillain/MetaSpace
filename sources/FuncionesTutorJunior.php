<?php
//   Date             Modified by         Change(s)
//   2013-10-03         HMP                 1.0

require_once 'Query.php';


/**
 * *Consulta Grupos relacionados a un Tutor
 * @param type $idTutor
 * @return type
 */
function consultaGrupos($idTutor)
{
   $sql="SELECT DISTINCT gp.id_grupo as id ,
                    gp.nombre_grupo as nombre ,
                    gp.clave as clave,
                    gp.id_escuela as \"idEscuela\",
                    gp.id_empresa as \"idEmpresa\",
                    gp.tipo_grupo as \"tipoGrupo\" 
    FROM rel_curso_tutor r_c_t
    JOIN rel_curso_grupo r_c_g
            ON r_c_t.id_rel_curso_grupo = r_c_g.id_rel_curso_grupo
    JOIN grupo gp
            ON gp.id_grupo = r_c_g.id_grupo 
    WHERE 	gp.status = 1
    AND     r_c_t.id_tutor = ".$idTutor;
   
    $consultaGrupo= new Query("SG");
    $consultaGrupo->sql=$sql;
    $resultado = $consultaGrupo->select("obj");
    
    return $resultado;
}

/**
 *@author HMP  
 *Consulta Todos Los Grupos Asigandos a un cursoAbierto
 *y pertenecientes a un tutor. 
 *Usado por Tutor Junior, Senior, Coordinador
 *@param $idCursoAbierto ID de Curso Abierto
 *@param $idTutor   ID(Puede ser Junior,Senior,Coordinador)
 *Nota: Por Get permite que se pueda ver otras cosas
 */
function consultaGruposRelacionCursoAbierto($idCursoAbierto,$idTutor)
{
   $sql="SELECT gp.id_grupo as id ,
                gp.nombre_grupo as nombre ,
                gp.clave as clave,
                gp.id_escuela as \"idEscuela\",
                gp.id_empresa as \"idEmpresa\",
                gp.tipo_grupo as tipoGrupo
            FROM rel_curso_tutor r_c_t
            JOIN rel_curso_grupo r_c_g
                    ON r_c_t.id_rel_curso_grupo = r_c_g.id_rel_curso_grupo
            JOIN grupo as gp
                    ON gp.id_grupo = r_c_g.id_grupo 
            WHERE gp.status = 1
            AND r_c_t.id_tutor = ".$idTutor."
            AND r_c_g.id_curso_abierto = ".$idCursoAbierto;
   
    $consultaGrupo= new Query("SG");
    $consultaGrupo->sql=$sql;
    $resultado = $consultaGrupo->select("obj");
    
    
    return $resultado; 
}


/**
 * @author HMP
 * Genenera Tabla de Grupos asignados a Tutor JR
 * @param type $idTutor ID del tutor
 */
function tablaGrupos($idTutor)
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
                <td><a class="icon-user icon-black" href="alumnosAsignados.php?idGrupo=$grupo->id" role="button" data-toggle="modal" title="Ver Alumno"></a>
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
 * Función que retorna los cursos de un tutor
 * @param type $idTutor
 * @return null
 */
function getCursosPorTutor($idTutor)
{
    if(!is_numeric($idTutor))
        return NULL;
    $consulta= new Query("SG");
    $consulta->sql=<<<Query
    select ca.id_curso as "idCurso", ca.id_curso_abierto as "idCursoAbierto"
    , ca.nombre_curso_abierto as "nombreCursoAbierto"
    , ca.fecha_inicio as "fechaInicio"
    , ca.fecha_fin as "fechaFin" 
    from cursos_abiertos ca 
    join rel_curso_grupo rcg
            on rcg.id_curso_abierto = ca.id_curso_abierto 
    join rel_curso_tutor r 
            on r.id_rel_curso_grupo = rcg.id_rel_curso_grupo
    where ca.status = 1 and r.id_tutor = $idTutor      
Query;
    return  $consulta->select("obj");
}

function trCursoPorTutor($idTutor){
    $arrCursos = getCursosPorTutor($idTutor);
    foreach ($arrCursos as $a){
        echo <<<tabla
        <tr>
                <td>iconitos</a>
                <td>$a->nombreCursoAbierto</td>
                <td>$a->fechaInicio</td>
                <td>$a->fechaFin</td>
                
            </tr>
tabla;
    }
}


/**
 * @author HMP
 * Consulta todos los Alumnos asignados aun Grupo en especifico 
 * @uses function consultaGruposRelacionCursoAbierto($idCursoAbierto,$idTutor)
 * @param type $iGrupo ID del Grupo
 */
function consultaAlumnosAsignadosGrupo($idGrupo)
{
    $sql="SELECT al.id_alumno as  \"idAlumno\", 
            dp.id_datos_personales id_datos_personales,
            (dp.primer_apellido||' '||dp.segundo_apellido) as apellidos,
            dp.nombre_pila as nombre,
            dp.nombre_usuario as \"nombreUsuario\",
            dp.correo as correo,
            CASE WHEN al.tipo_alumno = 0 THEN 'Estudiante'
                 WHEN al.tipo_alumno = 1 THEN 'Profesionista'
                ELSE 'other'
            END as \"tipoAlumno\"
        FROM grupo_alumno gp
        JOIN alumnos al
                ON gp.id_alumno = al.id_alumno
        JOIN datos_personales dp
                ON dp.id_datos_personales = al.id_datos_personales
        WHERE   al.status = 1 AND
                gp.id_grupo =".$idGrupo;
    
    $consultaGrupo= new Query("SG");
    $consultaGrupo->sql=$sql;
    $resultado = $consultaGrupo->select("obj");
    
    return $resultado;
    
}


/**
 * @author HMP
 * Construye la tabla de alumnos pertenecientes a un Grupo 
 * @uses function consultaAlumnosAsignadosGrupo($idGrupo)
 * @param type $iGrupo ID del Grupo
 */
function tablaAlumnos($idGrupo)
{
    
    if(!is_numeric($idGrupo))
        return null;
    
    $arrayAlumno= consultaAlumnosAsignadosGrupo($idGrupo);
    if($arrayAlumno)
    {
        foreach ($arrayAlumno as $alumno) {
            echo <<<HTML
            <tr>
                <td><a class = "icon-eye-open verDatosPersonales" href = "#verModal"  name="estudiante" id="$alumno->id_datos_personales" role = "button" data-toggle = "modal" title = "Ver"></a></td>
                <td>$alumno->nombreUsuario</td>
                <td>$alumno->correo</td>
                <td>$alumno->nombre</td>
                <td>$alumno->apellidos</td>    
                
            </tr>
HTML;
        } 
    }
    
}


function tablaAlumnosGrupoJunior($idGrupo)
{
    
    if(!is_numeric($idGrupo))
        return NULL;
    
    $arrayAlumno= consultaAlumnosAsignadosGrupo($idGrupo);
    if($arrayAlumno)
    {
        foreach ($arrayAlumno as $alumno) {
            echo <<<HTML
            <tr>
                <td><a class = "icon-eye-open verPerfil" href = "#verModal"  onclick="verPerfilAlumno($alumno->idAlumno);" role = "button" data-toggle = "modal" title = "Ver"></a></td>
                <td>$alumno->nombre</td>
                <td>$alumno->apellidos</td>
            </tr>
HTML;
        } 
    }
    
}


/**
 * Función que consulta los detalles de un alumno
 * @param type $idAlumno
 * @return type
 */
function consultarDetalleAlumno($idAlumno)
{
    $sql="SELECT al.matricula as \"matricula\",
                dp_a.nombre_pila as \"nombreAlumno\",
                (dp_a.primer_apellido||' '||dp_a.segundo_apellido)as \"apellidosAlumno\",
                dp_a.nombre_usuario \"nombreUsuario\",
                al.id_alumno as id,
                dp_a.correo as correo,
                dp_a.fecha_nacimiento as \"fechaNacimiento\",
                dp_a.curp as \"curp\",
                dp_a.codigo_postal as \"cp\",
                dp_a.no_casa_ext as \"casaExt\",
                dp_a.no_casa_int as \"casaInt\",
                dp_a.colonia_localidad as \"coloniaLocalidad\",
                dp_a.delegacion_municipio as \"delegacionMunicipio\",
                ef.nombre_entidad as \"estado\",
                dp_a.zona_horaria as \"zonaHoraria\",
                dp_a.telefono_fijo as \"telefonoFijo\",
                dp_a.telefono_movil as \"telefonoMovil\",
                (dp_p.nombre_pila||' '|| dp_p.primer_apellido||' '||dp_p.segundo_apellido ) as \"nombrePadre\",
                (dp_pf.nombre_pila||' '|| dp_pf.primer_apellido||' '||dp_pf.segundo_apellido ) as \"nombreProfesor\",
                nv_a.nombre as \"nivelEscolar\",
                gd_a.nombre_grado as \"gradoEscolar\",
                (CASE WHEN al.tipo_alumno=0 THEN 'Estudiante'
                     WHEN al.tipo_alumno=1 THEN 'Profesionista'
                     ELSE 'other'
                END) as \"tipoEstudiante\",
                al.puesto_ocupacion as \"puestoOcupacion\",
                emp.nombre_empresa as \"empresa\",
                dp_a.calle as \"calleAlumno\",
                esc.nombre_escuela as escuela,
                na.nacionalidad as nacionalidad
            FROM alumnos al
            JOIN datos_personales dp_a
                    ON al.id_datos_personales = dp_a.id_datos_personales
            JOIN padres pd
                    ON al.id_padre = pd.id_padre
            JOIN datos_personales dp_p
                    ON dp_p.id_datos_personales = pd.id_datos_personales
            JOIN nivel_escolar nv_a
                    ON al.id_nivel = nv_a.id_nivel
            JOIN grado_escolar gd_a
                    ON al.id_grado_escolar = gd_a.id_grado_escolar
            JOIN escuelas esc
                    ON al.id_escuela = esc.id_escuela
            JOIN empresa emp
                    ON al.id_empresa = emp.id_empresa
            JOIN entidad_federativa ef
                    ON ef.id_entidad_federativa = dp_a.id_entidad_federativa
            JOIN nacionalidad na
                    ON na.id_nacionalidad = dp_a.id_nacionalidad
            JOIN profesores_aula pf
                    ON pf.id_profesor_aula = al.id_profesor_aula
            JOIN datos_personales dp_pf
                    ON dp_pf.id_datos_personales = pf.id_datos_personales
            WHERE al.id_alumno = ".$idAlumno
                  ."AND al.status = 1
                    AND pd.status = 1
                    AND nv_a.status = 1
                    AND gd_a.status = 1
                    AND esc.status = 1
                    AND emp.status = 1
                    AND na.status = 1
                    AND pf.status = 1";
    
    $consultaAlumno = new Query("SG");
    $consultaAlumno->sql = $sql;
    
    $resultado = $consultaAlumno->select("obj");
    
    return $resultado;
}
/**
 * Función que retorna JSON de los datos de un alumno
 * @param type $idAlumno
 * @return type
 */
function obtenerJsonAlumno($idAlumno)
{
    
    $resultado = consultarDetalleAlumno($idAlumno);
    
    return $resultado;
//    if($resultado)
//    {
//        return json_encode($resultado);  
//    }
}
/**
 * Verifica si la sesión es de Tutor Jr y redirecciona a
 * FrontWEB tutor
 */
function verificarSesionTutorJr()
{
    if(!esJr())
        header('Location:../');
}



/**
 * Genera el contenido de la tabla que contiene todos los  grupos 
 * relacionados a un curso.
 * @uses function consultaGruposRelacionCursoAbierto($idCursoAbierto,$idTutor)
 * @param type $idCursoAbierto
 */
function tablaGruposTutorJuniorAsignadosTutor($idCursoAbierto,$idTutor)
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
                <a class="icon-book icon-black" href="tutoresJrAsignadosGrupo.php?idGrupo=$grupo->id&idCursoAbierto=$idCursoAbierto" role="button" data-toggle="modal" title="Ver Tutores Juniorr"></a>
                
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


?>
