//Control de cambios #5
//26-dic-2013
//Modificaciones requeridas por eblue para la plantilla
//Control de cambios #7
//15/ene/2014
//Volumen en la plantilla, arranque automático en admin y prevent click fuera de fancyCal

var cargaInicial = 0;
//Iniciamos con la función inicio
$(document).ready(inicio);
//Hasta que se carguen todos los componentes
$(window).load(function()
{
    cargaCompleta();
});
/**
 * Función que cuando termina la carga de la plantilla quita el loading y muestra e navegador de contenidos
 * @returns {undefined}
 */
function cargaCompleta()
{
    $("#smLoadingPlantilla").css("display", "none");
    $("#smPrincipal").css("opacity", "1");
}
/**
 * Función que arranca a toda la plantilla
 * @returns {undefined}
 */
function inicio()
{
    //Colocando porcentaje del  loader
    $("#loadingPorcentaje").html("0%");
    //Cargando los gráficos de toda la plantilla

    cargaBotoneria();
    $("#loadingPorcentaje").html("20%");
    eventosDeSustitucion();
    $("#loadingPorcentaje").html("40%");
    eventosBotones();
    $("#loadingPorcentaje").html("60%");
    debugConsole('id=' + idRelCursoGrupo);
    $('#btnMapa').attr("href", "mapa/mapaUnidad.php?idUnidad=" + idUnidad + '&idRelCursoGrupo=' + idRelCursoGrupo + '&idAlumno=' + idAlumno);
    if (idRelCursoGrupo !== 0)
    {
    }
    initInfoPlantilla();
    $("#loadingPorcentaje").html("80%");

    $(window).resize(function()
    {
        arreglaAlResize();
    });
    volumenGral = 50;

}
function arreglaAlResize()
{
    $(".smBotonT").width($(".smBotonT").height());

    $(".botones-bottom").width($(".botones-bottom").height());
    if (navegador !== "Google Chrome") {
        $("#botonPlay").css("width", "8%");
    }
    $("#botonPlay").width($("#botonPlay").height());
    debugConsole("Widthss:" + window.innerWidth + "Heigthss:" + window.innerHeight);
    if ($(window).width() < $(window).height())
    {
        debugConsole('soy e menir');
        $("#titulo").css("margin-left", "6%");
        $("#titulo").css("margin-top", "3%");
        $("#titulo").height("60%");
        $("#titulo").width($("#titulo").height() * 4);
        $("#smBottomCenter").css("padding-left", "30%");
        $(".smBotonT").width("8%");
        $(".smBotonT").height($(".smBotonT").width());
        $(".smBotonT").css("margin-top", "3%");
        $(".botones-bottom").css("margin-top", "4%");
        $("#smBottomCenter").css("margin-top", "1%");
        $(".smBotonL").css("width", "60%");
        $(".smBotonL").css("margin-left", "13%");
        $(".smBotonL").css("margin-top", "25%");

    }
    else
    {
        $("#titulo").css("margin-top", "0%");
        $("#titulo").css("margin-left", "40%");
        $("#titulo").css("width", "15%");
        $("#titulo").css("height", "100%");
        $("#titulo").css("position", "absolute");
        $("#smBottomCenter").css("padding-left", "40%");
        $(".smBotonT").width("5%");
        $(".smBotonT").height($(".smBotonT").width());
        $(".smBotonT").css("margin-top", "0%");
        $(".botones-bottom").css("margin-top", "2%");
        $("#smBottomCenter").css("margin-top", "0%");
        $(".smBotonL").css("width", "30%");
        $(".smBotonL").css("margin-left", "35%");
        $(".smBotonL").css("margin-top", "35%");


    }
    $(".smBotonL").height($(".smBotonL").width());
}
function cargaControles()
{
    trazaEnConsola('---------------Funcion: cargaControles', 4);
    $(function() {
        $("#sliderVolumen").slider(
                {
                    range: 'min',
                    max: 108,
                    min: -8,
                    orientation: "vertical",
                    step: 2,
                    stop: function(event, ui) {
                        if (ui.value <= 0) {
                            $("#sliderVolumen").slider("option", "value", 0);
                        }
                        if (ui.value >= 100) {
                            $("#sliderVolumen").slider("option", "value", 100);
                        }

                    },
                    slide: function(event, ui) {

                        if (ui.value >= 100) {
                            $("#sliderVolumen").slider("option", "value", 100);
                        }

                    }
//                    value: volumenGral
                });
    });
//    $(".ui-slider-handle").click(function(){
//        volumenGral+=20;
//        seteaVolumen(volumenGral);
//        $("#sliderVolumen").slider("option", "value", volumenGral);
//    })
    seteaVolumen(volumenGral);
    $("#sliderVolumen").slider("option", "value", volumenGral);
//    var metodo = pathToMetodo(detalleDeIndiceActual());
//inicia control de cambios #5
    $('.fancyRecurso').click(function() {
        pausarContenido();
    });
    $('.fancyInstrucciones').click(function() {
        pausarContenido();
    });
//termina control de cambios #5
    $('.fancyInstrucciones').fancybox(
            {
                minWidth: '95%',
                minHeight: '95%',
                'type': 'iframe',
                afterShow: function() {
                    var toolbar = '<audio preload="auto" controls preload="none" autoplay loop>'
                            + '<source src="' + unidades_path + arrCont[indiceActual].ruta + 'instrucciones/audio_ins.wav" />'
                            + '<source src="' + unidades_path + arrCont[indiceActual].ruta + 'instrucciones/audio_ins.mp3" />'
                            + '<source src="' + unidades_path + arrCont[indiceActual].ruta + 'instrucciones/audio_ins.ogg" />'
//                            + '<source src="' + unidades_path + "/" + carpetaSerieDeIndiceActual() + '/instrucciones/' + metodo + '_ins.wav" />'
//                            + '<source src="' + unidades_path + "/" + carpetaSerieDeIndiceActual() + '/instrucciones/' + metodo + '_ins.mp3" />'
//                            + '<source src="' + unidades_path + "/" + carpetaSerieDeIndiceActual() + '/instrucciones/' + metodo + '_ins.ogg" />'
                            + '</audio>';
                    $(".fancybox-outer").prepend(toolbar);
                },
//inicia control de cambios #5
                afterClose: function() {
                    playContenido();
                }
//termina control de cambios #5

            });
    $('.fancyRecurso').fancybox(
            {
                minWidth: '95%',
                minHeight: '95%',
                'type': 'iframe',
//inicia control de cambios #5
                afterClose: function() {
                    playContenido();
                }
//termina control de cambios #5
            });
    //inicia control de cambios #7
    $('.fancyCal').fancybox(
            {
                minWidth: '88%',
                maxHeight: '78%',
                minHeight: '78%',
                'type': 'iframe',
                closeBtn: false,
                closeClick: false,
                padding: 0,
                helpers: {
                    overlay: {closeClick: false} // prevents closing when clicking OUTSIDE fancybox 
                }
                //termina control de cambios #7
//inicia control de cambios #5
//                afterClose: function() {
//                    playContenido();
//                }
//termina control de cambios #5
            });

    $(function()
    {
        $('audio').audioPlayer({
            classPrefix: 'audioplayer',
            strPlay: 'Play',
            strPause: 'Pause',
            strVolume: 'Volume'
        });
    });
//    var metodo = pathToMetodo(detalleDeIndiceActual());
    $('#btnInstrucciones').attr("href", unidades_path + arrCont[indiceActual].ruta + 'instrucciones/index.html');
    $('#btnTutor').attr("href", unidades_path + arrCont[indiceActual].ruta + 'tutor/index.html');
//    $('#btnInstrucciones').attr("href", unidades_path + "/" + carpetaSerieDeIndiceActual() + '/instrucciones/' + metodo + '_ins.html');
//    $('#btnTutor').attr("href", unidades_path + "/" + carpetaSerieDeIndiceActual() + '/tutor/' + metodo + '_tutor.html');
//    serie = serieDeIndiceActual();
    debugConsole('Serie=' + serie);
//    serie_path = unidades_path + "/" + serie;
//    serie_path = unidades_path + "/" + serie;
    debugConsole('Serie_path =' + serie_path);
    $(this).mousemove(function(e) {

        parent.tiempoSesion = 0;
    });
    $(this).keypress(function(e) {
        parent.tiempoSesion = 0;
    });
    trazaEnConsola('---------------Fin: cargaControles', 4);
}
function eventosBotones()
{
    trazaEnConsola('------------------Función: eventosBotones', 4);

    $('#botonVolumen').click(function(event)
    {
        $("#sliderVolumen").toggle("fast");
    });
    $('#botonAnterior').click(function(event)
    {
        if (indiceActual > 0) {
            //pausarContenido();
            indiceActual--;
            volumenGral = $("#sliderVolumen").slider("option", "value");
            trazaEnConsola("Anterior, volumen=" + volumenGral, 2);
            cargaContenidooo();
//            recargaPaginaContenido(indiceActual);
        }
    });
    $('#botonSiguiente').click(function() {

        if (indiceActual < maxCantArr)
        {
//            pausarContenido();
            indiceActual++;
            volumenGral = $("#sliderVolumen").slider("option", "value");
            trazaEnConsola("Siguiente, volumen=" + volumenGral, 2);
            cargaContenidooo();
//            recargaPaginaContenido(indiceActual);
        }
    });
    $('#btnReiniciar').click(function() {
//        alert('presionaste Reiniciar');
        window.frameCont.location.reload();
    });
    $('#botonPlay').click(function() {
        if (statusReproduccion === 0) {
            playContenido();
//            statusReproduccion = 1;
        } else {
            pausarContenido();
//            statusReproduccion = 0;
        }

    });
////        if (typeof jQuery === 'undefined') {
////            debugConsole("jQuery is not loaded");
////            //or
////            alert("jQuery is loaded");
////        } else {
////            debugConsole("jQuery is not loaded");
////            alert("jQuery is not loaded");
////        }
//
//        switch (parseInt(statusActionDeIndiceActual()))
//        {
//            case 0:
//                //no aplica
//                break;
//            case 1:
//                pausarContenido();
//                break;
//            case 2:
//                playContenido();
//                break;
//        }
//    });

    $(".btnRecurso").on("touchstart", function() {
//        alert('touche1');
//        banderaTiempo = 1;
//        tiempo(this);
        tiempoT = new Date();
    });
    $(".btnRecurso").on("touchend", function() {
//        alert('no touche 1');
//        banderaTiempo = 0;
//        alert('Fueron seg='+tiempoT);
        var t = new Date();
        if ((t - tiempoT) >= 200) {
//            alert('Mas de eso');
//            var nombre
            $("#smMensaje").show();
            $("#smMensaje").html($(this).attr("title"));
            var pos = $(this).position();
//            alert("Left="+ pos.left +"Top="+pos.top);
            $("#smMensaje").css("margin-left", pos.left + 50);
            $("#smMensaje").css("margin-top", pos.top);
            setTimeout(function() {
                $("#smMensaje").css("display", "none");
            }, 2000);
        }
//        alert('Ini='+tiempoT+"Fin="+t+"dif ="+(t-tiempoT));
    });
    $(".btnRecurso").on("ontouchstart", function() {
        alert('touche2');
    });
    $(".btnRecurso").on("ontouchend", function() {
        banderaTiempo = 0;
        alert('Fueron seg=' + tiempoT);
    });
    $("#sliderVolumen").on("slide", function(event, ui) {
        var volumen = $("#sliderVolumen").slider("option", "value");
//        trazaEnConsola('Volumen' + volumen, 5);
        seteaVolumen(volumen);
    });
//    $(".btnRecurso").mousedown(function(event) {
//        setTimeout(function() {
//            debugConsole('Touche');
//        }, 2000);
//    });


//    $('#botonVolumen').on('click', function(event)
//    {
//        $("#sliderVolumen").toggle("fast");
//    });
//    $("#sliderVolumen").on("slide", function(event, ui) {
//        trazaEnConsola('Volumen' + $("#sliderVolumen").slider("option", "value"), 5);
//    });


//    $("#smLoadingPlantilla").css("display","none");
//    $("#smPrincipal").css("opacity","1");
    trazaEnConsola('------------------Fin eventosBotones', 4);
}
function tiempo(objB) {
//    if (banderaTiempo === 1) {
//        tiempoT++;
//        setTimeout(tiempo(objB), 1);
//    }
//    if(tiempoT >= 200){
//        $("#smMensaje").show();
//        $("#smMensaje").html($(objB).attr("title"));
//        $("#smMensaje").css("margin-left", $(objB).css("margin-left") + 50);
//        $("#smMensaje").css("margin-top", $(objB).css("margin-top") + 50);
//        setTimeout(function() {
//            $("#smMensaje").css("display", "none");
//        }, 2000);
//    }


}
function cierraCalif() {
    window.jQuery.fancybox.close();
}
function json2array(json) {
    var result = [];
    var keys = Object.keys(json);
    keys.forEach(function(key) {
        result.push(json[key]);
    });
    return result;
}
function initInfoPlantilla()
{
    var arrJSON = "--";
    var arrJSON2 = "--";
    if (alumno.toString() === 'si')
    {
        debugConsole("A enviar post porque alumno es SI Su id alumno es=" + idAlumno + "y su idUnidad es =" + idUnidad);
        $.post("../sources/ControladorPlantilla.php", {ins: "retornaArrContenidos", idUnidad: idUnidad, idAlumno: idAlumno}, function(respuesta)
        {
            debugConsole('Dentro');
            debugConsole(respuesta);
            arrJSON = $.parseJSON(respuesta);
//            arrCont = json2array(arrJSON);
            arrCont = $.parseJSON(respuesta);
            maxCantArr = arrCont.length - 1;
            debugConsole('ArrCont' + arrCont);
            debugConsole('+++++++++++++++alumno es=' + alumno + 'idAlumno=' + idAlumno);
//            if (idIndiceNuevo < 0) {
            $.post("../sources/ControladorPlantilla.php", {ins: "retornaIdProgreso", idRelCursoGrupo: idRelCursoGrupo, idAlumno: idAlumno, idUnidad: idUnidad}, function(respuesta2)
            {
                debugConsole(respuesta2);
                arrJSON2 = jQuery.parseJSON(respuesta2);
                debugConsole("ArrJSON2=" + respuesta2 + "**" + arrJSON2.valueOf());
                idRelCursoGrupo = arrJSON2.idRelCursoGrupo;

                idElementoAer = arrJSON2.idElementoAer;
                tipo_elemento = arrJSON2.letraElemento;
                debugConsole('idElemenAer' + parseInt(idElementoAer));
                indiceActual = indicePorIdElemento(parseInt(idElementoAer));
                debugConsole('IActual' + indiceActual);
                debugConsole('TIPO elemento' + tipo_elemento);
                debugConsole('aa');
                registraEntrada();
                cargaContenidooo();

            });
//            } else {
//                indiceActual = idIndiceNuevo;
//                cargaContenidooo();
//            }
        });
    } else {
        debugConsole("A enviar post porque alumno es  NO, idUnidad =" + idUnidad);
        $.post("../sources/ControladorPlantilla.php", {ins: "retornaArrContenidos", idUnidad: idUnidad}, function(respuesta)
        {
            debugConsole('Dentro');
            debugConsole('Resp' + respuesta);
//            arrJSON = $.parseJSON(respuesta);
//aaaaaa
            arrCont = $.parseJSON(respuesta);
//            arrCont = json2array(arrJSON);
            maxCantArr = arrCont.length - 1;
            debugConsole('ArrCont' + arrCont);
            debugConsole('+++++++++++++++alumno es=' + alumno);
            debugConsole('no hubo idrel');
            indiceActual = 0;
            idElementoAer = idElementoDeIndiceActual();
            tipo_elemento = tipoElementoDeIndiceActual();
//            if (idIndiceNuevo < 0) {
//            }
//            else {
//                indiceActual = idIndiceNuevo;
//            }
            cargaContenidooo();
        });

    }




}
function cargaContenidooo()
{
    statusReproduccion = 1;
    cargaControles();
    tipo_elemento = tipoElementoDeIndiceActual();
    debugConsole('en carga Botonería con fila ' + arrCont[indiceActual]);
    debugConsole('=======' + tipo_elemento);
    cargaBotoneria();
    tipo_elemento = tipoElementoDeIndiceActual();
    debugConsole('Se cargará botonería de elemento' + tipo_elemento);
    debugConsole('El path de unidades=' + unidades_path);

    debugConsole('Los valores 0=' + idProgresoDeIndiceActual() + '--1=' + idElementoDeIndiceActual() + '--2=' + detalleDeIndiceActual() + '--3=' + tipoElementoDeIndiceActual());
    debugConsole('--A punto de iniciar cargaContenido');
//    debugConsole('que hay='+arrCont[indiceActual]).valueOf();
    debugConsole("URL IFRAME:" + unidades_path + arrCont[indiceActual].ruta + "index.html");
    $("#frameCont").attr("src", unidades_path + arrCont[indiceActual].ruta + "index.html");

    if (alumno === "si" && arrCont[indiceActual].status_evaluacion.toString() === "0") {
        asignarStatusElemento(1);
    }
    $("#sliderVolumen").trigger("click");
    debugConsole("se triggeo el div");
    $(".ui-slider-handle").trigger("click");
    debugConsole("se triggeo el ui-slider-handle");
    $("#frameCont").load(function()
    {
        console.log("CorePlantilla: FrameCont load ready.");
        playContenido();
        seteaVolumen(volumenGral);
    });
//    document.getElementById("frameCont").onload = playContenido ;
//    frameCont.window.onload = playContenido;
    //inicia control de cambios #5
//    ();
    //termina control de cambios #5
//    debugConsole('lo va a cargar en ' + unidades_path + "/" + detalleDeIndiceActual());
//    $('#contenido').load(unidades_path + "/" + detalleDeIndiceActual(), function() {
//        pag_actual = arrCont[indiceActual];
//        trazaEnConsola('La pagina actual es ' + pag_actual, 1);
//        //debugConsole('El usuario es ' + usuario);
//        //trazaEnConsola('El metodo sera'+basename(arrCont[indiceActual]),1);  
//
////        eval('script' + basename(arregloCont[indiceActual]) + '();');
////        var metodo = pathToMetodo(detalleDeIndiceActual());
//        var staA = statusActionDeIndiceActual();
//        staA = parseInt(staA);
//        debugConsole('staA=' + staA);
//        switch (staA)
//        {
//            case 0:
//                trazaEnConsola('El metodo sera:-->' + pathToMetodo(detalleDeIndiceActual()) + '();', 3);
//                eval(metodo + '();');
//                debugConsole('EL estatus evaluacion es=' + parseInt(estatusEvaluacoinDeIndiceActual()));
//                if (parseInt(estatusEvaluacoinDeIndiceActual()) === 0) {
//                    cosole.log('Dentro de if');
//                    asignarStatusElemento(1);
//                }
//                setStatusActionEnIndiceActual(1);
//
//                break;
//            case 1:
//                //no aplica
//                break;
//            case 2:
//                playContenido();
//                break;
//        }

    //s05_c01();
//    });
}
//function s01_c01_play()
//{
//    debugConsole('nada');
//}
function asignarCalificacion(calif)
{
    debugConsole('Dentro de la función asignarCalificacion');
    if (alumno === "si") {
//        asignarStatusElemento(2);
        var idElemento = idElementoDeIndiceActual();
//        debugConsole('Antes de iniciar post');

        $.post("../sources/ControladorPlantilla.php", {ins: "asignarCalificacion", idAlumno: idAlumno, idElemento: idElemento, calificacion: calif}, function(respuesta)
        {
            registraIntento(calif);
//                debugConsole('Debtro del post');
//                asignarCalificacion(calif);
            debugConsole(respuesta);
            //Inicia la visualización de la plantilla
            debugConsole('el atributo href será=' + "./mensajeCalif.php?idCurso=" + idCurso + "&calificacion=" + calif + "&idElemento=" + idElemento);
            setTimeout(function() {
                $("#yt").attr("href", "./mensajeCalif.php?idCurso=" + idCurso + "&calificacion=" + calif + "&idElemento=" + idElemento);
                $("#yt").trigger("click");
            }, 3000);

        });


    } else {
        var idElemento = idElementoDeIndiceActual();
        debugConsole('el atributo href será=' + "./mensajeCalif.php?idCurso=" + idCurso + "&calificacion=" + calif + "&idElemento=" + idElemento);
        $("#yt").attr("href", "./mensajeCalif.php?idCurso=" + idCurso + "&calificacion=" + calif + "&idElemento=" + idElemento);
        $("#yt").trigger("click");
    }
}
function unaprueba() {
    alert('desde aca');
}
function asignarStatusElemento(status)
{
    debugConsole('Dentro de la función asignarStatusElemento');
    if (alumno === "si") {
        var estatus = -1;
        switch (status) {
            case 0:
            case 'noIniciado':
                estatus = 0;
                break;
            case 1:
            case 'iniciadoX':
                estatus = 1;
                break;
            case 2:
            case 'terminado':
                estatus = 2;
                break;
        }
        if (estatus > -1) {
            var idElemento = idElementoDeIndiceActual();
            $.post("../sources/ControladorPlantilla.php", {ins: "asignarStatusElemento", idAlumno: idAlumno, idElemento: idElemento, status: estatus}, function(respuesta)
            {
                debugConsole(respuesta);
                var fila = arrCont[indiceActual];
                fila.status_evaluacion = estatus;
            });
        } else {
            debugConsole('Se recibió un status desconocido, no se puede seguir');
        }
    }

}

