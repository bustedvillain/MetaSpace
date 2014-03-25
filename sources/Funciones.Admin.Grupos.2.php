<?php

$arrRangos = array();
$arrIds = array();

array_push($arrRangos, "20-40");
array_push($arrRangos, "1-3");
array_push($arrIds, 8);
array_push($arrIds, 9);
array_push($arrIds, 47);
array_push($arrIds, 48);
array_push($arrIds, 49);

//getUsuariosParaSubida(null, $arrRangos, $arrIds);
//getMatrizDeUsuarios(null, $arrRangos, $arrIds);

/**
 * Funcion que retorna los grupos que pertenecen a una escuela
 * @param type $idEmpresa
 * @return type
 */
function getGruposPorEscuela($idEscuela) {
    $query = new Query("SG");

    $query->sql = <<<SQL
            SELECT *
            FROM grupo
            WHERE id_escuela= $idEscuela
            and tipo_grupo = 0

SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        return $resultado;
    }
}

/**
 * Funcion que retorna los grupos que pertenecen a una escuela en una areglo JSON
 * @param type $idEmpresa
 * @return typed
 */
function getGruposPorEscuelaJSON($idEscuela) {
    return json_encode(getGruposPorEscuela($idEscuela));
}
/**
 * Funcion que retorna los grupos que fueron seleccionados en un curso
 * @param type $idCursoAbierto
 * @return type
 */
function getGruposSeleccionados($idCursoAbierto) {
    $query = new Query("SG");

    $query->sql = <<<SQL
            SELECT r.id_grupo as "id_grupo", g.nombre_grupo as "nombre_grupo", g.clave as "clave"
            FROM rel_curso_grupo r
            JOIN grupo g
                on g.id_grupo = r.id_grupo
            WHERE id_curso_abierto= $idCursoAbierto


SQL;

    $resultado = $query->select("obj");

    if ($resultado) {
        return $resultado;
    }
}

/**
 * Funcion que retorna los grupos que fueron seleccionados en un curso en una areglo JSON
 * @param type $idCursoAbierto
 * @return typed
 */
function getGruposSeleccionadosJSON($idCursoAbierto) {
    return json_encode(getGruposSeleccionados($idCursoAbierto));
}
/**
 * Obtiene las primeras 2 consonantes de un string
 * @param type $valor
 * @return string
 */
function getDosPrimerasConsosnantes($valor) {
    $vowels = array('B' ,'C' ,'D'  ,'F' ,'G' ,'H' ,'J' ,'K' ,'L' ,'M' ,'N' ,'Ñ' ,'P' ,'Q' ,'R' ,'S' ,'T' ,'U' ,'V' ,'W' ,'X' ,'Y' ,'Z');
    $j = 0;
    $valorFinal = "";
    for ($i = 0; $i < strlen($valor); $i++) {
//        echo 'Letra es' . $valor[$i];
        if ($j > 1)
            break;
        if (in_array($valor[$i], $vowels)) {
//            echo 'Fue:' . $valor[$i];
            $valorFinal = $valorFinal . $valor[$i];
            $j++;
        }
    }

    return $valorFinal;
}
/**
 * Funcion que retorna matriz de los datos de usuarios para subida masiva de algún tipo
 * @param type $tipoUsuario @0=traerá los alumnos @1=traera los tutores @2=traerá los profes de aula @3=traera a los padres @4=traerá a los gestores de contenido @5=traera los admin
 * @param type $arrRangos un arreglo con los rangos por ejemplo $arrRangos = array("12-15","20-56","200-250")
 * @param type $arrIdsDatosPersonales un arrelgo con los id que se desean buscar por ejemplo $arrIdsDatosPersonales = array(1,5,8,10,1112)
 * @return matriz
 */
function getMatrizDeUsuarios($tipoUsuario = null, $arrRangos = null, $arrIdsDatosPersonales = null) {
    
    $resultado = getUsuariosParaSubida($tipoUsuario, $arrRangos, $arrIdsDatosPersonales);
    $matrizDeUsuarios = null;
    foreach ($resultado as $number => $headersA) {
        $headers = $headersA;
        foreach ($headers as $header => $valor) {
//            echo "##N=$number H=$header V=$d <br/>";
            

            if ($header == 'password'){
            $valor = nDCrypt($valor);}
            else {
                $valor = entidadesToAcentos($valor);
            }
            if ($header == 'country')
                $valor = getDosPrimerasConsosnantes($valor);

            $matrizDeUsuarios[$number][$header] = $valor;
        }
    }
    return $matrizDeUsuarios;
}

/**
 * 
 */

/**
 * Limpia texto eliminando acentos y caracteres especiales
 * @param type $String
 * @return type
 */
Function entidadesToAcentos($String) {

    $String = str_replace("&aacute;", "á", $String);
    $String = str_replace("&eacute;", "é", $String);
    $String = str_replace("&iacute;", "í", $String);
    $String = str_replace("&oacute;", "ó", $String);
    $String = str_replace("&uacute;", "ú", $String);
    $String = str_replace("&AACUTE;", "Á", $String);
    $String = str_replace("&EACUTE;", "É", $String);
    $String = str_replace("&IACUTE;", "Í", $String);
    $String = str_replace("&OACUTE;", "Ó", $String);
    $String = str_replace("&UACUTE;", "Ú", $String);

    return $String;
}

/**
 * Funcion que retorna objeto de los datos de usuarios para subida masiva de algún tipo
 * @param type $tipoUsuario @0=traerá los alumnos @1=traera los tutores @2=traerá los profes de aula @3=traera a los padres @4=traerá a los gestores de contenido @5=traera los admin
 * @param type $arrRangos un arreglo con los rangos por ejemplo $arrRangos = array("12-15","20-56","200-250")
 * @param type $arrIdsDatosPersonales un arrelgo con los id que se desean buscar por ejemplo $arrIdsDatosPersonales = array(1,5,8,10,1112)
 * @return consulta en obj
 */
