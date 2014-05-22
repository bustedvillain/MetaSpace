<?php

//Control de cambios #&
//Autor:Omar Nava
//Objetivo: ALta cursos
//03-ene-2014
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 04 de Octubre del 2013
 * Objetivo: Trabaja con cursos, de Moodle y del sistema de gestion
 */

/**
 * CHANGE CONTROL 0.99.7
 * FECHA: 8 DE ENERO DEL 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJEVITO: ENROLAR USUARIOS EN CURSO/GRUPO DE MOODLE
 * Consulta que retorna los ids de tutores en un curso grupo
 */

/**
 * CHANGE CONTROL 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACIÓN: 21 DE MAYO DE 2014
 * OBJETIVO: CAMBIOS ESTETICOS
 */

/**
 * Funcion que consulta los cursos de Moodle, mostrando solo
 * lo que no han sido vinculados al sistema de gestión
 */
function consultaCursosMoodle() {
    //Crea objeto para realizar consultas a moodle
    $query = new Query("MOD");
    //Query a Moodle
    $query->sql = "SELECT id, fullname, shortname, format from mdl_course";
    $resultado = $query->select("obj");

    foreach ($resultado as $curso) {
        if ($curso->format == "topics") {
            if (!validaIdCursoMoodle($curso->id)) {
                echo <<<HTML
                    <tr>
                        <td>$curso->id</td>
                        <td>$curso->fullname</td>
                        <td>$curso->shortname</td>
                        <td><a href="nuevoCurso_1.php?id=$curso->id&fullname=$curso->fullname&shortname=$curso->shortname">Vincular</a></td>
                    </tr>
HTML;
            }
        }
    }
}

/**
 * Consulta la informacion especifica sobre un curso en moodle
 * @param type $idCurso
 * @return type
 */
function consultaCursoMoodle($idCurso) {

    //Crea objeto para realizar consultas a moodle
    $query = new Query("MOD");
    //Query a Moodle
    $query->sql = "SELECT id, fullname, shortname, format from mdl_course where id=$idCurso";
    $resultado = $query->select("obj");

    foreach ($resultado as $curso) {
        return array("id" => $curso->id, "fullname" => $curso->fullname, "shortname" => $curso->shortname);
    }
}

/**
 * Valida que si el curso de Moodie (id) ya se encuentra
 * vinculado al sistema de gestion
 * @param type $idCursoMoodle
 * @return boolean
 */
function validaIdCursoMoodle($idCursoMoodle) {
    //Crea objeto para realizar consultas al sistema de gestion
    $query = new Query("SG");
    //Query a SGI
    $query->sql = "SELECT id_curso, id_curso_moodle from cursos where id_curso_moodle=$idCursoMoodle";
    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $res) {
            echo <<<SCRIPT
            <script>
                debugConsole("Este curso ya esta vinculado, id:$res->id_curso");
            </script>
SCRIPT;
        }
        return true;
    } else {
        echo <<<SCRIPT
            <script>
                debugConsole("Este curso no esta vinculado, id:$idCursoMoodle");
            </script>
SCRIPT;
        return false;
    }
}

/**
 * Consulta los topicos que tiene un curso en Moodle
 * @param type $idCursoMoodle
 */
function consultaTopicosCursoMoodle($idCursoMoodle) {
    //Crea objeto para realizar consultas al sistema de gestion
    $query = new Query("MOD");
    //Query a Moodle
    $query->sql = "SELECT section.section, section.name, section.summary from mdl_course course, mdl_course_sections section where course.id=$idCursoMoodle and course.id=section.course order by section.section";
    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $topico) {
            $nombre = ($topico->name) ? strip_tags($topico->name) : "";
            $descripcion = ($topico->summary != "") ? strip_tags($topico->summary) : "";
            if ($topico->section != "0") {
                echo <<<HTML
                <h4>Bloque $topico->section</h4>
                <div class="input-append">
                    <label>Nombre del Bloque:</label>
                    <input type="text" name="nombre_unidad[]" placeholder="" value="$nombre">
                </div>
                <div class="input-append">
                    <label>Descripci&oacute;n:</label>
                    <input type="text" name="descripcion[]" placeholder="" value="$descripcion">
                </div>
                <div class="input-append">
                    <label>Contenido HTML5:</label>
                    <input type="file" name="contenido[]" placeholder="" class="contenido">
                </div>
HTML;
            }
        }
    } else {
        echo "<h3 class='text-warning'>No hay t&oacute;picos en este curso.</h3>";
    }
}

/**
 * Funcion que valida la existencia de un curso y la regresa en un objeto JSON
 * @param type $clave_curso
 * @return type boolean, true si si existe
 */
function validarClaveCursoJSON($clave_curso) {
    return json_encode(validarClaveCurso($clave_curso));
}

/**
 * Funcion que valida la existencia de una clave de curso
 * @param type $clave_curso
 * @return boolean true si si exsite
 */
function validarClaveCurso($clave_curso) {
    $query = new Query("SG");
    //Query a SGI
    $query->sql = "SELECT clave_curso from cursos where clave_curso='$clave_curso'";
    $resultado = $query->select("obj");

    if ($resultado) {
        return true;
    } else {
        return false;
    }
}

/**
 * Funcion que valida si ya existe ese nombre de curso y lo retorna en JSON
 * @param type $nombre_curso
 * @return type retorna true si si existe
 */
function validarNombreCursoJSON($nombre_curso) {
    return json_encode(validarNombreCurso($nombre_curso));
}

/**
 * Funcion que valida si ya existe ese nombre de curso
 * @param type $nombre_curso
 * @return boolean true si si existe
 */
function validarNombreCurso($nombre_curso) {
    $query = new Query("SG");
    //Query a SGI
    $query->sql = "SELECT nombre_curso from cursos where nombre_curso='$nombre_curso'";
    $resultado = $query->select("obj");

    if ($resultado) {
        return true;
    } else {
        return false;
    }
}

/**
 * Funcion que valida si el nombre corto del curso ya existe en JSON
 * @param type $nombre_corto
 * @return type true si si existe en un boolean JSON
 */
function validarNombreCortoJSON($nombre_corto) {
    return json_encode(validarNombreCorto($nombre_corto));
}

/**
 * Funcion que valida si el nombre corto ya existe
 * @param type $nombre_corto
 * @return boolean true si si existe
 */
function validarNombreCorto($nombre_corto) {
    $query = new Query("SG");
    //Query a SGI
    $query->sql = "SELECT nombre_corto from cursos where nombre_corto='$nombre_corto'";
    $resultado = $query->select("obj");

    if ($resultado) {
        return true;
    } else {
        return false;
    }
}

/**
 * Funcion que imprime una tabla con los cursos activos
 */
