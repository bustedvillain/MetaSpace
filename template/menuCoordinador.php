<!--CHANGE CONTROL 1.1.0
FECHA DE MODIFICACIÓN: 21 DE MAYO DEL 2014
AUTOR: JOSE MANUEL NIETO GOMEZ
OBJEVITO: CAMBIOS ESTETICOS-->

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" href="../frontweb/tutor/index.php">SIA Softmeta-A</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-tags icon-white"></i> Curso<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="../coordinador/cursosAsignados.php"><i  class="icon-tags"></i>Asignados</a></li>                               
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-flag icon-white"></i> Tutores<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="../coordinador/tutoresSeniorAsignados.php"><i  class="icon-user"></i>Senior Asignado</a></li>
                            <li><a href="../coordinador/tutoresJrAsignados.php"><i  class="icon-user"></i>Junior Asignado</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-th-large icon-white"></i> Reporte<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="../coordinador/reportes_1.php"><i  class="icon-upload"></i>Generar</a></li>
                            <li><a href="../coordinador/verReportes.php"><i  class="icon-search"></i>Listar</a></li>
                        </ul>
                    </li>
                    <li><a href="#verEditarPerfilModal" role="button" data-toggle="modal" onclick="verPerfilPropio(<?php echo obtenerIdDatosPersonales();?>)"><i class="icon-info-sign icon-white"></i> Perfil</a></li>
                    <li><a href="#" role="button" data-toggle="modal" onclick="javascript:document.formMoodle.submit();"><i class="icon-share icon-white"></i> Recursos</a></li>
                </ul>
                <p class="navbar-text pull-right">
                    Bienvenido(a) <a href="#" class="navbar-link dropdown-toggle" data-toggle="dropdown"><?php echo (isset($_SESSION["nombre"])) ? $_SESSION["nombre"] : "Tutor Coordinador"; ?></a> (<a href="../logout.php">Cerrar Sesi&oacute;n</a>)                       
                </p>         
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<?php include '../template/formularioMoodle.php';?>
<?php include("../senior/verEditarPerfil.php"); ?>