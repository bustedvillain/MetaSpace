/* 
 *Autor: Jose manuel Nieto Gomez
 *Fecha de creacion: 08 de Octubre del 2013
 *JQuery para funcionalidad en sesion de Administraodr
 */
//Control de cambios #&
//Autor:Omar Nava
//Objetivo: Left join a tabla de usuarios
//03-ene-2013

var contenido = {};
$(document).ready(function() {

//    $("#id_escuela").change(function(event) {
//        debugConsole("id_escuela change");
//        llenaSelectGruposDisponibles();
//    });
//
//
//
//    $("#idInstitucion").change(function(event) {
//        debugConsole("id_institucion change");
//        if ($("#enrolar").length)
//        {
////            if ($("#cargaAlumnos").val() === "1") {
////                llenaSelectGruposDisponibles();
////            }
//        }
//    });
    $("#confirmar").click(function() {
        if (confirm("Los cambios realizados no podrán revertirse, ¿Desea continuar?")) {
            $("#frmConfirmar").submit();
        }
    });
    $("#frmRelCursoGrupo").submit(function(event)
    {
//        alert('here');
        debugConsole("Relacionando curso-grupo...");
        //Antes de enviar el formulario selecciona las opciones
        seleccionaCombos("select-to");

        if (numeroSeleccionados("select-to") === 0) {
            debugConsole("Se cancelo el submit")
            alert('Debes seleccionar al menos un grupo');
            event.preventDefault();
        }
    });
    //inicia control de cambios #6
    /**
     * Cuando cambie el cmbo de escuela para cambiar sus grupos
     */
    $("#id_escuela").change(function() {
        var idEscuela = $("#id_escuela").val();
        llenaComboGrupos(idEscuela);
    });
    /**
     * Cuando cambie el combo, para cargar los grados escolares
     */
    $("#comboNivelEscolar").change(function() {
        var idNivel = $("#comboNivelEscolar").val();
        llenaComboGradoEscolar(idNivel);
        if ($("#comboGrupos").length) {
            llenaComboGrupos();
        }
    });   
    $("#comboGrupos").change(function() {
        $("#btnDescargar").attr("href", "descargaDatos.php?tipo=grupo" + "&idGrupo=" + $("#comboGrupos").val() + "&formato=" + $("#formatoDescarga").val());
        muestraTablaDescarga();
    });
    if ($("#comboNivelEscolar").length) {
        debugConsole('Si estuvo el comboNovelEscolar');
        llenaComboGradoEscolar($("#comboNivelEscolar").val());
    }

    $("#formatoDescarga").change(function() {
        $("#btnDescargar").attr("href", "descargaDatos.php?tipo=grupo" + "&idGrupo=" + $("#comboGrupos").val() + "&formato=" + $("#formatoDescarga").val());
    });

    //Combo de niveles educativos para edición de cursos scorm
    $("#comboNivelEscolarSco").change(function() {
        var idNivel = $("#comboNivelEscolarSco").val();
        llenaComboGradoEscolarSco(idNivel);
        if ($("#comboGrupos").length) {
            llenaComboGrupos();
        }
    });


});

function llenaComboGrupos(idEscuela) {
    debugConsole("En llenaComboGrupos");
    $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "comboGrupos", atributo: idEscuela}, function(respuesta) {
        debugConsole('res+' + respuesta + '+');

        $("#comboGrupos").empty();
        if (respuesta === "null") {
            debugConsole('datos nulos');
            $("#comboGrupos").append("<option>No hay grupos de ésta institución</option>");
            if (("#btnDescargar").length) {
                $("#btnDescargar").css("display", "none");
                $("#tablaDescarga").css("display", "none");
            }
        } else {
            debugConsole('si hubo');
            var datos = jQuery.parseJSON(respuesta);
            debugConsole(datos.valueOf());
            for (i = 0; i < datos.length; i++) {
                $("#comboGrupos").append("<option value='" + datos[i].id_grupo + "'>" + Encoder.htmlDecode(datos[i].nombre_grupo) + "</option>");
            }
            muestraTablaDescarga();
            $("#tablaDescarga").show();
            $("#btnDescargar").show();
            $("#btnDescargar").attr("href", "descargaDatos.php?tipo=grupo" + "&idGrupo=" + $("#comboGrupos").val() + "&formato=" + $("#formatoDescarga").val());
        }

    });
}
function muestraTablaDescarga() {
    var idGrupo = $("#comboGrupos").val();
    $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "tablaPorGrupo", atributo: idGrupo}, function(respuesta) {
        debugConsole(respuesta);
        $("#tablaDescarga tbody").html(respuesta);
    });

}
function descargaPorGrupo() {
    var idGrupo = $("#comboGrupos").val();
    var tipo = $("#formatoDescarga").val();
    $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "descargarUsuarios", idGrupo: idGrupo, tipo: tipo}, function(respuesta) {
        debugConsole(respuesta);
    });
}
/**
 * Llena el select de grupos seleccionadosal enrolar un grupo
 * @returns {undefined}
 */
