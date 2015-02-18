<?php
/**
 * Devuelve 2 arrays en una pila el primer pop saca idsDelCurso y el segundo IdsRelCursoGrupo
 * @param type $idAlumno idDelAlumno
 * @return array
 */
function getArraysIdsRelCursoGrupo($idAlumno) {
    //Crea objeto para realizar consultas a moodle
    $query = new Query("SG");
    $query->sql = <<<querye
            select r.id_rel_curso_grupo as "idRelCursoGrupo", c.id_curso as "idCurso"
            from grupo_alumno g
            join rel_curso_grupo r
                    on r.id_grupo = g.id_grupo
            join cursos_abiertos c
                    on c.id_curso_abierto = r.id_curso_abierto
	    join cursos cu
		on cu.id_curso = c.id_curso
            join categorias ca
                    on ca.id_categoria = cu.id_categoria
            
            where g.id_alumno = $idAlumno
            and lower(cu.nombre_curso) not like '%scorm%'
            order by ca.id_categoria
querye;
    $cursos = $query->select("obj");
    $arrIdsRelCursoGrupo = array();
    $arrIdsCurso = array();
    foreach ($cursos as $c) {
        array_push($arrIdsRelCursoGrupo, $c->idRelCursoGrupo);
        array_push($arrIdsCurso, $c->idCurso);
    }
    $arrAmbos = array();
    array_push($arrAmbos, $arrIdsRelCursoGrupo);
    array_push($arrAmbos, $arrIdsCurso);
    return $arrAmbos;
}


/**
 * Devuelve 2 arrays en una pila el primer pop saca idsDelCurso SCORM y el segundo IdsRelCursoGrupo
 * @param type $idAlumno idDelAlumno
 * @return array
 */
function getArraysIdsRelCursoGrupoScorm($idAlumno) {
    //Crea objeto para realizar consultas a moodle
    $query = new Query("SG");
    $query->sql = <<<querye
            select r.id_rel_curso_grupo as "idRelCursoGrupo", c.id_curso as "idCurso"
            from grupo_alumno g
            join rel_curso_grupo r
                    on r.id_grupo = g.id_grupo
            join cursos_abiertos c
                    on c.id_curso_abierto = r.id_curso_abierto
	    join cursos cu
		on cu.id_curso = c.id_curso
            join categorias ca
                    on ca.id_categoria = cu.id_categoria
            
            where g.id_alumno = $idAlumno
            and lower(cu.nombre_curso) like '%scorm%'
            order by ca.id_categoria
querye;
    $cursos = $query->select("obj");
    $arrIdsRelCursoGrupo = array();
    $arrIdsCurso = array();
    foreach ($cursos as $c) {
        array_push($arrIdsRelCursoGrupo, $c->idRelCursoGrupo);
        array_push($arrIdsCurso, $c->idCurso);
    }
    $arrAmbos = array();
    array_push($arrAmbos, $arrIdsRelCursoGrupo);
    array_push($arrAmbos, $arrIdsCurso);
    return $arrAmbos;
}

/**
 * ´Devuelve 2 arreglos en una pila, el primer pop saca los idsDeCursoDeltutor y el segundo los IdsDeCursosAbiertos
 * @param type $idTutor
 * @return array
 */
function getArrayIdsCursoPorTutor($idTutor){
    $cursos = getCursosPorTutor($idTutor);
    $arrIdsCurso = array();
    $arrIdsCursoAb = array();
    foreach($cursos as $c){
        if(!in_array($c->idCursoAbierto, $arrIdsCursoAb)){
            array_push($arrIdsCursoAb, $c->idCursoAbierto);
            array_push($arrIdsCurso, $c->idCurso);
        }    
    }
    $arrAmbos = array();
    array_push($arrAmbos, $arrIdsCursoAb);
    array_push($arrAmbos, $arrIdsCurso);
    return $arrAmbos;
}


/**
 * Imprime las cajas para el alumno
 * @param type $arrIdsCurso
 * @param type $arrIdsRelCursoGrupo
 * @param type $rutaCursos
 * @param type $cantidad
 * @return array
 */
