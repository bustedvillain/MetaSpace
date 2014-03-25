<?php

/**
 * Funcion que consulta todos los alumnos de un grupo
 * La matriz se convierte
 * //Convertir  los datos de la forma
    arr[0]['nombreColumna'] 
    arr['correoUnico']['nombreColumna']
 * @param type $idGrupo
 * @return null
 */
function convierteConsultaMatriz($idGrupo)
{
    if(!is_numeric($idGrupo))
        return null;
    
        
    $sql = "SELECT d_p.nombre_pila,
                d_p.primer_apellido,
                d_p.segundo_apellido,
                d_p.nombre_usuario,
                d_p.correo,
                d_p.fecha_nacimiento,
                d_p.curp,
                d_p.codigo_postal,
                d_p.calle,
                d_p.no_casa_ext,
                d_p.no_casa_int,
                d_p.colonia_localidad,
                d_p.delegacion_municipio,
                na.nacionalidad,
                e_f.nombre_entidad
         FROM grupo_alumno g_a
         JOIN alumnos al
                 ON al.id_alumno = g_a.id_alumno
         JOIN datos_personales d_p
                 ON d_p.id_datos_personales = al.id_datos_personales
         JOIN nacionalidad na
                 ON na.id_nacionalidad = d_p.id_nacionalidad
         JOIN entidad_federativa e_f
                 ON e_f.id_entidad_federativa = d_p.id_entidad_federativa
         WHERE g_a.id_grupo =".$idGrupo."
         AND al.status = 1
         AND na.status = 1";
    
    
    $consultaEvaluacionDiagnostica= new Query("SG");
    $consultaEvaluacionDiagnostica->sql=$sql;
    //Realiza la Consulta
    $resultadoConsulta = $consultaEvaluacionDiagnostica->select("arr");
    //Solicita los nombres de los campos
    $resultadoNombresCampos =  $consultaEvaluacionDiagnostica->camposSelect();
    
    
    if($resultadoConsulta  && $resultadoNombresCampos)
    {
        
        $numeroRegistrosSelect = count($resultadoConsulta);
        
        $arrayForMatcheo =  array();
        for($i=0; $i < $numeroRegistrosSelect; $i++)
        {
            $correUnico = $resultadoConsulta[$i]['correo'];
            
            
            foreach ($resultadoNombresCampos as $campoSelect) 
            {
                $arrayForMatcheo[$correUnico][$campoSelect] = html_entity_decode($resultadoConsulta[$i][$campoSelect],ENT_QUOTES, "UTF-8");
            }
            
        }
        return $arrayForMatcheo;
    }
    return NULL;
}

/**
 * Función que retorna todos los alumnos de un grupo, pero con el nombre completo 
 * en una sola columna
 * @param type $idGrupo
 * @return null
 */
function convierteConsultaMatrizConNombreCompleto($idGrupo)
{
    if(!is_numeric($idGrupo))
        return null;
    
        
    $sql = "SELECT d_p.nombre_pila,
                d_p.primer_apellido,
                d_p.segundo_apellido,
                d_p.nombre_pila || ' ' || d_p.primer_apellido || ' ' || d_p.segundo_apellido as \"nombreCompleto\",
                d_p.nombre_usuario,
                d_p.correo,
                d_p.fecha_nacimiento,
                d_p.curp,
                d_p.codigo_postal,
                d_p.calle,
                d_p.no_casa_ext,
                d_p.no_casa_int,
                d_p.colonia_localidad,
                d_p.delegacion_municipio,
                na.nacionalidad,
                e_f.nombre_entidad
         FROM grupo_alumno g_a
         JOIN alumnos al
                 ON al.id_alumno = g_a.id_alumno
         JOIN datos_personales d_p
                 ON d_p.id_datos_personales = al.id_datos_personales
         JOIN nacionalidad na
                 ON na.id_nacionalidad = d_p.id_nacionalidad
         JOIN entidad_federativa e_f
                 ON e_f.id_entidad_federativa = d_p.id_entidad_federativa
         WHERE g_a.id_grupo =".$idGrupo."
         AND al.status = 1
         AND na.status = 1";
    
    $consultaEvaluacionDiagnostica= new Query("SG");
    $consultaEvaluacionDiagnostica->sql=$sql;
    //Realiza la Consulta
    $resultadoConsulta = $consultaEvaluacionDiagnostica->select("arr");
    //Solicita los nombres de los campos
    $resultadoNombresCampos =  $consultaEvaluacionDiagnostica->camposSelect();
    
  
    
    if($resultadoConsulta  && $resultadoNombresCampos)
    {
        //Convertir  los datos de la forma
        // arr[0]['nombreColumna']
        //  a
        // arr['correoUnico']['nombreColumna']
        $numeroRegistrosSelect = count($resultadoConsulta);
        
        $arrayForMatcheo =  array();
        for($i=0; $i < $numeroRegistrosSelect; $i++)
        {
            $correUnico = $resultadoConsulta[$i]['nombreCompleto'];
            
            
            foreach ($resultadoNombresCampos as $campoSelect) 
            {
                $arrayForMatcheo[$correUnico][$campoSelect] = html_entity_decode($resultadoConsulta[$i][$campoSelect],ENT_QUOTES, "UTF-8");
            }
            
        }
        return $arrayForMatcheo;
    }
    return NULL;
}

/**
 * Genera el listado de tipo de reportes
 * @return string
 */ 
function generaListadoTipoReportes()
{
    $sql = "SELECT id_tipo_reporte, 
                nombre 
            FROM tipo_reporte
            WHERE status = 1
            ORDER BY nombre ASC";
    
    $consultaTiposReportes= new Query("SG");
    $consultaTiposReportes->sql=$sql;
    $resultadoConsulta = $consultaTiposReportes->select("obj");
    $html =" ";
    
    if($resultadoConsulta)
    {
        
        foreach ($resultadoConsulta as $res) 
        {
            $html .= '<option value='.$res->id_tipo_reporte.'>'.$res->nombre.'</option>';
        }
        
        
    }
    return $html;
}

/**
 * @author  HMP
 * Funcion que verifica la extension del Archivo
 * Retorna el tipo de extension, en caso de que no sea el tipo correcto
 * de extension permitidas XLS, y XLSX retorna NULL
 * @param type $FILE
 * @return string
 */
function verificaExcel($FILE)
{
    $type = $FILE["type"];
    
    //Verificar extension
    if ($type == 'application/vnd.ms-excel') {
        // Extension excel 97
        return $ext = 'xls';
    } else if ($type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
        // Extension excel 2007 y 2010
        return $ext = 'xlsx';
    } else {
        // Extension no valida
        //echo -1;
        return NULL;  
    }
}

