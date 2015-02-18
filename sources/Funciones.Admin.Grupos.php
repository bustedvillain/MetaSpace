<?php

//Control de cambios #5
//26-dic-2013
//Correcciones de acentos
/*
 * Autor: José Manuel Nieto Gómez
 * Fecha de Creación: 07 de Noviembre del 2013
 * Objetivo: Funciones generales sobre grupos
 * 
 * FECHA DE MODIFICACÓN: 16 DE DICIEMBRE DEL 2013
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * SE MODIFICO PARA QUE EL NOMBRE DEL GRUPO SE ESCAPEN SUS CARACTERES EN LA FUNCION
 * consultaNombreGrupo()
 */
//Control de cambios #&
//Autor:Omar Nava
//Objetivo: Left join a tabla de usuarios
//30-dic-2013

/**
 * Funcion que consulta los grupos activos
 * @return boolean
 */
function consultaGruposActivos() {
    $query = new Query("SG");

    $query->sql = <<<SQL
            SELECT g.id_grupo, g.nombre_grupo, g.clave, g.id_escuela, g.id_empresa, g.tipo_grupo
            FROM grupo g
            WHERE status=1

SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $res) {
            $nombreInstitucion = (getNombreInstitucion($res->id_escuela));
            $nombreEscuela = (getNombreEscuela($res->id_escuela));
            $nombreEmpresa = (getNombreEmpresa($res->id_empresa));
            $nAlumnos = getCantidadAlumnosGrupo($res->id_grupo);
            $nombreGrupo = ($res->nombre_grupo);
            $clave = ($res->clave);

            if ($res->tipo_grupo == 0) {
                $tipoGrupo = "Escuela";
            } else {
                $tipoGrupo = "Empresa";
            }
            //inicia control de cambios #5
            $tabla = <<<TABLA
                    
                <tr>
                    <td>
                        <a class="icon-user" title='Ver Alumnos' href="verAlumnosGrupo.php?id=$res->id_grupo"></a>
                        <a class="icon-edit editarGrupo" title="Editar Grupo" href="#editarModal" name="$res->id_grupo" role="button" data-toggle="modal"></a>
                        <a class="icon-trash" title="Borrar Grupo" href="borrarGrupo.php?id=$res->id_grupo" onClick="return confirm('¿Está seguro?');"></a>
                    </td>
                    <td>$nAlumnos</td>
                    <td>$nombreGrupo</td>
                    <td>$clave</td>
                    <td>$tipoGrupo</td>
                    <td>$nombreInstitucion</td>
                    <td>$nombreEscuela</td>
                    <td>$nombreEmpresa</td>
                    <td><a href="adminAlumnosGrupo.php?id=$res->id_grupo&tipo_grupo=$tipoGrupo">Administrar Alumnos</a></td>
                </tr>
TABLA;
            echo ($tabla);
            //termina control de cambios #5
        }
    } else {
        return false;
    }
}

/**
 * Funcion que retorna el nombre de la escuela a partir del id de la misma
 * @param type $idEscuela
 * @return type
 */
function getNombreEscuela($idEscuela) {
    if ($idEscuela != "" && $idEscuela != NULL) {
        $query = new Query("SG");

        $query->sql = <<<SQL
            SELECT nombre_escuela
            FROM escuelas
            WHERE id_escuela=$idEscuela

SQL;

        $resultado = $query->select("obj");

        if ($resultado) {
            foreach ($resultado as $res) {
                return ($res->nombre_escuela);
            }
        } else {
            return "";
        }
    } else {
        return "";
    }
}

/**
 * Funcion que retorn el ombre de la institucion educaiva a partir del
 * id de una escula
 * @param type $idEscuela
 * @return type
 */
function getNombreInstitucion($idEscuela) {

    if ($idEscuela != "" && $idEscuela != NULL) {
        $query = new Query("SG");

        $query->sql = <<<SQL
            SELECT i.nombre_institucion
            FROM escuelas e, instituciones i
            WHERE e.id_institucion = i.id_institucion
              and e.id_escuela=$idEscuela

SQL;

        $resultado = $query->select("obj");

        if ($resultado) {
            foreach ($resultado as $res) {
                return $res->nombre_institucion;
            }
        } else {
            return "";
        }
    } else {
        return "";
    }
}

