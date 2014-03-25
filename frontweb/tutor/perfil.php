<?php 
//Archivo creado para control de cambios #7
include '../../sources/Funciones.php';
verificaSesionTutor();
if(navegadorActual() == "Internet Explorer"){
    $metodo = "muestraFileI();";
    $estilo = "none";
    $idForm = '';
    $idFile = '';
    $idCmd = '';
}else{
    $metodo = "muestraImagenUp();";
    $estilo = "none";
    $idForm = ' id="frmSubeImagen" ';
    $idFile = ' id="inputSubida" ';
    $idCmd = ' id="btnSube" ';
    
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Perfil Tutor | META Space</title>
<meta name="lang" content="es" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<link rel="shortcut icon" href="/favicon.ico" />

<link href="../style/reset.css" type="text/css" rel="stylesheet"  media="all" />
<link href="../style/general.css" type="text/css" rel="stylesheet"  media="all" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Dosis:400,300,500' rel='stylesheet' type='text/css'>

<script language="JavaScript">

// Función para mostrar la lista desplegable de tutores
function muestra_oculta(idDiv){
	if(document.getElementById){
		var el = document.getElementById(idDiv); //Se define la variable "el" igual a nuestro div			
		el.style.display = (el.style.display == 'none' ) ? 'block' : 'none'; //damos un atributo display:none que oculta el div
	}
}
</script>
<script type="text/javascript" src="../../js/jquery-1.7.min.js"></script>
        <script type="text/javascript">
            $(document).on("ready", function() {
                inicio();
            });
            function inicio() {
                $("#inputSubida").change(function() {
//                    alert('la subiré');
//                    $("#frmSubeImagen").submit();
                        $("#btnSube").trigger("click");
//                        window.location.href = window.location.href;
                });
            }


            function muestraImagenUp() {
                $("#tutor_btn_capturar").click(function() {
                    $("#inputSubida").trigger("click");
//                    debugConsole('hubo click');
                });
            }
            function muestraFileI() {

                $("#frmImagen").toggle();
            }
        </script>
</head>



<body id="tutor">

	<div id="container">
    	<div id="header_tutor">
        <div id="nombre" class="fondo_azul">
        	<h3><span>Tutor: &nbsp;</span><?php nombreLogout(); ?></h3>
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
            	<div id="col_extra">
                
                    <div id="mensajes">
                        <h4>Mensajes</h4>
                        <p>Nuevo curso para docentes en Edomex.</p>
                        <p>Soluciones para escuelas públicas y privadas.</p>
                        <p>Ampliado el plazo para presentar documentación.</p>
                    </div>
                    
                     <div id="chat">
                   	   <div id="chat_title">
                         <span id="chat_head">Chat en línea</span>
                         <span id="chat_greenBtn"><a href="#" onClick="muestra_oculta('chat_online')"></a></span>
                       </div>
                        
                       
                        <div id="chat_online" style="display:none;" class="chat_conectados">
                            <p>Martín Garzón - Padre</p>
                            <p>Mengan Sams - Tutor</p>
                        </div>
                        
                       <div id="chat_scroll">
                            <div>
                            <span>Pedro dice...</span>
                            <p>Bien, casi acabándolos, gracias compañero.</p></div>
                            <div><span>Antonio dice...</span>
    <p>¿Qué tal los exámenes?</p></div>
                            <div>
                            <span>Pedro dice...</span>
                            <p>Dos días por lo menos.</p></div>
                            <div>
                            <span>Antonio dice...</span>
                            <p>¿Llevan mucho tiempo?</p></div>
                             <div>
                            <span>Pedro dice...</span>
                            <p>Bien, casi acabándolos, gracias compañero.</p></div>
                            <div><span>Antonio dice...</span>
    <p>¿Qué tal los exámenes?</p></div>
                            <div>
                            <span>Pedro dice...</span>
                            <p>Dos días por lo menos.</p></div>
                            <div>
                            <span>Antonio dice...</span>
                            <p>¿Llevan mucho tiempo?</p></div>
                            
                            </div>
                            <div class="chat_input">
                                <input type="text" placeholder="Ingresa aquí tu texto" />
                            </div>
                    </div>
                    
                 </div>
                 
           		<div id="lateral_nav">
                	<div id="lateral_nav_cursos"><a title="" href="cursos.php"></a></div>
            		<div id="lateral_nav_reportes"><a title="" href="reportes.php"></a></div>
            		<div id="lateral_nav_comunicacion"><a title="" href="comunicacion.php"></a></div>
                    <div id="lateral_nav_tutor_biblio"><a title="" href="biblioteca.php"></a></div>
            		
                
       	      </div>
              
              
              
              
              
              
              
              <div id="tutor_contenido">
             	<h2>Mi perfil</h2>
                <?php
 infoMiperfilTutor(obtenerIDTabla());
                    ?>
<!--                <div class="contenido">
                	<div id="foto_mi_perfil"><img src="../img/tutor/foto_mi_perfil.jpg" width="142" height="145" /></div>
                  <div id="datos_tutor">
                  Nombre:<br/>
                  <strong>Pedro Salinas Torres</strong><br/>
                  Edad: <strong>50</strong><br/>
                  Escuela: <strong>Miguel Hidalgo</strong><br/><br/>
                  Grupo: <strong>4B</strong><br/>
                  </div>
                </div>-->
                <div id="tutor_btn">
                    <div id="tutor_btn_regresar"><a href="javascript:history.back()"> </a></div>
                    <div id="tutor_btn_capturar"><a onclick="<?php echo $metodo; ?>"></a></div>
                </div>
                <div id="frmImagen" style="display:<?php echo $estilo; ?>; margin-left: 205px;">
                        <form method="post" <?php echo $idForm; ?> action="gdaImagen.php" enctype="multipart/form-data">
                            <input <?php echo $idFile; ?> type="file" required='required' name="imagen" value="Seleccionar imagen" title="Seleccionar imagen"/><br/>
                            <!--<input type="hidden" name="idDatosPersonales" value="<?php echo obtenerIdDatosPersonales(); ?>"/>-->
                            <input <?php echo $idCmd; ?> type="submit" value="Subir" title="Subir"/>
                        </form>
                        
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
