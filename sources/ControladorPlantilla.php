<?php

include_once 'Funciones.php';
if ($_POST) {
    $instruccion = $_POST["ins"];
    switch ($instruccion) {

        case "infoParaModalLinkeo":
            $idUnidad = $_POST["idUnidad"];

            $arr = arregloSeriesContenidos($idUnidad);
            //var_dump($arr);
            foreach ($arr as $a) {

                echo<<<arre
            $a
arre;
            }

            break;
        case "infoParaModalLinkeoEditar":
            $idUnidad = $_POST["idUnidad"];
            $arr = arregloSeriesContenidosEditar($idUnidad);
            foreach ($arr as $a) {

                echo<<<arre
            $a
arre;
            }

            break;

        case "prueba":
            echo 'ok';
            break;
        case "linkeoConSerie":

            $arr = $_POST;
            $v = "";
            $cont = 0;
            $idUnidad = $_POST["idUnidad"];
            $idSerie = 0;
            $contenido = "";
            $recursoEsperado = "habilidad";
            $habilidad = 0;
            $tipo = 0;
            foreach ($_POST as $a => $b) {
                $cont++;
                $v = $v . "##" . $a . "=" . $b;
                $nuevoValor = tresPrimerosCaracteres($b);
                if (matcheaCadena($nuevoValor, "serie")) {
                    $idSerie = almacenamientoSerie($nuevoValor, $idUnidad);
                } else if (matcheaCadena($nuevoValor, "contenido")) {
                    $contenido = $nuevoValor;
                    //almacenamientoContenido($nuevoValor, $idSerie);
                } else if (is_numeric($nuevoValor)) {
                    switch ($recursoEsperado) {
                        case 'habilidad':
                            $habilidad = $nuevoValor;
                            $recursoEsperado = "tipo";
                            break;
                        case 'tipo':
                            $tipo = $nuevoValor;
                            $recursoEsperado = "habilidad";
                            almacenamientoContenido($contenido, $idSerie, $tipo, $habilidad);
//                            echo almacenamientoContenido($contenido, $idSerie, $tipo, $habilidad);
                            break;
                    }
                }
            }
            //echo $v;
            //echo $_POST['idUnidad'];
            echo 'Datos actualizados correctamente';
            break;
        case "editaConSerie":

            $arr = $_POST;
            $v = "";
            $cont = 0;
            $idUnidad = $_POST["idUnidad"];
            $idSerie = 0;
            $contenido = "";
            $recursoEsperado = "habilidad";
            $habilidad = 0;
            $tipo = 0;
            foreach ($_POST as $a => $b) {
                $cont++;
                $v = $v . "##" . $a . "=" . $b;
                $nuevoValor = tresPrimerosCaracteres($b);
                if (matcheaCadena($nuevoValor, "serie")) {
                    $idSerie = idSerie($nuevoValor, $idUnidad);
                } else if (matcheaCadena($nuevoValor, "contenido")) {
                    $contenido = $nuevoValor;
                    //almacenamientoContenido($nuevoValor, $idSerie);
                } else if (is_numeric($nuevoValor)) {
                    switch ($recursoEsperado) {
                        case 'habilidad':
                            $habilidad = $nuevoValor;
                            $recursoEsperado = "tipo";
                            break;
                        case 'tipo':
                            $tipo = $nuevoValor;
                            $recursoEsperado = "habilidad";
                            editaContenido($contenido, $idSerie, $tipo, $habilidad);
//                            echo editaContenido($contenido, $idSerie, $tipo, $habilidad);
                            break;
                    }
                }
            }
            //echo $v;
            //echo $_POST['idUnidad'];
            echo 'Datos actualizados correctamente';
            break;
        case "retornaIdProgreso":
            //$arrayProgreso = array();
            $arrayProgreso = retornaInfoProgreso($_POST['idRelCursoGrupo'], $_POST['idAlumno'], $_POST['idUnidad']);
//            echo json_encode($arrayProgreso);
            echo $arrayProgreso;
            break;
        case "retornaArrContenidos":
            echo (retornaArrCont($_POST['idUnidad'], $_POST['idAlumno']));
            break;
        case "retornaArrContenidos2":
            echo json_encode(retornaArrCont2($_POST['idUnidad']));
            break;
        case "asignarCalificacion":
            echo asignarCalificacion($_POST['idAlumno'], $_POST['idElemento'], $_POST['calificacion']);
            break;
        case "asignarStatusElemento":
            echo asignarStatusElemento($_POST['idAlumno'], $_POST['idElemento'], $_POST['status'], date("Y-m-d"));
            break;
        case "registrarEntradaPlantilla":
            echo registrarEntradaPlantilla($_POST["idProgresoAlumno"]);
            break;
        case "registraIntentoPlantilla":
            echo registraIntentoPlantilla($_POST["idProgresoAlumno"], $_POST["calificacion"]);
            break;
        case "registraSalidaPlantilla":
            echo registraSalidaPlantilla($_POST["idProgresoAlumno"]);
            break;
    }
}
?>