/**
 * Funcion que retona el nombre de la empresa a patir de su id
 * @param type $idEmpresa
 * @return type
 */
function getNombreEmpresa($idEmpresa) {
    if ($idEmpresa != "" && $idEmpresa != NULL) {
        $query = new Query("SG");

        $query->sql = <<<SQL
            SELECT nombre_empresa
            FROM empresa
            WHERE id_empresa=$idEmpresa

SQL;

        $resultado = $query->select("obj");

        if ($resultado) {
            foreach ($resultado as $res) {
                return $res->nombre_empresa;
            }
        } else {
            return "";
        }
    } else {
        return "";
    }
}

/**
 * Funcion que retorna el nombre del grupo eacuerdo al id de un grupo
 * @param type $idGrupo
 * @return string
 */
function getNomreGrupo($idGrupo) {
    if ($idGrupo != "" && $idGrupo != NULL) {
        $query = new Query("SG");

        $query->sql = <<<SQL
            SELECT nombre_grupo
            FROM grupo
            WHERE id_grupo=$idGrupo

SQL;

        $resultado = $query->select("obj");

        if ($resultado) {
            foreach ($resultado as $res) {
                return $res->nombre_grupo;
            }
        } else {
            return "";
        }
    } else {
        return "";
    }
}

/**
 * Funcion que retorna el nombre de los alumnos pertenecientes a una escuela
 * @param type $idEscuela
 * @return type
 */
function getAlumnosEscuela($idEscuela, $cuales = "todos", $idGrupo = NULL) {
    $query = new Query("SG");

    switch ($cuales) {
        case "todos":
            $query->sql = <<<SQL
            SELECT dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, a.id_alumno
            FROM datos_personales dp, alumnos a
            WHERE a.id_datos_personales = dp.id_datos_personales
              and a.id_escuela= $idEscuela
              and a.status=1
SQL;
            break;
        case "disponibles":
            $query->sql = <<<SQL
            SELECT dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, a.id_alumno
            FROM datos_personales dp, alumnos a
            WHERE a.id_datos_personales = dp.id_datos_personales
              and a.id_escuela= $idEscuela
              and a.status=1
              and a.id_alumno not in(
                    SELECT a.id_alumno
                    FROM alumnos a, grupo_alumno ga
                    WHERE a.id_escuela= $idEscuela
                      and ga.id_alumno = a.id_alumno
                      and ga.id_grupo = $idGrupo
                )
SQL;
            break;
        case "seleccionados":
            $query->sql = <<<SQL
                SELECT dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, a.id_alumno
            FROM datos_personales dp, alumnos a, grupo_alumno ga
            WHERE a.id_datos_personales = dp.id_datos_personales
              and a.id_escuela= $idEscuela
              and ga.id_alumno = a.id_alumno
              and ga.id_grupo = $idGrupo
              and a.status=1
SQL;
            break;
    }


    $resultado = $query->select("obj");

    if ($resultado) {
        return $resultado;
    }
}

/**
 * Funcion que retorna los alumnos pertenecientes  a una escuela en un arreglo JSON
 * @param type $idEscuela
 * @return type
 */
function getAlumnosEscuelaJSON($idEscuela, $cuales = "todos", $idGrupo = NULL) {
    return json_encode(getAlumnosEscuela($idEscuela));
}

/**
 * Funcion que retorna los alumnos que pertenecen a una empresa
 * @param type $idEmpresa
 * @return type
 */
