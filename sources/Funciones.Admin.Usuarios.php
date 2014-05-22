<?php

/*
 * Fecha de Creación: Miercoles 16 de Octubre del 2013
 * Autor: José Manuel Nieto Gomez
 * Objetivo: Funciones relacionados a la manipulacion de usuarios por parte del
 * administrador
 * 
 * CHANGE CONTROL 0.99.5
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * FECHA: 26 DE DICIEMBRE DEL 2013
 * OBJETIVO: FUNCIONES PARA INSERTAR USUARIO EN MOODLE
 * FUNCION: insertaUsuarioMoodle(), get_assignable_roles()
 * 
 * 
 * CHANGE CONTROL 0.99.8
 * FECHA DE MODIFICACIÓN: 20 DE ENERO DEL 2014
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * OBJETIVO: VERIFICAR EN MOODLE LA EXISTENCIA DE UN USUARIO 
 * FUNCION: verificarUsuarioMoodle(), verificarUsuarioMoodleJSON()
 * imprimeFormularioGeneral() - para cambiar los datos que se piden a tutores y gestores de contenido
 */


//Control de cambios #&
//Autor:Omar Nava
//Objetivo: Left join a tabla de usuarios
//30-dic-2013

/**
 * Imprime el formulario para datos generales de todos los usuarios
 */
function imprimeFormularioGeneral($usuario = "todos") {

    echo <<<FORM
        <legend>DATOS DE ACCESO:</legend>
        <div class="input-append">
            <label>*Nombre de Usuario:</label>
            <input id="nombreUsuario" name="datos_personales/nombre_usuario" type="text" placeholder="miusuario" required autocomplete="off">
            <label class="text-error" id="errorUsuario"></label>
        </div>
        <div class="input-append">
            <label>*Correo:</label>
            <input id="correo" name="datos_personales/correo" type="email"  pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" placeholder="correo@correo.com" required autocomplete="off">
            <label class="text-error" id="errorCorreo"></label>
        </div>        
      
        <legend>DATOS PERSONALES:</legend>                    
        <div class="input-append">
            <label>*Nombre(s):</label>
            <input name="datos_personales/nombre_pila" type="text" placeholder="Juan" id="nombre_pila" required>
        </div>
        <div class="input-append">
            <label>*Primer Apellido:</label>
            <input name="datos_personales/primer_apellido" type="text" placeholder="Martinez" id="primer_apellido" required>
        </div>
        <div class="input-append">
            <label>Segundo Apellido:</label>
            <input name="datos_personales/segundo_apellido" type="text" placeholder="Gutierrez" id="segundo_apellido">
        </div>  
        <div class="input-append">
            <label>*Fecha de nacimiento (dd/mm/yyyy):</label>
            <input type="text" name="datos_personales/fecha_nacimiento" id="fechaNac" required pattern="\d{1,2}/\d{1,2}/\d{4}">
            <label class="text-error" id="errorFecha"></label>
        </div>
FORM;
    if ($usuario == "todos") {
        echo <<<FORM
        <div class="input-append">
            <label>CURP:</label>
            <input name="datos_personales/curp" type="text" placeholder="UJHT890212HMCTMN04" id="curp" maxlength="18"  min="18">
        </div> 
        <div class="input-append">
            <label>Nacionalidad:</label>
            <select name="datos_personales/id_nacionalidad">
FORM;
        comboNacionalidades();
        echo <<<FORM
                
            </select>
        </div>
FORM;
    }
    echo <<<FORM
        <legend>DOMICILIO:</legend> 
FORM;
    if ($usuario == "todos") {
        echo <<<FORM
        <div class="input-append">
            <label>*Calle:</label>
            <input name="datos_personales/calle" type="text" placeholder="Morelos" required>
        </div> 
        <div class="input-append">
            <label>*No. Casa Exterior</label>
            <input name="datos_personales/no_casa_ext" type="text" placeholder="218" required>
        </div> 
        <div class="input-append">
            <label>No. Casa Interior:</label>
            <input name="datos_personales/no_casa_int" type="text" placeholder="33-b">
        </div> 
        
        <div class="input-append">
            <label>*Colonia o Localidad:</label>
            <input name="datos_personales/colonia_localidad" type="text" placeholder="Independencia" required id="colonia_localidad">
        </div> 
FORM;
    }
    echo <<<FORM
        <div class="input-append">
            <label>*C&oacute;digo Postal:</label>
            <input name="datos_personales/codigo_postal" type="text" maxlength="5"  min="5" placeholder="50010" required id="codigoPostal">
        </div> 
FORM;
    if ($usuario == "todos") {
        echo <<<FORM
        <div class="input-append">
            <label>*Delegaci&oacute;n o Municipio:</label>
            <input name="datos_personales/delegacion_municipio" type="text" placeholder="Toluca" required id="delegacion_municipio">
        </div>
FORM;
    }
    echo <<<FORM
        <div class="input-append">
            <label>Entidad Federativa:</label>
            <select name="datos_personales/id_entidad_federativa">
FORM;
    comboEntidades();
    echo <<<FORM
            </select>
        </div> 
FORM;
    if ($usuario == "todos") {
        echo <<<FORM
        <div class="input-append">
            <label>Zona Horaria:</label>
            <select name="datos_personales/zona_horaria">
                <option value="GMT-6">GMT -6 (Tiempo del Centro)</option>
                <option value="GMT-7">GMT -7 (Tiempo del Pac&iacute;fico)</option>
                <option value="GMT-8">GMT -8 (Tiempo del Noroeste)</option>                            
            </select>
        </div>  
FORM;
    }
    echo <<<FORM
        <div class="input-append">
            <label>Tel&eacute;fono Fijo:</label>
            <input name="datos_personales/telefono_fijo" type="tel" placeholder="2283984509" maxlength="12" id="telefonoFijo">
        </div>        
        <div class="input-append">
            <label>Tel&eacute;fono M&oacute;vil:</label>
            <input name="datos_personales/telefono_movil" type="tel" placeholder="7823894621" maxlength="12" id="telefonoMovil">
        </div>
FORM;
}

/**
 * Funcion que inserta los datos personales
 * @param type $campos
 * @param type $datos
 * @return type
 */
function insertarDatos($tabla, $campos, $datos) {
    //Crea objeto para realizar consultas de sistema de gestion
    $query = new Query("SG");

    $query->insert($tabla, $campos, $datos);
    return $query->ultimoID($tabla);
}

/**
 * Realiza edicion sobre una tabla
 * @param type $tabla nombre de la tabla
 * @param type $sets valores de los sets
 * @param type $where condicion
 */
