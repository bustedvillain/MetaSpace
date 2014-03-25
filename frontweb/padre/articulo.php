<?php include '../../sources/Funciones.php'; 
verificarSesionPadre();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Artículo educativo Padre | META Space</title>
<meta name="lang" content="es" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<link rel="shortcut icon" href="/favicon.ico" />

<link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
<link href="../style/general.css" type="text/css" rel="stylesheet"  media="all" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Dosis:400,300,500' rel='stylesheet' type='text/css'>

<script language="JavaScript">

function muestra_oculta_letras(id){
if (document.getElementById){ //se obtiene el id
var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
}
}
window.onload = function(){/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
muestra_oculta_letras('contenido_a_mostrar_letras');/* "contenido_a_mostrar" es el nombre que le dimos al DIV */
}

function muestra_oculta_mates(id){
if (document.getElementById){ //se obtiene el id
var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
}
}
window.onload = function(){/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
muestra_oculta_mates('contenido_a_mostrar_mates');/* "contenido_a_mostrar" es el nombre que le dimos al DIV */
}



function muestra_oculta_ciencias(id){
if (document.getElementById){ //se obtiene el id
var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
}
}
window.onload = function(){/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
muestra_oculta_ciencias('contenido_a_mostrar_ciencias');/* "contenido_a_mostrar" es el nombre que le dimos al DIV */
}

function muestra_oculta_artes(id){
if (document.getElementById){ //se obtiene el id
var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
}
}
window.onload = function(){/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
muestra_oculta_artes('contenido_a_mostrar_artes');/* "contenido_a_mostrar" es el nombre que le dimos al DIV */
}

// Función para mostrar la lista desplegable de tutores
function muestra_oculta(idDiv){
	alert(document.getElementById(idDiv));
	if(document.getElementById){
		var el = document.getElementById(idDiv); //Se define la variable "el" igual a nuestro div	
		/*alert(el.style.display);
		if (el.style.display == 'none'){
			alert(el);
				el.style.display = 'block';
		}*/
		el.style.display = (el.style.display == 'none' ) ? 'block' : 'none'; //damos un atributo display:none que oculta el div
	}
}
</script>



</head>



<body id="padre">

  <div id="container">
    	<div id="header_padre">
        <div id="nombre" class="fondo_verde">
        	<h3><span>Padre: </span><?php nombreLogout(); ?></h3>
        </div>
            
            <div id="logo"><a href="index.php"><img src="../img/logo_meta.gif" width="347" height="72" alt="Ir a inicio de Meta Space" /></a></div>
            <div class="separador_padre"></div>
            <div id="buscador">
            <form id="" name=""  action="" enctype="multipart/form-data" method="post">
            	<div id="buscador_padre"><input name="buscador" size="35" maxlength="35" type="text" id="Buscador" placeholder="Buscar..."></div>
                <div id="lupa"><input name="submit" class="btn_envio" id="btn_envio" value="" type="submit"></div>
            </form>
            
          </div>
          <div class="separador_padre"></div>
          <div class="btn_perfil"><a href="perfil.php"><img src="../img/padre/icono_perfil.png" width="55" height="30" />mi perfil</a></div>
          <div class="separador_padre"></div>
          <div class="btn_perfil"><a href="como_navegar.php"><img src="../img/padre/icono_navegar.png" width="55" height="30" />como navegar</a></div>
          <div class="separador_padre"></div>
          <div class="btn_perfil"><a href="reportes.php"><img src="../img/padre/icono_reporte.png" width="55" height="30" />Reportes</a></div>
        <div id="foto_perfil" class="fondo_verde"><img src="<?php echo rutaFotoDeSesion(); ?>" width="77" height="78" class="margin10" /></div>
            </div>
            
            <div id="central">
            	
   		  <div id="lateral_nav">
                	<div id="lateral_pad_familia"><a title="" href="index.php"></a></div>
                	<div id="lateral_pad_educacion"><span></span></div>
                    <div id="lateral_pad_comunicacion"><a title="" href="comunicacion.php"></a></div>
                    <div id="lateral_pad_biblioteca"><a title="" href="biblioteca.php"></a></div>
       	      </div>
              
             <div id="alumno_sinopsis">
             	<h2>Artículo</h2>
                <div class="contenido">
                	<div class="foto_libro_lateral"><img src="../img/padre/maestra.gif" width="141" height="128" class="margin20l margin20t" /></div>
                    
                    <div class="libro_txt"><h1>EPN y la reforma educativa</h1> 
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <p> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                   
                    </div>
                	
                </div>
                <div id="padre_btn">
                    <div id="padre_btn_regresar"><a href="javascript:history.back()"> </a></div>
                </div>
             </div>
              
              
                 
      </div>
    
    
    
    <div id="footer">
       	  <div id="legal">
          	<ul>
            	<li><a href="">Derechos en trámite</a></li>
                <li><a href="">Aviso de privacidad</a></li>
            </ul>
          </div>
        	<div id="social"><img src="../img/padre/social.gif" width="117" height="50" /></div>
      </div>
    </div>
            

	
    

   	
<?php include_once '../../sources/ConfiguracionesGlobalesFW.php'; ?>
</body>
</html>