/**
 * Función que procesa todos los reportes 
 * @param type $FILE
 * @param type $idTipoReporte
 * @param type $idGrupo
 * @param type $idCursoAbierto
 * @param type $idUnidad
 * @param type $FILE2
 * @param type $ignoraExcel
 * @param type $fechas
 */
function procesoReporte($FILE,$idTipoReporte,$idGrupo,$idCursoAbierto,$idUnidad,$FILE2,$ignoraExcel='no',$fechas)
{
    if($ignoraExcel=='no')
        $extensionExcel=verificaExcel($FILE);
    else
        $extensionExcel = " ";
    
    
    if($extensionExcel !=null)
    { //Excel Correcto
        $filtros = determinaFiltro($idTipoReporte);
        //Se determina el tipo de Reporte
        if($filtros)
        {
            //Es alumno o Grupo
            $filtro  = $filtros[0];
            
            //tipo de reporte
            switch($filtros[1])
            {
                case 'diagnostica':
                    echo generarReporteEvaluacionDiagFinal($FILE, $extensionExcel,$filtro,$idGrupo,$idCursoAbierto,$idTipoReporte);
                    break;
                case 'final':
                    echo generarReporteEvaluacionDiagFinal($FILE, $extensionExcel,$filtro,$idGrupo,$idCursoAbierto,$idTipoReporte);
                    break;
                case 'aprovechamiento':
                    $extensionExcel2=verificaExcel($FILE2);
                    
                    echo generarReporteAprovechamiento($FILE, $extensionExcel,$filtro,$idGrupo,$FILE2,$extensionExcel2,$idCursoAbierto,$idTipoReporte);
                    break;
                case 'gestion':
                    //echo generarReporteGestion($FILE, $filtro, $idGrupo,$idCursoAbierto,$idTipoReporte);
                    generarReporteGestion2($idGrupo,$idCursoAbierto,$idTipoReporte,$fechas);
                    break;
                case 'autoevaluacion':
                    echo generarReporteAutoEvaluacion($FILE, $extensionExcel,$filtro,$idGrupo,$idCursoAbierto,$idTipoReporte);
                    break;
                case 'desempeño':
                    //requiere ID UNIDAD
                    generarReporteDesempeño($FILE, $extensionExcel, $filtro, $idGrupo,$idCursoAbierto,$idUnidad,$idTipoReporte);
                    break;
                case 'seguimiento':
                    generarReporteSeguimiento($idGrupo,$idCursoAbierto,$idTipoReporte,$fechas);
                    break;
            }
            
        }else
        {
            echo "El tipo de Reporte es incorrecto";
        }
    }
    else
    {//Archivo Incorrecto
        echo "La extension es incorrecta";
    }
}
/**
 * Funcion que determina el tipo de reporte si es grupo por alumno
 * @param type $idTipoReporte
 * @return null
 */
function determinaFiltro($idTipoReporte)
{
    if(!is_numeric($idTipoReporte))
        return NULL;
    
    $sql = "SELECT  CASE WHEN nombre LIKE '%Diagnostica%' THEN 'diagnostica'
                        WHEN nombre LIKE  '%Final%' THEN 'final'
                        WHEN nombre LIKE  '%Aprovechamiento%' THEN 'aprovechamiento'
                        WHEN nombre LIKE  '%Gesti&oacute;n Educativa%' THEN 'gestion'
                        WHEN nombre LIKE  '%Autoevaluaci&oacute;n%' THEN 'autoevaluacion'
                        WHEN nombre LIKE  '%Desempe&ntilde;o%' THEN 'desempeño'
                        WHEN nombre LIKE  '%Seguimiento API%' THEN 'seguimiento'
                        ELSE 'other'
                   END as tipo_reporte,
                       CASE WHEN nombre LIKE  '%Grupal%' THEN 'grupo'
                                WHEN nombre LIKE  '%Alumno%' THEN 'alumno'
                        ELSE 'other' END as filtro
            FROM tipo_reporte
            WHERE id_tipo_reporte = ".$idTipoReporte." AND
                  status = 1";
    
    $consultaTiposReporte= new Query("SG");
    $consultaTiposReporte->sql=$sql;
    $resultado= $consultaTiposReporte->select("arr");
    
    if($resultado)
    {
        return array($resultado[0]['filtro'], $resultado[0]['tipo_reporte']);
    }
    return NULL;
}


/**
 * Genera el reporte de Evaluación [Diagnostica|Final]
 * @param type $filtro
 */
