<?php include("../sources/Funciones.php");
?>
<!DOCTYPE html>
<!--
FECHA DE CREACION: 14 DE ENERO DEL 2014
AUTOR: JOSE MANUEL NIETO GOMEZ
OBJETIVO: ENVIA FORMULARIO PARA ENROLAR USUARIOS EN MOODLE
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $enrolid = $_GET["enrolid"];
        $url_moodle = IP_SERVER_PUBLIC . "moodle/enrol/manual/manage.php?enrolid=$enrolid";
        $rolId = $_GET["rolid"];
        $userids = explode(",", $_GET["userid"]);
        echo <<<POST
                <form action="$url_moodle" method="post" name="inscribir">
                    <input type='hidden' name='removeselect_searchtext' value=''/>
                    <input type='hidden' name='userselector_preserveselected' value='0'/>
                    <input type='hidden' name='userselector_autoselectunique' value='0'/>
                    <input type='hidden' name='userselector_searchanywhere' value='0'/>
                    <input type='hidden' name='add' value='◄ Agregar'/>
                    <input type='hidden' name='roleid' value='$rolId'/>
                    <input type='hidden' name='extendperiod' value=''/>
                    <input type='hidden' name='extendbase' value='3'/>
                    <input type='hidden' name='addselect_searchtext' value=''/>
POST;
        foreach ($userids as $userid) {
            if ($userid != "") {
                echo "<input type='hidden' name='addselect[]' value='$userid'/>";
            }
        }

        echo <<<POST
                </form>
                <script language="JavaScript">
                    document.inscribir.submit();
                </script>
POST;
        ?>

    </body>
</html>
