$(document).on("ready", inicio);
function inicio()
{
    eventosFancy();
    mcIconografia();
    ligasBotonesMc();
    $(window).resize(function()
    {
        acomodaMapaOnResize();
    });
}
function ligasBotonesMc()
{

    if (alumno === "no")
    {
        /**
         * CHANGE CONTRO 1.1.0
         * AUTOR: JOSE MANUEL NIETO GOMEZ
         * FECHA DE MOFIFICACION: 20 DE JUNIO DE 2014
         * OBJETIVO: 1.OPTIMIZAR CODIGO CON UN CICLO QUE GENERE LOS LINKS EN LOS BLOQUES
         *           2.VALIDAR QUE HAYA UN ID DE CURSO PARA HABILITAR EL BOTON
         */
        //NUEVO CODIGO: RECORRE LAS UNIDADES, VALIDA SI ESTA DEFINIDO EL ID DE CURSO PARA HABILITAR EL BOTON 
        for (i = 0; i < 6; i++) {
            if (typeof (arrIdUnidades[i]) !== "undefined" && arrIdUnidades[i] !== '-' && arrIdUnidades[i] !== null) {
                //$("#mcl" + (i + 1)).attr("href", '../plantilla/index.php?alumno=no&idRelCursoGrupo=' + idRelCursoGrupo + '&idUnidad=' + arrIdUnidades[i]);
                //$("#mcl" + (i + 1)).attr("class", 'fancyy');
                $("#mc" + (i + 1)).attr("src", rutaCompMapaCurso + "/" + (i + 1) + "-a.png");

                //Evento de click para abrir pantalla completa
                $("body").on("click", "#mc" + (i + 1), function() {
                    //launchFullscreen(document.documentElement);
                });
            }
        }
    }
    else
    {
        if (tipoEjecucion == "1") { //Ejecucion seriada, lo hace como antes
            debugConsole("EJECUCION DE CURSO CON SERIACION DE BLOQUES")
            debugConsole('La rutaCompMapaCurso =' + rutaCompMapaCurso);
            console.log(arrIdUnidades);
            
            //VALIDACION CON RECURSION
            habilitaSeriacionBloques();

        } else {//Ejecucion autonoma/libre
            debugConsole("EJECUCION DE CURSO DE FORMA AUTONOMA/LIBRE");
            for (i = 0; i < 6; i++) {
                    debugConsole("EJECUCION inactivo");
                    debugConsole(arrIdUnidades[i]);
                if (typeof (arrIdUnidades[i]) !== "undefined" && arrIdUnidades[i] !== '-' && arrIdUnidades[i] !== null) {
                    debugConsole("EJECUCION activo");
                    debugConsole(arrIdUnidades[i]);
//                    $("#mcl" + (i + 1)).attr("href", '../plantilla/index.php?alumno=no&idRelCursoGrupo=' + idRelCursoGrupo + '&idUnidad=' + arrIdUnidades[i]);
                    if (frontweb === true) {
                        //$("#mcl" + (i + 1)).attr("href", '../../plantilla/index.php?alumno=si&idRelCursoGrupo=' + idRelCursoGrupo + '&idUnidad=' + arrIdUnidades[i]);
                    } else {
                        //$("#mcl" + (i + 1)).attr("href", '../plantilla/index.php?alumno=no&idRelCursoGrupo=' + idRelCursoGrupo + '&idUnidad=' + arrIdUnidades[i]);
                    }

                    //$("#mcl" + (i + 1)).attr("class", 'fancyy');
                    $("#mc" + (i + 1)).attr("src", rutaCompMapaCurso + "/" + (i + 1) + "-a.png");

                    //Evento de click para abrir pantalla completa
                    $("body").on("click", "#mc" + (i + 1), function() {
                        //launchFullscreen(document.documentElement);
                    });
                }else if(arrIdUnidades[i] == '-'){
                    $("#mcl"+ (i + 1)).removeAttr("href");
                }else if(arrIdUnidades[i] === undefined){
                    $("#mcl"+ (i + 1)).removeAttr("href");
                }
            }
        }
    }
}

/**
 * Contador para validar el numero de recursiones del metodo "habilitaSeriacionBloques()"
 * @type Number
 */
var contadorBloques = 0;

