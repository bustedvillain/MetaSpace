<?php

/**
 * @author 
 * @copyright 2014
 */

class Scorm{
    /**
    * constructor q ue agrega funciones para realizar consultas a la base de datos 
    * 
    */
    
    public function __construct(){
        //require ("../sources/Funciones.php");
    }
    
    /**
    * función que devuelve un array de alumnos de acuerdo a un criterio de busqueda 
    * recibe @nombre (criterio de búsqueda)
    */
    public function buscaAlumno($idGrupo){
        $query = new Query("SG");
        $queryMoodle = new Query("MOD");
        $query->sql="SELECT ga.id_grupo_alumno, dp.id_datos_personales, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, a.id_alumno,dp.nombre_usuario,dp.correo
            FROM alumnos a, datos_personales dp, grupo_alumno ga
            WHERE a.id_datos_personales = dp.id_datos_personales
              and a.id_alumno = ga.id_alumno
              and a.status=1
              and ga.id_grupo = $idGrupo";
        $cursos = $query->select("arr");
        return $cursos;
    }
    
    /**
    * función que devuelve un array de alumnos de acuerdo a un criterio de busqueda 
    * recibe @nombre (criterio de búsqueda)
    */
    public function buscaAlumnoGestion($idGrupo){
        $query = new Query("SG");
        $queryMoodle = new Query("MOD");
        $query->sql="SELECT ga.id_grupo_alumno, dp.id_datos_personales, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, a.id_alumno,dp.nombre_usuario,dp.correo
            FROM alumnos a, datos_personales dp, grupo_alumno ga
            WHERE a.id_datos_personales = dp.id_datos_personales
              and a.id_alumno = ga.id_alumno
              and a.status=1
              and ga.id_grupo = $idGrupo";
        $cursos = $query->select("arr");
        return $cursos;
    }
    
    
    
    
    
    
    /**
    * función que devuelve un array de de grupos 
    * recibe @id (identificador del grupo)
    */
    public function desempenoGrupal($grupo){
        $query = new Query("SG");
        $queryMoodle = new Query("MOD");
        $matrizExcel= array();
        $query->sql="select id_grupo_moodle,id_curso_moodle from rel_curso_grupo cg
                        inner join cursos_abiertos ca on ca.id_curso_abierto=cg.id_curso_abierto
                        inner join cursos c on c.id_curso= ca.id_curso
                        and cg.id_grupo= $grupo";
        $group=$query->select("arr");
        $grupo_moodle=0;
        $curso_moodle=0;
        foreach($group as $res){
            $grupo_moodle=$res["id_grupo_moodle"];
            $curso_moodle=$res["id_curso_moodle"];
        }
        $queryMoodle->sql="select userid from mdl_groups_members where groupid=$grupo_moodle";
        $users=$queryMoodle->select("arr");
        $usuarios="";
        $noUsuarios=0;
        foreach($users as $resUsers){
            $usuarios.=$resUsers["userid"].",";
            $noUsuarios++;
        }
        $usuarios=substr($usuarios, 0, -1);
        $query->sql="SELECT COUNT(distinct t.id_tutor) as notutores
            FROM rel_curso_tutor rct, rel_curso_grupo rcg, grupo g, tutor t, rol_tutor rt, datos_personales dp
            WHERE g.id_grupo= $grupo
              and rct.id_tutor = t.id_tutor
              and t.id_rol_tutor = rt.id_rol_tutor
              and t.id_datos_personales = dp.id_datos_personales
              and rcg.id_grupo = g.id_grupo
              and rct.id_rel_curso_grupo = rcg.id_rel_curso_grupo";
        $result2=$query->select("arr");
        $noTutores=0;
        if($result2){
            foreach($result2 as $tut){
                $noTutores=$tut["notutores"];
            }        
        }
        $noUsuarios-=$noTutores;
        $queryMoodle->sql="select launch,m.id from mdl_grade_items gi
                                        inner join mdl_scorm s on s.course=gi.courseid
                                        inner join mdl_course_modules m on m.course=s.course
                                        inner join mdl_course_sections cs on cs.course= m.course
                                        where courseid= $curso_moodle and itemtype='mod' and gi.iteminstance= s.id and m.section= cs.id
                                        and m.instance=s.id and gi.itemname not like '' and s.name not like ''";
        $cursos = $queryMoodle->select("arr");
        $resultado="<div style='clear: both;width:100%'><table style='width:100%'><caption><h3>DESEMPEÑO</h3></caption><tr><th>Bloque</th><th>Nombre</th><th>Descripción</th><th>Promedio</th><th>Estatus</th></tr>";
        if($cursos){
            $contador=1;
            $queryScorm= new Query("MOD");
                $count=33;
                $totalUsers=0;
                $totalCompete=0;
            foreach($cursos as $scorm){
                $completado=0;
                $incompleto=0;
                $noComenzado=0;
                $cursado=0;
                $itemId=$scorm["id"];
                $launch=$scorm["launch"];
                $queryScorm->sql="select distinct m.id,finalgrade,t.element,t.value,s.name,s.intro,launch from mdl_grade_items gi 
                                    inner join mdl_grade_grades gg on gg.itemid=gi.id 
                                    inner join mdl_scorm s on s.id= gi.iteminstance 
                                    inner join mdl_course_modules m on m.course=s.course
                                    inner join mdl_scorm_scoes_track t on t.scormid= s.id 
                                    inner join mdl_course c on c.id = gi.courseid 
                                    inner join mdl_enrol e on e.courseid= c.id 
                                    inner join mdl_user_enrolments ue on ue.enrolid=e.id 
                                    inner join mdl_user u on u.id= ue.userid 
                                    and t.userid=u.id
                                    and u.id in ($usuarios) and m.id=$itemId and launch =$launch
                                    and t.element like '%cmi.core.lesson%'and gg.userid=u.id";
                $ResultSco=$queryScorm->select("arr");
                $matrizExcel[32]["Bloque"]="Bloque";
                $matrizExcel[32]["Nombre"]="Nombre";
                $matrizExcel[32]["Descripcion"]="Descripción";
                $matrizExcel[32]["Calificacion"]="Calificación";
                $matrizExcel[32]["Estatus"]="Estatus";
                    $suma=0;
                if($ResultSco){
                    $noUsers=0;
                    foreach($ResultSco as $cal){
                        switch($cal["value"]){
                            case "incomplete":
                                $incompleto++;
                                break;
                            case "completed":
                                $completado++;
                                break;
                            case "not attempted":
                                $noComenzado++;
                                break;
                        }
                        $suma+=$cal["finalgrade"];
                        $noUsers++;
                        $totalUsers++;
                    }
                    $incompletos = number_format(($incompleto * 100) / $noUsers);
                    $completo = number_format(($completado * 100) / $noUsers);
                    $noComenzados = number_format(($noComenzado * 100) / $noUsers);
                    $promedio= $suma / $noUsuarios;
                    $resultado.="<tr><td>$contador</td><td>".$cal["name"]."</td><td>".$cal["intro"]."</td><td>".number_format($promedio)."</td><td>Incompleto= $incompletos% <br>
                                    Completado = $completo% <br> No comenzado = $noComenzados%</td></tr>";
                    $matrizExcel[$count]["Bloque"]="$contador";
                    $matrizExcel[$count]["Nombre"]=$cal["name"];
                    $matrizExcel[$count]["Descripcion"]=$cal["intro"];
                    $matrizExcel[$count]["Calificacion"]=number_format($promedio);
                    $matrizExcel[$count]["Estatus"]= "Incompleto= $incompletos% \n
                                    Completado = $completo% \n No comenzado = $noComenzados%";
                }
                $totalCompete+=$completo;
                $count++;
                $contador++;
                
            }
            $totalCompete= $totalCompete/6;
            $cursados= ($totalCompete*100) / $noUsuarios;
            $resultado.="</table></div>";
            $matrizExcel[26]["avance"]="Avance";
            $matrizExcel[27]["Total Bloques"]="Total Bloques";
            $matrizExcel[27]["contador"]=$contador-1;
            $matrizExcel[28]["Cursados"]="Porcentaje de alumnos que han completado los bloques cursados";
            $matrizExcel[28]["cursado"]=$cursado;
            $avance="<div style='float:left;width:35%'><table syle='width:100%'><caption><h3>AVANCE</h3></caption>
                        <tr>
                            <td>Total de bloques: ".($contador-1)."</td>
                            <td>Porcentaje de alumnos que han completado los bloques cursados: ".number_format($totalCompete). "%</td>
                        </tr>
                    </table></div>";
        }else{
            return "no tiene actividades";
        }
        $query->sql="select nombre_categoria as \"area_conocimiento\", ne.nombre as \"nivel_educativo\", c.fecha_inicio,c.fecha_fin, nombre_curso,d.id_datos_personales as \"idUser\",c.id_curso_abierto as \"curso_abierto\",d.nombre_usuario,ge.nombre_grado,r.id_rel_curso_grupo
            from grupo_alumno g
	    join alumnos a on a.id_alumno=g.id_alumno
	    join datos_personales d on d.id_datos_personales= a.id_datos_personales
	    join grupo gr on gr.id_grupo= g.id_grupo
        join rel_curso_grupo r on r.id_grupo = g.id_grupo
        join cursos_abiertos c on c.id_curso_abierto = r.id_curso_abierto
	    join cursos cu on cu.id_curso = c.id_curso
	    join grado_escolar ge on ge.id_grado_escolar= cu.id_grado_escolar
	    join nivel_escolar ne on ne.id_nivel= ge.id_nivel
        join categorias ca on ca.id_categoria = cu.id_categoria
        where id_curso_moodle = $curso_moodle and gr.id_grupo=$grupo order by ca.id_categoria";
        $result=$query->select("arr");
        $idUser="";
        $cursoAbierto="";
        $username=array();
        $arrayIdUser=array();
        $relCursoGrupo=0;
        foreach($result as $res){
            $curso="<div style='clear: both;width: 50%;float: left'><h3>Curso</h3>";
            $idUser= $res["idUser"];
            $relCursoGrupo=$res["id_rel_curso_grupo"];
            $username[]=$res["nombre_usuario"];
            $arrayIdUser[]=$idUser;
            $cursoAbierto= $res["curso_abierto"];
            $curso.="<p>Nombre: ".$res["nombre_curso"]." </p>";
            $matrizExcel[18]["Personales"]="Curso: ";
            $matrizExcel[18]["nombre_curso"]=$res["nombre_curso"];
            $curso.="<p>Área de Conocimiento: ".$res["area_conocimiento"]."</p>";
            $matrizExcel[19]["Personales"]="Área de Conocimiento: ";
            $matrizExcel[19]["area_conocimiento"]=$res["area_conocimiento"];
            $curso.="<p>Nivel: ".$res["nivel_educativo"]."</p>";
            $matrizExcel[20]["Personales"]="Nivel: ";
            $matrizExcel[20]["nivel_educativo"]=$res["nivel_educativo"];
            $curso.="<p>Grado Escolar: ".$res["nombre_grado"]."</p>";
            $matrizExcel[21]["Personales"]="Grado Escolar: ";
            $matrizExcel[21]["nombre_grado"]=$res["nombre_grado"];
            $curso.="<p>Inicio de curso: ".$res["fecha_inicio"]."</p>";
            $matrizExcel[22]["Personales"]="Inicio de curso: ";
            $matrizExcel[22]["fecha_inicio"]=$res["fecha_inicio"];
            $curso.="<p>Fin del curso: ".$res["fecha_fin"]."</p>";
            $matrizExcel[23]["Personales"]="Fin del curso: ";
            $matrizExcel[23]["fecha_fin"]=$res["fecha_fin"];
        }        
        $curso.="</div>";
        $query->sql="SELECT distinct t.id_tutor, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, rt.nombre as rol
            FROM rel_curso_tutor rct, rel_curso_grupo rcg, grupo g, tutor t, rol_tutor rt, datos_personales dp
            WHERE rcg.id_curso_abierto= '$cursoAbierto'
              and rct.id_tutor = t.id_tutor
              and t.id_rol_tutor = rt.id_rol_tutor
              and t.id_datos_personales = dp.id_datos_personales
              and rcg.id_grupo = g.id_grupo
              and rct.id_rel_curso_grupo = rcg.id_rel_curso_grupo";
        $result2=$query->select("arr");
        $tutores="<div style='width: 30%;float: left'><h3>tutores</h3><p>";
        if($result2){
            $conteo=13;
             $matrizExcel[12]["Tutores"]="Tutores";
            foreach($result2 as $tut){
                $tutores.=$tut["rol"].": ".$tut["nombre_pila"]." ".$tut["primer_apellido"]." ".$tut["segundo_apellido"]."<br>";
                $matrizExcel[$conteo]["rol"]=$tut["rol"];
                $matrizExcel[$conteo]["Tutores"]=$tut["nombre_pila"]." ".$tut["primer_apellido"]." ".$tut["segundo_apellido"];
                $conteo++;
            }        
        }
        $tutores.="</p></div>";
        $datosUsuario= getInfoGrupo($grupo);   
        $datosPersonales="<div style='width: 35%;float: left'><h3>Grupo</h3>"; 
        $datosPersonales.="<p>Nombre del grupo: ".$datosUsuario["nombre_grupo"]."</p>";
        $matrizExcel[0][0]="Reporte de desempeño de grupo";
        $matrizExcel[1]["hola"]="Fecha de expedición: ".date('d-m-Y H:i:s');
        $datosPersonales.="<p>Clave: ".$datosUsuario["clave"]."</p>";
        $matrizExcel[2]["Personales"]="clave: ";
        $matrizExcel[2]["clave"]=$datosUsuario["clave"];
        $datosPersonales.="<p>No. de usuarios: ".$noUsuarios."</p></div>";
        $matrizExcel[3]["Personales"]="No. de usuarios: ";
        $matrizExcel[3]["no_alumnos"]=$noUsuarios;
        $datos= consultaDatosPersonales($idUser); 
        $datosPersonales.="<div style='width: 30%;float: left'><h3>Institución educativa</h3>";
        $datosPersonales.="<p>Institución: ".$datos["nombre_institucion"]."</p>";
        $matrizExcel[10]["Personales"]="Intitución educativa: ";
        $matrizExcel[10]["nombre_institucion"]=$datos["nombre_institucion"];
        $datosPersonales.="<p>Escuela: ".$datos["nombre_escuela"]."</p>";
        $matrizExcel[11]["Personales"]="Escuela: ";
        $matrizExcel[11]["nombre_escuela"]=$datos["nombre_escuela"];
        $datosPersonales.="</div>";
        $cadena="";
        $listadoAlumnos="<div style='clear: both;width:100%'><table style='width:100%'><tr><th>No.</th><th>Matrícula</th><th>Nombre</th><th>Correo</th><th>Promedio</th></tr>";
        $lista=0;
        $matrizExcel[50]["titulo"]="DETALLE DE RESULTADOS DE DESEMPEÑO";
        $matrizExcel[51]["titulo2"]="RESULTADOS POR ALUMNO";
        $matrizExcel[52]["no"]="No";
        $matrizExcel[52]["matricula"]="Matrícula";
        $matrizExcel[52]["nombre"]="Nombre";
        $matrizExcel[52]["correo"]="Correo";
        $matrizExcel[52]["promedio"]="Promedio";
        $countListado=54;
        foreach($username as $user){
            $data=consultaDatosPersonales($arrayIdUser[$lista]); 
            $lista++;
            $promedioAlumno=0;
            foreach($cursos as $scorm){
                $itemId=$scorm["id"];
                $launch=$scorm["launch"];
                $queryScorm->sql="select distinct m.id,finalgrade,t.element,t.value,s.name,s.intro,launch, u.email,u.email from mdl_grade_items gi 
                                    inner join mdl_grade_grades gg on gg.itemid=gi.id 
                                    inner join mdl_scorm s on s.id= gi.iteminstance 
                                    inner join mdl_course_modules m on m.course=s.course
                                    inner join mdl_scorm_scoes_track t on t.scormid= s.id 
                                    inner join mdl_course c on c.id = gi.courseid 
                                    inner join mdl_enrol e on e.courseid= c.id 
                                    inner join mdl_user_enrolments ue on ue.enrolid=e.id 
                                    inner join mdl_user u on u.id= ue.userid 
                                    and t.userid=u.id
                                    and u.username='$user' and m.id=$itemId and launch =$launch
                                    and t.element like '%cmi.core.lesson%' and gg.userid=u.id";
                $ResultSco=$queryScorm->select("arr");
                //echo  $queryScorm->sql."<br>";
                if($ResultSco){
                    foreach($ResultSco as $cal){
                        $promedioAlumno+=$cal["finalgrade"];
                    }
                }
                
            }
            $promedioAlumno= number_format($promedioAlumno / 6);
            $listadoAlumnos.="<tr><td>$lista</td><td>".$data["matricula"]."</td><td>".$data["nombre_pila"]." ".$data["primer_apellido"]." ".$data["segundo_apellido"]."</td><td>".$data["correo"]."</td><td>$promedioAlumno</td></tr>";
            $matrizExcel[$countListado]["no"]="$lista";
            $matrizExcel[$countListado]["matricula"]=$data["matricula"];
            $matrizExcel[$countListado]["nombre"]=$data["nombre_pila"]." ".$data["primer_apellido"]." ".$data["segundo_apellido"];
            $matrizExcel[$countListado]["correo"]=$data["correo"];
            $matrizExcel[$countListado]["promedio"]=$promedioAlumno;
            $countListado++;
        }
        $listadoAlumnos.="</table></div>";
        

        $cadena.=$datosPersonales."".$tutores."".$avance."".$curso."".$resultado."".$listadoAlumnos."";
        require_once("../sources/dompdf/dompdf_config.inc.php"); 
        $html =
          '<html><body><p align="right">Fecha de expedición: '.date("d-m-Y H:i:s").'</p>'.$cadena.'</body></html>';
        $dompdf = new DOMPDF();
        $dompdf->set_paper("letter", "portrait");
        $dompdf->load_html($html);
        $dompdf->render();
        $archivo=uniqid();
        $filename="/var/www/html/storage/reportes/".$archivo.'.pdf';
        $urlDescarga=BASE_STORAGE."reportes/".$archivo;
        $filenamePDF = BASE_STORAGE."reportes/".$archivo.'.pdf';
        $filenameXLS = BASE_STORAGE."reportes/".$archivo.'.xls';
        file_put_contents($filename, $dompdf->output());
        ksort($matrizExcel);
        /*$dompdf->stream($filename);       */ 
        //$dompdf->stream("/var/www/html/storage/reportes/".uniqid().".pdf");
        generaExcel("Repoerte Scorm",$matrizExcel,$archivo,"/var/www/html/storage/reportes/");
        $botones="<p><a id='toPDF' href='".$filenamePDF."' target='_blank'>Generar PDF</a><a id='toXLS' href='".$filenameXLS."' target='_blank'>Generar Excel</a></p>
                    <p align='right'>Fecha de expedición: ".date('d-m-Y H:i:s')."</p>";
        $decripcion="Reporte de desempeño del grupo";
        $sql= new Query("SG");
        $sql->insert("reportes_scorm","fecha_subida,descripcion,url_reporte,id_tipo_reporte_scorm,id_rel_curso_grupo,status","now(),'".$decripcion."','".$urlDescarga."',2,".$relCursoGrupo.",1");        
        return $botones." ".$cadena;
    }
    
    
    
    
    
