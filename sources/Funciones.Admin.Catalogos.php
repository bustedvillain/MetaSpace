<?php

/*
 * Autor: Jose Manuel Nieto Gomez
 * Fecha de Creacion: 08 de Octubre del 2013
 * Objetivo: Funciones para insertar catalogos, siendo dinamico
 * para cualquier tabla, de las que solo requieren un nombre y un status:
 * Atributos
 * Asignaturas
 * Categorias
 * Instituciones 
 * Empresas
 * Nacionalidades
 * Grados Escolares
 * Niveles Educativos
 * Consutas a moodle: $query = new Query("MOD");
 * Consultas a SGI: $query = new Query("SG");
 */

/**
 * CHANGE CONTROL 0.99.6
 * FECHA DE MODIFICACIÓN: 30 DE DICIEMBRE DEL 2013
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * OBJETIVO: MODIFICACIONES SOBRE RELACION DE NIVEL ESCOLAR CON GRADO ESCOLAR
 */

/**
 * Inserta un catalogo a la base de datos del sistema de gestion
 * Retorna un true si se inserto correctamente
 * Retorna false si ya existia este atributo en la BD.
 * @param type $nombre_atributo
 * @param type $atributo
 * @param type $tabla
 * @param type $id_atributo
 * @return boolean
 */