function catalogoCursos() {
    $query = new Query("SG");
    //Query a SGI
    $query->sql = "SELECT id_curso, clave_curso, nombre_curso, nombre_corto from cursos where status=1";
    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $reg) {
            echo <<<TABLA
                <tr>
                    <td>
                        <a class="icon-eye-open verCurso" href="#verModal" role="button" data-toggle="modal" name="$reg->id_curso" title="Ver"></a>                            
                        <a class="icon-edit editaCurso" href="#editarModal" role="button" data-toggle="modal" name="$reg->id_curso" title="Editar"></a>
                        <a class="icon-trash" href="borrarCurso.php?id=$reg->id_curso" onClick="return confirm('¿Está seguro?');" title="Borrar"></a>
                    </td>
                    <td>$reg->id_curso</td>
                    <td>$reg->nombre_curso</td>
                    <td>$reg->nombre_corto</td>
                    <td>$reg->clave_curso</td>                    
                    <td><a href="abrirCurso.php?id=$reg->id_curso&nombre=$reg->nombre_curso">Publicar Curso</a></td>
                </tr>
TABLA;
        }
    }
}

/**
 * Funcion que consulta la información de un curso y la retorna en un arreglo
 * @param type $idCurso
 * @return array 
 */
function consultaInfoCurso($idCurso) {
    $idCurso = __($idCurso);
    $query = new Query("SG");
    //Query a SGI
    //inicia control de cambios #6
    $query->sql = <<<SQL
            SELECT 
                c.id_curso, 
                c.clave_curso, 
                c.nombre_curso, 
                c.nombre_corto,
                ca.nombre_categoria,
                a.nombre_asignatura,
                ne.nombre nivel,
                ge.nombre_grado,
                c.id_curso_moodle,
                c.id_gestor_contenido,
                c.id_categoria,
                c.id_asignatura,
                c.id_grado_escolar grado, 
                ne.id_nivel
            from cursos c
            left join categorias ca
                    on ca.id_categoria = c.id_categoria
            left join grado_escolar ge
                    on ge.id_grado_escolar = c.id_grado_escolar
            left join nivel_escolar ne
                    on ne.id_nivel = ge.id_nivel
            left join asignaturas a
                    on c.id_asignatura = a.id_asignatura
            where c.id_curso = $idCurso
SQL;

//    echo "<br>SQL:<br>".$query->sql;
    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $res) {
            $cursoMoodle = consultaCursoMoodle($res->id_curso_moodle);

            $curso = array("id_curso" => $res->id_curso,
                "clave_curso" => $res->clave_curso,
                "nombre_curso" => $res->nombre_curso,
                "nombre_corto" => $res->nombre_corto,
                "nombre_categoria" => $res->nombre_categoria,
                "nombre_asignatura" => $res->nombre_asignatura,
                "nivel" => $res->nivel,
                "id_grado" => $res->grado,
                "nombre_grado" => $res->nombre_grado,
                //termina control de cambios #6
                "curso_moodle" => $cursoMoodle["fullname"],
                "id_categoria" => $res->id_categoria,
                "id_asignatura" => $res->id_asignatura,
                "id_nivel" => $res->id_nivel,
                "id_curso_moodle" => $res->id_curso_moodle);

            //Checa info del gestor de contenido
            if ($res->id_gestor_contenido != "") {
                $query->sql = "SELECT id_datos_personales FROM  gestor_contenido WHERE id_gestor_contenido = " . $res->id_gestor_contenido;
                $gestorConsulta = $query->select("obj");

                $gestorIdDatosPersonales = "";

                if ($gestorConsulta) {
                    foreach ($gestorConsulta as $g) {
                        $gestorIdDatosPersonales = $g->id_datos_personales;
                    }
                }
                $datosGestor = consultaDatosPersonales($gestorIdDatosPersonales);

                $curso["gestor"] = $datosGestor["nombre_pila"] . " " . $datosGestor["primer_apellido"] . " " . $datosGestor["segundo_apellido"];
            } else {
                $curso["gestor"] = "Administrador";
            }

            //Checa topicos
            $curso["topicos"] = consultaTopicos($idCurso);

            return $curso;
        }
    }
}

/**
 * Consulta la información de un curso y la retorna en un objeto JSON
 * @param type $idCurso
 * @return type}
 */
function consultaInfoCursoJSON($idCurso) {
    return json_encode(consultaInfoCurso($idCurso));
}

/**
 * Funcion que del id de un curso consulta los topicos relacionados
 * y lo retorna en un arreglo bidimensional
 * @param type $idCurso
 * @return type
 */
function consultaTopicos($idCurso) {
    $query = new Query("SG");
    //Query a SGI
    $query->sql = <<<SQL
            SELECT id_unidad, no_unidad, nombre_unidad, descripcion, url_unidad, status, id_curso
            FROM unidades 
            WHERE id_curso=$idCurso order by no_unidad;
SQL;

    $resultado = $query->select("obj");

    $topicos = array();
    $i = 0;
    if ($resultado) {
        foreach ($resultado as $res) {
            $topicos[$i]["id_unidad"] = $res->id_unidad;
            $topicos[$i]["no_unidad"] = $res->no_unidad;
            $topicos[$i]["nombre_unidad"] = $res->nombre_unidad;
            $topicos[$i]["descripcion"] = $res->descripcion;
            $topicos[$i]["status"] = $res->status;
            $topicos[$i]["url_unidad"] = $res->url_unidad;
            $topicos[$i]["id_curso"] = $res->id_curso;
            $i++;
        }
        return $topicos;
    }
}

/**
 * Prepara el codigo html para el formulario de abrir curso
 * donde se especifican las fechas para cada unidad
 * @param type $idCurso
 * @return type
 */
function imprimeTopicosFechas($idCurso) {
    $query = new Query("SG");
    //Query a SGI
    $query->sql = <<<SQL
            SELECT id_unidad, no_unidad, nombre_unidad, descripcion, url_unidad, status, id_curso
            FROM unidades 
            WHERE id_curso=$idCurso and status=1 order by no_unidad;
SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        $i = 0;
        foreach ($resultado as $res) {
            echo <<<HTML
                <h4>Bloque $res->no_unidad</h4>
                <p><b>Nombre:</b> $res->nombre_unidad</p>
                <p><b>Descripcion:</b> $res->descripcion</p>
                <div class="input-append">
                    <label>Fecha de Inicio (dd/mm/yyyy):</label>
                    <input type="date" placeholder="" name="fecha_inicio[]" id="fecha_inicio$i" class="fecha_inicio" pattern="\d{1,2}/\d{1,2}/\d{4}">
                </div>
                <div class="input-append">
                    <label>Fecha de Terminaci&oacute;n (dd/mm/yyyy):</label>
                    <input type="date" placeholder="" name="fecha_fin[]" id="fecha_fin$i" class="fecha_fin" pattern="\d{1,2}/\d{1,2}/\d{4}">
                </div>
                <label class="text-error" id="errorFecha$i"></label>
                <label class="text-error" id="errorFechaRango$i"></label>
                <input type="hidden" name="id_unidad[]" value="$res->id_unidad">
HTML;

            $i++;
        }
    }else{
        imprimeError("No hay bloques activos.");
    }
}

/**
 * Funcion que recibe información de un post, lo recorre
 * e inserta el tutor en la relacion curso abierto tutor
 * @param type $tutor
 * @param type $POST
 * @param type $idCursoAbierto
 */