function getAlumnosEmpresa($idEmpresa, $cuales = "todos", $idGrupo = NULL) {
    $query = new Query("SG");

    switch ($cuales) {
        case "todos":
            $query->sql = <<<SQL
            SELECT dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, a.id_alumno
            FROM datos_personales dp, alumnos a
            WHERE a.id_datos_personales = dp.id_datos_personales
              and a.id_empresa= $idEmpresa
SQL;
            break;
        case "disponibles":
            $query->sql = <<<SQL
            SELECT dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, a.id_alumno
            FROM datos_personales dp, alumnos a
            WHERE a.id_datos_personales = dp.id_datos_personales
              and a.id_empresa= $idEmpresa
              and a.status=1
              and a.id_alumno not in(
                    SELECT a.id_alumno
                    FROM alumnos a, grupo_alumno ga
                    WHERE a.id_empresa= $idEmpresa
                      and ga.id_alumno = a.id_alumno
                      and ga.id_grupo = $idGrupo
                )
SQL;
            break;
        case "seleccionados":
            $query->sql = <<<SQL
                SELECT dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, a.id_alumno
            FROM datos_personales dp, alumnos a, grupo_alumno ga
            WHERE a.id_datos_personales = dp.id_datos_personales
              and a.id_empresa= $idEmpresa
              and ga.id_alumno = a.id_alumno
              and ga.id_grupo = $idGrupo
              and a.status=1
SQL;
            break;
    }


    $resultado = $query->select("obj");

    if ($resultado) {
        return $resultado;
    }
}

/**
 * Funcion que retorna los alumnos de una empresa en una areglo JSON
 * @param type $idEmpresa
 * @return typed
 */
function getAlumnosEmpresaJSON($idEmpresa, $cuales = "todos", $idGrupo = NULL) {
    return json_encode(getAlumnosEmpresa($idEmpresa));
}

/**
 * Funcion que retorna la cantidad de alumnos dentro de un grupo
 * @param type $idGrupo
 * @return type
 */
function getCantidadAlumnosGrupo($idGrupo) {
    $query = new Query("SG");

    $query->sql = <<<SQL
            SELECT count(*) nalumnos
            FROM grupo_alumno ga, alumnos a
            WHERE ga.id_grupo=$idGrupo
              and a.id_alumno = ga.id_alumno
              and a.status=1
SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $res) {
            return $res->nalumnos;
        }
    }
}

//Inicia control de cambios #6
/**
 * 
 * @param type $idNivel
 * @return type
 */
function getGradosDeNivelJSON($idNivel) {
    return json_encode(getGradosDeNivel($idNivel));
}

/**
 * Funcion que retorna la cantidad de alumnos dentro de un grupo
 * @param type $idGrupo
 * @return type
 */
function getGradosDeNivel($idNivel) {
    if (isset($idNivel) && !empty($idNivel) && $idNivel != "") {
        $query = new Query("SG");

        $query->sql = <<<SQL
            SELECT *
            FROM grado_escolar
            where id_nivel = $idNivel
            and status = 1
            order by nombre_grado
SQL;

        $resultado = $query->select("obj");

        if ($resultado) {
            return $resultado;
        }
    }else{
        return null;
    }
}

/**
 * 
 * @param type $idNivel
 * @return type
 */
function getCursosDeEscuelaJSON($idEscuela) {
    return json_encode(getCursosDeEscuela($idEscuela));
}

/**
 * Funcion que retorna la cantidad de alumnos dentro de un grupo
 * @param type $idGrupo
 * @return type
 */
function getCursosDeEscuela($idEscuela) {
    $query = new Query("SG");

    $query->sql = <<<SQL
            SELECT distinct(g.id_grupo), n.nombre || ' - ' || ge.nombre_grado || ' - ' || g.nombre_grupo as "nombre_grupo"
            FROM grupo g
            join grupo_alumno ga
                    on ga.id_grupo = g.id_grupo
            join alumnos a
                    on a.id_alumno = ga.id_alumno
            join grado_escolar ge
                    on a.id_grado_Escolar = ge.id_grado_Escolar
            join nivel_escolar n
                    on ge.id_nivel = n.id_nivel
            where g.id_escuela = $idEscuela
SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        return $resultado;
    }
}

/**
 * 
 * @param type $idNivel
 * @return type
 */
//function getTablaPorGrupoJSON($idGrupo) {
//    return json_encode(getCursosDeEscuela($idGrupo));
//}

/**
 * Funcion que retorna la cantidad de alumnos dentro de un grupo
 * @param type $idGrupo
 * @return type
 */