    /**
    * función que devuelve un array de de grupos para gestión
    * recibe @nombre (identificador del grupo)
    */
    public function desempenoGrupalGestion($grupo){
        $query = new Query("SG");
        $queryMoodle = new Query("MOD");
        $matrizExcel= array();
        $query->sql="select id_grupo_moodle,id_curso_moodle from rel_curso_grupo cg
                        inner join cursos_abiertos ca on ca.id_curso_abierto=cg.id_curso_abierto
                        inner join cursos c on c.id_curso= ca.id_curso
                        and cg.id_grupo= $grupo";
        $group=$query->select("arr");
        $grupo_moodle=0;
        $curso_moodle=0;
        foreach($group as $res){
            $grupo_moodle=$res["id_grupo_moodle"];
            $curso_moodle=$res["id_curso_moodle"];
        }
        $queryMoodle->sql="select userid from mdl_groups_members where groupid=$grupo_moodle";
        $users=$queryMoodle->select("arr");
        $usuarios="";
        $noUsuarios=0;
        foreach($users as $resUsers){
            $usuarios.=$resUsers["userid"].",";
            $noUsuarios++;
        }
        $usuarios=substr($usuarios, 0, -1);
        $query->sql="SELECT COUNT(distinct t.id_tutor) as notutores
            FROM rel_curso_tutor rct, rel_curso_grupo rcg, grupo g, tutor t, rol_tutor rt, datos_personales dp
            WHERE g.id_grupo= $grupo
              and rct.id_tutor = t.id_tutor
              and t.id_rol_tutor = rt.id_rol_tutor
              and t.id_datos_personales = dp.id_datos_personales
              and rcg.id_grupo = g.id_grupo
              and rct.id_rel_curso_grupo = rcg.id_rel_curso_grupo";
        $result2=$query->select("arr");
        $noTutores=0;
        if($result2){
            foreach($result2 as $tut){
                $noTutores=$tut["notutores"];
            }        
        }
        $noUsuarios-=$noTutores;
        $queryMoodle->sql="select launch,m.id from mdl_grade_items gi
                                        inner join mdl_scorm s on s.course=gi.courseid
                                        inner join mdl_course_modules m on m.course=s.course
                                        inner join mdl_course_sections cs on cs.course= m.course
                                        where courseid= $curso_moodle and itemtype='mod' and gi.iteminstance= s.id and m.section= cs.id
                                        and m.instance=s.id and gi.itemname not like '' and s.name not like ''";
        $cursos = $queryMoodle->select("arr");
        $resultado="<div style='clear: both;width:100%'><table style='width:100%'><caption><h3>Gestión educativa promedio del grupo</h3></caption><tr><th>Bloque</th><th>Nombre</th><th>Fecha de Inicio Promedio</th><th>Tiempo Total Promedio</th><th>Promedio de Intentos</th><th>Promedio de Estatus en Bloques</th></tr>";
        if($cursos){
            $contador=1;
            $queryScorm= new Query("MOD");
                $count=33;
                $totalUsers=0;
                $totalCompete=0;
            foreach($cursos as $scorm){
                $completado=0;
                $incompleto=0;
                $noComenzado=0;
                $cursado=0;
                $itemId=$scorm["id"];
                $launch=$scorm["launch"];
                $queryScorm->sql="select distinct m.id,t.element,t.value,s.name,s.intro,launch,attempt,t.timemodified from mdl_grade_items gi 
                                    inner join mdl_grade_grades gg on gg.itemid=gi.id 
                                    inner join mdl_scorm s on s.id= gi.iteminstance 
                                    inner join mdl_course_modules m on m.course=s.course
                                    inner join mdl_scorm_scoes_track t on t.scormid= s.id 
                                    inner join mdl_course c on c.id = gi.courseid 
                                    inner join mdl_enrol e on e.courseid= c.id 
                                    inner join mdl_user_enrolments ue on ue.enrolid=e.id 
                                    inner join mdl_user u on u.id= ue.userid 
                                    and t.userid= u.id
                                    and u.id in ($usuarios) and m.id=$itemId and launch =$launch
                                    and t.element not like '%cmi.suspend_data%'
                                    and t.element not like '%cmi.core.score.raw%'";
                $ResultSco=$queryScorm->select("arr");
                $matrizExcel[32]["Bloque"]="Bloque";
                $matrizExcel[32]["Nombre"]="Nombre";
                $matrizExcel[32]["fecha_inicio"]="Fecha de inicio";
                $matrizExcel[32]["tiempo"]="tiempo";
                $matrizExcel[32]["intento"]="Intento";
                $matrizExcel[32]["Estatus"]="Estatus";
                if($ResultSco){
                    $intentos=0;
                    $noUsers=0;
                    $sumaIntentos=0;
                    $sumaTiempoTotal="00:00:00";
                    $sumaFechas=0;
                    $part="";
                    $part2="";
                    $part3="";
                    $time=0;
                    $hour=0;
                    foreach($ResultSco as $cal){
                        $nombre=$cal["name"];
                        if($cal["element"]=="x.start.time"){
                            $sumaFechas+= strtotime(date('Y-m-d h:i:s',$cal["value"]));
                            $time ++;
                        }else if($cal["element"]=="cmi.core.total_time"){
                            //$sumaTiempoTotal=strtotime("+".$cal["value"]."",$sumaTiempoTotal);
                            $sumaTiempoTotal= self::CalcularHoras($sumaTiempoTotal,substr($cal["value"],0,-2));
                            //echo $sumaTiempoTotal."<br>";
                            $hour++;
                        }else{
                            switch($cal["value"]){
                                case "incomplete":
                                    $incompleto++;
                                    break;
                                case "completed":
                                    $completado++;
                                    break;
                                case "not attempted":
                                    $noComenzado++;
                                    break;
                            }
                            $estatus_mat=$cal["value"];
                            $estatus="<td>".$cal["value"]."</td>";
                            $noUsers++;
                        }
                        $sumaIntentos+=$cal["attempt"];
                        $intentos++;
                        //$resultado.="<tr><td>$contador</td><td>".$cal["name"]."</td><td>".$cal["intro"]."</td><td>".$cal["finalgrade"]."</td><td>".$cal["value"]."</td></tr>";
                    }
                    $incompletos = number_format(($incompleto * 100) / $noUsers);
                    $completo = number_format(($completado * 100) / $noUsers);
                    $noComenzados = number_format(($noComenzado * 100) / $noUsers);
                    $promedio= number_format($suma / $noUsuarios);
                    $sumaIntentos= number_format($sumaIntentos / $intentos);
                    $sumaFechas= date('Y-m-d h:i:s',$sumaFechas/$time);
                    //$sumaTiempoTotal=  $sumaTiempoTotal / $hour;
                    $sumaTiempoTotal= self::horasSegundos($sumaTiempoTotal);
                    $sumaTiempoTotal= self::segundosHoras($sumaTiempoTotal,$hour);
                    
                    $resultado.="<tr><td>$contador</td><td>$nombre</td><td>$sumaFechas</td><td>$sumaTiempoTotal</td><td>$sumaIntentos</td><td>Incompleto= $incompletos% <br>
                                    Completado = $completo% <br> No comenzado = $noComenzados%</td></tr>";
                    $part="<tr><td>$contador</td><td>".$cal["name"]."</td>";
                    $part2="<td>".date('Y-m-d h:i:s',$cal["timemodified"])."</td>";
                    $part3="<td>".$cal["attempt"]."</td>";
                    $matrizExcel[$count]["Bloque"]="$contador";
                    $matrizExcel[$count]["Nombre"]=$nombre;
                    $matrizExcel[$count]["fecha_inicio"]=$fecha_inicio;
                    $matrizExcel[$count]["tiempo"]=$tiempo;
                    $matrizExcel[$count]["intento"]=$intento;
                    $matrizExcel[$count]["Estatus"]="Incompleto= $incompletos% \n
                                    Completado = $completo% \n No comenzado = $noComenzados%";
                }
                $totalCompete+=$completo;
                $count++;
                $contador++;
                
            }
            $totalCompete= $totalCompete/6;
            $cursados= ($totalCompete*100) / $noUsuarios;
            $resultado.="</table></div>";
            $matrizExcel[26]["avance"]="Avance";
            $matrizExcel[27]["Total Bloques"]="Total Bloques";
            $matrizExcel[27]["contador"]=$contador-1;
            $matrizExcel[28]["Cursados"]="Cursados";
            $matrizExcel[28]["cursado"]=$cursado;
            $matrizExcel[27]["Completados"]="Completados";
            $matrizExcel[27]["completado"]=$completado;
            $matrizExcel[28]["Incompletos"]="Incompletos";            
            $matrizExcel[28]["incompleto"]=$incompleto;
            $matrizExcel[29]["No Comenzados"]="No Comenzados";            
            $matrizExcel[29]["noComenzado"]=$noComenzado;            
            $avance="<div style='float:left;width:35%'><table syle='width:100%'><caption><h3>AVANCE</h3></caption>
                        <tr>
                            <td>Total de bloques: ".($contador-1)."</td>
                            <td>Porcentaje de alumnos que han completado los bloques cursados: ".number_format($totalCompete)."%</td>
                        </tr>
                    </table></div>";
        }else{
            return "no tiene actividades";
        }
        $query->sql="select nombre_categoria as \"area_conocimiento\", ne.nombre as \"nivel_educativo\", gr.nombre_grupo, c.fecha_inicio,c.fecha_fin, nombre_curso,d.id_datos_personales as \"idUser\",c.id_curso_abierto as \"curso_abierto\", d.nombre_usuario,ge.nombre_grado,r.id_rel_curso_grupo
            from grupo_alumno g
	    join alumnos a on a.id_alumno=g.id_alumno
	    join datos_personales d on d.id_datos_personales= a.id_datos_personales
	    join grupo gr on gr.id_grupo= g.id_grupo
        join rel_curso_grupo r on r.id_grupo = g.id_grupo
        join cursos_abiertos c on c.id_curso_abierto = r.id_curso_abierto
	    join cursos cu on cu.id_curso = c.id_curso
	    join grado_escolar ge on ge.id_grado_escolar= cu.id_grado_escolar
	    join nivel_escolar ne on ne.id_nivel= ge.id_nivel
        join categorias ca on ca.id_categoria = cu.id_categoria
        where id_curso_moodle = $curso_moodle and gr.id_grupo=$grupo order by ca.id_categoria";
        $result=$query->select("arr");
        $idUser="";
        $cursoAbierto="";
        $username=array();
        $arrayIdUser=array();
        $relCursoGrupo=0;
        foreach($result as $res){
            $curso="<div style='clear: both;width: 50%;float: left'><h3>Curso</h3>";
            $idUser= $res["idUser"];
            $relCursoGrupo=$res["id_rel_curso_grupo"];
            $username[]=$res["nombre_usuario"];
            $arrayIdUser[]=$idUser;
            $cursoAbierto= $res["curso_abierto"];
            $curso.="<p>Nombre: ".$res["nombre_curso"]." </p>";
            $matrizExcel[18]["Personales"]="Curso: ";
            $matrizExcel[18]["nombre_curso"]=$res["nombre_curso"];
            $curso.="<p>Área de Conocimiento: ".$res["area_conocimiento"]."</p>";
            $matrizExcel[19]["Personales"]="Área de Conocimiento: ";
            $matrizExcel[19]["area_conocimiento"]=$res["area_conocimiento"];
            $curso.="<p>Nivel: ".$res["nivel_educativo"]."</p>";
            $matrizExcel[20]["Personales"]="Nivel: ";
            $matrizExcel[20]["nivel_educativo"]=$res["nivel_educativo"];
            $curso.="<p>Grado Escolar: ".$res["nombre_grado"]."</p>";
            $matrizExcel[21]["Personales"]="Grado Escolar: ";
            $matrizExcel[21]["nombre_grado"]=$res["nombre_grado"];
            $curso.="<p>Inicio de curso: ".$res["fecha_inicio"]."</p>";
            $matrizExcel[22]["Personales"]="Inicio de curso: ";
            $matrizExcel[22]["fecha_inicio"]=$res["fecha_inicio"];
            $curso.="<p>Fin del curso: ".$res["fecha_fin"]."</p>";
            $matrizExcel[23]["Personales"]="Fin del curso: ";
            $matrizExcel[23]["fecha_fin"]=$res["fecha_fin"];
        }        
        $curso.="</div>";
        $query->sql="SELECT distinct t.id_tutor, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, rt.nombre as rol
            FROM rel_curso_tutor rct, rel_curso_grupo rcg, grupo g, tutor t, rol_tutor rt, datos_personales dp
            WHERE rcg.id_curso_abierto= '$cursoAbierto'
              and rct.id_tutor = t.id_tutor
              and t.id_rol_tutor = rt.id_rol_tutor
              and t.id_datos_personales = dp.id_datos_personales
              and rcg.id_grupo = g.id_grupo
              and rct.id_rel_curso_grupo = rcg.id_rel_curso_grupo";
        $result2=$query->select("arr");
        $tutores="<div style='width: 30%;float: left'><h3>tutores</h3><p>";
        if($result2){
            $conteo=13;
             $matrizExcel[12]["Tutores"]="Tutores";
            foreach($result2 as $tut){
                $tutores.=$tut["rol"].": ".$tut["nombre_pila"]." ".$tut["primer_apellido"]." ".$tut["segundo_apellido"]."<br>";
                $matrizExcel[$conteo]["rol"]=$tut["rol"];
                $matrizExcel[$conteo]["Tutores"]=$tut["nombre_pila"]." ".$tut["primer_apellido"]." ".$tut["segundo_apellido"];
                $conteo++;
            }        
        }
        $tutores.="</p></div>";
        $datosUsuario= getInfoGrupo($grupo);   
        $datosPersonales="<div style='width: 35%;float: left'><h3>Grupo</h3>"; 
        $datosPersonales.="<p>Nombre del grupo: ".$datosUsuario["nombre_grupo"]."</p>";
        $matrizExcel[0][0]="Reporte de desempeño de grupo";
        $matrizExcel[1]["hola"]="Fecha de expedición: ".date('d-m-Y H:i:s');
        $datosPersonales.="<p>Clave: ".$datosUsuario["clave"]."</p>";
        $matrizExcel[2]["Personales"]="clave: ";
        $matrizExcel[2]["clave"]=$datosUsuario["clave"];
        $datosPersonales.="<p>No. de usuarios: ".$noUsuarios."</p></div>";
        $matrizExcel[3]["Personales"]="No. de usuarios: ";
        $matrizExcel[3]["no_alumnos"]=$noUsuarios;
        $datos= consultaDatosPersonales($idUser); 
        $datosPersonales.="<div style='width: 30%;float: left'><h3>Institución educativa</h3>";
        $datosPersonales.="<p>Institución: ".$datos["nombre_institucion"]."</p>";
        $matrizExcel[10]["Personales"]="Intitución educativa: ";
        $matrizExcel[10]["nombre_institucion"]=$datos["nombre_institucion"];
        $datosPersonales.="<p>Escuela: ".$datos["nombre_escuela"]."</p>";
        $matrizExcel[11]["Personales"]="Escuela: ";
        $matrizExcel[11]["nombre_escuela"]=$datos["nombre_escuela"];
        $datosPersonales.="</div>";
        $cadena="";
        $listadoAlumnos="<div style='clear: both;width:100%'><table style='width:100%'><tr><th>No.</th><th>Matrícula</th><th>Nombre</th><th>Correo</th><th>Promedio</th></tr>";
        $lista=0;
        $matrizExcel[50]["titulo"]="DETALLE DE RESULTADOS DE DESEMPEÑO";
        $matrizExcel[51]["titulo2"]="RESULTADOS POR ALUMNO";
        $matrizExcel[52]["no"]="No";
        $matrizExcel[52]["matricula"]="Matrícula";
        $matrizExcel[52]["nombre"]="Nombre";
        $matrizExcel[52]["correo"]="Correo";
        $matrizExcel[52]["promedio"]="Promedio";
        $countListado=54;
        foreach($username as $user){
            $data=consultaDatosPersonales($arrayIdUser[$lista]); 
            $lista++;
            $promedioAlumno=0;
            foreach($cursos as $scorm){
                $itemId=$scorm["id"];
                $launch=$scorm["launch"];
                $queryScorm->sql="select distinct m.id,finalgrade,t.element,t.value,s.name,s.intro,launch, u.email,u.email from mdl_grade_items gi 
                                    inner join mdl_grade_grades gg on gg.itemid=gi.id 
                                    inner join mdl_scorm s on s.id= gi.iteminstance 
                                    inner join mdl_course_modules m on m.course=s.course
                                    inner join mdl_scorm_scoes_track t on t.scormid= s.id 
                                    inner join mdl_course c on c.id = gi.courseid 
                                    inner join mdl_enrol e on e.courseid= c.id 
                                    inner join mdl_user_enrolments ue on ue.enrolid=e.id 
                                    inner join mdl_user u on u.id= ue.userid 
                                    and t.userid=u.id
                                    and u.username='$user' and m.id=$itemId and launch =$launch
                                    and t.element like '%cmi.core.lesson%' and gg.userid=u.id";
                $ResultSco=$queryScorm->select("arr");
                //echo  $queryScorm->sql."<br>";
                if($ResultSco){
                    foreach($ResultSco as $cal){
                        $promedioAlumno+=$cal["finalgrade"];
                    }
                }
                
            }
            
            $query->sql="";
            $promedioAlumno= number_format($promedioAlumno / 6);
            $listadoAlumnos.="<tr><td>$lista</td><td>".$data["matricula"]."</td><td>".$data["nombre_pila"]." ".$data["primer_apellido"]." ".$data["segundo_apellido"]."</td><td>".$data["correo"]."</td><td>$promedioAlumno</td></tr>";
            $matrizExcel[$countListado]["no"]="$lista";
            $matrizExcel[$countListado]["matricula"]=$data["matricula"];
            $matrizExcel[$countListado]["nombre"]=$data["nombre_pila"]." ".$data["primer_apellido"]." ".$data["segundo_apellido"];
            $matrizExcel[$countListado]["correo"]=$data["correo"];
            $matrizExcel[$countListado]["promedio"]=$promedioAlumno;
            $countListado++;
        }
        $listadoAlumnos.="</table></div>";
        

        $cadena.=$datosPersonales."".$tutores."".$avance."".$curso."".$resultado."".$listadoAlumnos."";
        require_once("../sources/dompdf/dompdf_config.inc.php"); 
        $html =
          '<html><body><p align="right">Fecha de expedición: '.date("d-m-Y H:i:s").'</p>'.$cadena.'</body></html>';
        $dompdf = new DOMPDF();
        $dompdf->set_paper("letter", "portrait");
        $dompdf->load_html($html);
        $dompdf->render();
        $archivo=uniqid();
        $filename="/var/www/html/storage/reportes/".$archivo.'.pdf';
        $urlDescarga=BASE_STORAGE."reportes/".$archivo;
        $filenamePDF = BASE_STORAGE."reportes/".$archivo.'.pdf';
        $filenameXLS = BASE_STORAGE."reportes/".$archivo.'.xls';
        file_put_contents($filename, $dompdf->output());
        ksort($matrizExcel);
        /*$dompdf->stream($filename);       */ 
        //$dompdf->stream("/var/www/html/storage/reportes/".uniqid().".pdf");
        generaExcel("Repoerte Scorm",$matrizExcel,$archivo,"/var/www/html/storage/reportes/");
        $botones="<p><a id='toPDF' href='".$filenamePDF."' target='_blank'>Generar PDF</a><a id='toXLS' href='".$filenameXLS."' target='_blank'>Generar Excel</a></p>
                    <p align='right'>Fecha de expedición: ".date('d-m-Y H:i:s')."</p>";
        $decripcion="Reporte de gestión del grupo";
        $sql= new Query("SG");
        $sql->insert("reportes_scorm","fecha_subida,descripcion,url_reporte,id_tipo_reporte_scorm,id_rel_curso_grupo,status","now(),'".$decripcion."','".$urlDescarga."',4,".$relCursoGrupo.",1");        
        return $botones." ".$cadena;
    }
    