function llenaSelectGruposSeleccionados(idCursoAbierto) {
    debugConsole('En llenaSelectGruposSeleccionados');
    //Vaciar listas

    //si el select existe
    if ($("#enrolar").length)
    {
        $(".grupo").html("");
        var idEscuela = $("#id_escuela").val();

        $.post("../sources/Controlador.Admin.Grupos.php", {consulta: "getGruposSeleccionados", atributo: idCursoAbierto}, function(respuesta)
        {
            $(".grupo2").html("");
            if (respuesta !== "null") {
                $("#noAlumnos").hide("fast");
                var datos = jQuery.parseJSON(respuesta);
                debugConsole(datos.valueOf());
                $(".grupo2").html("");
                for (i = 0; i < datos.length; i++) {
                    $("#select-to").append("<option value='" + datos[i].id_grupo + "'>" + datos[i].clave + " " + datos[i].nombre_grupo + "</option>");
                }

            } else {
                debugConsole("no hay alumnos");
                $("#noAlumnos").show("fast");
            }
            $(".cargando").html("");
        });

    }
}
/**
 * Funcion que recibe como parámetro un nivel y carga en el combo los niveles disponibles
 * @param {type} idNivel
 * @returns {undefined}
 */
function llenaComboGradoEscolar(idNivel) {
    debugConsole("En actualizaComboGradoEscolar");
    $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "combosGradoEscolar", atributo: idNivel}, function(respuesta) {
        if (respuesta !== "null") {
            debugConsole(respuesta);
            var datos = jQuery.parseJSON(respuesta);
            debugConsole(datos.valueOf());
            $("#comboGradoEscolares").empty();
            for (i = 0; i < datos.length; i++) {
                $("#comboGradoEscolares").append("<option value='" + Encoder.htmlDecode(datos[i].id_grado_escolar + "'>" + datos[i].nombre_grado) + "</option>");
            }
            if (cambioGrado) {
                debugConsole('Cambiando grado escolar' + idGradoEscolar);
                $("#comboGradoEscolares option[value=" + idGradoEscolar + "]").attr("selected", true);
                cambioGrado = false;
            }
        } else {
            $("#comboGradoEscolares").empty();
            $("#comboGradoEscolares").append("<option>Sin grados disponibles</option>");
        }

    });
}

/**
 * Funcion que recibe como parÃ¡metro un nivel y carga en el combo los niveles disponibles
 * @param {type} idNivel
 * @returns {undefined}
 */
function llenaComboGradoEscolarSco(idNivel) {
    debugConsole("En actualizaComboGradoEscolar");
    $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "combosGradoEscolar", atributo: idNivel}, function(respuesta) {
        if (respuesta !== "null") {
            debugConsole(respuesta);
            var datos = jQuery.parseJSON(respuesta);
            debugConsole(datos.valueOf());
            $("#comboGradoEscolaresSco").empty();
            for (i = 0; i < datos.length; i++) {
                $("#comboGradoEscolaresSco").append("<option value='" + Encoder.htmlDecode(datos[i].id_grado_escolar + "'>" + datos[i].nombre_grado) + "</option>");
            }
            if (cambioGrado) {
                debugConsole('Cambiando grado escolar' + idGradoEscolar);
                $("#comboGradoEscolaresSco option[value=" + idGradoEscolar + "]").attr("selected", true);
                cambioGrado = false;
            }
        } else {
            $("#comboGradoEscolaresSco").empty();
            $("#comboGradoEscolaresSco").append("<option>Sin grados disponibles</option>");
        }

    });
}

