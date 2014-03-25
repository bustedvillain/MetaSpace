<?php
//   Date             Modified by         Change(s)
//   2013-09-24         HMP                 1.1

//if (!file_exists("Conexion.inc"))
//	die("<p>El archivo <code><b>Conexion.inc</b></code> no existe en el directorio ra&iacute;z.<br/></p>");
//if (!file_exists("Error.inc"))
//	die("<p>El archivo <code><b>Error.inc</b></code> no existe en el directorio ra&iacute;z.<br/></p>");

require_once 'Conexion.inc';
require_once 'Error.inc';

class Query
{
	public   $sql;
        private  $conexion;
        private  $idConexion;
        private  $idQuery;#representa lo que devuelve el Query
        private  $arregloObj;
	private  $arregloArr;
        private  $ultimoId;
        
        /**
         * Construye la conexión y crea el objeto
         * @param type $tipoBD
         */
	function __construct($tipoBD)
	{
            $this->conexion = new Conexion($tipoBD);
            $this->idConexion = $this->conexion->getIdConexion();
                    
	}

        /**
         * Ejecuta la consulta
         * @param type $tipo "obj"  lo devuelve como objeto, "arr" lo devuelve como array
         * @return null
         */
	function select($tipo = "obj")
	{
            if(!empty($this->sql))
	    {
                unset($this->idQuery,$this->arregloObj,$this->arregloArr);
                $this->idQuery = pg_query($this->idConexion, $this->sql)
                or die(Error::error_pgsql(pg_errormessage(),__FILE__,__LINE__,__CLASS__,__FUNCTION__,__METHOD__,$_SERVER['PHP_SELF'],$this->sql));
                
                if($this->numRegistros()>0)
                {
                    if(strcmp($tipo,"obj")==0)
                    {
                        $this->arregloObj = array();
                        while($row = pg_fetch_object($this->idQuery))
                        {
                            $this->arregloObj[] = $row;
                        }
                        return $this->arregloObj;
                    }
                    else if(strcmp($tipo,"arr")==0)
                    {
                        $this->arregloArr = array();
                        //$this->arregloArr = pg_fetch_array($this->idQuery,5, PGSQL_BOTH);
                        while($row = pg_fetch_array($this->idQuery,NULL, PGSQL_BOTH))
                        {
                            $this->arregloArr[] = $row;
                        }
                        
                        return $this->arregloArr;
                    }
                    else
                    {
                        die("<h3>ERROR: No me has dicho como quieres los registros?.</h3>");
                    }
                }
                else
                {
                    return NULL;
                }
	    }
	    else
	    {
                die("<h3>ERROR: No has especificado un query &quot;SELECT&quot; v&oacute;lido.</h3>");
	    }
	}
        
        /**
         * Ejecuta el update que se manda en el parámetro sql
         * @param type $sql
         * @return boolean
         */
	function update($sql = NULL)
	{
                if(!empty($sql))
		{
                        //echo "<code>$sql</code>";
			unset($this->sql,$this->idQuery);
			$this->idQuery = pg_query($this->idConexion,$sql)
                        or die(Error::error_pgsql(pg_errormessage(),__FILE__,__LINE__,__CLASS__,__FUNCTION__,__METHOD__,$_SERVER['PHP_SELF'],$this->sql));	
			return TRUE;
		}
		else
		{
			exit("<p>ERROR: No has especificado un query &quot;UPDATE&quot; v&aacute;lido.</p>");
		}
        }
        
