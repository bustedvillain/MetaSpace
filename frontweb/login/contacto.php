 <?php require_once '../../sources/Funciones.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contacto | META Space</title>
<meta name="lang" content="es" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<link rel="shortcut icon" href="/favicon.ico" />

<link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
<link href="../style/intro.css" type="text/css" rel="stylesheet"  media="all" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>
<link href="http://fonts.googleapis.com/css?family=Dosis:400,300,500" rel="stylesheet" type="text/css"/>


</head>


<body>
	<div id="intro_container">
    	<div id="intro_titulo"><h2>Espacio Dígital para el Aprendizaje Autónomo Meta Space</h2></div>
    <div id="intro_logo">
        	<div id="intro_nav">
            <div id="intro_nav_nosotros"><a title="Nosotros" href="nosotros.html"></a></div>
            <div id="intro_nav_servicios"><a title="Servicios" href="servicios.html"></a></div>
            <div id="intro_nav_contacto"><span></span></div>
            <div id="intro_nav_login"><a title="Login" href="index.php"></a></div> 
            
        </div>
      </div>
        
        <div id="intro_general">
        	<h3 class="color_azul">Contacto</h3>
            <p>Si deseas ponerte en contacto con nosotros, por favor, rellena el formulario</p>
            <div id="intro_contacto_form">
           
                <form id="frmContact" class="login" method="post" action="../../sources/ControladorContacto.php">

 


<div class="form"><input name="nombre" size="35" maxlength="50" type="text" id="ContactoNombre" placeholder="Nombre..." /></div>

<div class="form"><input name="correo" size="35" maxlength="100" type="text" id="ContactoCorreo" placeholder="Correo electrónico..." /></div>
<div class="form_largo"><textarea name="mensaje" cols="50" rows="3" wrap="soft" id="LoginMensaje" placeholder="Mensaje..." /></textarea></div>
<div class="elem_right"><input name="submit" class="btn_envio" value="Enviar" style="background-color:#04b7f9;" type="submit"></div>

                
                
                </form>
                
            </div>
            <br/>
            <br/>
            <br/>
            <?php if(isset($_GET["msg"])) mensaje($_GET['msg']);?>
           
        	
        </div>
         
        <div id="footer">
       	  <div id="legal">
          	<ul>
            	<li><a href="">Derechos en trámite</a></li>
                <li><a href="">Aviso de privacidad</a></li>
            </ul>
          </div>
        	<div id="social"><img src="../img/social.gif" width="117" height="50" /></div>
      </div>
    </div>
</body>
</html>
