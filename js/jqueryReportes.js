//$(document).ready(function() 
//{
////    var cambioEscuela = false;
////    
////    
////    $("#tipo_grupoR").change(function(event) {
////        debugConsole("tipo_grupo:" + $(this).val());
////        if ($(this).val() === "0") {
////            debugConsole("tipo_grupo: grupo estudiantes")
////            $(".grupo_estudiante").show("fast");
////            $(".grupo_profesionista").hide("fast");
////        } else if ($(this).val() === "1") {
////            debugConsole("tipo_grupo: grupo profesionistas");
////            $(".grupo_estudiante").hide("fast");
////            $(".grupo_profesionista").show("fast");
////        }
////        
////    });
////    
////    
////    $("#idInstitucionR").change(function(event) {
////        document.getElementById("id_escuelaR").length = 0;
////        debugConsole("change institucion");
////        var idInstitucion = $(this).val();
////        debugConsole("idInstitucion:" + idInstitucion);
////
////        $.post("../sources/ControladorAdmin.Catalogos.php", {consulta: "getEscuelas", idAtributo: idInstitucion}, function(respuesta) {
////            if (respuesta) {
////                debugConsole(respuesta);
////                var escuelas = jQuery.parseJSON(respuesta);
////
////                for (i = 0; i < escuelas.length; i++) {
////                    debugConsole("escuela:" + escuelas[i].nombre_escuela);
////                    $("<option value='" + escuelas[i].id_escuela + "'>" + Encoder.htmlDecode(escuelas[i].nombre_escuela) + "</option>").appendTo("#id_escuelaR");
////                }
////
////                if (cambioEscuela) {
////                    debugConsole("Cambiando escuela");
////                    $("#d_escuelaR option[value=" + id_escuela + "]").attr("selected", true);
////                    cambioEscuela = false;
////                }
////
////                
////            } else {
////                $("<option>Esta institución no tiene escuelas.</option>").appendTo("#id_escuelaR");
////                //Vacia listas para la seccion de enrolamiento
////                debugConsole("Vaciar listas");
////                $(".grupo").html("");
////            }
////            $("#id_escuelaR").change();
////        });
////    });
////     $("#idInstitucionR").change();
////    
////    
////    $("#id_escuelaR").change(function(event) {
////        document.getElementById("id_grupoR").length = 0;
////        debugConsole("change escuela");
////        
////        //las empresas no aplican
////        
////        var idEscuela = $(this).val();
////        
////        if(idEscuela===null)
////        {
////             debugConsole($( "#id_escuelaR" ).val());
////        }
////        
////        debugConsole("id_EscuelaS:" + idEscuela);
////
////        $.post("../sources/ControladorReportes.php", {consulta:'getGrupos',idEscuelaEmpresa:idEscuela}, function(respuesta) {
////            if (respuesta) {
////                debugConsole(respuesta);
////                var grupos = jQuery.parseJSON(respuesta);
////
////                for (i = 0; i < grupos.length; i++) {
////                    debugConsole("escuela:" + grupos[i].nombre_escuela);
////                    $("<option value='" + grupos[i].id_escuela + "'>" + Encoder.htmlDecode(grupos[i].nombre_escuela) + "</option>").appendTo("#id_grupoR");
////                }
////
//////                if (cambioEscuela) {
//////                    debugConsole("Cambiando escuela");
//////                    $("#d_escuelaR option[value=" + id_escuela + "]").attr("selected", true);
//////                    cambioEscuela = false;
//////                }
////
////                
////            } else {
////                $("<option> No se Tiene Grupos asignados en esta escuela </option>").appendTo("#id_grupoR");
////                //Vacia listas para la seccion de enrolamiento
////                debugConsole("Vaciar listas");
////                //$(".grupo").html("");
////            }
////        });
////    });
//     
//    
//    
//    
//    
//});