function registraEntrada() {
    var idProgreso = idProgresoDeIndiceActual();
    $.post("../sources/ControladorPlantilla.php", {ins: 'registrarEntradaPlantilla', idProgresoAlumno: idProgreso}, function(respuesta) {
        debugConsole('Resultado de registrar entrada:' + respuesta);
    });
}
function registraSalida() {
    trazaEnConsola("--Funcion registraSalida()")
    var idProgreso = idProgresoDeIndiceActual();
    $.post("../sources/ControladorPlantilla.php", {ins: 'registraSalidaPlantilla', idProgresoAlumno: idProgreso}, function(respuesta) {
        debugConsole('Resultado de registrar salida:' + respuesta);
        parent.location.reload();
    });
    trazaEnConsola("--termina Funcion registraSalida()")
}
function registraIntento(calif) {
    var idProgreso = idProgresoDeIndiceActual();
    $.post("../sources/ControladorPlantilla.php", {ins: 'registraIntentoPlantilla', idProgresoAlumno: idProgreso, calificacion: calif}, function(respuesta) {
        debugConsole('Resultado de registrar intento:' + respuesta);
    });
}
function avanceDesdeCalif() {
    if (indiceActual < maxCantArr) {
        indiceActual++;
        volumenGral = $("#sliderVolumen").slider("option", "value");
        cargaContenidooo();
        cierraCalif();
    } else {
        debugConsole('salir');
        registraSalida();
    }
}

