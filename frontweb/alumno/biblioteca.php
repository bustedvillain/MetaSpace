<?php include '../../sources/Funciones.php'; verificarSesionAlumno();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Biblioteca | META Space</title>
<meta name="lang" content="es" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<link rel="shortcut icon" href="/favicon.ico" />

<link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
<link href="../style/general.css" type="text/css" rel="stylesheet"  media="all" />

<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Dosis:400,300,500' rel='stylesheet' type='text/css'>


</head>



<body>
  <div id="container">
    	<div id="header">
        <div id="nombre" class="fondo_naranja">
        	<h3><span>Alumno: </span><?php nombreLogout(); ?></h3>
        </div>
            
            <div id="logo"><a href="index.php"><img src="../img/logo_meta.gif" width="347" height="72" alt="Ir a inicio de Meta Space" /></a></div>
            <div class="separador"></div>
            <div id="buscador">
            <form id="" name=""  action="" enctype="multipart/form-data" method="post">
            	<div id="buscador_alu"><input name="buscador" size="35" maxlength="35" type="text" id="Buscador" placeholder="Buscar..."></div>
                <div id="lupa"><input name="submit" class="btn_envio" id="btn_envio" value="" type="submit"></div>
            </form>
            
          </div>
          <div class="separador"></div>
          <div class="btn_perfil"><a href="perfil.php"><img src="../img/alumno/icono_perfil.png" width="55" height="30" />mi perfil</a></div>
          <div class="separador"></div>
          <div class="btn_perfil"><a href="como_navegar.php"><img src="../img/alumno/icono_navegar.png" width="55" height="30" />como navegar</a></div>
          <div class="separador"></div>
          <div class="btn_perfil"><a href="amigos.php"><img src="../img/alumno/icono_amigos.png" width="55" height="30" />amigos</a></div>
        <div id="foto_perfil" class="fondo_naranja"><img src="<?php echo rutaFotoDeSesion(); ?>" width="77" height="78" class="margin10" /></div>
        
            </div>
    
            <div id="central">
            	
   		  <div id="lateral_nav">
                	<div id="lateral_nav_aprender"><a title="" href="index.php"></a></div>
                    <div id="lateral_nav_baul"><a title="" href="baul.php"></a></div>
            		<div id="lateral_nav_mi_locker"><a title="" href="mi_locker.php"></a></div>
                    <div id="lateral_nav_biblioteca"><span></span></div>
            		
                
       	      </div>
              
             <div id="biblioteca">
             <div id="nav_biblio">
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
                    	<div class="libro_sup">Destacado</div>
                        <div class="libro_a_foto"><img src="../img/alumno/biblioteca/libro_ejemplo.gif" width="141" height="128" /></div>
                        <div class="libro_a_titulo"><h3><a href="sinopsis.php">The House of Loto (Special Edition)...</a></h3></div>
                        <div class="libro_autor">de <strong>Rick Jordan</strong></div>
                        <div class="libro_inf">
                        	<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                    		<div class="estrellax"><a></a></div>
                        </div>
                    </div>
                    <div class="libro_a">
      	<div class="libro_enlace"><a href="sinopsis.php"></a></div>
                    	<div class="libro_sup">Destacado</div>
                        <div class="libro_a_foto"><img src="../img/alumno/biblioteca/libro_rojo.gif" width="141" height="128" /></div>
                        <div class="libro_a_titulo"><h3><a href="sinopsis.php">Música para niños y padres (2013)...</a></h3></div>
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
                        <div class="libro_b_foto"><img src="../img/alumno/biblioteca/libro_gris.gif" width="141" height="128" /></div>
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
                        <div class="libro_b_foto"><img src="../img/alumno/biblioteca/libro_verde.gif" width="141" height="128" /></div>
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
                        <div class="libro_b_foto"><img src="../img/alumno/biblioteca/libro_allo.gif" width="141" height="128" /></div>
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
                        <div class="libro_b_foto"><img src="../img/alumno/biblioteca/libro_morado.gif" width="141" height="128" /></div>
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
        	<div id="social"><img src="../img/social.gif" width="117" height="50" /></div>
      </div>
        
 
         	    	
    
    </div>

	
    

   	
<?php include_once '../../sources/ConfiguracionesGlobalesFW.php'; ?>
</body>
</html>