function generarReporteEvaluacionDiagFinal($FILE,$extension,$filtro,$idGrupo,$idCursoAbierto,$idReporte)
{
    
        if(intval($FILE['error'])>0)
            return NULL;
         
        //$archivo  = uniqid().".".$extension;
        $archivo =$FILE["tmp_name"];
        $rutaPath="../../storage/reportes/";
        
        $rutaExcelNava = "../../storage/reportes/"; 
        
                $posUsername = 5;
                $posIniQ = NULL;
                $posCalif = 10;
                $arrTodo = (@generaArrFromExcel($posUsername, $posIniQ, $posCalif, $rutaExcelNava, $archivo));
//                var_dump($arrTodo);
                $arrUsernames = array_pop ($arrTodo); 
                //var_dump($arrUsernames);
                $arrExcel = array_pop($arrTodo);
                //var_dump($arrExcel);
                $datosAlumnos=convierteConsultaMatriz($idGrupo);
//                var_dump($datosAlumnos);
                if($datosAlumnos)
                {
                    $arrayMatch = @matrizFromMatcheo($arrUsernames, $arrExcel,$datosAlumnos);
                    //var_dump($arrayMatch);
                    //exit();
                    if($arrayMatch)
                    {
                        $idRelCursoGrupo=  getidRelCursoGrupo3($idCursoAbierto, $idGrupo);
                        
                        $idTipoReporte=$idReporte;
                        if(!$idRelCursoGrupo)
                        {
                            echo "Curso Abierto No existe o Grupo no Existe.<br/>";
                            flush();
                            
                        }
                            
                        
                        if($filtro == 'grupo')
                        {
                                $nombreArchivoNuevo = uniqid();
                                
                                if(@generaExcel('Reportes', $arrayMatch,$nombreArchivoNuevo, $rutaPath))
                                {
                                    echo "Excel Generado Correctamente<br/>";
                                    flush();
                                }

                                if(@generaPDF('Reportes', $arrayMatch, ",",$nombreArchivoNuevo , $rutaPath))
                                {
                                    echo "PDF Generado Correctamente<br/>";
                                    flush();
                                }

                                $decripcion="Reporte Grupal";
                                $urlDescarga="storage/reportes/".$nombreArchivoNuevo;
                                $sql = new Query('SG');
                                $sql->insert("reportes",
                                             "fecha_subida,descripcion,url_reporte,id_tipo_reporte,id_rel_curso_grupo,status",
                                             "now(),'".$decripcion."','".$urlDescarga."',".$idTipoReporte.",".$idRelCursoGrupo.",1");

                        }
                        else if($filtro == 'alumno')
                        {
                            
                            for($i=1; $i<=count($arrayMatch); $i++)
                            {
                                $matrizInd = @matrizIndividualDeMatrizota($arrayMatch, $i);
                                $alumno    = getalumnoNombre($matrizInd[1]['correo']);
                                $nombreArchivoNuevo = uniqid();
                                $urlDescarga="storage/reportes/".$nombreArchivoNuevo;
                                $decripcion = "Reporte de alumno: ".$alumno;
                                
                                if(@generaExcel('Reporte Individual', $matrizInd ,$nombreArchivoNuevo, $rutaPath))
                                {
                                    echo "Excel de alumno:".$alumno. ". Generado correctamente<br/>";
                                    flush();
                                }

                                if(@generaPDF('Reporte Individual', $matrizInd , ",",$nombreArchivoNuevo , $rutaPath))
                                {
                                    echo "PDF de alumno:".$alumno. ". Generado correctamente<br/>";
                                    flush();
                                }
                                $sql = new Query('SG');
                                $sql->insert("reportes",
                                             "fecha_subida,descripcion,url_reporte,id_tipo_reporte,id_rel_curso_grupo,status",
                                             "now(),'".$decripcion."','".$urlDescarga."',".$idTipoReporte.",".$idRelCursoGrupo.",1");

                            }
                        }
                        
                        //Parte Final del Documento
                        echo '<br/><a class="btn btn-primary"  href="verReportes.php">
                                    Ver Reportes
                                </a>';
                        flush();
                    }else
                    {
                        echo 'No se pudo realizar el reporte.<br/>
                            El reporte de Moodle no empata con Sistema de Gestión.<br/>
                            Verifica que el curso y el grupo sean los correctos.
                            <br/><a class="btn btn-primary"  href="verReportes.php">
                                    Ver Reportes
                                </a>
                            ';
                        flush();
                    }
                }
                else
                {
                     echo 'Sistema de Gestión no tiene información de este grupo .<br/>
                            No se generó el reporte.<br/><a class=\"btn btn-primary\"  href=\"verReportes.php\">
                                                               Ver Reportes
                           ';
                     flush();
                }
    
}
/**
 * Genera el listado de reportes ya generados
 * Se basa en el método obtenerIDTabla() y esAdministrador()
 */
function generarListadoReportes()
{
    if(obtenerIDTabla()==null || esAdministrador())
    {
       $from  = "FROM rel_curso_grupo  r_c_g ";
       $anexo = " "; 
    }
    else
    {
        $from ="FROM rel_curso_tutor r_c_t
            JOIN rel_curso_grupo  r_c_g
                    ON r_c_t.id_rel_curso_grupo = r_c_g.id_rel_curso_grupo";
        $anexo="WHERE rep.status = 1
            AND t_r.status = 1
            AND r_c_t.id_tutor = ".obtenerIDTabla();
    }
    
    $sqlC = "SELECT DISTINCT rep.id_rel_curso_grupo,
                            rep.id as id,
                            rep.fecha_subida as fecha,
                            rep.descripcion as desc,
                            url_reporte as url,
                            t_r.nombre as nombre,
                             gp.nombre_grupo,
			    ca.nombre_curso_abierto
            ".$from."
            JOIN reportes rep
                    ON rep.id_rel_curso_grupo =  r_c_g.id_rel_curso_grupo
            JOIN tipo_reporte t_r
                    ON t_r.id_tipo_reporte = rep.id_tipo_reporte
            JOIN grupo gp
		    ON gp.id_grupo = r_c_g.id_grupo
            JOIN cursos_abiertos ca
		   ON ca.id_curso_abierto = r_c_g.id_curso_abierto 
            ".$anexo;
    
     $sql =  new Query("SG");
     $sql->sql=$sqlC;
     
     $resultadoConsulta =  $sql->select("obj");
     
     
     if($resultadoConsulta)
     {
         $IP=IP_SERVER_PUBLIC;
         foreach ($resultadoConsulta as $reporte)
         {
           echo <<<HTML
            <tr>
             <td>$reporte->id</td>
                   
             <td>$reporte->fecha
             </td>
             <td>$reporte->nombre_curso_abierto 
             </td>
              <td>$reporte->nombre_grupo
             </td>
             <td>$reporte->nombre
             </td>
             <td>$reporte->desc</td>
             
                 <td>
                    <a class = " icon-file" href="$IP$reporte->url.xls"   role = "button" data-toggle = "modal" title = "Descargar Excel"></a>      
                 </td>
                 <td>  
                    <a class = " icon-file" href="$IP$reporte->url.pdf"   role = "button" data-toggle = "modal" title = "Descargar PDF"></a>      
                 </td>
            </tr>
HTML;
             
         }
         
     }
    
}


/**
 * Genera reporte AutoEvaluación
 * @param type $filtro
 */
