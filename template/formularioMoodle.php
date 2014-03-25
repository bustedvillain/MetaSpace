<form name="formMoodle" action="<?php echo RUTA_MOODLE;?>" method ="post" target="_blank">
    <input type="hidden" name="username" value="<?php echo $_SESSION["userMail"];?>">
    <input type="hidden" name="password" value="<?php echo $_SESSION["pass"];?>">
</form>