function editaDatos($tabla, $sets, $where) {
    $query = new Query("SG");
    $sql = "UPDATE $tabla set $sets WHERE $where";
//    echo "<br>SQL:$sql";
    $query->update($sql);
}

/**
 * Elimina registros de la base de datos
 * @param type $tabla
 * @param type $where
 */
function borrarDatos($tabla, $where) {
    $query = new Query("SG");
    $query->delete($tabla, $where);
}

/**
 * Funcion que imprime una tabla de usuarios adaptada a DataTables
 * con las siguientes columnas:
 * Nombre de Usuario, Correo, Nombre, Primer Apellido, Segundo Apellido
 * 
 * MODIFICACIÓN PARA EL CHANGE CONTROL 0.99.3
 * FECHA: 16 DE DICEMBRE DEL 2013
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * MODIFICACIÓN: EN EL TITLE PARA EL BOTON DE EDITAR SE CAMBIO A ESPAÑOL
 * CAMBADO LA PALABRA "Edit" por "Editar"
 * @param type $tipo_usuario
 */
function tablaUsuarios($tipo_usuario) {

    $select = "dp.id_datos_personales, dp.nombre_usuario, dp.correo, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido";
    $from = "datos_personales dp";
    $extraHead = "";
    $extraBody = "";
    switch ($tipo_usuario) {
        //inicia control de cambios #6
        case "estudiante":
            $select = $select . ", a.id_alumno id, e.nombre_escuela escuela, i.nombre_institucion institucion, n.nombre nivel, ge.nombre_grado grado
                , g.nombre_grupo grupo";
            $from = $from . ", alumnos a
                left join escuelas e 
                    on e.id_escuela = a.id_escuela
                left join instituciones i
                    on e.id_institucion = i.id_institucion
                left join grado_escolar ge
                    on a.id_grado_escolar = ge.id_grado_escolar
                left join nivel_escolar n
                    on ge.id_nivel = n.id_nivel
                
                left join grupo_alumno ga
                    on a.id_alumno = ga.id_alumno
                left join grupo g
                    on g.id_grupo = ga.id_grupo";
            $where = "a.id_datos_personales = dp.id_datos_personales and   dp.tipo_usuario=0 and a.tipo_alumno=0 and a.status=1";
            break;
        //finaliza control de cambios #6
        case "profesionista":
            $select = $select . ", id_alumno id";
            $from = $from . ", alumnos a";
            $where = "a.id_datos_personales = dp.id_datos_personales and dp.tipo_usuario=0 and a.tipo_alumno=1 and a.status=1";
            break;
        case "tutor":
            $select = $select . ", id_tutor id";
            $select = $select . ", rt.nombre rol";
            $from = $from . ", tutor t, rol_tutor rt";
            $where = "t.id_datos_personales = dp.id_datos_personales and t.status=1 and t.id_rol_tutor = rt.id_rol_tutor and dp.tipo_usuario=1";
            break;
        case "profesor":
            $select = $select . ", id_profesor_aula id";
            $from = $from . ", profesores_aula p";
            $where = "p.id_datos_personales = dp.id_datos_personales and p.status=1 and dp.tipo_usuario=2";
            break;
        case "padre":
            $select = $select . ", id_padre id, ge.nombre_grado grado";
            $from = $from . ", padres p
                left join grado_escolar ge
                    on p.id_ultimo_grado_escolar = ge.id_grado_escolar
                left join nivel_escolar n
                    on ge.id_nivel = n.id_nivel";
            $where = "p.id_datos_personales = dp.id_datos_personales and p.status=1 and dp.tipo_usuario=3";
            break;
        case "gestor":
            $select = $select . ", id_gestor_contenido id";
            $from = $from . ", gestor_contenido g";
            $where = "g.id_datos_personales = dp.id_datos_personales and g.status=1 and dp.tipo_usuario=4";
            break;
        case "admin":
            $select = $select . ", id_datos_personales id";
            $where = "dp.tipo_usuario=5";
            break;
    }

    $query = new Query("SG");
    $query->sql = "select $select from $from where $where";
//    echo "sql:".$query->sql;
    $resultados = $query->select("obj");

    echo <<<TABLA
        <table cellpadding="0" cellspacing="0" border="0" class="display tabla">
            <thead>
                <tr>
                    <th>Acci&oacute;n</th>
                    <th>id</th>
TABLA;
    if ($tipo_usuario == "tutor") {
        echo "<th>Tipo de Tutor</th>";
    }
    if ($tipo_usuario == "estudiante") {
        $extraHead = "
            <th>Instituci&oacute;n</th>
            <th>Escuela</th>
            <th>Nivel educativo</th>
            <th>Grado escolar</th>
            <th>Grupo</th>
            ";
    }
    echo <<<TABLA
                    <th>Nombre de Usuario</th>
                    <th>Correo</th>
                    <th>Nombre</th>
                    <th>Primer Apellido</th>
                    <th>Segundo Apellido</th>
                    $extraHead
                </tr>                    
            </thead>
            <tbody>
TABLA;

    if ($resultados) {

        foreach ($resultados as $res) {
            echo <<<TABLA
            <tr>
                <td>
                    <a class="icon-eye-open verDatosPersonales" name="$tipo_usuario" id="$res->id_datos_personales" title="Ver" href="#verModal" role="button" data-toggle="modal"></a>
                    <a class="icon-edit editarDatosPersonales" name="$tipo_usuario" id="$res->id_datos_personales" title="Editar" href="#editarModal" role="button" data-toggle="modal"></a>
                    <a class="icon-trash" title="Borrar" href="borrarUsuario.php?id=$res->id_datos_personales" onClick="return confirm('¿Está seguro?');"></a>
                </td>
                <td>$res->id_datos_personales</td>
TABLA;
            if ($tipo_usuario == "tutor") {
                echo "<th>" . $res->rol . "</th>";
            }
            echo <<<TABLA
                <td>$res->nombre_usuario</td>
                <td>$res->correo</td>
                <td>$res->nombre_pila</td>
                <td>$res->primer_apellido</td>
                <td>$res->segundo_apellido</td>
TABLA;
            if ($tipo_usuario == "estudiante") {
                $algo = <<<ESTUDIANTES
                <td>$res->institucion</td>
                <td>$res->escuela</td>
                <td>$res->nivel</td>
                <td>$res->grado</td>
                <td>$res->grupo</td>
ESTUDIANTES;
//                echo html_entity_decode($algo);
                echo ($algo);
            }

            echo "</tr>";
        }
    }

    echo <<<TABLA
            </tbody>
        </table>
TABLA;
}

/**
 * Funcion que verifica la existencia de un usuario
 * Si existe retorna verdadero
 * Si no existe retorna falso
 * @param type $usuario nombre de usuario
 * @return type boolean JSON
 */