function getUsuariosParaSubida($tipoUsuario = 0, $arrRangos = null, $arrIdsDatosPersonales = null) {
    include_once 'Query.php';
    $banderaPrimeravez = true;
    $query = new Query("SG");
    $betweenSql = "";
    $inSql = "";
    $tipoU = "";
    if (isset($tipoUsuario)) {
        $tipoU = " where  tipo_usuario = " . $tipoUsuario;
    }
    if (isset($arrRangos)) {
        foreach ($arrRangos as $rango) {
            $valores = explode("-", $rango);
            if ($banderaPrimeravez) {
                $betweenSql = $betweenSql . " d.id_datos_personales between " . $valores[0] . " and " . $valores[1];
                $banderaPrimeravez = false;
            } else {
                $betweenSql = $betweenSql . "or d.id_datos_personales between " . $valores[0] . " and " . $valores[1];
            }
        }
    }
    if (isset($arrIdsDatosPersonales)) {
        $stringIds = "";
        $banderaPrimeravez2 = true;
        foreach ($arrIdsDatosPersonales as $idDatosP) {
            if ($banderaPrimeravez2) {
                $stringIds = $idDatosP;
                $banderaPrimeravez2 = false;
            } else {
                $stringIds = $stringIds . "," . $idDatosP;
            }
        }
        if ($banderaPrimeravez) {
            $inSql = $inSql . " d.id_datos_personales in(" . $stringIds . ") ";
            $banderaPrimeravez = false;
        } else {
            $inSql = $inSql . " or d.id_datos_personales in(" . $stringIds . ") ";
        }
    }
    $predicado = "";
    if (isset($arrRangos) || isset($arrIdsDatosPersonales)) {
        $predicado = " and(" . $betweenSql . $inSql . ")";
    }

    $extraSelect = "";
    $extraJoin = "";
    switch ($tipoUsuario)
    {
        case 0:
            $extraSelect = ",'alumno' as \"role1\" , c.nombre_corto as \"course1\", g.nombre_grupo as \"group1\"";
            $extraJoin = "
            left join alumnos a
                    on a.id_datos_personales = d.id_datos_personales
            left join grupo_alumno ga
                    on ga.id_alumno = a.id_alumno
            left join grupo g
                    on g.id_grupo = ga.id_grupo
            left join rel_curso_grupo rcg
                    on rcg.id_grupo = g.id_grupo
            left join cursos_abiertos ca
                    on ca.id_curso_abierto = rcg.id_curso_abierto
            left join cursos c
                    on c.id_curso = ca.id_curso
                ";
            $predicado = $predicado . "  and a.status = 1";
            break;
        case 1:
            $extraSelect = ", lower(r.nombre) as \"role1\", c.nombre_corto as \"course1\", g.nombre_grupo as \"group1\"";
            $extraJoin = "
            left join tutor t
                    on t.id_datos_personales = d.id_datos_personales
            left join rol_tutor r
                    on r.id_rol_tutor = t.id_rol_tutor
            left join rel_curso_tutor rct
                    on rct.id_tutor = t.id_tutor
            left join rel_curso_grupo rcg
                    on rcg.id_rel_curso_grupo = rct.id_rel_curso_grupo
            left join cursos_abiertos ca
                    on ca.id_curso_abierto = rcg.id_curso_abierto
            left join cursos c
                    on c.id_curso = ca.id_curso
            left join grupo g
                    on g.id_grupo = rcg.id_grupo";
            $predicado = $predicado . "  and t.status = 1";
            break;
        case 2:
            $extraSelect = ",'profesor de aula' as \"role1\"";
            $extraJoin = "";
            break;
        case 3:
            $extraSelect = ",'padre' as \"role1\"";
            $extraJoin = "";
            break;
        case 4:
            $extraSelect = ",'gestor' as \"role1\"";
            $extraJoin = "";
            break;
        case 5:
            $extraSelect = ",'admin' as \"role1\"";
            $extraJoin = "";
            break;
        case 6:
            $extraSelect = ",'alumno' as \"role1\" , c.nombre_corto as \"course1\", g.nombre_grupo as \"group1\"";
            $extraJoin = "
            left join alumnos a
                    on a.id_datos_personales = d.id_datos_personales
            left join grupo_alumno ga
                    on ga.id_alumno = a.id_alumno
            left join grupo g
                    on g.id_grupo = ga.id_grupo
            left join rel_curso_grupo rcg
                    on rcg.id_grupo = g.id_grupo
            left join cursos_abiertos ca
                    on ca.id_curso_abierto = rcg.id_curso_abierto
            left join cursos c
                    on c.id_curso = ca.id_curso
                ";
            $predicado = $predicado . " and a.tipo_alumno = 1 and a.status = 1 ";
            break;
    }
    $query->sql = <<<SQL
            select nombre_usuario as "username", contrasena as "password", nombre_pila as "firstname", primer_apellido as "lastname", 
            correo as "email", delegacion_municipio as "city", upper(n.nacionalidad) as "country" $extraSelect

            from datos_personales d
            left join nacionalidad n 
                    on d.id_nacionalidad = n.id_nacionalidad
            $extraJoin
            
            $tipoU
            $predicado
            
            order by d.id_datos_personales

SQL;
//    and tipo_usuario not in (2,3)

    $resultado = $query->select("obj");
    
    if ($resultado) {
        return $resultado;
    }
//    echo $query->sql;
}

?>
