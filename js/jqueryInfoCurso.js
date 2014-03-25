//   Date             Modified by         Change(s)
//   2013-11-13         HMP                 1.0

/*
 * Funciones JS que se cargan a todos los tutores 
 * Junior, Senior, Coordinador
 */
$(document).ready(function() {
    
    function getDatosCursoJSON(idAtributo, tipo_funcion) {
        debugConsole("getDatosCusoJSON");
        var datos;
        $.post("../sources/ControladorAdmin.Cursos.php", {consulta: "consultaInfoCurso", atributo: idAtributo}, function(datos) {
            debugConsole("Dentro del post");
            debugConsole(datos);
            if (datos) {
                datos = jQuery.parseJSON(datos);
                debugConsole("La informacion esta lista!");
                switch (tipo_funcion) {
                    case "ver":
                        setVerDatosCurso(datos);
                        break;
                    case "editar":
                        setEditarDatosCurso(datos);
                        break;
                }
            }

        });

        return datos;
    }
    
    function setVerDatosCurso(datos) {
        $(".cargando").html("");
        debugConsole("setVerDatosCurso");

        $("#ver_curso_moodle").html(datos.curso_moodle).text();
        $("#ver_gestor").html(datos.gestor).text();
        $("#ver_clave_curso").html(datos.clave_curso).text();
        $("#ver_nombre_curso").html(datos.nombre_curso).text();
        $("#ver_nombre_corto").html(datos.nombre_corto).text();
        $("#ver_categoria").html(datos.nombre_categoria).text();
        $("#ver_asignatura").html(Encoder.htmlDecode(datos.nombre_asignatura)).text();
        $("#ver_nivel_escolar").html(datos.nivel).text();

        $("#ver_topicos").html("");
        
        if(datos.topicos===null)
        {
            return ;
        }
        for (i = 0; i < datos.topicos.length; i++) {
            //Titulo
            $("#ver_topicos").append("<tr>");
            $("#ver_topicos").append("<td><b>Unidad " + datos.topicos[i].no_unidad + "</b></td><td></td>");
            $("#ver_topicos").append("</tr>");

            //Nombre de la unidad
            $("#ver_topicos").append("<tr>");
            $("#ver_topicos").append("<td>Nombre Unidad:</td>");
            $("#ver_topicos").append("<td>" + datos.topicos[i].nombre_unidad + "</td>")
            $("#ver_topicos").append("</tr>");

            //Descripcion de la unidad
            $("#ver_topicos").append("<tr>");
            $("#ver_topicos").append("<td>Descripción:</td>");
            $("#ver_topicos").append("<td>" + datos.topicos[i].descripcion + "</td>")
            $("#ver_topicos").append("</tr>");

            //Estatus de la unidad
            $("#ver_topicos").append("<tr>");
            $("#ver_topicos").append("<td>Estatus</td>");
            var status = "";
            if (datos.topicos[i].status === "1") {
                status = "<p class='text-success'><b>Activo.</b></p>";
            } else {
                status = "<p class='text-error'><b>Inactivo.</b></p>";
            }
            $("#ver_topicos").append("<td>" + status + "</td>")
            $("#ver_topicos").append("</tr>");

            //Ver contenido
//            $("#ver_topicos").append("<tr>");
//            $("#ver_topicos").append("<td>Contenido HTML5</td>");
//            if (datos.topicos[i].url_unidad != null) {
//                $("#ver_topicos").append("<td><a href='" + datos.topicos[i].url_unidad + "' target='_blank'>Ver Contenido</a></td>")
//            } else {
//                $("#ver_topicos").append("<td></td>")
//            }
//
//            $("#ver_topicos").append("</tr>");

        }

    }
    
    
    $(".verCursoVistaTutor").click(function(event) {
        $(".cargando").html("<h4><b>Cargando datos...</b><img src='../img/loading.gif' width='30'></h4>");
        debugConsole("verCurso");
        var idAtributo = $(this).attr("name");
        debugConsole("Getting datos curso:" + idAtributo);
        var datos = getDatosCursoJSON(idAtributo, "ver");
    });
    
});
