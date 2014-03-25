<?php

/*
 * FECHA DE CREACION: 9 DE ENERO DEL 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJETIVO: CORE PARA EL FUNCIONAMIENTO DE MENSAJES
 */

/**
 * Funcion que inserta un mensaje
 * @param type $idRemitente
 * @param type $idDestinatario
 * @param type $mensaje
 */
function insertarMensaje($idRemitente, $idDestinatario, $mensaje) {
    $mensaje = __($mensaje);
    $query = new Query("SG");
    $query->insert("mensajes", "id_remitente, id_destinatario, fecha_envio, mensaje", "$idRemitente, $idDestinatario, now(), '$mensaje'");
    return true;
}

/**
 * Funcion generica que consulta mensajes
 * @param type $idRemitente
 * @param type $idDestinatario
 * @param type $limit
 * @param type $pagina
 * @return null
 */
function consultarMensajes($idRemitente = NULL, $idDestinatario = NULL, $limit = NULL, $pagina = 1) {
    //OFFSET
    if (isset($pagina) && is_numeric($pagina)) {
        $pagina--;
        if (isset($limit)) {
            $offset = $limit * $pagina;
        } else {
            $offset = 10 * $pagina;
        }
    } else {
        $offset = 0;
    }
    $query = new Query("SG");

    //IN's  Remitente   
    if (isset($idRemitente)) {
        $insRemitente = "(";
        if (is_array($idRemitente)) {
            $primer = true;
            foreach ($idRemitente as $remitente) {
                if ($primer === true) {
                    $insRemitente.="$remitente";
                    $primer = false;
                } else {
                    $insRemitente.=", $remitente";
                }
            }
        } else {
            $insRemitente.="$idRemitente";
        }
        $insRemitente .= ")";
        $idRemitente = $insRemitente;
    }

    //IN's  Destinatario   
    if (isset($idDestinatario)) {
        $insDestinatario = "(";
        if (is_array($idDestinatario)) {
            $primer = true;
            foreach ($idDestinatario as $remitente) {
                if ($primer === true) {
                    $insDestinatario.="$remitente";
                    $primer = false;
                } else {
                    $insDestinatario.=", $remitente";
                }
            }
        } else {
            $insDestinatario.="$idDestinatario";
        }
        $insDestinatario .= ")";
        $idDestinatario = $insDestinatario;
    }

    //Si se quiere ver un mensajes entre 2 usuarios
    if (isset($idRemitente) && isset($idDestinatario)) {
        if (isset($limit)) {
            $limitext = "LIMIT $limit";
        } else {
            $limitext = "";
        }
        $query->sql = <<<select
                    SELECT m.id_mensaje, 
                           m.id_remitente, 
                           m.id_destinatario, 
                           to_char(m.fecha_envio, 'DD/MM/YYYY') fecha_envio, 
                           m.mensaje, 
                           (dp1.nombre_pila || ' ' || dp1.primer_apellido || ' ' || dp1.segundo_apellido) nombre_remitente,
                           (dp2.nombre_pila || ' ' || dp2.primer_apellido || ' ' || dp2.segundo_apellido) nombre_destinatario
                    FROM datos_personales dp1, datos_personales dp2, mensajes m
                    WHERE m.id_remitente = dp1.id_datos_personales
                      and m.id_destinatario = dp2.id_datos_personales
                      and m.id_remitente in $idRemitente
                      and m.id_destinatario in $idDestinatario
                    ORDER BY m.id_mensaje desc
                    $limitext
                    OFFSET $offset                   
select;
    }

    //Si se quiere ver los mensajes recibidos de un usuario
    if (isset($idDestinatario) && !isset($idRemitente)) {
        $query->sql = <<<select
                    SELECT m.id_mensaje, 
                           m.id_remitente, 
                           m.id_destinatario, 
                           to_char(m.fecha_envio, 'DD/MM/YYYY') fecha_envio, 
                           m.mensaje, 
                           (dp1.nombre_pila || ' ' || dp1.primer_apellido || ' ' || dp1.segundo_apellido) nombre_remitente,
                           (dp2.nombre_pila || ' ' || dp2.primer_apellido || ' ' || dp2.segundo_apellido) nombre_destinatario
                    FROM datos_personales dp1, mensajes m, datos_personales dp2
                    WHERE m.id_remitente = dp1.id_datos_personales
                      and m.id_destinatario = dp2.id_datos_personales
                      and m.id_destinatario in $idDestinatario
                    ORDER BY m.id_mensaje desc
                    $limitext
                    OFFSET $offset
                    
select;
    }

    //Si se quiere ver los mensajes enviado de un usuario
    if (!isset($idDestinatario) && isset($idRemitente)) {
        if (isset($limit)) {
            $limitext = "LIMIT $limit";
        } else {
            $limitext = "";
        }
        $query->sql = <<<select
                    SELECT m.id_mensaje, 
                           m.id_remitente, 
                           m.id_destinatario, 
                           to_char(m.fecha_envio, 'DD/MM/YYYY') fecha_envio, 
                           m.mensaje, 
                           (dp1.nombre_pila || ' ' || dp1.primer_apellido || ' ' || dp1.segundo_apellido) nombre_remitente,
                           (dp2.nombre_pila || ' ' || dp2.primer_apellido || ' ' || dp2.segundo_apellido) nombre_destinatario
                    FROM datos_personales dp1, datos_personales dp2, mensajes m
                    WHERE m.id_remitente = dp1.id_datos_personales
                      and m.id_destinatario = dp2.id_datos_personales
                      and m.id_remitente in $idRemitente     
                    ORDER BY m.id_mensaje desc                  
                    $limitext
                    OFFSET $offset
                    
select;
    }

    //Si se quiere ver todos los mensajes 
    if (!isset($idDestinatario) && !isset($idRemitente)) {
        if (isset($limit)) {
            $limitext = "LIMIT $limit";
        } else {
            $limitext = "";
        }
        $query->sql = <<<select
                    SELECT m.id_mensaje, 
                           m.id_remitente, 
                           m.id_destinatario, 
                           to_char(m.fecha_envio, 'DD/MM/YYYY') fecha_envio, 
                           m.mensaje, 
                           (dp1.nombre_pila || ' ' || dp1.primer_apellido || ' ' || dp1.segundo_apellido) nombre_remitente,
                           (dp2.nombre_pila || ' ' || dp2.primer_apellido || ' ' || dp2.segundo_apellido) nombre_destinatario
                    FROM datos_personales dp1, datos_personales dp2, mensajes m
                    WHERE m.id_remitente = dp1.id_datos_personales
                      and m.id_destinatario = dp2.id_datos_personales  
                    ORDER BY m.id_mensaje desc
                    $limitext
                    OFFSET $offset
                    
select;
    }

    $resultSet = $query->select();

    if ($resultSet) {
        return $resultSet;
    } else {
        return NULL;
    }
}

/**
 * Devuelve
 * @param type $idRemitente
 * @param type $idDestinatario
 * @param type $limit
 * @return string
 */
function cantidadPaginas($idRemitente = NULL, $idDestinatario = NULL, $limit = 10) {

    $mensajes = consultarMensajes($idRemitente, $idDestinatario);
    if ($mensajes) {
        $nMensajes = count($mensajes);
        $paginas = $nMensajes / $limit;
        $testRound = round($paginas);
        
        if($paginas > $testRound){
            $paginas = round($paginas) + 1;
        }
        if ($paginas == 0) {
            $paginas++;
        }
        return $paginas;
    } else {
        return "";
    }
}

?>