    public function CalcularHoras($hora_ingreso,$jornal) {         
        //$jornal ="8:30"; 
        $hora_ingreso=split(":",$hora_ingreso); 
        $jornal=split(":",$jornal); 
        $horas=(int)$hora_ingreso[0]+(int)$jornal[0]; 
        $minutos=(int)$hora_ingreso[1]+(int)$jornal[1]; 
        $horas+=(int)($minutos/60); 
        $minutos=$minutos%60; 
        if($minutos<10)$minutos="0".$minutos ; 
        return $hora_salida = $horas.":".$minutos.":".$hora_ingreso[2]; 
        } 
        
        
    public function horasSegundos($hora){
        list($horas, $minutos, $segundos) = explode(':', $hora);
        $hora_en_segundos = ($horas * 3600 ) + ($minutos * 60 ) + $segundos;
        return $hora_en_segundos;  
    }

    public function segundosHoras($segundos, $us){
        $segundos= $segundos / $us;
        $horas = floor($segundos/3600); 
        $minutos = floor(($segundos-($horas*3600))/60); 
        $segundos = $segundos-($horas*3600)-($minutos*60); 
        return $horas.':'.$minutos.':'.$segundos; 
    }
    /**
    * función que devuelve reporte de desempeño alumno 
    * recibe @user (strin que contiene user y correo de moodle)
    * @id (id del amuno)
    */
    
