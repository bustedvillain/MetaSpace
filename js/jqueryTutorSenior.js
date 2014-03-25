$(document).on("ready", inicio);
var alumno = {};
function inicio()
{

    $("#btnActivarEdicion").click(function()
    {
        $("form#frmPerfil :input").each(function() {
            $(this).removeAttr("disabled");
            $("#cmdGuardarPerfil").show("fast");
            $("#avisoCambios1").show("fast");
            $("#avisoCambios2").show("fast");
            $("#labelContrasena2").show("fast");
            $("#contrasena2").show("fast");
        });
    });
    $("#cmdCerrarPerfil").click(function()
    {
        $("form#frmPerfil :input").each(function() {
            $(this).attr("disabled", "diabled");
            $("#cmdGuardarPerfil").css("display", "none");
            $("#avisoCambios1").css("display", "none");
            $("#avisoCambios2").css("display", "none");
            $("#labelContrasena2").css("display", "none");
            $("#contrasena2").css("display", "none");
        });
    });

    $('#frmPerfil').submit(enviarDatosPerfilPropio);
}

function verPerfilPorTutor(id)
{
    $.post("../sources/ControladorSenior.php", {ins: "verPerfil", idTutor: id}, function(respuesta)
    {
        var objTutor = jQuery.parseJSON(respuesta);
        $("#vtIdTutor").html(objTutor.idTutor);
//        $("#vtIdDatosPersonales").html(objTutor.idDatosPersonales);
        $("#vtNombreUsuario").html(objTutor.nombreUsuario);
        $("#vtCorreo").html(objTutor.correo);
        $("#vtNombre").html(objTutor.nombre);
        $("#vtApellidos").html(objTutor.apellidos);
        $("#vtFechaNacimiento").html(objTutor.fechaNacimiento);
        $("#vtCurp").html(objTutor.curp);
        $("#vtCodigoPostal").html(objTutor.codigoPostal);
        $("#vtCalle").html(objTutor.calle);
        $("#vtNoCasaExt").html(objTutor.noCasaExt);
        $("#vtNoCasaInt").html(objTutor.noCasaInt);
        $("#vtColoniaLocalidad").html(objTutor.coloniaLocalidad);
        $("#vtDelegacionMunicipio").html(objTutor.delegacionMunicipio);
        $("#vtNombreEntidad").html(objTutor.nombreEntidad);
        $("#vtNacionalidad").html(objTutor.nacionalidad);
        $("#vtZonaHoraria").html(objTutor.zonaHoraria);
        $("#vtTelefonoFijo").html(objTutor.telefonoFijo);
        $("#vtTelefonoMovil").html(objTutor.telefonoMovil);
    });
}


function verActividadPorTutor(id)
{
    $.post("../sources/ControladorSenior.php", {ins: "verActividadTutor", idTutor: id}, function(respuesta)
    {
        $("#tablaActividadTutor").html(respuesta);
    });
}


function prepararJSON($atributo)
{
    if ($atributo.attr("type") !== "submit")
    {
        alumno[$atributo.attr("name")] = $atributo.val();
    }
    if ($atributo.attr("type") === "checkbox")
    {
        alumno[$atributo.attr("name")] = $("#" + $atributo.attr("name") + "1").is(":checked");
    }
}

function enviarDatosPerfilPropio()
{
    debugConsole('mirame');
    $("form#frmPerfil :input").each(function() {
        prepararJSON($(this));
    });
    //debugConsole(alumno);

    alumno['ins'] = "modificaPerfil";
    debugConsole('iddat--' + alumno['idDatosPersonales']);
    debugConsole('nombre--' + alumno['nombre']);
    debugConsole(alumno);
    $.post("../sources/ControladorSenior.php", alumno, function(respuesta) {
        alert(respuesta);
        if (respuesta.toString() === 'Datos actualizados correctamente')
        {
            location.reload();
        }
    });
}