function generarReporteAutoEvaluacion($FILE,$extension,$filtro,$idGrupo,$idCursoAbierto,$idReporte)
{
        if(intval($FILE['error'])>0)
         {
            return NULL;
         }
        $archivo =$FILE["tmp_name"];
        $rutaPath="../../storage/reportes/";
        
        $rutaExcelNava = "../../storage/reportes/"; 
        
           
                $posUsername = 5;
                $posIniQ = NULL;
                $posCalif = 10;
                $arrTodo = (@generaArrFromExcel($posUsername, $posIniQ, $posCalif, $rutaExcelNava, $archivo));
                $arrUsernames = array_pop ($arrTodo); 
                $arrExcel = array_pop($arrTodo);
                
                $datosAlumnos=convierteConsultaMatriz($idGrupo);
                if($datosAlumnos)
                {
                    $arrayMatch = @matrizFromMatcheo($arrUsernames, $arrExcel,$datosAlumnos);
                    if($arrayMatch)
                    {
                        
                        $idRelCursoGrupo=  getidRelCursoGrupo3($idCursoAbierto, $idGrupo);
                        
                        $idTipoReporte=$idReporte;
                        if(!$idRelCursoGrupo)
                        {
                            echo "Curso Abierto No existe o Grupo no Existe.<br/>";
                            flush();
                            
                        }
                        if($filtro == 'grupo')
                        {
                            $nombreArchivoNuevo = uniqid();
                        
                            if(generaExcel('Reportes', $arrayMatch,$nombreArchivoNuevo, $rutaPath))
                            {
                                echo "Excel Generado Correctamente.<br/>";
                                flush();
                            }

                            if(generaPDF('Reportes', $arrayMatch, ",",$nombreArchivoNuevo , $rutaPath))
                            {
                                echo "PDF Generado Correctamente.<br/>";
                                flush();
                            }
                            $decripcion="Reporte Grupal";
                       
                            $urlDescarga="storage/reportes/".$nombreArchivoNuevo;

                            $sql = new Query('SG');
                            $sql->insert("reportes",
                                         "fecha_subida,descripcion,url_reporte,id_tipo_reporte,id_rel_curso_grupo,status",
                                         "now(),'".$decripcion."','".$urlDescarga."',".$idTipoReporte.",".$idRelCursoGrupo.",1");
                        
                        }
                        else if($filtro == 'alumno')
                        {
                            for($i=1; $i<=count($arrayMatch); $i++)
                            {
                                $matrizInd = @matrizIndividualDeMatrizota($arrayMatch, $i);
                                $alumno    = getalumnoNombre($matrizInd[1]['correo']);
                                $nombreArchivoNuevo = uniqid();
                                $urlDescarga="storage/reportes/".$nombreArchivoNuevo;
                                $decripcion = "Reporte de alumno: ".$alumno;
                                
                                if(generaExcel('Reporte Individual', $matrizInd ,$nombreArchivoNuevo, $rutaPath))
                                {
                                    echo "Excel de alumno:".$alumno. ". Generado correctamente<br/>";
                                    flush();
                                }

                                if(generaPDF('Reporte Individual', $matrizInd , ",",$nombreArchivoNuevo , $rutaPath))
                                {
                                    echo "PDF de alumno:".$alumno. ". Generado correctamente<br/>";
                                    flush();
                                }
                                $sql = new Query('SG');
                                $sql->insert("reportes",
                                             "fecha_subida,descripcion,url_reporte,id_tipo_reporte,id_rel_curso_grupo,status",
                                             "now(),'".$decripcion."','".$urlDescarga."',".$idTipoReporte.",".$idRelCursoGrupo.",1");

                            }
                            
                        }
                        
                        //Parte Final del Documento
                        echo '<br/><a class="btn btn-primary"  href="verReportes.php">
                                    Ver Reportes
                                </a>';
                        flush();
                        
                    }else
                    {
                        echo 'No se pudo realizar el reporte.<br/>
                            El reporte de Moodle no empata con Sistema de Gestión.<br/>
                            Verifica que el curso y el grupo sean los correctos.
                            <br/><a class="btn btn-primary"  href="verReportes.php">
                                    Ver Reportes
                                </a>
                            ';
                        flush();
                    }
                }
                else
                {
                     echo 'Sistema de Gestión no tiene información de este grupo .<br/>
                            No se generó el reporte.<br/><a class=\"btn btn-primary\"  href=\"verReportes.php\">
                                                               Ver Reportes
                           ';
                     flush();
                }
      
}


/**
 * Genera Reporte Desempeño
 * @param type $filtro
 */
function generarReporteDesempeño($FILE,$extension,$filtro,$idGrupo,$idCursoAbierto,$idUnidad,$idReporte)
{
    
        if(intval($FILE['error'])>0)
            return NULL;
         
        $archivo =$FILE["tmp_name"];
        $rutaPath="../../storage/reportes/";
        
        $rutaExcelNava = "../../storage/reportes/"; 
        
        
          
                $posUsername = 2;
                $posIniQ = NULL;
                $posCalif = 4;
                $arrTodo = (@generaArrFromExcel($posUsername, $posIniQ, $posCalif, $rutaExcelNava, $archivo));
                
                
                array_pop ($arrTodo); 
                $arrExcel = array_pop($arrTodo);
                
                
                $matriz = matrizSeriesElementos($idGrupo,$idCursoAbierto,$idUnidad);
                
               
                $matriz2 = convierteConsultaMatriz($idGrupo);
                if(!$matriz2)
                {
                     echo "Sistema de Gestion no tiene informacion de este grupo.<br/>";
                     flush();
                     
                }
                $arrUsernames = arrUserNamesFromMatriz($matriz);
                
                $unionDatosPSeriesAER = matrizFromDosMatrices($arrUsernames, $matriz, $matriz2);
                
//                var_dump($unionDatosPSeriesAER);
//                exit();
                
                
                if($unionDatosPSeriesAER)
                {
                    $arrayMatch = @matrizFromMatcheo($arrUsernames, $arrExcel,$unionDatosPSeriesAER);
                    
                    if($arrayMatch)
                    {
                        
                        $idRelCursoGrupo=  getidRelCursoGrupo3($idCursoAbierto, $idGrupo);
                        
                        $idTipoReporte=$idReporte;
                        if(!$idRelCursoGrupo)
                        {
                            echo "Curso Abierto No existe o Grupo no Existe.<br/>";
                            flush();
                            exit();
                        }
                        
                        if($filtro == 'grupo')
                        {
                                $nombreArchivoNuevo = uniqid();
                                
                                if(generaExcel('Reportes', $arrayMatch,$nombreArchivoNuevo, $rutaPath))
                                {
                                    echo "Excel Generado Correctamente<br/>";
                                    flush();
                                }

                                if(generaPDF('Reportes', $arrayMatch, ",",$nombreArchivoNuevo , $rutaPath))
                                {
                                    echo "PDF Generado Correctamente<br/>";
                                    flush();
                                }

                                $decripcion="Reporte Grupal";
                                $urlDescarga="storage/reportes/".$nombreArchivoNuevo;
                                $sql = new Query('SG');
                                $sql->insert("reportes",
                                             "fecha_subida,descripcion,url_reporte,id_tipo_reporte,id_rel_curso_grupo,status",
                                             "now(),'".$decripcion."','".$urlDescarga."',".$idTipoReporte.",".$idRelCursoGrupo.",1");

                        }
                        else if($filtro == 'alumno')
                        {
                            for($i=1; $i<=count($arrayMatch); $i++)
                            {
                                $matrizInd = @matrizIndividualDeMatrizota($arrayMatch, $i);
                                $alumno    = getalumnoNombre($matrizInd[1]['correo']);
                                $nombreArchivoNuevo = uniqid();
                                $urlDescarga="storage/reportes/".$nombreArchivoNuevo;
                                $decripcion = "Reporte de alumno: ".$alumno;
                                
                                if(generaExcel('Reporte Individual', $matrizInd ,$nombreArchivoNuevo, $rutaPath))
                                {
                                    echo "Excel de alumno:".$alumno. ". Generado correctamente<br/>";
                                    flush();
                                }

                                if(generaPDF('Reporte Individual', $matrizInd , ",",$nombreArchivoNuevo , $rutaPath))
                                {
                                    echo "PDF de alumno:".$alumno. ". Generado correctamente<br/>";
                                    flush();
                                }
                                $sql = new Query('SG');
                                $sql->insert("reportes",
                                             "fecha_subida,descripcion,url_reporte,id_tipo_reporte,id_rel_curso_grupo,status",
                                             "now(),'".$decripcion."','".$urlDescarga."',".$idTipoReporte.",".$idRelCursoGrupo.",1");

                            }
                        }
                        
                        
                        //Parte Final del Documento
                        echo '<br/><a class="btn btn-primary"  href="verReportes.php">
                                    Ver Reportes
                                </a>';
                        flush();
                        
                    }else
                    {
                        echo 'No se pudo realizar el reporte.<br/>
                            El reporte de Moodle no empata con Sistema de Gestión.<br/>
                            Verifica que el curso y el grupo sean los correctos.
                            <br/><a class="btn btn-primary"  href="verReportes.php">
                                    Ver Reportes
                                </a>
                            ';
                        flush();
                    }
                }
                else
                {
                     echo 'No se pudo realizar el reporte.<br/>
                            El reporte de Moodle no empata con Sistema de Gestión.<br/>
                            Verifica que el curso y el grupo sean los correctos.
                            <br/><a class="btn btn-primary"  href="verReportes.php">
                                    Ver Reportes
                                </a>
                            ';
                     flush();
                }
}



