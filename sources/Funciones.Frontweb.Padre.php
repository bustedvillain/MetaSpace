<?php

/**
 * Función que imprime los datos de mi perfil del alumno
 */
function infoMiperfilPadre($idPadre) {
    $query = new Query("SG");
    $sql = <<<sql
        select distinct(p.id_padre) as "idPadre", p.id_datos_personales as "idDatosPersonales", nombre_pila || ' ' || primer_apellido || ' ' || segundo_apellido as "nombreCompleto", 
            fecha_nacimiento,  to_char(now(), 'YYYY') as "hoy", to_char(fecha_nacimiento, 'YYYY') as "fenac", 
            url_foto as "ruta", nombre_escuela as "escuela", g.nombre_grupo as "grupo",
            EXTRACT(YEAR FROM age(timestamp 'now()', (fecha_nacimiento) )) as anios
        from padres p
        left join alumnos a
		on a.id_padre = p.id_padre
        left join datos_personales d
                on d.id_datos_personales = p.id_datos_personales
        left join grupo_alumno ga
                on ga.id_alumno = a.id_alumno
        left join grupo g
                on g.id_grupo = ga.id_grupo
        left join escuelas e
                on g.id_escuela = e.id_escuela
        where p.id_padre = $idPadre
sql;
//echo 'idportabla='.obtenerIDTabla();
    $query->sql = $sql;
    $resultado = $query->select("obj");
    $padre = null;
    if ($resultado) {
        foreach ($resultado as $r) {
            $padre = $r;
            break;
        }
    }
    $escuelas = escuelasVinculadasPadre($idPadre);
    $grupos = gruposVinculadasPadre($idPadre);
    $ruta = rutaFotoDeSesion();
    $edad = $padre->hoy - $padre->fenac;
    $html = <<<html
        <div class="contenido">
                <div id="foto_mi_perfil"><img src="$ruta" width="142" height="145" /></div>
          <div id="datos_mi_perfil">
          Nombre:<br/>
          <strong>$padre->nombreCompleto</strong><br/>
          Edad: <strong>$padre->anios a&ntilde;os</strong><br/>
          Escuela: <strong>$escuelas</strong><br/><br/>
          Grupo: <strong>$grupos</strong>
          </div>
        </div>
html;

    echo $html;
}
/**
 * Devuelve cadena para un where de los grupos vinculados a un padre
 * @param type $idPadre
 * @return type
 */
function gruposVinculadasPadre($idPadre){
    $hijos = hijosDePadre($idPadre);
    $cadena = "";
    $arrayEscuelas = array();
    foreach($hijos as $h){
        if(!in_array($h->id_grupo, $arrayEscuelas) &&  $h->id_grupo."" != ""){
            $cadena .=  $h->nombre_grupo. ", ";
            array_push($arrayEscuelas, $h->id_grupo);
        }
    }
    if($cadena != ""){
        $cadena = substr($cadena, 0, strlen($cadena)-2);
    }
    return $cadena;
}
/**
 * Devuelve cadena para un where de las escuelas vinculados a un padre
 * @param type $idPadre
 * @return type
 */
function escuelasVinculadasPadre($idPadre){
    $hijos = hijosDePadre($idPadre);
    $cadena = "";
    $arrayEscuelas = array();
    foreach($hijos as $h){
        if(!in_array($h->id_escuela, $arrayEscuelas) &&  $h->id_escuela."" != ""){
            $cadena .= $h->nombre_escuela. ", ";
            array_push($arrayEscuelas, $h->id_escuela);
        }
    }
    if($cadena != ""){
        $cadena = substr($cadena, 0, strlen($cadena)-2);
    }
    return $cadena;
}
/**
 * Función que imprime el listado de hijos de un padre
 * @param type $idPadre
 * @return null
 */
function generarListadoHijos($idPadre) {
    if (!is_numeric($idPadre))
        return NULL;

    $sql = new Query('SG');
    $sql->sql = "SELECT al.id_alumno as id, nombre_pila,
                    dp.id_datos_personales as idpersonales
                    FROM alumnos al
                    JOIN datos_personales dp
                    ON  al.id_datos_personales = dp.id_datos_personales 
                    WHERE id_padre = " . $idPadre . "
                    AND status = 1
                    ORDER BY nombre_pila";

    $resultadoConsulta = $sql->select();
    $HTML = "";
    if ($resultadoConsulta) {
        foreach ($resultadoConsulta as $hijo) {
            $urlFOTO = rutaFotoDeIdDatosPersonales($hijo->idpersonales);
            $HTML .= '<div class="hijo">
                <a href="familia_cursos.php?idAlumno=' . $hijo->id . '&idDatosPersonales=' . $hijo->idpersonales . '">
                  <div class="hijo_foto"><img src="' . $urlFOTO . '" width="94" height="94" /></div>
                    <div class="hijo_nombre">' . $hijo->nombre_pila . '</div>
                    </a>
                </div>';
        }
    }
    echo $HTML;
}

/**
 * Retorna los hijos del padre que se coloque
 * @param type $idPadre
 * @return null
 */
function hijosDePadre($idPadre) {
    if (!is_numeric($idPadre))
        return NULL;

    $sql = new Query('SG');
    $sql->sql = "SELECT al.id_alumno as id, nombre_pila,
                    dp.id_datos_personales as idpersonales, e.id_escuela, e.nombre_escuela,
                    g.nombre_grupo, g.id_grupo
                    FROM alumnos al
                    JOIN datos_personales dp
                    ON  al.id_datos_personales = dp.id_datos_personales 
                    left join escuelas e
                        on e.id_escuela = al.id_escuela
                    left join grupo_alumno ga
                        on ga.id_alumno = al.id_alumno
                    left join grupo g
                        on g.id_grupo = ga.id_grupo
                    WHERE id_padre = " . $idPadre . "
                    AND al.status = 1
                    ORDER BY nombre_pila";

    $hijos = $sql->select();
    return $hijos;
}