function insertaAtributo($nombre_atributo, $atributo, $tabla, $id_atributo) {
    //Limpia cadena de texto
    $atributo = __($atributo);

    //Insert a SG 
    if (!reactivarAtributo($atributo, $tabla, $id_atributo, $nombre_atributo)) {
        if (validaInsercionAtributo($atributo, $tabla, $id_atributo, $nombre_atributo)) {
            //Crea objeto para realizar consultas de sistema de gestion
            $query = new Query("SG");
            $query->insert("$tabla", "$nombre_atributo, status", "'$atributo', 1");
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

/**
 * 
 * @param type $atributo el valor del atributo
 * @param type $tabla el nombre de la tabla
 * @param type $id_atributo el nombre del id de la tabla
 * @return boolean
 */
function reactivarAtributo($atributo, $tabla, $id_atributo, $nombre_atributo) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "SELECT $id_atributo, $nombre_atributo from $tabla where upper($nombre_atributo)=upper('$atributo') and status=0";

    $tabla2 = $query->select("obj");

    if ($tabla2) {
        foreach ($tabla2 as $atributo) {
            eval("\$id = \$atributo->$id_atributo;");
            $query->sql = "UPDATE $tabla set status=1 where $id_atributo=" . $id;
            if ($query->update($query->sql)) {
//               exit("HICE UPDATE!");
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}

/**
 * Funcion que valida si ya existe una atributo en la base de datos
 * Si esta ya existe retornara un falso
 * Si no existe retornara un true
 * @param type $atributo el valor del atributo
 * @param type $tabla el nombre de la tabla
 * @param type $id_atributo el nombre del id de la tabla
 * @param type $id valor del id
 * @return boolean
 */
function validaInsercionAtributo($atributo, $tabla, $id_atributo, $nombre_atributo, $id = NULL) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    //Si especifica un idHabildiad excluirlo en la busqueda
    if ($id) {
        $query->sql = "SELECT $nombre_atributo from $tabla where upper($nombre_atributo) =upper('$atributo') and $id_atributo != $id and status=1";
    } else {
        $query->sql = "SELECT $nombre_atributo from $tabla where upper($nombre_atributo) =upper('$atributo') and status=1";
    }
    $tabla = $query->select("obj");

    if ($tabla) {
        foreach ($tabla as $atributo) {
            return false;
        }
    } else {
        return true;
    }
}

/**
 * Consulta catalogo de $tabla
 *
 * @param type $tabla tabla a la que hara consulta
 * @param type $id_atributo el ombre de la llave primaria
 * @param type $nombre_atributo nombre del campo a consultar
 */
function consultaAtributos($tabla, $id_atributo, $nombre_atributo) {
    $nombreAtributoCaps = preparaNombreVariable($tabla);
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "SELECT $id_atributo, $nombre_atributo from $tabla where status=1";
    $tabla2 = $query->select("obj");

    if ($tabla2) {
        foreach ($tabla2 as $atributo) {
            eval("\$id = \$atributo->$id_atributo;");
            eval("\$nombre = \$atributo->$nombre_atributo;");
            $nombre = ($nombre);
            echo <<<HTML
                <tr>                    
                    <td>
                        <a class="icon-edit editaAtributo" id="$id" title="Editar" href="#editarModal" name="get$nombreAtributoCaps" role="button" data-toggle="modal"></a>
                        <a class="icon-trash" href="borrarAtributo.php?id=$id&tipoAtributo=$nombreAtributoCaps" title="Borrar" onClick="return confirm('¿Está seguro?');"></a>
                    </td>
                    <td>$nombre</td>
                </tr>
HTML;
        }
    } else {
        echo "No hay datos que mostrar";
    }
}

/**
 *  Busca una atributo y la retorna en un objeto JSON
 * @param type $idAtributo Valor del id buscado
 * @param type $tabla Nombre de la tabla
 * @param type $nombre_atributo nombre del campo de la tabla
 * @param type $id_nombre nombre del id
 * @return type objeto JSON para editarse
 */
function buscaAtributoJSON($idAtributo, $tabla, $nombre_atributo, $id_nombre) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "SELECT $id_nombre, $nombre_atributo from $tabla where $id_nombre=$idAtributo and status=1";
    $tabla = $query->select("obj");

    if ($tabla) {
        foreach ($tabla as $atributo) {
            eval("\$nombre_atributo = \$atributo->$nombre_atributo;");
            $nombre_atributo = ($nombre_atributo);
            $objeto = array("id_atributo" => "$idAtributo", "nombre_atributo" => "$nombre_atributo");
            return json_encode($objeto);
        }
    }
}

/**
 * Actualiza el nombre de una atributo a un id en especifico
 * @param type $idAtributo el valor del id
 * @param type $atributo valor del atribut
 * @param type $tabla nombre de la tabla
 * @param type $nombre_atributo nombre del campo de la tabla
 * @param type $id_nombre nombre del id de la tabla
 * @return boolean
 */
function actualizaAtributo($idAtributo, $atributo, $tabla, $nombre_atributo, $id_nombre) {
    $atributo = __($atributo);
    //Valida si existe otra atributo con este nombre
    if (validaInsercionAtributo($atributo, $tabla, $id_nombre, $nombre_atributo, $idAtributo)) {
        actualiza($idAtributo, $atributo, $tabla, $nombre_atributo, $id_nombre);
        return true;
    } else {
        return false;
    }
}

/**
 * Actualiza un atributo en la base de datos
 * @param type $idAtributo
 * @param type $atributo
 * @param type $tabla
 * @param type $nombre_atributo
 * @param type $id_nombre
 * @return boolean
 */
function actualiza($idAtributo, $atributo, $tabla, $nombre_atributo, $id_nombre) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "UPDATE $tabla set $nombre_atributo='$atributo' where $id_nombre=$idAtributo";
//    imprimeConsola($query->sql);
    if ($query->update($query->sql)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Borra una atributo, acutaliando el status a 0
 * @param type $idAtributo valor del id
 * @param type $tabla nombre de la tabla
 * @param type $id_nombre nombre de la llave primaria
 * @return boolean
 */
function borraAtributo($idAtributo, $tabla, $id_nombre) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "UPDATE $tabla set status=0 where $id_nombre=$idAtributo";
    if ($query->update($query->sql)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Funcion que parsea el nombre de una variable
 * Ej: String inicial nombre_variable
 * Res: NombreVariable
 * @param type $nombre_variable
 * @return type
 */
function preparaNombreVariable($nombre_variable) {
    switch ($nombre_variable) {
        case "habilidades":
            return "Habilidad";
        case "asignaturas":
            return "Asignatura";
        case "categorias":
            return "Categoria";
        case "instituciones":
            return "Institucion";
        case "empresa":
            return "Empresa";
        case "nivel_escolar":
            return "NivelEducativo";
        case "grado_escolar":
            return "GradoEscolar";
        case "nacionalidad":
            return "Nacionalidad";
    }
}

/**
 * Funciones especiales para el catalogo de escuelas
 */

/**
 * Consulta las escuelas activas
 */
function consultaEscuelas() {

    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "SELECT e.id_escuela, e.nombre_escuela, i.nombre_institucion  from escuelas e, instituciones i  where e.id_institucion=i.id_institucion and e.status=1";
    $tabla = $query->select("obj");

    if ($tabla) {
        foreach ($tabla as $atributo) {
            $nombreInstitucion = ($atributo->nombre_institucion);
//            $nombreEscuela = html_entity_decode($atributo->nombre_escuela);
            $nombreEscuela = ($atributo->nombre_escuela);
            echo <<<HTML
                <tr>                    
                    <td>
                        <a class="icon-edit editaEscuela" title="Editar" id="$atributo->id_escuela" href="#editarModal"  role="button" data-toggle="modal"></a>
                        <a class="icon-trash" title="Borrar" href="borrarEscuela.php?id=$atributo->id_escuela" onClick="return confirm('¿Está seguro?');"></a>
                    </td>
                    <td>$nombreInstitucion</td>
                    <td>$nombreEscuela</td>
                </tr>
HTML;
        }
    } else {
        echo "No hay datos que mostrar";
    }
}

/**
 * Inserta una escuela en la base de datos
 * @param type $escuela nombre de la escuela
 * @param type $idInstitucion id de la institucion
 * @return boolean retorna si fue correcta la inserción
 */
function insertaEscuela($escuela, $idInstitucion) {
    //Limpia cadena de texto
    $escuela = __($escuela);
    //Reutilizando metodos
    $tabla = "escuelas";
    $id_atributo = "id_escuela";
    $nombre_atributo = "nombre_escuela";
    //Insert a SG 
    if (!reactivarAtributo($escuela, $tabla, $id_atributo, $nombre_atributo)) {
        if (validaInsercionAtributo($escuela, $tabla, $id_atributo, $nombre_atributo)) {
            //Crea objeto para realizar consultas de sistema de gestion
            $query = new Query("SG");
            $query->insert("$tabla", "$nombre_atributo, status, id_institucion", "'$escuela', 1, $idInstitucion");
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

/**
 * Busca una escuela y la retorna en un objeto JSON 
 * @param type $idEscuela
 * @return type
 */
function buscaEscuelaJSON($idEscuela) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "SELECT nombre_escuela, id_institucion from escuelas where id_escuela=$idEscuela and status=1";
    $tabla = $query->select("obj");

    if ($tabla) {
        foreach ($tabla as $atributo) {
            $objeto = array("id_escuela" => "$idEscuela", "nombre_escuela" => "$atributo->nombre_escuela", "id_institucion" => "$atributo->id_institucion");
            return json_encode($objeto);
        }
    }
}

/**
 * Actualiza la información de una escuela
 * @param type $idInstitucion id de la institucion
 * @param type $escuela nombre de la escuela
 * @param type $idEscuela id de la escuela
 * @return boolean
 */
function actualizaEscuela($idInstitucion, $escuela, $idEscuela) {
    //Limpiando cadena 
    $escuela = __($escuela);
    //Atributos para validacion
    $atributo = $escuela;
    $tabla = "escuelas";
    $id_nombre = "id_escuela";
    $nombre_atributo = "nombre_escuela";
    $idAtributo = $idEscuela;

    //Valida si existe otra atributo con este nombre
    if (validaInsercionAtributo($atributo, $tabla, $id_nombre, $nombre_atributo, $idAtributo)) {
        //Crea objeto para realizar consultas de sistema de gestion
        $query = new Query("SG");
        //Query to SG
        $query->sql = "UPDATE $tabla set $nombre_atributo='$atributo', id_institucion=$idInstitucion where $id_nombre=$idAtributo";
        if ($query->update($query->sql)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Funcion que actualiza un grado escolar validando si se puede cambiar
 * @param type $idGradoEscolar
 * @param type $gradoEscolar
 * @param type $idNivel
 * @return boolean
 */
function actualizaGradoEscolar($idGradoEscolar, $gradoEscolar, $idNivel) {
    //Limpiando cadena 
    $gradoEscolar = __($gradoEscolar);
    
    //Valida si existe otra atributo con este nombre
    if (!validaInsercionGradoEscolar($gradoEscolar, $idNivel, $idGradoEscolar)) {
        //Crea objeto para realizar consultas de sistema de gestion
        $query = new Query("SG");
        //Query to SG
        $query->sql = "UPDATE grado_escolar set nombre_grado='$gradoEscolar', id_nivel=$idNivel where id_grado_escolar=$idGradoEscolar";
        if ($query->update($query->sql)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Combos de catalogos
 */

/**
 * Funcion que genera opciones de un combo de 
 * acuerdo a los niveles educativos en la base de datos
 */
function comboNivelesEducativos($tipo = null) {
    $query = new Query("SG");

    $query->sql = "SELECT id_nivel, nombre FROM nivel_escolar where status=1";
    $niveles = $query->select("obj");

    if ($niveles) {
        if(!isset($tipo)){
            echo <<<combo
            <option value="">Seleccione un nivel educativo</option>
combo;
        }
        
        foreach ($niveles as $nivel) {
            $nombre = ($nivel->nombre);
            echo <<<HTML
                <option value="$nivel->id_nivel">$nombre</option>
HTML;
        }
    }else{
        echo <<<combo
            <option value="">No hay niveles educativos</option>
combo;
    }
}

/**
 * Funcion que genera un combo box con las categorias
 * activas en la base de datos
 */
function comboCategorias() {
    $query = new Query("SG");

    $query->sql = "SELECT id_categoria, nombre_categoria FROM categorias where status=1";
    $categorias = $query->select("obj");

    if ($categorias) {
        foreach ($categorias as $categoria) {
            $nombre = ($categoria->nombre_categoria);
            echo <<<HTML
                <option value="$categoria->id_categoria">$nombre</option>
HTML;
        }
    }else{
        echo <<<html
            <option value="">No hay categoias</option>
html;
    }
    
}

/**
 * Genera combo de asignaturas
 */
function comboAsignaturas() {
    $query = new Query("SG");

    $query->sql = "SELECT id_asignatura, nombre_asignatura FROM asignaturas where status=1";
    $asignaturas = $query->select("obj");

    if ($asignaturas) {
        foreach ($asignaturas as $asignatura) {
            $nombre = ($asignatura->nombre_asignatura);
            echo <<<HTML
                <option value="$asignatura->id_asignatura">$nombre</option>
HTML;
        }
    }else{
        echo <<<html
            <option value="">No hay asignaturas</option>
html;
    }
}

/**
 * Funcion que genera options a partir de los 
 * grados escolares en la base de datos
 */
function comboGradoEscolar() {
    $query = new Query("SG");

    $query->sql = "SELECT id_grado_escolar, nombre_grado FROM grado_escolar where status=1 order by nombre_grado";
    $grados = $query->select("obj");

    if ($grados) {
        echo <<<combo
            <option value="">Seleccione un Grado Escolar</option>
combo;
        foreach ($grados as $grado) {
            $nombre = html_entity_decode($grado->nombre_grado);
            echo <<<HTML
                <option value="$grado->id_grado_escolar">$nombre</option>
HTML;
        }
    }else{
        echo <<<combo
            <option value="">No hay grados escolares</option>
combo;
    }
}

/**
 * Funcion que genera un combo con las instituciones activas en la
 * base de datos
 * @param type $idSelect, el nombre que se le va a poner al id del componente
 */
function comboInstituciones($idSelect = NULL, $name = NULL) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "SELECT id_institucion, nombre_institucion  from instituciones i  where status=1";
    $tabla = $query->select("obj");

    if ($name) {
        echo <<<HTML
            <select name="$name" id="$idSelect">
HTML;
    } else {
        echo <<<HTML
            <select name="idInstitucion" id="$idSelect">
HTML;
    }
    
    if ($tabla) {
        foreach ($tabla as $atributo) {
            $nombre = ($atributo->nombre_institucion);
            echo <<<HTML
                <option value="$atributo->id_institucion">$nombre</option>
HTML;
        }
    } else {
        echo "<option value=''>No hay instituciones</option>";
    }
    echo <<<HTML
            </select>
HTML;
}

/**
 * Funcion que consulta las escuelas de una institucion y las retorna en un objeto JSON
 * @param type $idInstitucion
 * @return type
 */
function getEscuelasJSON($idInstitucion) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");
    //Query to SG
    $query->sql = "SELECT id_escuela, nombre_escuela from escuelas where id_institucion=$idInstitucion and status=1";

    $tabla = $query->select("obj");

    if ($tabla) {
        return json_encode($tabla);
    }
}

/**
 * CHANGE CONTROL 0.99.6
 * FECHA DE MODIFICACIÓN: 30 DE DICIEMBRE DEL 2013
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * OBJETIVO: MODIFICACIONES SOBRE RELACION DE NIVEL ESCOLAR CON GRADO ESCOLAR
 */

/**
 * Funcion que imprime una tabla con los grados y niveles
 * educativos activos
 */
function consultaGradosEscolares(){
    $query = new Query("SG");
    $query->sql= <<<select
        SELECT ge.id_grado_escolar, ge.nombre_grado, ne.nombre nivel_escolar 
        FROM grado_escolar ge, nivel_escolar ne 
        WHERE ge.status=1 and ne.status=1 and ge.id_nivel = ne.id_nivel
select;
   
    $resultSet = $query->select();
    if($resultSet){
        foreach($resultSet as $result){
            $nombreGrado = ($result->nombre_grado);
            $nombreNivel = ($result->nivel_escolar);
            echo <<<HTML
                <tr>                    
                    <td>
                        <a class="icon-edit editaGrado" id="$result->id_grado_escolar" title="Editar" href="#editarModal" role="button" data-toggle="modal"></a>
                        <a class="icon-trash" href="borrarAtributo.php?id=$result->id_grado_escolar&tipoAtributo=GradoEscolar" title="Borrar" onClick="return confirm('¿Está seguro?');"></a>
                    </td>
                    <td>$nombreNivel</td>
                    <td>$nombreGrado</td>
                </tr>
HTML;
        }
    }
    
}

/**
 * Funcion que consulta un grado escolar
 * @param type $idGrado
 * @return null
 */
function consultaGradoEscolar($idGrado){
    $query = new Query("SG");
    $query->sql = <<<select
        SELECT ge.id_grado_escolar, ge.nombre_grado, ne.nombre nivel_escolar, ne.id_nivel 
        FROM grado_escolar ge, nivel_escolar ne 
        WHERE ge.status=1 
          and ne.status=1 
          and ge.id_nivel = ne.id_nivel
          and ge.id_grado_escolar = $idGrado
select;
    
    $resultSet = $query->select();
    
    if($resultSet){
        foreach($resultSet as $result){
            return $result;
        }
    }else{
        return null;
    }
}

/**
 * Funcion que consulta un grado escolar y lo retorna
 * en un objeto JSON
 * @param type $idGrado
 * @return type
 */
function consultaGradoEscolarJSON($idGrado){
    return json_encode(consultaGradoEscolar($idGrado));
}

/**
 * Inserta grado escolar en la base de datos
 * @param type $escuela nombre de la escuela
 * @param type $idInstitucion id de la institucion
 * @return boolean retorna si fue correcta la inserción
 */
function insertaGradoEscolar($gradoEscolar, $idNivel) {
    //Limpia cadena de texto
    $gradoEscolar = __($gradoEscolar);
    //Reutilizando metodos
    $tabla = "grado_escolar";
    $id_atributo = "id_grado_escolar";
    $nombre_atributo = "nombre_grado";
    //Insert a SG 
    if (!reactivarAtributo($gradoEscolar, $tabla, $id_atributo, $nombre_atributo)) {
        if (!validaInsercionGradoEscolar($gradoEscolar, $idNivel)) {
            //Crea objeto para realizar consultas de sistema de gestion
            $query = new Query("SG");
            $query->insert("$tabla", "$nombre_atributo, status, id_nivel", "'$gradoEscolar', 1, $idNivel");
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

/**
 * Funcion que valida si existe un grado escolar en algun nivel escolar en particular
 * Si existe retorna un true, si no existe ninguno retorna false
 * @param type $gradoEscolar
 * @param type $idNivel
 * @return boolean
 */
function validaInsercionGradoEscolar($gradoEscolar, $idNivel, $idGradoEscolar = NULL){
    $query = new Query("SG");
    if(isset($idGradoEscolar)){
                    $query->sql = <<<sql
            Select * FROM grado_escolar WHERE nombre_grado = '$gradoEscolar' and id_nivel = $idNivel and id_grado_escolar != $idGradoEscolar
sql;
    }else{
            $query->sql = <<<sql
            Select * FROM grado_escolar WHERE nombre_grado = '$gradoEscolar' and id_nivel = $idNivel
sql;
    }


    $resultSet = $query->select();
    
    if($resultSet){
        return true;
    }else{
        return false;
    }
    
}   


?>