/**
 * $FILE 1 y FILE 2 son de Final y Diagnostico
 * @author HMP
 * @param type $filtro
 */
function generarReporteAprovechamiento($FILE,$extension,$filtro,$idGrupo,$FILE2,$extension2,$idCursoAbierto,$idReporte)
{
        if(intval($FILE['error'])>0 || intval($FILE2['error'])>0)
         {
            return NULL;
         }
        $archivo =$FILE["tmp_name"];
        $rutaPath="../../storage/reportes/";
        
        $rutaExcelNava = "../../storage/reportes/"; 
        
        
        $archivo2 =$FILE["tmp_name"];
        $rutaExcelNava2 = "../../storage/reportes/";
        
        

                $posUsername = 5;
                $posIniQ = NULL;
                $posCalif = 10;
               
                $arrTodo = (generaArrFromDosExcel($posUsername, $posIniQ, $posCalif, $rutaExcelNava, $archivo,
                                                   $posUsername, $posIniQ, $posCalif, $rutaExcelNava2, $archivo2));               
                $arrUsernames = array_pop ($arrTodo); 
                $arrExcel = array_pop($arrTodo);
                
                $datosAlumnos=convierteConsultaMatriz($idGrupo);
                
                
                if($datosAlumnos)
                {
                    $arrayMatch = @matrizFromMatcheo($arrUsernames, $arrExcel,$datosAlumnos);
                    //var_dump($arrayMatch);
                    if($arrayMatch)
                    {
                        
                        $idRelCursoGrupo=  getidRelCursoGrupo3($idCursoAbierto, $idGrupo);
                        
                        $idTipoReporte=$idReporte;
                        if(!$idRelCursoGrupo)
                        {
                            echo "Curso Abierto No existe o Grupo no Existe.<br/>";
                            flush();    
                        }
                        if($filtro == 'grupo')
                        {
                            $nombreArchivoNuevo = uniqid();
                            if(generaExcel('Reportes', $arrayMatch,$nombreArchivoNuevo, $rutaPath))
                            {
                                echo "Excel Generado Correctamente.<br/>";
                                flush();
                            }
                            if(generaPDF('Reportes', $arrayMatch, ",",$nombreArchivoNuevo , $rutaPath))
                            {
                                echo "PDF Generado Correctamente.<br/>";
                                flush();
                            }
                            $decripcion="Reporte Grupal";

                            $urlDescarga="storage/reportes/".$nombreArchivoNuevo;

                            $sql = new Query('SG');
                            $sql->insert("reportes",
                                         "fecha_subida,descripcion,url_reporte,id_tipo_reporte,id_rel_curso_grupo,status",
                                         "now(),'".$decripcion."','".$urlDescarga."',".$idTipoReporte.",".$idRelCursoGrupo.",1");
                            
                        }
                        else if($filtro == 'alumno')
                        {
                            for($i=1; $i<=count($arrayMatch); $i++)
                            {
                                $matrizInd = @matrizIndividualDeMatrizota($arrayMatch, $i);
                                $alumno    = getalumnoNombre($matrizInd[1]['correo']);
                                $nombreArchivoNuevo = uniqid();
                                $urlDescarga="storage/reportes/".$nombreArchivoNuevo;
                                $decripcion = "Reporte de alumno: ".$alumno;
                                
                                if(generaExcel('Reporte Individual', $matrizInd ,$nombreArchivoNuevo, $rutaPath))
                                {
                                    echo "Excel de alumno:".$alumno. ". Generado correctamente<br/>";
                                    flush();
                                }

                                if(generaPDF('Reporte Individual', $matrizInd , ",",$nombreArchivoNuevo , $rutaPath))
                                {
                                    echo "PDF de alumno:".$alumno. ". Generado correctamente<br/>";
                                    flush();
                                }
                                $sql = new Query('SG');
                                $sql->insert("reportes",
                                             "fecha_subida,descripcion,url_reporte,id_tipo_reporte,id_rel_curso_grupo,status",
                                             "now(),'".$decripcion."','".$urlDescarga."',".$idTipoReporte.",".$idRelCursoGrupo.",1");

                            }
                        }
                        
                        
                        
                        //Parte Final del Documento
                        echo '<br/><a class="btn btn-primary"  href="verReportes.php">
                                    Ver Reportes
                                </a>';
                        flush();
                        
                    }else
                    {
                        echo 'No se pudo realizar el reporte.<br/>
                            El reporte de Moodle no empata con Sistema de Gestión.<br/>
                            Verifica que el curso y el grupo sean los correctos.
                            <br/><a class="btn btn-primary"  href="verReportes.php">
                                    Ver Reportes
                                </a>
                            ';
                        flush();
                    }
                }
                else
                {
                     echo 'Sistema de Gestión no tiene información de este grupo .<br/>
                            No se generó el reporte.<br/><a class=\"btn btn-primary\"  href=\"verReportes.php\">
                                                               Ver Reportes
                           ';
                     flush();
                }

}

