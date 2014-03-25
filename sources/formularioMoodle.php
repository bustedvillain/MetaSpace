<?php require 'Funciones.php';?>
<html>
    <body>
        <form name="enviar" action="<?php echo RUTA_MOODLE;?>" method ="post">
            <input type="hidden" name="username" value="<?php echo $_SESSION["userMail"];?>">
            <input type="hidden" name="password" value="<?php echo $_SESSION["pass"];?>">
        </form>
        <script type="text/javascript">
        document.enviar.submit();
        </script>
    </body>
</html>
