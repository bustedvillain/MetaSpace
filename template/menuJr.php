<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" href="../frontweb/tutor/index.php">SGI Softmeta-A</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-tags icon-white"></i> Curso<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="../jr/cursosAsignados.php"><i  class="icon-tags"></i>Asignados</a></li>                               
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-th-large icon-white"></i> Reporte<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="../jr/reportes_1.php"><i  class="icon-user"></i>Subir</a></li>
                            <li><a href="../jr/verReportes.php"><i  class="icon-user"></i>Listar</a></li>
                        </ul>
                    </li>
                    <li><a href="#verEditarPerfilModal" role="button" data-toggle="modal" onclick="verPerfilPropio(<?php echo obtenerIdDatosPersonales();?>)"><i class="icon-info-sign icon-white"></i> Perfil</a></li>
                    <li><a href="#" role="button" data-toggle="modal" onclick="javascript:document.formMoodle.submit();"><i class="icon-info-sign icon-white"></i>Gesti&oacute;n escolar</a></li>
                </ul>
                <p class="navbar-text pull-right">
                    Bienvenido(a) <a href="#" class="navbar-link dropdown-toggle" data-toggle="dropdown"><?php echo (isset($_SESSION["nombre"])) ? $_SESSION["nombre"] : "Tutor Junior"; ?></a> (<a href="../logout.php">Cerrar Sesi&oacute;n</a>)                       
                </p>         
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<?php include '../template/formularioMoodle.php';?>
<?php include("../senior/verEditarPerfil.php"); ?>