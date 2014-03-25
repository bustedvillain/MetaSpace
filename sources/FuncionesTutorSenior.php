<?php

//   Date             Modified by         Change(s)
//   2013-10-03         HMP                 1.0
/**
 * Funcion que genera tabla de tutors jr
 */
function tablaTutoresJr() {
    $listaTutores = selectTutor("todos", "junior");
    foreach ($listaTutores as $tutor) {
        echo<<<tablaTutores
        <tr class = "gradeC">
            <td>
                <a class = "icon-eye-open verPerfil" href = "#verModal" onclick="verPerfilPorTutor($tutor->idTutor);" role = "button" data-toggle = "modal" title = "Ver"></a>
                <a class = "icon-info-sign" href = "#actividadTutorModal" onclick="verActividadPorTutor($tutor->idTutor);"  role = "button" data-toggle = "modal" title = "Ver Informacion de Actividad"></a>
                <a class = "icon-tags" href = "gruposTutorJr.php" title = "Ver Grupos de &eacute;ste tutor"></a>
                <a class = "icon-edit" href = "#editarModal" role = "button" data-toggle = "modal" title = "Editar"></a>
                <a class = "icon-trash" href = "borrarUsuario.php" title = "Eliminar" onClick = "return confirm('¿Está seguro?');"></a>
            </td>
            <td>$tutor->idTutor</td>
            <td>$tutor->nombre</td>
            <td>$tutor->apellidos</td>
        </tr>
tablaTutores;
    }
}

/**
 * Metodo que realiza búsquedas, de tutores
 * @param type $id colocar el id a buscar, si deseas obtener todos los registros obtener 'todos'
 * @param type $rol Puede ser "junior", "senior", o "coordinador", envíale null si no quieres un rol en especial
 */
function selectTutor($id, $rol) {
    if ($id == "todos") {
        if (!isset($rol)) {
            $predicadoId = "";
        } else {
            $predicadoId = " and lower(r.nombre) like '" . $rol . "%' ";
        }
    } else {
        $predicadoId = " and t.id_tutor = " . $id;
    }

    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql = "select t.id_tutor as \"idTutor\", r.nombre as \"rol\", d.nombre_pila as \"nombre\", d.primer_apellido  || ' ' || d.segundo_apellido as \"apellidos\",
        d.primer_apellido as \"primerApellido\", d.segundo_apellido as \"segundoApellido\",
	d.nombre_usuario as \"nombreUsuario\", d.correo as \"correo\", d.contrasena as \"contrasena\", d.fecha_nacimiento as \"fechaNacimiento\",
	d.curp as \"curp\", d.codigo_postal as \"codigoPostal\", d.calle as \"calle\", d.no_casa_ext as \"noCasaExt\", d.no_casa_int as \"noCasaInt\",
	d.colonia_localidad as \"coloniaLocalidad\", d.delegacion_municipio as \"delegacionMunicipio\", e.nombre_entidad as \"nombreEntidad\",
        d.id_nacionalidad as \"idNacionalidad\", d.id_entidad_federativa as \"idEntidadFederativa\", 
        n.nacionalidad as \"nacionalidad\", d.id_datos_personales as \"idDatosPersonales\", 
	d.zona_horaria as \"zonaHoraria\", d.telefono_fijo as \"telefonoFijo\", d.telefono_movil as \"telefonoMovil\", t.status as \"status\"
            from tutor t
            left join rol_tutor r
                    on r.id_rol_tutor = t.id_rol_tutor
            left join datos_personales d
                    on d.id_datos_personales = t.id_datos_personales
            left join entidad_federativa e
                    on e.id_entidad_federativa = d.id_entidad_federativa
            left join nacionalidad n
                    on n.id_nacionalidad = d.id_nacionalidad

            where t.status = 1 " . $predicadoId;
    $resultado = $sql->select("obj");
    return $resultado;
}

/**
 * Metodo que convierte un objeto Tutor en un JSON
 * @param type $tutor Requiere un objeto tutor por ejemplo sacado de un foreach
 * @return type retorna el objeto tutor como un JSON para usar libremente
 */
