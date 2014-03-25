<?php include '../../sources/Funciones.php'; 
verificarSesionPadre();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Vista Padre | META Space</title>
<meta name="lang" content="es" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<link rel="shortcut icon" href="/favicon.ico" />

<link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
<link href="../style/general.css" type="text/css" rel="stylesheet"  media="all" />

<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Dosis:400,300,500' rel='stylesheet' type='text/css'>


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
                	<div id="lateral_pad_familia"><span></span></div>
                	<div id="lateral_pad_educacion"><a title="" href="educacion.php"></a></div>
                    <div id="lateral_pad_comunicacion"><a title="" href="comunicacion.php"></a></div>
                    <div id="lateral_pad_biblioteca"><a title="" href="biblioteca.php"></a></div>
                    <div id="lateral_pad_hijo"><p>Juan</p></div>
       	      </div>
              
             <div id="baul">
             <div id="reporte_nav">
                <ul>
                	<li><span>Bloque 1</span></li>
                    <li><a href="">Bloque 2</a></li>
                    <li><a href="">Bloque 3</a></li>
                    <li><a href="">Bloque 4</a></li>
                    <li><a href="">Bloque 5</a></li>
                    <li><a href="">Bloque 6</a></li>
                </ul>
                </div>
                <div id="baul_tab">
               	  <div id="baul_letras">
                    <div class="puntuacion_baul">
                    	<div class="estrella"><a></a></div>
                    	<div class="estrella"><a></a></div>
                    	<div class="estrella"><a></a></div>
                    </div>
                    	<div class="item_on"><img src="../img/padre/reporte/a.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/b.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/c.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/d.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/d.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/d.png" width="98" height="98" /></div>
                        
                    </div>
                    
                    <div id="baul_mates">
                    <div class="puntuacion_baul">
                    	<div class="estrella"><a></a></div>
                    	<div class="estrella"><a></a></div>
                    	<div class="estrella"><a></a></div>
                    </div>
                    	<div class="item_on"><img src="../img/padre/reporte/a.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/b.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/c.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/d.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/d.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/d.png" width="98" height="98" /></div>
                        
                    </div>
                    <div id="baul_ciencias">
                    <div class="puntuacion_baul">
                    	<div class="estrella"><a></a></div>
                    	<div class="estrella"><a></a></div>
                    	<div class="estrella"><a></a></div>
                    </div>
                    	<div class="item_on"><img src="../img/padre/reporte/a.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/b.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/c.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/d.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/d.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/d.png" width="98" height="98" /></div>
                        
                    </div>
                    
                    <div id="baul_artes">
                    <div class="puntuacion_baul">
                    	<div class="estrella"><a></a></div>
                    	<div class="estrella"><a></a></div>
                    	<div class="estrella"><a></a></div>
                    </div>
                    	<div class="item_on"><img src="../img/padre/reporte/a.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/b.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/c.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/d.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/d.png" width="98" height="98" /></div>
                        <div class="item_on"><img src="../img/padre/reporte/d.png" width="98" height="98" /></div>
                        
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