function getTablaPorGrupo($idGrupo) {
    $query = new Query("SG");
    $query->sql = <<<tabla
        select  i.nombre_institucion institucion, e.nombre_escuela escuela, i.nombre_institucion institucion, n.nombre nivel, ge.nombre_grado grado
                , g.nombre_grupo grupo, dp.id_datos_personales, dp.nombre_usuario username, dp.correo, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido 
        from grupo_alumno ga
        left join alumnos a
                on a.id_alumno = ga.id_alumno
        left join datos_personales dp
                on a.id_datos_personales = dp.id_datos_personales
        left join escuelas e
                on a.id_escuela = e.id_escuela
        left join instituciones i
                on i.id_institucion = e.id_institucion

        left join grado_escolar ge
            on a.id_grado_escolar = ge.id_grado_escolar
        left join nivel_escolar n
            on ge.id_nivel = n.id_nivel
        left join grupo g
            on g.id_grupo = ga.id_grupo
        where ga.id_grupo = $idGrupo
tabla;
//    getArrIdsAlumno($idGrupo);

    $resultado = $query->select("obj");
    $tipo = "even";
    $final = "";
    $req = "";
    if ($resultado) {
        foreach ($resultado as $r) {
            if ($tipo == "even") {
                $tipo = "odd";
            } else {
                $tipo = "even";
            }
            $req = <<<req
                    <tr class='$tipo'>
                        <td>$r->institucion</td>
                        <td>$r->escuela</td>
                        <td>$r->nivel</td>
                        <td>$r->grado</td>
                        <td>$r->grupo</td>
                        <td>$r->username</td>
                        <td>$r->correo</td>
                        <td>$r->nombre_pila</td>
                        <td>$r->primer_apellido</td>
                        <td>$r->segundo_apellido</td>
                    </tr>
req;
            $final = $final . $req;
        }
        return $final;
    }
}

//termina control de cambios #6
/**
 * funcion que retorna la información de un grupo a partir del id de este
 * @param type $idGrupo
 * @return type
 */
function getInfoGrupo($idGrupo) {
    $query = new Query("SG");
    $query->sql = <<<SQL
            SELECT id_grupo, nombre_grupo, clave, id_escuela, id_empresa, tipo_grupo
            FROM grupo 
            WHERE status=1 and id_grupo=$idGrupo

SQL;

    $resultado = $query->select("arr");

    if ($resultado) {
        foreach ($resultado as $res) {
            //Si es e una escula
            if ($res["tipo_grupo"] == 0) {
                $res["id_institucion"] = getIdInstitucion($res["id_escuela"]);
            }
            return $res;
        }
    }
}

/**
 * Funcion que retorna la información de un grupo a partir del id de este en un
 * objeto JSON
 * @param type $idGrupo
 * @return type
 */
function getInfoGrupoJSON($idGrupo) {
    return json_encode(getInfoGrupo($idGrupo));
}

/**
 * Obtiene la institucion de una escuela
 * @param type $idEscuela
 * @return type
 */
function getIdInstitucion($idEscuela) {
    $query = new Query("SG");

    $query->sql = <<<SQL
            SELECT i.id_institucion
            FROM escuelas e, instituciones i
            WHERE e.id_institucion = i.id_institucion
              and e.id_escuela=$idEscuela

SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $res) {
            return $res->id_institucion;
        }
    }
}

/**
 * Funcion qu verifica si se va a cambiar de grupo
 * Si se detecta un cambio de grupo retorna un true
 * Si sigu siendo el mismo grupo retona un false
 * @param type $tipo_grupo nuevo tipo de grupo
 * @param type $idReferencia id de la referencia
 * @param type $idGrupo id del grupo
 * @return boolean
 */
function verificaCambioGrupo($tipo_grupo, $idReferencia, $idGrupo) {

    $grupo = getInfoGrupo($idGrupo);

    //Verifica grupo estudiante
    if ($tipo_grupo == 0) {
        if ($grupo["tipo_grupo"] == $tipo_grupo && $grupo["id_escuela"] == $idReferencia) {
            return false;
        } else {
            return true;
        }
    } else if ($tipo_grupo == 1) {
        //Verifica grupo profesionista
        if ($grupo["tipo_grupo"] == $tipo_grupo && $grupo["id_empresa"] == $idReferencia) {
            return false;
        } else {
            return true;
        }
    }
}

/**
 * Funcion que vacia todas la relaciones de un grupo 
 * de acuerdo al id de un grupo
 * @param type $idGrupo
 */
