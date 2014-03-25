<?php

/*
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * ALTA MASIVA
 */

/**
 * CHANGE CONTROL 0.99.8
 * FECHA DE MODIFICACION: 21 DE ENERO DEL 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJETIVO: ADECUAR LOS DATOS PERSONALES A TUTORES Y GESTORES
 * Funciones: getDatosPersonalesStr(), getDatosPersonalesBool()
 */

/**
 * Función que lee un archivo de excel y lo retorna en una matriz bidimensional
 * @param type $FILE
 * @param type $debug
 * @param type $fStart
 * @return string|boolean
 */
function excelToArray($FILE, $debug = NULL, $fStart = 1) {
    ($debug != NULL) ? imprimeConsola("exceltoAray") : "";

    ($debug != NULL) ? var_dump($FILE) : "";
    
    $archivo = $FILE["tmp_name"];
    $type = $FILE["type"];


    //Areglo donde se guardara el excel
    $superArray = array();
    $nombresColumnas = array();
    $nFilas = 0;

    //Librerias necesarias para funcionamiento
    //error_reporting(E_ALL);
    ini_set('display_errors', FALSE);
    ini_set('display_startup_errors', FALSE);
    define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
    require_once "PHPExcel/PHPExcel/IOFactory.php";

    //Verificar extension
    if ($type == 'application/vnd.ms-excel') {
        // Extension excel 97
        $ext = 'xls';
    } else if ($type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
        // Extension excel 2007 y 2010
        $ext = 'xlsx';
    } else {
        // Extension no valida
        //echo -1;
        $errores = "El archivo tene una extension no valida";
        return $errores;
        exit();
    }

    $xlsx = 'Excel2007';
    $xls = 'Excel5';

    $cont = 2;


    ($debug != NULL) ? imprimeConsola("Cargado el archivo") : "";
    //cargamos el archivo
    $objPHPExcel = PHPExcel_IOFactory::load($archivo);

    $dim = $objPHPExcel->getActiveSheet()->calculateWorksheetDimension();

    // list coloca en array $start y $end
    list($start, $end) = explode(':', $dim);

    if (!preg_match('#([A-Z]+)([0-9]+)#', $start, $rslt)) {
        return false;
    }
    list($start, $start_h, $start_v) = $rslt;
    if (!preg_match('#([A-Z]+)([0-9]+)#', $end, $rslt)) {
        return false;
    }
    list($end, $end_h, $end_v) = $rslt;

    //Obtener el nombre de las columnas
    ($debug != NULL) ? imprimeConsola("columnas...") : "";
    for ($h = $start_h; ord($h) <= ord($end_h); pp($h)) {
        $cellValue = get_cell($h . $fStart, $objPHPExcel);
        if ($cellValue !== null) {
            $cellValue = str_replace(" ", "", $cellValue);
//            $superArray["$cellValue"] = array();
            array_push($nombresColumnas, $cellValue);
            ($debug != NULL) ? imprimeConsola("columna: $cellValue") : "";
//            array_push($superArray, array());
        }
    }

    //empieza  lectura vertical
    ($debug != NULL) ? imprimeConsola("Leyendo registros") : "";

    $nFilas = $end_v - $start_v + $fStart;
    //$fStart++;
    for ($v = $start_v + $fStart; $v <= $end_v; $v++) {
//        sleep(1);
        //empieza lectura horizontal
        for ($h = $start_h, $col = 0; ord($h) <= ord($end_h); pp($h), $col++) {
            $cellValue = get_cell($h . $v, $objPHPExcel);
            if ($cellValue !== null) {
                $superArray[$v - 1][$nombresColumnas[$col]] = trim($cellValue);
            }
        }
//        echo "<script>document.getElementById('progreso').style.width='" . ($v * 100 / $end_v) . "';</script>";
//        echo <<<SCRIPT
//            <script>         
//                $("#progress").css("width", " . ($v * 100 / $end_v) . ");
//            </script>
//SCRIPT;
        $porcentaje = round($v * 100 / $end_v) . "% ";
        if ($debug != NULL) {
            echo $porcentaje;
            flush();
        }
    }

    ($debug != NULL) ? imprimeConsola("Retornando Arreglo") : "";
    return array("arreglo" => $superArray, "columnas" => $nombresColumnas, "nFilas" => $nFilas);
}

/**
 * Obtiene una celda en especifico de un arreglo de excel
 * @param type $cell
 * @param type $objPHPExcel
 * @return type
 */
function get_cell($cell, $objPHPExcel) {
    //select one cell
    $objCell = ($objPHPExcel->getActiveSheet()->getCell($cell));
    //get cell value
    return $objCell->getvalue();
}

/**
 * Busca una columna
 * @param type $var
 * @return boolean
 */
function pp(&$var) {
    $var = chr(ord($var) + 1);
    return true;
}

/**
 * Imprime mensaje en consola con etiqueta p en texto azul
 * @param type $mensaje
 */
function imprimeConsola($mensaje) {
    echo "<p class='text-info'>$mensaje<p>";
    flush();
}

/**
 * Imprime mensaje
 * @param type $mensaje
 */
function imprimeError($mensaje) {
    echo "<p class='text-error'>$mensaje<p>";
    flush();
}

/**
 * Imprime mensaje con etiqueta de parrafo y texto en verde
 * @param type $mensaje
 */
function imprimeOk($mensaje) {
    echo "<p class='text-success'>$mensaje<p>";
    flush();
}

/**
 * Funcion que recibe el arreglo de excel y valida las columnas
 * Una vez que se valida el tipo de carga que va a ser
 * Se recorre cada uno de los registros y se validan los datos.
 * Cada registro adecuado se agrega a un arreglo donde estaran preparados
 * los da
 * @param type $arreglo
 * @param type $nFilas
 * @param type $tipo_carga
 * @param type $debug
 * @return type
 */
