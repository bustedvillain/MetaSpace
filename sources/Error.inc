<?php

//   Date             Modified by         Change(s)
//   2013-09-24         HMP                 1.0

class Error {
    #die(Error::error_mysql(mysqli_connect_errno(),mysqli_connect_error(),__FILE__,__LINE__,__CLASS__,__FUNCTION__,__METHOD__,$_SERVER['PHP_SELF'],$this->sql));

    static function error_pgsql($error, $archivo, $linea, $clase, $funcion, $metodo, $script, $sql) {
//        echo "<h1>Bueno, esto es vergonzoso.</h1>";
//        print ("<p>Ocurri&oacute; un error. Es todo lo que sabemos.</p>
//               <p>En estos momentos nuestros ingenieros trabajan para solucionarlo.</p>
//               <p><b>Lamentamos las molestias que esto te ocasiona, por favor ingresa nuevamente en 3 minutos.</b><p>");
//
//        $cuerpo = "<p>ERROR. " . $error . "</p>";
//        $cuerpo .= "<p>Error enviado por: " . $archivo . "</p>";
//        $cuerpo .= "<p>Linea: " . $linea . "</p>";
//        $cuerpo .= "<p>Clase: " . $clase . "</p>";
//        $cuerpo .= "<p>Funcion: " . $funcion . "</p>";
//        $cuerpo .= "<p>Metodo: " . $metodo . "</p>";
//        $cuerpo .= "<p>SQL: " . $sql . "</p>";
//        $cuerpo .= "<p>Error generado en: " . $script . "</p>";
//        //mail($de,$as,$cuerpo,$he);
//        echo $cuerpo;

        echo "<h1>Se gener&oacute; un error en el sistema</h1>";
        print ("<p>Anota este c&oacute;digo de error y ponte en contacto con el administrador del sistema.</p>");

        $cuerpo = "<p>ERROR. " . $error . "</p>";
        $cuerpo .= "<p>Error enviado por: " . $archivo . "</p>";
        $cuerpo .= "<p>Linea: " . $linea . "</p>";
        $cuerpo .= "<p>Clase: " . $clase . "</p>";
        $cuerpo .= "<p>Funcion: " . $funcion . "</p>";
        $cuerpo .= "<p>Metodo: " . $metodo . "</p>";
        $cuerpo .= "<p>SQL: " . $sql . "</p>";
        $cuerpo .= "<p>Error generado en: " . $script . "</p>";
        echo $cuerpo;
        exit();
    }

}

?>