function playContenido()
{
//    setStatusActionEnIndiceActual(1);
//    var metodo = pathToMetodo(detalleDeIndiceActual());
//    metodo = metodo + '_play';
//    existeArrancaFuncion(metodo);
//inicia control de cambios #5

    //termina control de cambios #5
    existeArrancaFuncion('playContenido');
}
function pausarContenido()
{
//    setStatusActionEnIndiceActual(2);
//    var metodo = pathToMetodo(detalleDeIndiceActual());
//    metodo = metodo + '_pausar';
//    existeArrancaFuncion(metodo);
//inicia control de cambios #5

    //termina control de cambios #5
    existeArrancaFuncion('pausarContenido');
}
function reiniciarContenido()
{
//    var metodo = pathToMetodo(detalleDeIndiceActual());
//    metodo = metodo + '_reiniciar';
//    existeArrancaFuncion(metodo);
    existeArrancaFuncion('reinciiarContenido');
}
function seteaVolumen(volumen)
{
//    var metodo = pathToMetodo(detalleDeIndiceActual());
//    metodo = metodo + '_setVolumen';
//    existeArrancaFuncionV(metodo, volumen);
    if (isFinite(volumen)) {
        existeArrancaFuncionV('setVolumenContenido', volumen);
    }
    trazaEnConsola("Volumne = " + volumen, 4);

}
/**
 * CHANGE CONTROL 1.1.0
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJETIVO: MEJORAR LA EJECUCIÓN DE FUNCIONES DE INTERFAZ, PARA CORRECCIÓN DE BUGS
 * @param {type} nombreFuncion
 * @returns {undefined}
 */