function consultaUsuarioJSON($usuario, $id_datos_personales = NULL) {
    return json_encode(consultaUsuario($usuario, $id_datos_personales));
}

/**
 * Verifica la existencia de un usuario
 * Si existe retorna verdadero
 * Si no existe retorna falso
 * @param type $usuario nombre de usuario
 * @return boolean
 */
function consultaUsuario($usuario, $id_datos_personales = NULL, $status = true) {
    $query = new Query("SG");
    $usuario = strtolower(__($usuario));
    //Verifica si hay registros con ese usuario
    if ($id_datos_personales != NULL) {
        $query->sql = "select * from datos_personales where lower(nombre_usuario)='$usuario' and id_datos_personales != $id_datos_personales";
    } else {
        $query->sql = "select * from datos_personales where lower(nombre_usuario)='$usuario'";
    }

    $resultados = $query->select("obj");

    //Si existe una coincidencia verifica si tiene status activo
    if ($resultados) {
        foreach ($resultados as $res) {
            if ($status === true) {
                $tipoUsuario = getTipoUsuario($res->id_datos_personales);
                if ($tipoUsuario["tabla"] == "datos_personales") {
                    return true;
                } else {
                    $tabla = $tipoUsuario["tabla"];
                    $nombreId = $tipoUsuario["id"];
                    $valorId = $tipoUsuario["valor_id"];

                    $query = new Query("SG");
                    $query->sql = <<<SQL
                    select * from $tabla where status = 1 and $nombreId=$valorId
SQL;
                    $verificacion = $query->select("obj");
                    if ($verificacion) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                return true;
            }
        }

        return true;
    } else {
        return false;
    }
}

/**
 * Funcion que verifica la existencia de un correo
 * Si existe retorna verdadero
 * Si no existe retorna falso
 * @param type $correo
 * @return type boolean JSON
 */
function consultaCorreoJSON($correo, $id_datos_personales = NULL) {
    return json_encode(consultaCorreo($correo, $id_datos_personales));
}

/**
 * Funcion que verifica la existencia de un correo
 * Si existe retorna verdadero
 * Si no existe retorna falso
 * @param type $correo
 * @param type $id_datos_personales
 * @param type $activo, si quieremos que busque en los contactos activos, si se le manda
 * un false, no hara distincion entre usuarios activos o inactivos
 * @return boolean
 */
function consultaCorreo($correo, $id_datos_personales = NULL, $activo = true, $return = "boolean") {
    $query = new Query("SG");
    $correo = strtolower(__($correo));

    if ($id_datos_personales != NULL) {
        $query->sql = "select id_datos_personales from datos_personales where lower(correo)='$correo' and id_datos_personales != $id_datos_personales";
    } else {
        $query->sql = "select id_datos_personales from datos_personales where lower(correo)='$correo'";
    }

    $resultados = $query->select("obj");

    //Si retorno registros, validar que estos se encuentren en status activo
    if ($resultados) {
        if ($activo === true) {
            foreach ($resultados as $res) {
                $tipoUsuario = getTipoUsuario($res->id_datos_personales);
                if ($tipoUsuario["tabla"] == "datos_personales") {
                    if ($return = "boolean")
                        return $res->id_datos_personales;
                    else
                        return false;
                } else {
                    $tabla = $tipoUsuario["tabla"];
                    $nombreId = $tipoUsuario["id"];
                    $valorId = $tipoUsuario["valor_id"];

                    $query = new Query("SG");
                    $query->sql = <<<SQL
                    select * from $tabla where status = 1 and $nombreId=$valorId
SQL;
                    $verificacion = $query->select("obj");
                    if ($verificacion) {
                        if ($return = "boolean")
                            return $res->id_datos_personales;
                        else
                            return false;
                    } else {
                        return false;
                    }
                }
            }
        } else {
            foreach ($resultados as $res) {
                if ($return = "boolean")
                    return $res->id_datos_personales;
                else
                    return false;
            }
        }
    } else {
        return false;
    }
}

/**
 * Borrar a un usuario especificando el id de datos personales
 * @param type $id
 * @param type $tipo_usuario
 */
function borrarUsuario($id) {

    //Obtenemos el tipo de usuario 
    $tipoUsuario = getTipoUsuario($id);

    //Eliminacion de administrador
    if ($tipoUsuario["tabla"] == "datos_personales") {
        $query = new Query("SG");
        $query->delete("datos_personales", "id_datos_personales=$id");
    } else {
        //Eliminacion de cualquier otro usuario diferente al administrador
        $query = new Query("SG");
        $query->sql = "UPDATE " . $tipoUsuario["tabla"] . " SET status=0 WHERE " . $tipoUsuario["id"] . "=" . $tipoUsuario["valor_id"];
//        echo $query->sql;
        $query->update($query->sql);
    }
}

/**
 * Funcion que consulta los datos personales de un usuario (cualquier tipo)
 * @param type $id
 * @return type
 */
function consultaDatosPersonales($id) {
    //Obtenemos el tipo de usuario 
    $tipoUsuario = getTipoUsuario($id);

    $campos = "";
    $tabla = "";
    $where = "";
    $alumnos = array();
    //var_dump($tipoUsuario);
    switch ($tipoUsuario["tabla"]) {
        case "alumnos":
            switch ($tipoUsuario["tipo_alumno"]) {
                case "estudiante":
                    $campos = <<<SQL
                        ,a.id_alumno, 
                        a.status, 
                        a.matricula, 
                        a.id_padre, 
                        a.id_profesor_aula, 
                        a.id_grado_escolar, 
                        a.id_escuela, 
                        e.id_institucion,
                        a.puesto_ocupacion, 
                        (SELECT dp2.nombre_pila || ' ' || dp2.primer_apellido || ' ' || dp2.segundo_apellido
                         FROM datos_personales dp2, padres p2
                         WHERE p2.id_datos_personales = dp2.id_datos_personales
                           and p2.id_padre = p.id_padre) nombre_padre,
                        (SELECT dp3.nombre_pila || ' ' || dp3.primer_apellido || ' ' || dp3.segundo_apellido
                         FROM datos_personales dp3, profesores_aula pa2
                         WHERE pa2.id_datos_personales = dp3.id_datos_personales
                           and pa2.id_profesor_aula = pa.id_profesor_aula) nombre_profesor,
                        e.nombre_escuela,
                        ne.nombre nivel_escolar,
                        ge.nombre_grado grado_escolar,                        
                        i.nombre_institucion,
                        ne.id_nivel
                        
SQL;
//                    $tabla = ", " . $tipoUsuario["tabla"] . " a, padres p, profesores_aula pa, nivel_escolar ne, grado_escolar ge, escuelas e, instituciones i ";
                    $tabla = <<<sql
                                , alumnos a
                                LEFT JOIN padres p
                                    ON a.id_padre = p.id_padre
                                LEFT JOIN profesores_aula pa
                                    ON a.id_profesor_aula = pa.id_profesor_aula
                                , nivel_escolar ne, grado_escolar ge, escuelas e, instituciones i
sql;
                    $where = <<<SQL
                        and a.id_datos_personales = dp.id_datos_personales 
                        and a.id_escuela = e.id_escuela
                        and e.id_institucion = i.id_institucion
                        and a.tipo_alumno=0
                        and a.id_grado_escolar = ge.id_grado_escolar
                        and ge.id_nivel = ne.id_nivel
SQL;
                    break;
                case "profesionista":
                    $campos = <<<SQL
                        , a.id_alumno, a.status, a.id_empresa, e.nombre_empresa, ne.nombre nivel_escolar, ge.nombre_grado grado_escolar, ge.id_grado_escolar, ne.id_nivel
SQL;
                    $tabla = ", " . $tipoUsuario["tabla"] . " a, empresa e, grado_escolar ge, nivel_escolar ne";
                    $where = "and a.id_datos_personales = dp.id_datos_personales and a.id_empresa = e.id_empresa and a.tipo_alumno=1 and a.id_grado_escolar = ge.id_grado_escolar and ge.id_nivel = ne.id_nivel";
                    break;
            }
            break;
        case "tutor":
            $campos = <<<SQL
                , t.id_tutor, t.status, t.id_rol_tutor, rt.nombre nombre_rol
SQL;
            $tabla = ", " . $tipoUsuario["tabla"] . " t, rol_tutor rt";
            $where = "and t.id_datos_personales = dp.id_datos_personales and t.id_rol_tutor = rt.id_rol_tutor";
            break;
        case "profesores_aula":
            $campos = <<<SQL
                , pa.id_profesor_aula, 
                  pa.status, 
                  pa.puesto_ocupacion, 
                  pa.id_escuela, 
                  pa.id_grado_escolar_enrolado, 
                  ne.id_nivel, 
                  e.nombre_escuela, 
                  i.nombre_institucion, 
                  i.id_institucion, 
                  ge.nombre_grado, 
                  ne.nombre nivel_escolar
SQL;
            $tabla = <<<tabla
                    , escuelas e, instituciones i, profesores_aula pa
                left join grado_escolar ge
                    on pa.id_grado_escolar_enrolado = ge.id_grado_escolar
                left join nivel_escolar ne
                    on ge.id_nivel = ne.id_nivel
tabla;

            $where = <<<SQL
              and pa.id_datos_personales = dp.id_datos_personales 
              and pa.id_escuela= e.id_escuela
              and e.id_institucion = i.id_institucion             
              
SQL;

            $alumnos = getAlumnosProfesor($tipoUsuario["valor_id"]);
            break;
        case "padres":
            $campos = <<<SQL
                , p.id_padre, p.status, p.id_ultimo_grado_escolar, ge.nombre_grado, ne.nombre nivel_escolar, ne.id_nivel
SQL;
            $tabla = <<<tabla
                , padres p  
                left join grado_escolar ge
                    on p.id_ultimo_grado_escolar = ge.id_grado_escolar
                left join nivel_escolar ne
                    on ge.id_nivel = ne.id_nivel
tabla;

            $where = "and p.id_datos_personales = dp.id_datos_personales";
            $alumnos = getHijosPadre($tipoUsuario["valor_id"]);
            break;
        case "gestor_contenido":
            $campos = <<<SQL
                , gc.id_gestor_contenido, gc.status
SQL;
            $tabla = ", " . $tipoUsuario["tabla"] . " gc";
            $where = "and gc.id_datos_personales = dp.id_datos_personales";
            break;
        case "datos_personales":
            break;
    }

    $query = new Query("SG");
    $query->sql = <<<SQL
            SELECT dp.id_datos_personales,
                   dp.nombre_pila,
                   dp.primer_apellido,
                   dp.segundo_apellido,
                   dp.nombre_usuario,
                   dp.correo,
                   dp.contrasena,
                   dp.fecha_nacimiento,
                   dp.curp,
                   dp.codigo_postal,
                   dp.calle,
                   dp.no_casa_ext,
                   dp.no_casa_int,
                   dp.colonia_localidad,
                   dp.delegacion_municipio,
                   dp.id_entidad_federativa,
                   dp.id_nacionalidad,
                   dp.zona_horaria,
                   dp.telefono_fijo,
                   dp.telefono_movil,
                   dp.tipo_usuario,
                   dp.url_foto,
                   n.nacionalidad,
                   ef.nombre_entidad
                   $campos
            FROM datos_personales dp
       LEFT JOIN nacionalidad n
              ON n.id_nacionalidad=dp.id_nacionalidad  
       LEFT JOIN entidad_federativa ef 
              ON dp.id_entidad_federativa = ef.id_entidad_federativa
                   $tabla
            WHERE dp.id_datos_personales=$id $where
SQL;
//    echo $query->sql;
    
    //echo $query->sql;
    $resultados = $query->select("arr");

    if ($resultados) {
        foreach ($resultados as $res) {
            if ($tipoUsuario["tabla"] == "padres" || $tipoUsuario["tabla"] == "profesores_aula") {
                $res["alumnos"] = $alumnos;
            }
            //HTML entity decode
//            foreach($res as $campo => $valor){
//                $res[$campo]=  html_entity_decode($valor);
//            }
            //Darle formato a la fehca
            //Transforma el texto en un formato especial a fecha
            $fechaNacimiento = DateTime::createFromFormat("Y-m-d", $res["fecha_nacimiento"]);
            //Guarda la fecha en el formato que necesita PostgreSQL
            $res["fecha_nacimiento"] = $fechaNacimiento->format('d/m/Y');

            return $res;
        }
    }
}

/**
 * Consulta los datos personales mas los datos escpecificos de cierto id en la tabla de datos personales
 * @param type $id
 * @return type todos los datos en un objeto encodeado JSON
 */
function consultaDatosPersonalesJSON($id) {
    $objJSON = json_encode(consultaDatosPersonales($id));
    return $objJSON;
}

/**
 * De acuerdo al id de datos personales retorna en un arreglo la tabla
 * a la que pertenece el usuario y el nombre de su llave primaria
 * @param type $id id_datos_personales de la tabla de datos_personales
 * @return type arreglo(tabla, id)
 */
function getTipoUsuario($id = NULL, $correo = NULL, $nombreUsuario = NULL) {
    $query = new Query("SG");

    if (isset($id)) {
        $query->sql = "SELECT * FROM datos_personales where id_datos_personales=$id";
    } else if (isset($correo)) {
        $query->sql = "SELECT * FROM datos_personales where correo='$correo'";
    } else if (isset($nombreUsuario)) {
        $query->sql = "SELECT * FROM datos_personales where nombre_usuario='$nombreUsuario'";
    }

    $resultados = $query->select("obj");

    if ($resultados) {
        foreach ($resultados as $result) {
            $id = $result->id_datos_personales;
            switch ($result->tipo_usuario) {
                case 0:
                    $query->sql = "SELECT id_alumno, tipo_alumno FROM alumnos where id_datos_personales=$id";
                    $resultados = $query->select("obj");
                    if ($resultados) {
                        foreach ($resultados as $res) {
                            if ($res->tipo_alumno == 0) {
                                return array("tabla" => "alumnos", "id" => "id_alumno", "tipo_alumno" => "estudiante", "valor_id" => $res->id_alumno);
                            }
                            if ($res->tipo_alumno == 1) {
                                return array("tabla" => "alumnos", "id" => "id_alumno", "tipo_alumno" => "profesionista", "valor_id" => $res->id_alumno);
                            }
                        }
                    }
                case 1:
                    $query->sql = "SELECT id_tutor FROM tutor where id_datos_personales=$id";
                    $resultados = $query->select("obj");
                    if ($resultados) {
                        foreach ($resultados as $res) {
                            return array("tabla" => "tutor", "id" => "id_tutor", "valor_id" => $res->id_tutor);
                        }
                    }
                case 2:
                    $query->sql = "SELECT id_profesor_aula FROM profesores_aula where id_datos_personales=$id";
                    $resultados = $query->select("obj");
                    if ($resultados) {
                        foreach ($resultados as $res) {
                            return array("tabla" => "profesores_aula", "id" => "id_profesor_aula", "valor_id" => $res->id_profesor_aula);
                        }
                    }
                case 3:
                    $query->sql = "SELECT id_padre FROM padres where id_datos_personales=$id";
                    $resultados = $query->select("obj");
                    if ($resultados) {
                        foreach ($resultados as $res) {
                            return array("tabla" => "padres", "id" => "id_padre", "valor_id" => $res->id_padre);
                        }
                    }
                case 4:
                    $query->sql = "SELECT id_gestor_contenido FROM gestor_contenido where id_datos_personales=$id";
                    $resultados = $query->select("obj");
                    if ($resultados) {
                        foreach ($resultados as $res) {
                            return array("tabla" => "gestor_contenido", "id" => "id_gestor_contenido", "valor_id" => $res->id_gestor_contenido);
                        }
                    }
                case 5:
                    return array("tabla" => "datos_personales", "id" => "id_datos_personales");
            }
        }
    }
}

/**
 * CHANGE CONTROL 0.99.8
 * FECHA DE MODIFICACION: 21 DE ENERO DEL 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJETIVO: MODIFICACION PARA IMPRIMIR CAMPOS ESPECIALES PARA
 * TUTORES Y GESTORES DE CONTENIDO
 */

/**
 * Funcion que imprime tabla para llenar datos personales de los usuarios
 */
function imprimirVerDatosPersonales($usuario = "todos") {
    echo <<<HTML
        <tr>
            <td colspan="2"><b>DATOS DE ACCESO</b></td>
        </tr>
        <tr>
            <td>Nombre de Usuario:</td>
            <td><div id="ver_nombre_usuario"></div></td>
        </tr>
        <tr>
            <td>Correo:</td>
            <td ><div id="ver_correo"></div></td>
        </tr>

        <tr>
            <td colspan="2"><b>DATOS PERSONALES</b></td>
        </tr>
        <tr>
            <td>id:</td>
            <td><div id="ver_id_datos_personales"></div></td>
        </tr>
        <tr>
            <td>Nombre:</td>
            <td ><div id="ver_nombre_pila"></div></td>
        </tr>
        <tr>
            <td>Primer Apellido:</td>
            <td ><div id="ver_primer_apellido"></div></td>
        </tr>
        <tr>
            <td>Segundo Apellido:</td>
            <td ><div id="ver_segundo_apellido"></div></td>
        </tr>
        <tr>
            <td>Fecha de Nacimiento:</td>
            <td ><div id="ver_fecha_nacimiento"></div></td>
        </tr>
HTML;
    if ($usuario == "todos") {
        echo <<<HTML
        <tr>
            <td>CURP:</td>
            <td ><div id="ver_curp"></div></td>
        </tr>
        <tr>
            <td>Nacionalidad:</td>
            <td ><div id="ver_nacionalidad"></div></td>
        </tr>
HTML;
    }
    echo <<<HTML
        <tr>
            <td colspan="2"><b>DOMICILIO</b></td>
        </tr>
HTML;
    if ($usuario == "todos") {
        echo <<<HTML
        <tr>
            <td>Calle:</td>
            <td ><div id="ver_calle"></div></td>
        </tr>
        <tr>
            <td>No. Casa Ext:</td>
            <td ><div id="ver_no_casa_ext"></div></td>
        </tr>
        <tr>
            <td>No. Casa Int:</td>
            <td ><div id="ver_no_casa_int"></div></td>
        </tr>
        <tr>
            <td>Colonia o Localidad:</td>
            <td ><div id="ver_colonia_localidad"></div></td>
        </tr> 
HTML;
    }
    echo <<<HTML
        <tr>
            <td>C&oacute;digo Postal:</td>
            <td ><div id="ver_codigo_postal"></div></td>
        </tr>
HTML;
    if ($usuario == "todos") {
        echo <<<HTML
        <tr>
            <td>Delegaci&oacute;n o Municipio:</td>
            <td ><div id="ver_delegacion_municipio"></div></td>
        </tr>
HTML;
    }
    echo <<<HTML
        <tr>
            <td>Entidad Federativa:</td>
            <td ><div id="ver_entidad_federativa"></div></td>
        </tr>
HTML;
    if ($usuario == "todos") {
        echo <<<HTML
        <tr>
            <td>Zona Horaria:</td>
            <td><div id="ver_zona_horaria"></div></td>
        </tr>
HTML;
    }
    echo <<<HTML
        <tr>
            <td>Tel&eacute;fono Fijo:</td>
            <td ><div id="ver_telefono_fijo"></div></td>
        </tr>
        <tr>
            <td>Tel&eacute;fono M&oacute;vil</td>
            <td ><div id="ver_telefono_movil"></div></td>
        </tr>
HTML;
}

/**
 * Función que imprime el html para editar los datos personales
 * 
 * FECHA DE MODIFICACIÓN: 16 DE DICIEMBRE DEL 2013
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * SE CORRGIERON LOS ID'S DE LA CONTRASEÑA Y CONFIRMAR CONTRASEÑA
 * 
 * CHANGE CONTROL 0.99.8
 * FECHA DE MODIFICACIÓN: 21 DE ENERO DEL 2013
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * OBJEVITO: EDITAR TUTORES Y GESTORES
 */
function imprimeEditarDatosPersonales($usuario = "todos") {
    echo <<<HTML
        <tr>
            <td colspan="2"><b>DATOS DE ACCESO</b></td>
        </tr>
        <tr>
            <td>*Nombre de Usuario:</td>
            <td>
                <input name="datos_personales/nombre_usuario" id="edita_nombre_usuario" type="text" required/>
                <label class="text-error" id="errorUsuario"></label>
            </td>
        </tr>
        <tr>
            <td>*Correo:</td>
            <td>
                <input name="datos_personales/correo" id="edita_correo" type="email" required/>
                <label class="text-error" id="errorCorreo"></label>
            </td>
        </tr>
        <tr>
            <td>Actualizar Contrase&ntilde;a</td>
            <td><input type="checkbox" id="habEditContrasena" name="habEditContrasena"></td>
        </tr>
        <tr>
            <td>*Contrase&ntilde;a:</td>
            <td>
                <input name="datos_personales/contrasena" id="contrasena" type="password" required class="editaContrasena"/>
                <label class="text-error" id="errorContrasena"></label>
            </td>
        </tr>
        <tr>
            <td>*Confirma Contrase&ntilde;a:</td>
            <td>
                <input id="confirmaContrasena" type="password" required class="editaContrasena"/>
                <label class="text-error" id="errorConfirmaContrasena"></label>
            </td>
        </tr>
        <tr>
            <td colspan="2"><b>DATOS PERSONALES</b></td>
        </tr>                        
        <tr>
            <td>*Nombre:</td>
            <td><input name="datos_personales/nombre_pila" id="edita_nombre_pila" type="text" required/></td>
        </tr>
        <tr>
            <td>*Primer Apellido:</td>
            <td><input name="datos_personales/primer_apellido" type="text" id="edita_primer_apellido" required/></td>
        </tr>
        <tr>
            <td>*Segundo Apellido:</td>
            <td><input name="datos_personales/segundo_apellido" id="edita_segundo_apellido" type="text"/></td>
        </tr>
        <tr>
            <td>*Fecha de Nacimiento (dd/mm/yyyy):</td>
            <td>
                <input name="datos_personales/fecha_nacimiento" id="edita_fecha_nacimiento" pattern="\d{1,2}/\d{1,2}/\d{4}" type="text" required/>
                <label class="text-error" id="errorFecha"></label>
            </td>
        </tr>
HTML;
    if ($usuario == "todos") {
        echo <<<HTML
        <tr>
            <td>CURP:</td>
            <td><input name="datos_personales/curp" id="edita_curp" type="text" maxlength="18" /></td>
        </tr>
        <tr>
            <td>Nacionalidad:</td>
            <td>
                <select name="datos_personales/id_nacionalidad" id="edita_id_nacionalidad">
HTML;
        comboNacionalidades();
        echo <<<HTML
                </select>
            </td>
        </tr>
HTML;
    }
    echo <<<HTML
        <tr>
            <td colspan="2"><b>DOMICILIO</b></td>
        </tr> 
HTML;
    if ($usuario == "todos") {
        echo <<<HTML
        <tr>
            <td>*Calle:</td>
            <td><input name="datos_personales/calle" id="edita_calle" type="text" required></td>
        </tr>
        <tr>
            <td>No. Casa Ext:</td>
            <td><input name="datos_pesonales/no_casa_ext" id="edita_no_casa_ext" type="text" ></td>
        </tr>
        <tr>
            <td>No. Casa Int:</td>
            <td><input name="datos_personales/no_casa_int" id="edita_no_casa_int" type="text"></td>
        </tr>
        <tr>
            <td>*Colonia o Localidad:</td>
            <td><input name="datos_personales/colonia_localidad" id="edita_colonia_localidad" type="text" required></td>
        </tr>
HTML;
    }
    echo <<<HTML
        <tr>
            <td>*C&oacute;digo Postal:</td>
            <td><input name="datos_personales/codigo_postal" id="edita_codigo_postal" type="text" maxlength="5" id="codigoPostal" required/></td>
        </tr>
HTML;
    if ($usuario == "todos") {
        echo <<<HTML
        <tr>
            <td>*Delegaci&oacute;n o Municipio:</td>
            <td><input name="datos_personales/delegacion_municipio" id="edita_delegacion_municipio" type="text" required></td>
        </tr>
HTML;
    }
    echo <<<HTML
        <tr>
            <td>Entidad Federativa:</td>
            <td>
                <select name="datos_personales/id_entidad_federativa" id="edita_id_entidad_federativa">
HTML;
    comboEntidades();
    echo <<<HTML
                </select>
            </td>
        </tr>
HTML;
    if ($usuario == "todos") {
        echo <<<HTML
        <tr>
            <td>Zona Horaria:</td>
            <td>
                <select name="datos_personales/zona_horaria" id="edita_zona_horaria">
                    <option value="GMT -6">GMT -6</option>
                    <option value="GMT -7">GMT -7</option>
                    <option value="GMT -8">GMT -8</option>                            
                </select>
            </td>
        </tr>
HTML;
    }
    echo <<<HTML
        <tr>
            <td>Tel&eacute;fono Fijo:</td>
            <td><input name="datos_personales/telefono_fijo" id="edita_telefono_fijo" type="tel" maxlength="12" id="telefonoFijo"></td>
        </tr>
        <tr>
            <td>Tel&eacute;fono M&oacute;vil</td>
            <td><input name="datos_personales/telefono_movil" id="edita_telefono_movil" type="tel" maxlength="12" id="telefonoMovil"></td>
        </tr>
HTML;
}

/**
 * Funcion que retorna todos los alumnos relacionados a un padre
 * @param type $id id del padre
 * @return type arreglo con los alumnos
 */
function getHijosPadre($id) {
    $query = new Query("SG");
    $query->sql = <<<SQL
          SELECT id_datos_personales FROM alumnos WHERE id_padre=$id
SQL;

    $resultados = $query->select("obj");
    $hijos = array();
    $i = 0;
    if ($resultados) {
        foreach ($resultados as $res) {
            $hijos[$i] = consultaDatosPersonales($res->id_datos_personales);
            $i++;
        }
        return $hijos;
    }
}

/**
 * Funcion que retorna los alumnos relacionados a un profesor
 * @param type $id id del profesor
 * @return type arreglo de alumnos con todos sus datos
 */
function getAlumnosProfesor($id) {
//    echo "getAlumnosProfesor:$id";
    $query = new Query("SG");
    $query->sql = <<<SQL
          SELECT id_datos_personales FROM alumnos WHERE id_profesor_aula=$id
SQL;

    $resultados = $query->select("obj");
    $alumnos = array();
    $i = 0;
    if ($resultados) {
        foreach ($resultados as $res) {
            $alumnos[$i] = consultaDatosPersonales($res->id_datos_personales);
            $i++;
        }
    }

    return $alumnos;
}

/**
 * Funcion que actualiza la informacion de un usuario
 * Retorna true si se hizo la actualizacion correctamente
 * Retorna false si no paso la validacion
 * @param type $POST
 * @param type $tablaExtra
 * @param type $id_datos_personales
 * @return boolean
 */
function actualizaUsuario($POST, $tablaExtra = NULL, $id_datos_personales) {
    //Siempre se van a recibir datos de la tabla de datos personales
    //La tabla extra podria perteneces a:
    //Alumnos, Padres de familia, Profesores de aula, tutores, gestores de contenido
    //La tabla de admin no tiene una tabla extra
    //Si trae un tabla extra
    if (isset($tablaExtra)) {
//        echo " Hay tabla extra";
        $sets = destripaPostEdicion($POST, "/", "datos_personales, $tablaExtra");
    } else {
//        echo " No hay tabla extra";
        $sets = destripaPostEdicion($_POST, "/", "datos_personales");
    }
//    var_dump($sets);
    //Verifica la existencia del 
    if (!consultaCorreo($POST["datos_personales/correo"], $id_datos_personales) && !consultaUsuario($POST["datos_personales/usuario"], $id_datos_personales)) {
        //Listo, este usuario puede editar todos su datos
//        echo " Es editable!";
//        echo "<br>sets:";
//        var_dump($sets);
        editaDatos("datos_personales", $sets["datos_personales"], "id_datos_personales=$id_datos_personales");

        if (isset($tablaExtra)) {
            editaDatos($tablaExtra, $sets[$tablaExtra], "id_datos_personales=$id_datos_personales");
        }

        return true;
    } else {
        return false;
    }
}

/**
 * Envia correo de bienvenida a un usuario recien registrado
 * @param type $correo
 * @param type $nombreUsuario
 * @param type $password
 * @param type $perfil
 */
function correoUsuarioNuevo($correo, $nombreUsuario, $password, $perfil = "") {
//    $idDatosPersonales = obtieneIDdeCorreo($correo);
//    if ($idDatosPersonales) {

    if ($perfil == "") {
        $arrayTipoUsuario = getTipoUsuario(NULL, $correo, NULL);
//        echo "Tipo de usuario correo nuevo:";
//        var_dump($arrayTipoUsuario);
        $tipoUsuario = $arrayTipoUsuario['tabla'];

        switch ($tipoUsuario) {
            case "alumnos":
                $tipoUsuario = "Alumno";
                break;
            case "tutor":
                $tipoUsuario = "Tutor";
                break;
            case "profesores_aula":
                $tipoUsuario = "Profesor de Aula";
                break;
            case "padres":
                $tipoUsuario = "Padre de Familia";
                break;
            case "gestor_contenido":
                $tipoUsuario = "Gestor de Contenido";
                break;
            case "datos_personales":
                $tipoUsuario = "Administrador";
                break;
        }
    } else {
        $tipoUsuario = $perfil;
    }

    $destinatario = $correo;
    $cuerpo = <<<correo
    <html>
    <head>
    <title>Bienvenido a MetaSpace</title>
    </head>
    <body>   
    <h1>Bienvenido a MetaSpace</h1>
    <p>Ha sido creada tu nueva cuenta de usuario, tus claves de acceso son las siguientes:</p>
    <ul>
        <li><b>Correo:</b> $correo</li>
        <li><b>Nombre de Usuario:</b> $nombreUsuario</li>
        <li><b>Perfil:</b> $tipoUsuario</li>    
        <li><b>Contrase&ntilde;a:</b> $password</li>
    </ul>
            
    <p>Recuerda que para acceder puedes usar tu correo o nombre de usuario</p>
    
    
    Atentamente <br/>
    <b>MetaSpace.</b>
    </body>
    </html>
correo;

    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "X-Priority: 3\r\n";
    $headers .= "X-Mailer: PHP" . phpversion() . "\r\n";
    $headers .= "Reply-To: " . CUENTA_CORREO . "\r\n";
    $headers .= "From: MetaSpace";

    //Con copia para que no se vea la cadena de correos
//    $headers .="Cc: " . getCorreos() . "\r\n";
//    $correo = new Correo($destinatario,"Bienvenido a MetaSpace", $cuerpo);
//    $correo->enviar();
    if (@!mail($destinatario, "Bienvenido a MetaSpace", $cuerpo, $headers)) {
        echo "No se pudo enviar correo. En este momento";
    }
}

/**
 * Verifica si es necesario eliminar a un usuario que tenga status 0 para eliminarlo definitivamente
 * @param type $correo
 * @param type $usuario
 */
function verificaEliminacionUsuario($correo, $usuario) {
    //Si existe alguno con ese correo o usuario elimina en cascada
    if (($id = consultaCorreo($correo, NULL, false, "id")) !== false || ($id = consultaUsuario($usuario, NULL, false, "id") !== false)) {
//        imprimeConsola("Borrando usuario en cascada");
//        imprimeConsola("id_datos_personales: $id");
        $query = new Query("SG");
        $query->delete("datos_personales", "id_datos_personales=$id");
    }
}

/**
 * @author HMP
 * Obtiene el id_datos_personales en base al  correo
 * @return id_datos_personales
 */
function obtieneIDdeCorreo($correo) {
    $sql = "SELECT id_datos_personales as id
            FROM datos_personales 
            WHERE correo ='" . $correo . "'
            LIMIT 1";

    $consulta = new Query("SG");
    $consulta->sql = $sql;
    $resultado = $consulta->select("arr");

    if ($resultado) {
        return $resultado[0]['id'];
    }
    return null;
}

/**
 * CHANGE CONTROL 0.99.5
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * FECHA: 26 DE DICIEMBRE DEL 2013
 * OBJETIVO: FUNCIONES PARA INSERTAR USUARIO EN MOODLE
 * 
 * Funcion que inserta un usuario en moodle y le asigna un rol
 * @param type $username
 * @param type $password
 * @param type $firstname
 * @param type $lastname
 * @param type $email
 * @param type $rol
 * @return string|boolean
 */
function insertaUsuarioMoodle($username, $password, $firstname, $lastname, $email, $rol = NULL, $returnId = NULL) {
    try {
//        echo "<br>Creando cliente soap<br>";
        $client = new SoapClient(SERVER_URL);
//        echo "<br>Cliente inicializado<br>";
    } catch (SoapFault $e) {
//        die("<br>Error al crear cliente soap: $e<br>");
        return "Error al crear cliente soap: $e";
    }

    // Creacion de objeto usuario
    $functionname = 'core_user_create_users';
    $user1 = new stdClass();
    $user1->username = $username;
    $user1->password = $password;
    $user1->firstname = $firstname;
    $user1->lastname = $lastname;
    $user1->email = $email;
    $user1->auth = 'manual';
    $params = array($user1);
    try {
        $resp = $client->__soapCall($functionname, array($params));
        $idUsuario = $resp[0]["id"];
    } catch (exception $e) {
        echo "<br>Error al insertar usuario de moodle<br>";
        var_dump($e);
        echo($e->xdebug_message);
        return "Error al insertar usuario de moodle";
    }

    //ENrolar
    if (false) {
        if (($idRol = get_assignable_roles($rol)) !== false) {
//            imprimeConsola("Se quiere el rol:$rol");
//            imprimeConsola("Id del rol:$idRol");
//            var_dump($idRol);
            $functionname = 'core_role_assign_roles';
            $role = new stdClass();
            $role->roleid = intval($idRol);
            $role->userid = intval($idUsuario);
            $role->contextid = 1;
            $params = array($role);

            try {
//                var_dump(array($params));
                $resp = $client->__soapCall($functionname, array($params));
//                echo "Usuario enrolado correctamente";
//                var_dump($resp);
            } catch (exception $e) {
                echo "<br>Error al asignar rol al usuario en Moodle<br>";
                var_dump($e);
                echo($e->xdebug_message);
                return "Error al asignar rol al usuario en Moodle";
            }
        } else {
            return("<br>No existe el rol en Moodle, solo se pudo crear el usuario<br>");
        }
    }

    if (isset($returnId)) {
        return $idUsuario;
    } else {
        return true;
    }
}

/**
 * Función que consulta en moodle un rol en especifico y retorna su id
 * @param type $shortname
 * @return null
 */
function get_assignable_roles($shortname) {
    $query = new Query("MOD");
    $query->sql = "SELECT id FROM mdl_role WHERE shortname = '$shortname'";
    $resultSet = $query->select();

    if ($resultSet) {
        foreach ($resultSet as $result) {
            return $result->id;
        }
    } else {
        return false;
    }
}

/**
 * Funcion que consulta un usuario en moodle, buscando un parametro
 * que coincida por email o por nombre de usuario
 * @param type $userEmail
 * @return boolean
 */
function get_moodle_user($userEmail) {
    $query = new Query("MOD");
    $query->sql = "SELECT id FROM mdl_user WHERE username = '$userEmail' or email = '$userEmail'";
    $resultSet = $query->select();

    if ($resultSet) {
        foreach ($resultSet as $result) {
            return $result->id;
        }
    } else {
        return false;
    }
}

/**
 * Función que retorna nombre_usuario , correo 
 * de un Alumno en base a su $idAlumno
 * @param type $idAlumno
 * @return null
 */
function obtenerCorreoUsuarioAlumno($idAlumno) {
    if (!is_numeric($idAlumno))
        return NULL;

    $query = new Query("SG");
    $query->sql = "SELECT nombre_usuario, correo
        FROM alumnos al
        JOIN datos_personales dp
                ON dp.id_datos_personales = al.id_datos_personales
        WHERE al.status = 1 
        AND al.id_alumno = " . $idAlumno;
    $result = $query->select();

    if ($result) {
        foreach ($result as $res) {
            return $res;
        }
    }
    return null;
}

/**
 * Funcion que consulta el correo de un tutor
 * @param type $idTutor
 * @return null
 */
function obtenerCorreoUsuarioTutor($idTutor) {
    if (!is_numeric($idTutor))
        return NULL;

    $query = new Query("SG");
    $query->sql = "SELECT nombre_usuario, correo
        FROM tutor t
        JOIN datos_personales dp
                ON dp.id_datos_personales = t.id_datos_personales
        WHERE t.status = 1 
        AND t.id_tutor = " . $idTutor;
    $result = $query->select();

    if ($result) {
        foreach ($result as $res) {
            return $res;
        }
    }
    return null;
}

/**
 * CHANGE CONTROL 0.99.8
 * FECHA DE MODIFICACIÓN: 20 DE ENERO DEL 2014
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * OBJETIVO: VERIFICAR EN MOODLE LA EXISTENCIA DE UN USUARIO * 
 */

/**
 * Funcion que verifica la existencia de un usuario en Moodle
 * Si existe retorna true
 * Si no existe retorna false
 * @param type $username
 * @return boolean
 */
function verificarUsuarioMoodle($username) {
    if (isset($username)) {
        $query = new Query("MOD");
        $query->sql = "SELECT id FROM mdl_user WHERE username = '$username'";
        $resultSet = $query->select();

        if ($resultSet) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Funcion que verifica la existencia de un usuario de moodle y lo retorna en un objeto JSON
 * @param type $username
 * @return type
 */
function verificarUsuarioMoodleJSON($username) {
    return json_encode(verificarUsuarioMoodle($username));
}

/**
 * Function que genera opciones de un combo de los
 * profesores de aula en la base de datos
 */
function comboProfesoresAula() {
    $query = new Query("SG");

    $query->sql = "SELECT  p.id_profesor_aula, dp.nombre_pila, dp.primer_apellido, dp.segundo_apellido FROM profesores_aula p, datos_personales dp where p.id_datos_personales=dp.id_datos_personales and p.status = 1";
    $profesores = $query->select("obj");

    echo <<<combo
        <option value="">Seleccione un Profesor de Aula</option>
combo;
    if ($profesores) {
        foreach ($profesores as $profesor) {
            echo <<<HTML
                <option value="$profesor->id_profesor_aula">$profesor->nombre_pila $profesor->primer_apellido $profesor->segundo_apellido</option>
HTML;
        }
    }
}


/**
 * Funcion que genera un combo con las empresas registradas
 * en la base de datos
 */
function comboEmpresas() {
    $query = new Query("SG");

    $query->sql = "SELECT  id_empresa, nombre_empresa FROM empresa where status = 1";
    $empresas = $query->select("obj");

    if ($empresas) {
        foreach ($empresas as $empresa) {
            echo <<<HTML
                <option value="$empresa->id_empresa">$empresa->nombre_empresa</option>
HTML;
        }
    } else {
        echo <<<html
            <option value=''>No hay empresas disponibles</option>
html;
    }
}
?>