function vaciarRelacionGrupoAlumno($idGrupo = NULL, $idAlumno = NULL, $idGrupoAlumno = NULL) {
    //Eliminando todos los alumnos de un grupo
    if ($idGrupo != NULL && $idAlumno == NULL) {
        $query = new Query("SG");
        $query->delete("grupo_alumno", "id_grupo=$idGrupo");
    } else if ($idGrupo != NULL && $idAlumno != NULL) {
        //Elimina la coinciendcia deun alumno/grupo
        $query = new Query("SG");
        $query->delete("grupo_alumno", "id_grupo=$idGrupo and id_alumno=$idAlumno");
    } else if ($idGrupoAlumno != NULL) {
        $grupo = consultaGrupo($idGrupoAlumno);
        $idGrupo = $grupo->id_grupo;
        $idAlumno = $grupo->id_alumno;
        //Elimina una relacion directa de grupo_alumno
        $query = new Query("SG");
        $query->delete("grupo_alumno", "id_grupo_alumno=$idGrupoAlumno");
    }
    //Consulta si el grupo esta enrolado a un curso abierto
    if (($idsRelCursoGrupo = consultaCursosGrupo($idGrupo)) !== false) {
        foreach ($idsRelCursoGrupo as $curso) {
//            imprimeConsola("------Deshbilitar progreso alumnos---------------");
//            imprimeConsola("id rel curso grupo:".$curso->id_rel_curso_grupo);
            deshabilitarProgresoAlumnos($curso->id_rel_curso_grupo, $idAlumno);
        }
        return "deshabilitoProgreso";
    } else {
        return "borroRelacionSinProgreso";
    }
}

/**
 * Funcion que retorna options con los alumnos que pertenecen o no a un grupo
 * @param type $tipo_alumno
 * @param type $idLugar
 * @param type $cuales
 * @param type $idGrupo
 */
function comboAlumnos($tipo_grupo, $idLugar, $cuales = "todos", $idGrupo = NULL) {

    if ($tipo_grupo == "Escuela") {
        $alumnos = getAlumnosEscuela($idLugar, $cuales, $idGrupo);
    } else if ($tipo_grupo == "Empresa") {
        $alumnos = getAlumnosEmpresa($idLugar, $cuales, $idGrupo);
    }

    foreach ($alumnos as $alumno) {
        echo <<<COMBO
                <option value="$alumno->id_alumno">$alumno->nombre_pila $alumno->primer_apellido $alumno->segundo_apellido</option>
COMBO;
    }
}

/**
 * Funcion que consulta la existencia de un nombre de grupo, 
 * si existe retorna un true
 * si no existe retorna un false
 * 
 * FECHA DE MODIFICACÓN: 16 DE DICIEMBRE DEL 2013
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * SE MODIFICO PARA QUE EL NOMBRE DEL GRUPO SE ESCAPEN SUS CARACTERES
 * @param type $nombreGrupo
 * @param type $idGrupo
 * @return boolean
 */
function consultaNombreGrupo($nombreGrupo, $idGrupo = NULL) {

    $query = new Query("SG");
    $nombreGrupo = __($nombreGrupo);

    if ($idGrupo != NULL) {
        $query->sql = <<<SQL
                select nombre_grupo from grupo where nombre_grupo='$nombreGrupo'  and id_grupo != $idGrupo and status = 1
SQL;
    } else {
        $query->sql = <<<SQL
                select nombre_grupo from grupo where nombre_grupo='$nombreGrupo' and status = 1
SQL;
    }

    $resultados = $query->select("obj");

    if ($resultados) {
        return true;
    } else {
        return false;
    }
}

/**
 * Consulta la clavde de un grupo
 * @param type $claveGrupo
 * @param type $idGrupo
 * @return boolean
 */
function consultaClaveGrupo($claveGrupo, $idGrupo = NULL) {

    $query = new Query("SG");

    $claveGrupo = __($claveGrupo);

    if ($idGrupo != NULL) {
        $query->sql = <<<SQL
                select clave from grupo where clave='$claveGrupo'  and id_grupo != $idGrupo and 
SQL;
    } else {
        $query->sql = <<<SQL
                select clave from grupo where clave='$claveGrupo' 
SQL;
    }

    $resultados = $query->select("obj");

    if ($resultados) {
        return true;
    } else {
        return false;
    }
}

