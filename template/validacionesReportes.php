<?php
if ($_GET) {
    if (isset($_GET['tipoReporte'])) {
        $arr = determinaFiltro($_GET['tipoReporte']);
        $nombreRep = $arr[1];
    }
    if ($nombreRep == "aprovechamiento") {
        $nombreRep = "Evaluaci&oacute;n final";
    }
    $idCurso = $_GET['idCurso'];
    $idCursoAbierto = $_GET['idCursoAbierto'];
    $idGrupo = $_GET['idGrupo'];
} else {
    //no dejar pasar
}
?>