function insertarTutoresCursoAbierto($tutor, $POST, $idRelacionCursoGrupo, $idGrupo, $borraReferencia = false) {
    $refGrupo = $idGrupo;
    if ($borraReferencia === true) {
        $refGrupo = "";
    }
//    echo "<br><br>++++++++++++++++++++++++++++";
//    echo "<br>Tutor: $tutor<br>";
//    var_dump($POST[$tutor . $refGrupo]);
    //Valida que si traiga esa variable
    $idTutores = array();
    if (array_key_exists($tutor . $refGrupo, $POST)) {
        foreach ($POST[$tutor . $refGrupo] as $idTutor) {

//        echo "<br><br>*********************";
//        echo "<br>id tutor:$idTutor";
//        echo "<br>id curso abierto:$idCursoAbierto";
//        echo "<br>id grupo:$idGrupo";
//        echo "<br>id_rel_curso_grupo:$idRelacionCursoGrupo";
            $campos = "id_rel_curso_grupo, id_tutor";
            $valores = "$idRelacionCursoGrupo, $idTutor";

            //Insertar 
            insertarDatos("rel_curso_tutor", $campos, $valores);
            array_push($idTutores, $idTutor);
        }
    }

    return $idTutores;
}

/**
 * Consulta id de relacion curso grupo
 * @param type $idCursoAbierto
 * @param type $idGrupo
 * @return type
 */
function consultaRelCursoGrupo($idCursoAbierto, $idGrupo) {
    $query = new Query("SG");
    $query->sql = <<<SQL
            SELECT id_rel_curso_grupo
            FROM rel_curso_grupo
            WHERE id_curso_abierto = $idCursoAbierto 
              and id_grupo = $idGrupo
SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $res) {
            return $res->id_rel_curso_grupo;
        }
    }
}

/**
 * Funcion que recibe un curso abierto y vacia las relacones
 * @param type $idCursoAbierto
 * @return boolean
 */
function vaciaRelacionesCursoTutor($idRelCursoGrupo) {
    $query = new Query("SG");

    if ($query->delete("rel_curso_tutor", "id_rel_curso_grupo=$idRelCursoGrupo")) {
        return true;
    } else {
        return false;
    }
}

/**
 * Funcion qu genera una tabla con los cursos abierto
 */
function catalogoCursosAbiertos() {
    $query = new Query("SG");
    //Query a SGI
    $query->sql = "SELECT ca.id_curso_abierto, ca.nombre_curso_abierto, ca.descripcion, ca.fecha_inicio, ca.fecha_fin, c.nombre_curso from cursos c, cursos_abiertos ca where c.status=1 and ca.status=1 and c.id_curso = ca.id_curso";
    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $reg) {
            echo <<<TABLA
                <tr>
                    <td>
                        <a class="icon-eye-open verCursoAbierto" title='Ver' href="#verModal" role="button" data-toggle="modal" name="$reg->id_curso_abierto"></a>                            
                        <a class="icon-edit editaCursoAbierto" title='Editar' href="#editarModal" role="button" data-toggle="modal" name="$reg->id_curso_abierto"></a>
                        <a class="icon-lock" title='Cerrar Curso' href="cerrarCurso.php?id=$reg->id_curso_abierto" onClick="return confirm('¿Está seguro de cerrar este curso?');"></a>
                        <a class="icon-bookmark" title='Ver Tutores Asignados' href="verTutoresCurso.php?id=$reg->id_curso_abierto"></a>
                        <a class="icon-user" title='Ver Grupos Asignados' href="verGruposCurso.php?id=$reg->id_curso_abierto"></a>
                    </td>
                    <td>$reg->id_curso_abierto</td>
                    <td>$reg->nombre_curso_abierto</td>
                    <td>$reg->descripcion</td>
                    <td>$reg->fecha_inicio</td>
                    <td>$reg->fecha_fin</td>
                    <td><a href="enrolarGrupo.php?id=$reg->id_curso_abierto">Asignar Grupos</a></td>
                </tr>
TABLA;
        }
    }
}

/**
 * Funcion que consulta un curso abierto y lo retorna
 * en un objeto JSON 
 * @param type $idCursoAbierto
 * @return type
 */
function consultaCursoAbiertoJSON($idCursoAbierto) {
    return json_encode(consultaCursoAbierto($idCursoAbierto));
}

/**
 * Funcion que busca la información de un curso abierto
 * @param type $idCursoAbierto
 * @return null
 */
function consultaCursoAbierto($idCursoAbierto) {
    $query = new Query("SG");
    //Query a SGI
    $query->sql = "SELECT ca.id_curso_abierto, ca.nombre_curso_abierto, ca.descripcion, ca.fecha_inicio, ca.fecha_fin, c.nombre_curso from cursos c, cursos_abiertos ca where c.status=1 and ca.status=1 and c.id_curso = ca.id_curso and ca.id_curso_abierto=$idCursoAbierto";
    $resultado = $query->select("arr");

    if ($resultado) {
        foreach ($resultado as $r) {
            $r["fechas_unidades"] = consultaFechasUnidadesCursos($idCursoAbierto);
//            $r["tutores"]=consultaTutoresCursoAbierto($idCursoAbierto);
            return $r;
        }
    } else {
        return null;
    }
}

/**
 * Funcion que consulta las fechas de las unidades dentro 
 * de un curso abierto
 * @param type $idCursoAbierto
 * @return type
 */
function consultaFechasUnidadesCursos($idCursoAbierto) {
    $query = new Query("SG");
    //Query a SGI
    $query->sql = <<<SQL
            SELECT u.nombre_unidad, u.no_unidad, u.descripcion, f.id_fecha_unidades_curso, f.fecha_inicio, f.fecha_fin, f.id_curso_abierto, f.id_unidad
            FROM fechas_unidades_cursos f, unidades u
            WHERE f.id_curso_abierto=$idCursoAbierto 
              and f.id_unidad = u.id_unidad
                  order by f.id_unidad;
SQL;

    $resultado = $query->select("obj");

    $topicos = array();
    $i = 0;
    if ($resultado) {
        foreach ($resultado as $res) {
            $topicos[$i]["id_fecha_unidades_curso"] = $res->id_fecha_unidades_curso;

            if ($res->fecha_inicio != NULL)
                $topicos[$i]["fecha_inicio"] = $res->fecha_inicio;
            else
                $topicos[$i]["fecha_inicio"] = "";

            if ($res->fecha_fin != NULL)
                $topicos[$i]["fecha_fin"] = $res->fecha_fin;
            else
                $topicos[$i]["fecha_fin"] = "";

            $topicos[$i]["id_curso_abierto"] = $res->id_curso_abierto;
            $topicos[$i]["id_unidad"] = $res->id_unidad;
            $topicos[$i]["nombre_unidad"] = $res->nombre_unidad;
            $topicos[$i]["no_unidad"] = $res->no_unidad;
            $topicos[$i]["descripcion"] = $res->descripcion;

            $i++;
        }
        return $topicos;
    }
}

/**
 * Consulta los tutores en un curso abierto
 * @param type $idCursoAbierto
 */