/**
 * Consulta los grupos de curso abierto
 * @param type $idCursoAbierto
 * @return type
 */
function getGruposdeCursoAbierto($idCursoAbierto) {
    $query = new Query("SG");
    $query->sql = <<<SQL
            SELECT id_grupo
            FROM rel_curso_grupo
            WHERE id_curso_abierto = $idCursoAbierto
SQL;
    $resultados = $query->select("obj");
    return $resultados;
}

/**
 * Elimina un grupo de un curso borrando su progreso
 * @param type $arrGrupos
 * @param type $idCursoAbierto
 */
function eliminaGrupoDeCurso($arrGrupos, $idCursoAbierto) {
    foreach ($arrGrupos as $idGrupo) {
        $idRelCursoGrupo = getidRelCursoGrupo2($idCursoAbierto, $idGrupo);
        borraProgresoPorIdRelCursoGrupo($idRelCursoGrupo);
        borraRelCursoGrupo($idCursoAbierto, $idGrupo);
    }
}

/**
 * Borra todas las relaciones dentro un un curso grupo
 * @param type $idCursoAbierto
 * @param type $idGrupo
 * @return boolean
 */
function borraRelCursoGrupo($idCursoAbierto, $idGrupo) {
    $query = new Query("SG");
    if ($query->delete("rel_curso_grupo", "id_curso_abierto = $idCursoAbierto and id_grupo = $idGrupo")) {
        return true;
    } else {
        return false;
    }
}

/**
 * Consulta que retorna el id de la recion de un curso grupo
 * @param type $idCursoAbierto
 * @param type $idGrupo
 * @return type
 */
function getidRelCursoGrupo2($idCursoAbierto, $idGrupo) {
    $query = new Query("SG");
    $query->sql = <<<SQL
            SELECT id_rel_curso_grupo
            FROM rel_curso_grupo
            WHERE id_curso_abierto = $idCursoAbierto and id_grupo = $idGrupo
SQL;
    $resultados = $query->select("obj");
    foreach ($resultados as $r) {
        return $r->id_rel_curso_grupo;
    }
}

/**
 * Borra el progreso a un id especifico de relacion curso grupo
 * @param type $idRelCursoGrupo
 * @return boolean
 */
function borraProgresoPorIdRelCursoGrupo($idRelCursoGrupo) {
    $query = new Query("SG");
    if ($query->delete("progreso_alumno", "id_rel_curso_grupo=$idRelCursoGrupo")) {
        return true;
    } else {
        return false;
    }
}

/**
 * Consulta el nombre de un grupo y lo retorna en un objeto JSON
 * @param type $nombreGrupo
 * @param type $idGrupo
 * @return type
 */
function consultaNombreGrupoJSON($nombreGrupo, $idGrupo = NULL) {
    return json_encode(consultaNombreGrupo($nombreGrupo, $idGrupo));
}

/**
 * Consula la clave de un grupo y la retorna en un objeto JSON
 * @param type $claveGrupo
 * @param type $idGrupo
 * @return type
 */
function consultaClaveGrupoJSON($claveGrupo, $idGrupo = NULL) {
    return json_encode(consultaClaveGrupo($claveGrupo, $idGrupo));
}

/**
 * Consulta los alumnos de un gurpo
 * @param type $idGrupo
 * @param type $tipo_usuario
 */
function consultaAlumnosGrupo($idGrupo, $tipo_usuario) {
    $query = new Query("SG");

    $query->sql = <<<SQL
            SELECT ga.id_grupo_alumno, dp.id_datos_personales, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, a.id_alumno
            FROM alumnos a, datos_personales dp, grupo_alumno ga
            WHERE a.id_datos_personales = dp.id_datos_personales
              and a.id_alumno = ga.id_alumno
              and a.status=1
              and ga.id_grupo = $idGrupo
SQL;

    $resultados = $query->select("obj");

    if ($resultados) {
        foreach ($resultados as $res) {
            echo <<<TABLA
                <tr>
                    <td>
                        <a class="icon-eye-open verDatosPersonales" id="$res->id_datos_personales" name="$tipo_usuario" title='Ver Alumno' href="#verModal" role="button" data-toggle="modal"></a>
                        <a class="icon-trash" title="Borrar Alumno del Grupo" href="borrarAlumnoGrupo.php?id_grupo_alumno=$res->id_grupo_alumno" onClick="return confirm('¿Está seguro?');"></a>
                    </td>
                    <td>$res->id_alumno</td>
                    <td>$res->nombre_pila</td>
                    <td>$res->primer_apellido</td>
                    <td>$res->segundo_apellido</td>
                </tr>
TABLA;
        }
    }
}