/**
 * $FILE 1 
 * Genera Reporte Géstion
 * @author HMP
 * @param type $filtro
 */
function generarReporteGestion($FILE,$filtro,$idGrupo,$idCursoAbierto,$idReporte)
{
    
        if(intval($FILE['error']) > 0 )
         {
            return NULL;
         }
         $rutaPath="../../storage/reportes/";
         
        $arreglo = @excelToArray($FILE, NULL, 3);
            //var_dump($arreglo);
        $arrExcel = @preparaReporteGestion($arreglo,  $arreglo["nFilas"], true);
        
        $arrSG = convierteConsultaMatrizConNombreCompleto($idGrupo);
        
        if($arrSG)
        {  
                    $arrUsernames= @arrUserNamesFromMatriz($arrSG);
                
                    $matriz = @matrizFromMatcheo($arrUsernames, $arrExcel, $arrSG);
                    //var_dump($arrayMatch);
                    if($matriz)
                    {
                        
                        $idRelCursoGrupo=  getidRelCursoGrupo3($idCursoAbierto, $idGrupo);
                        
                        $idTipoReporte=$idReporte;
                        if(!$idRelCursoGrupo)
                        {
                            echo "Curso Abierto No existe o Grupo no Existe.<br/>";
                            flush();    
                        }
                        if($filtro =='grupo')
                        {
                            $nombreArchivoNuevo = uniqid();
                        
                            if(generaExcel('Reportes', $matriz,$nombreArchivoNuevo, $rutaPath))
                            {
                                echo "Excel Generado Correctamente.<br/>";
                                flush();
                            }

                            if(generaPDF('Reportes', $matriz, ",",$nombreArchivoNuevo , $rutaPath))
                            {
                                echo "PDF Generado Correctamente.<br/>";
                                flush();
                            }

                            $decripcion="Reporte Grupal";

                            if(!$idRelCursoGrupo)
                                return NULL;
                            $urlDescarga="storage/reportes/".$nombreArchivoNuevo;

                            $sql = new Query('SG');
                            $sql->insert("reportes",
                                         "fecha_subida,descripcion,url_reporte,id_tipo_reporte,id_rel_curso_grupo,status",
                                         "now(),'".$decripcion."','".$urlDescarga."',".$idTipoReporte.",".$idRelCursoGrupo.",1");

                        }
                        else if($filtro =='alumno')
                        {
                            for($i=1; $i<=count($matriz); $i++)
                            {
                                $matrizInd = @matrizIndividualDeMatrizota($matriz, $i);
                                $alumno    = getalumnoNombre($matrizInd[1]['correo']);
                                $nombreArchivoNuevo = uniqid();
                                $urlDescarga="storage/reportes/".$nombreArchivoNuevo;
                                $decripcion = "Reporte de alumno: ".$alumno;
                                
                                if(generaExcel('Reporte Individual', $matrizInd ,$nombreArchivoNuevo, $rutaPath))
                                {
                                    echo "Excel de alumno:".$alumno. ". Generado correctamente<br/>";
                                    flush();
                                }

                                if(generaPDF('Reporte Individual', $matrizInd , ",",$nombreArchivoNuevo , $rutaPath))
                                {
                                    echo "PDF de alumno:".$alumno. ". Generado correctamente<br/>";
                                    flush();
                                }
                                $sql = new Query('SG');
                                $sql->insert("reportes",
                                             "fecha_subida,descripcion,url_reporte,id_tipo_reporte,id_rel_curso_grupo,status",
                                             "now(),'".$decripcion."','".$urlDescarga."',".$idTipoReporte.",".$idRelCursoGrupo.",1");

                            }
                            
                        }
                        
                        
                        
                        //Parte Final del Documento
                        echo '<br/><a class="btn btn-primary"  href="verReportes.php">
                                    Ver Reportes
                                </a>';
                        flush();
                        
                    }
                    else
                    {
                        echo 'No se pudo realizar el reporte.<br/>
                            El reporte de Moodle no empata con Sistema de Gestión.<br/>
                            Verifica que el curso y el grupo sean los correctos.
                            <br/><a class="btn btn-primary"  href="verReportes.php">
                                    Ver Reportes
                                </a>
                            ';
                        flush();
                    }
        }
        else
         {
                     echo 'Sistema de Gestión no tiene información de este grupo .<br/>
                            No se generó el reporte.<br/><a class=\"btn btn-primary\"  href=\"verReportes.php\">
                                                               Ver Reportes
                           ';
                     flush();
         }
}
/**
 * Obtiene el IDRelCursoGrupo en base
 * a curso abierto y grupo
 * @param type $idCursoAbierto
 * @param type $idGrupo
 * @return null
 */
function getidRelCursoGrupo3($idCursoAbierto,$idGrupo)
{
    
    if(!is_numeric($idCursoAbierto) && !is_numeric($idGrupo))
        return NULL;
    
    $sqlC="SELECT id_rel_curso_grupo as id
            FROM rel_curso_grupo
            WHERE id_curso_abierto = ".$idCursoAbierto."
            AND id_grupo =".$idGrupo."
            LIMIT 1";
    
    $sql = new Query("SG");
    $sql->sql=$sqlC;
    $resultadoConsulta =$sql->select("arr");  
    if($resultadoConsulta)
    {
        return $resultadoConsulta[0]['id'];
    }
    return null;
}

/**
 * Funcion que retorna Nombre Completo del Alumno
 * en base a su correo
 * @param type $correo
 */
function getalumnoNombre($correo)
{
    $sqlC= "SELECT primer_apellido || ' '|| segundo_apellido||' '||nombre_pila as nombre
            FROM datos_personales
            WHERE correo = '".$correo."'
            LIMIT 1";
    $sql = new Query('SG');
    $sql->sql=$sqlC;
    $resultadoConsulta = $sql->select("arr");
    
    if($resultadoConsulta)
        return entidadesToAcentos($resultadoConsulta[0]['nombre']);
    return null;
    
}

/**
 * Reporte de Seguimiento
 * @param type $idGrupo
 * @param type $idCursoAbierto
 * @param type $idReporte
 */
