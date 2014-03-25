<?php

//Archivo creado para control de cambios #7
/**
 * Funcion que devuelve la foto de acuerdo al id de sesion
 */
function rutaFotoDeSesion() {
    $idDatosPersonales = obtenerIdDatosPersonales();
//    $ruta = BASE_STORAGE . "fotos/" . $idDatosPersonales;
    $ruta = rutaFotoDeIdDatosPersonales($idDatosPersonales);
    return $ruta;
}

/**
 * Funcion que devuelve la foto de un id de datos personales
 * @param type $idDatosPersonales
 * @return string
 */
function rutaFotoDeIdDatosPersonales($idDatosPersonales) {
//    $ruta = BASE_STORAGE . "fotos/" . $idDatosPersonales;
//    return $ruta;

    $query = new Query("SG");
    $query->sql = "SELECT url_foto FROM datos_personales WHERE id_datos_personales = $idDatosPersonales";
    $resultSet = $query->select();

    if ($resultSet) {
        foreach ($resultSet as $result) {
            return $result->url_foto;
        }
    } else {
        return null;
    }
}

/**
 * Funcion que almacena imagen en el storage y 
 * redirecciona de acuerdo a la location que se indique
 * @param type $location
 */
function almacenaInsertaImagen($location = NULL, $idDatosPersonales = NULL) {
    if (!isset($idDatosPersonales)) {
        $idDatosPersonales = obtenerIdDatosPersonales();
    }
    $ruta = guardaImagen($idDatosPersonales);
    $ruta = BASE_STORAGE . "fotos/" . $idDatosPersonales;
    if ($ruta != "") {
        $query = new Query("SG");
        $sql = "UPDATE datos_personales set url_foto = '$ruta' where id_datos_personales= $idDatosPersonales";
        $query->update($sql);
        if (isset($location)) {
            header("Location:$location");
        }
    }
}

/**
 * Función que imprime los datos de mi perfil del alumno
 */
function infoMiperfilAlumno($idAlumno) {
    $query = new Query("SG");
    $sql = <<<sql
        select a.id_alumno as "idAlumno", a.id_datos_personales as "idDatosPersonales", nombre_pila || ' ' || primer_apellido || ' ' || segundo_apellido as "nombreCompleto", 
            fecha_nacimiento,  to_char(now(), 'YYYY') as "hoy", to_char(fecha_nacimiento, 'YYYY') as "fenac", 
            url_foto as "ruta", nombre_escuela as "escuela", g.nombre_grupo as "grupo", to_char(age(now(), fecha_nacimiento), 'YY') edad , age(now(), fecha_nacimiento),
            EXTRACT(YEAR FROM age(timestamp 'now()', (fecha_nacimiento) )) as anios
        from alumnos a
        left join datos_personales d
                on d.id_datos_personales = a.id_datos_personales
        left join grupo_alumno ga
                on ga.id_alumno = a.id_alumno
        left join grupo g
                on g.id_grupo = ga.id_grupo
        left join escuelas e
                on a.id_escuela = e.id_escuela
        where a.id_alumno = $idAlumno
sql;
    $query->sql = $sql;
    $resultado = $query->select("obj");
    $alumno = null;
    if ($resultado) {
        foreach ($resultado as $r) {
            $alumno = $r;
            break;
        }
    }
    $ruta = rutaFotoDeSesion();
//    $edad = $alumno->hoy - $alumno->fenac;
    $edad = intval($alumno->anios);
    $html = <<<html
        <div class="contenido">
                <div id="foto_mi_perfil"><img src="$ruta" width="142" height="145" /></div>
          <div id="datos_mi_perfil">
          Nombre:<br/>
          <strong>$alumno->nombreCompleto</strong><br/>
          Edad: <strong>$edad a&ntilde;os</strong><br/>
          Escuela: <strong>$alumno->escuela</strong><br/><br/>
          Grupo: <strong>$alumno->grupo</strong>
          </div>
        </div>
            
        <script type="text/javascript">
//            $("#btn_int_capturar").click(function(){
//                $("#inputSubida").trigger("click");
//            });
        </script>
html;

    echo $html;
}

