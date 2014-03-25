<?php

/**
 * Obtiene un objeto de consulta de los tutores 
 * @param type $idTutor
 * @return type
 */
function tutoresDeT($idTutor) {
    switch (obtenerTipo()) {
        case "Coordinador":
            $tutores = tutoresDeCoordinador($idTutor);
            break;
        case "Senior":
            $tutores = tutoresDeSeniorJunior($idTutor);
            break;
        case "Junior":
            $tutores = tutoresDeSeniorJunior($idTutor);
            break;
    }
    return $tutores;
}

/**
 * Imprime los options de los tutores de un tutor
 * @param type $idTutor
 */
function tutoresDeTutor($idTutor) {
    $tutores = null;
//    echo 'idtutor' . $idTutor;

    switch (obtenerTipo()) {
        case "Coordinador":
            $tutores = tutoresDeCoordinador($idTutor);
            break;
        case "Senior":
            $tutores = tutoresDeSeniorJunior($idTutor);
            break;
        case "Junior":
            $tutores = tutoresDeSeniorJunior($idTutor);
            break;
    }
//    var_dump($tutores);
    $arrIds = array();
    if (empty($tutores)) {
        echo "No hay tutores relacionados";
    } else {
        foreach ($tutores as $t) {
            //imprimo el combo
            if ($t->idTutor != obtenerIDTabla() and !in_array($t->idTutor, $arrIds)) {
                echo <<<op
            <option value="$t->idDatosPersonales">$t->nombreCompleto Tutor $t->rol</option>
op;
                array_push($arrIds, $t->idTutor);
            }
        }
    }
}

/**
 * Devuelve un arreglo con los IdRelCursoGrupodelTutor
 * @param type $idTutor
 * @return array
 */
function idRelCursoTutorDeTutor($idTutor) {
    $arrIds = array();
    $query = new Query("SG");
//    echo 'orasi' . $idTutor;
    $sql = <<<sql
        select distinct(id_rel_curso_grupo) as "idRelCursoGrupo"
        from rel_curso_tutor 
        where id_tutor = $idTutor
sql;
    $query->sql = $sql;
    $resultado = $query->select("obj");
    foreach ($resultado as $r) {
        array_push($arrIds, $r->idRelCursoGrupo);
    }
    return $arrIds;
}

/**
 * Devuelve objeto de query de los tutores relacionados a un coordinador
 * @param type $idTutor
 * @return type
 */
function tutoresDeCoordinador($idTutor) {
    $arrRelCurso = idRelCursoTutorDeTutor($idTutor);
//    echo 'idRelCurso' . var_dump($arrRelCurso);
    if (!empty($arrRelCurso)) {
        $cadenaIn = "";
        foreach ($arrRelCurso as $a) {
            $cadenaIn = $cadenaIn . $a . ",";
        }
        $cadenaIn = substr($cadenaIn, 0, strlen($cadenaIn) - 1);
        $query = new Query("SG");
        $sql = <<<sql
            select distinct(rct.id_tutor) as "idTutor", r.nombre as "rol", rct.id_rel_curso_grupo, d.id_datos_personales as "idDatosPersonales", d.nombre_pila || ' ' || d.primer_apellido || ' ' || d.segundo_apellido as "nombreCompleto"
            from rel_curso_tutor rct
            join tutor t
                    on t.id_tutor = rct.id_tutor
            join datos_personales d
                    on d.id_datos_personales = t.id_datos_personales
            join rol_tutor r
                    on r.id_rol_tutor = t.id_rol_tutor
            where (lower(r.nombre) like 'senior' or lower(r.nombre) like 'junior') 
                    and rct.id_rel_curso_grupo in ($cadenaIn)
sql;
        $query->sql = $sql;
        $resultado = $query->select("obj");
        return $resultado;
    }
}

/**
 * Devuelve objeto de consulta con los tutores asigandos a un senior o junior
 * @param type $idTutor
 * @return type
 */
function tutoresDeSeniorJunior($idTutor) {
    $arrRelCurso = idRelCursoTutorDeTutor($idTutor);
    if (!empty($arrRelCurso)) {
        $cadenaIn = "";
        foreach ($arrRelCurso as $a) {
            $cadenaIn = $cadenaIn . $a . ",";
        }
        $cadenaIn = substr($cadenaIn, 0, strlen($cadenaIn - 1));
        $query = new Query("SG");
        $sql = <<<sql
            select distinct(rct.id_tutor) as "idTutor", r.nombre as "rol", rct.id_rel_curso_grupo, d.id_datos_personales as "idDatosPersonales", d.nombre_pila || ' ' || d.primer_apellido || ' ' || d.segundo_apellido as "nombreCompleto"
            from rel_curso_tutor rct
            join tutor t
                    on t.id_tutor = rct.id_tutor
            join datos_personales d
                    on d.id_datos_personales = t.id_datos_personales
            join rol_tutor r
                    on r.id_rol_tutor = t.id_rol_tutor
            where rct.id_rel_curso_grupo in ($cadenaIn)
sql;
        $query->sql = $sql;
        $resultado = $query->select("obj");
        return $resultado;
    }
}