        /**
         * Ejecuta un insert
         * @param type $tabla la tabla en la que se quiere insertar
         * @param type $campos los campos separados por comas
         * @param type $values los valores a insertar separados por comas
         */
	function insert($tabla = NULL, $campos = NULL, $values = NULL)
	{
                if(!empty($tabla) || !empty($campos) || !empty($values))
		{
			unset($this->sql);
			$this->sql = "INSERT INTO $tabla ($campos) VALUES ($values) ";
//                        imprimeConsola($this->sql);
                        $this->idQuery = pg_query($this->idConexion, $this->sql)
                        or die(Error::error_pgsql(pg_errormessage(),__FILE__,__LINE__,__CLASS__,__FUNCTION__,__METHOD__,$_SERVER['PHP_SELF'],$this->sql));
		}
		else
		{
			exit("<p>ERROR: No has especificado un query &quot;INSERT&quot; v&aacute;lido.</p>");
		}
	}
        
        
       
        
        /**
         * Función que ejecuta un delete 
         * @param type $tabla tabla donde se aplica
         * @param type $where condición
         * @return boolean
         */
	function delete($tabla = NULL, $where = NULL)
	{
                if(!empty($tabla) || !empty($where))
		{
			unset($this->sql,$this->idQuery);
                        $this->sql = "DELETE FROM $tabla WHERE $where";
                        $this->idQuery = pg_query($this->idConexion, $this->sql)
                        or die(Error::error_pgsql(pg_errormessage(),__FILE__,__LINE__,__CLASS__,__FUNCTION__,__METHOD__,$_SERVER['PHP_SELF'],$this->sql));
                            
                        //Falta optimizar
                        return TRUE;
		}
		else
		{
			exit("<p>ERROR: No has especificado un query &quot;DELETE&quot; v&aacute;lido.</p>");
		}
	}
        /**
         * Funcion que devuelve el Ultimo ID de la tabla
         * @param type $tabla
         * @see Esta funcion funciona correctamente si la tabla fue creada con  un ID serial
         * ya que el nombre de la secuencia sera nombreTabla_campoID_seq, 
         * Tambien se requiere que el campo ID de la tabla sea el primero en orden VER linea 140
         * de Query.php
         * Retorna null si la tabla esta 
        */
        function ultimoID($tabla)
        {
            $this->sql = "SELECT * FROM ".$tabla." LIMIT 1";
            $this->select("arr");
            $campoId = pg_field_name($this->idQuery, 0) or die("Error"); 
            
            if($campoId)
            {
                $this->sql = "SELECT currval('".$tabla."_".$campoId."_seq')";
                $resultado  = $this->select("arr");
                
                if($resultado)
                {
                    return $resultado[0]['currval'];
                }
        
            }
            
            return NULL;
        }
        /**
         * Obtiene el siguiente id 
         * @param type $tabla tabla en la que aplica
         * @return null || diguienteId
         */
        function siguienteID($tabla)
        {
            $this->sql = "SELECT * FROM ".$tabla." LIMIT 1";
            $this->select("arr");
            $campoId = pg_field_name($this->idQuery, 0) or die("Error"); 
            
            if($campoId)
            {
                $this->sql = "SELECT  (last_value) as sig FROM ".$tabla."_".$campoId."_seq";
                $resultado  = $this->select("arr");
                
                if($resultado)
                {
                    return $resultado[0]['sig'];
                }
        
            }
            
            return NULL;
        }


	#Devuielve numero de campos obtenidos de un select
	function numCampos()
	{
            return ($this->idQuery)? pg_num_fields($this->idQuery):0;
	}

	#Retorna el numero de tuplas modificadas en INSERT, UPDATE, and DELETE queries. 
	function camposAfectados()
	{
            return ($this->idQuery)?pg_affected_rows($this->idQuery):0;
	}

	#Devuelve el numero de registros obtenidos de un SELECT
	function numRegistros()
	{
            return ($this->idQuery)? pg_num_rows($this->idQuery):0;
	}
        /**
         * Aplica un flush
         * @return type
         */
	function flush()
	{
           if($this->idQuery)
	   {
	       pg_free_result($this->idQuery);
	       return;
	   }
	 
	}
        #Devuelve en un array todos los campos devueltos por un select
        function camposSelect()
        {
            if($this->idQuery)
            {
                $arrayCampos= array();
                
                $i = pg_num_fields($this->idQuery);
                for ($j = 0; $j < $i; $j++) {
                    $fieldname = pg_field_name($this->idQuery, $j);
                    array_push($arrayCampos, $fieldname);
                }
                
                return $arrayCampos;
            }
            return NULL;
        }
}



?>