/**
 * Función para validar la seriación de bloques
 * FUNCION RECURSIVA, si el bloque actual esta activo, verifica el contador de bloques y se llama de nuevo así mismo.
 * La función termina cuando encuentra un bloque inactivo, o si ha alcanzado el máximo de recursiones/bloques
 * @returns {undefined}
 */
function habilitaSeriacionBloques() {
    if (typeof (arrIdUnidades[contadorBloques]) !== "undefined" && arrIdUnidades[contadorBloques] !== '-' && arrIdUnidades[contadorBloques] !== null) {
        if (frontweb === true) {
            //$("#mcl" + (contadorBloques + 1)).attr("href", '../../plantilla/index.php?alumno=si&idRelCursoGrupo=' + idRelCursoGrupo + '&idUnidad=' + arrIdUnidades[contadorBloques]);
        } else {
            //$("#mcl" + (contadorBloques + 1)).attr("href", '../plantilla/index.php?alumno=si&idRelCursoGrupo=' + idRelCursoGrupo + '&idUnidad=' + arrIdUnidades[contadorBloques]);
        }

        $("#mc" + (contadorBloques + 1)).attr("src", rutaCompMapaCurso + "/" + (contadorBloques + 1) + "-a.png");
        //$("#mcl" + (contadorBloques + 1)).attr("class", 'fancyy');
        $("body").on("click", "#mc" + (contadorBloques + 1), function() {
            //launchFullscreen(document.documentElement);
        });

    }else if(arrIdUnidades[contadorBloques] == '-'){
        $("#mcl"+ (contadorBloques + 1)).removeAttr("href");
    }else if(arrIdUnidades[contadorBloques] == 'undefined'){
        $("#mcl"+ (contadorBloques + 1)).removeAttr("href");
    }
    //RECURSIVIDAD
    contadorBloques++;
    if (contadorBloques < 6) {
        habilitaSeriacionBloques();
    }
}


function acomodaMapaOnResize()
{
    if ($(window).innerWidth() < $(window).innerHeight())
    {
        $("#mcPrincipal").css("margin-top", "40%");
    }
    else
    {
        $("#mcPrincipal").css("margin-top", "0%");
    }
}
function eventosFancy()
{
    $('.fancyy').fancybox(
            {
                minWidth: '100%',
                minHeight: '100%',
                margin: '-20%',
                'closeBtn': false,
                border: '0',
                'type': 'iframe',
                padding: 0,
                scrolling: 'no',
                autoSize: false, // so the size will be 800x1200
                autoCenter: false, // so fancybox will scroll down if needed
                fitToView: false,
                beforeShow: function() {
                    $(".fancybox-skin").css("backgroundColor", "transparent");
                    this.height = $(this.element).data("height");
                }

            });
    function cierraFancy()
    {
        debugConsole('cerrando fancy');
        $.fancybox.close();
    }
    debugConsole('cargamos eventos fancy');
}
function mcIconografia()
{
    $('#mcPrincipal').css("background-image", "url(" + rutaCompMapaCurso + "/fondoMC.png)");

    if ($("#mapa").height()) {
        if (alumno === "si") {
            $('#mcPrincipal').css("height", $("#mapa").height());
        } else {
            $('#mcPrincipal').css("height", $("#mapa").height() - 80);
        }
    } else {
        $('#mcPrincipal').css("height", $(window).innerWidth() * .52);///esta funciona bien cuando se abre al 100

    }
    //    $('#mcPrincipal').css("height", $(window).innerWidth() * .32);
    $('#mc1').attr("src", rutaCompMapaCurso + "/1-b.png");
    $('#mc2').attr("src", rutaCompMapaCurso + "/2-b.png");
    $('#mc3').attr("src", rutaCompMapaCurso + "/3-b.png");
    $('#mc4').attr("src", rutaCompMapaCurso + "/4-b.png");
    $('#mc5').attr("src", rutaCompMapaCurso + "/5-b.png");
    $('#mc6').attr("src", rutaCompMapaCurso + "/6-b.png");
    $('#mc6').css("margin-bottom", "0%");
    $('#mc6').css("float", "bottom");
    acomodaMapaOnResize();
//    $('#mcPrincipal').css("height", $(window).innerHeight());
}