function generarReporteSeguimiento($idGrupo,$idCursoAbierto,$idReporte,$fechas)
{
    if(!is_numeric($idGrupo) || 
       !is_numeric($idCursoAbierto) || 
       !is_numeric($idReporte)) 
            return NULL;
    if(!$fechas[0] || !$fechas[1])
        return NULL;
    
  
    //var_dump($fechas);
    $arrayConsulta = consultaDatosBitacoraProgreso($idGrupo,$idCursoAbierto,$fechas);
    //var_dump($arrayConsulta);
   // exit();
    if($arrayConsulta)
    {
        $nombreArchivoNuevo = uniqid();
        $idRelCursoGrupo=  getidRelCursoGrupo3($idCursoAbierto, $idGrupo);
        $idTipoReporte = $idReporte;
        $rutaPath="../../storage/reportes/";
        
        if (generaExcel('Reportes', $arrayConsulta, $nombreArchivoNuevo, $rutaPath)) {
            echo "Excel Generado Correctamente<br/>";
            flush();
        }

        if (generaPDF('Reportes', $arrayConsulta, ",", $nombreArchivoNuevo, $rutaPath)) {
            echo "PDF Generado Correctamente<br/>";
            flush();
        }

        $decripcion = "Reporte Seguimiento API";
        $urlDescarga = "storage/reportes/" . $nombreArchivoNuevo;
        $sql = new Query('SG');
                                $sql->insert("reportes",
                                             "fecha_subida,descripcion,url_reporte,id_tipo_reporte,id_rel_curso_grupo,status",
                                             "now(),'".$decripcion."','".$urlDescarga."',".$idTipoReporte.",".$idRelCursoGrupo.",1");
    
         //Parte Final del Documento
                        echo '<br/><a class="btn btn-primary"  href="verReportes.php">
                                    Ver Reportes
                                </a>';
                        flush();                        
                                
        }
        else
        {
            echo "No se tiene registros de Actividad.<br/>
                  No se generó el reporte.
                  <br/><a class=\"btn btn-primary\"  href=\"verReportes.php\">
                                    Ver Reportes
                                </a>
                 ";
        }
}

/**
 * Consulta todos los registros de Bitacora Progreso
 * en base IDGRUPO IDCURSOABIERTO y array Fechas
 * @param type $idGrupo
 * @param type $idCursoAbierto
 * @param type $fechas
 * @return type
 */
function consultaDatosBitacoraProgreso($idGrupo,$idCursoAbierto,$fechas)
{
    $rango1 ='00:00:00.000000';
    if($fechas[0]==$fechas[1])
    {
        
        $rango2 ='23:59:59.000000';
    }
    else
    {
        $rango2 = $rango1; 
    }
    
    
        $sql="SELECT p_a.id_alumno, 
        d_p.nombre_pila || ' '||d_p.primer_apellido||' '||d_p.segundo_apellido as nombre,
        CASE WHEN b_p.tipo=0 THEN 'entrada'
             WHEN b_p.tipo=1 THEN 'intento'
             WHEN b_p.tipo=2 THEN 'salida'
                    ELSE 'other'
        END as tipo,
        to_char(b_p.fecha, 'DD/MM/YYYY') as fecha,
        to_char(b_p.fecha, 'HH24:MI:SS') as hora,
        cu.nombre_curso,
        'serie ' || s_aer.nivel_serie_aer as serie,
        t_p.nombre_tipo elemento,

        b_p.calificacion,
        to_char(b_p.permanencia, 'HH24:MI:SS') as permanencia 
        FROM bitacora_progreso b_p
        JOIN progreso_alumno p_a
                ON p_a.id_progreso_alumno = b_p.id_progreso_alumno
        JOIN alumnos al
                ON al.id_alumno = p_a.id_alumno
        JOIN datos_personales d_p
                ON d_p.id_datos_personales = al.id_datos_personales
        JOIN elemento_aer e_aer
                ON e_aer.id_elemento_aer = p_a.id_elemento_aer
        JOIN tipo_elemento t_p
                ON t_p.id_tipo_elemento = e_aer.id_tipo_elemento
        JOIN serie_aer s_aer
                ON s_aer.id_serie_aer = e_aer.id_serie_aer
        JOIN unidades uni
                ON uni.id_unidad  = s_aer.id_unidad
        JOIN cursos cu
                on cu.id_curso = uni.id_curso
        JOIN grupo_alumno g_p
                ON g_p.id_alumno = p_a.id_alumno
        JOIN cursos_abiertos c_a
                ON c_a.id_curso = cu.id_curso
        WHERE p_a.status = 1
        AND al.status = 1
        AND e_aer.status = 1
        AND t_p.status = 1
        AND s_aer.status = 1
        AND uni.status = 1
        AND cu.status = 1
        AND c_a.status = 1
        AND g_p.id_grupo = ".$idGrupo." 
        AND c_a.id_curso_abierto = ".$idCursoAbierto."
        AND fecha >= to_timestamp('".$fechas[0]." ".$rango1."', 'DD/MM/YYYY HH24:MI:SS:MS')
        AND fecha <= to_timestamp('".$fechas[1]." ".$rango2."', 'DD/MM/YYYY HH24:MI:SS:MS')
        ORDER by hora";
        
        $consulta = new Query("SG");
        $consulta->sql = $sql;
        $resultadoConsulta = $consulta->select("obj");
        
        return $resultadoConsulta;
}
/**
 * Función que consulta todos los datos de bitacora acceso en base a idGrupo 
 * idCursoABierto y array fechas
 * @param type $idGrupo
 * @param type $idCursoAbierto
 * @param type $fechas
 * @return type
 */