/**
 * Funcrion que imprime los mensajes del locker
 */
function imprimeMensajesLocker() {
    imprimeMensajesLockerPadre();
    imprimeMensajesLockerTutor();
}

/**
 * Funcrion que imprime los mensajes del locker del padre
 */
function imprimeMensajesLockerPadre() {
    $idAlumno = obtenerIdDatosPersonales();
//    echo "alu=$idAlumno ".'loqueenvio'.padreDeAlumno($idAlumno);
    $correos = consultarMensajes(padreDeAlumno($idAlumno), $idAlumno, 2);
    $contador = 1;
    foreach ($correos as $c) {
        $mensajeC = substr($c->mensaje, 0, 35);
        echo <<<html
        <div class="locker_nota">
            <p><strong>$c->nombre_remitente</strong><br/>
                $mensajeC</p>
            <span class="fecha">$c->fecha_envio</span>
            <span class="btn_responder"><a href="responder_mensaje_padre.php?idPadre=$c->id_remitente">Responder</a></span>
        </div>
html;
        if ($contador >= 2) {
            break;
        }
        $contador++;
    }
}

/**
 * Funcrion que imprime los mensajes del locker del tutor
 */
function imprimeMensajesLockerTutor() {
    $correos = array();
    $contador = 1;
    $arrIdTutores = tutoresDeAlumno(obtenerIDTabla());
    if (!empty($arrIdTutores)) {
//        $arrMensajes = array();
        foreach ($arrIdTutores as $idTutor) {

            $correosT = consultarMensajes($idTutor, obtenerIdDatosPersonales(), 2);
            foreach ($correosT as $c) {
                $correos[$c->id_mensaje] = $c;
            }
        }
        sort($correos);
        foreach ($correos as $c) {
            $mensajeC = substr($c->mensaje, 0, 35);
            echo <<<html
            <div class="locker_nota">
                <p><strong>$c->nombre_remitente</strong><br/>
                    $mensajeC</p>
                <span class="fecha">$c->fecha_envio</span>
                <span class="btn_responder"><a href="responder_mensaje_tutor.php?idTutor=$c->id_remitente">Responder</a></span>
            </div>
html;
            if ($contador >= 2) {
                break;
            }
            $contador++;
        }
    }
}

/**
 * Funcrion que retorna un arreglo con los ids de los tutores que tienen relacion con un alumno
 * @param type $idAlumno
 * @return array
 */
function tutoresDeAlumno($idAlumno) {
    $arrTutores = array();
    $query = new Query("SG");
    $query->sql = <<<query
            select t.id_datos_personales
            from alumnos a
            join grupo_alumno ga
                    on ga.id_alumno =  a.id_alumno
            join rel_curso_grupo rcg
                    on rcg.id_grupo = ga.id_grupo
            join rel_curso_tutor rct
                    on rct.id_rel_curso_grupo = rcg.id_rel_curso_grupo
            join tutor t
                    on t.id_tutor = rct.id_tutor
            where a.id_alumno = $idAlumno
query;
    $resultado = $query->select("obj");
    if ($resultado) {
        foreach ($resultado as $r) {
            array_push($arrTutores, $r->id_datos_personales);
        }
    }
//    }var_dump($arrTutores);
    return $arrTutores;
}

/**
 * devuelve el padre de un alumno
 * @param type $idAlumno
 * @return type
 */
function padreDeAlumno($idAlumno) {
    $query = new Query("SG");
    $query->sql = <<<query
            select p.id_datos_personales as "idDatosPersonales"
            from alumnos a
            join padres p
                on p.id_padre = a.id_padre
            join datos_personales d
                on d.id_datos_personales = p.id_datos_personales 
            where a.id_datos_personales = $idAlumno
query;
    $resultado = $query->select("obj");
    if ($resultado) {
        foreach ($resultado as $r) {
//            echo 'padrreeeee'.$r->idDatosPersonales;
            return $r->idDatosPersonales;
        }
    }
}

