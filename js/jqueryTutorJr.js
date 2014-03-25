$(document).on("ready", inicio);
function inicio()
{
    $("#btnActivarEdicion").click(function()
    {
        $("form#frmPerfil :input").each(function() {    
            $(this).removeAttr("disabled");
            $("#cmdGuardarPerfil").show("fast");
            $("#avisoCambios1").show("fast");
            $("#avisoCambios2").show("fast");
        });
    });
    $("#cmdCerrarPerfil").click(function()
    {
        $("form#frmPerfil :input").each(function() {   
            $(this).attr("disabled","diabled");
            $("#cmdGuardarPerfil").css("display","none");
            $("#avisoCambios1").css("display","none");
            $("#avisoCambios2").css("display","none");
        });
    });
}

