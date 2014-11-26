<!--Change control #7
Objetivo: Cambiar boton de ir a moodle por Gestión escolar
Autor: Omar
Fecha: 14/Enero/2014-->

<!--CHANGE CONTROL 0.99.9
FECHA DE MODIFICACION: 23 DE ENERO DEL 2014
AUTOR: JOSE MANUEL NIETO GOMEZ
OBJETIVO: VALIDAR LA SESION PARA SABER QUE MENU DESPLEGAR-->

<!--CHANGE CONTROL 1.1.0
FECHA DE MODIFICACION: 21 DE MAYO DEL 2014
AUTOR: JOSE MANUEL NIETO GOMEZ
OBJETIVO: AJUSTES ESTETICOS-->

<?php if(esAdministrador()){ ?>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" href="index.php">SIA Softmeta-A</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <!--<li><a href="index.php"><i class="icon-home icon-white"></i> Inicio</a></li>-->
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i> Usuarios<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-submenu">
                                <a href="javascript:void(0)"><i class="icon-user"></i> Alumnos</a>
                                <ul class="dropdown-menu">
                                    <li><a href="altaAlumnoEstudiante.php"><i class="icon-upload"></i> Agregar</a></li>
                                    <li><a href="verAlumnosEstudiantes.php"><i class="icon-search"></i> Listar</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu">
                                <a href="javascript:void(0)"><i class="icon-user"></i> Usuarios</a>
                                <ul class="dropdown-menu">
                                    <li><a href="altaAlumnoProfesionista.php"><i class="icon-upload"></i> Agregar</a></li>
                                    <li><a href="verAlumnosProfesionistas.php"><i class="icon-search"></i> Listar</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu">
                                <a href="javascript:void(0)"><i class="icon-user"></i> Padre de Familia</a>
                                <ul class="dropdown-menu">
                                    <li><a href="altaPadres.php"><i class="icon-upload"></i> Agregar</a></li>
                                    <li><a href="verPadres.php"><i class="icon-search"></i> Listar</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu">
                                <a href="javascript:void(0)"><i class="icon-user"></i> Profesor de Aula</a>
                                <ul class="dropdown-menu">
                                    <li><a href="altaProfesores.php"><i class="icon-upload"></i> Agregar</a></li>
                                    <li><a href="verProfesores.php"><i class="icon-search"></i> Listar</a></li>
                                </ul>
                            </li>  
                            <li class="dropdown-submenu">
                                <a href="javascript:void(0)"><i class="icon-list"></i> Grupo</a>
                                <ul class="dropdown-menu">
                                    <li><a href="altaGrupos.php"><i class="icon-upload"></i> Agregar</a></li>
                                    <li><a href="verGrupos.php"><i class="icon-search"></i> Listar</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="altaMasiva.php"><i class="icon-file"></i> Alta Masiva</a>
                            </li>
                        </ul>
                    </li>  
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-indent-left"></i> Administraci&oacute;n<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-submenu">
                                <a href="javascript:void(0)">Tutor</a>
                                <ul class="dropdown-menu">
                                    <li><a href="altaTutores.php"><i class="icon-upload"></i> Agregar</a></li>
                                    <li><a href="verTutores.php"><i class="icon-search"></i> Listar</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu">
                                <a href="javascript:void(0)">Gestor de Contenido</a>
                                <ul class="dropdown-menu">
                                    <li><a href="altaGestores.php"><i class="icon-upload"></i> Agregar</a></li>
                                    <li><a href="verGestores.php"><i class="icon-search"></i> Listar</a></li>
                                </ul>
                            </li>  
                            <li class="dropdown-submenu">
                                <a href="javascript:void(0)">Administrador</a>
                                <ul class="dropdown-menu">
                                    <li><a href="altaAdministradores.php"><i class="icon-upload"></i> Agregar</a></li>
                                    <li><a href="verAdministradores.php"><i class="icon-search "></i> Listar</a></li>
                                </ul>
                            </li>                            
                            <li>
                                <a href="configuracion.php">Configurar</a>                                
                            </li>                          
                            
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-tags"></i> Cat&aacute;logos<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="nav-header">Relacionado a curso</li>
                            <li><a href="habilidades.php">Habilidad</a></li>
                            <li><a href="asignaturas.php">Asignatura</a></li>
                            <li><a href="categorias.php">Categor&iacute;a</a></li>                                    
                            <li class="divider"></li>                                    
                            <li class="nav-header">Informaci&oacute;n General</li>
                            <li><a href="instituciones.php">Instituci&oacute;n</a></li>
                            <li><a href="escuelas.php">Escuela</a></li>                             
                            <li><a href="empresa.php">Empresa</a></li> 
                            <li><a href="nivel_escolar.php">Nivel Educativo</a></li> 
                            <li><a href="grado_escolar.php">Grado Escolar</a></li>                                    
                            <li><a href="nacionalidad.php">Nacionalidad</a></li>
                            <li class="divider"></li>                                    
                            <li><a target="_blank" href="<?=URL_BIBLIOTECA_ADMIN?>">Biblioteca Virtual</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-book"></i> Curso<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="nuevoCurso.php"><i class="icon-upload"></i> Vincular</a></li>
                            <li><a href="cursos.php"><i class="icon-search"></i> Ver</a></li>
                            <li><a href="cursosAbiertos.php"><i class="icon-random"></i> Asignar Grupos</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-th-large"></i> Reporte<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="../admin/reportes_1.php"><i  class="icon-upload"></i>Generar</a></li>
                            <li><a href="../admin/verReportes.php"><i  class="icon-search"></i>Listar</a></li>
                        </ul>
                    </li>
                    <li><a href="#verEditarPerfilModal" role="button" data-toggle="modal" onclick="verPerfilPropio(<?php echo $_SESSION["idPorTabla"];?>)"><i class="icon-info-sign icon-white"></i> Perfil</a></li>
                    <li><a href="#" role="button" data-toggle="modal" onclick="javascript:document.formMoodle.submit();"><i class="icon-share"></i> Recursos</a></li>
                </ul>
                <p class="navbar-text pull-right">
                    Bienvenido(a) <a href="javascript:void(0)" class="navbar-link dropdown-toggle" data-toggle="dropdown"><?php echo (isset($_SESSION["nombre"])) ? $_SESSION["nombre"] : "Admin"; ?></a> (<a href="../logout.php">Cerrar Sesi&oacute;n</a>)                       
                </p>         
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<?php include '../template/formularioMoodle.php';?>
<?php include("../senior/verEditarPerfil.php"); 
}else if(esGestorContenido()){
    include("../template/menuGestor.php");
}
?>