function tutorToJSON($tutor) {
    $arregloNormal = array(
        'idTutor' => $tutor->idTutor,
        'idDatosPersonales' => $tutor->idDatosPersonales,
        'nombre' => $tutor->nombre,
        'primerApellido' => $tutor->primerApellido,
        'segundoApellido' => $tutor->segundoApellido,
        'apellidos' => $tutor->apellidos,
        'nombreUsuario' => $tutor->nombreUsuario,
        'correo' => $tutor->correo,
        'contrasena' => nDCrypt($tutor->contrasena),
        'fechaNacimiento' => $tutor->fechaNacimiento,
        'curp' => $tutor->curp,
        'codigoPostal' => $tutor->codigoPostal,
        'calle' => $tutor->calle,
        'noCasaExt' => $tutor->noCasaExt,
        'noCasaInt' => $tutor->noCasaInt,
        'coloniaLocalidad' => $tutor->coloniaLocalidad,
        'delegacionMunicipio' => $tutor->delegacionMunicipio,
        'nombreEntidad' => $tutor->nombreEntidad,
        'nacionalidad' => $tutor->nacionalidad,
        'idNacionalidad' => $tutor->idNacionalidad,
        'idEntidadFederativa' => $tutor->idEntidadFederativa,
        'zonaHoraria' => $tutor->zonaHoraria,
        'telefonoFijo' => $tutor->telefonoFijo,
        'telefonoMovil' => $tutor->telefonoMovil,
        'status' => $tutor->status
    );
    $arregloJSON = json_encode($arregloNormal);
    return $arregloJSON;
}

/**
 * Metodo que realiza una busqueda de actividades por tutor recibe el id
 * @param type $idTutor
 * @return type retorna un objeto de actividades
 */
function selectActividadTutor($idTutor) {
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql = "
        select a.fecha as \"fecha\", a.detalle as \"detalle\"
        from actividad_tutor a
        where id_tutor = " . $idTutor . " order by a.fecha
        ";
    $resultado = $sql->select("obj");
    return $resultado;
}

function selectEntradasTutor($idTutor)
{
    $sql = new Query("SG");
    $sql->sql = "
                SELECT to_char(fecha_entrada, 'DD/MM/YYYY HH24:MI:SS') as fecha_entrada,
                       to_char(fecha_salida, 'DD/MM/YYYY HH24:MI:SS')as fecha_salida FROM log_acceso_tutor
                WHERE id_tutor = ".$idTutor." ORDER BY fecha_entrada
        ";
    $resultado = $sql->select("obj");
    return $resultado;
    
}

function comboNacionalidades() {
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql = "
        select * from nacionalidad where status = 1 order by nacionalidad 
        ";
    $nacionalidades = $sql->select("obj");
    if ($nacionalidades) {
        foreach ($nacionalidades as $nac) {
            $nacionalidad = ($nac->nacionalidad);
            echo<<<nacio
        <option value = "$nac->id_nacionalidad">$nacionalidad</option>
nacio;
        }
    } else {
        echo <<<html
            <option value="">No hay nacionalidades</option>
html;
    }
}
/**
 * Funcion que genera combo entidades
 */
function comboEntidades() {
    require_once 'Query.php';
    $sql = new Query("SG");
    $sql->sql = "
        select * from entidad_federativa order by nombre_entidad
        ";
    $nacionalidades = $sql->select("obj");
    if ($nacionalidades) {
        foreach ($nacionalidades as $nac) {
            echo<<<nacio
        <option value = "$nac->id_entidad_federativa">$nac->nombre_entidad</option>
nacio;
        }
    } else {
        echo <<<html
            <option value="">No hay entidades</option>
html;
    }
}
/**
 * Función que genera la sesion de tutor senior y redirecciona a su frontweb correspondiente
 */
function verificarSesionSenior() {
    if (!esSenior())
        header('Location:../');
}

/**
 * Genera el contenido de la tabla que contiene todos los  grupos 
 * relacionados a un curso.
 * @uses function consultaGruposRelacionCursoAbierto($idCursoAbierto,$idTutor)
 * @param type $idCursoAbierto
 */
function tablaGruposTutorSeniorAsignadosTutor($idCursoAbierto, $idTutor) {

    if (!is_numeric($idCursoAbierto))
        return NULL;

    if (!is_numeric($idTutor))
        return NULL;


    $arrayGrupos = consultaGruposRelacionCursoAbierto($idCursoAbierto, $idTutor);
    if ($arrayGrupos) {
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
