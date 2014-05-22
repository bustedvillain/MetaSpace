<?php
//Control de cambios 3
//Se agregaron funciones para devolver la fecha del servidor function fechaServidor


//Inicia control de cambios 3
/**
 * Función que devuelve la hora del servidor, OJO no acepta null como parametros
 * @param type $dias  si no se desean sumar/restar puede ser 0
 * @param type $meses si no se desean sumar/restar puede ser 0
 * @param type $anyos si no se desean sumar/restar puede ser 0
 * @return type
 */
function fechaServidor($dias = 0, $meses = 0, $anyos = 0) {
    $actual = date("Y-m-j");
    if (isset($dias)) {
        $actual = strtotime("+$dias day", strtotime($actual));
    }
    $actual = date("Y-m-j", $actual);
    if (isset($meses)) {
        $actual = strtotime("+$meses month", strtotime($actual));
    }
    $actual = date("Y-m-j", $actual);
    if (isset($anyos)) {
        $actual = strtotime("+$anyos year", strtotime($actual));
    }
    return date('d/m/Y', $actual);
}
///finaliza control de cambios 3

/**
 * Funcion que recibe una fecha y la retorna en el formato adecuado
 * para insertarla en la base de datos PostgreSql
 * @param type $fecha
 * @return boolean
 */
    function preparaFechaBaseDatos($fecha) {    
    if (preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $fecha, $matches)) {
        //dd/mm/yyyy
        return $matches[3]."-".$matches[2]."-".$matches[1];
    } else if (preg_match("/([0-9]{2})\-([0-9]{2})\-([0-9]{4})/", $fecha, $matches)) {
        //dd-mm-yyyy
        return $matches[3]."-".$matches[2]."-".$matches[1];
    } else if (preg_match("/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/", $fecha, $matches)) {
        //yyyy-mm-dd
        return $fecha;
    } else if (preg_match("/([0-9]{4})\/([0-9]{2})\/([0-9]{2})/", $fecha, $matches)) {
        //yyyy/mm/dd
        return $matches[1]."-".$matches[2]."-".$matches[3];
    } else {
        return false;
    }
}
?>