function consultaTutoresCursoAbierto($idCursoAbierto) {
    $query = new Query("SG");
    //Query a SGI
    $query->sql = <<<SQL
            SELECT dp.id_datos_personales, g.nombre_grupo, rct.id_rel_curso_tutor, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, rt.nombre rol, t.id_tutor
            FROM rel_curso_tutor rct, rel_curso_grupo rcg, grupo g, tutor t, rol_tutor rt, datos_personales dp
            WHERE rcg.id_curso_abierto= $idCursoAbierto
              and rct.id_tutor = t.id_tutor
              and t.id_rol_tutor = rt.id_rol_tutor
              and t.id_datos_personales = dp.id_datos_personales
              and rcg.id_grupo = g.id_grupo
              and rct.id_rel_curso_grupo = rcg.id_rel_curso_grupo
              
SQL;
//    echo "SQL:".$query->sql;

    $resultado = $query->select("obj");

//    $tutores = array();
//    $i = 0;
    if ($resultado) {
        foreach ($resultado as $res) {
//            $tutores[$i]["nombre"]= "$res->nombre_pila $res->primer_apellido $res->segundo_apellido";
//            $tutores[$i]["rol"]= $res->rol;
//            $i++;
            $nombre_grupo = html_entity_decode($res->nombre_grupo);
            echo <<<TABLA
              <tr>
                    <td>
                        <a class="icon-eye-open verDatosPersonales" name="tutor" id="$res->id_datos_personales" title="Ver Datos Personales" href="#verModal" role="button" data-toggle="modal"></a>
                        <a class="icon-trash" title='Remover del Curso' href="removerTutor.php?idRelacion=$res->id_rel_curso_tutor&id=$idCursoAbierto" onClick="return confirm('¿Está seguro de remover al tutor este curso?');"></a>
                    </td>
                    <td>$res->id_tutor</td>
                    <td>$nombre_grupo</td>
                    <td>$res->rol</td>
                    <td>$res->nombre_pila $res->primer_apellido $res->segundo_apellido</td>
               </tr> 
TABLA;
        }
//        return $tutores;
    }
}

/**
 * Funcion que elimina la relacion entre un tutor y un curso abierto
 * @param type $idRelacion
 * @return boolean
 */
function borrarRelacionTutorCursoAbierto($idRelacion) {

    //Verifica si es un coordinador el que se quiere borrar
    if (verificaCoordinador($idRelacion)) {
        //Verifica si hay mas de un coodinador en el grupo_curso
        if (validaCoordiandorGrupoCursoAbierto(getRelCursoGrupo($idRelacion))) {
            $borra = true;
        } else {
            $borra = false;
        }
    } else {
        $borra = true;
    }

    if ($borra == true) {
        $query = new Query("SG");
        if ($query->delete("rel_curso_tutor", "id_rel_curso_tutor=$idRelacion")) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Funcion que verifica si el id_relacion_curso pertenece
 * a coordinador de tutores, si es asi, retorna un true, 
 * si no un false
 * @param type $idRelacion
 * @return boolean
 */
function verificaCoordinador($idRelacion) {
    $query = new Query("SG");

    $query->sql = <<<SQL
            SELECT t.id_rol_tutor
            FROM rel_curso_tutor r, tutor t
            WHERE t.status=1
              and r.id_tutor = t.id_tutor
              and r.id_rel_curso_tutor= $idRelacion

SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $res) {
            if ($res->id_rol_tutor == 3) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}

/**
 * Valida que haya al menos un coordinador de tutores en el curso
 * @param type $idCursoAbierto
 * @return boolean
 */
function validaCoordiandorGrupoCursoAbierto($idRelCursoGrupo) {
    $query = new Query("SG");

    $query->sql = <<<SQL
            SELECT count(*) coords
            FROM rel_curso_tutor r, tutor t, rol_tutor rt
            WHERE rt.status=1
              and t.status=1
              and t.id_rol_tutor = 3
              and r.id_tutor = t.id_tutor
              and t.id_rol_tutor = rt.id_rol_tutor
              and r.id_rel_curso_grupo=$idRelCursoGrupo

SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $res) {
            if ($res->coords > 1) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}

/**
 * Obtiene la relacion curso grupo de acuerdo a la relacion de un tutor
 * @param type $idRelCursoTutor
 * @return boolean
 */
function getRelCursoGrupo($idRelCursoTutor) {
    $query = new Query("SG");

    $query->sql = <<<SQL
            SELECT id_rel_curso_grupo
            FROM rel_curso_tutor
            WHERE id_rel_curso_tutor=$idRelCursoTutor

SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $res) {
            return $res->id_rel_curso_grupo;
        }
    } else {
        return false;
    }
}

/**
 * Funcion qu recibe el id de un curso bierto y retorna su nombre
 * @param type $idCursoAbierto
 * @return boolean
 */
function getNombreCursoAbierto($idCursoAbierto) {
    if (!$idCursoAbierto)
        return FALSE;


    $query = new Query("SG");

    $query->sql = <<<SQL
            SELECT nombre_curso_abierto
            FROM cursos_abiertos
            WHERE id_curso_abierto=$idCursoAbierto

SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $res) {
            return $res->nombre_curso_abierto;
        }
    } else {
        return false;
    }
}

/**
 * CHANGE CONTROL 1.1.0
 * FECHA DE MODIFICACION: 21 DE MAYO DEL 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJETIVO: CREAR FUNCION PARA RETORNAR NOMBRE DEL CURSO RAIZA DE UN CURSO ABIERTO/PUBLICADO
 * 
 * Funcion qu recibe el id de un curso bierto y retorna el nombre del curso raiz
 * @param type $idCursoAbierto
 * @return boolean
 */
function getNombreCurso($idCursoAbierto) {
    if (!$idCursoAbierto)
        return FALSE;

    $query = new Query("SG");

    $query->sql = <<<SQL
            SELECT c.nombre_curso
            FROM cursos_abiertos ca, cursos c
            WHERE ca.id_curso_abierto=$idCursoAbierto 
              and ca.id_curso = c.id_curso

SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $res) {
            return $res->nombre_curso;
        }
    } else {
        return false;
    }
}

/**
 * Devuelve un arreglo con id's de elementos aer que corresponden a un curso abierto
 * @param type $idCursoAbierto
 * @return array|boolean
 */
function getIdElementosAERdeCursoAbierto($idCursoAbierto) {
    $query = new Query("SG");

    $query->sql = <<<SQL
    select e.id_elemento_aer as "idElementoAer"
    from elemento_aer e
    join serie_aer s
            on s.id_serie_aer = e.id_serie_aer
    join unidades u
            on u.id_unidad = s.id_unidad
    join cursos_abiertos ca
            on ca.id_curso = u.id_curso
    where ca.id_curso_abierto = $idCursoAbierto

SQL;

    $resultado = $query->select("obj");
    $arrElementos = array();
    if ($resultado) {
//        imprimeConsola("Encontro eleentos");
        foreach ($resultado as $res) {
            array_push($arrElementos, $res->idElementoAer);
        }
        return $arrElementos;
    } else {
//        imprimeError("No hay elmentos");
        return false;
    }
}

