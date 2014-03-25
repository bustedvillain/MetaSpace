<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Verifica si la sesión es de un profesor
 */
function verificaSesionProfersor()
{
    if(!esProfesorAula())
        header('Location:../');
}
/**
 * Obtiene los grupos asignados a un profe
 * @return type
 */
function obtenerGruposAsignados()
{
    $sql = "SELECT DISTINCT gp.id_grupo as id,
                   nombre_grupo
            FROM alumnos al
            JOIN grupo_alumno g_a
                ON al.id_alumno = g_a.id_alumno
            JOIN grupo gp
                ON gp.id_grupo = g_a.id_grupo
            WHERE al.id_profesor_aula =".  obtenerIDTabla();
        ; 
    $consulta = new Query("SG");
    $consulta->sql=$sql;
    return $consulta->select("obj");
}
/**
 * Genera el listado de grupo de un profe
 */
function generarListadoGrupo()
{
    $arrayGrupos =  obtenerGruposAsignados();
    if($arrayGrupos)
    {
        foreach ($arrayGrupos as $grupo) {
            echo <<<HTML
            <tr>
                <td>
                <a class = "icon-tags" href="idGrupo=$grupo->id" title = "Ver Reportes"></a>
                 </td>
                <td>$grupo->nombre_grupo</td>
            </tr>
HTML;
        } 
        
    }
}

?>