/**
 * Funcion que imprime los options de los hijos de un padre
 * @param type $idPadre
 */
function optionsDeHijosDelPadre($idPadre) {
    $hijos = hijosDePadre($idPadre);
    if (!empty($hijos)) {
        foreach ($hijos as $h) {
            echo <<<html
            <option value = '$h->idpersonales'>$h->nombre_pila</option>
html;
        }
    } else {
        echo "<option>Sin hijos disponibles</option>";
    }
}

/**
 * Funcion que imprime los options de los hijos de un padre
 * @param type $idPadre
 */
function optionsDeHijosDelPadre2($idPadre) {
    $hijos = hijosDePadre($idPadre);
    if (!empty($hijos)) {
        foreach ($hijos as $h) {
            echo <<<html
            <option value = '$h->id'>$h->nombre_pila</option>
html;
        }
    } else {
        echo "<option>Sin hijos disponibles</option>";
    }
}

/**
 * Functión que imprime los mensajes del padre
 * @param type $idPadre
 * @param type $pagina
 * @param type $tipo
 */
function mensajeAlPadreDeHijos($idPadre, $pagina, $tipo) {
    $hijos = hijosDePadre($idPadre);
    $arrIds = array();
    //llenamos los hijos
    foreach ($hijos as $idH) {
        if (!in_array($idH->idpersonales, $arrIds) && $idH->idpersonales != "") {
            array_push($arrIds, $idH->idpersonales);
            //llenamos los tutores
            $arrTutores = tutoresDeAlumno($idH->id);
            foreach ($arrTutores as $t){
                if(!in_array($t, $arrIds) && $t!=""){
                    array_push($arrIds, $t);
                }
            }
        }
    }
    
    
    //array_push de los tutores tambien
    if ($tipo == "recibidos") {
        $mensajes = consultarMensajes($arrIds, obtenerIdDatosPersonales(), 10, $pagina);
    } else {//eviados
        $mensajes = consultarMensajes(obtenerIdDatosPersonales(), $arrIds, 10, $pagina);
    }
    if (empty($mensajes)) {
        echo 'No hay mensajes disponibles';
    } else {
        foreach ($mensajes as $m) {
            if ($tipo == "recibidos") {
                $persona = $m->nombre_remitente;
            } else {//eviados
                $persona = $m->nombre_destinatario;
            }
//        $padre = 
            $mensajeC = substr($m->mensaje, 0, 70);
            echo <<<html
        <li><a href="#"><div class="msg_head"><span class="alignLeft">$persona</span><span class="alignRight">$m->fecha_envio</span></div>
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
 * Hace la paginacion del tutor
 * @param type $idTutor
 * @param type $tipo
 */
function paginacionTutorDelPadre($idPadre, $tipo) {

    $hijos = hijosDePadre($idPadre);
    $arrIds = array();
//    foreach ($hijos as $idH) {
//        array_push($arrIds, $idH->idpersonales);
//    }
//    //llenamos los hijos
    foreach ($hijos as $idH) {
        if (!in_array($idH->idpersonales, $arrIds) && $idH->idpersonales != "") {
            array_push($arrIds, $idH->idpersonales);
            //llenamos los tutores
            $arrTutores = tutoresDeAlumno($idH->id);
            foreach ($arrTutores as $t){
                if(!in_array($t, $arrIds) && $t!=""){
                    array_push($arrIds, $t);
                }
            }
        }
    }
    //array_push de los tutores tambien
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
 * Devuelve los tutores que están relacionados con un alumno
 * @param type $idAlumno
 * @return type
 */
function tutoresDeAlumnoPadre($idAlumno) {
    $query = new Query("SG");
    $query->sql = <<<sql
        select distinct(t.id_tutor) as "idTutor", r.nombre as "rol", t.id_datos_personales as "idDatosPersonales", nombre_pila || ' ' || primer_apellido || ' ' || segundo_apellido as "nombreCompleto"
                , nombre_pila || ' ' || primer_apellido || ' ' || segundo_apellido || ' Tutor ' || r.nombre  as "nombreConRol"
        from grupo_alumno ga
        join rel_curso_grupo rcg
                on ga.id_grupo = rcg.id_grupo
        join rel_curso_tutor rct
                on rct.id_rel_curso_grupo = rcg.id_rel_curso_grupo
        join tutor t
                on t.id_tutor = rct.id_tutor
        join datos_personales d
                on d.id_datos_personales = t.id_datos_personales
        join rol_tutor r
                on r.id_rol_tutor = t.id_rol_tutor
        where ga.id_alumno = $idAlumno
        order by "nombreCompleto"
sql;
    $resultado = $query->select("obj");
    return $resultado;
}
/**
 * Devuelve en json los tutores relacionados a un padre
 * @param type $idAlumno
 * @return type
 */
function optionsComboPadreDeTutoresJSON($idAlumno) {
    $tutores = tutoresDeAlumnoPadre($idAlumno);
    return json_encode($tutores);
}

/**
 * Imprime los options de los tutores de un alumno
 * @param type $idAlumno
 */
function optionsComboPadreDeTutores($idAlumno) {
    $tutores = tutoresDeAlumno($idAlumno);
    foreach ($tutores as $t) {
        echo <<<htm
        <option value=$t->idDatosPersonales>$t->nombreCompleto Tutor $t->rol</option>
htm;
    }
}
?>