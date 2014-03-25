<?php include '../../sources/Funciones.php'; 
verificaSesionTutor();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Biblioteca Tutor | META Space</title>
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


</script>



</head>



<body id="tutor">

	<div id="container">
    	<div id="header_tutor">
        <div id="nombre" class="fondo_azul">
        	<h3><span>Tutor:</span><?php nombreLogout(); ?></h3>
        </div>
            
            <div id="logo"><a href="index.php"><img src="../img/logo_meta.gif" width="347" height="72" alt="Ir a inicio de Meta Space" /></a></div>
            <div class="separador_tutor"></div>
            <div id="buscador">
            <form id="" name=""  action="" enctype="multipart/form-data" method="post">
            	<div id="buscador_tutor"><input name="buscador" size="35" maxlength="35" type="text" id="Buscador" placeholder="Buscar..."></div>
                <div id="lupa"><input name="submit" class="btn_envio" id="btn_envio" value="" type="submit"></div>
            </form>
            
          </div>
          <div class="separador_tutor"></div>
          <div class="btn_perfil"><a href="perfil.php"><img src="../img/tutor/icono_perfil.png" width="55" height="30" />mi perfil</a></div>
          <div class="separador_tutor"></div>
          <div class="btn_perfil"><a href="como_navegar.php"><img src="../img/tutor/icono_navegar.png" width="55" height="30" />como navegar</a></div>
          <div class="separador_tutor"></div>
          <div class="btn_perfil"><a href="grupos.php"><img src="../img/tutor/icono_grupo.png" width="55" height="30" />grupos</a></div>
        <div id="foto_perfil" class="fondo_azul"><img src="<?php echo rutaFotoDeSesion(); ?>" width="77" height="78" class="margin10" /></div>
            </div>
            
            <div id="central">
            	
   		  <div id="lateral_nav">
                	<div id="lateral_nav_cursos"><a title="" href="cursos.php"></a></div>
            		<div id="lateral_nav_reportes"><a title="" href="reportes.php"></a></div>
            		<div id="lateral_nav_comunicacion"><a title="" href="comunicacion.php"></a></div>
                    <div id="lateral_nav_tutor_biblio"><span></span></div>
            		
                
       	      </div>
              
             <div id="biblioteca">
             <div id="nav_biblio_tutor">
                	<ul>
                    	<li><a href="">Todo</a></li>
                        <li><a href="">Libros  (5)</a></li>
                        <li><a href="">Revistas  (0)</a></li>
                        <li><a href="">Periódicos (0)</a></li>
                        <li><a href="">Libros de Texto  (5)</a></li>
                        <li><a href="">Apps</a></li>
                    </ul>
                </div> 
                <div id="destacados_biblio">
   	  <div class="libro_a">
      	<div class="libro_enlace"><a href="sinopsis.php"></a></div>
                    	<div class="libro_sup" style="color: #04b7f9;">Destacado</div>
                        <div class="libro_a_foto"><img src="../img/alumno/biblioteca/libro_ejemplo.gif" width="141" height="128" /></div>
                        <div class="libro_a_titulo" style="background-color: #04b7f9;"><h3><a href="sinopsis.php">The House of Loto (Special Edition)...</a></h3></div>
                        <div class="libro_autor">de <strong>Rick Jordan</strong></div>
                        <div class="libro_inf">
                        	<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                        </div>
                    </div>
                    <div class="libro_a">
      	<div class="libro_enlace"><a href="sinopsis.php"></a></div>
                    	<div class="libro_sup" style="color: #04b7f9;">Destacado</div>
                        <div class="libro_a_foto"><img src="../img/alumno/biblioteca/libro_ejemplo.gif" width="141" height="128" /></div>
                        <div class="libro_a_titulo" style="background-color: #04b7f9;"><h3><a href="sinopsis.php">Música para niños y padres (2013)...</a></h3></div>
                        <div class="libro_autor">de <strong>Pepe Fuentes</strong></div>
                        <div class="libro_inf">
                        	<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                        </div>
                    </div>
                </div> 
                
                <div id="carrusel_biblio">
                <div class="libro_b">
      	<div class="libro_enlace"><a href="sinopsis.php"></a></div>
                    	<div class="libro_sup"></div>
                        <div class="libro_b_foto"><img src="../img/alumno/biblioteca/libro_ejemplo.gif" width="141" height="128" /></div>
                        <div class="libro_b_titulo"><h3><a href="sinopsis.php">El pirata matemático (2013) </a></h3></div>
                        <div class="libro_autor">de <strong>Jack Sholder</strong></div>
                        <div class="libro_inf">
                        	<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                        </div>
                    </div>
                    
                    <div class="libro_b">
      	<div class="libro_enlace"><a href="sinopsis.php"></a></div>
                    	<div class="libro_sup"></div>
                        <div class="libro_b_foto"><img src="../img/alumno/biblioteca/libro_ejemplo.gif" width="141" height="128" /></div>
                        <div class="libro_b_titulo"><h3><a href="sinopsis.php">Peter Pan (versión con comentarios) </a></h3></div>
                        <div class="libro_autor">de <strong>James M. Barrie</strong></div>
                        <div class="libro_inf">
                        	<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                        </div>
                    </div>
                    
                    <div class="libro_b">
      	<div class="libro_enlace"><a href="sinopsis.php"></a></div>
                    	<div class="libro_sup"></div>
                        <div class="libro_b_foto"><img src="../img/alumno/biblioteca/libro_ejemplo.gif" width="141" height="128" /></div>
                        <div class="libro_b_titulo"><h3><a href="sinopsis.php">Alicia en el País de las Maravillas</a></h3></div>
                        <div class="libro_autor">de <strong>Lewis Carrol</strong></div>
                        <div class="libro_inf">
                        	<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                        </div>
                    </div>
                    <div class="libro_b">
      	<div class="libro_enlace"><a href="sinopsis.php"></a></div>
                    	<div class="libro_sup"></div>
                        <div class="libro_b_foto"><img src="../img/alumno/biblioteca/libro_ejemplo.gif" width="141" height="128" /></div>
                        <div class="libro_b_titulo"><h3><a href="sinopsis.php">El pirata matemático (2013) </a></h3></div>
                        <div class="libro_autor">de <strong>Rick Jordan</strong></div>
                        <div class="libro_inf">
                        	<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                        </div>
                    </div>
                
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
        	<div id="social"><img src="../img/tutor/social.gif" width="117" height="50" /></div>
      </div>
    </div>
            

	
    

   	
<?php include_once '../../sources/ConfiguracionesGlobalesFW.php'; ?>
</body>
</html>