/**
 * Inserta un alumno en un grupo
 * @param type $idAlumno
 * @param type $idGrupo
 */
function insertarGrupoAlumno($idAlumno, $idGrupo) {
    $campos = "id_alumno, id_grupo";
    $valores = "$idAlumno, $idGrupo";

    //Insertar             
    insertarDatos("grupo_alumno", $campos, $valores);

    //Verificar si tenia registros de progreso
    if (($idsRelCursoGrupo = consultaCursosGrupo($idGrupo)) !== false) {
        foreach ($idsRelCursoGrupo as $curso) {
//            imprimeConsola("------Cursos Grupo enrlado---------------");
//          s  imprimeConsola("id rel curso grupo:".$curso->id_rel_curso_grupo);
//            imprimeConsola("id curso abierto:".$curso->id_curso_abierto);
//            imprimeConsola("id grupo:".$curso->id_grupo);
//            imprimeConsola("id alumno: $idAlumno");
            if (verificaProgreso($curso->id_rel_curso_grupo, $idAlumno)) {
                //Reactiva progreso del alumno
                habilitarProgresoAlumnos($curso->id_rel_curso_grupo, $idAlumno);
//                imprimeConsola("Se habilito el progreso alumno en id_rel_curso_aluno:" . $curso->id_rel_curso_grupo . " alumno: $idAlumno");
            } else {
                //Inserta progreso nuevo de alumno
                $arrElementos = getIdElementosAERdeCursoAbierto($curso->id_curso_abierto);
//                imprimeConsola("arr elementos");
//                var_dump($arrElementos);
                insertaEnProgresoAlumno($curso->id_rel_curso_grupo, $arrElementos, $idAlumno);
//                imprimeConsola("Se inserto en progreso alumno:".$curso->id_rel_curso_grupo. " alumno:$idAlumno");
            }
        }
    }
}

function borrarRelGrupoAlumno($idGrupoAlumno) {
    //Obtiene el curso al que pertenecia
    if (($idGrupo = consultaGrupo($idGrupoAlumno)) !== false) {
        //Verificar si tenia registros de progreso
        if (($idsRelCursoGrupo = consultaCursosGrupo($idGrupo->id_grupo)) !== false) {
            foreach ($idsRelCursoGrupo as $idRelCursoGrupo) {
                //Borra los progresos que haya tenido
                //borrarProgreso($idRelCursoGrupo, $idGrupo->id_alumno);
                deshabilitarProgresoAlumnos($idRelCursoGrupo, $idGrupo->id_alumno);
            }
        }
    }

    $query = new Query("SG");
    $query->delete("grupo_alumno", "id_grupo_alumno=$idGrupoAlumno");
}

/**
 * Funcion que consuta un alumno que esta en un grupo
 * @param type $idGrupoAlumno
 * @return boolean
 */
function consultaGrupo($idGrupoAlumno) {
    $query2 = new Query("SG");
    $query2->sql = <<<SQL
        SELECT id_grupo, id_alumno
        FROM grupo_alumno
        WHERE id_grupo_alumno = $idGrupoAlumno
SQL;

    $resultados = $query2->select("obj");

    if ($resultados) {
        foreach ($resultados as $res) {
            return $res;
        }
    } else {
        return false;
    }
}

/**
 * Elimina un grupo de la base de datos
 * @param type $idGrupo
 * @return boolean|null
 */
function eliminaGrupo($idGrupo) {
    if (!is_numeric($idGrupo))
        return NULL;

    $sql = new Query('SG');
    $sql->delete("grupo", "id_grupo = " . $idGrupo);
    return true;
}

?>