    public function reporteAlumno($id,$user, $curso){
        $query = new Query("SG");
        $queryMoodle = new Query("MOD");
        $matrizExcel= array();
        $queryMoodle->sql="select launch,m.id from mdl_grade_items gi
                                        inner join mdl_scorm s on s.course=gi.courseid
                                        inner join mdl_course_modules m on m.course=s.course
                                        inner join mdl_course_sections cs on cs.course= m.course
                                        where courseid= $curso and itemtype='mod' and gi.iteminstance= s.id and m.section= cs.id
                                        and m.instance=s.id and gi.itemname not like '' and s.name not like '' order by cs.section";
        $cursos = $queryMoodle->select("arr");
        $resultado="<div style='clear:both; width:100%'><table style='width:100%'><caption><h3>DESEMPEÑO</h3></caption><tr><th>Bloque</th><th>Nombre</th><th>Descripción</th><th>Calificación</th><th>Estatus</th></tr>";
        if($cursos){
            $contador=0;
            $queryScorm= new Query("MOD");
                $completado=0;
                $incompleto=0;
                $noComenzado=0;
                $cursado=0;
                $count=33;
            foreach($cursos as $scorm){
                $contador++;
                $itemId=$scorm["id"];
                $launch=$scorm["launch"];
                $queryScorm->sql="select distinct m.id,finalgrade,t.element,t.value,s.name,s.intro,launch from mdl_grade_items gi 
                                    inner join mdl_grade_grades gg on gg.itemid=gi.id 
                                    inner join mdl_scorm s on s.id= gi.iteminstance 
                                    inner join mdl_course_modules m on m.course=s.course
                                    inner join mdl_scorm_scoes_track t on t.scormid= s.id 
                                    inner join mdl_course c on c.id = gi.courseid 
                                    inner join mdl_enrol e on e.courseid= c.id 
                                    inner join mdl_user_enrolments ue on ue.enrolid=e.id 
                                    inner join mdl_user u on u.id= ue.userid 
                                    and t.userid=u.id
                                    and u.username='$user' and u.email='$id' and m.id=$itemId and launch =$launch
                                    and t.element like '%cmi.core.lesson%' and gg.userid=u.id";
                $ResultSco=$queryScorm->select("arr");
                $matrizExcel[32]["Bloque"]="Bloque";
                $matrizExcel[32]["Nombre"]="Nombre";
                $matrizExcel[32]["Descripcion"]="Descripción";
                $matrizExcel[32]["Calificacion"]="Calificación";
                $matrizExcel[32]["Estatus"]="Estatus";
                if($ResultSco){
                    foreach($ResultSco as $cal){
                        switch($cal["value"]){
                            case "incomplete":
                                $incompleto++;
                                break;
                            case "completed":
                                $completado++;
                                break;
                            case "not attempted":
                                $noComenzado++;
                                break;
                        }
                        $resultado.="<tr><td>$contador</td><td>".$cal["name"]."</td><td>".$cal["intro"]."</td><td>".$cal["finalgrade"]."</td><td>".$cal["value"]."</td></tr>";
                        $matrizExcel[$count]["Bloque"]="$contador";
                        $matrizExcel[$count]["Nombre"]=$cal["name"];
                        $matrizExcel[$count]["Descripcion"]=$cal["intro"];
                        $matrizExcel[$count]["Calificacion"]=$cal["finalgrade"];
                        $matrizExcel[$count]["Estatus"]=$cal["value"];
                        
                    }
                }else{
                    
                    $queryScorm->sql="select distinct m.id,t.element,t.value,s.name,s.intro,launch from mdl_grade_items gi 
                                        inner join mdl_scorm s on s.id= gi.iteminstance 
                                        inner join mdl_course_modules m on m.course=s.course
                                        inner join mdl_scorm_scoes_track t on t.scormid= s.id 
                                        inner join mdl_course c on c.id = gi.courseid 
                                        inner join mdl_enrol e on e.courseid= c.id 
                                        inner join mdl_user_enrolments ue on ue.enrolid=e.id 
                                        inner join mdl_user u on u.id= ue.userid 
                                        and t.userid=u.id
                                        and u.username='$user' and u.email='$id' and m.id=$itemId and launch =$launch
                                        and t.element like '%cmi.core.lesson%'";
                    $ResultSco=$queryScorm->select("arr");
                    $matrizExcel[32]["Bloque"]="Bloque";
                    $matrizExcel[32]["Nombre"]="Nombre";
                    $matrizExcel[32]["Descripcion"]="Descripción";
                    $matrizExcel[32]["Calificacion"]="Calificación";
                    $matrizExcel[32]["Estatus"]="Estatus";
                    if($ResultSco){
                        foreach($ResultSco as $cal){
                            switch($cal["value"]){
                                case "incomplete":
                                    $incompleto++;
                                    break;
                                case "completed":
                                    $completado++;
                                    break;
                                case "not attempted":
                                    $noComenzado++;
                                    break;
                            }
                            $resultado.="<tr><td>$contador</td><td>".$cal["name"]."</td><td>".$cal["intro"]."</td><td>0</td><td>".$cal["value"]."</td></tr>";
                            $matrizExcel[$count]["Bloque"]="$contador";
                            $matrizExcel[$count]["Nombre"]=$cal["name"];
                            $matrizExcel[$count]["Descripcion"]=$cal["intro"];
                            $matrizExcel[$count]["Calificacion"]=0;
                            $matrizExcel[$count]["Estatus"]=$cal["value"];
                            
                        }
                    }else{
                        $queryScorm->sql="select distinct m.id,s.name,s.intro,launch from mdl_grade_items gi 
                            inner join mdl_scorm s on s.id= gi.iteminstance 
                            inner join mdl_course_modules m on m.course=s.course 
                            inner join mdl_course c on c.id = gi.courseid 
                            inner join mdl_enrol e on e.courseid= c.id 
                            inner join mdl_user_enrolments ue on ue.enrolid=e.id 
                            inner join mdl_user u on u.id= ue.userid 
                                            and u.username='$user' and u.email='$id' and m.id=$itemId and launch =$launch";
                        $ResultSco=$queryScorm->select("arr");
                        foreach($ResultSco as $cal){
                            $resultado.="<tr><td>$contador</td><td>".$cal["name"]."</td><td>".$cal["intro"]."</td><td>0</td><td>not attemped</td></tr>";
                            $matrizExcel[$count]["Bloque"]="$contador";
                            $matrizExcel[$count]["Nombre"]=$cal["name"];
                            $matrizExcel[$count]["Descripcion"]=$cal["intro"];
                            $matrizExcel[$count]["Calificacion"]=$cal["finalgrade"];
                            $matrizExcel[$count]["Estatus"]=$cal["value"];
                        }
                    }                    
                }
                $count++;
            }
            $cursados= $completado + $incompleto;
            $resultado.="</table></div>";
            $matrizExcel[26]["avance"]="Avance";
            $matrizExcel[27]["Total Bloques"]="Total Bloques";
            $matrizExcel[27]["contador"]="$contador";
            $matrizExcel[28]["Cursados"]="Cursados";
            $matrizExcel[28]["cursado"]=$cursado;
            $matrizExcel[27]["Completados"]="Completados";
            $matrizExcel[27]["completado"]=$completado;
            $matrizExcel[28]["Incompletos"]="Incompletos";            
            $matrizExcel[28]["incompleto"]=$incompleto;
            $matrizExcel[29]["No Comenzados"]="No Comenzados";            
            $matrizExcel[29]["noComenzado"]=$noComenzado;            
            $avance="<div style='float:left;width:35%'><table syle='width:100%'><caption><h3>AVANCE</h3></caption>
                        <tr>
                            <td>Total de bloques: $contador</td>
                            <td>Completados: $completado</td>
                        </tr>
                        <tr>
                            <td>Cursados: $cursado</td>
                            <td>Incompletos: $incompleto</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>No Comenzados: $noComenzado</td>
                        </tr>
                    </table></div>";
        }else{
            return "no tiene actividades";
        }
        $query->sql="select nombre_categoria as \"area_conocimiento\", ne.nombre as \"nivel_educativo\", gr.nombre_grupo, c.fecha_inicio,c.fecha_fin, nombre_curso,d.id_datos_personales as \"idUser\",c.id_curso_abierto as \"curso_abierto\",a.id_alumno,r.id_rel_curso_grupo
            from grupo_alumno g
	    join alumnos a on a.id_alumno=g.id_alumno
	    join datos_personales d on d.id_datos_personales= a.id_datos_personales
	    join grupo gr on gr.id_grupo= g.id_grupo
        join rel_curso_grupo r on r.id_grupo = g.id_grupo
        join cursos_abiertos c on c.id_curso_abierto = r.id_curso_abierto
	    join cursos cu on cu.id_curso = c.id_curso
	    join grado_escolar ge on ge.id_grado_escolar= cu.id_grado_escolar
	    join nivel_escolar ne on ne.id_nivel= ge.id_nivel
        join categorias ca on ca.id_categoria = cu.id_categoria
        where d.nombre_usuario='$user' and d.correo='$id'
	    and id_curso_moodle = $curso order by ca.id_categoria";
        $result=$query->select("arr");
        $curso="<div style='clear: both;width: 50%;float: left'><h3>Curso</h3>";
        $idUser="";
        $cursoAbierto="";
        $idAlumno=0;
        $relCursoGrupo=0;
        foreach($result as $res){
            $idUser= $res["idUser"];
            $relCursoGrupo=$res["id_rel_curso_grupo"];
            $idAlumno= $res["id_alumno"];
            $cursoAbierto= $res["curso_abierto"];
            $curso.="<p>Nombre: ".$res["nombre_curso"]." </p>";
            $matrizExcel[18]["Personales"]="Curso: ";
            $matrizExcel[18]["nombre_curso"]=$res["nombre_curso"];
            $curso.="<p>Área de Conocimiento: ".$res["area_conocimiento"]."</p>";
            $matrizExcel[19]["Personales"]="Área de Conocimiento: ";
            $matrizExcel[19]["area_conocimiento"]=$res["area_conocimiento"];
            $curso.="<p>Nivel: ".$res["nivel_educativo"]."</p>";
            $matrizExcel[20]["Personales"]="Nivel: ";
            $matrizExcel[20]["nivel_educativo"]=$res["nivel_educativo"];
            $curso.="<p>Grupo: ".$res["nombre_grupo"]."</p>";
            $matrizExcel[21]["Personales"]="Grupo: ";
            $matrizExcel[21]["nombre_grupo"]=$res["nombre_grupo"];
            $curso.="<p>Inicio de curso: ".$res["fecha_inicio"]."</p>";
            $matrizExcel[22]["Personales"]="Inicio de curso: ";
            $matrizExcel[22]["fecha_inicio"]=$res["fecha_inicio"];
            $curso.="<p>Fin del curso: ".$res["fecha_fin"]."</p>";
            $matrizExcel[23]["Personales"]="Fin del curso: ";
            $matrizExcel[23]["fecha_fin"]=$res["fecha_fin"];
        }        
        $curso.="</div>";
        $query->sql="SELECT distinct t.id_tutor, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, rt.nombre as rol
            FROM rel_curso_tutor rct, rel_curso_grupo rcg, grupo g, tutor t, rol_tutor rt, datos_personales dp
            WHERE rcg.id_curso_abierto= '$cursoAbierto'
              and rct.id_tutor = t.id_tutor
              and t.id_rol_tutor = rt.id_rol_tutor
              and t.id_datos_personales = dp.id_datos_personales
              and rcg.id_grupo = g.id_grupo
              and rct.id_rel_curso_grupo = rcg.id_rel_curso_grupo";
        $result2=$query->select("arr");
        $tutores="<div style='width: 30%;float: left'><h3>tutores</h3><p>";
        if($result2){
            $conteo=13;
             $matrizExcel[12]["Tutores"]="Tutores";
            foreach($result2 as $tut){
                $tutores.=$tut["rol"].": ".$tut["nombre_pila"]." ".$tut["primer_apellido"]." ".$tut["segundo_apellido"];
                $matrizExcel[$conteo]["Tutores"]=$tut["nombre_pila"]." ".$tut["primer_apellido"]." ".$tut["segundo_apellido"];
                $conteo++;
            }        
        }
        $tutores.="</p></div>";
        $datosUsuario= consultaDatosPersonales($idUser);    
        $datosPersonales="<div style='width: 35%;float: left'><h3>Alumno</h3>"; 
        $datosPersonales.="<p><img src='".$datosUsuario["url_foto"]."'/></p>";
        $datosPersonales.="<p>Nombre: ".$datosUsuario["nombre_pila"]." ".$datosUsuario["primer_apellido"]." ".$datosUsuario["segundo_apellido"]."</p>";
        $matrizExcel[0][0]="Reporte de desempeño del alumno";
        $matrizExcel[1]["hola"]="Fecha de expedición: ".date('d-m-Y H:i:s');
        $matrizExcel[2]["Personales"]="Alumno: ";
        $matrizExcel[2]["Datos"]=$datosUsuario["nombre_pila"]." ".$datosUsuario["primer_apellido"]." ".$datosUsuario["segundo_apellido"];
        $datosPersonales.="<p>CURP: ".$datosUsuario["curp"]."</p>";
        $matrizExcel[3]["Personales"]="CURP: ";
        $matrizExcel[3]["curp"]=$datosUsuario["curp"];
        $datosPersonales.="<p>Nivel Educativo: ".$datosUsuario["nivel_escolar"]."</p>";
        $matrizExcel[4]["Personales"]="Nivel Educativo: ";
        $matrizExcel[4]["nivel_escolar"]=$datosUsuario["nivel_escolar"];
        $datosPersonales.="<p>Grado Escolar: ".$datosUsuario["grado_escolar"]."</p>";
        $matrizExcel[5]["Personales"]="Grado escolar: ";
        $matrizExcel[5]["grado_escolar"]=$datosUsuario["grado_escolar"];
        $datosPersonales.="<p>Matrícula: ".$datosUsuario["matricula"]."</p></div>";
        $matrizExcel[6]["Personales"]="Matrícula: ";
        $matrizExcel[6]["matricula"]=$datosUsuario["matricula"];
        $datosPersonales.="<div style='width: 35%;float: left'><h3>Usuarios Asociados</h3>";
        $datosPersonales.="<p>Familiar Responsable: ".$datosUsuario["nombre_padre"]."</p>";
        $matrizExcel[8]["nombre_padre"]=$datosUsuario["nombre_padre"];
        $datosPersonales.="<p>Profesor(a) de aula: ".$datosUsuario["nombre_profesor"]."</p>";
        $matrizExcel[9]["nombre_profesor"]=$datosUsuario["nombre_profesor"];
        $datosPersonales.="</div>";
        $datosPersonales.="<div style='width: 30%;float: left'><h3>Institución educativa</h3>";
        $datosPersonales.="<p>Institución: ".$datosUsuario["nombre_institucion"]."</p>";
        $matrizExcel[10]["Personales"]="Intitución educativa: ";
        $matrizExcel[10]["nombre_institucion"]=$datosUsuario["nombre_institucion"];
        $datosPersonales.="<p>Escuela: ".$datosUsuario["nombre_escuela"]."</p>";
        $matrizExcel[11]["Personales"]="Escuela: ";
        $matrizExcel[11]["nombre_escuela"]=$datosUsuario["nombre_escuela"];
        $datosPersonales.="</div>";
        $cadena="";
        $cadena.=$datosPersonales."".$tutores."".$avance."".$curso."".$resultado;
        require_once("../sources/dompdf/dompdf_config.inc.php"); 
        $html =
          '<html><body><p align="right">Fecha de expedición: '.date("d-m-Y H:i:s").'</p>'.$cadena.'</body></html>';
        $dompdf = new DOMPDF();
        $dompdf->set_paper("letter", "portrait");
        $dompdf->load_html($html);
        $dompdf->render();
        $archivo=uniqid();
        $filename="/var/www/html/storage/reportes/".$archivo.'.pdf';
        $urlDescarga=BASE_STORAGE."reportes/".$archivo;
        $filenamePDF = BASE_STORAGE."reportes/".$archivo.'.pdf';
        $filenameXLS = BASE_STORAGE."reportes/".$archivo.'.xls';
        file_put_contents($filename, $dompdf->output());
        ksort($matrizExcel);
        /*$dompdf->stream($filename);       */ 
        //$dompdf->stream("/var/www/html/storage/reportes/".uniqid().".pdf");
        generaExcel("Repoerte Scorm",$matrizExcel,$archivo,"/var/www/html/storage/reportes/");
        $botones="<p><a id='toPDF' href='".$filenamePDF."' target='_blank'>Generar PDF</a><a id='toXLS' href='".$filenameXLS."' target='_blank'>Generar Excel</a></p>
                    <p align='right'>Fecha de expedición: ".date('d-m-Y H:i:s')."</p>";
        $decripcion="Reporte de desempeño del alumno";
        $sql= new Query("SG");
        $sql->insert("reportes_scorm","fecha_subida,descripcion,url_reporte,id_tipo_reporte_scorm,id_rel_curso_grupo,status,id_alumno",
                        "now(),'".$decripcion."','".$urlDescarga."',1,".$relCursoGrupo.",1,".$idAlumno."");        
        return $botones." ".$cadena;
        
        
    }

