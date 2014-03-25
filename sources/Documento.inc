


<?php

/*
 * 1 verificar error
 * 2 verificar extension
 * 3 verificar tama�o 
 * 4 cambiar nombre
 * 5 copiar archivo
  $_FILES["imagen"]["error"];# 1 al 6
  $_FILES["imagen"]["tmp_name"]; #c:/temp/php01ax.jpg
  $_FILES["imagen"]["name"]; #micky mouse.jpg
  $_FILES["imagen"]["size"]; #12324 = 12Kb
  $_FILES["imagen"]["type"];# image/jpg
 */

class Documento {

    public $archivo;
    public $error;
    public $nombre;
    public $tamano;
    public $tipo;
    public $destino;
    public $titulo;
    private $extension;

    function __construct() {
        
    }

    function __destruct() {
        
    }

    function verificaerror() {
        if ($this->error > 0) {
            exit("<center><h5 id='errorLogin'>ERROR Al subir el archivo</h5></center>");
        } else {
            return TRUE;
        }
    }

    function verificarExtension() {
        global $extensionesPermitidas;
        if (in_array(end(explode(".", $this->nombre)), $extensionesPermitidas)) {
            $this->extension = end(explode(".", strtolower($this->nombre)));
            return TRUE;
        } else {
            exit("<center><h5 id='errorLogin'>ERROR. Tipo de archivo no v&aacute;lido, s&oacute;lo se permiten extensiones: " . implode(", ", $extensionesPermitidas) . "</h5></center>");
        }
    }

    function cambiarNombre() {
        $acento = array("&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&ntilde;", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&Ntilde;");
        $sinacento = array('a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N');
        $prefijo = substr(md5(uniqid(rand())), 0, 5);

        $this->titulo = str_replace($acento, $sinacento, $this->titulo);
        $this->titulo = preg_replace("/[^a-z0-9-_.]/i", "", $this->titulo);

        $this->nombre = preg_replace("/[^a-z0-9-_.]/i", "", $this->nombre);
        $this->nombre = str_replace($this->nombre, "_", $this->nombre);
        $this->nombre = str_replace("_", $this->titulo . "." . $this->extension, $this->nombre);
        $this->nombre = strtolower($this->nombre);
        $this->nombre = str_replace($acento, $sinacento, $this->nombre);
        $this->nombre = str_replace(" ", "_", $this->nombre);
        $this->nombre = $prefijo . "_" . $this->nombre;
        return $this->nombre;
    }

    function copia() {
//        echo $this->destino . "/" . $this->nombre;
        if (move_uploaded_file($this->archivo, $this->destino . "/" . $this->nombre)) {
            unset($this->archivo);
            @unlink($this->archivo);
//            return TRUE;
            
            return $this->destino . "/" . $this->nombre;
        } else {
            exit("<center><h5 id='errorLogin'>ERROR. El archivo no puede ser copiado, intenta nuevamente.</h5></center>");
        }
    }

}
?>
		