function procesaRegistros($arreglo, $nFilas, $tipo_carga, $debug = NULL) {
    ($debug != NULL) ? imprimeConsola("prepara datos personles") : "";
    $errores = array();
    $inserts = array(); //Fila excel, id_datos_personales
    $filtroRegitros = array(); //Se registros que si se van a insertar
    //Arreglo par validar antes que traiga el archivo los campos correctos
    $validaCampos = getDatosPersonalesBool($tipo_carga);

    //Lee el nombre de la columna que trae y lo guarda en el arreglo
    ($debug != NULL) ? imprimeConsola("Validando columnas") : "";
    //var_dump($arreglo["columnas"]);
    $validaCampos = guardaEnArreglo($arreglo["columnas"], $validaCampos, $debug);

    //Verificar que tengamos todos los campos qu se necesitan
    ($debug != NULL) ? imprimeConsola("Verificando que el archivo cuente con los campos necesarios") : "";
    validaExistenciaDatos($validaCampos, $debug, $errores);

    //Imprime si todo va bien
    if (count($errores) == 0) {
        ($debug != NULL) ? imprimeOk("El archivo cuenta con los campos de datos personales necesarios para datos personales.") : "";
    }

    //Valida que traiga los campos especificos
    ($debug != NULL) ? imprimeConsola("Verificando que el archivo cuente con los campos especificos.") : "";
    $validaCamposEspecificos = getCamposEspecificosBool($tipo_carga);

    //Lee el nombre de la columna que trae y lo guarda en el arreglo
    ($debug != NULL) ? imprimeConsola("Validando columnas para campos especificos") : "";
    $validaCamposEspecificos = guardaEnArreglo($arreglo["columnas"], $validaCamposEspecificos, $debug);

    //Verificar que tengamos todos los campos qu se necesitan
    ($debug != NULL) ? imprimeConsola("Verificando que el archivo cuente con los campos especificos") : "";
    validaExistenciaDatos($validaCamposEspecificos, $debug, $errores);

    //Si hay errores detiene la ejecucion
    if (count($errores) == 0) {
        ($debug != NULL) ? imprimeOk("Validacion de campos correcta. Se procedera a leer registros.") : "";
        //Recorre cada una de las filas
        for ($i = 1; $i < $nFilas; $i++) {
            ($debug != NULL) ? imprimeConsola("Validando el registro $i") : "";
            //var_dump($arreglo["arreglo"][$i]);
            //Variable que decide si se inserta el registro
            $insercion = true;

            //Crea un arreglo para guardar los datos y validarlos
            $validaDatos = getDatosPersonalesStr();

            //Lee el nombre de la columna que trae y lo guarda en el arreglo
            $validaDatos = guardaEnArregloValor($arreglo["arreglo"][$i], $validaDatos, $debug);

            //Valida campos y va preparando el insert
            $campos = "";
            $datos = "";

            //Nombre de pila, campo obligatorio
            if (!validaCampoObligatorio("nombre_pila", $validaDatos, $campos, $datos, $debug, $i, $errores)) {
                $insercion = false;
            }

            $campos = str_replace(",", "", $campos);
            $datos = str_replace(",", "", $datos);

            //Primer apellido, campo obligatorio
            if (!validaCampoObligatorio("primer_apellido", $validaDatos, $campos, $datos, $debug, $i, $errores)) {
                $insercion = false;
            }

            //Segundo apellido, campo no obligatorio
            validaCampoNoObligatorio("segundo_apellido", $validaDatos, $campos, $datos);

            //Nombre de usuario, campo obligatorio y no se debe de repetir
            if ($validaDatos["nombre_usuario"] == "") {
                array_push($errores, "El campo 'nombre_usuario' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'nombre_usuario' es obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (consultaUsuario($validaDatos["nombre_usuario"])) {
                    array_push($errores, "El nombre de usuario " . $validaDatos["nombre_usuario"] . " ya ha sido registrado. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. El nombre de usuario " . $validaDatos["nombre_usuario"] . " ya ha sido registrado. Registro $i") : "";
                    $insercion = false;
                } else {
                    /**
                     * CHANGE CONTROL 0.99.8
                     * AUTOR: JOSE MANUEL NIETO GOMEZ
                     * FECHA DE MODIFICACION: 21 DE ENERO DEL 2014
                     * OBJETIVO: VALIDAR LA EXISTENCIA DEL USUARIO EN MOODLE
                     */
                    if (verificarUsuarioMoodle($validaDatos["nombre_usuario"])) {
                        array_push($errores, "El nombre de usuario " . $validaDatos["nombre_usuario"] . " ya ha sido registrado en Moodle. Registro $i");
                        ($debug != NULL) ? imprimeError("ERROR. El nombre de usuario " . $validaDatos["nombre_usuario"] . " ya ha sido registrado en Moodle. Registro $i") : "";
                        $insercion = false;
                    } else {
                        if (validaNombreUsuario($validaDatos["nombre_usuario"])) {
                            $campos.=", nombre_usuario";
                            $datos.=", '" . __($validaDatos["nombre_usuario"]) . "'";
                        } else {
                            $nombreusuario = $validaDatos["nombre_usuario"];
                            array_push($errores, "Moodle no acepta este nombre de usuario '$nombreusuario'. El nombredeusuario solo puede contener caracteres alfanumericos en minusculas, subrayado (_), guion (-), punto (.) o arroba (@). Registro $i");
                            ($debug != NULL) ? imprimeError("ERROR. Moodle no acepta este nombre de usuario. El nombredeusuario solo puede contener caracteres alfanumericos en minusculas, subrayado (_), guion (-), punto (.) o arroba (@). Registro $i") : "";
                            $insercion = false;
                        }
                    }
                }
            }

            //Correo, campo obligatorio, no se puede repetir y valida que sea un correo valido
            if ($validaDatos["correo"] == "") {
                array_push($errores, "El campo 'correo' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'correo' es obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (esEmail($validaDatos["correo"])) {
                    if (consultaCorreo($validaDatos["correo"])) {
                        array_push($errores, "El correo " . $validaDatos["correo"] . " ya ha sido registrado. Registro $i");
                        ($debug != NULL) ? imprimeError("ERROR. El nombre de usuario " . $validaDatos["correo"] . " ya ha sido registrado. Registro $i") : "";
                        $insercion = false;
                    } else {
                        $campos.=", correo";
                        $datos.=", '" . __($validaDatos["correo"]) . "'";
                    }
                } else {
                    $correo = $validaDatos["correo"];
                    array_push($errores, "No es un correo valido '$correo'. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. No es un correo valido. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Contrasena, campo obligatorio, y encripta la contraseña
            if ($validaDatos["contrasena"] == "") {
                $contrasena = ucfirst(Utilidades::generaPwd(6)) . "1As$";
                $contrasenaEnc = nCrypt($contrasena);
                $campos .= ", contrasena";
                $datos .= ", '$contrasenaEnc'";
                $arreglo["arreglo"][$i]["contrasena"] = $contrasena;
            } else {
                if (validaFortaleza($validaDatos["contrasena"])) {
                    $contrasenaEnc = nCrypt($validaDatos["contrasena"]);
                    $campos.=", contrasena";
                    $datos.=", '$contrasenaEnc'";
                } else {
                    array_push($errores, "La fortaleza de la contrasena es debil. La contrasena debe tener una longitud minima de 8 caracteres de los cuales al menos 1 debe ser un numero. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La fortaleza de la contrasena es debil. La contrasena debe tener una longitud minima de 8 caracteres de los cuales al menos 1 debe ser un numero. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Fecha de nacimiento, campo obligatorio, valida que sea fecha
            if ($validaDatos["fecha_nacimiento"] == "") {
                array_push($errores, "El campo 'fecha_nacimiento' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'fecha_nacimiento' es obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($fecha = DateTime::createFromFormat("d/m/Y", $validaDatos["fecha_nacimiento"])) === false) {
                    array_push($errores, "No ha insertado una fecha de nacimiento valida. Formato valido: 'dd/mm/yyyy'. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. No ha insertado una fecha de nacimiento valida. Formato valido: 'dd/mm/yyyy'. Registro $i") : "";
                    $insercion = false;
                } else {
                    $fecha = $fecha->format('Y-m-d');
                    $campos.=", fecha_nacimiento";
                    $datos.=", '" . $fecha . "'";
                }
            }

            /**
             * CHANGE CONTROL 0.99.8
             * FECHA DE MODIFICACION: 21 DE ENERO DEL 2014
             * AUTOR: JOSE MANUEL NIETO GOMEZ
             * OBJETIVO: AJUSTAR LA VALIDACION DE DATOS A TUTORES Y GESTORES
             */
            //Curp, es opcional, solo hay que validar que no sea mayor a 18 digitos
            if ($tipo_carga != "tutores" && $tipo_carga != "gestores") {
                if ($validaDatos["curp"] != "") {
                    if (strlen($validaDatos["curp"]) > 18) {
                        array_push($errores, "El CURP no debe sobrepasar los 18 caracteres. Registro $i");
                        ($debug != NULL) ? imprimeError("ERROR. El CURP no debe sobrepasar los 18 caracteres. Registro $i") : "";
                        $insercion = false;
                    } else {
                        $campos.=", curp";
                        $datos.=", '" . __($validaDatos["curp"]) . "'";
                    }
                }
            }

            //Codigo postal es un campo obligatorio, validar que sea numero y no pase de 5 caracteres.
            if ($validaDatos["codigo_postal"] == "") {
                array_push($errores, "El campo 'codigo_postal' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'codigo_postal' es obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (strlen($validaDatos["codigo_postal"]) > 5 || !is_numeric($validaDatos["codigo_postal"])) {
                    array_push($errores, "Formato de codigo postal incorrecto. Formato valido: ##### ej 50010. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. Formato de codigo postal incorrecto. Formato valido: ##### ej 50010. Registro $i") : "";
                    $insercion = false;
                } else {
                    $campos.=", codigo_postal";
                    $datos.=", '" . $validaDatos["codigo_postal"] . "'";
                }
            }

            //Calle, es un campo obligatorio
            if ($tipo_carga != "tutores" && $tipo_carga != "gestores") {
                if (!validaCampoObligatorio("calle", $validaDatos, $campos, $datos, $debug, $i, $errores)) {
                    $insercion = false;
                }
            }

            //Numero de casa exterior es obligatorio
            if ($tipo_carga != "tutores" && $tipo_carga != "gestores") {
                if (!validaCampoObligatorio("no_casa_ext", $validaDatos, $campos, $datos, $debug, $i, $errores)) {
                    $insercion = false;
                }
            }

            //Numero de casa interior no es obligatorio
            if ($tipo_carga != "tutores" && $tipo_carga != "gestores") {
                validaCampoNoObligatorio("no_casa_int", $validaDatos, $campos, $datos);
            }

            //Colonia o localidad, es un campo obligatorio
            if ($tipo_carga != "tutores" && $tipo_carga != "gestores") {
                if (!validaCampoObligatorio("colonia_localidad", $validaDatos, $campos, $datos, $debug, $i, $errores)) {
                    $insercion = false;
                }
            }

            //Delegacion o municipio, es un campo obligatorio
            if ($tipo_carga != "tutores" && $tipo_carga != "gestores") {
                if (!validaCampoObligatorio("delegacion_municipio", $validaDatos, $campos, $datos, $debug, $i, $errores)) {
                    $insercion = false;
                }
            }

            //Entidad federativa, es campo obligatorio, debe ir a buscar el id de la entidad federativa
            if ($validaDatos["entidad_federativa"] == "") {
                array_push($errores, "El campo 'entidad_federativa' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'entidad_federativa' es obligatorio.  Registro $i") : "";
                $insercion = false;
            } else {
                if (($id_entidad = getIdEntidadFederativa($validaDatos["entidad_federativa"])) != false) {
                    $campos.=", id_entidad_federativa";
                    $datos.=", '" . $id_entidad . "'";
                } else {
                    //var_dump($id_entidad);
                    array_push($errores, "La entidad federativa no existe o no esta escrita correctamente. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La entidad federativa no existe o no esta escrita correctamente. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Nacionalidad
            if ($tipo_carga != "tutores" && $tipo_carga != "gestores") {
                if ($validaDatos["nacionalidad"] == "") {
                    array_push($errores, "El campo 'nacionalidad' es obligatorio. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. El campo 'nacionalidad' es obligatorio.  Registro $i") : "";
                    $insercion = false;
                } else {
                    if (($id_nacionalidad = getIdNacionalidad($validaDatos["nacionalidad"]) ) != false) {
                        $campos.=", id_nacionalidad";
                        $datos.=", '" . $id_nacionalidad . "'";
                    } else {
                        array_push($errores, "La nacionalidad no existe o no esta escrita correctamente. Registro $i");
                        ($debug != NULL) ? imprimeError("ERROR. La nacionalidad no existe o no esta escrita correctamente. Registro $i") : "";
                        $insercion = false;
                    }
                }
            }

            //Zona horaria, Campo obligatorio y valida que sea una de las permitidas
            if ($tipo_carga != "tutores" && $tipo_carga != "gestores") {
                if ($validaDatos["zona_horaria"] != "") {
                    $zona = strtoupper(str_replace(" ", "", trim($validaDatos["zona_horaria"])));
                    if ($zona == "GMT-6" || $zona == "GMT-7" || $zona == "GMT-8") {
                        $campos.=", zona_horaria";
                        $datos.=", '" . $zona . "'";
                    } else {
                        array_push($errores, "El campo 'zona_horaria' tiene un formato incorrecto. Formatos validos: GMT-6, GMT-7, GMT-8. Registro $i");
                        ($debug != NULL) ? imprimeError("ERROR. El campo 'zona_horaria' tiene un formato incorrecto. Formatos validos: GMT-6, GMT-7, GMT-8. Registro $i") : "";
                        $insercion = false;
                    }
                }
            }

            //Telefono fijo no obligatorio
            validaCampoNoObligatorio("telefono_fijo", $validaDatos, $campos, $datos);

            //Telefono movil no obligatorio
            validaCampoNoObligatorio("telefono_movil", $validaDatos, $campos, $datos);

            //Asigna tipo_usuario
            $campos.=", tipo_usuario";
            $datos.=", '" . tipoUsuario($tipo_carga) . "'";

            //Valida campos especificos            

            ($debug != NULL) ? imprimeConsola("Validando campos especificos. Registro $i") : "";
            $validaDatosEspecificos = getCamposEspecificosStr($tipo_carga);

            //Guarda en validaDatosEspecificos lo que trae el archivo
            $validaDatosEspecificos = guardaEnArregloValor($arreglo["arreglo"][$i], $validaDatosEspecificos, $debug);

            //Valida los datos especificos del usuario
            $insertsEspecificos = validaDatosEspecificos($validaDatosEspecificos, $insercion, $tipo_carga, $errores, $i, $debug);

            //Termina validacion
            if ($insercion === true) {
                ($debug != NULL) ? imprimeOk("Termina la validacion del registro $i, se insertara") : "";
                $registro = array(
                    "campos" => $campos,
                    "datos" => $datos,
                    "insertsEspecificos" => $insertsEspecificos);
                //Guarda la informacion preparada para su registro, SQL
                array_push($inserts, $registro);
                //Guarda en el filtro los registros que si se insertaran, para mostrarse en tabla
                array_push($filtroRegitros, $arreglo["arreglo"][$i]);
            } else {
                ($debug != NULL) ? imprimeError("Este registro contiene error(res). Se omitira su insercion. Registro $i") : "";
            }
        }
    } else {
        ($debug != NULL) ? imprimeError("Lectura del archivo detenida, corrija los errores. " . count($errores) . " error(es) encontrado(s).") : "";
    }

    return array("errores" => $errores, "inserts" => $inserts, "filtroRegistros" => $filtroRegitros);
}

/**
 * De acuerdo al tipo de carga retorn el tipo de usuario en la base de datos
 * @param type $tipo_carga
 * @return null|int
 */
function tipoUsuario($tipo_carga) {
    switch ($tipo_carga) {
        case "combo":
            return 0;
        case "estudiantes":
            return 0;
        case "profesionistas":
            return 0;
        case "tutores":
            return 1;
        case "profesores":
            return 2;
        case "padres":
            return 3;
        case "gestores":
            return 4;
        case "admin":
            return 5;
    }
}

/**
 * De acuerdo al tipo de carga retorna un arreglo con los campos booleanos en falso
 * @param type $tipo_carga
 * @return type
 */
function getCamposEspecificosBool($tipo_carga) {
    switch ($tipo_carga) {
        case "combo":
            return array(
                "matricula" => false,
                "padre" => false,
                "profesor_aula" => false,
                "nivel_educativo" => false,
                "grado_escolar" => false,
                "escuela" => false,
                "padre_nombre_pila" => false,
                "padre_primer_apellido" => false,
                "padre_segundo_apellido" => false,
                "padre_nombre_usuario" => false,
                "padre_correo" => false,
                "padre_contrasena" => false,
                "padre_fecha_nacimiento" => false,
                "padre_curp" => false,
                "padre_codigo_postal" => false,
                "padre_calle" => false,
                "padre_no_casa_ext" => false,
                "padre_no_casa_int" => false,
                "padre_colonia_localidad" => false,
                "padre_delegacion_municipio" => false,
                "padre_entidad_federativa" => false,
                "padre_nacionalidad" => false,
                "padre_zona_horaria" => false,
                "padre_telefono_fijo" => false,
                "padre_telefono_movil" => false,
                "padre_grado_escolar" => false,
                "profesor_nombre_pila" => false,
                "profesor_primer_apellido" => false,
                "profesor_segundo_apellido" => false,
                "profesor_nombre_usuario" => false,
                "profesor_correo" => false,
                "profesor_contrasena" => false,
                "profesor_fecha_nacimiento" => false,
                "profesor_curp" => false,
                "profesor_codigo_postal" => false,
                "profesor_calle" => false,
                "profesor_no_casa_ext" => false,
                "profesor_no_casa_int" => false,
                "profesor_colonia_localidad" => false,
                "profesor_delegacion_municipio" => false,
                "profesor_entidad_federativa" => false,
                "profesor_nacionalidad" => false,
                "profesor_zona_horaria" => false,
                "profesor_telefono_fijo" => false,
                "profesor_telefono_movil" => false,
                "profesor_puesto_ocupacion" => false,
                "profesor_escuela" => false,
                "profesor_nivel_educativo" => false,
                "profesor_grado_escolar" => false
            );

        case "estudiantes":
            return array(
                "matricula" => false,
                "padre" => false,
                "profesor_aula" => false,
                "nivel_educativo" => false,
                "grado_escolar" => false,
                "escuela" => false
            );
        case "profesionistas":
            return array(
                "puesto_ocupacion" => false,
                "nivel_educativo" => false,
                "grado_escolar" => false,
                "empresa" => false
            );
        case "tutores":
            return array(
                "rol" => false
            );
        case "profesores":
            return array(
                "puesto_ocupacion" => false,
                "escuela" => false,
                "nivel_educativo" => false,
                "grado_escolar" => false
            );
        case "padres":
            return array(
                "nivel_educativo" => false,
                "grado_escolar" => false
            );
        case "gestores":
            return array();
        case "admin":
            return array();
    }
}

/**
 * De acuerdo al tipo de carga retorna un arreglo con los campos con strings vacios
 * @param type $tipo_carga
 * @return type
 */
function getCamposEspecificosStr($tipo_carga) {
    switch ($tipo_carga) {
        case "combo":
            return array(
                "matricula" => "",
                "padre" => "",
                "profesor_aula" => "",
                "nivel_educativo" => "",
                "grado_escolar" => "",
                "escuela" => "",
                "padre_nombre_pila" => "",
                "padre_primer_apellido" => "",
                "padre_segundo_apellido" => "",
                "padre_nombre_usuario" => "",
                "padre_correo" => "",
                "padre_contrasena" => "",
                "padre_fecha_nacimiento" => "",
                "padre_curp" => "",
                "padre_codigo_postal" => "",
                "padre_calle" => "",
                "padre_no_casa_ext" => "",
                "padre_no_casa_int" => "",
                "padre_colonia_localidad" => "",
                "padre_delegacion_municipio" => "",
                "padre_entidad_federativa" => "",
                "padre_nacionalidad" => "",
                "padre_zona_horaria" => "",
                "padre_telefono_fijo" => "",
                "padre_telefono_movil" => "",
                "padre_grado_escolar" => "",
                "padre_nivel_educativo" => "",
                "profesor_nombre_pila" => "",
                "profesor_primer_apellido" => "",
                "profesor_segundo_apellido" => "",
                "profesor_nombre_usuario" => "",
                "profesor_correo" => "",
                "profesor_contrasena" => "",
                "profesor_fecha_nacimiento" => "",
                "profesor_curp" => "",
                "profesor_codigo_postal" => "",
                "profesor_calle" => "",
                "profesor_no_casa_ext" => "",
                "profesor_no_casa_int" => "",
                "profesor_colonia_localidad" => "",
                "profesor_delegacion_municipio" => "",
                "profesor_entidad_federativa" => "",
                "profesor_nacionalidad" => "",
                "profesor_zona_horaria" => "",
                "profesor_telefono_fijo" => "",
                "profesor_telefono_movil" => "",
                "profesor_puesto_ocupacion" => "",
                "profesor_escuela" => "",
                "profesor_nivel_educativo" => "",
                "profesor_grado_escolar" => ""
            );

        case "estudiantes":
            return array(
                "matricula" => "",
                "padre" => "",
                "profesor_aula" => "",
                "nivel_educativo" => "",
                "grado_escolar" => "",
                "escuela" => ""
            );
        case "profesionistas":
            return array(
                "puesto_ocupacion" => "",
                "nivel_educativo" => "",
                "grado_escolar" => "",
                "empresa" => ""
            );
        case "tutores":
            return array(
                "rol" => ""
            );
        case "profesores":
            return array(
                "puesto_ocupacion" => "",
                "escuela" => "",
                "nivel_educativo" => "",
                "grado_escolar" => ""
            );
        case "padres":
            return array(
                "nivel_educativo" => "",
                "grado_escolar" => ""
            );
        case "gestores":
            return array();
        case "admin":
            return array();
    }
}

/**
 * Valida un campo obligatorio que no este vacio. Si esta vacio agrega un error y retorna false.
 * Si no esta vacio lo concatena y retorna un verdadero.
 * @param type $nombre_campo
 * @param type $arreglo arreglo que contiene los datos a validar
 * @param type $campos variable donde se concatenan los campos para el sql
 * @param string $datos variable donde se concatenan los datos para el sql
 * @param type $debug variable que determina si se imprime un debug o no
 * @param type $nRegistro numero de registro del archivo en el que se esta validando
 * @param type $errores variable que contiene los errores en las validaciones
 * @return boolean
 */
function validaCampoObligatorio($nombre_campo, $arreglo, &$campos, &$datos, $debug = NULL, $nRegistro, &$errores) {
    if ($arreglo[$nombre_campo] == "") {
        array_push($errores, "El campo '$nombre_campo' es obligatorio. Registro $nRegistro");
        ($debug != NULL) ? imprimeError("ERROR. El campo '$nombre_campo' es obligatorio. Registro $nombre_campo") : "";
        return false;
    } else {
        $dato = __($arreglo[$nombre_campo]);
        $campos.=", $nombre_campo";
        $datos.=", '" . $dato . "'";
        return true;
    }
}

/**
 * Valida un campo no obligatorio para corroborar solo si no viene vacio y concatenarlo
 * @param type $nombre_campo
 * @param type $arreglo arreglo que contiene los datos a validar
 * @param type $campos variable que concatena los campos para el sql
 * @param string $datos variable que concatena los datos para el sql
 */
function validaCampoNoObligatorio($nombre_campo, $arreglo, &$campos, &$datos) {
    if ($arreglo[$nombre_campo] != "") {
        $dato = __($arreglo[$nombre_campo]);
        $campos.=", $nombre_campo";
        $datos.=", '" . $dato . "'";
    }
}

/**
 * De los campos que recibe en un arreglo, los busca en el otro arreglo y guarda en valor en
 * el nombre del campo correspondiente
 * @param type $arreglo arreglo que viene del archivo de excel
 * @param boolean $validaCampos arreglo donde se estan guardando los datos del registro
 * @param type $debug determina si se imprime debug o no
 * @return boolean
 */
function guardaEnArreglo($arreglo, $validaCampos, $debug = NULL) {
    foreach ($arreglo as $campo) {
        if (array_key_exists($campo, $validaCampos)) {
            $validaCampos[$campo] = true;
            ($debug != NULL) ? imprimeOk("$campo encontrado para campos requeridos") : "";
        }
    }
    return $validaCampos;
}

/**
 * Relacionar los valores de dos matrices
 * @param type $arreglo
 * @param type $validaCampos
 * @param type $debug
 * @return type
 */
function guardaEnArregloValor($arreglo, $validaCampos, $debug = NULL) {
    foreach ($arreglo as $campo => $valor) {
        if (array_key_exists($campo, $validaCampos)) {
            $validaCampos[$campo] = $valor;
        }
    }
    return $validaCampos;
}

/**
 * Valida en un arreglo que que todos sus campos sean true. Por cada campo con false agrega un error
 * @param type $validaCampos arreglo que trae los datos a validar
 * @param type $debug determina si se imprime el debug o no
 * @param type $errores arreglo que contiene los errores de las validaciones
 */
function validaExistenciaDatos($validaCampos, $debug = NULL, &$errores) {
    foreach ($validaCampos as $campo => $valor) {
        if (!$valor) {
            array_push($errores, "No se encuentra la columna $campo, requerida en el archivo para llenar los datos personales.");
            ($debug != NULL) ? imprimeError("ERROR. No se encuentra la columna $campo, requerida en el archivo para llenar los datos personales.") : "";
        }
    }
}

/**
 * CHANGE CONTROL 0.99.8
 * FECHA DE MODIFICACION: 21 DE ENERO DEL 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJETIVO: ADECUAR LOS DATOS PERSONALES A TUTORES Y GESTORES
 * 
 * Retorna un arreglo con los datos personales todos setteados con strings vacios
 * @return type
 */
function getDatosPersonalesStr($tipo_carga) {
    if ($tipo_carga == "tutores" || $tipo_carga == "gestores") {
        return array(
            "nombre_pila" => "",
            "primer_apellido" => "",
            "segundo_apellido" => "",
            "nombre_usuario" => "",
            "correo" => "",
            "contrasena" => "",
            "fecha_nacimiento" => "",
            "codigo_postal" => "",
            "entidad_federativa" => "",
            "telefono_fijo" => "",
            "telefono_movil" => "");
    } else {
        return array(
            "nombre_pila" => "",
            "primer_apellido" => "",
            "segundo_apellido" => "",
            "nombre_usuario" => "",
            "correo" => "",
            "contrasena" => "",
            "fecha_nacimiento" => "",
            "curp" => "",
            "codigo_postal" => "",
            "calle" => "",
            "no_casa_ext" => "",
            "no_casa_int" => "",
            "colonia_localidad" => "",
            "delegacion_municipio" => "",
            "entidad_federativa" => "",
            "nacionalidad" => "",
            "zona_horaria" => "",
            "telefono_fijo" => "",
            "telefono_movil" => "");
    }
}

/**
 * CHANGE CONTROL 0.99.8
 * FECHA DE MODIFICACION: 21 DE ENERO DEL 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJETIVO: ADECUAR LOS DATOS PERSONALES A TUTORES Y GESTORES
 * 
 * Devuelve un arreglo con los campos de datos personales todos setteados en false
 * @return type
 */
function getDatosPersonalesBool($tipo_carga) {
    imprimeConsola("getDatosEspecificosBool, tipo_carga:$tipo_carga");
    if ($tipo_carga == "tutores" || $tipo_carga == "gestores") {
        return array(
            "nombre_pila" => false,
            "primer_apellido" => false,
            "segundo_apellido" => false,
            "nombre_usuario" => false,
            "correo" => false,
            "contrasena" => false,
            "fecha_nacimiento" => false,
            "codigo_postal" => false,
            "entidad_federativa" => false,
            "telefono_fijo" => false,
            "telefono_movil" => false);
    } else {
        return array(
            "nombre_pila" => false,
            "primer_apellido" => false,
            "segundo_apellido" => false,
            "nombre_usuario" => false,
            "correo" => false,
            "contrasena" => false,
            "fecha_nacimiento" => false,
            "curp" => false,
            "codigo_postal" => false,
            "calle" => false,
            "no_casa_ext" => false,
            "no_casa_int" => false,
            "colonia_localidad" => false,
            "delegacion_municipio" => false,
            "entidad_federativa" => false,
            "nacionalidad" => false,
            "zona_horaria" => false,
            "telefono_fijo" => false,
            "telefono_movil" => false);
    }
}

/**
 * Busca una entidad federativa, convirtiendola a mayusculas y poniendole entidades html
 * Si encuentra la entidad federativa retorna su id
 * Si no lo encuentra retorna un false
 * @param type $nombre_entidad
 * @return boolean
 */
function getIdEntidadFederativa($nombre_entidad) {
    if ($nombre_entidad != "") {
        $nombre_entidad = strtolower(__($nombre_entidad));
        $query = new Query("SG");
        $query->sql = <<<SQL
            select id_entidad_federativa from entidad_federativa where lower(nombre_entidad) ='$nombre_entidad'
SQL;

//    imprimeConsola("<pre>".$query->sql."</pre>");
        $registro = $query->select("obj");
        if ($registro) {
            foreach ($registro as $reg) {
                return $reg->id_entidad_federativa;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Funcion que busca el id de una empresa por su nombre
 * @param type $nombre_empresa
 * @return boolean
 */
function getIdRol($nombre_rol) {
    if ($nombre_rol != "") {
        $nombre_rol = strtolower(__($nombre_rol));
        $query = new Query("SG");
        $query->sql = <<<SQL
            select id_rol_tutor from rol_tutor where lower(nombre) ='$nombre_rol' and status = 1
SQL;

//    imprimeConsola("<pre>".$query->sql."</pre>");
        $registro = $query->select("obj");
        if ($registro) {
            foreach ($registro as $reg) {
                return $reg->id_rol_tutor;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Recibe el nombre de la empresa y realiza una consulta para retornar su ID
 * @param type $nombre_empresa
 * @return boolean
 */
function getIdEmpresa($nombre_empresa) {
    if ($nombre_empresa != "") {
        $nombre_empresa = strtolower(__($nombre_empresa));
        $query = new Query("SG");
        $query->sql = <<<SQL
            select id_empresa from empresa where lower(nombre_empresa) ='$nombre_empresa' and status = 1
SQL;

//    imprimeConsola("<pre>".$query->sql."</pre>");
        $registro = $query->select("obj");
        if ($registro) {
            foreach ($registro as $reg) {
                return $reg->id_empresa;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Busca el nombre de una nacionalidad y retorna su id si la encuentra
 * Si no la encuentra retorna un false
 * @param type $nacionalidad
 * @return boolean
 */
function getIdNacionalidad($nacionalidad) {
    if ($nacionalidad != "") {
        $nacionalidad = strtolower(__($nacionalidad));

        $query = new Query("SG");
        $query->sql = <<<SQL
            select id_nacionalidad from nacionalidad where lower(nacionalidad) ='$nacionalidad' and status=1
SQL;
//    imprimeConsola("<pre>SQL get id nacionalidad: ".$query->sql."</pre>");
        $registro = $query->select("obj");

        if ($registro) {
            foreach ($registro as $reg) {
                return $reg->id_nacionalidad;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Funcion que busca una coincidencia de padre ya sea por el
 * correo o por nombre de usuario y si lo encuentra
 * retorna el id del padre
 * @param type $padre
 * @return boolean
 */
function getIdPadre($padre) {
    if ($padre != "") {
        $padre = strtolower(__($padre));
        $query = new Query("SG");
        $query->sql = <<<SQL
            SELECT p.id_padre 
            FROM datos_personales dp, padres p 
            WHERE dp.id_datos_personales = p.id_datos_personales
              and (lower(dp.correo) = '$padre' or lower(dp.nombre_usuario) = '$padre')
              and p.status=1
SQL;
        imprimeConsola($query->sql);
        $registro = $query->select("obj");

        if ($registro) {
            foreach ($registro as $reg) {
                return $reg->id_padre;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Funcion que busca la coincidencia de un correo o nombre de usuario
 * de un profesor de aula y retorna su id de profesor
 * @param type $profesor
 * @return boolean
 */
function getIdProfesor($profesor) {
    if ($profesor != "") {
        $profesor = strtolower(__($profesor));
        $query = new Query("SG");
        $query->sql = <<<SQL
            SELECT p.id_profesor_aula 
            FROM datos_personales dp, profesores_aula p
            WHERE dp.id_datos_personales = p.id_datos_personales
              and (lower(dp.correo) = '$profesor' or lower(dp.nombre_usuario) = '$profesor')
              and p.status=1
SQL;
        $registro = $query->select("obj");

        if ($registro) {
            foreach ($registro as $reg) {
                return $reg->id_profesor_aula;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Funcion que recibe el nombre de un nivel educativo
 * y regresa el id. Si no existe retorna un false
 * @param type $nivel
 * @return boolean
 */
function getIdNivel($nivel) {
    if ($nivel != "") {
        $nivel = strtolower(__($nivel));
        $query = new Query("SG");
        $query->sql = <<<SQL
            select id_nivel from nivel_escolar where lower(nombre) ='$nivel' and status=1
SQL;
//    imprimeConsola("<pre>".$query->sql."</pre>");
        $registro = $query->select("obj");

        if ($registro) {
            foreach ($registro as $reg) {
                return $reg->id_nivel;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Funcion que busca un nombre de grado escolar y retorna su id
 * Si no lo encuentra retorna un false
 * @param type $grado
 * @return boolean
 */
function getIdGrado($grado, $idNivel) {
    if ($grado != "" && $idNivel != "") {
        $grado = strtolower(__($grado));
        $query = new Query("SG");
        $query->sql = <<<SQL
            select id_grado_escolar from grado_escolar where lower(nombre_grado) ='$grado' and status=1 and id_nivel = $idNivel
SQL;
//    imprimeConsola("<pre>".$query->sql."</pre>");
        $registro = $query->select("obj");

        if ($registro) {
            foreach ($registro as $reg) {
                return $reg->id_grado_escolar;
            }
        } else {
            return false;
        }
    } else {
        return true;
    }
}

/**
 * Funcion que busca el nombre de una escuela y retorna su id
 * Si no encuentra la escuela retorna un false
 * @param type $escuela
 * @return boolean
 */
function getIdEscuela($escuela) {
    if ($escuela != "") {
        $escuela = strtolower(__($escuela));
        $query = new Query("SG");
        $query->sql = <<<SQL
            select id_escuela from escuelas where lower(nombre_escuela) ='$escuela'
SQL;

        $registro = $query->select("obj");

        if ($registro) {
            foreach ($registro as $reg) {
                return $reg->id_escuela;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Valida datos especificos de un usuario
 * @param type $validaDatosEspecificos
 * @param type $insercion
 * @param type $tipo_carga
 * @param type $errores
 * @param type $i
 * @param type $debug
 * @return null
 */
function validaDatosEspecificos($validaDatosEspecificos, &$insercion, $tipo_carga, &$errores, $i, $debug = NULL) {
    $camposEspecificos = "";
    $datosEspecificos = "";
    switch ($tipo_carga) {
        case "combo":
            //Asigna status y tipo de alumno
            $camposEspecificos = "status, tipo_alumno";
            $datosEspecificos = "1, 0";

            //Matricula no obligatoria
            validaCampoNoObligatorio("matricula", $validaDatosEspecificos, $camposEspecificos, $datosEspecificos);

            //Padre, no es obligatorio, si trae un registro busca el id del padre
            //puede ser el nombre de usuario o el correo del padre
            if ($validaDatosEspecificos["padre"] != "") {
                if (($idPadre = getIdPadre($validaDatosEspecificos["padre"])) != false) {
                    $camposEspecificos .= ", id_padre";
                    $datosEspecificos .= ", $idPadre";
                } else {
                    $referencia = $validaDatosEspecificos["padre"];
                    array_push($errores, "La referencia con el padre no existe: $referencia. Puede ingresarse el correo o el nombre de usaurio del Padre. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el padre no existe: $referencia. Puede ingresarse el correo o el nombre de usaurio del Padre. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Profesor de aula, no es obligatorio, si trae un registro busca el id del profesor
            if ($validaDatosEspecificos["profesor_aula"] != "") {
                if (($idProfesor = getIdProfesor($validaDatosEspecificos["profesor_aula"])) != false) {
                    $camposEspecificos .= ", id_profesor_aula";
                    $datosEspecificos .= ", $idProfesor";
                } else {
                    $referencia = $validaDatosEspecificos["profesor_aula"];
                    array_push($errores, "La referencia con el profesor de aula no existe: $referencia. Puede ingresarse el correo o el nombre de usaurio del profesor de aula. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el profesor de aula no existe: $referencia. Puede ingresarse el correo o el nombre de usaurio del profesor de aula. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Nivel escolar, es un campo obligatorio, busca el nombre del nivel escolar  y retorna su id
            if ($validaDatosEspecificos["nivel_educativo"] == "") {
                array_push($errores, "El 'nivel_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'nivel_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
                $idNivel = false;
            } else {
                if (($idNivel = getIdNivel($validaDatosEspecificos["nivel_educativo"])) != false) {
//                    $camposEspecificos .= ", id_nivel";
//                    $datosEspecificos .= ", $idNivel";
                } else {
                    array_push($errores, "La referencia con el nivel escolar no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el nivel escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Grado escolar, es un campo obligatorio, busca el nombre del grado y retorna su id
            if ($validaDatosEspecificos["grado_escolar"] == "" && $idNivel === false) {
                array_push($errores, "El 'grado_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'grado_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($idGrado = getIdGrado($validaDatosEspecificos["grado_escolar"], $idNivel)) != false) {
                    $camposEspecificos .= ", id_grado_escolar";
                    $datosEspecificos .= ", $idGrado";
                } else {
                    $grado = $validaDatosEspecificos["grado_escolar"];
                    array_push($errores, "La referencia con el grado escolar '$grado' no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el grado escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Escuela es un campo obligatorio, busca el nombre de la escuela y retorna su id
            if ($validaDatosEspecificos["escuela"] == "") {
                array_push($errores, "La 'escuela' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. La 'escuela' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($idEscuela = getIdEscuela($validaDatosEspecificos["escuela"])) != false) {
                    $camposEspecificos .= ", id_escuela";
                    $datosEspecificos .= ", $idEscuela";
                } else {
                    array_push($errores, "La referencia con la escuela no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con la escuela no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            /**
             * Validar al padre
             */
            //Valida campos y va preparando el insert
            $camposPadre = "";
            $datosPadre = "";

            //Nombre de pila, campo obligatorio
            if (!validaCampoObligatorio("padre_nombre_pila", $validaDatosEspecificos, $camposPadre, $datosPadre, $debug, $i, $errores)) {
                $insercion = false;
            }

            $camposPadre = str_replace(",", "", $camposPadre);
            $datosPadre = str_replace(",", "", $datosPadre);

            //Primer apellido, campo obligatorio
            if (!validaCampoObligatorio("padre_primer_apellido", $validaDatosEspecificos, $camposPadre, $datosPadre, $debug, $i, $errores)) {
                $insercion = false;
            }

            //Segundo apellido, campo no obligatorio
            validaCampoNoObligatorio("padre_segundo_apellido", $validaDatosEspecificos, $camposPadre, $datosPadre);

            //Nombre de usuario, campo obligatorio y no se debe de repetir
            if ($validaDatosEspecificos["padre_nombre_usuario"] == "") {
                array_push($errores, "El campo 'padre_nombre_usuario' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'nombre_usuario' es obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (consultaUsuario($validaDatosEspecificos["padre_nombre_usuario"])) {
                    array_push($errores, "El nombre de usuario " . $validaDatosEspecificos["padre_nombre_usuario"] . " ya ha sido registrado. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. El nombre de usuario " . $validaDatosEspecificos["padre_nombre_usuario"] . " ya ha sido registrado. Registro $i") : "";
                    $insercion = false;
                } else {
                    if (validaNombreUsuario($validaDatos["padre_nombre_usuario"])) {
                        $camposPadre.=", nombre_usuario";
                        $datosPadre.=", '" . __($validaDatosEspecificos["padre_nombre_usuario"]) . "'";
                    } else {
                        array_push($errores, "Error en 'padre_nombre_usuario'. Moodle no acepta este nombre de usuario. Los nombre de usuario deben tener solo minusculas y no deben contener espacios. Registro $i");
                        ($debug != NULL) ? imprimeError("ERROR. Error en 'padre_nombre_usuario'. Moodle no acepta este nombre de usuario. Los nombre de usuario deben tener solo minusculas y no deben contener espacios. Registro $i") : "";
                        $insercion = false;
                    }
                }
            }

            //Correo, campo obligatorio, no se puede repetir y valida que sea un correo valido
            if ($validaDatosEspecificos["padre_correo"] == "") {
                array_push($errores, "El campo 'padre_correo' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'padre_correo' es obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (esEmail($validaDatosEspecificos["padre_correo"])) {
                    if (consultaCorreo($validaDatosEspecificos["padre_correo"])) {
                        array_push($errores, "El correo " . $validaDatosEspecificos["padre_correo"] . " ya ha sido registrado. Registro $i");
                        ($debug != NULL) ? imprimeError("ERROR. El nombre de usuario " . $validaDatosEspecificos["padre_correo"] . " ya ha sido registrado. Registro $i") : "";
                        $insercion = false;
                    } else {
                        $camposPadre.=", correo";
                        $datosPadre.=", '" . __($validaDatosEspecificos["padre_correo"]) . "'";
                    }
                } else {
                    array_push($errores, "No es un padre_correo valido. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. No es un padre_correo valido. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Contrasena, campo obligatorio, y encripta la contraseña
            if ($validaDatosEspecificos["padre_contrasena"] == "") {
                $contrasena = ucfirst(Utilidades::generaPwd(6)) . "1As$";
                $contrasenaEnc = nCrypt($contrasena);
                $campos .= ", contrasena";
                $datos .= ", '$contrasenaEnc'";
                $arreglo["arreglo"][$i]["padre_contrasena"] = $contrasena;
            } else {
                if (validaFortaleza($validaDatosEspecificos["padre_contrasena"])) {
                    $contrasenaEnc = nCrypt($validaDatosEspecificos["padre_contrasena"]);
                    $campos.=", contrasena";
                    $datos.=", '$contrasenaEnc'";
                } else {
                    array_push($errores, "La fortaleza de la contrasena es debil 'padre_contrasena'. La contrasena debe tener una longitud minima de 8 caracteres de los cuales al menos 1 debe ser un numero. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La fortaleza de la contrasena es debil 'padre_contrasena'. La contrasena debe tener una longitud minima de 8 caracteres de los cuales al menos 1 debe ser un numero. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Fecha de nacimiento, campo obligatorio, valida que sea fecha
            if ($validaDatosEspecificos["padre_fecha_nacimiento"] == "") {
                array_push($errores, "El campo 'padre_fecha_nacimiento' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'padre_fecha_nacimiento' es obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($fecha = DateTime::createFromFormat("d/m/Y", $validaDatosEspecificos["padre_fecha_nacimiento"])) === false) {
                    array_push($errores, "No ha insertado una padre_fecha de nacimiento valida. Formato valido: 'dd/mm/yyyy'. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. No ha insertado una padre_fecha de nacimiento valida. Formato valido: 'dd/mm/yyyy'. Registro $i") : "";
                    $insercion = false;
                } else {
                    $fecha = $fecha->format('Y-m-d');
                    $camposPadre.=", fecha_nacimiento";
                    $datosPadre.=", '$fecha'";
                }
            }

            //Curp, es opcional, solo hay que validar que no sea mayor a 18 digitos
            if ($validaDatosEspecificos["padre_curp"] != "") {
                if (strlen($validaDatosEspecificos["padre_curp"]) > 18) {
                    array_push($errores, "El padre_CURP no debe sobrepasar los 18 caracteres. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. El padre_CURP no debe sobrepasar los 18 caracteres. Registro $i") : "";
                    $insercion = false;
                } else {
                    $camposPadre.=", curp";
                    $datosPadre.=", '" . __($validaDatosEspecificos["padre_curp"]) . "'";
                }
            }

            //Codigo postal es un campo obligatorio, validar que sea numero y no pase de 5 caracteres.
            if ($validaDatosEspecificos["padre_codigo_postal"] == "") {
                array_push($errores, "El campo 'padre_codigo_postal' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'padre_codigo_postal' es obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (strlen($validaDatosEspecificos["padre_codigo_postal"]) > 5 || !is_numeric($validaDatosEspecificos["codigo_postal"])) {
                    array_push($errores, "Formato de padre_codigo postal incorrecto. Formato valido: ##### ej 50010. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. Formato de padre_codigo postal incorrecto. Formato valido: ##### ej 50010. Registro $i") : "";
                    $insercion = false;
                } else {
                    $camposPadre.=", codigo_postal";
                    $datosPadre.=", '" . $validaDatosEspecificos["padre_codigo_postal"] . "'";
                }
            }

            //Calle, es un campo obligatorio
            if (!validaCampoObligatorio("padre_calle", $validaDatosEspecificos, $camposPadre, $datosPadre, $debug, $i, $errores)) {
                $insercion = false;
            }

            //Numero de casa exterior es obligatorio
            if (!validaCampoObligatorio("padre_no_casa_ext", $validaDatosEspecificos, $camposPadre, $datosPadre, $debug, $i, $errores)) {
                $insercion = false;
            }

            //Numero de casa interior no es obligatorio
            validaCampoNoObligatorio("padre_no_casa_int", $validaDatosEspecificos, $camposPadre, $datosPadre);

            //Colonia o localidad, es un campo obligatorio
            if (!validaCampoObligatorio("padre_colonia_localidad", $validaDatosEspecificos, $camposPadre, $datosPadre, $debug, $i, $errores)) {
                $insercion = false;
            }

            //Delegacion o municipio, es un campo obligatorio
            if (!validaCampoObligatorio("padre_delegacion_municipio", $validaDatosEspecificos, $camposPadre, $datosPadre, $debug, $i, $errores)) {
                $insercion = false;
            }

            //Entidad federativa, es campo obligatorio, debe ir a buscar el id de la entidad federativa
            if ($validaDatosEspecificos["padre_entidad_federativa"] == "") {
                array_push($errores, "El campo 'padre_entidad_federativa' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'padre_entidad_federativa' es obligatorio.  Registro $i") : "";
                $insercion = false;
            } else {
                if (($id_entidad = getIdEntidadFederativa($validaDatosEspecificos["padre_entidad_federativa"])) != false) {
                    $camposPadre.=", id_entidad_federativa";
                    $datosPadre.=", '" . $id_entidad . "'";
                } else {
                    //var_dump($id_entidad);
                    array_push($errores, "La padre_entidad federativa no existe o no esta escrita correctamente. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La padre_ entidad federativa no existe o no esta escrita correctamente. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Nacionalidad
            if ($validaDatosEspecificos["padre_nacionalidad"] == "") {
                array_push($errores, "El campo 'padre_nacionalidad' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'padre_nacionalidad' es obligatorio.  Registro $i") : "";
                $insercion = false;
            } else {
                if (($id_nacionalidad = getIdNacionalidad($validaDatosEspecificos["padre_nacionalidad"]) ) != false) {
                    $camposPadre.=", id_nacionalidad";
                    $datosPadre.=", '" . $id_nacionalidad . "'";
                } else {
                    array_push($errores, "La nacionalidad del padre no existe o no esta escrita correctamente. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La nacionalidad del padre no existe o no esta escrita correctamente. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Zona horaria, Campo obligatorio y valida que sea una de las permitidas
            if ($validaDatosEspecificos["padre_zona_horaria"] != "") {
                $zona = strtoupper(str_replace(" ", "", trim($validaDatosEspecificos["padre_zona_horaria"])));
                if ($zona == "GMT-6" || $zona == "GMT-7" || $zona == "GMT-8") {
                    $camposPadre.=", zona_horaria";
                    $datosPadre.=", '" . $zona . "'";
                } else {
                    array_push($errores, "El campo 'padre_zona_horaria' tiene un formato incorrecto. Formatos validos: GMT-6, GMT-7, GMT-8. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. El campo 'padre_zona_horaria' tiene un formato incorrecto. Formatos validos: GMT-6, GMT-7, GMT-8. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Telefono fijo no obligatorio
            validaCampoNoObligatorio("padre_telefono_fijo", $validaDatosEspecificos, $camposPadre, $datosPadre);

            //Telefono movil no obligatorio
            validaCampoNoObligatorio("padre_telefono_movil", $validaDatosEspecificos, $camposPadre, $datosPadre);

            //Asigna tipo_usuario
            $camposPadre.=", tipo_usuario";
            $datosPadre.=", 3";

            //Campos especificos padre
            $camposEspecificosPadre = "status";
            $datosEspecificosPadre = "1";

            //Nivel escolar, es un campo obligatorio, busca el nombre del nivel escolar  y retorna su id
            if ($validaDatosEspecificos["nivel_educativo"] == "") {
                array_push($errores, "El 'nivel_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'nivel_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
                $idNivel = false;
            } else {
                if (($idNivel = getIdNivel($validaDatosEspecificos["nivel_educativo"])) != false) {
//                    $camposEspecificos .= ", id_nivel";
//                    $datosEspecificos .= ", $idNivel";
                } else {
                    array_push($errores, "La referencia con el nivel escolar no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el nivel escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Grado escolar, no es un campo obligatorio, busca el nombre del grado y retorna su id
            if ($validaDatosEspecificos["padre_grado_escolar"] == "" && $idNivel === false) {
                array_push($errores, "El 'grado_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'grado_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($idGrado = getIdGrado($validaDatosEspecificos["padre_grado_escolar"], $idNivel)) != false) {
                    $camposEspecificos .= ", id_ultimo_grado_escolar";
                    $datosEspecificos .= ", $idGrado";
                } else {
                    $grado = $validaDatosEspecificos["padre_grado_escolar"];
                    array_push($errores, "La referencia con el grado escolar '$grado' no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el grado escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            /**
             * Profesor
             */
            //Valida campos y va preparando el insert
            $camposProfesor = "";
            $datosProfesor = "";

            //Nombre de pila, campo obligatorio
            if (!validaCampoObligatorio("profesor_nombre_pila", $validaDatosEspecificos, $camposProfesor, $datosProfesor, $debug, $i, $errores)) {
                $insercion = false;
            }

            $camposProfesor = str_replace(",", "", $camposProfesor);
            $datosProfesor = str_replace(",", "", $datosProfesor);

            //Primer apellido, campo obligatorio
            if (!validaCampoObligatorio("profesor_primer_apellido", $validaDatosEspecificos, $camposProfesor, $datosProfesor, $debug, $i, $errores)) {
                $insercion = false;
            }

            //Segundo apellido, campo no obligatorio
            validaCampoNoObligatorio("profesor_segundo_apellido", $validaDatosEspecificos, $camposProfesor, $datosProfesor);

            //Nombre de usuario, campo obligatorio y no se debe de repetir
            if ($validaDatosEspecificos["profesor_nombre_usuario"] == "") {
                array_push($errores, "El campo 'profesor_nombre_usuario' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'nombre_usuario' es obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (consultaUsuario($validaDatosEspecificos["profesor_nombre_usuario"])) {
                    array_push($errores, "El nombre de usuario " . $validaDatosEspecificos["profesor_nombre_usuario"] . " ya ha sido registrado. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. El nombre de usuario " . $validaDatosEspecificos["profesor_nombre_usuario"] . " ya ha sido registrado. Registro $i") : "";
                    $insercion = false;
                } else {
                    if (validaNombreUsuario($validaDatos["profesor_nombre_usuario"])) {
                        $camposProfesor.=", nombre_usuario";
                        $datosProfesor.=", '" . __($validaDatosEspecificos["profesor_nombre_usuario"]) . "'";
                    } else {
                        array_push($errores, "Error en 'profesor_nombre_usuario'. Moodle no acepta este nombre de usuario. Los nombre de usuario deben tener solo minusculas y no deben contener espacios. Registro $i");
                        ($debug != NULL) ? imprimeError("ERROR. Error en 'profesor_nombre_usuario'. Moodle no acepta este nombre de usuario. Los nombre de usuario deben tener solo minusculas y no deben contener espacios. Registro $i") : "";
                        $insercion = false;
                    }
                }
            }

            //Correo, campo obligatorio, no se puede repetir y valida que sea un correo valido
            if ($validaDatosEspecificos["profesor_correo"] == "") {
                array_push($errores, "El campo 'profesor_correo' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'profesor_correo' es obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (esEmail($validaDatosEspecificos["profesor_correo"])) {
                    if (consultaCorreo($validaDatosEspecificos["profesor_correo"])) {
                        array_push($errores, "El correo " . $validaDatosEspecificos["profesor_correo"] . " ya ha sido registrado. Registro $i");
                        ($debug != NULL) ? imprimeError("ERROR. El nombre de usuario " . $validaDatosEspecificos["profesor_correo"] . " ya ha sido registrado. Registro $i") : "";
                        $insercion = false;
                    } else {
                        $camposProfesor.=", correo";
                        $datosProfesor.=", '" . __($validaDatosEspecificos["profesor_correo"]) . "'";
                    }
                } else {
                    array_push($errores, "No es un profesor_correo valido. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. No es un profesor_correo valido. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Contrasena, campo obligatorio, y encripta la contraseña
            if ($validaDatosEspecificos["profesor_contrasena"] == "") {
                $contrasena = ucfirst(Utilidades::generaPwd(6)) . "1As$";
                $contrasenaEnc = nCrypt($contrasena);
                $campos .= ", contrasena";
                $datos .= ", '$contrasenaEnc'";
                $arreglo["arreglo"][$i]["profesor_contrasena"] = $contrasena;
            } else {
                if (validaFortaleza($validaDatosEspecificos["profesor_contrasena"])) {
                    $contrasenaEnc = nCrypt($validaDatosEspecificos["profesor_contrasena"]);
                    $campos.=", contrasena";
                    $datos.=", '$contrasenaEnc'";
                } else {
                    array_push($errores, "La fortaleza de la contrasena es debil. La contrasena debe tener una longitud minima de 8 caracteres de los cuales al menos 1 debe ser un numero. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La fortaleza de la contrasena es debil. La contrasena debe tener una longitud minima de 8 caracteres de los cuales al menos 1 debe ser un numero. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Fecha de nacimiento, campo obligatorio, valida que sea fecha
            if ($validaDatosEspecificos["profesor_fecha_nacimiento"] == "") {
                array_push($errores, "El campo 'profesor_fecha_nacimiento' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'profesor_fecha_nacimiento' es obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($fecha = DateTime::createFromFormat("d/m/Y", $validaDatosEspecificos["padre_fecha_nacimiento"])) === false) {
                    array_push($errores, "No ha insertado una profesor_fecha de nacimiento valida. Formato valido: 'dd/mm/yyyy'. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. No ha insertado una profesor_fecha de nacimiento valida. Formato valido: 'dd/mm/yyyy'. Registro $i") : "";
                    $insercion = false;
                } else {
                    $fecha = $fecha->format('Y-m-d');
                    $camposProfesor.=", fecha_nacimiento";
                    $datosProfesor.=", '$fecha'";
                }
            }

            //Curp, es opcional, solo hay que validar que no sea mayor a 18 digitos
            if ($validaDatosEspecificos["profesor_curp"] != "") {
                if (strlen($validaDatosEspecificos["profesor_curp"]) > 18) {
                    array_push($errores, "El profesor_CURP no debe sobrepasar los 18 caracteres. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. El profesor_CURP no debe sobrepasar los 18 caracteres. Registro $i") : "";
                    $insercion = false;
                } else {
                    $camposProfesor.=", curp";
                    $datosProfesor.=", '" . __($validaDatosEspecificos["profesor_curp"]) . "'";
                }
            }

            //Codigo postal es un campo obligatorio, validar que sea numero y no pase de 5 caracteres.
            if ($validaDatosEspecificos["profesor_codigo_postal"] == "") {
                array_push($errores, "El campo 'profesor_codigo_postal' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'profesor_codigo_postal' es obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (strlen($validaDatosEspecificos["profesor_codigo_postal"]) > 5 || !is_numeric($validaDatosEspecificos["codigo_postal"])) {
                    array_push($errores, "Formato de profesor_codigo postal incorrecto. Formato valido: ##### ej 50010. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. Formato de profesor_codigo postal incorrecto. Formato valido: ##### ej 50010. Registro $i") : "";
                    $insercion = false;
                } else {
                    $camposProfesor.=", codigo_postal";
                    $datosProfesor.=", '" . $validaDatosEspecificos["profesor_codigo_postal"] . "'";
                }
            }

            //Calle, es un campo obligatorio
            if (!validaCampoObligatorio("profesor_calle", $validaDatosEspecificos, $camposProfesor, $datosProfesor, $debug, $i, $errores)) {
                $insercion = false;
            }

            //Numero de casa exterior es obligatorio
            if (!validaCampoObligatorio("profesor_no_casa_ext", $validaDatosEspecificos, $camposProfesor, $datosProfesor, $debug, $i, $errores)) {
                $insercion = false;
            }

            //Numero de casa interior no es obligatorio
            validaCampoNoObligatorio("profesor_no_casa_int", $validaDatosEspecificos, $camposProfesor, $datosProfesor);

            //Colonia o localidad, es un campo obligatorio
            if (!validaCampoObligatorio("profesor_colonia_localidad", $validaDatosEspecificos, $camposProfesor, $datosProfesor, $debug, $i, $errores)) {
                $insercion = false;
            }

            //Delegacion o municipio, es un campo obligatorio
            if (!validaCampoObligatorio("profesor_delegacion_municipio", $validaDatosEspecificos, $camposProfesor, $datosProfesor, $debug, $i, $errores)) {
                $insercion = false;
            }

            //Entidad federativa, es campo obligatorio, debe ir a buscar el id de la entidad federativa
            if ($validaDatosEspecificos["profesor_entidad_federativa"] == "") {
                array_push($errores, "El campo 'profesor_entidad_federativa' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'profesor_entidad_federativa' es obligatorio.  Registro $i") : "";
                $insercion = false;
            } else {
                if (($id_entidad = getIdEntidadFederativa($validaDatosEspecificos["profesor_entidad_federativa"])) != false) {
                    $camposProfesor.=", id_entidad_federativa";
                    $datosProfesor.=", '" . $id_entidad . "'";
                } else {
                    //var_dump($id_entidad);
                    array_push($errores, "La profesor_entidad federativa no existe o no esta escrita correctamente. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La profesor_ entidad federativa no existe o no esta escrita correctamente. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Nacionalidad
            if ($validaDatosEspecificos["profesor_nacionalidad"] == "") {
                array_push($errores, "El campo 'profesor_nacionalidad' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'profesor_nacionalidad' es obligatorio.  Registro $i") : "";
                $insercion = false;
            } else {
                if (($id_nacionalidad = getIdNacionalidad($validaDatosEspecificos["profesor_nacionalidad"]) ) != false) {
                    $camposProfesor.=", id_nacionalidad";
                    $datosProfesor.=", '" . $id_nacionalidad . "'";
                } else {
                    array_push($errores, "La nacionalidad del padre no existe o no esta escrita correctamente. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La nacionalidad del padre no existe o no esta escrita correctamente. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Zona horaria, Campo obligatorio y valida que sea una de las permitidas
            if ($validaDatosEspecificos["profesor_zona_horaria"] != "") {
                $zona = strtoupper(str_replace(" ", "", trim($validaDatosEspecificos["profesor_zona_horaria"])));
                if ($zona == "GMT-6" || $zona == "GMT-7" || $zona == "GMT-8") {
                    $camposProfesor.=", zona_horaria";
                    $datosProfesor.=", '" . $zona . "'";
                } else {
                    array_push($errores, "El campo 'profesor_zona_horaria' tiene un formato incorrecto. Formatos validos: GMT-6, GMT-7, GMT-8. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. El campo 'profesor_zona_horaria' tiene un formato incorrecto. Formatos validos: GMT-6, GMT-7, GMT-8. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Telefono fijo no obligatorio
            validaCampoNoObligatorio("profesor_telefono_fijo", $validaDatosEspecificos, $camposProfesor, $datosProfesor);

            //Telefono movil no obligatorio
            validaCampoNoObligatorio("profesor_telefono_movil", $validaDatosEspecificos, $camposProfesor, $datosProfesor);

            //Asigna tipo_usuario
            $camposProfesor.=", tipo_usuario";
            $datosProfesor.=", 2";

            //Campos especificos profesor
            $camposEspecificosProfesor = "status";
            $datosEspecificosProfesor = "1";

            //Puesto ocupacion
//            validaCampoNoObligatorio("profesor_puesto_obligacion", $validaDatosEspecificos, $camposEspecificos, $datosEspecificos);
            //Puesto ocupacion, campo obligatorio
            if (!validaCampoObligatorio("profesor_puesto_ocupacion", $validaDatosEspecificos, $camposProfesor, $datosProfesor, $debug, $i, $errores)) {
                $insercion = false;
            }

            //Escuela es un campo obligatorio, busca el nombre de la escuela y retorna su id
            if ($validaDatosEspecificos["profesor_escuela"] == "") {
                array_push($errores, "La 'profesor_escuela' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. La 'profesor_escuela' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($idEscuela = getIdEscuela($validaDatosEspecificos["profesor_escuela"])) != false) {
                    $camposEspecificosProfesor .= ", id_escuela";
                    $datosEspecificosProfesor .= ", $idEscuela";
                } else {
                    array_push($errores, "La referencia con la profesor_escuela no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con la profesor_escuela no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Nivel escolar, no es un campo obligatorio, busca el nombre del nivel escolar  y retorna su id
            if ($validaDatosEspecificos["profesor_nivel_educativo"] == "") {
                array_push($errores, "El 'profesor_nivel_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'profesor_nivel_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
                $idNivel = false;
            } else {
                if (($idNivel = getIdNivel($validaDatosEspecificos["profesor_nivel_educativo"])) != false) {
//                    $camposEspecificosProfesor .= ", id_nivel";
//                    $datosEspecificosProfesor .= ", $idNivel";
                } else {
                    array_push($errores, "La referencia con el nivel escolar no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el nivel escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Grado escolar, no es un campo obligatorio, busca el nombre del grado y retorna su id
            if ($validaDatosEspecificos["profesor_grado_escolar"] == "" && $idNivel === false) {
                array_push($errores, "El 'profesor_grado_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'profesor_grado_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($idGrado = getIdGrado($validaDatosEspecificos["profesor_grado_escolar"], $idNivel)) != false) {
                    $camposEspecificosProfesor .= ", id_grado_escolar_enrolado";
                    $datosEspecificosProfesor .= ", $idGrado";
                } else {
                    $grado = $validaDatosEspecificos["profesor_grado_escolar"];
                    array_push($errores, "La referencia con el grado escolar '$grado' no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el grado escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }
            return array(
                "campos" => $camposEspecificos,
                "datos" => $datosEspecificos,
                "camposPadre" => $camposPadre,
                "datosPadre=" => $datosPadre,
                "camposEspecificosPadre" => $camposEspecificosPadre,
                "datosEspecificosPadre" => $datosEspecificosPadre,
                "camposProfesor" => $camposProfesor,
                "datosProfesor" => $datosProfesor,
                "camposEspecificosProfesor" => $camposEspecificosProfesor,
                "datosEspecificosProfesor" => $datosEspecificosProfesor);
            break;
        case "estudiantes":
            //Asigna status y tipo de alumno
            $camposEspecificos = "status, tipo_alumno";
            $datosEspecificos = "1, 0";

            //Matricula no obligatoria
            validaCampoNoObligatorio("matricula", $validaDatosEspecificos, $camposEspecificos, $datosEspecificos);

            //Padre, no es obligatorio, si trae un registro busca el id del padre
            //puede ser el nombre de usuario o el correo del padre
            if ($validaDatosEspecificos["padre"] != "") {
                if (($idPadre = getIdPadre($validaDatosEspecificos["padre"])) != false) {
                    $camposEspecificos .= ", id_padre";
                    $datosEspecificos .= ", $idPadre";
                } else {
                    array_push($errores, "La referencia con el padre no existe. Puede ingresarse el correo o el nombre de usaurio del Padre. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el padre no existe. Puede ingresarse el correo o el nombre de usaurio del Padre. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Profesor de aula, no es obligatorio, si trae un registro busca el id del profesor
            if ($validaDatosEspecificos["profesor_aula"] != "") {
                if (($idProfesor = getIdProfesor($validaDatosEspecificos["profesor_aula"])) != false) {
                    $camposEspecificos .= ", id_profesor_aula";
                    $datosEspecificos .= ", $idProfesor";
                } else {
                    array_push($errores, "La referencia con el profesor de aula no existe. Puede ingresarse el correo o el nombre de usaurio del profesor de aula. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el profesor de aula no existe. Puede ingresarse el correo o el nombre de usaurio del profesor de aula. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Nivel escolar, es un campo obligatorio, busca el nombre del nivel escolar  y retorna su id
            if ($validaDatosEspecificos["nivel_educativo"] == "") {
                array_push($errores, "El 'nivel_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'nivel_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
                $idNivel = false;
            } else {
                if (($idNivel = getIdNivel($validaDatosEspecificos["nivel_educativo"])) != false) {
//                    $camposEspecificos .= ", id_nivel";
//                    $datosEspecificos .= ", $idNivel";
                } else {
                    array_push($errores, "La referencia con el nivel escolar no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el nivel escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Grado escolar, es un campo obligatorio, busca el nombre del grado y retorna su id
            if ($validaDatosEspecificos["grado_escolar"] == "" && $idNivel === false) {
                array_push($errores, "El 'grado_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'grado_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($idGrado = getIdGrado($validaDatosEspecificos["grado_escolar"], $idNivel)) != false) {
                    $camposEspecificos .= ", id_grado_escolar";
                    $datosEspecificos .= ", $idGrado";
                } else {
                    $grado = $validaDatosEspecificos["grado_escolar"];
                    array_push($errores, "La referencia con el grado escolar '$grado' no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el grado escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Escuela es un campo obligatorio, busca el nombre de la escuela y retorna su id
            if ($validaDatosEspecificos["escuela"] == "") {
                array_push($errores, "La 'escuela' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. La 'escuela' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($idEscuela = getIdEscuela($validaDatosEspecificos["escuela"])) != false) {
                    $camposEspecificos .= ", id_escuela";
                    $datosEspecificos .= ", $idEscuela";
                } else {
                    array_push($errores, "La referencia con la escuela no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con la escuela no existe. Registro $i") : "";
                    $insercion = false;
                }
            }
            return array("campos" => $camposEspecificos, "datos" => $datosEspecificos);
            break;
        case "profesionistas":
            $camposEspecificos = "status, tipo_alumno";
            $datosEspecificos = "1, 1";

            //Empresa, es un campo obligatorio, buscar el nombre de la empresa y regresar el id
            if ($validaDatosEspecificos["empresa"] == "") {
                array_push($errores, "El campo 'empresa' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'empresa' es obligatorio.  Registro $i") : "";
                $insercion = false;
            } else {
                if (($id_empresa = getIdEmpresa($validaDatosEspecificos["empresa"])) != false) {
                    $camposEspecificos.=", id_empresa";
                    $datosEspecificos.=", '" . $id_empresa . "'";
                } else {
                    array_push($errores, "La empresa no existe o no esta escrita correctamente. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La empresa no existe o no esta escrita correctamente. Registro $i") : "";
                    $insercion = false;
                }
            }
            //Puesto ocupacion
            validaCampoNoObligatorio("puesto_ocupacion", $validaDatosEspecificos, $camposEspecificos, $datosEspecificos);

            //Nivel escolar, es un campo obligatorio, busca el nombre del nivel escolar  y retorna su id
            if ($validaDatosEspecificos["nivel_educativo"] == "") {
                array_push($errores, "El 'nivel_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'nivel_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
                $idNivel = false;
            } else {
                if (($idNivel = getIdNivel($validaDatosEspecificos["nivel_educativo"])) != false) {
//                    $camposEspecificos .= ", id_nivel";
//                    $datosEspecificos .= ", $idNivel";
                } else {
                    array_push($errores, "La referencia con el nivel escolar no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el nivel escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Grado escolar, es un campo obligatorio, busca el nombre del grado y retorna su id
            if ($validaDatosEspecificos["grado_escolar"] == "" && $idNivel === false) {
                array_push($errores, "El 'grado_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'grado_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($idGrado = getIdGrado($validaDatosEspecificos["grado_escolar"], $idNivel)) != false) {
                    $camposEspecificos .= ", id_grado_escolar";
                    $datosEspecificos .= ", $idGrado";
                } else {
                    $grado = $validaDatosEspecificos["grado_escolar"];
                    array_push($errores, "La referencia con el grado escolar '$grado' no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el grado escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            return array("campos" => $camposEspecificos, "datos" => $datosEspecificos);
            break;
        case "tutores":
            $camposEspecificos = "status";
            $datosEspecificos = "1";

            //Rol del tutor es un campo obligatorio, busca el id del tutor por medio del nombre
            if ($validaDatosEspecificos["rol"] == "") {
                array_push($errores, "El campo 'rol' es obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El campo 'rol' es obligatorio.  Registro $i") : "";
                $insercion = false;
            } else {
                if (($id_rol = getIdRol($validaDatosEspecificos["rol"])) != false) {
                    $camposEspecificos.=", id_rol_tutor";
                    $datosEspecificos.=", '" . $id_rol . "'";
                } else {
                    array_push($errores, "El rol no existe o no esta escrita correctamente. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. El rol no existe o no esta escrita correctamente. Registro $i") : "";
                    $insercion = false;
                }
            }
            return array("campos" => $camposEspecificos, "datos" => $datosEspecificos);
            break;
        case "profesores":
            $camposEspecificos = "status";
            $datosEspecificos = "1";

            //Puesto u ocupacion es un campo obligatorio
            if (!validaCampoObligatorio("puesto_ocupacion", $validaDatosEspecificos, $camposEspecificos, $datosEspecificos, $debug, $i, $errores)) {
                $insercion = false;
            }

            //Escuela es un campo obligatorio, busca el nombre de la escuela y retorna su id
            if ($validaDatosEspecificos["escuela"] == "") {
                array_push($errores, "La 'escuela' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. La 'escuela' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($idEscuela = getIdEscuela($validaDatosEspecificos["escuela"])) != false) {
                    $camposEspecificos .= ", id_escuela";
                    $datosEspecificos .= ", $idEscuela";
                } else {
                    array_push($errores, "La referencia con la escuela no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con la escuela no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Nivel escolar, no es un campo obligatorio, busca el nombre del nivel escolar  y retorna su id
            if ($validaDatosEspecificos["nivel_educativo"] == "") {
                array_push($errores, "El 'nivel_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'nivel_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
                $idNivel = false;
            } else {
                if (($idNivel = getIdNivel($validaDatosEspecificos["nivel_educativo"])) != false) {
//                    $camposEspecificos .= ", id_nivel";
//                    $datosEspecificos .= ", $idNivel";
                } else {
                    array_push($errores, "La referencia con el nivel escolar no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el nivel escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Grado escolar, no es un campo obligatorio, busca el nombre del grado y retorna su id
            if ($validaDatosEspecificos["grado_escolar"] == "" && $idNivel === false) {
                array_push($errores, "El 'grado_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'grado_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($idGrado = getIdGrado($validaDatosEspecificos["grado_escolar"], $idNivel)) != false) {
                    $camposEspecificos .= ", id_grado_escolar_enrolado";
                    $datosEspecificos .= ", $idGrado";
                } else {
                    $grado = $validaDatosEspecificos["grado_escolar"];
                    array_push($errores, "La referencia con el grado escolar '$grado' no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el grado escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }
            return array("campos" => $camposEspecificos, "datos" => $datosEspecificos);
            break;
        case "padres":
            $camposEspecificos = "status";
            $datosEspecificos = "1";

            //Nivel escolar, no es un campo obligatorio, busca el nombre del nivel escolar  y retorna su id
            if ($validaDatosEspecificos["nivel_educativo"] == "") {
                array_push($errores, "El 'nivel_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'nivel_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
                $idNivel = false;
            } else {
                if (($idNivel = getIdNivel($validaDatosEspecificos["nivel_educativo"])) != false) {
//                    $camposEspecificos .= ", id_nivel";
//                    $datosEspecificos .= ", $idNivel";
                } else {
                    array_push($errores, "La referencia con el nivel escolar no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el nivel escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }

            //Grado escolar, no es un campo obligatorio, busca el nombre del grado y retorna su id
            if ($validaDatosEspecificos["grado_escolar"] == "" && $idNivel === false) {
                array_push($errores, "El 'grado_escolar' es un campo obligatorio. Registro $i");
                ($debug != NULL) ? imprimeError("ERROR. El 'grado_escolar' es un campo obligatorio. Registro $i") : "";
                $insercion = false;
            } else {
                if (($idGrado = getIdGrado($validaDatosEspecificos["grado_escolar"], $idNivel)) != false) {
                    $camposEspecificos .= ", id_ultimo_grado_escolar";
                    $datosEspecificos .= ", $idGrado";
                } else {
                    $grado = $validaDatosEspecificos["grado_escolar"];
                    array_push($errores, "La referencia con el grado escolar '$grado' no existe. Registro $i");
                    ($debug != NULL) ? imprimeError("ERROR. La referencia con el grado escolar no existe. Registro $i") : "";
                    $insercion = false;
                }
            }
            return array("campos" => $camposEspecificos, "datos" => $datosEspecificos);
            break;
        case "gestores":
            $camposEspecificos = "status";
            $datosEspecificos = "1";

            return array("campos" => $camposEspecificos, "datos" => $datosEspecificos);
            break;
        case "admin":
            break;
    }
    return NULL;
}

/**
 * Funcion que valida si trae mayusuclas y espacios
 * Devuelve false si tiene mayusculas o si tienes espacios
 * @param type $username
 * @return boolean
 */
function validaNombreUsuario($username) {
    $validacion = true;
    for ($i = 0; $i < strlen($username); $i++) {
        if ((ctype_alnum($username[$i]) && ctype_lower($username[$i])) || $username[$i] == "_" || $username[$i] == "-" || $username[$i] == "." || $username[$i] == "@" || ctype_digit($username[$i])) {
            $validacion = true;
        } else {
            return false;
        }
    }
    return $validacion;
}

?>
