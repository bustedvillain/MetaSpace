<?php

/*
 * Autor: José Manuel Nieto Gómez
 * Fecha de Creación: Miercoles 6 de Noviembre del 2013
 * Objetivo: Funciones generales sobre tutores 
 */

/**
 * Funcion que recibe el id del rol de tutor y regresa el nombre
 * de todos los tutores activos con eses rol
 * @param type $idRol
 * @return type
 */
function getTutores($idRol, $cuales = "todos", $idCursoAbierto = NULL, $idRelCursoGrupo = NULL) {
    $query = new Query("SG");
    //Query a SGI
    switch ($cuales) {
        case "todos":
            $query->sql = <<<SQL
                SELECT t.id_tutor, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido
                FROM datos_personales dp, tutor t
                WHERE t.id_datos_personales = dp.id_datos_personales
                  and t.id_rol_tutor = $idRol
                  and t.status=1
SQL;
            break;
        case "disponibles":
            if (isset($idCursoAbierto)) {
                $query->sql = <<<SQL
                    SELECT t.id_tutor, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido
                    FROM datos_personales dp, tutor t
                    WHERE t.id_datos_personales = dp.id_datos_personales
                      and t.id_rol_tutor = $idRol
                      and t.status=1
                      and t.id_tutor not in(
                          SELECT distinct(t2.id_tutor)
                          FROM tutor t2, rel_curso_tutor rct, rel_curso_grupo rcg
                          WHERE t2.id_rol_tutor = $idRol
                            and t2.status=1
                            and rct.id_tutor = t2.id_tutor
                            and rcg.id_curso_abierto = $idCursoAbierto
                            and rcg.id_rel_curso_grupo = rct.id_rel_curso_grupo
                          )
SQL;
            }
            if (isset($idRelCursoGrupo)) {
                $query->sql = <<<SQL
                    SELECT t.id_tutor, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido
                    FROM datos_personales dp, tutor t
                    WHERE t.id_datos_personales = dp.id_datos_personales
                      and t.id_rol_tutor = $idRol
                      and t.status=1
                      and t.id_tutor not in(
                          SELECT distinct(t2.id_tutor)
                          FROM tutor t2, rel_curso_tutor rct
                          WHERE t2.id_datos_personales = dp.id_datos_personales
                            and t2.id_rol_tutor = $idRol
                            and t2.status=1
                            and rct.id_tutor = t2.id_tutor
                            and rct.id_rel_curso_grupo = $idRelCursoGrupo
                          )
SQL;
            }
            break;
        case "seleccionados":
            if (isset($idCursoAbierto)) {
                $query->sql = <<<SQL
                    SELECT distinct(t.id_tutor), dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido
                    FROM datos_personales dp, tutor t, rel_curso_tutor rct, rel_curso_grupo rcg
                    WHERE t.id_datos_personales = dp.id_datos_personales
                      and t.id_rol_tutor = $idRol
                      and t.status=1
                      and rct.id_tutor = t.id_tutor
                      and rcg.id_curso_abierto = $idCursoAbierto
                      and rcg.id_rel_curso_grupo = rct.id_rel_curso_grupo
SQL;
            }
            if (isset($idRelCursoGrupo)) {
                $query->sql = <<<SQL
                    SELECT distinct(t.id_tutor), dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido
                    FROM datos_personales dp, tutor t, rel_curso_tutor rct
                    WHERE t.id_datos_personales = dp.id_datos_personales
                      and t.id_rol_tutor = $idRol
                      and t.status=1
                      and rct.id_tutor = t.id_tutor
                      and rct.id_rel_curso_grupo = $idRelCursoGrupo
SQL;
            }
            break;
    }



    $resultado = $query->select("obj");

    return $resultado;
}

/**
 * Funcion generica que genera un combo con TODOS los tutores disponibles en el sistema
 * de un rol en especifico
 * @param type $idRol rol del tutor
 */
function comboTutores($idRol, $cuales = "todos", $idCursoAbierto = NULL, $idRelCursoGrupo = NULL) {
    $tutores = getTutores($idRol, $cuales, $idCursoAbierto, $idRelCursoGrupo);
    if (!empty($tutores)) {
        foreach ($tutores as $tutor) {
            echo <<<COMBO
            <option value="$tutor->id_tutor">$tutor->nombre_pila $tutor->primer_apellido $tutor->segundo_apellido</option>        
COMBO;
        }
    }
}

?>