/**
 * Devuelve un arreglo de id_grupo_alumno de un grupo
 * @param type $idGrupo
 * @return array|boolean
 */
function getArrGrupoAlumno($idGrupo) {
    $query = new Query("SG");

    $query->sql = <<<SQL
    select *
    from grupo_alumno 
    where id_grupo = $idGrupo

SQL;

    $resultado = $query->select("obj");
    $arrGrupoAlumno = array();
    if ($resultado) {
        foreach ($resultado as $res) {
            array_push($arrGrupoAlumno, $res->id_grupo_alumno);
        }
        return $arrGrupoAlumno;
    } else {
        return false;
    }
}

/**
 * Devuelve un arreglo de id_grupo_alumno de un grupo
 * @param type $idGrupo
 * @return array|boolean
 */
function getArrIdsAlumno($idGrupo) {
    $query = new Query("SG");

    $query->sql = <<<SQL
    select *
    from grupo_alumno 
    where id_grupo = $idGrupo

SQL;

    $resultado = $query->select("obj");
    $arrGrupoAlumno = array();
    if ($resultado) {
        foreach ($resultado as $res) {
            array_push($arrGrupoAlumno, $res->id_alumno);
        }
        return $arrGrupoAlumno;
    } else {
        return false;
    }
}

/**
 * CHANGE CONTROL 0.99.7
 * Autor: José Manuel Nieto Gómez
 * Objetivo: Consulta que retorna los ids de tutores en un curso grupo 
 * Funcion que recibe el id curso tutor y retorna los id's de los tutores
 * @param type $idRelCursoTutor
 * @return boolean|array
 */
function getArrIdTutores($idRelCursoTutor) {
    $query = new Query("SG");

    $query->sql = <<<SQL
    select *
    from rel_curso_tutor 
    where id_rel_curso_tutor = $idRelCursoTutor

SQL;

    $resultado = $query->select("obj");
    $arrCursoTutor = array();
    if ($resultado) {
        foreach ($resultado as $res) {
            array_push($arrCursoTutor, $res->id_tutor);
        }
        return $arrCursoTutor;
    } else {
        return false;
    }
}

/**
 * 
 * @param type $idGrupo
 * @param type $idCursoAbierto
 * @param type $arrElementos
 * @param type $arrIdAlumnos
 * @return type
 */
function vinculaGrupoAlumnoCursoAbierto($idGrupo, $idCursoAbierto, $arrElementos, $arrIdAlumnos) {
    $query = new Query("SG");
    $query->sql = <<<SQL
    select *
    from rel_curso_grupo 
    where id_grupo = $idGrupo and id_curso_abierto = $idCursoAbierto
SQL;
//    imprimeConsola($query->sql);

    $result = $query->select("obj");
    if ($result) {
        foreach($result as $r){
            $idRelCursoGrupo = $r->id_rel_curso_grupo;
        }        
    } else {
        $idRelCursoGrupo = insertarDatos("rel_curso_grupo", "id_curso_abierto, id_grupo", "$idCursoAbierto, $idGrupo");
//        var_dump($arrIdAlumnos);
//        var_dump($arrElementos);
        foreach ($arrIdAlumnos as $idAlumno) {
            @insertaEnProgresoAlumno($idRelCursoGrupo, $arrElementos, $idAlumno);
        }
    }

    return $idRelCursoGrupo;
}

//function vinculaGrupoAlumnoCursoAbierto($arrElementos,$arrIdGrupos, $idCursoAbierto)
//{
//    foreach($arrIdGrupos as $idGrupoAlumno)
//    {
//        $idRelCursoGrupo = insertarDatos("rel_curso_grupo", "id_curso_abierto, id_grupo_alumno", "$idCursoAbierto, $idGrupoAlumno");
//        insertaEnProgresoAlumno($idRelCursoGrupo,$arrElementos);
//    }
//}

/**
 * Inserta los registros de progreso para un alumno dentro de un grupo
 * @param type $idRelCursoGrupo
 * @param type $arrElementos
 * @param type $idAlumno
 */
function insertaEnProgresoAlumno($idRelCursoGrupo, $arrElementos, $idAlumno) {
    foreach ($arrElementos as $elem) {
        if (!existeAlumnoEnProgreso($idAlumno, $elem)) {
            insertarDatos("progreso_alumno", "status, id_elemento_aer, estatus_evaluacion, id_rel_curso_grupo, id_alumno", "1, $elem, 0, $idRelCursoGrupo, $idAlumno");
        }
    }
}

/**
 * Verifica si el alumno tiene progreso en algun curso
 * @param type $idAlumno
 * @param type $idElemento
 * @return boolean
 */
