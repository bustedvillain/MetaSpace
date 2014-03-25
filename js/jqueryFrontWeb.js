$(document).ready(function() {
    
    
    if ($("#MensajeGrupo").length) {
        generarAlumnos($("#MensajeGrupo").val());
    }
    if ($("#MensajeGrupo2").length) {
        generarPadres($("#MensajeGrupo2").val());
    }
                
     $("#MensajeGrupo").change(function() 
     {
        var idSeleccionado = $(this).val();
        generarAlumnos(idSeleccionado);
     });
     
     $("#MensajeGrupo2").change(function() 
     {
        var idSeleccionado = $(this).val();
        generarPadres(idSeleccionado);
     });
    
    
    function generarAlumnos(idSeleccionado)
    {
        $.post("../../sources/ControladorGrupos.php", {idGrupo:idSeleccionado,tipo:'alumno'}, function(respuesta) {
        debugConsole('res+' + respuesta + '+');

        $("#ContactoAlumno").empty();
        if (respuesta === "null") {
            debugConsole('datos nulos');
            $("#ContactoAlumno").append("<option>No hay alumnos en este grupo</option>");
        } else {
            
            var datos = jQuery.parseJSON(respuesta);
            
            for (i = 0; i < datos.length; i++) {
                $("#ContactoAlumno").append("<option value='" + datos[i].id + "'>" + (datos[i].nombre) + "</option>");
            }
           
        }

        });
    }
    function generarPadres(idGrupo)
    {
        $.post("../../sources/ControladorGrupos.php", {idGrupo:idGrupo,tipo:'padre'}, function(respuesta) {
        debugConsole('res+' + respuesta + '+');

        $("#ContactoPadre").empty();
        if (respuesta === "null") {
            debugConsole('datos nulos');
            $("#ContactoPadre").append("<option>No hay alumnos en este grupo</option>");
        } else {
            
            var datos = jQuery.parseJSON(respuesta);
            
            for (i = 0; i < datos.length; i++) {
                $("#ContactoPadre").append("<option value='" + datos[i].id + "'>" + (datos[i].nombre) + "</option>");
            }
           
        }

        });
    }
    
    
    
    
    
    
});