function imprimeCajas($arrIdsCurso, $arrIdsRelCursoGrupo, $rutaCursos, $cantidad) {
    $contador = 1;
    $arrayEventos = array();
    if (($cantidad % 2 == 0) && $cantidad > 4) {//si es par y mayor a 4
        $final1 = $arrIdsCurso[$cantidad - 2];
        $final2 = $arrIdsCurso[$cantidad - 1];
    } elseif (($cantidad % 2 != 0) && $cantidad > 4) {//si no es par y mayor a 4
        $final1 = $arrIdsCurso[$cantidad - 2];
        $final2 = $arrIdsCurso[$cantidad - 1];
    }
//    echo'///'.$final1.'////'.$final2;
    foreach ($arrIdsCurso as $c) {
        $rutaC1 = $rutaCursos . $c . "/frontweb/cuadro-a.gif";
        $rutaC2 = $rutaCursos . $c . "/frontweb/slide.gif";
        $rutaC3 = $rutaCursos . $c . "/frontweb/btn_empezar.png";
        switch ($contador) {
            case 1:
                $classAlign = "modulo_izq";
                $idCaja = "modulo_letras";
                $idCaja2 = "contenido_a_mostrar_letras";
                $metodo1 = "contenido_a_mostrar_letras" . $contador;
                $idSlide = $idCaja . $contador;
                break;
            case 2:
                $classAlign = "modulo_decha";
                $idCaja = "modulo_mates";
                $idCaja2 = "contenido_a_mostrar_mates";
                $metodo1 = "contenido_a_mostrar_mates" . $contador;
                $idSlide = $idCaja . $contador;
                break;
            case 3:
                if ($cantidad <= 4) {
                    $classAlign = "modulo_izq";
                    $idCaja = "modulo_ciencias";
                    $idCaja2 = "contenido_a_mostrar_ciencias";
                    $metodo1 = "contenido_a_mostrar_ciencias" . $contador;
                    $idSlide = $idCaja . $contador;
                } else {
                    $classAlign = "modulo_izq";
                    $idCaja = "modulo_letras";
                    $idCaja2 = "contenido_a_mostrar_letras";
                    $metodo1 = "contenido_a_mostrar_letras" . $contador;
                    $idSlide = $idCaja . $contador;
                }
                break;
            case 4:
                if ($cantidad <= 4) {
                    $classAlign = "modulo_decha";
                    $idCaja = "modulo_artes";
                    $idCaja2 = "contenido_a_mostrar_artes";
                    $metodo1 = "contenido_a_mostrar_artes" . $contador;
                    $idSlide = $idCaja . $contador;
                } else {
                    $classAlign = "modulo_decha";
                    $idCaja = "modulo_mates";
                    $idCaja2 = "contenido_a_mostrar_mates";
                    $metodo1 = "contenido_a_mostrar_mates" . $contador;
                    $idSlide = $idCaja . $contador;
                }
                break;
            default:
                if($contador == $final1){
                    $classAlign = "modulo_izq";
                    $idCaja = "modulo_ciencias";
                    $idCaja2 = "contenido_a_mostrar_ciencias";
                    $metodo1 = "contenido_a_mostrar_ciencias" . $contador;
                    $idSlide = $idCaja . $contador;
                }elseif($contador == $final2){
                    $classAlign = "modulo_decha";
                    $idCaja = "modulo_artes";
                    $idCaja2 = "contenido_a_mostrar_artes";
                    $metodo1 = "contenido_a_mostrar_artes" . $contador;
                    $idSlide = $idCaja . $contador;
                }elseif(($contador % 2) !=0){
                    $classAlign = "modulo_izq";
                    $idCaja = "modulo_letras";
                    $idCaja2 = "contenido_a_mostrar_letras";
                    $metodo1 = "contenido_a_mostrar_letras" . $contador;
                    $idSlide = $idCaja . $contador;
                }else{
                    $classAlign = "modulo_decha";
                    $idCaja = "modulo_mates";
                    $idCaja2 = "contenido_a_mostrar_mates";
                    $metodo1 = "contenido_a_mostrar_mates" . $contador;
                    $idSlide = $idCaja . $contador;
                }
                break;
        }
        array_push($arrayEventos, $metodo1);
        $url = 'idRelCursoGrupo=' . $arrIdsRelCursoGrupo[$contador - 1] . '&idCurso=' . $arrIdsCurso[$contador - 1];
        echo<<<primera
            <div class="$classAlign">
                <div class="$idCaja" class="cajaImg" style="background-image:url($rutaC1)">
                    <a href="javascript:void(0);" onClick="muestra_oculta('$metodo1')" title=""></a>
                </div>
                <div id="$metodo1" class="$idCaja2">
                    <a href="javascript:void(0);" onClick="muestra_oculta('$metodo1')" title="">
                        <img src="$rutaC2" width="280" height="270" />
                    </a>
                    <span class="contenido_enlace" style="background-image:url($rutaC3)">
                        <a href="mapa.php?alumno=si&$url "></a>
                    </span>
                </div>
            </div>
primera;
        $contador++;
    }
    return $arrayEventos;
    
}