/**
 * Función que imprime los datos de mi perfil del alumno
 */
function infoMiperfilTutor($idTutor) {
    $query = new Query("SG");
    $sql = <<<sql
        select distinct(t.id_tutor) as "idTutor", t.id_datos_personales as "idDatosPersonales", nombre_pila || ' ' || primer_apellido || ' ' || segundo_apellido as "nombreCompleto", 
            fecha_nacimiento,  to_char(now(), 'YYYY') as "hoy", to_char(fecha_nacimiento, 'YYYY') as "fenac", 
            url_foto as "ruta", nombre_escuela as "escuela", g.nombre_grupo as "grupo",
            EXTRACT(YEAR FROM age(timestamp 'now()', (fecha_nacimiento) )) as anios
        from tutor t
        left join datos_personales d
                on d.id_datos_personales = t.id_datos_personales
        left join rel_curso_tutor rct
                on rct.id_tutor = t.id_tutor
	left join rel_curso_grupo rcg
		on rcg.id_rel_curso_grupo = rct.id_rel_curso_grupo
        left join grupo g
                on g.id_grupo = rcg.id_grupo
        left join escuelas e
                on g.id_escuela = e.id_escuela
        where t.id_tutor = $idTutor
sql;
//echo 'idportabla='.obtenerIDTabla();
    $query->sql = $sql;
    $resultado = $query->select("obj");
    $tutor = null;
    if ($resultado) {
        foreach ($resultado as $r) {
            $tutor = $r;
            break;
        }
    }

    $escuelas = stringDeEscuelasTutor($idTutor);
    $grupos = stringDeGruposTutor($idTutor);
    $ruta = rutaFotoDeSesion();
    $edad = $tutor->hoy - $tutor->fenac;
    $html = <<<html
        <div class="contenido">
                <div id="foto_mi_perfil"><img src="$ruta" width="142" height="145" /></div>
          <div id="datos_mi_perfil">
          Nombre:<br/>
          <strong>$tutor->nombreCompleto</strong><br/>
          Edad: <strong>$tutor->anios a&ntilde;os</strong><br/>
          Escuela: <strong>$escuelas</strong><br/><br/>
          Grupo: <strong>$grupos</strong>
          </div>
        </div>
html;

    echo $html;
}

/**
 * Imprime los mensajes recibidos a un tutor
 * @param type $idTutor
 * @param type $pagina
 * @param type $tipo
 */
