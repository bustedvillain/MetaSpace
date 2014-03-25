<?php include("Funciones.php");
/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 8 de Octubre del 2013
 * Realiza consultas retornado resultados en objetos JSON
 */
if($_POST){
    //Consulta que desea realizar
    $consulta = $_POST["consulta"];
    $idAtributo=$_POST["idAtributo"];
    switch($consulta){
        case "getHabilidad":
            $tabla="habilidades";
            $nombre_atributo="nombre_habilidad";
            $id_nombre="id_habilidad";
            break;        
        case "getAsignatura":
            $tabla="asignaturas";
            $nombre_atributo="nombre_asignatura";
            $id_nombre="id_asignatura";
            break; 
        case "getCategoria":
            $tabla="categorias";
            $nombre_atributo="nombre_categoria";
            $id_nombre="id_categoria";
            break;
        case "getInstitucion":
            $tabla="instituciones";
            $nombre_atributo="nombre_institucion";
            $id_nombre="id_institucion";
            break;
        case "getEmpresa":
            $tabla="empresa";
            $nombre_atributo="nombre_empresa";
            $id_nombre="id_empresa";
            break;
        case "getNivelEducativo":
            $tabla="nivel_escolar";
            $nombre_atributo="nombre";
            $id_nombre="id_nivel";
            break;
        case "getGradoEscolar":
            $tabla="grado_escolar";
            $nombre_atributo="nombre_grado";
            $id_nombre="id_grado_escolar";
            break;
        case "getNacionalidad":
            $tabla="nacionalidad";
            $nombre_atributo="nacionalidad";
            $id_nombre="id_nacionalidad";
            break;
    }
    
    echo buscaAtributoJSON($idAtributo, $tabla, $nombre_atributo, $id_nombre);
    
    
    
}
?>
