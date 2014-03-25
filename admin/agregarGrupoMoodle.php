<?php include("../sources/Funciones.php");
?>
<!DOCTYPE html>
<!--
FECHA DE CREACION: 16 DE ENERO DEL 2014
AUTOR: JOSE MANUEL NIETO GOMEZ
OBJETIVO: ENVIA FORMULARIO PARA AGREGAR USUARIOS EN UN GRUPO DENTRO DE UN CURSO
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $groupid = $_GET["groupid"];        
        $url_moodle = IP_SERVER_PUBLIC . "moodle/group/members.php?group=$groupid";
        $userids = explode(",", $_GET["userid"]);
        echo <<<POST
                <form action="$url_moodle" method="post" name="inscribir">
                    <input type='hidden' name='removeselect_searchtext' value=''/>
                    <input type='hidden' name='userselector_preserveselected' value='0'/>
                    <input type='hidden' name='userselector_autoselectunique' value='0'/>
                    <input type='hidden' name='userselector_searchanywhere' value='0'/>
                    <input type='hidden' name='add' value='◄ Agregar'/>                    
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