/**
 * Ejecuta una función ubicada en la interfaz dentro del contenido cargado, no recibe parametros
 * @param {type} nombreFuncion
 * @returns {undefined}
 */
function existeArrancaFuncion(nombreFuncion) {

    if (navegador === "Mozilla Firefox") {
        var isDefined = eval('(typeof ' + 'frameCont.contentWindow.' + nombreFuncion + '==\'function\');');
        if (isDefined) {
            trazaEnConsola('Se ejecutará  funcion: ' + 'frameCont.contentWindow.' + nombreFuncion + '()', 1);
            eval('frameCont.contentWindow.' + nombreFuncion + '();');
            if (nombreFuncion === "playContenido") {
                $('#botonPlay').attr("src", template_path + "/" + tipo_elemento + "/botonPausa-a.png");
                $('#botonPlay').attr("title", "Pausar");
                debugConsole('Cambiando title a pausar');
                statusReproduccion = 1;
            } else if (nombreFuncion === "pausarContenido") {
                $('#botonPlay').attr("src", template_path + "/" + tipo_elemento + "/botonPlay-a.png");
                $('#botonPlay').attr("title", "Reproducir");
                debugConsole('Cambiando title a reproducir');
                statusReproduccion = 0;
            }
        } else {
            trazaEnConsola('La funcion: ' + 'frameCont.contentWindow.' + nombreFuncion + '() No esta definida. Try anyway.', 1);
            try {
                trazaEnConsola('Se intentara ejecutar la funcion no definida: ' + 'frameCont.contentWindow.' + nombreFuncion + '()', 1);
                eval('frameCont.contentWindow.' + nombreFuncion + '();');
                if (nombreFuncion === "playContenido") {
                    $('#botonPlay').attr("src", template_path + "/" + tipo_elemento + "/botonPausa-a.png");
                    $('#botonPlay').attr("title", "Pausar");
                    debugConsole('Cambiando title a pausar');
                    statusReproduccion = 1;
                } else if (nombreFuncion === "pausarContenido") {
                    $('#botonPlay').attr("src", template_path + "/" + tipo_elemento + "/botonPlay-a.png");
                    $('#botonPlay').attr("title", "Reproducir");
                    debugConsole('Cambiando title a reproducir');
                    statusReproduccion = 0;
                }
            } catch (e) {
                trazaEnConsola('La funcion: ' + 'frameCont.contentWindow.' + nombreFuncion + '() Genero una excepcion.', 1);
            }
        }
    } else {
        //Esta función ya no funciona en Chrome, se probará hacerlo como se hace con FireFox
//        var isDefined = eval('(typeof ' + 'frameCont.' + nombreFuncion + '==\'function\');');
//        Prueba
        var isDefined = eval('(typeof ' + 'frameCont.contentWindow.' + nombreFuncion + '==\'function\');');
        if (isDefined) {
            //Esta funcion ya no funciona en Chrome, se probará hacerlo como se hace en Firefox
            //            trazaEnConsola('Se ejecutará  funcion: ' + 'frameCont.' + nombreFuncion + '()', 1);            
//            eval('frameCont.' + nombreFuncion + '();');

            //Prueba
            trazaEnConsola('Se ejecutará  funcion: ' + 'frameCont.contentWindow.' + nombreFuncion + '()', 1);
            eval('frameCont.contentWindow.' + nombreFuncion + '();');

            if (nombreFuncion === "playContenido") {
                $('#botonPlay').attr("src", template_path + "/" + tipo_elemento + "/botonPausa-a.png");
                $('#botonPlay').attr("title", "Pausar");
                debugConsole('Cambiando title a pausar');
                statusReproduccion = 1;
            } else if (nombreFuncion === "pausarContenido") {
                $('#botonPlay').attr("src", template_path + "/" + tipo_elemento + "/botonPlay-a.png");
                $('#botonPlay').attr("title", "Reproducir");
                debugConsole('Cambiando title a reproducir');
                statusReproduccion = 0;
            }
        } else {
            trazaEnConsola('La funcion: ' + 'frameCont.' + nombreFuncion + '() No esta definida. Try it anyway.', 1);

            try {
                trazaEnConsola('Se intentara ejecutar la funcion: ' + 'frameCont.' + nombreFuncion + '()', 1);
                eval('frameCont.' + nombreFuncion + '();');
                if (nombreFuncion === "playContenido") {
                    $('#botonPlay').attr("src", template_path + "/" + tipo_elemento + "/botonPausa-a.png");
                    $('#botonPlay').attr("title", "Pausar");
                    debugConsole('Cambiando title a pausar');
                    statusReproduccion = 1;
                } else if (nombreFuncion === "pausarContenido") {
                    $('#botonPlay').attr("src", template_path + "/" + tipo_elemento + "/botonPlay-a.png");
                    $('#botonPlay').attr("title", "Reproducir");
                    debugConsole('Cambiando title a reproducir');
                    statusReproduccion = 0;
                }
            } catch (e) {
                trazaEnConsola('La funcion: ' + 'frameCont.contentWindow.' + nombreFuncion + '() Genero una excepcion.', 1);
            }
        }
    }

}