/**
 * Para imprimir la paginación del alumno mensajes recibidos del padre
 */
function paginacionAlumnoDelPadre() {
//    echo '+++++++++++++++++++';
    for ($i = 1; $i <= cantidadPaginas(padreDeAlumno(obtenerIdDatosPersonales()), obtenerIdDatosPersonales(), 10); $i++) {
        echo "<a href='padre_revisar.php?pagina=$i'>$i</a> ";
    }
}

/**
 * Para imprimir la paginación del alumno mensajes recibidos del tutor
 */
function paginacionAlumnoDelTutor() {
//    echo '+++++++++++++++++++';
    for ($i = 1; $i <= cantidadPaginas(tutoresDeAlumno(obtenerIDTabla()), obtenerIdDatosPersonales(), 10); $i++) {
        echo "<a href='tutor_revisar.php?pagina=$i'>$i</a> ";
    }
}

/**
 * Imprime los mensajes que ha recibido un alumno de sus tutores
 * @param type $pagina
 */
function mensajesAlAlumnoDelTutor($pagina) {
//    echo '---';
//    var_dump(tutoresDeAlumno(obtenerIDTabla()));
//    echo '---';
    $mensajes = consultarMensajes(tutoresDeAlumno(obtenerIDTabla()), obtenerIdDatosPersonales(), 10, $pagina);
    foreach ($mensajes as $m) {
//        $padre = 
        $mensajeC = substr($m->mensaje, 0, 70);
        $grupo = obtenerGrupoAlumno(obtenerIdDatosPersonales());
        echo <<<html
        <li><a href="#"><div class="msg_head"><span class="alignLeft">$m->nombre_remitente - $grupo</span><span class="alignRight">$m->fecha_envio</span></div>
            <span class="alignLeft">$mensajeC</span></a>
            <ul class="msgs">
                <li>
                    <div id="msg_body">                       
                        <div class="msg_text" style="text-align:justify">$m->mensaje.</div>
                        <div>
                            <form id="frm$m->id_mensaje" action="enviaMensaje.php" method="post">
                                <input type="hidden" id="idDestinatario" name="idDestinatario" value="$m->id_remitente"/>
                                <input type="hidden" id="location" name="location" value="tutor_revisar.php"/>
                                <textarea required="required" id="msg_cuerpo" cols="60" name = "mensaje" rows="3" wrap="soft" placeholder="Mensaje..." style="text-align:justify"> </textarea>
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

/**
 * ID DATOS PERSONALES
 * @param type $idDatosPersonales
 */
function obtenerGrupoAlumno($idDatosPersonales) {

    if (!is_numeric($idDatosPersonales))
        return " ";
    $sql = "SELECT  g.nombre_grupo as grupo
                FROM datos_personales dp
                JOIN alumnos al
                ON al.id_datos_personales = dp.id_datos_personales
                JOIN grupo_alumno gp
                ON gp.id_alumno = al.id_alumno
                JOIN grupo g
                ON g.id_grupo = gp.id_grupo
                WHERE dp.id_datos_personales = " . $idDatosPersonales . "
                LIMIT 1";

    $sql2 = new Query("SG");
    $sql2->sql = $sql;
    $resultado = $sql2->select('arr');

    if ($resultado) {
        return $resultado[0]['grupo'];
    }
    else
        return " ";
}

/**
 * Imprime los mensajes del alumno que ha recibido del padre
 * @param type $pagina
 */
function mensajesAlAlumnoDelPadre($pagina) {
    $mensajes = consultarMensajes(padreDeAlumno(obtenerIdDatosPersonales()), obtenerIdDatosPersonales(), 10, $pagina);
    foreach ($mensajes as $m) {
//        $padre = 
        $grupo = obtenerGrupoAlumno(obtenerIdDatosPersonales());
        $mensajeC = substr($m->mensaje, 0, 70);
        echo <<<html
        <li><a href="#"><div class="msg_head"><span class="alignLeft">$m->nombre_remitente - $grupo</span><span class="alignRight">$m->fecha_envio</span></div>
            <span class="alignLeft">$mensajeC</span></a>
            <ul class="msgs">
                <li>
                    <div id="msg_body">                       
                        <div class="msg_text" style="text-align:justify">$m->mensaje.</div>
                        <div>
                            <form id="frm$m->id_mensaje" action="enviaMensaje.php" method="post">
                                <input type="hidden" id="idDestinatario" name="idDestinatario" value="$m->id_remitente"/>
                                <input type="hidden" id="location" name="location" value="padre_revisar.php"/>
                                <textarea id="msg_cuerpo" cols="60" name = "mensaje" rows="3" wrap="soft" placeholder="Mensaje..." style="text-align:justify"> </textarea>
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
/**
 * Imprime los options si hay  alumnos o uno si no lo hay
 * @param type $idAlumno
 */
function imprimeOptionsDeCompanyerosDeAlumno($idAlumno) {
    $alumnos = companyerosDeAlumno($idAlumno);
    
    if ($alumnos == null) {
        echo "<option>No hay compa&ntilde;eros en tu grupo.</option>";
    } else {
        foreach ($alumnos as $a) {
            echo "<option value = $a->id_datos_personales>$a->nombre_pila $a->primer_apellido $a->segundo_apellido</option>";
        }
    }
}

/**
 * Devuelve un objeto de consulta de los compañeros en los mismos grupos que un alumno que se envíe como parámetro
 * @param type $idAlumno
 * @return type
 */
function companyerosDeAlumno($idAlumno) {
    $idsGrupo = gruposDeAlumno($idAlumno);
    if ($idsGrupo != "") {
        $query = new Query("SG");
        $query->sql = <<<query
        select distinct(a.id_alumno), d.id_datos_personales, d.nombre_pila, d.primer_apellido, d.segundo_apellido
        from grupo_alumno ga
        left join alumnos a
            on a.id_alumno = ga.id_alumno
        left join datos_personales d
            on a.id_datos_personales = d.id_datos_personales
        where ga.id_grupo in ($idsGrupo) and a.id_alumno != $idAlumno
query;
//        echo $query->sql;
        $resultado = $query->select("obj");
//        var_dump($resultado);
        return $resultado;
        
    } else {
        return null;
    }
}

/**
 * Devuelve un string separados por comas de los grupos en los que está un alumno
 * @param type $idAlumno
 * @return type
 */
function gruposDeAlumno($idAlumno) {
    $query = new Query("SG");
    $query->sql = <<<query
        select id_grupo
        from grupo_alumno
        where id_alumno = $idAlumno
query;

    $consulta = $query->select("obj");
    $ins = "";
    foreach ($consulta as $c) {
        $ins .= $ins. $c->id_grupo . " ,";
    }
//    var_dump($consulta);
    return substr($ins, 0, strlen($ins) - 2);
//    return $ins;
}

/**
 * Imprime los mensajes recibidos a un tutor
 * @param type $idTutor
 * @param type $pagina
 * @param type $tipo
 */
function mensajesAlAlumnoDeCompanyeros($idAlumno, $pagina, $tipo) {
//    echo '---' . $idTutor;
    $arrIds = array();
    $compas = companyerosDeAlumno($idAlumno);
    foreach($compas as $c){
        if(!in_array($c->id_datos_personales, $arrIds)){
            array_push($arrIds, $c->id_datos_personales);
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
            } else {//eviados
                $persona = $m->nombre_destinatario;
            }
            $mensajeC = substr($m->mensaje, 0, 70);
            $grupo = obtenerGrupoAlumno($m->id_remitente);
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
?>
