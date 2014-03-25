<?php include("../sources/Funciones.php");
?>
<!DOCTYPE html>
<!--
FECHA DE CREACION: 16 DE ENERO DEL 2014
AUTOR: JOSE MANUEL NIETO GOMEZ
OBJETIVO: ENVIA FORMULARIO PARA ASIGNAR ADMINISTRADOR DE MOODLE
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $url_moodle = IP_SERVER_PUBLIC . "moodle/admin/roles/admins.php";
        $userid = $_GET["userid"];
        echo <<<POST
                <form action="$url_moodle" method="post" name="inscribir">
                    <input type='hidden' name='confirmadd' value='$userid'/>
                </form>
                <script language="JavaScript">
                    document.inscribir.submit();
                </script>
POST;
        ?>

    </body>
</html>