/**
 * Ejecuta una función ubicada en la interfaz dentro del contenido cargado, recibe un parametro
 * @param {type} nombreFuncion
 * @param {type} parametros
 * @returns {undefined}
 */
function existeArrancaFuncionV(nombreFuncion, parametros)
{
    if (navegador === "Mozilla Firefox") {
        var isDefined = eval('(typeof ' + 'frameCont.contentWindow.' + nombreFuncion + '==\'function\');');
        if (isDefined) {
            trazaEnConsola('Se ejecutará  funcion: ' + nombreFuncion + '(' + parametros + ')', 5);
            eval('frameCont.contentWindow.' + nombreFuncion + '(' + parametros + ');');
        }
        else {
            trazaEnConsola('La funcion: ' + 'frameCont.contentWindow.' + nombreFuncion + '(' + parametros + ') No definida. Try it anyway.', 1);

            try {
                trazaEnConsola('Se ejecutará  funcion: ' + nombreFuncion + '(' + parametros + ')', 5);
                eval('frameCont.contentWindow.' + nombreFuncion + '(' + parametros + ');');
            } catch (e) {
                trazaEnConsola('La funcion: ' + 'frameCont.contentWindow.' + nombreFuncion + '(' + parametros + ') Genero Excepcion', 1);
            }
        }
    } else {
        //Funcion desactualizada en Chrom
//        var isDefined = eval('(typeof ' + 'frameCont.' + nombreFuncion + '==\'function\');');
        //Acutlización
        var isDefined = eval('(typeof ' + 'frameCont.contentWindow.' + nombreFuncion + '==\'function\');');

        if (isDefined) {
            trazaEnConsola('Se ejecutará  funcion: ' + nombreFuncion + '(' + parametros + ')', 5);
            //Funcion desactualizada por Chrome
            eval('frameCont.' + nombreFuncion + '(' + parametros + ');');

            //Actualizacion
            eval('frameCont.contentWindow.' + nombreFuncion + '(' + parametros + ');');
        }
        else {
            trazaEnConsola('La funcion: ' + 'frameCont.' + nombreFuncion + '(' + parametros + ') No definida. Try it anyway..', 1);

            try {
                trazaEnConsola('Se ejecutará  funcion: ' + nombreFuncion + '(' + parametros + ')', 5);
                eval('frameCont.' + nombreFuncion + '(' + parametros + ');');
            } catch (e) {
                trazaEnConsola('La funcion: ' + 'frameCont.' + nombreFuncion + '(' + parametros + ') Genero excepcion', 1);
            }
        }
    }

}
function setStatusActionEnIndiceActual(status)
{
    trazaEnConsola('Funcion: setStatusActionEnIndiceActual-->status=' + status, 4);
    var fila = arrCont[indiceActual];
    fila = fila.split("##");
    var prefila = fila[0] + "##" + fila[1] + "##" + fila[2] + "##" + fila[3] + "##" + fila[4] + "##" + status;
    arrCont[indiceActual] = prefila;
}
function idProgresoDeIndiceActual()
{
    trazaEnConsola('Funcion: tipoElementoDeIndiceActual', 4);
    var fila = arrCont[indiceActual];
//    fila = fila.split("##");
//    return fila[0];
    return fila.idProgreso;
}
function idElementoDeIndiceActual()
{
    trazaEnConsola('----Funcion: idElementoDeIndiceActual', 4);
    var fila = arrCont[indiceActual];
//    fila = fila.split("##");
//    debugConsole('El idElemento del indiceActual es:' + fila[1]);
//    return fila[1];
//    trazaEnConsola('----Fin idElementoDeIndiceActual', 4);
    return fila.idElemento;
}
function detalleDeIndiceActual()
{
    trazaEnConsola('----Funcion: detalleDeIndiceActual', 4);
    var fila = arrCont[indiceActual];
//    debugConsole('FILA:' + fila);
//    fila = fila.split("##");
//    return fila[2];
    return fila.rutaCorta;
    trazaEnConsola('----Fin detalleDeIndiceActual', 4);
}
function serieDeIndiceActual() {
    trazaEnConsola('----Funcion: serieDeIndiceActual', 4);
    var detalle = detalleDeIndiceActual();
    detalle = detalle.split("/");
    return detalle[0];
    trazaEnConsola('----Fin serieDeIndiceActual', 4);

}
function carpetaSerieDeIndiceActual()
{
    var detalle = detalleDeIndiceActual();
    var serie = detalle.split("/");
    return serie[0];
}
function tipoElementoDeIndiceActual()
{
    trazaEnConsola('Funcion: tipoElementoDeIndiceActual', 4);
    var fila = arrCont[indiceActual];
//    fila = fila.split("##");
//    return fila[3];
    return fila.tipoElemento;
}
function estatusEvaluacoinDeIndiceActual()
{
    trazaEnConsola('Funcion: estatusEvaluacoinDeIndiceActual', 4);
    var fila = arrCont[indiceActual];
//    fila = fila.split("##");
//    return fila[4];
    return fila.status_evaluacion;
}
function statusActionDeIndiceActual()
{
    trazaEnConsola('Funcion: statusActionDeIndiceActual', 4);
    var fila = arrCont[indiceActual];
    fila = fila.split("##");
    return fila[5];
}