    /**
    * función que devuelve reporte de desempeño de gestión alumno 
    * recibe @user (strin que contiene user y correo de moodle)
    * @id (id del amuno)
    */
    public function reporteAlumnoGestion($id,$user, $curso){
        $query = new Query("SG");
        $queryMoodle = new Query("MOD");
        $matrizExcel= array();
        $queryMoodle->sql="select launch,m.id from mdl_grade_items gi
                                        inner join mdl_scorm s on s.course=gi.courseid
                                        inner join mdl_course_modules m on m.course=s.course
                                        inner join mdl_course_sections cs on cs.course= m.course
                                        where courseid= $curso and itemtype='mod' and gi.iteminstance= s.id and m.section= cs.id
                                        and m.instance=s.id and gi.itemname not like '' and s.name not like '' order by cs.section";
        $cursos = $queryMoodle->select("arr");
        $resultado="<div style='clear:both; width:100%'><table style='width:100%'><caption><h3>DESEMPEÑO</h3></caption><tr><th>Bloque</th><th>Nombre</th><th>Fecha de inicio</th><th>Último Acceso</th><th>Tiempo total</th><th>Intentos</th><th>Estatus</th></tr>";
        if($cursos){
            $contador=0;
            $queryScorm= new Query("MOD");
                $completado=0;
                $incompleto=0;
                $noComenzado=0;
                $cursado=0;
                $count=33;
            foreach($cursos as $scorm){
                $contador++;
                $itemId=$scorm["id"];
                $launch=$scorm["launch"];
                $queryScorm->sql="select distinct m.id,t.element,t.value,s.name,s.intro,launch,attempt,t.timemodified from mdl_grade_items gi 
                                    inner join mdl_grade_grades gg on gg.itemid=gi.id 
                                    inner join mdl_scorm s on s.id= gi.iteminstance 
                                    inner join mdl_course_modules m on m.course=s.course 
                                    inner join mdl_scorm_scoes_track t on t.scormid= s.id 
                                    inner join mdl_course c on c.id = gi.courseid 
                                    inner join mdl_enrol e on e.courseid= c.id 
                                    inner join mdl_user_enrolments ue on ue.enrolid=e.id 
                                    inner join mdl_user u on u.id= ue.userid 
                                    and t.userid= u.id
                                    and u.username='$user' 
                                    and u.email='$id' 
                                    and m.id=$itemId and launch =$launch 
                                    and t.element not like '%cmi.suspend_data%'
                                    and t.element not like '%cmi.core.score.raw%'";
                //echo $queryScorm->sql."<br>";
                $ResultSco=$queryScorm->select("arr");
                $matrizExcel[32]["Bloque"]="Bloque";
                $matrizExcel[32]["Nombre"]="Nombre";
                $matrizExcel[32]["fecha_inicio"]="Fecha de inicio";
                $matrizExcel[32]["last_access"]="Último Acceso";
                $matrizExcel[32]["tiempo"]="tiempo";
                $matrizExcel[32]["intento"]="Intento";
                $matrizExcel[32]["Estatus"]="Estatus";
                if($ResultSco){
                    $part="";
                    $part2="";
                    $part3="";
                    foreach($ResultSco as $cal){
                        $nombre=$cal["name"];
                        if($cal["element"]=="x.start.time"){
                            $fecha_inicio = date('Y-m-d h:i:s',$cal["value"]);
                            $fechaInicio="<td>". date('Y-m-d h:i:s',$cal["value"])."</td>";
                        }else if($cal["element"]=="cmi.core.total_time"){
                            $tiempo=$cal["value"];
                            $timeTotal="<td>".$cal["value"]."</td>";
                        }else{
                            switch($cal["value"]){
                                case "incomplete":
                                    $incompleto++;
                                    break;
                                case "completed":
                                    $completado++;
                                    break;
                                case "not attempted":
                                    $noComenzado++;
                                    break;
                            }
                            $estatus_mat=$cal["value"];
                            $estatus="<td>".$cal["value"]."</td>";
                        }
                        $last_access = date('Y-m-d h:i:s',$cal["timemodified"]);
                        $intento=$cal["attempt"];
                        //$resultado.="<tr><td>$contador</td><td>".$cal["name"]."</td><td>".$cal["intro"]."</td><td>".$cal["finalgrade"]."</td><td>".$cal["value"]."</td></tr>";
                        $part="<tr><td>$contador</td><td>".$cal["name"]."</td>";
                        $part2="<td>".date('Y-m-d h:i:s',$cal["timemodified"])."</td>";
                        $part3="<td>".$cal["attempt"]."</td>";
                    }
                    $matrizExcel[$count]["Bloque"]="$contador";
                    $matrizExcel[$count]["Nombre"]=$nombre;
                    $matrizExcel[$count]["fecha_inicio"]=$fecha_inicio;
                    $matrizExcel[$count]["last_access"]= $last_access;
                    $matrizExcel[$count]["tiempo"]=$tiempo;
                    $matrizExcel[$count]["intento"]=$intento;
                    $matrizExcel[$count]["Estatus"]=$estatus_mat;
                    $part.=$fechaInicio."".$part2."".$timeTotal."".$part3."".$estatus;
                    $resultado.=$part."</tr>";
                }else{
                    $queryScorm->sql="select distinct m.id,gi.itemname,s.intro,launch from mdl_grade_items gi 
                        inner join mdl_scorm s on s.id= gi.iteminstance 
                        inner join mdl_course_modules m on m.course=s.course 
                        inner join mdl_course c on c.id = gi.courseid 
                        inner join mdl_enrol e on e.courseid= c.id 
                        inner join mdl_user_enrolments ue on ue.enrolid=e.id 
                        inner join mdl_user u on u.id= ue.userid 
                                        and u.username='$user' and u.email='$id' and m.id=$itemId and launch =$launch";
                    $ResultSco=$queryScorm->select("arr");
                    foreach($ResultSco as $cal){
                        $resultado.="<tr><td>$contador</td><td>".$cal["itemname"]."</td><td>0</td><td>0</td><td>0</td><td>0</td><td>not attemped</td></tr>";
                        $matrizExcel[$count]["Bloque"]="$contador";
                        $matrizExcel[$count]["Nombre"]=$cal["name"];
                        $matrizExcel[$count]["Descripcion"]=$cal["intro"];
                        $matrizExcel[$count]["Calificacion"]=$cal["finalgrade"];
                        $matrizExcel[$count]["Estatus"]=$cal["value"];
                    }
                }
                $count++;
            }
            $cursados= $completado + $incompleto;
            $resultado.="</table></div>";
            $matrizExcel[26]["avance"]="Avance";
            $matrizExcel[27]["Total Bloques"]="Total Bloques";
            $matrizExcel[27]["contador"]="$contador";
            $matrizExcel[28]["Cursados"]="Cursados";
            $matrizExcel[28]["cursado"]=$cursado;
            $matrizExcel[27]["Completados"]="Completados";
            $matrizExcel[27]["completado"]=$completado;
            $matrizExcel[28]["Incompletos"]="Incompletos";            
            $matrizExcel[28]["incompleto"]=$incompleto;
            $matrizExcel[29]["No Comenzados"]="No Comenzados";            
            $matrizExcel[29]["noComenzado"]=$noComenzado;            
            $avance="<div style='float:left;width:35%'><br><table syle='width:100%'><caption><h3>AVANCE</h3></caption>
                        <tr>
                            <td>Total de bloques: $contador</td>
                            <td>Completados (C): $completado</td>
                        </tr>
                        <tr>
                            <td>Cursados: $cursado</td>
                            <td>Incompletos (I): $incompleto</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>No Comenzados (NC): $noComenzado</td>
                        </tr>
                    </table></div>";
        }else{
            return "no tiene actividades";
        }
        $query->sql="select nombre_categoria as \"area_conocimiento\", ne.nombre as \"nivel_educativo\", gr.nombre_grupo, c.fecha_inicio,c.fecha_fin, nombre_curso,d.id_datos_personales as \"idUser\",c.id_curso_abierto as \"curso_abierto\",a.id_alumno,r.id_rel_curso_grupo
            from grupo_alumno g
	    join alumnos a on a.id_alumno=g.id_alumno
	    join datos_personales d on d.id_datos_personales= a.id_datos_personales
	    join grupo gr on gr.id_grupo= g.id_grupo
        join rel_curso_grupo r on r.id_grupo = g.id_grupo
        join cursos_abiertos c on c.id_curso_abierto = r.id_curso_abierto
	    join cursos cu on cu.id_curso = c.id_curso
	    join grado_escolar ge on ge.id_grado_escolar= cu.id_grado_escolar
	    join nivel_escolar ne on ne.id_nivel= ge.id_nivel
        join categorias ca on ca.id_categoria = cu.id_categoria
        where d.nombre_usuario='$user' and d.correo='$id'
	    and id_curso_moodle = $curso order by ca.id_categoria";
        $result=$query->select("arr");
        $curso="<div style='clear: both;width: 50%;float: left'><h3>Curso</h3>";
        $idUser="";
        $cursoAbierto="";
        $idAlumno=0;
        $matrizExcel[17]["Curso"]="Curso";
        $relCursoGrupo=0;
        foreach($result as $res){
            $idUser= $res["idUser"];
            $idAlumno= $res["id_alumno"];
            $relCursoGrupo=$res["id_rel_curso_grupo"];
            $cursoAbierto= $res["curso_abierto"];
            $curso.="<p>Nombre: ".$res["nombre_curso"]." </p>";
            $matrizExcel[18]["Personales"]="Curso: ";
            $matrizExcel[18]["nombre_curso"]=$res["nombre_curso"];
            $curso.="<p>Área de Conocimiento: ".$res["area_conocimiento"]."</p>";
            $matrizExcel[19]["Personales"]="Área de Conocimiento: ";
            $matrizExcel[19]["area_conocimiento"]=$res["area_conocimiento"];
            $curso.="<p>Nivel: ".$res["nivel_educativo"]."</p>";
            $matrizExcel[20]["Personales"]="Nivel: ";
            $matrizExcel[20]["nivel_educativo"]=$res["nivel_educativo"];
            $curso.="<p>Grupo: ".$res["nombre_grupo"]."</p>";
            $matrizExcel[21]["Personales"]="Grupo: ";
            $matrizExcel[21]["nombre_grupo"]=$res["nombre_grupo"];
            $curso.="<p>Inicio de curso: ".$res["fecha_inicio"]."</p>";
            $matrizExcel[22]["Personales"]="Inicio de curso: ";
            $matrizExcel[22]["fecha_inicio"]=$res["fecha_inicio"];
            $curso.="<p>Fin del curso: ".$res["fecha_fin"]."</p>";
            $matrizExcel[23]["Personales"]="Fin del curso: ";
            $matrizExcel[23]["fecha_fin"]=$res["fecha_fin"];
        }        
        $curso.="</div>";
        $query->sql="SELECT distinct t.id_tutor, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido, rt.nombre as rol
            FROM rel_curso_tutor rct, rel_curso_grupo rcg, grupo g, tutor t, rol_tutor rt, datos_personales dp
            WHERE rcg.id_curso_abierto= '$cursoAbierto'
              and rct.id_tutor = t.id_tutor
              and t.id_rol_tutor = rt.id_rol_tutor
              and t.id_datos_personales = dp.id_datos_personales
              and rcg.id_grupo = g.id_grupo
              and rct.id_rel_curso_grupo = rcg.id_rel_curso_grupo";
        $result2=$query->select("arr");
        $tutores="<div style='width: 30%;float: left'><h3>tutores</h3><br><p>";
        if($result2){
            $conteo=13;
             $matrizExcel[12]["Tutores"]="Tutores";
            foreach($result2 as $tut){
                $tutores.=$tut["rol"].": ".$tut["nombre_pila"]." ".$tut["primer_apellido"]." ".$tut["segundo_apellido"];
                $matrizExcel[$conteo]["Tutores"]=$tut["nombre_pila"]." ".$tut["primer_apellido"]." ".$tut["segundo_apellido"];
                $conteo++;
            }        
        }
        $tutores.="</p></div>";
        $datosUsuario= consultaDatosPersonales($idUser);    
        $datosPersonales="<div style='width: 35%;float: left'><h3>Alumno</h3>"; 
        $datosPersonales.="<p><img src='".$datosUsuario["url_foto"]."'/></p>";
        $datosPersonales.="<p>Nombre: ".$datosUsuario["nombre_pila"]." ".$datosUsuario["primer_apellido"]." ".$datosUsuario["segundo_apellido"]."</p>";
        $matrizExcel[0][0]="Reporte de gestión del alumno";
        $matrizExcel[1]["hola"]="Fecha de expedición: ".date('d-m-Y H:i:s');
        $matrizExcel[2]["Personales"]="Alumno: ";
        $matrizExcel[2]["Datos"]=$datosUsuario["nombre_pila"]." ".$datosUsuario["primer_apellido"]." ".$datosUsuario["segundo_apellido"];
        $datosPersonales.="<p>CURP: ".$datosUsuario["curp"]."</p>";
        $matrizExcel[3]["Personales"]="CURP: ";
        $matrizExcel[3]["curp"]=$datosUsuario["curp"];
        $datosPersonales.="<p>Nivel Educativo: ".$datosUsuario["nivel_escolar"]."</p>";
        $matrizExcel[4]["Personales"]="Nivel Educativo: ";
        $matrizExcel[4]["nivel_escolar"]=$datosUsuario["nivel_escolar"];
        $datosPersonales.="<p>Grado Escolar: ".$datosUsuario["grado_escolar"]."</p>";
        $matrizExcel[5]["Personales"]="Grado escolar: ";
        $matrizExcel[5]["grado_escolar"]=$datosUsuario["grado_escolar"];
        $datosPersonales.="<p>Matrícula: ".$datosUsuario["matricula"]."</p></div>";
        $matrizExcel[6]["Personales"]="Matrícula: ";
        $matrizExcel[6]["matricula"]=$datosUsuario["matricula"];
        $datosPersonales.="<div style='width: 35%;float: left'><h3>Usuarios Asociados</h3>";
        $datosPersonales.="<p>Familiar Responsable: ".$datosUsuario["nombre_padre"]."</p>";
        $matrizExcel[8]["nombre_padre"]=$datosUsuario["nombre_padre"];
        $datosPersonales.="<p>Profesor(a) de aula: ".$datosUsuario["nombre_profesor"]."</p>";
        $matrizExcel[9]["nombre_profesor"]=$datosUsuario["nombre_profesor"];
        $datosPersonales.="</div>";
        $datosPersonales.="<div style='width: 30%;float: left'><h3>Institución educativa</h3>";
        $datosPersonales.="<p>Institución: ".$datosUsuario["nombre_institucion"]."</p>";
        $matrizExcel[10]["Personales"]="Intitución educativa: ";
        $matrizExcel[10]["nombre_institucion"]=$datosUsuario["nombre_institucion"];
        $datosPersonales.="<p>Escuela: ".$datosUsuario["nombre_escuela"]."</p>";
        $matrizExcel[11]["Personales"]="Escuela: ";
        $matrizExcel[11]["nombre_escuela"]=$datosUsuario["nombre_escuela"];
        $datosPersonales.="</div>";
        $cadena="";
        $cadena.=$datosPersonales."".$tutores."".$avance."".$curso."".$resultado;
        require_once("../sources/dompdf/dompdf_config.inc.php"); 
        $html =
          '<html><body><p align="right">Fecha de expedición: '.date("d-m-Y H:i:s").'</p>'.$cadena.'</body></html>';
        $dompdf = new DOMPDF();
        $dompdf->set_paper("letter", "portrait");
        $dompdf->load_html($html);
        $dompdf->render();
        $archivo=uniqid();
        $filename="/var/www/html/storage/reportes/".$archivo.'.pdf';
        $urlDescarga=BASE_STORAGE."reportes/".$archivo;
        $filenamePDF = BASE_STORAGE."reportes/".$archivo.'.pdf';
        $filenameXLS = BASE_STORAGE."reportes/".$archivo.'.xls';
        file_put_contents($filename, $dompdf->output());
        ksort($matrizExcel);
        /*$dompdf->stream($filename);       */ 
        //$dompdf->stream("/var/www/html/storage/reportes/".uniqid().".pdf");
        generaExcel("Repoerte Scorm",$matrizExcel,$archivo,"/var/www/html/storage/reportes/");
        $botones="<p><a id='toPDF' href='".$filenamePDF."' target='_blank'>Generar PDF</a><a id='toXLS' href='".$filenameXLS."' target='_blank'>Generar Excel</a></p>
                    <p align='right'>Fecha de expedición: ".date('d-m-Y H:i:s')."</p>";
        $decripcion="Reporte de Gestión del alumno";
        $sql= new Query("SG");
        $sql->insert("reportes_scorm","fecha_subida,descripcion,url_reporte,id_tipo_reporte_scorm,id_rel_curso_grupo,status,id_alumno","now(),'".$decripcion."','".$urlDescarga."',3,".$relCursoGrupo.",1,".$idAlumno."");        
        return $botones."".$cadena;
    }


}

?>