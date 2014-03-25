<?php

//   Date             Modified by         Change(s)
//   2013-11-27         HMP                 1.0



//function consultaGruposAsignadosJSON($idEscuelaEmpresa)
//{
//    if(!is_numeric(obtenerIDTabla()) || !is_numeric($idEscuelaEmpresa)   )   
//        return NULL;
//    
//    $sql = "SELECT DISTINCT r_c_g.id_grupo
//            FROM  rel_curso_tutor r_c_t
//            JOIN  rel_curso_grupo r_c_g 
//                    ON r_c_g.id_rel_curso_grupo = r_c_t.id_rel_curso_tutor
//            JOIN grupo gp
//                    ON gp.id_grupo = r_c_g.id_grupo
//            WHERE  r_c_t.id_tutor = ".  obtenerIDTabla()." AND
//                   (gp.id_escuela = ".$idEscuelaEmpresa."   OR  gp.id_empresa =".$idEscuelaEmpresa." ) ";
//    
//    $consulta= new Query("SG");
//    $consulta->sql=$sql;
//    $resultado = $consulta->select("obj");
//    
//    if($resultado)
//    {
//        return json_encode($resultado);  
//    }
//    return NULL;
//    
//}
//function consultaCursosAsignados_GrupoTutorJSON( $idGrupo)
//{
//    if(!is_numeric($idGrupo))   
//        return NULL;
//    
//    $sql = "SELECT DISTINCT c_a.id_curso
//            FROM  rel_curso_tutor r_c_t
//            JOIN  rel_curso_grupo r_c_g 
//                    ON r_c_g.id_rel_curso_grupo = r_c_t.id_rel_curso_tutor
//            JOIN cursos_abiertos c_a 
//                    ON c_a.id_curso_abierto = r_c_g.id_curso_abierto
//            WHERE  r_c_t.id_tutor = ".obtenerIDTabla()." AND
//                   r_c_g.id_grupo = ".$idGrupo;
//    
//    $consulta= new Query("SG");
//    $consulta->sql=$sql;
//    $resultado = $consulta->select("obj");
//    
//    if($resultado)
//    {
//        return json_encode($resultado);  
//    }
//    return NULL;
//}


?>