function mensajesAlTutorDeTutores($idTutor, $pagina, $tipo) {
//    echo '---' . $idTutor;
    $arrTutores = tutoresDeT($idTutor);
    $arrIds = array();
//    echo '---'.var_dump($arrTutores);
    //Llenando los ids de tutores
    foreach ($arrTutores as $a) {
        if ($a->idTutor != obtenerIDTabla()) {
            if (!in_array($a->idDatosPersonales, $arrIds) && $a->idDatosPersonales != "") {
                array_push($arrIds, $a->idDatosPersonales);
            }
        }
    }
    //llenando los ids de alumnos y padres
//    tutoresDeAlumno($idAlumno);
//    tutoresDeAlumnoPadre($idAlumno);
    $grupos = obtenerGruposDeTutor($idTutor);
    foreach ($grupos as $g) {
        $alumnos = obtenerAlumnos($g->id_grupo);
        foreach ($alumnos as $a) {
            if (!in_array($a->id, $arrIds) && $a->id != "") {
                array_push($arrIds, $a->id);
            }
            if (!in_array(padreDeAlumno($a->id), $arrIds) && padreDeAlumno($a->id) != "") {
                array_push($arrIds, padreDeAlumno($a->id));
            }
        }
    }

//    echo '---' . var_dump($arrIds) . 'mio' . obtenerIdDatosPersonales();
    if ($tipo == "recibidos") {
        $mensajes = consultarMensajes($arrIds, obtenerIdDatosPersonales(), 10, $pagina);
    } else {//eviados
        $mensajes = consultarMensajes(obtenerIdDatosPersonales(), $arrIds, 10, $pagina);
    }

//    echo 'acaaaa'.var_dump($mensajes);
    if (empty($mensajes)) {
        echo 'No hay mensajes disponibles';
    } else {
        foreach ($mensajes as $m) {
//        $padre = 
            if ($tipo == "recibidos") {
                $persona = $m->nombre_remitente;
                if (devuelveTipoUsuario($m->id_remitente) == "alumno") {
                    $grupo = obtenerGrupoAlumno($m->id_remitente);
                } elseif (devuelveTipoUsuario($m->id_remitente) == "padre") {
                    $idDatosPadre = idPadreDeIdDatosPersonales($m->id_remitente);
                    $grupo = @gruposVinculadasPadre($idDatosPadre) . " Padre";
//                    $grupo = obtenerGrupoAlumno($m->id_remitente);
                }
            } else {//eviados
                $persona = $m->nombre_destinatario;
                if (devuelveTipoUsuario($m->id_destinatario) == "alumno") {
                    $grupo = obtenerGrupoAlumno($m->id_destinatario);
                } elseif (devuelveTipoUsuario($m->id_destinatario) == "padre") {
                    $idDatosPadre = idPadreDeIdDatosPersonales($m->id_destinatario);
                    $grupo = @gruposVinculadasPadre($idDatosPadre) . " Padre";
                }
            }
            $mensajeC = substr($m->mensaje, 0, 70);
            echo <<<html
        <li><a href="#"><div class="msg_head"><span class="alignLeft">$persona - $grupo</span><span class="alignRight">$m->fecha_envio</span></div>
            <span class="alignLeft">$mensajeC</span></a>
            <ul class="msgs">
                <li>
                    <div id="msg_body">                       
                        <div class="msg_text" style="text-align:justify">$m->mensaje.</div>
                        <div>
                            <form id="frm$m->id_mensaje" action="enviaMensaje.php" method="post">
                                <input type="hidden" id="idDestinatario" name="idDestinatario" value="$m->id_remitente"/>
                                <input type="hidden" id="location" name="location" value="mensaje_revisar.php"/>
                                <textarea required="required" id="msg_cuerpo" cols="60" name = "mensaje" rows="3" wrap="soft" placeholder="Mensaje..." style="text-align:justify"></textarea>
                                <span class="horizontal_btn2"> <a onclick="enviaForm('frm$m->id_mensaje')">Responder</a></span>

                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </li>
html;
        }
    }
}

/**
 * Devuelve tipo de usuario
 * @param type $idDatosPersonales
 * @return string
 */
function devuelveTipoUsuario($idDatosPersonales) {
    $query = new Query("SG");
    $query->sql = <<<sql
        select tipo_usuario
        from datos_personales
        where id_datos_personales = $idDatosPersonales
sql;
    $res = $query->select("obj");
    if ($res) {
        foreach ($res as $r) {
            switch ($r->tipo_usuario) {
                case 0:
                    return "alumno";
                    break;
                case 1:
                    return "tutor";
                    break;
                case 2:
                    return "profesor";
                    break;
                case 3:
                    return "padre";
                    break;
                case 4:
                    return "gestor";
                    break;
                case 5:
                    return "admin";
                    break;
            }
        }
    }
}

/**
 * Devuelve tipo de usuario
 * @param type $idDatosPersonales
 * @return string
 */
function idAlumnoDeIdDatosPersonales($idDatosPersonales) {
    $query = new Query("SG");
    $query->sql = <<<sql
        select id_alumno
        from alumnos
        where id_datos_personales = $idDatosPersonales
sql;
    $res = $query->select("obj");
    if ($res) {
        return $res->id_datos_personales;
    }
}

/**
 * Devuelve tipo de usuario
 * @param type $idDatosPersonales
 * @return string
 */
function idPadreDeIdDatosPersonales($idDatosPersonales) {
    $query = new Query("SG");
    $query->sql = <<<sql
        select id_padre
        from padres
        where id_datos_personales = $idDatosPersonales
sql;
    $res = $query->select("obj");
    if ($res) {
        foreach($res as $r){
            return $r->id_padre;
        }
        
    }
}

/**
 * Hace la paginacion del tutor
 * @param type $idTutor
 * @param type $tipo
 */