function consultaDatosBitacoraAcceso($idGrupo,$idCursoAbierto,$fechas)
{
    $rango1 ='00:00:00.000000';
    if($fechas[0]==$fechas[1])
    {
        
        $rango2 ='23:59:59.000000';
    }
    else
    {
        $rango2 = $rango1; 
    }
    
    
    
        $sql="(SELECT  d_p.id_datos_personales,
                d_p.nombre_pila || ' '||d_p.primer_apellido||' '||d_p.segundo_apellido as nombre,
                CASE WHEN d_p.tipo_usuario =0 THEN 'alumno'
                     WHEN d_p.tipo_usuario =1 THEN 'tutor'
                     WHEN d_p.tipo_usuario =2 THEN 'profesor_aula'
                     WHEN d_p.tipo_usuario =3 THEN 'padre'
                     WHEN d_p.tipo_usuario =3 THEN 'gestor_contenido'
                            ELSE 'other'
                END as tipo,
                CASE WHEN b_a.tipo=0 THEN 'entrada'
                     WHEN b_a.tipo=1 THEN 'salida'
                            ELSE 'other'
                END as tipo,
                to_char(b_a.fecha, 'DD/MM/YYYY') as fecha,
                to_char(b_a.fecha, 'HH24:MI:SS') as hora,
                to_char(permanencia, 'HH24:MI:SS') as permanencia 
        FROM bitacora_acceso b_a
        JOIN datos_personales d_p
                on d_p.id_datos_personales =  b_a.id_datos_personales
        LEFT JOIN alumnos al
                ON al.id_datos_personales  = b_a.id_datos_personales
        LEFT JOIN grupo_alumno g_p
                ON g_p.id_alumno =al.id_alumno
        LEFT JOIN rel_curso_grupo r_c_g 
                ON r_c_g.id_grupo =  g_p.id_grupo
        WHERE d_p.tipo_usuario != 5
        AND  (g_p.id_grupo  =  ".$idGrupo."
        AND r_c_g.id_curso_abierto = ".$idCursoAbierto.") 
        AND fecha >= to_timestamp('".$fechas[0]." ".$rango1."', 'DD/MM/YYYY HH24:MI:SS:MS')
        AND fecha <= to_timestamp('".$fechas[1]." ".$rango2."', 'DD/MM/YYYY HH24:MI:SS:MS')) UNION 

        (SELECT  d_p.id_datos_personales,
                d_p.nombre_pila || ' '||d_p.primer_apellido||' '||d_p.segundo_apellido as nombre,
                CASE WHEN d_p.tipo_usuario =0 THEN 'alumno'
                     WHEN d_p.tipo_usuario =1 THEN 'tutor'
                     WHEN d_p.tipo_usuario =2 THEN 'profesor_aula'
                     WHEN d_p.tipo_usuario =3 THEN 'padre'
                     WHEN d_p.tipo_usuario =3 THEN 'gestor_contenido'
                            ELSE 'other'
                END as tipo,
                CASE WHEN b_a.tipo=0 THEN 'entrada'
                     WHEN b_a.tipo=1 THEN 'salida'
                            ELSE 'other'
                END as tipo,
                to_char(b_a.fecha, 'DD/MM/YYYY') as fecha,
                to_char(b_a.fecha, 'HH24:MI:SS') as hora,
                to_char(permanencia, 'HH24:MI:SS') as permanencia 
        FROM bitacora_acceso b_a
        JOIN datos_personales d_p
                on d_p.id_datos_personales =  b_a.id_datos_personales
        LEFT JOIN tutor tu
                ON tu.id_datos_personales  = b_a.id_datos_personales
        LEFT JOIN rel_curso_tutor r_c_t
                ON r_c_t.id_tutor = tu.id_tutor
        LEFT JOIN rel_curso_grupo r_c_g
                ON r_c_g.id_rel_curso_grupo = r_c_t.id_rel_curso_grupo
        WHERE d_p.tipo_usuario != 5 and d_p.tipo_usuario = 1
        AND r_c_g.id_grupo = ".$idGrupo." AND
        r_c_g.id_curso_abierto = ".$idCursoAbierto."
        AND fecha >= to_timestamp('".$fechas[0]." ".$rango1."', 'DD/MM/YYYY HH24:MI:SS:MS')
        AND fecha <= to_timestamp('".$fechas[1]." ".$rango2."', 'DD/MM/YYYY HH24:MI:SS:MS'))

        ORDER BY hora";
        
        $consulta = new Query("SG");
        $consulta->sql = $sql;
        $resultadoConsulta = $consulta->select("obj");
        
        return $resultadoConsulta;
}

/**
 * Reporte de Seguimiento
 * @param type $idGrupo
 * @param type $idCursoAbierto
 * @param type $idReporte
 */
function generarReporteGestion2($idGrupo,$idCursoAbierto,$idReporte,$fechas)
{
    if(!is_numeric($idGrupo) || 
       !is_numeric($idCursoAbierto) || 
       !is_numeric($idReporte)) 
            return NULL;
    if(!$fechas[0] || !$fechas[1])
        return NULL;
    
  
    
    $arrayConsulta = consultaDatosBitacoraAcceso($idGrupo,$idCursoAbierto,$fechas);
    
    if($arrayConsulta)
    {
        $nombreArchivoNuevo = uniqid();
        $idRelCursoGrupo=  getidRelCursoGrupo3($idCursoAbierto, $idGrupo);
        $idTipoReporte = $idReporte;
        $rutaPath="../../storage/reportes/";
        
        if (generaExcel('Reportes', $arrayConsulta, $nombreArchivoNuevo, $rutaPath)) {
            echo "Excel Generado Correctamente<br/>";
            flush();
        }

        if (generaPDF('Reportes', $arrayConsulta, ",", $nombreArchivoNuevo, $rutaPath)) {
            echo "PDF Generado Correctamente<br/>";
            flush();
        }

        $decripcion = "Reporte Gestión";
        $urlDescarga = "storage/reportes/" . $nombreArchivoNuevo;
        $sql = new Query('SG');
                                $sql->insert("reportes",
                                             "fecha_subida,descripcion,url_reporte,id_tipo_reporte,id_rel_curso_grupo,status",
                                             "now(),'".$decripcion."','".$urlDescarga."',".$idTipoReporte.",".$idRelCursoGrupo.",1");
    
         //Parte Final del Documento
                        echo '<br/><a class="btn btn-primary"  href="verReportes.php">
                                    Ver Reportes
                                </a>';
                        flush();                        
                                
        }
        else
        {
            echo "No se tiene registros de Actividad.<br/>
                  No se generó el reporte.
                 <br/><a class=\"btn btn-primary\"  href=\"verReportes.php\">
                                    Ver Reportes
                                </a>";
        }
}
/**
 * Función que determina el nombre del reporte en  base
 * al idTipoReporte
 * @param type $idTipoReporte
 * @return null
 */
function determinaNombreReporte($idTipoReporte)
{
    if(!is_numeric($idTipoReporte))
        return NULL;
    
    $sql = "SELECT  nombre
            FROM tipo_reporte
            WHERE id_tipo_reporte = ".$idTipoReporte." AND
                  status = 1";
    
    $consultaTiposReporte= new Query("SG");
    $consultaTiposReporte->sql=$sql;
    $resultado= $consultaTiposReporte->select("arr");
    
    if($resultado)
    {
        return ($resultado[0]['nombre']);
    }
    return NULL;
}

?>