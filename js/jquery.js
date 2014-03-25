/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var debug = true;
var tiempoSesion = 0;
var tiempoMaxSesion = 20;
var fw = false;

$(document).ready(function() {
    $("#login").click(function(event) {
        var usuario = $("#usuario").val();
        switch (usuario) {
            case "admin":
                document.location = "admin/index.php";
                break;
            case "padre":
                document.location = "padre/index.php";
                break;
            case "profesor":
                document.location = "profesor/index.php";
                break;
            case "jr":
                document.location = "jr/index.php";
                break;
            case "senior":
                document.location = "senior/index.php";
                break;
            case "coord":
                document.location = "coord/index.php";
                break;

        }
    });

    var progress = 0;
    $("#form").click(function(event) {
        debugConsole("Progress");
        avance();
    });

    function avance() {
        var val = progress + "%";
        debugConsole("val:" + val);
        $("#progress").css("width", val);
        progress += 10;
        if (progress < 125) {
            setTimeout(avance, 500);
        } else {
            $("#masive").submit();
        }
    }

    $('#btn-add').click(function() {
        $('#select-from option:selected').each(function() {
            $('#select-to').append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
            $(this).remove();
        });
    });
    $('#btn-remove').click(function() {
        $('#select-to option:selected').each(function() {
            $('#select-from').append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
            $(this).remove();
        });
    });

    $(".btn-add").click(function() {
        var id = $(this).attr("name");
        $('#select-from' + id + ' option:selected').each(function() {
            $('#select-to' + id).append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
            $(this).remove();
        });
    })

    $('.btn-remove').click(function() {
        var id = $(this).attr("name");
        $('#select-to' + id + ' option:selected').each(function() {
            $('#select-from' + id).append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
            $(this).remove();
        });
    });

    $('#btn-add2').click(function() {
        $('#select-from2 option:selected').each(function() {
            $('#select-to2').append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
            $(this).remove();
        });
    });
    $('#btn-remove2').click(function() {
        $('#select-to2 option:selected').each(function() {
            $('#select-from2').append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
            $(this).remove();
        });
    });

    $('#btn-add3').click(function() {
        $('#select-from3 option:selected').each(function() {
            $('#select-to3').append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
            $(this).remove();
        });
    });
    $('#btn-remove3').click(function() {
        $('#select-to3 option:selected').each(function() {
            $('#select-from3').append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
            $(this).remove();
        });
    });


    //Increment the idle time counter every minute.
    tiempoSesion = setInterval("timerIncrement()", 60000); // 1 minute

    //Zero the idle timer on mouse movement.
    $(this).mousemove(function(e) {

        tiempoSesion = 0;
    });
    $(this).keypress(function(e) {
        tiempoSesion = 0;
    });
    
 
//    $("a").each(function(event){
//        var link = $(this).attr("href");
//        console.log("Evento mouse link a liga:"+link);
//        $(this).attr("redireccion", link);
//        $(this).attr("href", "javascript:void(0)");  
//    });
//    
//    $("body").on("mouseover mouseleave mouseout", "a", function(event) {
//        console.log("Metaspace link...");
//        window.status='';
//        return true;
//    });


});
function debugConsole(text){
    if(debug === true){
        console.log(text);
    }
}

function timerIncrement() {
//    console.log('vale=' + tiempoSesion);
    tiempoSesion = tiempoSesion + 1;
    if (tiempoSesion >= tiempoMaxSesion) { // 20 minutes
//        debugConsole('aaaa');
        alert('Tiempo de sesión agotado');
        var rutaI = window.location.href;
        if(rutaI.indexOf("frontweb") != -1){
            window.location.href = "../../logout.php";
        }else{
            window.location.href = "../logout.php";
        }
        
    }
}

function verPerfilPropio(id){
    debugConsole("id:"+id);
//    $.post("../sources/ControladorSenior.php", {ins: "verPerfil", idTutor: id}, function(respuesta){
      $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "consultaDatosPersonales", atributo: id}, function(respuesta) {
        if (respuesta) {
            debugConsole("Respuesta");
            debugConsole(respuesta);
            var datos = jQuery.parseJSON(respuesta);
           
            $("#vpIdTutor").val(datos.idTutor);
            $("#vpIdDatosPersonales").val(Encoder.htmlDecode(datos.id_datos_personales));
            $("#vpNombreUsuario").val(Encoder.htmlDecode(datos.nombre_usuario));
            $("#vpCorreo").val(Encoder.htmlDecode(datos.correo));
//            $("#vpContrasena").val(datos.contrasena);
            $("#vpNombre").val(Encoder.htmlDecode(datos.nombre_pila));
            $("#vpApellidoPaterno").val(Encoder.htmlDecode(datos.primer_apellido));
            $("#vpApellidoMaterno").val(Encoder.htmlDecode(datos.segundo_apellido));
//        $("#vpApellidos").val(objTutor.apellidos);
            $("#vpFechaNacimiento").val(Encoder.htmlDecode(datos.fecha_nacimiento));
            $("#vpCurp").val(Encoder.htmlDecode(datos.curp));
            $("#vpCodigoPostal").val(Encoder.htmlDecode(datos.codigo_postal));
            $("#vpCalle").val(Encoder.htmlDecode(datos.calle));
            $("#vpNoCasaExt").val(Encoder.htmlDecode(datos.no_casa_ext));
            $("#vpNoCasaInt").val(Encoder.htmlDecode(datos.no_casa_int));
            $("#vpColoniaLocalidad").val(Encoder.htmlDecode(datos.colonia_localidad));
            $("#vpDelegacionMunicipio").val(Encoder.htmlDecode(datos.delegacion_municipio));
            $("#vpEntidadFederativa").val(Encoder.htmlDecode(datos.id_entidad_federativa));
            $("#vpNacionalidad").val(Encoder.htmlDecode(datos.id_nacionalidad));
            $("#vpZonaHoraria").val(Encoder.htmlDecode(datos.zona_horaria));
            $("#vpTelefonoFijo").val(Encoder.htmlDecode(datos.telefono_fijo));
            $("#vpTelefonoMovil").val(Encoder.htmlDecode(datos.telefono_movil));
        } else {
            debugConsole("No hay respuesta");
        }

    });
}