function existeAlumnoEnProgreso($idAlumno, $idElemento) {
    $query = new Query("SG");
    $query->sql = <<<SQL
    select *
    from progreso_alumno 
    where id_alumno = $idAlumno and id_elemento_aer = $idElemento
SQL;

    $query->select("obj");
    if ($query->numRegistros() > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * Funcion que imprime lo grupos relacionados a un curso abierto
 * si secconados es true se para los tutores por seleccionaodos y disponibles
 * @param type $idCursoAbierto
 * @param type $secciondos
 */
function imprimeGruposCursoAbierto($idCursoAbierto, $seccionados = false) {

    $query = new Query("SG");

    $query->sql = <<<SQL
            SELECT r.id_rel_curso_grupo, r.id_grupo, g.nombre_grupo, g.clave, g.id_escuela, g.id_empresa, g.tipo_grupo
            FROM rel_curso_grupo r, grupo g
            WHERE r.id_grupo = g.id_grupo
              and r.id_curso_abierto = $idCursoAbierto
              and g.status=1
SQL;
    $resultados = $query->select("obj");
    $grupos = "";


    $i = 0;
    if ($resultados) {
        foreach ($resultados as $res) {
            echo "<div class='accordion-group'>";

            $grupos.=$res->id_grupo . ";";


            echo <<<heading
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse$res->id_grupo">
                        <b>Asigne Tutores Senior y Junior para el grupo:</b> $res->nombre_grupo
                    </a>
                </div>
                    
                <div id="collapse$res->id_grupo" class="accordion-body collapse">
                    <div class="accordion-inner">
                    
heading;

            if ($res->tipo_grupo == 0) {
                $nombreInstitucion = getNombreInstitucion($res->id_escuela);
                $nombreEscuela = getNombreEscuela($res->id_escuela);

                echo <<<nombre
                    <p><b>Instituci&oacute;n:</b> $nombreInstitucion</p>
                    <p><b>Escuela:</b> $nombreEscuela</p>
nombre;
            } else if ($res->tipo_grupo == 1) {
                $nombreEmpresa = getNombreEmpresa($res->id_empresa);

                echo <<<nombre
                    <p><b>Empresa:</b> $nombreEmpresa</p>
nombre;
            }

            //Imprime selects de tutores
            echo <<<tutores
                <legend>Tutores Senior</legend>
                <p class="text-warning">A continuaci&oacute;n se muestran los Tutores Senior disponibles para asignar en el curso.</p>
                <p class="text-warning">Seleccione los tutores disponibles y dé click en 'Agregar' para asignarlos. Si desea remover tutores asignados, seleccionelos y dé click en 'Quitar'.</p>
                
                <br/><br/>
                <div class="divGrupo">
                    <p>Tutores Senior Disponibles:</p>
                    <select class="grupo select-from" id="select-from$i" name="$i" multiple size="15">
tutores;
            if ($seccionados == true) {
                comboTutores(2, "disponibles", NULL, $res->id_rel_curso_grupo);
            } else {
                comboTutores(2);
            }
            echo <<<tutores
                    </select>
                </div>
                <div class="botones">
                    <button type="button" class="btn btn-success btn-add" name="$i">Agregar</button>
                    <button type="button" class="btn btn-info btn-remove" name="$i">Quitar</button>
                </div>
                <div class="divGrupo">
                    <p>Tutores Senior Asignados:</p>
                    <select class="grupo select-to" name="seniors$res->id_grupo[]" id="select-to$i" title="$i" multiple size="15">
tutores;
            if ($seccionados == true) {
                comboTutores(2, "seleccionados", NULL, $res->id_rel_curso_grupo);
            }
            echo <<<tutores
                    </select>                                            
                </div>                   
                
                <div style="clear: both"></div>
tutores;
            $i++;
            echo <<<tutores
                <legend>Tutores Junior</legend>
                <p class="text-warning">A continuaci&oacute;n se muestran los Tutores Junior disponibles para asignar en el curso. </p>
                <p class="text-warning">Seleccione los tutores disponibles y dé click en 'Agregar' para asignarlos. Si desea remover tutores asignados, seleccionelos y dé click en 'Quitar'.</p>
                
                <br/><br/>
                <div class="divGrupo">
                    <p>Tutores Junior Disponibles:</p>
                    <select class="grupo select-from" id="select-from$i" name="$i" multiple size="15">
tutores;
            if ($seccionados == true) {
                comboTutores(1, "disponibles", NULL, $res->id_rel_curso_grupo);
            } else {
                comboTutores(1);
            }
            echo <<<tutores
                    </select>
                </div>
                <div class="botones">
                    <button type="button" class="btn btn-success btn-add" name="$i">Agregar</button>
                    <button type="button" class="btn btn-info btn-remove" name="$i">Quitar</button>
                </div>
                <div class="divGrupo">
                    <p>Tutores Junior Asignados:</p>
                    <select class="grupo select-to" name="juniors$res->id_grupo[]" id="select-to$i" title="$i" multiple size="15">
tutores;
            if ($seccionados == true) {
                comboTutores(1, "seleccionados", NULL, $res->id_rel_curso_grupo);
            }
            echo <<<tutores
                    </select>                                            
                </div>
                   
                
tutores;

            echo "</div></div></div>";
            $i++;
        }
        echo "<input type='hidden' name='id_grupos' value='$grupos'/>";
    }
}

/**
 * Funcion que consulta los grupos de un curso abierto
 * @param type $idCursoAbierto
 */
function consultaGruposCurso($idCursoAbierto) {
    $query = new Query("SG");
    $query->sql = <<<SQL
            SELECT r.id_rel_curso_grupo, g.nombre_grupo, g.id_grupo, g.clave
            FROM rel_curso_grupo r, grupo g
            WHERE r.id_curso_abierto = $idCursoAbierto
              and r.id_grupo = g.id_grupo
              and g.status = 1
SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $res) {
            $infoGrupo = getInfoGrupo($res->id_grupo);
            $nombreInstiucion = (getNombreInstitucion($infoGrupo["id_escuela"]));
            $nombreEscuela = getNombreEscuela($infoGrupo["id_escuela"]);
            $nombreEmpresa = (getNombreEmpresa($infoGrupo["id_empresa"]));
            echo <<<TABLA
                <tr>
                    <td>
                        <a class="icon-user" title="Ver Alumnos en Grupo" href="verAlumnosGrupoCurso.php?id_grupo=$res->id_grupo&id_curso_abierto=$idCursoAbierto"></a>
                        <a class="icon-trash" title='Remover del Curso' href="removerGrupoCurso.php?id_rel_curso_grupo=$res->id_rel_curso_grupo&id_curso_abierto=$idCursoAbierto" onClick="return confirm('¿Está seguro de remover al grupo este curso?. El progreso de los alumnos y la relaciones de tutores con el grupo tambien seran eliminados.');"></a>
                    </td>
                    <td>$res->id_grupo</td>
                    <td>$res->nombre_grupo</td>
                    <td>$res->clave</td>
                    <td>$nombreInstiucion</td>
                    <td>$nombreEscuela</td>
                    <td>$nombreEmpresa</td>
               </tr> 
                
TABLA;
        }
    }
}

/**
 * Funcion que recibe el id_rel_curso_grupo y borra las tablas 
 * que tienen dependencias
 * @param type $idRelacion
 * @return boolean
 */