function indicePorIdElemento(idElementoAer)
{

    debugConsole('//////' + arrCont.valueOf());
    debugConsole('length=' + arrCont.length);
    for (var i = 0; i < arrCont.length; i++) {
        debugConsole('hay:' + arrCont[i]);
//        debugConsole('IdelemAer=' + idElementoAer + "\nFilaToidEe" + filaToIdElemento(arrCont[i]));
        if (idElementoAer.toString() === arrCont[i].idElemento.toString())
//        if (idElementoAer.toString() === filaToIdElemento(arrCont[i]).toString())
        {
            debugConsole('Fueron iguales en pos' + i + "\nFila es:" + arrCont[i]);
            return i;
        }
    }
}
function filaToIdElemento(fila)
{
    var arrayFila = fila.split("##");
    debugConsole('enflatoidElemento=' + arrayFila[1]);
    return arrayFila[1];
}
function retornaIdProgreso(idRelCursoGrupo)
{


    //return arrJSON;
}

function eventosDeSustitucion()
{
    trazaEnConsola('------------------Función: eventosDeSustitucion', 4);
    $('.smBoton').mouseenter(function(event)
    {
        $(this).attr("src", cambiaImagenBoton($(this).attr('src'), "b"));
    });
    $('.smBoton').mouseout(function(event)
    {
        $(this).attr("src", cambiaImagenBoton($(this).attr('src'), "a"));
    });
    $('.smBoton').mouseup(function(event)
    {
        $(this).attr("src", cambiaImagenBoton($(this).attr('src'), "a"));
    });
    $('.smBoton').mouseup(function(event)
    {
        $(this).attr("src", cambiaImagenBoton($(this).attr('src'), "a"));
    });
    $('.smBoton').mousedown(function(event)
    {
        $(this).attr("src", cambiaImagenBoton($(this).attr('src'), "c"));
    });
    $('.smBoton').click(function(event)
    {
        //cargaRecurso($(this).attr("id"));
    });
    $('.smBotonL').mouseenter(function(event)
    {
        $(this).attr("src", cambiaImagenBoton($(this).attr('src'), "b"));
    });
    $('.smBotonL').mouseout(function(event)
    {
        $(this).attr("src", cambiaImagenBoton($(this).attr('src'), "a"));
    });
    $('.smBotonL').mouseup(function(event)
    {
        $(this).attr("src", cambiaImagenBoton($(this).attr('src'), "a"));
    });
    $('.smBotonL').mousedown(function(event)
    {
        $(this).attr("src", cambiaImagenBoton($(this).attr('src'), "c"));
    });
    $('.smBotonL').click(function(event)
    {
        //cargaRecurso($(this).attr("id"));
    });
    $('#botonSalir').click(function(event)
    {
        debugConsole('salir');
        registraSalida();


    });
    trazaEnConsola('------------------Fin eventosDeSustitucion', 4);

}