/**
 * Imprime las cajas scorm para el alumno
 * @param type $arrIdsCurso
 * @param type $arrIdsRelCursoGrupo
 * @param type $rutaCursos
 * @param type $cantidad
 * @return array
 */
function imprimeCajasScorm($arrIdsCurso, $arrIdsRelCursoGrupo, $rutaCursos, $cantidad) {
    $contador = 1;
    $arrayEventos = array();
    if (($cantidad % 2 == 0) && $cantidad > 4) {//si es par y mayor a 4
        $final1 = $arrIdsCurso[$cantidad - 2];
        $final2 = $arrIdsCurso[$cantidad - 1];
    } elseif (($cantidad % 2 != 0) && $cantidad > 4) {//si no es par y mayor a 4
        $final1 = $arrIdsCurso[$cantidad - 2];
        $final2 = $arrIdsCurso[$cantidad - 1];
    }
//    echo'///'.$final1.'////'.$final2;
    $arrIdsCurso= array_unique($arrIdsCurso);
    foreach ($arrIdsCurso as $c) {
        $rutaC1 = $rutaCursos . $c . "/frontweb/cuadro-a.gif";
        $rutaC2 = $rutaCursos . $c . "/frontweb/slide.gif";
        $rutaC3 = $rutaCursos . $c . "/frontweb/btn_empezar.png";
        switch ($contador) {
            case 1:
                $classAlign = "modulo_izq";
                $idCaja = "modulo_letras";
                $idCaja2 = "contenido_a_mostrar_letras";
                $metodo1 = "contenido_a_mostrar_letras_sco" . $contador;
                $idSlide = $idCaja . $contador;
                break;
            case 2:
                $classAlign = "modulo_decha";
                $idCaja = "modulo_mates";
                $idCaja2 = "contenido_a_mostrar_mates";
                $metodo1 = "contenido_a_mostrar_mates_sco" . $contador;
                $idSlide = $idCaja . $contador;
                break;
            case 3:
                if ($cantidad <= 4) {
                    $classAlign = "modulo_izq";
                    $idCaja = "modulo_ciencias";
                    $idCaja2 = "contenido_a_mostrar_ciencias";
                    $metodo1 = "contenido_a_mostrar_ciencias_sco" . $contador;
                    $idSlide = $idCaja . $contador;
                } else {
                    $classAlign = "modulo_izq";
                    $idCaja = "modulo_letras";
                    $idCaja2 = "contenido_a_mostrar_letras";
                    $metodo1 = "contenido_a_mostrar_letras_sco" . $contador;
                    $idSlide = $idCaja . $contador;
                }
                break;
            case 4:
                if ($cantidad <= 4) {
                    $classAlign = "modulo_decha";
                    $idCaja = "modulo_artes";
                    $idCaja2 = "contenido_a_mostrar_artes";
                    $metodo1 = "contenido_a_mostrar_artes_sco" . $contador;
                    $idSlide = $idCaja . $contador;
                } else {
                    $classAlign = "modulo_decha";
                    $idCaja = "modulo_mates";
                    $idCaja2 = "contenido_a_mostrar_mates";
                    $metodo1 = "contenido_a_mostrar_mates_sco" . $contador;
                    $idSlide = $idCaja . $contador;
                }
                break;
            default:
                if($contador == $final1){
                    $classAlign = "modulo_izq";
                    $idCaja = "modulo_ciencias";
                    $idCaja2 = "contenido_a_mostrar_ciencias";
                    $metodo1 = "contenido_a_mostrar_ciencias_sco" . $contador;
                    $idSlide = $idCaja . $contador;
                }elseif($contador == $final2){
                    $classAlign = "modulo_decha";
                    $idCaja = "modulo_artes";
                    $idCaja2 = "contenido_a_mostrar_artes";
                    $metodo1 = "contenido_a_mostrar_artes_sco" . $contador;
                    $idSlide = $idCaja . $contador;
                }elseif(($contador % 2) !=0){
                    $classAlign = "modulo_izq";
                    $idCaja = "modulo_letras";
                    $idCaja2 = "contenido_a_mostrar_letras";
                    $metodo1 = "contenido_a_mostrar_letras_sco" . $contador;
                    $idSlide = $idCaja . $contador;
                }else{
                    $classAlign = "modulo_decha";
                    $idCaja = "modulo_mates";
                    $idCaja2 = "contenido_a_mostrar_mates";
                    $metodo1 = "contenido_a_mostrar_mates_sco" . $contador;
                    $idSlide = $idCaja . $contador;
                }
                break;
        }
        array_push($arrayEventos, $metodo1);
        array_push($arrayEventos, $metodo1);
        $url = 'idRelCursoGrupo=' . $arrIdsRelCursoGrupo[$contador - 1] . '&idCurso=' . $c;
        echo<<<primera
            <div class="$classAlign">
                <div class="$idCaja" class="cajaImg" style="background-image:url($rutaC1)">
                    <a href="javascript:void(0);" onClick="muestra_oculta('$metodo1')" title=""></a>
                </div>
                <div id="$metodo1" class="$idCaja2">
                    <a href="javascript:void(0);" onClick="muestra_oculta('$metodo1')" title="">
                        <img src="$rutaC2" width="280" height="270" />
                    </a>
                    <span class="contenido_enlace" style="background-image:url($rutaC3)">
                        <a href="mapaSco.php?alumno=si&$url "></a>
                    </span>
                </div>
            </div>
primera;
        $contador++;
    }
    return $arrayEventos;
    
}