function borrarRelacionGrupoCursoAbierto($idRelacion) {

    //Borra primero la relacion del tutor dentro del grupo que se va a eliminar
    $query = new Query("SG");
    if ($query->delete("rel_curso_tutor", "id_rel_curso_grupo=$idRelacion")) {
        //Borra el progreso de los alumnos del grupo dentro del curso abierto
        if ($query->delete("progreso_alumno", "id_rel_curso_grupo=$idRelacion")) {
            //Borra la relacion del grupo en el curso
            if ($query->delete("rel_curso_grupo", "id_rel_curso_grupo=$idRelacion")) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Funcion que valida que haya grupos en un curso
 * @param type $idCursoAbierto
 * @return boolean
 */
function validarGruposCurso($idCursoAbierto) {
    $query = new Query("SG");
    $query->sql = <<<SQL
            SELECT id_rel_curso_grupo
            FROM rel_curso_grupo
            WHERE id_curso_abierto = $idCursoAbierto            
SQL;

    $registros = $query->select("obj");

    if ($registros) {
        return true;
    } else {
        return false;
    }
}

/**
 * Funcion que retorna las relaciones con cursos abiertos de
 * un grupo
 * @param type $idGrupo
 * @return null
 */
function consultaCursosGrupo($idGrupo) {
    $query = new Query("SG");
    $query->sql = <<<SQL
            SELECT id_rel_curso_grupo, id_curso_abierto, id_grupo
            FROM rel_curso_grupo 
            WHERE id_grupo = $idGrupo
SQL;

    $resultados = $query->select("obj");

    if ($resultados) {
        return $resultados;
    } else {
        return false;
    }
}

/**
 * Funcion que verifica si un alumno tiene progreso en un curso
 * Si no tiene retorna un false
 * @param type $idRelCursoGrupo
 * @param type $idAlumno
 * @return boolean
 */
function verificaProgreso($idRelCursoGrupo, $idAlumno) {
    $query = new Query("SG");
    $query->sql = <<<SQL
            SELECT id_progreso_alumno
            FROM progreso_alumno
            WHERE id_rel_curso_grupo = $idRelCursoGrupo
              and id_alumno = $idAlumno
SQL;

    $resultados = $query->select("obj");

    if ($resultados) {
        return true;
    } else {
        return false;
    }
}

/**
 * Funcion que borra prograso en de un alumno en un curso/grupo
 * @param type $idRelCursoGrupo
 * @param type $idAlumno
 * @return boolean
 */
function borrarProgreso($idRelCursoGrupo, $idAlumno) {
    $query = new Query("SG");
    if ($query->delete("progreso_alumno", "id_rel_curso_grupo=$idRelCursoGrupo and id_alumno = $idAlumno")) {
        return true;
    } else {
        return false;
    }
}

/**
 * Funcion que hace un update para cambiar los status
 * de ciertos alumnos dnetro de cierta relacion curso grupo
 * haciendo el status 0
 * @param type $idRelCursoGrupo
 */
function deshabilitarProgresoAlumnos($idRelCursoGrupo, $idAlumno = NULL) {
    $query = new Query("SG");
    if ($idAlumno != NULL) {
        $query->update("UPDATE progreso_alumno set status=0 where id_rel_curso_grupo = $idRelCursoGrupo and id_alumno = $idAlumno");
    } else {
        $query->update("UPDATE progreso_alumno set status=0 where id_rel_curso_grupo = $idRelCursoGrupo");
    }
}

/**
 * Funcion que hace un update para cambiar los 
 * status a activos en el progreso de un alumno
 * dentro de una relacion curso grupo
 * @param type $idRelCursoGrupo
 * @param type $idAlumno
 */
function habilitarProgresoAlumnos($idRelCursoGrupo, $idAlumno) {
    $query = new Query("SG");
    if ($query->update("UPDATE progreso_alumno set status=1 where id_rel_curso_grupo = $idRelCursoGrupo and id_alumno=$idAlumno"))
        ;
}

/**
 * CHANGE CONTROL 0.99.7
 * FECHA: 8 DE ENERO DEL 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJEVITO: ENROLAR USUARIOS EN CURSO/GRUPO DE MOODLE
 */

/**
 * Función que consulta el id del curso de moodle por el id del curso abierto
 * @param type $idCursoAbierto
 * @return null
 */
function consultaIdMoodle($idCursoAbierto) {
    $query = new Query("SG");
    $query->sql = <<<sql
            SELECT id_curso_moodle FROM cursos c, cursos_abiertos ca WHERE ca.id_curso = c.id_curso and ca.id_curso_abierto = $idCursoAbierto
sql;
    $resultSet = $query->select();

    if ($resultSet) {
        foreach ($resultSet as $result) {
            return $result->id_curso_moodle;
        }
    } else {
        return null;
    }
}

/**
 * Funcion que enrola alumnos y/o tutores a un grupo
 * DEPRECATED
 * @param type $idCursoAbierto
 * @param type $ids
 * @param type $idGrupo
 * @param type $rol
 * @param type $idGrupoMoodle
 * @return string
 */
function enrolarMoodle($idCursoAbierto, $ids, $idGrupo, $rol, $idGrupoMoodle = NULL) {
    imprimeConsola("Enrolar Moodle");
    if (($idRol = get_assignable_roles($rol)) !== false) {
        imprimeConsola("Creando cliente soap");
        try {
            $client = new SoapClient(SERVER_URL);
            imprimeConsola("Cliente soap creado inicialmente");
        } catch (SoapFault $e) {
            return "Error al crear cliente soap: $e";
        }
        imprimeConsola("Cliente soap creado");

        //Inscribir al curso
        $functionname = 'enrol_manual_enrol_userss';
        $enroles = array();
        imprimeConsola("Inscribiendo alumnos al curso");
        foreach ($ids as $id) {
            if ($rol == ROL_ALUMNO) {
                $correo = obtenerCorreoUsuarioAlumno($id)->correo;
            } else if ($rol == ROL_COORDINADOR || $rol == ROL_TUTOR_JUNIOR || $rol == ROL_TUTOR_SENIOR) {
                $correo = obtenerCorreoUsuarioTutor($id)->correo;
            }
            imprimeConsola("correo:$correo");
            //Insertar alumno en el grupo
            $enrol = new stdClass();
            $enrol->roleid = intval($idRol);
            $enrol->courseid = intval(consultaIdMoodle($idCursoAbierto));
            $enrol->userid = intval(get_moodle_user($correo));
            imprimeConsola("moodle user id:" . $enrol->userid);

            $params = array($enrol);
            array_push($enroles, $params);
        }
        imprimeConsola("Se preparo el objeto de enrolamiento");
        try {
            imprimeConsola("Enroles");
            var_dump($enroles);
            $resp = $client->__soapCall($functionname, $enroles);
            var_dump($resp);
        } catch (exception $e) {
            echo "<br>Error al inscribir alumnos en curso de moodle<br>";
            var_dump($e);
            echo($e->xdebug_message);
            return "Error al inscribir alumnos en curso de moodle";
        }

        //Crear grupo
        if (!isset($idGrupoMoodle)) {
            //Info de grupo
            $grupo = consultaUnGrupo($idGrupo);
            if (($idGrupoTest = verificaGrupoMoodle($grupo->nombre_grupo)) == NULL) {
                $functionname = 'core_group_create_groups';
                $group = new stdClass();
                $group->courseid = intval(consultaIdMoodle($idCursoAbierto));
                $group->name = $grupo->nombre_grupo;
                $group->description = $grupo->clave;

                $params = array($group);

                try {
                    $resp = $client->__soapCall($functionname, array($params));
                    $idGrupoMoodle = $resp[0]["id"];
                } catch (exception $e) {
                    echo "<br>Error al crear grupo en moodle<br>";
                    var_dump($e);
                    echo($e->xdebug_message);
                    return "Error al icrear grupo de moodle";
                }
            } else {
                $idGrupoMoodle = $idGrupoTest;
            }
        }
        //Insertar alumnos en el grupo
        unset($params);
        $groupadds = array();
        $functionname = 'core_group_add_group_members';

        foreach ($ids as $id) {
            if ($rol == ROL_ALUMNO) {
                $correo = obtenerCorreoUsuarioAlumno($id)->correo;
            } else if ($rol == ROL_COORDINADOR || $rol == ROL_TUTOR_JUNIOR || $rol == ROL_TUTOR_SENIOR) {
                $correo = obtenerCorreoUsuarioTutor($id)->correo;
            }
            //Insertar alumno en el grupo
            $groupadd = new stdClass();
            $groupadd->groupid = $idGrupoMoodle;
            $groupadd->userid = get_moodle_user($correo);

            $params = array($groupadd);
            array_push($groupadds, $params);
        }

        try {
            $resp = $client->__soapCall($functionname, $groupadds);
        } catch (exception $e) {
            echo "<br>Error al agregar alumnos en grupo de moodle<br>";
            var_dump($e);
            echo($e->xdebug_message);
            return "Error al agregar alumnos en grupo de moodle";
        }
    } else {
        return("<br>No existe el rol en Moodle, solo se pudo crear el usuario<br>");
    }

    return $idGrupoMoodle;
}

/**
 * Funcion que crea un grupo en moodle y retorna informacion para 
 * inscribir usuarios dentro del mismo
 * @param type $idCursoAbierto
 * @param type $idGrupo
 * @param type $ids
 * @param type $rol
 * @param type $idGrupoMoodle
 * @return string
 */
function crearGrupoMoodle($idCursoAbierto, $idGrupo, $ids, $rol, $idGrupoMoodle = NULL) {
    $courseid = consultaIdMoodle($idCursoAbierto);
    
    //Crear grupo
    if (!isset($idGrupoMoodle)) {
        //Info de grupo
        $grupo = consultaUnGrupo($idGrupo);
        
        if (($idGrupoTest = verificaGrupoMoodle($grupo->nombre_grupo, $courseid)) == NULL) {
            $functionname = 'core_group_create_groups';
            $group = new stdClass();
            $group->courseid = $courseid;
            $group->name = $grupo->nombre_grupo;
            $group->description = $grupo->clave;

            $params = array($group);

            try {
                $client = new SoapClient(SERVER_URL);                
            } catch (SoapFault $e) {
                return "Error al crear cliente soap: $e";
            }

            try {
                $resp = $client->__soapCall($functionname, array($params));
                $idGrupoMoodle = $resp[0]["id"];                
            } catch (exception $e) {
                echo "<br>Error al crear grupo en moodle<br>";
                var_dump($e);
                echo($e->xdebug_message);
                return "Error al icrear grupo de moodle";
            }
        } else {
            $idGrupoMoodle = $idGrupoTest;
        }
    }
    
    //id del rol en moodle
    $idRol = get_assignable_roles($rol);
    
    //id de enrolamiento en moodle    
    $enrolid = get_enrol_id($courseid);

    //Ids de usuarios
    $userid = "";
    foreach ($ids as $id) {
        if ($rol == ROL_ALUMNO) {
            $correo = obtenerCorreoUsuarioAlumno($id)->correo;
        } else if ($rol == ROL_COORDINADOR || $rol == ROL_TUTOR_JUNIOR || $rol == ROL_TUTOR_SENIOR) {
            $correo = obtenerCorreoUsuarioTutor($id)->correo;
        }
        //Concatenacion de user id's
        if($correo != ""){
            $moodle_user =get_moodle_user($correo);
            if($moodle_user != ""){
                $userid .=  "$moodle_user,";
            }
        }        
    }

    return array(
        "groupid" => $idGrupoMoodle,
        "enrolid" => $enrolid,
        "rolid" => $idRol,
        "userid" => $userid);
}

/**
 * Funcion que consulta un grupo por su id
 * @param type $idGrupo
 * @return null
 */
function consultaUnGrupo($idGrupo) {
    $query = new Query("SG");
    $query->sql = <<<sql
            SELECT * FROM grupo WHERE id_grupo = $idGrupo
sql;
    $resultSet = $query->select();

    if ($resultSet) {
        foreach ($resultSet as $result) {
            return $result;
        }
    } else {
        return null;
    }
}

/**
 * Consulta id del grupo en moodle
 * @param type $idRelCursoGrupo
 * @return null
 */
function get_id_moodle_group($idRelCursoGrupo) {
    $query = new Query("SG");
    $query->sql = <<<sql
            SELECT id_grupo_moodle FROM rel_curso_grupo WHERE id_rel_curso_grupo = $idRelCursoGrupo
sql;
    $resultSet = $query->select();

    if ($resultSet) {
        foreach ($resultSet as $result) {
            return $result->id_grupo_moodle;
        }
    } else {
        return null;
    }
}

/**
 * Funcion que verifica si el grupo ya esta agregado
 * Si esta agregado retorna su id
 * Sino esta agregado returna un nulo
 * @param type $nombre
 * @return null
 */
function verificaGrupoMoodle($nombre, $courseid) {
    $query = new Query("MOD");
    $query->sql = <<<sql
            SELECT id FROM mdl_groups WHERE name = '$nombre' and courseid = $courseid
sql;
    $resultSet = $query->select();

    if ($resultSet) {
        foreach ($resultSet as $result) {
            return $result->id;
        }
    } else {
        return null;
    }
}

/**
 * Funcion que consulta la relacion curso/enrolamiento en moodle y retorna el id
 * de enrolamiento, si retorna un nulo es por que no encontro nada
 * @param type $courseid
 * @return null
 */
function get_enrol_id($courseid) {
    $query = new Query("MOD");
    $query->sql = <<<sql
            SELECT id FROM mdl_enrol WHERE courseid = $courseid and enrol = 'manual'
sql;
    $resultSet = $query->select();

    if ($resultSet) {
        foreach ($resultSet as $result) {
            return $result->id;
        }
    } else {
        return null;
    }
}

/**
 * Devuelve true si una unidad tiene series
 * @param type $idUnidad
 * @return boolean
 */
function unidadEnAlgunaSerie($idUnidad) {

    $query = new Query("SG");
    $query->sql = "select id_serie_aer from serie_aer where id_unidad = $idUnidad";
    $series = $query->select("obj");
    if ($query->numRegistros() > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * CHANGE CONTROL 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * FECHA DE MODIFICACIÓN: 21 DE MAYO DE 2014
 * OBJETIVO: CAMBIOS ESTETICOS
 */

/**
 * Devuelve los cursos Moodle para el linkeo de series API
 * @param type $idCursoSG
 */
function cursosMoodleParaSerie($idCursoSG) {
//Crea objeto para realizar consultas al sistema de gestion
    $query = new Query("SG");
//Query a Moodle
    $query->sql = "SELECT * from unidades where id_curso=$idCursoSG and status = 1 and url_unidad is not null";
    $resultado = $query->select("obj");

    if ($resultado) {
        foreach ($resultado as $topico) {
            if (!unidadEnAlgunaSerie($topico->id_unidad))
                $boton = '<a class="btn btn-info" data-toggle="modal" href="#verModalLinkeo" onclick="llenaModalLinkeo(\'' . $topico->id_unidad . '\');">Agregar informaci&oacute;n de series';
            else
                $boton = '<a class="btn btn-success" data-toggle="modal" href="#verModalLinkeo" onclick="llenaModalLinkeoEditar(\'' . $topico->id_unidad . '\');">Editar informaci&oacute;n de series';
            echo <<<HTML
            <div class="well">
                <h5>$topico->nombre_unidad </h5> $boton </a> 
            </div>
HTML;
        }
    } else {
        echo "<h3 class='text-warning'>No hay bloques activos en este curso.</h3>";
    }
}

/**
 * Imprime los options de los tipos de elementos
 * @return string
 */
function comboTipoElemento() {

    $query = new Query("SG");

    $query->sql = "SELECT * from tipo_elemento where status = 1";
    $tiposE = $query->select("obj");
    $var = "";
    if ($tiposE) {
        foreach ($tiposE as $te) {
            $var = $var . "<option value = '$te->id_tipo_elemento'>$te->nombre_tipo</option>";
//            echo<<<hab
//        <option value = "$te->id_tipo_elemento">$te->nombre_tipo</option>
//hab;
        }
    } else {
        echo <<<html
            <option value="">No hay tipos de elementos</option>
html;
    }
    return $var;
}
?>