function paginacionTutorDelTutor($idTutor, $tipo) {

    $arrTutores = tutoresDeT($idTutor);
    $arrIds = array();
//    echo '---'.var_dump($arrTutores);
    foreach ($arrTutores as $a) {
        if ($a->idTutor != obtenerIDTabla()) {
            if (!in_array($a->idDatosPersonales, $arrIds) && $a->idDatosPersonales != "") {
                array_push($arrIds, $a->idDatosPersonales);
            }
        }
    }
    //llenando los ids de alumnos y padres
    $grupos = obtenerGruposDeTutor($idTutor);
    foreach ($grupos as $g) {
        $alumnos = obtenerAlumnos($g->id_grupo);
        foreach ($alumnos as $a) {
            if (!in_array($a->id, $arrIds) && $a->id != "") {
                array_push($arrIds, $a->id);
            }
            if (!in_array(padreDeAlumno($a->id), $arrIds) && padreDeAlumno($a->id) != "") {
                array_push($arrIds, padreDeAlumno($a->id));
            }
        }
    }
//    echo'--'.  cantidadPaginas($arrIds, obtenerIdDatosPersonales(), 10);
    if ($tipo == "recibidos") {
        for ($i = 1; $i <= cantidadPaginas($arrIds, obtenerIdDatosPersonales(), 10); $i++) {
//            echo 'aaa';
            echo "<a href='mensaje_revisar.php?tipo=recibidos&pagina=$i'>$i</a> ";
        }
    } else {
        for ($i = 1; $i <= cantidadPaginas(obtenerIdDatosPersonales(), $arrIds, 10); $i++) {
            echo "<a href='mensaje_revisar.php?tipo=enviados&pagina=$i'>$i</a> ";
        }
    }
}

/**
 * Funcion que devuelve los grupos que un tutor tiene asignado
 * @param type $idTutor
 * @return type
 */
function obtenerGruposDeTutor($idTutor) {
    $query = new Query("SG");
    $query->sql = <<<sql
        select distinct(g.id_grupo), g.nombre_grupo
        from rel_curso_grupo rcg
        join rel_curso_tutor rct
                on rcg.id_rel_curso_grupo = rct.id_rel_curso_grupo
        join grupo g
            on g.id_grupo = rcg.id_grupo
        where rct.id_tutor = $idTutor
sql;
    $resultado = $query->select("obj");
    return $resultado;
}

/**
 * Imprimir los options de los grupos asignados a un tutor
 * @param type $idTutor
 */
function imprimeOptionsDeGruposDeTutor($idTutor) {
    $grupos = obtenerGruposDeTutor($idTutor);
//    var_dump($grupos);
    foreach ($grupos as $g) {
        echo "<option value='$g->id_grupo'>$g->nombre_grupo</option>";
    }
}

/**
 * Imprime los lis de los reportes
 */
function imprimeLiDeCursosAbiertos($arrIdCursosAb) {
    foreach ($arrIdCursosAb as $a) {
        $cursoa = consultaCursoAbierto($a);
//        var_dump($cursoa);
        $nombre = $cursoa["nombre_curso_abierto"];
        $id = $cursoa["id_curso_abierto"];
        echo<<<html
        <li><a href="reportes_generar.php?idCursoAbierto=$id">$nombre</a></li>
html;
    }
}

/**
 * Devuelve los alumnos y su info relacionadas a un id de tutor
 * @param type $idTutor
 * @return null
 */
function alumnosDeTutor($idTutor) {
    $query = new Query("SG");
    $query->sql = <<<sql
            select distinct(a.id_alumno), rct.id_tutor, d.nombre_pila, e.nombre_escuela, g.nombre_grupo
        from rel_curso_tutor rct
        left join rel_curso_grupo rcg
                on rcg.id_rel_curso_grupo = rct.id_rel_curso_grupo
        left join grupo g
                on g.id_grupo = rcg.id_grupo
        left join grupo_alumno ga
                on g.id_grupo = ga.id_grupo
        left join alumnos a
                on a.id_alumno = ga.id_alumno
        left join escuelas e
                on e.id_escuela = a.id_escuela
        left join datos_personales d
                on d.id_datos_personales = a.id_datos_personales
        where id_tutor = $idTutor
sql;
    $resultado = $query->select("obj");
    if ($resultado) {
        return $resultado;
    } else {
        return null;
    }
}

/**
 * Función que retorna el string de escuelas de un tutor
 * @param type $idTutor
 * @return type
 */
function stringDeEscuelasTutor($idTutor) {
    $alumnos = alumnosDeTutor($idTutor);
    $string = "";
    $escuelas = array();
    foreach ($alumnos as $a) {
        if (!in_array($a->nombre_escuela, $escuelas)) {
            array_push($escuelas, $a->nombre_escuela);
            $string.= $a->nombre_escuela . ", ";
        }
    }
    return substr($string, 0, strlen($string) - 2);
}

/**
 * Función que retorna el string de grupos de un tutor
 * @param type $idTutor
 * @return type
 */
function stringDeGruposTutor($idTutor) {
    $alumnos = alumnosDeTutor($idTutor);
    $string = "";
    $grupos = array();
    foreach ($alumnos as $a) {
        if (!in_array($a->nombre_grupo, $grupos)) {
            array_push($grupos, $a->nombre_grupo);
            $string.= $a->nombre_grupo . ", ";
        }
    }
    return substr($string, 0, strlen($string) - 2);
}

?>