function cambiaImagenBoton(nombreImagen, letra)
{
    trazaEnConsola('Nimagen=' + nombreImagen, 5);
    //nombreImagen=template_path+"/"+tipo_elemento+"/"+nombreImagen;
    var extensionImagen = nombreImagen.substr(nombreImagen.length - 4, nombreImagen.length);
    var nombreSinExt = nombreImagen.substr(0, nombreImagen.length - 5);
    var nuevoNombre = nombreSinExt + letra + extensionImagen;
//    trazaEnConsola('nombreSine'+nombreSinExt,5);
    trazaEnConsola('Cambiando imagen' + nuevoNombre, 5);
    return nuevoNombre;
    //return imagenes_template_path + "/next-a.png";
    //$(this).attr("src", nuevoNombre);
}
function alertMedidasPixeles()
{
    var w = $("#contenido").width();
    var h = $("#contenido").height();
    alert("width:" + screen.width + "height:" + screen.height + "\nWidth:" + w + "height" + h);
}
function cargaBotoneria()
{
    trazaEnConsola('------------------Funcion Carga Botonería', 4);
    debugConsole('El Id de la unidad es:' + idUnidad);
//    template_path = "/eblue/softmeta/storage/templates/1";
    debugConsole('La ruta template_path:' + template_path);
    nom_dir_template = 'templateaa';
    debugConsole('@@@@@@@' + tipo_elemento);
    //Cargamndo las imágenes de la plantilla
    //Fondo de la plantilla
    $('#smPrincipal').css("background-image", "url(" + template_path + "/" + tipo_elemento + "/marco.png)");
    //Botones de la parte de arriba

    $('#titulo').attr("src", template_path + "/" + tipo_elemento + "/titulo.png");
    $('#mapa').attr("src", template_path + "/" + tipo_elemento + "/mapa-a.png");
    $('#instrucciones').attr("src", template_path + "/" + tipo_elemento + "/instrucciones-a.png");
    $('#tutor').attr("src", template_path + "/" + tipo_elemento + "/tutor-a.png");
    //Botones laterales izquierdos
    $('#ayuda').attr("src", template_path + "/" + tipo_elemento + "/ayuda-a.png");
    $('#temario').attr("src", template_path + "/" + tipo_elemento + "/temario-a.png");
    $('#glosario').attr("src", template_path + "/" + tipo_elemento + "/glosario-a.png");
    $('#bibliografia').attr("src", template_path + "/" + tipo_elemento + "/bibliografia-a.png");
    //Créditos ya no existe más
//    $('#creditos').attr("src",template_path+"/"+tipo_elemento+"/creditos-a.png");
    //Botones de la parte de abajo
    $('#botonReiniciar').attr("src", template_path + "/" + tipo_elemento + "/botonReiniciar-a.png");
    $('#botonAnterior').attr("src", template_path + "/" + tipo_elemento + "/botonAnterior-a.png");
    //Inicia control de cambios #5
    $('#botonPlay').attr("src", template_path + "/" + tipo_elemento + "/botonPlay-a.png");
    //Termina control de cambios #5
    $('#botonSiguiente').attr("src", template_path + "/" + tipo_elemento + "/botonSiguiente-a.png");
    $('#botonVolumen').attr("src", template_path + "/" + tipo_elemento + "/botonVolumen-a.png");
    $('#botonSalir').attr("src", template_path + "/" + tipo_elemento + "/botonSalir-a.png");
//    $('#btnMapa').attr("href", "mapa/mapaUnidad.php?idUnidad=" + idUnidad + '&idRelCursoGrupo=' + idRelCursoGrupo);

    //Asignación de rutas a los href de los recursos por unidad de la class btnRecurso
    $('.btnRecurso').each(function()
    {
        $(this).attr("href", unidades_path + "/recursos/" + $(this).attr("name") + ".html");
    });

    //Propiedades para que el contenido se visualice dentro del área designada
    $("#contenido").css("overflow", "hidden");
    $("#smTop").css("z-index", "5000");
    $("#smLeft").css("z-index", "5000");
    $("#smBottom").css("z-index", "5000");

    $("#sliderVolumen").slider("option", "value", volumenGral);

    //Ajustar toda la botonería de acuerdo a las dimensiones de la pantalla
    arreglaAlResize();
    trazaEnConsola('------------------Fin Funcion Carga Botonería', 4);
}
function llenaArregloContenidos()
{
    $.post("../../sources/ControladorPlantilla.php", {ins: "llenarArreglo", idUnidad: idUnidad}, function(respuesta) {
        arrContenidos = respuesta;
    });
}
function trazaEnConsola(texto, level)
{
    if (level <= traceLevel)
    {
        debugConsole(texto);
    }
}
function basename(str) {
    var base = new String(str).substring(str.lastIndexOf('/') + 1);
    if (base.lastIndexOf(".") != -1)
        base = base.substring(0, base.lastIndexOf("."));
    return base;
}
function pathToMetodo(str)
{
    var newStr = str.replace('/', '_');
    var arrStr = newStr.split(".");
//    trazaEnConsola(arrStr[0],"4");
    return arrStr[0];
}
//function asignarCalificacion(calificacion)
//{
//    if(alumno === 'si'){
//        debugConsole('++++++++Calif' + calificacion + '+++progreso' + idProgresoDeIndiceActual());
//    $.post("../sources/ControladorPlantilla.php", {ins: "asignarCalificacion", idUnidad: idUnidad}, function(respuesta) {
//        debugConsole("Respuesta de asignar calificación" + respuesta);
//    });
//    }
//    
//}
function recargaPaginaContenido(idInidceN) {
    var rutaActual = location.href;
    var nuevaRuta = "";
    var bandera = 0;
    if (alumno === "si") {
        var arrRuta = rutaActual.split("&");
        for (var i = 0; i <= 2; i++) {
            if (bandera === 0) {
                nuevaRuta = nuevaRuta + arrRuta[i];
                bandera = 1;
            } else {
                nuevaRuta = nuevaRuta + "&" + arrRuta[i];
            }

        }
        nuevaRuta = nuevaRuta + "&idIndiceNuevo=" + idInidceN;
        window.location.href = nuevaRuta;
    } else {
        var arrRuta = rutaActual.split("&");
        for (var i = 0; i <= 1; i++) {
            if (bandera === 0) {
                nuevaRuta = nuevaRuta + arrRuta[i];
                bandera = 1;
            } else {
                nuevaRuta = nuevaRuta + "&" + arrRuta[i];
            }

        }
        nuevaRuta = nuevaRuta + "&idIndiceNuevo=" + idInidceN;
        window.location.href = nuevaRuta;
    }
}