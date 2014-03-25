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
        $("#mcl1").attr("href", '../plantilla/index.php?alumno=no&idRelCursoGrupo='+ idRelCursoGrupo +'&idUnidad=' + arrIdUnidades[0]);
        $("#mcl1").attr("class", 'fancyy');
        $("#mc1").attr("src", rutaCompMapaCurso + "/1-a.png");
        $("#mcl2").attr("href", '../plantilla/index.php?alumno=no&idRelCursoGrupo='+ idRelCursoGrupo +'&idUnidad=' + arrIdUnidades[1]);
        $("#mcl2").attr("class", 'fancyy');
        $("#mc2").attr("src", rutaCompMapaCurso + "/2-a.png");
        $("#mcl3").attr("href", '../plantilla/index.php?alumno=no&idRelCursoGrupo='+ idRelCursoGrupo +'&idUnidad=' + arrIdUnidades[2]);
        $("#mcl3").attr("class", 'fancyy');
        $("#mc3").attr("src", rutaCompMapaCurso + "/3-a.png");
        $("#mcl4").attr("href", '../plantilla/index.php?alumno=no&idRelCursoGrupo='+ idRelCursoGrupo +'&idUnidad=' + arrIdUnidades[3]);
        $("#mcl4").attr("class", 'fancyy');
        $("#mc4").attr("src", rutaCompMapaCurso + "/4-a.png");
        $("#mcl5").attr("href", '../plantilla/index.php?alumno=no&idRelCursoGrupo='+ idRelCursoGrupo +'&idUnidad=' + arrIdUnidades[4]);
        $("#mcl5").attr("class", 'fancyy');
        $("#mc5").attr("src", rutaCompMapaCurso + "/5-a.png");
        $("#mcl6").attr("href", '../plantilla/index.php?alumno=no&idRelCursoGrupo='+ idRelCursoGrupo +'&idUnidad=' + arrIdUnidades[5]);
        $("#mcl6").attr("class", 'fancyy');
        $("#mc6").attr("src", rutaCompMapaCurso + "/6-a.png");

    }
    else
    {
        debugConsole('La rutaCompMapaCurso ='+rutaCompMapaCurso);
        $("#mcl1").attr("href", '../plantilla/index.php?alumno=si&idRelCursoGrupo='+ idRelCursoGrupo +'&idUnidad=' + arrIdUnidades[0]);
        $("#mc1").attr("src", rutaCompMapaCurso + "/1-a.png");
        if (typeof(arrIdUnidades[0])!== "undefined" && arrIdUnidades[0] !== '-' && arrIdUnidades[0] !==null){
            if (typeof(arrIdUnidades[1])!== "undefined" && arrIdUnidades[1] !== '-' && arrIdUnidades[1] !==null){
                $("#mcl2").attr("href", '../plantilla/index.php?alumno=si&idRelCursoGrupo='+ idRelCursoGrupo +'&idUnidad=' + arrIdUnidades[1]);
                $("#mc2").attr("src", rutaCompMapaCurso + "/2-a.png");
                if (typeof(arrIdUnidades[2])!== "undefined" && arrIdUnidades[2] !== '-' && arrIdUnidades[2] !==null){
                    $("#mcl3").attr("href", '../plantilla/index.php?alumno=si&idRelCursoGrupo='+ idRelCursoGrupo +'&idUnidad=' + arrIdUnidades[2]);
                    $("#mc3").attr("src", rutaCompMapaCurso + "/3-a.png");
                    if (typeof(arrIdUnidades[3])!== "undefined" && arrIdUnidades[3] !== '-' && arrIdUnidades[3] !==null){
                        $("#mcl4").attr("href", '../plantilla/index.php?alumno=si&idRelCursoGrupo='+ idRelCursoGrupo +'&idUnidad=' + arrIdUnidades[3]);
                        $("#mc4").attr("src", rutaCompMapaCurso + "/4-a.png");
                        if (typeof(arrIdUnidades[4])!== "undefined" && arrIdUnidades[4] !== '-' && arrIdUnidades[4] !==null){
                            $("#mcl5").attr("href", '../plantilla/index.php?alumno=si&idRelCursoGrupo='+ idRelCursoGrupo +'&idUnidad=' + arrIdUnidades[4]);
                            $("#mc5").attr("src", rutaCompMapaCurso + "/5-a.png");
                            if (typeof(arrIdUnidades[5])!== "undefined" && arrIdUnidades[5] !== '-' && arrIdUnidades[5] !==null){
                                $("#mcl6").attr("href", '../plantilla/index.php?alumno=si&idRelCursoGrupo='+ idRelCursoGrupo +'&idUnidad=' + arrIdUnidades[5]);
                                $("#mc6").attr("src", rutaCompMapaCurso + "/6-a.png");
                            }
                        }
                    }
                }
            }

        }
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
//            {
//                minWidth: '100%',
//                minHeight: '100%',
//                margin: '-20%',
//                'closeBtn': false,
//                border: '0',
//                'type': 'iframe',
//                padding: 0,
//                scrolling: 'no',
//                autoSize: false, // so the size will be 800x1200
//                autoCenter: false, // so fancybox will scroll down if needed
//                fitToView: false,
//                beforeShow: function() {
//                    $(".fancybox-skin").css("backgroundColor", "transparent");
//                    this.height = $(this.element).data("height");
//                }
//
//            });
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
    $('#mcPrincipal').css("height", $(window).innerWidth() * .52);///esta funciona bien cuando se abre al 100
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