/**
 * Llena el select de alumnos disponibles al enrolar un grupo
 * @returns {undefined}
 */
function llenaSelectGruposDisponibles()
{
    debugConsole('En llenaSelectGruposDisponibles');
    //Vaciar listas

    //si el select existe
    if ($("#enrolar").length)
    {
        $(".grupo").html("");
        $(".grupo").empty();
//        $(".grupo2").empty();
        var idEscuela = $("#id_escuela").val();
        debugConsole('IdEscuela=' + idEscuela);
        $.post("../sources/Controlador.Admin.Grupos.php", {consulta: "getGruposPorEscuela", atributo: idEscuela}, function(respuesta)
        {
            $(".grupo").html("");
            if (respuesta !== "null") {
                $("#noAlumnos").hide("fast");
                var datos = jQuery.parseJSON(respuesta);
                debugConsole(datos.valueOf());
                $(".grupo").html("");
                for (i = 0; i < datos.length; i++) {
                    $("#select-from").append("<option value='" + datos[i].id_grupo + "'>" + datos[i].clave + " " + datos[i].nombre_grupo + "</option>");
                }

            } else {
                debugConsole("no hay alumnos");
                $("#noAlumnos").show("fast");
            }
            $(".cargando").html("");
        });

    }
}
function prepararJSONAdmin($atributo)
{
    debugConsole('jsoooon');
    if ($atributo.attr("type") !== "submit")
    {
        contenido[$atributo.attr("name")] = $atributo.val();
    }
    if ($atributo.attr("type") === "checkbox")
    {
        contenido[$atributo.attr("name")] = $("#" + $atributo.attr("name") + "1").is(":checked");
    }

}
function llenaModalLinkeo(idUnidad)
{


    $.post("../sources/ControladorPlantilla.php", {ins: "infoParaModalLinkeo", idUnidad: idUnidad}, function(respuesta) {
        debugConsole('heree' + respuesta);
        $("#frmLinkeo").html(respuesta);
        $("#divCargando").css("display", "none");
    });

}
function llenaModalLinkeoEditar(idUnidad)
{

    $.post("../sources/ControladorPlantilla.php", {ins: "infoParaModalLinkeoEditar", idUnidad: idUnidad}, function(respuesta) {
        $("#frmLinkeo").html(respuesta);
        $("#divCargando").css("display", "none");
    });

}
function guardaLinkeo()
{

    $("#divCargando").show('fast');
    $("#divCargandoo2").show('fast');
    debugConsole('chambeo');
    $("form#frmLinkeo :input").each(function() {
        prepararJSONAdmin($(this));
    });
    if ($("#operacion").val() === "nuevo")
        contenido['ins'] = "linkeoConSerie";
    else
        contenido['ins'] = "editaConSerie";
    debugConsole('operacion->' + $("#operacion").val());
//    $.each(contenido, function ()
//    {
//        debugConsole('asd');
//        debugConsole(this);
//    });
    debugConsole(contenido.valueOf())
    $.post("../sources/ControladorPlantilla.php", contenido, function(respuesta) {
        $("#divCargandoo2").css("display", "none");
        alert(respuesta);
        if (respuesta.toString() === 'Datos actualizados correctamente')
        {
            location.reload();
        }
    });
    $("#divCargando").css("display", "none");


}

//Finaliza control de cambios #6

///////////Retirar cuando se pase con manolo
/**
 * Selecciona todas la opciones de un combo multiple
 * @param {type} id
 * @returns {undefined}
 */
//    function seleccionaCombos(id) {
//        //Preparar opciones seleccionadas
//        var option = document.getElementById(id);
//        for (i = 0; i < option.length; i++) {
//            debugConsole("Seleccionando:" + option.options[i].text);
//            option.options[i].selected = true;
//        }
//    }
//
//    /**
//     * Verifica cuantas opciones han sido seleccionadas en un combo multiple
//     * @param {type} id
//     * @returns {Number}
//     */
//    function numeroSeleccionados(id) {
//        var option = document.getElementById(id);
//        var nSeleccionados = 0;
//        for (i = 0; i < option.length; i++) {
//            debugConsole("Seleccionando:" + option.options[i].text);
//            if (option.options[i].selected) {
//                nSeleccionados++;
//            }
//        }
//        return nSeleccionados;
//    }