/**
 * Imprime las cajas para el alumno
 * @param type $arrIdsCurso
 * @param type $arrIdsRelCursoGrupo
 * @param type $rutaCursos
 * @param type $cantidad
 * @return array
 */
function imprimeCajasTutor($arrIdsCurso, $arrIdsCursoAb, $rutaCursos, $cantidad) {
    $contador = 1;
    $arrayEventos = array();
    if (($cantidad % 2 == 0) && $cantidad > 4) {//si es par y mayor a 4
        $final1 = $arrIdsCurso[$cantidad - 2];
        $final2 = $arrIdsCurso[$cantidad - 1];
    } elseif (($cantidad % 2 != 0) && $cantidad > 4) {//si no es par y mayor a 4
        $final1 = $arrIdsCurso[$cantidad - 2];
        $final2 = $arrIdsCurso[$cantidad - 1];
    }
    foreach ($arrIdsCurso as $c) {
        $rutaC1 = $rutaCursos . $c . "/frontweb/cuadro-a.gif";
        $rutaC2 = $rutaCursos . $c . "/frontweb/slide.gif";
        $rutaC3 = $rutaCursos . $c . "/frontweb/btn_empezar.png";
        switch ($contador) {
            case 1:
                $classAlign = "modulo_izq";
                $idCaja = "modulo_letras";
                $idCaja2 = "contenido_a_mostrar_letras";
                $metodo1 = "contenido_a_mostrar_letras" . $contador;
                $idSlide = $idCaja . $contador;
                break;
            case 2:
                $classAlign = "modulo_decha";
                $idCaja = "modulo_mates";
                $idCaja2 = "contenido_a_mostrar_mates";
                $metodo1 = "contenido_a_mostrar_mates" . $contador;
                $idSlide = $idCaja . $contador;
                break;
            case 3:
                if ($cantidad <= 4) {
                    $classAlign = "modulo_izq";
                    $idCaja = "modulo_ciencias";
                    $idCaja2 = "contenido_a_mostrar_ciencias";
                    $metodo1 = "contenido_a_mostrar_ciencias" . $contador;
                    $idSlide = $idCaja . $contador;
                } else {
                    $classAlign = "modulo_izq";
                    $idCaja = "modulo_letras";
                    $idCaja2 = "contenido_a_mostrar_letras";
                    $metodo1 = "contenido_a_mostrar_letras" . $contador;
                    $idSlide = $idCaja . $contador;
                }
                break;
            case 4:
                if ($cantidad <= 4) {
                    $classAlign = "modulo_decha";
                    $idCaja = "modulo_artes";
                    $idCaja2 = "contenido_a_mostrar_artes";
                    $metodo1 = "contenido_a_mostrar_artes" . $contador;
                    $idSlide = $idCaja . $contador;
                } else {
                    $classAlign = "modulo_decha";
                    $idCaja = "modulo_mates";
                    $idCaja2 = "contenido_a_mostrar_mates";
                    $metodo1 = "contenido_a_mostrar_mates" . $contador;
                    $idSlide = $idCaja . $contador;
                }
                break;
            default:
                if($contador == $final1){
                    $classAlign = "modulo_izq";
                    $idCaja = "modulo_ciencias";
                    $idCaja2 = "contenido_a_mostrar_ciencias";
                    $metodo1 = "contenido_a_mostrar_ciencias" . $contador;
                    $idSlide = $idCaja . $contador;
                }elseif($contador == $final2){
                    $classAlign = "modulo_decha";
                    $idCaja = "modulo_artes";
                    $idCaja2 = "contenido_a_mostrar_artes";
                    $metodo1 = "contenido_a_mostrar_artes" . $contador;
                    $idSlide = $idCaja . $contador;
                }elseif(($contador % 2) !=0){
                    $classAlign = "modulo_izq";
                    $idCaja = "modulo_letras";
                    $idCaja2 = "contenido_a_mostrar_letras";
                    $metodo1 = "contenido_a_mostrar_letras" . $contador;
                    $idSlide = $idCaja . $contador;
                }else{
                    $classAlign = "modulo_decha";
                    $idCaja = "modulo_mates";
                    $idCaja2 = "contenido_a_mostrar_mates";
                    $metodo1 = "contenido_a_mostrar_mates" . $contador;
                    $idSlide = $idCaja . $contador;
                }
                break;
        }
        array_push($arrayEventos, $metodo1);
//        $url = 'idRelCursoGrupo=' . $arrIdsRelCursoGrupo[$contador - 1] . '&idCurso=' . $arrIdsCurso[$contador - 1];
        $idCursoAbierto = $arrIdsCursoAb[$contador - 1];
        $rutaPrograma = BASE_STORAGE. "cursos/$c/archivos/programa.pdf";
        $colorCaja  = obtenerColor(STORAGE_PATH."cursos/".$c."/frontweb/colores.ini");
        echo<<<primera
            <div class="$classAlign">
                <div class="$idCaja" class="cajaImg" style="background-image:url($rutaC1)">
                    <a href="javascript:void(0);" onClick="muestra_oculta('$metodo1')" title=""></a>
                </div>
                <div id="$metodo1" class="$idCaja2">
                    <a href="javascript:void(0);" onClick="muestra_oculta_letras('contenido_a_mostrar_letras')" title=""><div class="extra_cajas" style="background-color: $colorCaja"></div></a>
                    <span class="tutor_enlace1"><a href="grupos.php?idCursoAbierto=$idCursoAbierto">Grupos</a></span>
                    <span class="tutor_enlace2"><a target="_blank" href="$rutaPrograma">Programa de curso</a></span>
                    <span class="tutor_enlace3"><a href="mapa.php?alumno=no&idCurso=$c">Mapa de ruta</a></span>
                </div>
            </div>
primera;
        $contador++;
    }
    return $arrayEventos;
//    <a href="#" onClick="muestra_oculta('$metodo1')" title="">
//                        <img src="$rutaC2" width="280" height="270" />
//                    </a>
//                    <span class="contenido_enlace" style="background-image:url($rutaC3)">
//                        <a href="mapa.php?alumno=si&$url "></a>
//                    </span>
}
?>
