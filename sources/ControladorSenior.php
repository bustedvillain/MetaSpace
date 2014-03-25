<?php

include_once 'Funciones.php';
if ($_POST) {
    $instruccion = $_POST["ins"];
    switch ($instruccion) {
        case "verPerfil":
            $idTutor = $_POST["idTutor"];
            $listaTutores = selectTutor($idTutor, null);
            foreach ($listaTutores as $tutor) {
                $arregloJSON = tutorToJSON($tutor);
               
            }
            echo $arregloJSON;
            break;

        case "verActividadTutor":
            $idTutor = $_POST["idTutor"];
            $listaActividades = selectActividadTutor($idTutor);
            $listadoEntradas = selectEntradasTutor($idTutor);
            
            echo<<<Divactividad
            <table class="table table-hover table-bordered">
            <thead>
                <th><b>Fecha Actividad</b></th>
                <th><b>Detalle</b></th>
            </thead>
            <tbody>

Divactividad;
            foreach ($listaActividades as $actividad) {
                echo<<<tablaTutoresJr
                <tr>
                    <td>
                        $actividad->fecha
                    </td>
                    <td>
                        $actividad->detalle
                    </td>
                </tr>
tablaTutoresJr;
            }
            
            foreach ($listadoEntradas as $entrada)
                {
                 echo<<<tablaTutoresJr2
                <tr>
                    <td>
                        $entrada->fecha_entrada
                    </td>
                    <td>
                        Entrada al sitio
                    </td>
                </tr>
tablaTutoresJr2;
                if(!$entrada->fecha_salida == '')
                {
                     echo<<<tablaTutoresJr4
                <tr>
                    <td>
                        $entrada->fecha_salida
                    </td>
                    <td>
                        Salida del sitio
                    </td>
                </tr>
tablaTutoresJr4;
                    
                }
                }
            
            
            echo<<<Divactividad
            </tbody>

        </table>
Divactividad;
            break;
        case "modificaPerfil":
            $idDatosPersonales = $_POST['idDatosPersonales'];
            $nombre = $_POST['nombre'];
            $primerApellido = $_POST['apellidoPaterno'];
            $segundoApellido = $_POST['apellidoMaterno'];
//            $nombreUsuario = $_POST['nombreUsuario'];
            $correo = $_POST['correo'];
            $contrasena = $_POST['contrasena'];
            $contrasena2 = $_POST['contrasena2'];
            $fechaNacimiento = $_POST['fechaNacimiento'];
            $curp = $_POST['curp'];
            $codigoPostal = $_POST['codigoPostal'];
            $calle = $_POST['calle'];
            $noCasaExt = $_POST['noExterior'];
            $noCasaInt = $_POST['noInterior'];
            $coloniaLocalidad = $_POST['coloniaLocalidad'];
            $delegacionMunicipio = $_POST['delegacionMunicipio'];
            $idEntidadFederativa = $_POST['entidadFederativa'];
            $idNacionalidad = $_POST['nacionalidad'];
            $zonaHoraria = $_POST['zonaHoraria'];
            $telefonoFijo = $_POST['telefonoFijo'];
            $telefonoMovil = $_POST['telefonoMovil'];
            $fechaActual = preparaFechaBaseDatos(fechaServidor(0, 0, -3));
            $fechaIngresada = preparaFechaBaseDatos($fechaNacimiento);
            $arrayErrores = array();
//            preparaFechaBaseDatos(0);
//            array_push($arrayErrores, "actual es $fechaActual y la ingresada es $fechaIngresada");
            if($fechaActual < $fechaIngresada){
                array_push($arrayErrores, "No puedes colocar una fecha menor a 3  años");
            }else{
//                array_push($arrayErrores, "No entró, actual es $fechaActual e ingresada es $fechaIngresada");
                $fechaNacimiento = preparaFechaBaseDatos($fechaNacimiento);
            }
                
//            else
//                array_push($arrayErrores, "La ingresada es mayor a 3 anos");
//            array_push($arrayErrores, "actual es $fechaActual y la ingresada es $fechaIngresada");
//            array_push($arrayErrores, "actual es $fechaActual y la ingresada es $fechaIngresada");
            //verificar si hubo errores
            
            if ($contrasena != $contrasena2) {array_push($arrayErrores, "Las contraseñas no coinciden");}
            if (!is_string($nombre)) {array_push($arrayErrores, "El nombre no es texto");}
            if (!is_string($primerApellido)) {array_push($arrayErrores, "El primer apellido no es texto");}
            if (!is_string($segundoApellido)) {array_push($arrayErrores, "El segundo apellido no es texto");}
            if (!is_string($correo)) {array_push($arrayErrores, "El correo no es texto");}
            if (!is_string($contrasena)) {array_push($arrayErrores, "La contraseña no es texto");}
            if (!is_string($fechaNacimiento)) {array_push($arrayErrores, "La fecha nacimiento no es texto");}
//            if (!is_string($curp)) {array_push($arrayErrores, "El CURP no es texto");}
//            if (!is_string($calle)) {array_push($arrayErrores, "La calle no es texto");}
//            if (!is_numeric($noCasaExt)) {array_push($arrayErrores, "El número exterior no es un número".$noCasaExt);}
//            if (!is_numeric($noCasaInt)) {array_push($arrayErrores, "El número interior no es un número".$noCasaInt);}
//            if (!is_string($coloniaLocalidad)) {array_push($arrayErrores, "La colonia localidad no es texto");}
//            if (!is_string($delegacionMunicipio)) {array_push($arrayErrores, "La delegacion/municpio no es texto");}
            if (!is_numeric($telefonoFijo)) {array_push($arrayErrores, "El teléfono fijo no es un número".$noCasaInt);}
            if (!is_numeric($telefonoMovil)) {array_push($arrayErrores, "El teléfono fijo no es un número".$noCasaInt);}
            if (strlen($nombre) < 1){array_push($arrayErrores,"El campo nombre está vacío");}
            if (strlen($primerApellido) < 1){array_push($arrayErrores,"El campo primer apellido está vacío");}
//            if (strlen($segundoApellido) < 1){array_push($arrayErrores,"El campo segundo  está vacío");}

            if (strlen($fechaNacimiento) < 1){array_push($arrayErrores,"El campo fecha de nacimiento está vacío");}
            if (strlen($codigoPostal) < 1){array_push($arrayErrores,"El campo código postal está vacío");}
//            if (strlen($calle) < 1){array_push($arrayErrores,"El campo calle está vacío");}
//            if (strlen($noCasaExt) < 1){array_push($arrayErrores,"El campo número exterior está vacío");}
//            if (strlen($coloniaLocalidad) < 1){array_push($arrayErrores,"El campo colonia localidad está vacío");}
//            if (strlen($delegacionMunicipio) < 1){array_push($arrayErrores,"El campo delegación municipio está vacío");}
            if (count($arrayErrores) > 0) {
                echo llenaCadenaErrores($arrayErrores);
            } else {
                $correoQ = $passQ = "";
                if (!strlen($correo) < 1){
                    $correoQ = " correo = '$correo', ";   
                }
                if (!strlen($contrasena) < 1){
                    $passQ = " contrasena = '".nCrypt($contrasena)."', ";
                    
                }
                require_once 'Query.php';
                $sql = new Query("SG");
                $query = "UPDATE datos_personales set 
                nombre_pila = '$nombre', 
                primer_apellido = '$primerApellido', 
                segundo_apellido = '$segundoApellido',     
                $correoQ
                $passQ
                fecha_nacimiento = '$fechaIngresada', 
                codigo_postal = $codigoPostal, 
                id_entidad_federativa = $idEntidadFederativa, 
                telefono_fijo = $telefonoFijo, 
                telefono_movil = $telefonoMovil 
                    where id_datos_personales = $idDatosPersonales";
                //echo $sql->sql;
                if ($sql->update($query)) {
                    echo 'Datos actualizados correctamente';
                } else {
                    echo 'Algo fallo';
                }
            }

            
            break;
    }
}

function llenaCadenaErrores($arrErrores) {
    $cadenaError = "Hubo algunos errores que debes anteder:";
    foreach ($arrErrores as $error) {
        $cadenaError = $cadenaError . "\n\t•" . $error;
    }
    return $cadenaError;
}

?>
