
<!--CHANGE CONTROL 1.1.0
FECHA DE MODIFICACION: 21 DE MAYO DEL 2014
AUTOR: JOSE MANUEL NIETO GOMEZ
OBJETIVO: AJUSTES ESTETICOS-->

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" href="javascript:void(0)">SIA Softmeta-A</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li><a href="index.php"><i class="icon-home icon-white"></i> Inicio</a></li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-book icon-white"></i> Cursos<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="nuevoCurso.php"><i class="icon-upload"></i> Vincular</a></li>
                            <li><a href="cursos.php"><i class="icon-search"></i> Ver</a></li>
                            <li><a href="cursosAbiertos.php"><i class="icon-random"></i> Asignar Grupos</a></li>
                        </ul>
                    </li>
                    <li><a href="#verEditarPerfilModal" role="button" data-toggle="modal" onclick="verPerfilPropio(<?php echo obtenerIdDatosPersonales();?>)"><i class="icon-info-sign icon-white"></i> Perfil</a></li>
                    <li><a href="#" role="button" data-toggle="modal" onclick="javascript:document.formMoodle.submit();"><i class="icon-share icon-white"></i> Recursos</a></li>
                </ul>
                <p class="navbar-text pull-right">
                    Bienvenido(a) <a href="javascript:void(0)" class="navbar-link dropdown-toggle" data-toggle="dropdown"><?php echo (isset($_SESSION["nombre"])) ? $_SESSION["nombre"] : "Gestor de Contenido"; ?></a> (<a href="../logout.php">Cerrar Sesi&oacute;n</a>)                       
                </p>         
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<?php include '../template/formularioMoodle.php';?>
<?php include("../senior/verEditarPerfil.php"); ?>
