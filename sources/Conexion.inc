<?php
//   Date             Modified by         Change(s)
//   2013-09-24         HMP                 1.1



require_once ("Config.php");
require_once 'Error.inc';
class Conexion
{
    private $id = null;
    /**
     * Construcción del objeto Conexión
     * @param type $tipoBD    MOD = Moodle
     *                        SG  = SIAA
     */
    function __construct($tipoBD = "")
    {
        $URL="";
        if(strcmp($tipoBD,"MOD")==0)
        {
           $URL="host=".HOST_MOD." port=".PORT_MOD." dbname=".DB_NAME_MOD." user=".USER_MOD." password=".PASS_MOD;
        }
        elseif (strcmp($tipoBD,"SG")==0) 
        {
            $URL="host=".HOST_SG." port=".PORT_SG." dbname=".DB_NAME_SG." user=".USER_SG." password=".PASS_SG;
        }
        $this->id = pg_connect($URL);
        if (!$this->id) 
              (Error::error_pgsql(pg_errormessage(),__FILE__,__LINE__,__CLASS__,__FUNCTION__,__METHOD__,$_SERVER['PHP_SELF'],"Error al Conectar a la Base de Datos"));

    }
     /**
      * Destrucción del objeto Conexión
      * @return type
      */
     function __destruct()
     {
		if($this->id)
		{
                    return @pg_close($this->id);
		}
     }
     /**
      * Obtner ID Conexión
      * @return type
      */
     function getIdConexion()
     {
            return $this->id;
     }

}


?>
