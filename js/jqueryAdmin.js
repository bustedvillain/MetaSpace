/* 
 *Autor: Jose manuel Nieto Gomez
 *Fecha de creacion: 08 de Octubre del 2013
 *JQuery para funcionalidad en sesion de Administraodr
 */
//Control de cambios #3
//Funciones para devolver la fecha del servidor

//Inicia control de cambios #3
var tiempo = "";
//FInaliza control de cambios #3

/**
 * CHANGE CONTROL 0.99.6
 * FECHA DE MODIFICACION: 30 DE DICIEMBRE DEL 2013
 * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
 * OBJETIVO: CREAR EVENTO PARA EDICION DE GRADOS ESCOLARES
 */

/**
 * CHANGE CONTROL 0.99.9
 * FECHA DE MODIFICACION: 23 DE ENERO DEL 2014
 * AUTOR: JOSE MANUEL NIETO GOMEZ
 * OBJETIVO: IMPELEMENTAR LA FUNCION DE ESTANDARIZACION DE FECHAS
 * 
 */

var cambioGrado, idGradoEscolar;
//Encoder
Encoder.EncodeType = "entity";

$(document).ready(function() {
    var fechaValida = fechaServidor(0, 0, -3);
    //Inicia control de cambios 3
    /**
     * Fecha de modificación: 17 de Diciember del 2013
     * Autor: Omar Nava Pulido
     * Objetivo: Consulta la fecha del servidor y puede sumar o restar
     * dias, meses y años
     * @param {type} dias
     * @param {type} meses
     * @param {type} anyos
     * @returns {undefined}
     */
    function fechaServidor(dias, meses, anyos) {
        var algo = 'fechaServidor';
        var res = "---";
        $.post("../sources/ControladorFechas.php", {ins: algo, dias: dias, meses: meses, anyos: anyos}, function(respuesta) {
            debugConsole('Res=' + respuesta);
            if (respuesta) {
                var tiempo = respuesta;
                tiempo = tiempo.trim();
//            alert(tiempo);
                renderDatePicker(tiempo);
                return tiempo;
            }
        });

    }
//Finaliza control de cambio 3


    /**
     * Funcion generica para editar nombres en catalogos:
     * Habilidades
     * Asignaturas
     * Categorias
     * Instituciones
     * Empresas
     * Niveles Educativos
     * Grados Escolares
     * Nacionalidades
     * Carga el nombre del campo para poderla editar
     */
    $(".editaAtributo").click(function(event) {
        var idAtributo = $(this).attr("id");
        var nombreConsulta = $(this).attr("name");
        debugConsole("Getting idAtributo:" + idAtributo);
        $.post("../sources/ControladorAdmin.php", {consulta: nombreConsulta, idAtributo: idAtributo}, function(respuesta) {
            debugConsole(respuesta);
            var atributo = jQuery.parseJSON(respuesta);
            $("#editaAtributo").val(Encoder.htmlDecode(atributo.nombre_atributo));
            $("#idAtributo").val(atributo.id_atributo);
            debugConsole("Campos actualizados");
        });
    });

    $(".editaEscuela").click(function(event) {
        var idAtributo = $(this).attr("id");
        debugConsole("Getting idAtributo:" + idAtributo);
        $.post("../sources/ControladorAdmin.Catalogos.php", {consulta: "getEscuela", idAtributo: idAtributo}, function(respuesta) {
            debugConsole(respuesta);
            var atributo = jQuery.parseJSON(respuesta);
            $("#editaAtributo").val(Encoder.htmlDecode(atributo.nombre_escuela));
            $("#idAtributo").val(atributo.id_escuela);
            $("#idInstitucion option[value=" + atributo.id_institucion + "]").attr("selected", true);
            //ubicarCombo("", atributo)
            debugConsole("Campos actualizados");
        });
    });

    function guardaLinkeo()
    {
        $("#divCargandoo").show('fast');

    }
    function llenaModalLinkeo(idUnidad)
    {

        $.post("../sources/ControladorPlantilla.php", {ins: "infoParaModalLinkeo", idUnidad: idUnidad}, function(respuesta) {
            $("#frmLinkeo").html(respuesta);
            $("#divCargando").css("display", "none");
        });

    }
    $("#idInstitucion").change(function(event) {
        document.getElementById("id_escuela").length = 0;
        debugConsole("change institucion");
        var idInstitucion = $(this).val();
        debugConsole("idInstitucion:" + idInstitucion);

        $.post("../sources/ControladorAdmin.Catalogos.php", {consulta: "getEscuelas", idAtributo: idInstitucion}, function(respuesta) {
            if (respuesta) {
                debugConsole(respuesta);
                var escuelas = jQuery.parseJSON(respuesta);

                for (i = 0; i < escuelas.length; i++) {
                    debugConsole("escuela:" + escuelas[i].nombre_escuela);
                    $("<option value='" + escuelas[i].id_escuela + "'>" + Encoder.htmlDecode(escuelas[i].nombre_escuela) + "</option>").appendTo("#id_escuela");
                }
                if ($("#comboGrupos").length) {
                    debugConsole('idEscuelaaa:' + $("#id_escuela").val());
                    llenaComboGrupos($("#id_escuela").val());
                }

                if (cambioEscuela) {
                    debugConsole("Cambiando escuela");
                    $("#id_escuela option[value=" + id_escuela + "]").attr("selected", true);
                    cambioEscuela = false;
                }

                if ($("#enrolar").length) {
                    llenaSelectGruposDisponibles();
                } else {
                    if ($("#cargaAlumnos").val() === "1") {
                        cargaAlumnosDisponibles();
                    }
                }
            } else {
                $("<option value=''>Esta institución no tiene escuelas.</option>").appendTo("#id_escuela");
                if ($("#comboGrupos").length) {
                    $("#comboGrupos").empty();
                    $("<option>Sin información disponible.</option>").appendTo("#comboGrupos");
                }
                if (("#btnDescargar").length) {
                    $("#btnDescargar").css("display", "none");
                    $("#tablaDescarga").css("display", "none");
                }
                //Vacia listas para la seccion de enrolamiento
                debugConsole("Vaciar listas");
                $(".grupo").html("");
            }
        });
    });
    $("#idInstitucion").change();

    //Si el formulario pasa
    var formSuccessUsuario = true;
    var formSuccessCorreo = true;
    var formSuccessContrasena = true;
    var formSuccessConfirmaContrasena = true;
    var formSuccessFecha = true;
    //$("button[type=submit]").attr("disabled", "disabled");

    /**
     * Verifica la fortaleza de la contrasena
     * 
     * FECHA DE MODIFICACIÓN: 16 DE DICIEMBRE DEL 2013
     * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
     * SE CAMBIO EL EVENTO PARA QUE UTILIZARA LA FUNCION ON
     */
    $("body").on("input", "#contrasena", function(event) {
        debugConsole("#contrasena:" + $(this).val());
        if (!validaFortaleza($(this).val())) {
            debugConsole("contraseña ok");
            formSuccessContrasena = false;
            $("button[type=submit]").attr("disabled", "disabled");
            $("#errorContrasena").html("La contraseña debe tener una <br>longitud de 8 caracteres y <br>al menos un número");
        } else {
            debugConsole("Contraseña no valida");
            formSuccessContrasena = true;
            $("#errorContrasena").html("");
            verificaSuccess();
        }
        $("#confirmaContrasena").keyup();
    });
//    $("#contrasena").keyup(function(event) {
//        
//    });

    /**
     * Funcion que valida la fortaleza de la contraseña
     * Si cumple con el requerimiento de tener una longitud
     * minima de 8 caracteres y al menos un numero entonces es
     * valida y devuelve un true
     * @param {type} contrasena
     * @returns {Boolean}
     */
    function validaFortaleza(contrasena) {
        debugConsole("Valida fortaleza");
        var longitud = 0;
        var numero = false;

        longitud = contrasena.length;
        debugConsole("longitud:" + longitud);

        for (i = 0; i < longitud; i++) {
            debugConsole("ciclo");
            if (!isNaN(contrasena.charAt(i))) {
                numero = true;
                debugConsole("encontre un numero!");
            }
        }

        if (longitud >= 8 && numero) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Verifica que coincidan las contraseñas
     * 
     * FECHA DE MODIFICACIÓN: 16 DE DICIEMBRE DEL 2013
     * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
     * SE CAMBIO EL EVENTO PARA QUE UTILIZARA LA FUNCION ON
     */
    $("body").on("input", "#confirmaContrasena", function(event) {
        debugConsole("confirmando coincidencia contraseñas");
        if ($(this).val() != $("#contrasena").val()) {
            formSuccessConfirmaContrasena = false;
            $("#errorConfirmaContrasena").html("Las contraseñas no coinciden.");
            $("button[type=submit]").attr("disabled", "disabled");
        } else {
            $("#errorConfirmaContrasena").html("");
            formSuccessConfirmaContrasena = true;
            verificaSuccess();
        }
    });
//    $("#confirmaContrasena").keyup(function(event) {
//
//    });


    /**
     * Escucha evento del teclado en usuario y verifica si existe
     */
    $("#nombreUsuario").keyup(function(event) {
        debugConsole("Verifica usuario");
        var usuario = normalize(limpiaEspacios($(this).val()).toLowerCase());
        $(this).val(usuario);

        $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "verificaUsuario", atributo: usuario}, function(respuesta) {
            respuesta = respuesta.trim();
            debugConsole("existe el usuario:" + respuesta);
            if (respuesta === 'true') {
                formSuccessUsuario = false;
                $("#errorUsuario").html("Este nombre de usuario ya ha sido registrado.");
                $("button[type=submit]").attr("disabled", "disabled");
            } else {
                $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "verificaUsuarioMoodle", atributo: usuario}, function(respuesta) {
                    respuesta = respuesta.trim();
                    debugConsole("existe el usuario en moodle:" + respuesta);
                    if (respuesta === 'true') {
                        formSuccessUsuario = false;
                        $("#errorUsuario").html("Este nombre de usuario ya ha sido registrado en Moodle.");
                        $("button[type=submit]").attr("disabled", "disabled");
                    } else {
                        $("#errorUsuario").html("");
                        formSuccessUsuario = true;
                        verificaSuccess();
                    }
                });
            }
        });
    });

    /**
     * Verifica si ya existe el nombre de usuario, pero ignora 
     * el de si mismo para que pueda mantener el mismo
     */
    $("#edita_nombre_usuario").keyup(function(event) {
        debugConsole("Verifica usuario");
        var id_datos_personales = $("#id_datos_personales").val();
        debugConsole("id_datos_personales:" + id_datos_personales);
        var usuario = normalize(limpiaEspacios($(this).val()).toLowerCase());
        $(this).val(usuario);

        $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "verificaUsuarioEditando", atributo: usuario, id_datos_personales: id_datos_personales}, function(respuesta) {
            respuesta = respuesta.trim();
            debugConsole("existe el usuario:" + respuesta);
            if (respuesta === 'true') {
                formSuccessUsuario = false;
                $("#errorUsuario").html("Este nombre de usuario ya ha sido registrado.");
                $("button[type=submit]").attr("disabled", "disabled");
            } else {
                $("#errorUsuario").html("");
                formSuccessUsuario = true;
                verificaSuccess();
            }
        });
    });


    /**
     * Checa si el usuario desea actualizar la contraseña de una cuenta
     */
    var actualizarContrasena = false; //Originalmente no se quiere cambiar la contrasena
    $("#habEditContrasena").click(function(event) {
        actualizarContrasena = !actualizarContrasena;
        validaMostrarCamposContrasena();
        debugConsole("habEditContrasena:" + actualizarContrasena);
    });

    validaMostrarCamposContrasena();
    function validaMostrarCamposContrasena() {
        if (actualizarContrasena) {
            $(".editaContrasena").removeAttr("disabled");
        } else {
            $(".editaContrasena").attr("disabled", "enabled");
        }
    }

    /**
     * Verifica la disponibildiad de la cuenta de correo
     */
    $("#correo").keyup(function(event) {
        debugConsole("Verifica usuario");
        var usuario = normalize(limpiaEspacios($(this).val()).toLowerCase());
        $(this).val(usuario);

        $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "verificaCorreo", atributo: usuario}, function(respuesta) {
            respuesta = respuesta.trim();
            debugConsole("existe el correo:" + respuesta);
            if (respuesta !== 'false') {
                formSuccessCorreo = false;
                $("#errorCorreo").html("Este correo ya ha sido registrado.");
                $("button[type=submit]").attr("disabled", "disabled");
            } else {
                $("#errorCorreo").html("");
                formSuccessCorreo = true;
                verificaSuccess();
            }
        });
    });

    /**
     * Verifica si ya existe ese correo, ignorando el propio
     */
    $("#edita_correo").keyup(function(event) {
        debugConsole("Verifica usuario");
        var id_datos_personales = $("#id_datos_personales").val();
        var usuario = normalize(limpiaEspacios($(this).val()).toLowerCase());
        $(this).val(usuario);

        $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "verificaCorreoEditando", atributo: usuario, id_datos_personales: id_datos_personales}, function(respuesta) {
            respuesta = respuesta.trim();
            debugConsole("existe el correo:" + respuesta);
            if (respuesta !== 'false') {
                formSuccessCorreo = false;
                $("#errorCorreo").html("Este correo ya ha sido registrado.");
                $("button[type=submit]").attr("disabled", "disabled");
            } else {
                $("#errorCorreo").html("");
                formSuccessCorreo = true;
                verificaSuccess();
            }
        });
    });

    function verificaSuccess() {
        if (formSuccessUsuario && formSuccessCorreo && formSuccessContrasena && formSuccessConfirmaContrasena && formSuccessFecha) {
            $("button[type=submit]").removeAttr("disabled");
        }
    }

    /**
     * Limpia Numeros
     */
    $("#nombre_pila").keyup(function(event) {
        $(this).val(limpiaNumeros($(this).val()));
    });

    $("#primer_apellido").keyup(function(event) {
        $(this).val(limpiaNumeros($(this).val()));
    });

    $("#segundo_apellido").keyup(function(event) {
        $(this).val(limpiaNumeros($(this).val()));
    });

    $("#delegacion_municipio").keyup(function(event) {
        $(this).val(limpiaNumeros($(this).val()));
    });

    $("#colonia_localidad").keyup(function(event) {
        $(this).val(limpiaNumeros($(this).val()));
    });
    //Editar
    $("#edita_nombre_pila").keyup(function(event) {
        $(this).val(limpiaNumeros($(this).val()));
    });

    $("#edita_primer_apellido").keyup(function(event) {
        $(this).val(limpiaNumeros($(this).val()));
    });

    $("#edita_segundo_apellido").keyup(function(event) {
        $(this).val(limpiaNumeros($(this).val()));
    });

    $("#edita_delegacion_municipio").keyup(function(event) {
        $(this).val(limpiaNumeros($(this).val()));
    });

    $("#edita_colonia_localidad").keyup(function(event) {
        $(this).val(limpiaNumeros($(this).val()));
    });

    /**
     * Limpia caracteres
     */

    $("#fechaNac").keyup(function(event) {
        $(this).val(limpiaCaracteresFecha($(this).val()));
    });

    $("#curp").keyup(function(event) {
        $(this).val(formatoCURP($(this).val()));
    });

    $("#edita_curp").keyup(function(event) {
        $(this).val(formatoCURP($(this).val()));
    });

    $("#edita_fecha_nacimiento").keyup(function(event) {
        $(this).val(limpiaCaracteresFecha($(this).val()));
    });

    $("#telefonoFijo").keyup(function(event) {
        $(this).val(limpiaCaracteres($(this).val()));
    });

    $("#edita_telefono_fijo").keyup(function(event) {
        $(this).val(limpiaCaracteres($(this).val()));
    });

    $("#telefonoMovil").keyup(function(event) {
        $(this).val(limpiaCaracteres($(this).val()));
    });

    $("#edita_telefono_movil").keyup(function(event) {
        $(this).val(limpiaCaracteres($(this).val()));
    });

    $("#codigoPostal").keyup(function(event) {
        $(this).val(limpiaCaracteres($(this).val()));
    });

    $("#edita_codigo_postal").keyup(function(event) {
        $(this).val(limpiaCaracteres($(this).val()));
    });

    /**
     * Verifica que no haya seleccionado una fecha en el futuro 
     * al dia de hoy
     */
    $("#edita_fecha_nacimiento").change(function(event) {
        var fecha = $(this).val();
        debugConsole("Fecha:" + fecha);
        debugConsole("Fecha Actual:" + fechaValida);

        if (!compare_dates(fecha, fechaValida)) {
            debugConsole("Fecha ok");
            formSuccessFecha = true;
            $("#errorFecha").html("");
            verificaSuccess();
        } else {
            debugConsole("Nacio en el futuro");
            formSuccessFecha = false;
            $("#errorFecha").html("No puede seleccionar una fecha donde el usuario sea menor a 3 años.");
            $("button[type=submit]").attr("disabled", "disabled");
        }
    });

    $("#edita_fecha_nacimiento").keyup(function(event) {
        var fecha = $(this).val();
        debugConsole("Fecha:" + fecha);
        debugConsole("Fecha Actual:" + fechaValida);

        if (!compare_dates(fecha, fechaValida)) {
            debugConsole("Fecha ok");
            formSuccessFecha = true;
            $("#errorFecha").html("");
            verificaSuccess();
        } else {
            debugConsole("Nacio en el futuro");
            formSuccessFecha = false;
            $("#errorFecha").html("No puede seleccionar una fecha donde el usuario sea menor a 3 años.");
            $("button[type=submit]").attr("disabled", "disabled");
        }
    });


//    var nowTemp = new Date();
//    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    function renderDatePicker(fechaValida) {
        try {
            $('#fechaNac').datepicker({
                format: 'dd/mm/yyyy',
                viewMode: 2,
                onRender: function(date) {
                    var now = new Date(getAnio(fechaValida), getMes(fechaValida), getDia(fechaValida), 0, 0, 0, 0);
//                    debugConsole("año:" + getAnio(fechaValida));
//                    debugConsole("mes:" + getMes(fechaValida));
//                    debugConsole("date:" + getDia(fechaValida));
                    return date.valueOf() > now.valueOf() ? 'disabled' : '';
                }
            });

            $('#edita_fecha_nacimiento').datepicker({
                format: 'dd/mm/yyyy',
                viewMode: 2,
                onRender: function(date) {
                    var now = new Date(getAnio(fechaValida), getMes(fechaValida), getDia(fechaValida), 0, 0, 0, 0);
//                    debugConsole("año:" + getAnio(fechaValida));
//                    debugConsole("mes:" + getMes(fechaValida));
//                    debugConsole("date:" + getDia(fechaValida));
                    return date.valueOf() > now.valueOf() ? 'disabled' : '';
                }
            });

            $("#fechaNac").datepicker().on('changeDate', function(ev) {
                var fecha = $(this).val();
                debugConsole("Fecha:" + fecha);
                debugConsole("Fecha Actual:" + fechaValida);

                if (!compare_dates(fecha, fechaValida)) {
                    debugConsole("Fecha ok");
                    formSuccessFecha = true;
                    $("#errorFecha").html("");
                    verificaSuccess();
                } else {
                    debugConsole("Nacio en el futuro");
                    formSuccessFecha = false;
                    $("#errorFecha").html("No puede seleccionar una fecha donde el usuario sea menor a 3 años.");
                    $("button[type=submit]").attr("disabled", "disabled");
                }
            });

            $("#edita_fecha_nacimiento").datepicker().on('changeDate', function(ev) {
                var fecha = $(this).val();
                debugConsole("Fecha:" + fecha);
                debugConsole("Fecha Actual:" + fechaValida);

                if (!compare_dates(fecha, fechaValida)) {
                    debugConsole("Fecha ok");
                    formSuccessFecha = true;
                    $("#errorFecha").html("");
                    verificaSuccess();
                } else {
                    debugConsole("Nacio en el futuro");
                    formSuccessFecha = false;
                    $("#errorFecha").html("No puede seleccionar una fecha donde el usuario sea menor a 3 años.");
                    $("button[type=submit]").attr("disabled", "disabled");
                }
            });


        } catch (ex) {
            debugConsole("No datepicker");
        }

        /**
         * Verifica que no haya seleccionado una fecha en el futuro 
         * al dia de hoy hace 3 años
         */
        $("#fechaNac").change(function(event) {
            var fecha = $(this).val();
            debugConsole("Fecha:" + fecha);
            debugConsole("Fecha Actual:" + fechaValida);

            if (!compare_dates(fecha, fechaValida)) {
                debugConsole("Fecha ok");
                formSuccessFecha = true;
                $("#errorFecha").html("");
                verificaSuccess();
            } else {
                debugConsole("Nacio en el futuro");
                formSuccessFecha = false;
                $("#errorFecha").html("No puede seleccionar una fecha donde el usuario sea menor a 3 años.");
                $("button[type=submit]").attr("disabled", "disabled");
            }
        });

        $("#fechaNac").keyup(function(event) {
            var fecha = $(this).val();
            debugConsole("Fecha:" + fecha);
            debugConsole("Fecha Actual:" + fechaValida);

            if (!compare_dates(fecha, fechaValida)) {
                debugConsole("Fecha ok");
                formSuccessFecha = true;
                $("#errorFecha").html("");
                verificaSuccess();
            } else {
                debugConsole("Nacio en el futuro");
                formSuccessFecha = false;
                $("#errorFecha").html("No puede seleccionar una fecha donde el usuario sea menor a 3 años.");
                $("button[type=submit]").attr("disabled", "disabled");
            }
        });
    }

    //Quitar caracteres especiales
    var normalize = (function() {
        var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç",
                to = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc",
                mapping = {};

        for (var i = 0, j = from.length; i < j; i++)
            mapping[ from.charAt(i) ] = to.charAt(i);

        return function(str) {
            var ret = [];
            for (var i = 0, j = str.length; i < j; i++) {
                var c = str.charAt(i);
                if (mapping.hasOwnProperty(str.charAt(i)))
                    ret.push(mapping[ c ]);
                else
                    ret.push(c);
            }
            return ret.join('');
        }

    })();

    /**
     * Funcion quita los caracteres de una cadena
     * @param {type} cadena
     * @returns {String}
     */
    function limpiaCaracteres(cadena) {
        var nuevaCadena = "";
        for (var i = 0; i < cadena.length; i++) {
            if (!isNaN(Number(cadena.charAt(i)))) {
                nuevaCadena += cadena.charAt(i);
            }
        }
        return nuevaCadena.trim();
    }

    /**
     * Funcion que limpia caracteres no validos dentro de el campo de fecha
     * @param {type} cadena
     * @returns {String}
     */
    function limpiaCaracteresFecha(cadena) {
        var nuevaCadena = "";
        for (var i = 0; i < cadena.length; i++) {
            if (!isNaN(Number(cadena.charAt(i))) || cadena.charAt(i) === "/") {
                nuevaCadena += cadena.charAt(i);
            }
        }
        return limpiaEspacios(nuevaCadena);
    }

    /**
     * Funcion que quita numeros a una cadena de texto
     * @param {type} cadena
     * @returns {unresolved}
     */
    function limpiaNumeros(cadena) {
        var nuevaCadena = "";
        for (var i = 0; i < cadena.length; i++) {
            if (isNaN(cadena.charAt(i)) || cadena.charAt(i) === " ") {
                nuevaCadena += cadena.charAt(i);
            }
        }
        return nuevaCadena;
    }

    /**
     * Quita los espacios en blanco de una cadena
     * @param {type} cadena
     * @returns {@exp;@call;trim@call;replace}
     */
    function limpiaEspacios(cadena) {
        return cadena.replace(" ", "").trim();
    }

    /**
     * Validar formato del curp
     * @param {type} cadena
     * @returns {@exp;nuevaCadena@call;toUpperCase}
     */
    function formatoCURP(cadena) {
        var nuevaCadena = "";
        for (var i = 0; i < cadena.length; i++) {
            if (!/[^A-Za-z\d]/.test(cadena.charAt(i))) {
                nuevaCadena += cadena.charAt(i);
            }
        }
        return nuevaCadena.toUpperCase();
    }

    /**
     * Realiza consulta de datos personales y la retorna en JSON
     * @param {type} idAtributo
     * @returns {undefined}
     */
    function getDatosPersonalesJSON(idAtributo, tipo_funcion, tipo_usuario) {
        debugConsole("getDatosPersonalesJSON");
        var datos;
        $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "consultaDatosPersonales", atributo: idAtributo}, function(datos) {
            debugConsole("Dentro del post");
            debugConsole(datos);
            if (datos != 'null') {
                datos = jQuery.parseJSON(datos);
                debugConsole("La informacion esta lista!");
                switch (tipo_funcion) {
                    case "ver":
                        setVerDatosUsuario(datos, tipo_usuario);
                        break;
                    case "editar":
                        setEditarDatosUsuario(datos, tipo_usuario);
                        break;
                }
            } else {
                debugConsole("No llegaron los datos");
            }

        });

        return datos;
    }

    $(".verDatosPersonales").click(function(event) {
        $(".cargando").html("<h4><b>Cargando datos...</b><img src='../img/loading.gif' width='30'></h4>");
        debugConsole("verDatosPersonales");
        var idAtributo = $(this).attr("id");
        var tipo_usuario = $(this).attr("name");
        debugConsole("Getting datos personales:" + idAtributo);
        var datos = getDatosPersonalesJSON(idAtributo, "ver", tipo_usuario);
    });

    $(".editarDatosPersonales").click(function(event) {
        $(".cargando").html("<h4><b>Cargando datos...</b><img src='../img/loading.gif' width='30'></h4>");
        debugConsole("verDatosPersonales");
        var idAtributo = $(this).attr("id");
        var tipo_usuario = $(this).attr("name");
        debugConsole("Getting datos personales:" + idAtributo);
        var datos = getDatosPersonalesJSON(idAtributo, "editar", tipo_usuario);
    });

    /**
     * Define los datos en la ventana modal para ver
     * @param {type} datos
     * @param {type} tipo_usuario
     * @returns {undefined}
     */
    function setVerDatosUsuario(datos, tipo_usuario) {
        debugConsole("setDatos");
        $("#ver_id_datos_personales").html(datos.id_datos_personales).text();
        $("#ver_nombre_usuario").html(datos.nombre_usuario).text();
        $("#ver_correo").html(datos.correo).text();
        $("#ver_nombre_pila").html(datos.nombre_pila).text();
        $("#ver_primer_apellido").html(datos.primer_apellido).text();
        $("#ver_segundo_apellido").html(datos.segundo_apellido).text();
        $("#ver_fecha_nacimiento").html(datos.fecha_nacimiento).text();
        $("#ver_curp").html(datos.curp).text();
        $("#ver_codigo_postal").html(datos.codigo_postal).text();
        $("#ver_calle").html(datos.calle).text();
        $("#ver_no_casa_ext").html(datos.no_casa_ext).text();
        $("#ver_no_casa_int").html(datos.no_casa_int).text();
        $("#ver_colonia_localidad").html(datos.colonia_localidad).text();
        $("#ver_delegacion_municipio").html(datos.delegacion_municipio).text();
        $("#ver_entidad_federativa").html(datos.nombre_entidad).text();
        $("#ver_nacionalidad").html(datos.nacionalidad).text();
        $("#ver_zona_horaria").html(datos.zona_horaria).text();
        $("#ver_telefono_fijo").html(datos.telefono_fijo).text();
        $("#ver_telefono_movil").html(datos.telefono_movil).text();

        if (datos.url_foto) {
            $("#ver_imagen").html("<img src='" + datos.url_foto + "' width='100'/>");
        } else {
            $("#ver_imagen").html("No hay imagen de perfil");
        }


        debugConsole("tipo_usuario:" + tipo_usuario);
        switch (tipo_usuario) {
            case "estudiante":
                $("#ver_matricula").html(Encoder.htmlDecode(datos.matricula)).text();

                if (datos.nombre_padre != null) {
                    $("#ver_padre").html(Encoder.htmlDecode(datos.nombre_padre)).text();
                } else {
                    $("#ver_padre").html("No hay relación con un Padre de Familia");
                }

                if (datos.nombre_profesor != null) {
                    $("#ver_profesor_aula").html(Encoder.htmlDecode(datos.nombre_profesor)).text();
                } else {
                    $("#ver_profesor_aula").html("No hay relación con un Profesor de Aula");
                }

                $("#ver_nivel_educativo").html(Encoder.htmlDecode(datos.nivel_escolar)).text();
                $("#ver_grado_escolar").html(Encoder.htmlDecode(datos.grado_escolar)).text();
                $("#ver_institucion").html(Encoder.htmlDecode(datos.nombre_institucion)).text();
                $("#ver_escuela").html(Encoder.htmlDecode(datos.nombre_escuela)).text();

                if ($("#verAlumnosGrupo").val() === "1") {
                    $(".grupo_estudiante").show("fast");
                    $(".grupo_profesionista").hide("fast");
                }
                break;
            case "profesionista":
                $("#ver_empresa").html(datos.nombre_empresa).text();

                if ($("#verAlumnosGrupo").val() === "1") {
                    $(".grupo_estudiante").hide("fast");
                    $(".grupo_profesionista").show("fast");
                }
                break;
            case "tutor":
                $("#ver_rol_tutor").html(datos.nombre_rol).text();
                break;
            case "profesor":
                $("#ver_puesto_ocupacion").html(Encoder.htmlDecode(datos.puesto_ocupacion)).text();
                $("#ver_institucion").html(Encoder.htmlDecode(datos.nombre_institucion)).text();
                $("#ver_escuela").html(Encoder.htmlDecode(datos.nombre_escuela)).text();
                $("#ver_grado_escolar").html(Encoder.htmlDecode(datos.nombre_grado)).text();
                $("#ver_nivel_educativo").html(Encoder.htmlDecode(datos.nivel_escolar)).text();
                debugConsole("Alumnos:");
                $("#ver_alumnos").html("");
                for (var i = 0; i < datos.alumnos.length; i++) {
                    debugConsole(datos.alumnos[i].nombre_pila);
                    $("#ver_alumnos").append("<li><a href='' onclick='javascript:void(0);'>" + datos.alumnos[i].nombre_pila + " " + datos.alumnos[i].primer_apellido + " " + datos.alumnos[i].segundo_apellido + "</a></li>");
                }
                break;
            case "padre":
                //ge.nombre_grado, ne.nombre nivel_escolar,
                debugConsole("Nivel Escolar:" + datos.nivel_escolar);
                $("#ver_ultimo_nivel").html(Encoder.htmlDecode(datos.nivel_escolar)).text();
                $("#ver_ultimo_grado").html(Encoder.htmlDecode(datos.nombre_grado)).text();
                $("#ver_hijos").html("");
                debugConsole("Hijos:");
                $("#ver_hijos").html("");
                try {
                    for (var i = 0; i < datos.alumnos.length; i++) {
                        debugConsole(datos.alumnos[i].nombre_pila);
                        $("#ver_hijos").append("<li><a href='' onclick='javascript:void(0);'>" + datos.alumnos[i].nombre_pila + " " + datos.alumnos[i].primer_apellido + " " + datos.alumnos[i].segundo_apellido + "</a></li>");
                    }
                } catch (e) {
                    debugConsole("Error al cargar hijos, probablemente no tiene");
                }
                break;
            case "gestor":
                break;
            case "admin":
                break;
        }
        $(".cargando").html("");
    }

    var cambioEscuela = false;
    var id_escuela = 0;
    function setEditarDatosUsuario(datos, tipo_usuario) {
        debugConsole("setEditarDatos");
        $("#id_datos_personales").val(datos.id_datos_personales);
        $("#edita_nombre_usuario").val(Encoder.htmlDecode(datos.nombre_usuario));
        $("#edita_correo").val(Encoder.htmlDecode(datos.correo));
        $("#edita_nombre_pila").val(Encoder.htmlDecode(datos.nombre_pila));
        $("#edita_primer_apellido").val(Encoder.htmlDecode(datos.primer_apellido));
        $("#edita_segundo_apellido").val(Encoder.htmlDecode(datos.segundo_apellido));
        $("#edita_fecha_nacimiento").val(datos.fecha_nacimiento);
        //Datepicker
        try {
            $("#edita_fecha_nacimiento").datepicker('setValue', datos.fecha_nacimiento);
        } catch (e) {
            debugConsole("Error al settear fecha en datepicker");
        }
        $("#edita_curp").val(datos.curp);
        $("#edita_codigo_postal").val(datos.codigo_postal);
        $("#edita_calle").val(Encoder.htmlDecode(datos.calle));
        $("#edita_no_casa_ext").val(datos.no_casa_ext);
        $("#edita_no_casa_int").val(datos.no_casa_int);
        $("#edita_colonia_localidad").val(Encoder.htmlDecode(datos.colonia_localidad));
        $("#edita_delegacion_municipio").val(Encoder.htmlDecode(datos.delegacion_municipio));
        $("#edita_id_entidad_federativa option[value=" + datos.id_entidad_federativa + "]").attr("selected", true);
        $("#edita_id_nacionalidad option[value=" + datos.id_nacionalidad + "]").attr("selected", true);
        $("#edita_zona_horaria option[value='" + datos.zona_horaria + "']").attr("selected", true);
        $("#edita_telefono_fijo").val(datos.telefono_fijo);
        $("#edita_telefono_movil").val(datos.telefono_movil);
        $("#id_datos_personales").val(datos.id_datos_personales);

        if (datos.url_foto != null) {
            $("#ver_imagen").html("<img src='" + datos.url_foto + "' width='100'/>");
        } else {
            $("#ver_imagen").html("No hay imagen de perfil");
        }

        debugConsole("tipo_usuario:" + tipo_usuario);
        switch (tipo_usuario) {
            case "estudiante":
                $("#edita_matricula").val(datos.matricula);
                $("#edita_id_padre option[value=" + datos.id_padre + "]").attr("selected", true);
                $("#edita_id_profesor_aula option[value=" + datos.id_profesor_aula + "]").attr("selected", true);
                $("#comboNivelEscolar option[value=" + datos.id_nivel + "]").attr("selected", true);
                cambioGrado = true;
                idGradoEscolar = datos.id_grado_escolar;
                $("#comboNivelEscolar").change();
//                $("#edita_id_grado_escolar option[value=" + datos.id_grado_escolar + "]").attr("selected", true);
                $("#idInstitucion option[value=" + datos.id_institucion + "]").attr("selected", true);
                cambioEscuela = true;
                id_escuela = datos.id_escuela;
                $("#idInstitucion").change();
                debugConsole("id_escuela:" + datos.id_escuela);
                break;
            case "profesionista":
                $("#edita_id_empresa option[value=" + datos.id_empresa + "]").attr("selected", true);
                break;
            case "tutor":
                $("#edita_id_rol_tutor option[value=" + datos.id_rol_tutor + "]").attr("selected", true);
                break;
            case "profesor":
                $("#edita_puesto_ocupacion").val(Encoder.htmlDecode(datos.puesto_ocupacion));
                $("#idInstitucion option[value=" + datos.id_institucion + "]").attr("selected", true);
                cambioEscuela = true;
                id_escuela = datos.id_escuela;
                $("#idInstitucion").change();
                debugConsole("id_escuela:" + datos.id_escuela);
                $("#comboNivelEscolar option[value=" + datos.id_nivel + "]").attr("selected", true);
                cambioGrado = true;
                idGradoEscolar = datos.id_grado_escolar;
                $("#comboNivelEscolar").change();
//                $("#edita_id_grado_escolar_enrolado option[value=" + datos.id_grado_escolar_enrolado + "]").attr("selected", true);
//                $("#edita_id_nivel option[value=" + datos.id_nivel + "]").attr("selected", true);
                break;
            case "padre":
                $("#comboNivelEscolar option[value=" + datos.id_nivel + "]").attr("selected", true);
                cambioGrado = true;
                idGradoEscolar = datos.id_ultimo_grado_escolar;
                $("#comboNivelEscolar").change();
//                $("#id_ultimo_grado_escolar option[value=" + datos.id_empresa + "]").attr("selected", true);
                break;
            case "gestor":
                break;
            case "admin":
                break;
        }
        $(".cargando").html("");
    }

    /**
     * Retorna la fecha actual en un string
     * 
     * FECHA DE MODIFICACIÓN: 16 DE DICIEMBRE DEL 2013
     * AUTOR: JOSÉ MANUEL NIETO GÓMEZ     * 
     * MODIFICACIÓN PARA QUE COMPARE CON EL FORMATO
     * DD/MM/YYYY, HACIENDOLO PARAMETRIZABLE
     * @param {type} formato dd-mm-yyyy, dd/mm/yyyy, yyyy-mm-dd, yyyy/mm/dd
     * @returns {String}
     */
    function getFechaActual(formato) {
        var fecha_actual = new Date();
        var diaActual = fecha_actual.getDate();
        var mesActual = fecha_actual.getMonth() + 1;

        if (diaActual < 10)
            diaActual = "0" + diaActual;

        if (mesActual < 10)
            mesActual = "0" + mesActual;

        if (formato === 'dd-mm-yyyy') {
            var fecha3 = diaActual + "-" + mesActual + "-" + fecha_actual.getFullYear();
            return fecha3;
        } else if (formato === 'dd/mm/yyyy') {
            var fecha3 = diaActual + "/" + mesActual + "/" + fecha_actual.getFullYear();
            return fecha3;
        } else if (formato === 'yyyy-mm-dd') {
            var fecha3 = fecha_actual.getFullYear() + "-" + mesActual + "-" + diaActual;
            return fecha3;
        } else if (formato === 'yyyy/mm/dd') {
            var fecha3 = fecha_actual.getFullYear() + "/" + mesActual + "/" + diaActual;
            return fecha3;
        } else {
            return(false);
        }
    }

    /**
     * Funcion que retorna la fech actual en un formato específico
     * 
     * FECHA DE MODIFICACIÓN: 16 DE DICIEMBRE DEL 2013
     * AUTOR: JOSÉ MANUEL NIETO GÓMEZ     * 
     * MODIFICACIÓN PARA QUE COMPARE CON EL FORMATO
     * DD/MM/YYYY, HACIENDOLO PARAMETRIZABLE
     * @param {type} formato dd-mm-yyyy, dd/mm/yyyy, yyyy-mm-dd, yyyy/mm/dd
     * @param {type} restaDias años que se le quieran restar
     * @returns {String}
     */
    function getFechaActual(formato, restaDias) {
        var fecha_actual = new Date();

        fecha_actual = new Date(fecha_actual.getTime() - (restaDias * 24 * 3600 * 1000));

        var diaActual = fecha_actual.getDate();
        var mesActual = fecha_actual.getMonth() + 1;

        if (diaActual < 10)
            diaActual = "0" + diaActual;

        if (mesActual < 10)
            mesActual = "0" + mesActual;

        if (formato === 'dd-mm-yyyy') {
            var fecha3 = diaActual + "-" + mesActual + "-" + fecha_actual.getFullYear();
            return fecha3;
        } else if (formato === 'dd/mm/yyyy') {
            var fecha3 = diaActual + "/" + mesActual + "/" + fecha_actual.getFullYear();
            return fecha3;
        } else if (formato === 'yyyy-mm-dd') {
            var fecha3 = fecha_actual.getFullYear() + "-" + mesActual + "-" + diaActual;
            return fecha3;
        } else if (formato === 'yyyy/mm/dd') {
            var fecha3 = fecha_actual.getFullYear() + "/" + mesActual + "/" + diaActual;
            return fecha3;
        } else {
            return(false);
        }

    }

    /**
     * Compara si la fecha es  es mayor a la fecha 2
     * Si la primer fecha es mayor retorna true
     * Formato de Fecha: YYYY-MM-DD
     * 
     * FECHA DE MODIFICACIÓN: 16 DE DICIEMBRE DEL 2013
     * AUTOR: JOSÉ MANUEL NIETO GÓMEZ     * 
     * MODIFICACIÓN PARA QUE COMPARE CON EL FORMATO
     * DD/MM/YYYY, HACIENDOLO PARAMETRIZABLE
     * 
     * CHANGE CONTROL 0.99.9
     * FECHA DE MODIFICACION: 23 DE ENERO DEL 2014
     * AUTOR: JOSE MANUEL NIETO GOMEZ
     * OBJETIVO: IMPELEMENTAR LA FUNCION DE ESTANDARIZACION DE FECHAS
     * 
     * @param {type} fecha
     * @param {type} fecha2
     * @param {type} formato dd-mm-yyyy, dd/mm/yyyy, yyyy-mm-dd, yyyy/mm/dd
     * @returns {Boolean}
     */
    function compare_dates(fecha, fecha2) {
        fecha = estandarizaFecha(fecha.trim());
        fecha2 = estandarizaFecha(fecha2.trim());
        
      
        
        debugConsole("Compare_dates");
        debugConsole("Formato estandarizado 1:"+fecha);
        debugConsole("Formato estandarizado 2:"+fecha2);

        if (fecha == null || fecha2 == null) {
            return false;
        }
        //Espera el formato estandarizado dd/mm/yyyy
        var xDay = Number(fecha.substring(0, 2));
        var xMonth = Number(fecha.substring(3, 5));
        var xYear = Number(fecha.substring(6, 10));

        var yDay = Number(fecha2.substring(0, 2));
        var yMonth = Number(fecha2.substring(3, 5));
        var yYear = Number(fecha2.substring(6, 10));

//        if (formato === 'dd-mm-yyyy' || formato === 'dd/mm/yyyy') {
//            var xDay = fecha.substring(0, 2);
//            var xMonth = fecha.substring(3, 5);
//            var xYear = fecha.substring(6, 10);
//
//            var yDay = fecha2.substring(0, 2);
//            var yMonth = fecha2.substring(3, 5);
//            var yYear = fecha2.substring(6, 10);
//        } else if (formato === 'yyyy-mm-dd' || formato === 'yyyy/mm/dd') {
//            var xDay = fecha.substring(8, 10);
//            var xMonth = fecha.substring(3, 5);
//            var xYear = fecha.substring(0, 4);
//
//            var yDay = fecha2.substring(8, 10);
//            var yMonth = fecha2.substring(3, 5);
//            var yYear = fecha2.substring(0, 4);
//        } else {
//            return(false);
//        }

        if (xYear > yYear) {
            return(true);
        }
        else {
            if (xYear === yYear) {
                if (xMonth > yMonth) {
                    return(true);
                }
                else {
                    if (xMonth === yMonth) {
                        if (xDay > yDay)
                            return(true);
                        else
                            return(false);
                    } else
                        return(false);
                }
            }
            else
                return(false);
        }
    }

/**
 * 
 * @returns {undefined}
 */
function sonFechasIguales(fecha,fecha2)
{
     fecha = estandarizaFecha(fecha.trim());
     fecha2 = estandarizaFecha(fecha2.trim());
     
      if (fecha == null || fecha2 == null) {
            return false;
        }
        //Espera el formato estandarizado dd/mm/yyyy
        var xDay = Number(fecha.substring(0, 2));
        var xMonth = Number(fecha.substring(3, 5));
        var xYear = Number(fecha.substring(6, 10));

        var yDay = Number(fecha2.substring(0, 2));
        var yMonth = Number(fecha2.substring(3, 5));
        var yYear = Number(fecha2.substring(6, 10));
        
        return (xDay===yDay && xMonth===yMonth && xYear === yYear)? true:false;
}


    /**
     * CHANGE CONTROL 0.99.9
     * FECHA DE MODIFICACION: 23 DE ENERO DEL 2014
     * AUTOR: JOSE MANUEL NIETO GOMEZ
     * OBJETIVO: ESTANDARIZAR FORMATO DE FECHA
     * 
     * Funcion que estandariza el formato de una fecha pudiendo recibir
     * los siguientes formatos: dd/mm/yyyy, dd-mm-yyyy, yyyy-mm-dd, y yyyy/mm/dd
     * Siempre retornara el formato en dd/mm/yyyy
     * Si la entrada de la fecha no es correcta retorna un null
     * @param {type} fecha
     * @returns {String}
     */
    function estandarizaFecha(fecha) {
        var matches;
        if ((matches = fecha.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/)) != null) {
            //dd/mm/yyyy
            return fecha;
        } else if ((matches = fecha.match(/^(\d{1,2})\-(\d{1,2})\-(\d{4})$/)) != null) {
            //dd-mm-yyyy
            return matches[1] + "/" + matches[2] + "/" + matches[3];
        } else if ((matches = fecha.match(/^(\d{4})\-(\d{1,2})\-(\d{1,2})$/)) != null) {
            //yyyy-mm-dd
            return matches[3] + "/" + matches[2] + "/" + matches[1];
        } else if ((matches = fecha.match(/^(\d{4})\/(\d{1,2})\/(\d{1,2})$/)) != null) {
            //yyyy/mm/dd
            return matches[3] + "/" + matches[2] + "/" + matches[1];
        } else {
            return null;
        }
    }

    var successCargaCurso = true;
    $("#nuevoCurso").submit(function(event) {
        $(".bar").show("fast");
    });

//    $(".contenido").change(function(event) {
//        
//    });

    $("body").on("change", ".contenido", function() {
        debugConsole("Validando nuevo contenido");
        var ext = "zip";
        if (!validaExtension($(this).val(), ext)) {
            $(this).val("");
            try {
                $("#error").modal({
                    show: true
                });
            } catch (e) {
                alert("Tipo de extensión para contenidos no válido. Solo admiten archivos ZIP.");
            }
        }
    });

    /**
     * Valida rangos en curso nuevo
     */

    $("#rango1").on('input', function(event) {
        $("#repRango1").val(Number($(this).val()) + 1);
        validarRangos();
    });

    $("#rango2").on('input', function(event) {
        $("#repRango2").val(Number($(this).val()) + 1);
        validarRangos();
    });

    $("#rango3").on('input', function(event) {
        $("#repRango3").val(Number($(this).val()) + 1);
        validarRangos();
    });

    function validarRangos() {
        var successRango1 = true;
        var successRango2 = true;
        var successRango3 = true;

        var rango1 = $("#rango1").val();
        var rango2 = $("#rango2").val();
        var rango3 = $("#rango3").val();
        var rango4 = $("#rango4").val();

        if (rango1 !== "" && rango2 !== "") {
            try {
                if (Number(rango1) >= Number(rango2)) {
                    successRango1 = false;
                    $("#errorRango2").html("Error. El rango 2 es menor o igual al rango 1");
                } else {
                    successRango1 = true;
                    $("#errorRango2").html("");
                }
            } catch (e) {
                successRango1 = false;
                $("#errorRango2").html("Error. El formato es incorrecto");
            }
        }

        if (rango2 !== "" && rango3 !== "") {
            try {
                if (Number(rango2) >= Number(rango3)) {
                    successRango2 = false;
                    $("#errorRango3").html("Error. El rango 3 es menor o igual al rango 2");
                } else {
                    successRango2 = true;
                    $("#errorRango3").html("");
                }
            } catch (e) {
                successRango2 = false;
                $("#errorRango3").html("Error. El formato es incorrecto");
            }
        }

        if (rango3 !== "" && rango4 !== "") {
            try {
                if (Number(rango3) >= Number(rango4)) {
                    successRango3 = false;
                    $("#errorRango4").html("Error. El rango  3 es mayor o igual al rango 4");
                } else {
                    successRango3 = true;
                    $("#errorRango4").html("");
                }
            } catch (e) {
                successRango3 = false;
                $("#errorRango4").html("Error. El formato es incorrecto");
            }
        }
        if (successRango1 && successRango2 && successRango3 && formSuccessClaveCurso) {
            $("button[type=submit]").removeAttr("disabled");
        } else {
            $("button[type=submit]").attr("disabled", "disabled");
        }
    }


    /**
     * Valida una extension, devuelve true si es valida
     * y false si no es valia
     * @param {type} prueba
     * @param {type} ext
     * @returns {Boolean}
     */
    function validaExtension(prueba, ext) {
        prueba = prueba.trim();
        ext = ext.trim();
        var thisExt = prueba.split(".");
        if (thisExt[thisExt.length - 1] !== ext) {
            debugConsole("Error, extension no valida. Prueba:" + prueba + " ext:" + ext);

            return false;
        } else {
            return true;
        }
    }

    /**
     * Validacion en cursos
     */

    var formSuccessNombreCurso = true;
    var formSuccessClaveCurso = true;
    var formSuccessNombreCorto = true;

    /**
     * Evento que valida mediante JSON si el nobmre del curso ya existe
     * Si ya existe, inhabilita el boton de guardar del formulario
     */
    $("#nombre_curso").keyup(function(event) {
        debugConsole("Verifica nombre del curso");
        var nombre_curso = $(this).val();

        $.post("../sources/ControladorAdmin.Cursos.php", {consulta: "validaNombreCurso", atributo: nombre_curso}, function(respuesta) {
            debugConsole("existe el nombre del curso:" + respuesta);
            if (respuesta === "true") {
                formSuccessNombreCurso = false;
                $("#errorNombreCurso").html("El nombre del curso ya ha sido registrado.");
                $("button[type=submit]").attr("disabled", "disabled");
            } else {
                $("#errorNombreCurso").html("");
                formSuccessNombreCurso = true;
                verificaSuccessCurso();
            }
        });
    });

    /**
     * Evento que valida mediante JSON si la clave del curso ya existe
     * Si ya existe, inhabilita el boton de guardar del formulario
     */
    $("#nombre_corto").keyup(function(event) {
        debugConsole("Verifica clave del curso");
        var nombre_corto = $(this).val();

        $.post("../sources/ControladorAdmin.Cursos.php", {consulta: "validaNombreCorto", atributo: nombre_corto}, function(respuesta) {
            debugConsole("existe la clave del curso:" + respuesta);
            if (respuesta === "true") {
                formSuccessNombreCorto = false;
                $("#errorNombreCorto").html("El nombre corto ya ha sido registrado.");
                $("button[type=submit]").attr("disabled", "disabled");
            } else {
                $("#errorNombreCorto").html("");
                formSuccessNombreCorto = true;
                verificaSuccessCurso();
            }
        });
    });

    /**
     * Evento que valida mediante JSON si la clave del curso ya existe
     * Si ya existe, inhabilita el boton de guardar del formulario
     */
    $("#clave_curso").on("input", function(event) {
        debugConsole("Verifica clave del curso");
        var clave_curso = $(this).val();

        $.post("../sources/ControladorAdmin.Cursos.php", {consulta: "validaClaveCurso", atributo: clave_curso}, function(respuesta) {
            debugConsole("existe la clave del curso:" + respuesta);
            if (respuesta === "true") {
                formSuccessClaveCurso = false;
                $("#errorClaveCurso").html("La clave del curso ya ha sido registrada.");
                $("button[type=submit]").attr("disabled", "disabled");
            } else {
                $("#errorClaveCurso").html("");
                formSuccessClaveCurso = true;
                verificaSuccessCurso();
            }
        });
    });

    /**
     * Verifica que todas las validaciones sean verdaderas
     * Para habilitar el boton
     * @returns {undefined}
     */
    function verificaSuccessCurso() {
        debugConsole("verifica success formulario");
        if (formSuccessNombreCurso && formSuccessClaveCurso && formSuccessNombreCorto) {
            debugConsole("Se permite el submit");
            $("button[type=submit]").removeAttr("disabled");
        }
    }

    $(".verCurso").click(function(event) {
        $(".cargando").html("<h4><b>Cargando datos...</b><img src='../img/loading.gif' width='30'></h4>");
        debugConsole("verCurso");
        var idAtributo = $(this).attr("name");
        debugConsole("Getting datos curso:" + idAtributo);
        var datos = getDatosCursoJSON(idAtributo, "ver");
    });

    $(".editaCurso").click(function(event) {
        $(".cargando").html("<h4><b>Cargando datos...</b><img src='../img/loading.gif' width='30'></h4>");
        debugConsole("editaCurso");
        var idAtributo = $(this).attr("name");
        debugConsole("Getting datos personales:" + idAtributo);
        var datos = getDatosCursoJSON(idAtributo, "editar");
    });

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
        $("#ver_grado_escolar").html(datos.nombre_grado).text();
        $("#probar_curso").html("<a href='../mapaCurso/index.php?alumno=no&idCurso=" + datos.id_curso + "' target='_blank'>Probar Curso</a>");

        $("#ver_topicos").html("");
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
                status = "<p class='text-success'><b>Activo</b></p>";
            } else {
                status = "<p class='text-error'><b>Inactivo</b></p>";
            }
            $("#ver_topicos").append("<td>" + status + "</td>")
            $("#ver_topicos").append("</tr>");

//            //Ver contenido
//            $("#ver_topicos").append("<tr>");
//            $("#ver_topicos").append("<td>Contenido HTML5</td>");
//            if (datos.topicos[i].url_unidad != null) {
//                $("#ver_topicos").append("<td><a href='" + datos.topicos[i].url_unidad + "' target='_blank'>Ver Contenido</a></td>")
//            } else {
//                $("#ver_topicos").append("<td></td>")
//            }

            $("#ver_topicos").append("</tr>");

        }

    }

    function setEditarDatosCurso(datos) {
        $(".cargando").html("");
        debugConsole("setEditarDatosCurso");

//        (Encoder.htmlDecode(datos.nombre_usuario)
        $("#id_curso").val(datos.id_curso);
        $("#ver_curso_moodle2").html(datos.curso_moodle).text();

        $("#edita_clave_curso").val(Encoder.htmlDecode(datos.clave_curso));
        $("#edita_nombre_curso").val(Encoder.htmlDecode(datos.nombre_curso));
        $("#edita_nombre_corto").val(Encoder.htmlDecode(datos.nombre_corto));
        $("#edita_categoria option[value=" + datos.id_categoria + "]").attr("selected", true);
        $("#edita_asignatura option[value=" + datos.id_asignatura + "]").attr("selected", true);
//        $("#comboGradoEscolares option[value=" + datos.id_grado_escolar + "]").attr("selected", true);
//        $("#comboGradoEscolares").change();

        $("#comboNivelEscolar option[value=" + datos.id_nivel + "]").attr("selected", true);
        cambioGrado = true;
        idGradoEscolar = datos.id_grado;
        $("#comboNivelEscolar").change();
//        $("#comboGradoEscolares option[value=" + datos.grado + "]").attr("selected", true);
        debugConsole('el grado es' + datos.grado);

        $("#edita_contenido").html("");
        for (i = 0; i < datos.topicos.length; i++) {
            //Titulo
            $("#edita_contenido").append("<tr>");
            $("#edita_contenido").append("<td><b>Unidad " + datos.topicos[i].no_unidad + "</b></td><td></td>");
            $("#edita_contenido").append("</tr>");

            //Nombre de la unidad
            $("#edita_contenido").append("<tr>");
            $("#edita_contenido").append("<td>Nombre Unidad:</td>");
            $("#edita_contenido").append("<td><input type='text' name='nombre_unidad[]' value='" + datos.topicos[i].nombre_unidad + "'/></td>")
            $("#edita_contenido").append("</tr>");

            //Descripcion de la unidad
            $("#edita_contenido").append("<tr>");
            $("#edita_contenido").append("<td>Descripción:</td>");
            $("#edita_contenido").append("<td><input type='text' name='descripcion[]' value='" + datos.topicos[i].descripcion + "'/></td>")
            $("#edita_contenido").append("</tr>");

            //Estatus de la unidad
            $("#edita_contenido").append("<tr>");
            $("#edita_contenido").append("<td>Estatus:</td>");
            var status = "<select name='status[]'>";
            if (datos.topicos[i].status === "1") {
                status += "<option value='1' selected='selected'>Activo</option>";
                status += "<option value='0'>Inactivo</option>";
            } else {
                status += "<option value='1'>Activo</option>";
                status += "<option value='0' selected='selected'>Inactivo</option>";
            }
            status += "</select>";
            debugConsole("status: " + status);
            $("#edita_contenido").append("<td>" + status + "</td>")
            $("#edita_contenido").append("</tr>");
            $("#edita_contenido").append("<input type='hidden' name='id_unidad[]' value='" + datos.topicos[i].id_unidad + "'>")

            //Ver contenido
            var msjContenido = "";
            if (datos.topicos[i].url_unidad != null) {
                msjContenido = "<p class='text-success'><b>Contenido HTML5 existente</b></p>";
            } else {
                msjContenido = "<p class='text-error'><b>Contenido HTML5 no existente</b></p>";
            }
            $("#edita_contenido").append("<tr>");
            $("#edita_contenido").append("<td>Contenido HTML5 <small>(*Si deja el cotenido vacío, este no se actualizará)</small>:</td>");
            $("#edita_contenido").append("<td><input type='file' name='contenido[]' class='contenido'>" + msjContenido + "</td>");

            $("#edita_contenido").append("</tr>");

        }

    }

    $("#editarCurso").submit(function(event) {
        $(".cargando").html("<h4><b>Actualizando datos, espere un momento...</b><img src='../img/loading.gif' width='30'></h4>");
    });

    /**
     * Selecciona todas la opciones de un combo multiple
     * @param {type} id
     * @returns {undefined}
     */
    function seleccionaCombos(id) {
        //Preparar opciones seleccionadas
        var option = document.getElementById(id);
        for (i = 0; i < option.length; i++) {
            debugConsole("Seleccionando:" + option.options[i].text);
            option.options[i].selected = true;
        }
    }

    /**
     * Verifica cuantas opciones han sido seleccionadas en un combo multiple
     * @param {type} id
     * @returns {Number}
     */
    function numeroSeleccionados(id) {
        var option = document.getElementById(id);
        var nSeleccionados = 0;
        for (i = 0; i < option.length; i++) {
            debugConsole("Seleccionando:" + option.options[i].text);
            if (option.options[i].selected) {
                nSeleccionados++;
            }
        }
        return nSeleccionados;
    }

    /**
     * Cursos abiertos
     * @type Boolean|Boolean|Boolean
     */

    var successFormFechas = true;
    var successFormFechasUnidades = true;

    $("#formAbrirCurso").submit(function(event) {
        debugConsole("Guardando curso abierto...");
        //Antes de enviar el formulario selecciona las opciones
        seleccionaCombos("select-to");
        seleccionaCombos("select-to2");
        seleccionaCombos("select-to3");

        if (numeroSeleccionados("select-to") == 0) {
            debugConsole("Se cancelo el submit")
            $("#errorCoord").show("fast");
            event.preventDefault();
        }
    });

    $("#formEnrolarTutores").submit(function(event) {
        debugConsole("Guardando tutores en curso abierto");

        debugConsole("seleccionando combo coords select-to");
        seleccionaCombos("select-to");
        debugConsole("selecconando combos select-to");
        $('.select-to').each(function() {
            var id = $(this).attr("title");
            debugConsole("id del select to:" + id);
            seleccionaCombos("select-to" + id);
        });

        if (numeroSeleccionados("select-to") == 0) {
            debugConsole("Se cancelo el submit")
            $("#errorCoord").show("fast");
            event.preventDefault();
        }

    });
    $(".btn").click(function() {
        if ($("#enrolar").length) {
            $("#cambio").val(1);
        }
    });
    $("#btnErrorCoord").click(function(event) {
        $("#errorCoord").hide("fast");
    });

    $("#fecha_inicio").change(function(event) {
        validaFechasCursoAbierto("fecha_inicio", "fecha_fin");
    });
    
    $("body").on("input", "#fecha_inicio", function(event){
        validaFechasCursoAbierto("fecha_inicio", "fecha_fin");
    });

    $("#fecha_fin").change(function(event) {
        validaFechasCursoAbierto("fecha_inicio", "fecha_fin");
    });
    
    $("body").on("input", "#fecha_fin", function(event){
        validaFechasCursoAbierto("fecha_inicio", "fecha_fin");
    });

    $("#edita_fecha_inicio").change(function(event) {
        validaFechasCursoAbierto("edita_fecha_inicio", "edita_fecha_fin");
    });
    
    $("body").on("input", "#edita_fecha_inicio", function(event){
        validaFechasCursoAbierto("edita_fecha_inicio", "edita_fecha_fin");
    });

    $("#edita_fecha_fin").change(function(event) {
        validaFechasCursoAbierto("edita_fecha_inicio", "edita_fecha_fin");
    });
    
    $("body").on("input", "#edita_fecha_fin", function(event){
        validaFechasCursoAbierto("edita_fecha_inicio", "edita_fecha_fin");
    });

    $("body").on("input", ".fecha_inicio", function() {
        validaFechasUnidades();
    });

    $("body").on("input", ".fecha_fin", function() {
        validaFechasUnidades();
    });

    function validaFechasUnidades() {
        debugConsole("validaFechasUnidades----------------------------------");
        var success = true;

        $.each($('.fecha_inicio'), function(index, value) {
            var fechaInicio = $(this).val();
            var fechaFin = $("#fecha_fin" + index).val();

            debugConsole("fechas de unidades " + index);
            debugConsole("noItem:" + index);
            debugConsole("fecha inicio:" + fechaInicio);
            debugConsole("fecha fin:" + fechaFin);

//            debugConsole("FI:"+fechaInicio);
//            debugConsole("FF:"+fechaFin);
            
            if (fechaInicio != "" && fechaFin != "") {
                if (!compare_dates(fechaInicio, fechaFin)) {
                    debugConsole("Fecha ok");
                    $("#errorFecha" + index).html("");
                    $("#errorFechaRango" + index).html("");
                    //comparar rango dentro de validacion
                    
                    
                    var fechaInicioCursoAbierto = $("#fecha_inicio").val();
                   // debugConsole(fechaInicioCursoAbierto);
                    var fechaFinCursoAbierto =$("#fecha_fin").val();
                    
//                    debugConsole(compare_dates( fechaFin, fechaInicioCursoAbierto ));
//                    debugConsole(compare_dates( fechaFinCursoAbierto, fechaInicio ));
                  

                
                
                    if( (compare_dates( fechaFin, fechaInicioCursoAbierto )    || sonFechasIguales(fechaFin,fechaInicioCursoAbierto)) && 
                        (compare_dates( fechaFinCursoAbierto, fechaFin )       || sonFechasIguales(fechaFinCursoAbierto,fechaFin)) &&
                        (compare_dates( fechaFinCursoAbierto, fechaInicio )    || sonFechasIguales(fechaFinCursoAbierto,fechaInicio)) &&
                        (compare_dates( fechaInicio, fechaInicioCursoAbierto ) || sonFechasIguales(fechaInicio,fechaInicioCursoAbierto)))
                    {
                        
                        
                    }else
                    {
                        success = false;
                        $("#errorFechaRango" + index).html("La fecha inicio y terminación de la unidad no están dentro del rango de fecha inicio y terminación del curso abierto. ");
                    }
                    
                    
                } else {
                    debugConsole("Fecha inicio mayor a la final");
                    success = false;
                    $("#errorFecha" + index).html("La fecha de inicio es mayor a la fecha fin.");
                     
                }
            }
        });

        if (success) {
            successFormFechasUnidades = true;
            verificaSuccessCursoAbierto();
        } else {
            successFormFechasUnidades = false;
            $("button[type=submit]").attr("disabled", "disabled");
        }
    }

    /**
     * Funcion generica que valida las fechas de un formulario
     * @param {type} idF1 id del input donde esta la fecha inicial
     * @param {type} idF2 id del input donde esta la fecha final
     * @returns {undefined}
     */
    function    validaFechasCursoAbierto(idF1, idF2) {
        var fechaInicio = $("#" + idF1).val();
        debugConsole("fecha inicio:" + fechaInicio);
        var fechaFin = $("#" + idF2).val();
        debugConsole("fecha fin:" + fechaFin);

        if (fechaInicio != "" && fechaFin != "") {
            if (!compare_dates(fechaInicio, fechaFin)) {
                debugConsole("Fecha ok");
                successFormFechas = true;
                $("#errorFecha").html("");
                verificaSuccessCursoAbierto();
            } else {
                debugConsole("Fecha inicio mayor a la final");
                successFormFechas = false;
                $("#errorFecha").html("La fecha de inicio es mayor a la fecha fin.");
                $("button[type=submit]").attr("disabled", "disabled");
            }
        }

    }

    function verificaSuccessCursoAbierto() {
        if (successFormFechas && successFormFechasUnidades) {
            $("button[type=submit]").removeAttr("disabled");
        }
    }

    $(".verCursoAbierto").click(function(event) {
        $(".cargando").html("<h4><b>Cargando datos...</b><img src='../img/loading.gif' width='30'></h4>");
        debugConsole("verCursoAbierto");
        var idAtributo = $(this).attr("name");
        debugConsole("Getting datos curso abierto:" + idAtributo);
        var datos = getDatosCursoAbiertoJSON(idAtributo, "ver");
    });

    $(".editaCursoAbierto").click(function(event) {
        $(".cargando").html("<h4><b>Cargando datos...</b><img src='../img/loading.gif' width='30'></h4>");
        debugConsole("editaCurso");
        var idAtributo = $(this).attr("name");
        debugConsole("Getting datos personales:" + idAtributo);
        var datos = getDatosCursoAbiertoJSON(idAtributo, "editar");
    });

    function getDatosCursoAbiertoJSON(idAtributo, tipo_funcion) {
        debugConsole("getDatosCusoAbiertoJSON");
        var datos;
        $.post("../sources/ControladorAdmin.Cursos.php", {consulta: "consultaInfoCursoAbierto", atributo: idAtributo}, function(datos) {
            debugConsole("Dentro del post");
            if (datos) {
                datos = jQuery.parseJSON(datos);
                debugConsole(datos);

                debugConsole("La informacion esta lista!");
                switch (tipo_funcion) {
                    case "ver":
                        setVerDatosCursoAbierto(datos);
                        break;
                    case "editar":
                        setEditarDatosCursoAbierto(datos);
                        break;
                }
            }
        });
        return datos;
    }

    function setVerDatosCursoAbierto(datos) {
        $(".cargando").html("");
        debugConsole("setVerDatosCursoAbierto");

        $("#ver_nombre_curso").html(datos.nombre_curso).text();
        $("#ver_nombre_curso_abierto").html(datos.nombre_curso_abierto).text();
        $("#ver_descripcion").html(datos.descripcion).text();
        $("#ver_fecha_inicio").html(datos.fecha_inicio).text();
        $("#ver_fecha_fin").html(datos.fecha_fin).text();


        $("#ver_fecha_unidades").html("");
        debugConsole("leng datos fechas unidades:" + datos.fechas_unidades.length);
        for (i = 0; i < datos.fechas_unidades.length; i++) {
            //Titulo
            $("#ver_fecha_unidades").append("<tr>");
            $("#ver_fecha_unidades").append("<td><b>Unidad " + datos.fechas_unidades[i].no_unidad + "</b></td><td></td>");
            $("#ver_fecha_unidades").append("</tr>");

            //Nombre de la unidad
            $("#ver_fecha_unidades").append("<tr>");
            $("#ver_fecha_unidades").append("<td>Nombre Unidad:</td>");
            $("#ver_fecha_unidades").append("<td>" + datos.fechas_unidades[i].nombre_unidad + "</td>");
            $("#ver_fecha_unidades").append("</tr>");

            //Descripcion de la unidad
            $("#ver_fecha_unidades").append("<tr>");
            $("#ver_fecha_unidades").append("<td>Descripción:</td>");
            $("#ver_fecha_unidades").append("<td>" + datos.fechas_unidades[i].descripcion + "</td>");
            $("#ver_fecha_unidades").append("</tr>");

            //Fecha de inicio
            $("#ver_fecha_unidades").append("<tr>");
            $("#ver_fecha_unidades").append("<td>Fecha de Inicio:</td>");
            $("#ver_fecha_unidades").append("<td>" + datos.fechas_unidades[i].fecha_inicio + "</td>");
            $("#ver_fecha_unidades").append("</tr>");

            //Fecha fin
            $("#ver_fecha_unidades").append("<tr>");
            $("#ver_fecha_unidades").append("<td>Fecha Fin:</td>");
            $("#ver_fecha_unidades").append("<td>" + datos.fechas_unidades[i].fecha_fin + "</td>");
            $("#ver_fecha_unidades").append("</tr>");
        }

//        //Tutores en el curso
//        $("#ver_fecha_unidades").append("<tr>");
//        $("#ver_fecha_unidades").append("<td colspan='2'><b>Tutores en el curso</b></td>");
//        $("#ver_fecha_unidades").append("</tr>");
//        
//        $("#ver_fecha_unidades").append("<tr>");
//        $("#ver_fecha_unidades").append("<td><b>Nombre</b></td>");
//        $("#ver_fecha_unidades").append("<td><b>Rol</b></td>");
//        $("#ver_fecha_unidades").append("</tr>");


//        for (i = 0; i < datos.tutores.length; i++) {
//            $("#ver_fecha_unidades").append("<tr>");
//            $("#ver_fecha_unidades").append("<td>"+datos.tutores[i].nombre+"</td>");
//            $("#ver_fecha_unidades").append("<td>"+datos.tutores[i].rol+"</td>");
//            $("#ver_fecha_unidades").append("</tr>");
//        }

    }

    function setEditarDatosCursoAbierto(datos) {
        $(".cargando").html("");
        debugConsole("setEditarDatosCursoAbierto");

//        (Encoder.htmlDecode(datos.nombre_usuario)
        $("#id_curso_abierto").val(datos.id_curso_abierto);
        $("#ver_nombre_curso2").html(datos.nombre_curso).text();

        $("#edita_nombre_curso_abierto").val(Encoder.htmlDecode(datos.nombre_curso_abierto));
        $("#edita_descripcion").val(Encoder.htmlDecode(datos.descripcion));
        $("#edita_fecha_inicio").val(Encoder.htmlDecode(datos.fecha_inicio));
        $("#edita_fecha_fin").val(Encoder.htmlDecode(datos.fecha_fin));

        $("#editar_fechas_unidades").html("");
        for (i = 0; i < datos.fechas_unidades.length; i++) {
            //Titulo
            $("#editar_fechas_unidades").append("<tr>");
            $("#editar_fechas_unidades").append("<td><b>Unidad " + datos.fechas_unidades[i].no_unidad + "</b></td><td></td>");
            $("#editar_fechas_unidades").append("</tr>");

            //Nombre de la unidad
            $("#editar_fechas_unidades").append("<tr>");
            $("#editar_fechas_unidades").append("<td>Nombre Unidad:</td>");
            $("#editar_fechas_unidades").append("<td>" + datos.fechas_unidades[i].nombre_unidad + "</td>");
            $("#editar_fechas_unidades").append("</tr>");

            //Descripcion de la unidad
            $("#editar_fechas_unidades").append("<tr>");
            $("#editar_fechas_unidades").append("<td>Descripción:</td>");
            $("#editar_fechas_unidades").append("<td>" + datos.fechas_unidades[i].descripcion + "</td>");
            $("#editar_fechas_unidades").append("</tr>");

            //Fecha de inicio
            $("#editar_fechas_unidades").append("<tr>");
            $("#editar_fechas_unidades").append("<td>Fecha de Inicio:</td>");
            $("#editar_fechas_unidades").append("<td><input type='date' name='fecha_inicio[]' id='fecha_inicio" + i + "' class='fecha_inicio' value='" + datos.fechas_unidades[i].fecha_inicio + "'></td>");
            $("#editar_fechas_unidades").append("</tr>");

            //Fecha fin
            $("#editar_fechas_unidades").append("<tr>");
            $("#editar_fechas_unidades").append("<td>Fecha de Fin:</td>");
            $("#editar_fechas_unidades").append("<td><input type='date' name='fecha_fin[]' id='fecha_fin" + i + "' class='fecha_fin' value='" + datos.fechas_unidades[i].fecha_fin + "'>\n\
                                                <label class='text-error' id='errorFecha" + i + "'></label></td>");
            $("#editar_fechas_unidades").append("</tr>");

            $("#editar_fechas_unidades").append("<input type='hidden' name='id_unidad[]' value='" + datos.fechas_unidades[i].id_unidad + "'>");
            $("#editar_fechas_unidades").append("<input type='hidden' name='id_fechas_unidades_cursos[]' value='" + datos.fechas_unidades[i].id_fecha_unidades_curso + "'>");
        }

        debugConsole($("#editar_fechas_unidades").val());
    }


    $("#editarCurso").submit(function(event) {
        $(".cargando").html("<h4><b>Actualizando datos, espere un momento...</b><img src='../img/loading.gif' width='30'></h4>");
    });

    /**
     * Alta de grupos
     */

    $("#id_escuela").change(function(event) {
        debugConsole("id_escuela change");
        if ($("#enrolar").length) {
            llenaSelectGruposDisponibles();
        } else {
            if ($("#cargaAlumnos").val() === "1") {
                cargaAlumnosDisponibles();
            }
        }
    });


    $("#id_empresa").change(function(event) {
        debugConsole("id_empersa change");
        if ($("#enrolar").length) {
            //llenaSelectGruposDisponibles();
        } else {
            if ($("#cargaAlumnos").val() === "1") {
                cargaAlumnosDisponibles();
            }
        }
    });

    $("#tipo_grupo").change(function(event) {
        debugConsole("tipo_grupo:" + $(this).val());
        if ($(this).val() === "0") {
            debugConsole("tipo_grupo: grupo estudiantes")
            $(".grupo_estudiante").show("fast");
            $(".grupo_profesionista").hide("fast");
        } else if ($(this).val() === "1") {
            debugConsole("tipo_grupo: grupo profesionistas");
            $(".grupo_estudiante").hide("fast");
            $(".grupo_profesionista").show("fast");
        }
        if ($("#enrolar").length) {
            llenaSelectGruposDisponibles();
        } else {
            if ($("#cargaAlumnos").val() === "1") {
                cargaAlumnosDisponibles();
            }
        }
    });

    function cargaAlumnosDisponibles() {
        $(".cargando").html("<h4><b>Cargando datos...</b><img src='../img/loading.gif' width='30'></h4>");
        var tipo_grupo = $("#tipo_grupo").val();

        debugConsole("cargaAlumnosDisponiboles");
        //Vaciar listas
        $(".grupo").html("");
        //Alumnos de escuela
        if (tipo_grupo === "0") {
            var idEscuela = $("#id_escuela").val();
            debugConsole("cargando alumnos de una escula:" + idEscuela);

            $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "getAlumnosEscuela", atributo: idEscuela}, function(datos) {
                debugConsole(datos);
                if (datos != "null") {
                    $("#noAlumnos").hide("fast");
                    datos = jQuery.parseJSON(datos);
                    debugConsole(datos);

                    for (i = 0; i < datos.length; i++) {
                        $("#select-from").append("<option value='" + datos[i].id_alumno + "'>" + datos[i].nombre_pila + " " + datos[i].primer_apellido + " " + datos[i].segundo_apellido + "</option>");
                    }

                } else {
                    debugConsole("no hay alumnos");
                    $("#noAlumnos").show("fast");
                }
                $(".cargando").html("");
            });
        } else if (tipo_grupo === "1") {
            //Alumnos de empresa
            var idEmpresa = $("#id_empresa").val();
            debugConsole("Cargando alumnos empresa:" + idEmpresa);

            $.post("../sources/Controlador.Admin.Usuarios.php", {consulta: "getAlumnosEmpresa", atributo: idEmpresa}, function(datos) {
                debugConsole(datos);
                if (datos != "null") {
                    $("#noAlumnos").hide("fast");
                    datos = jQuery.parseJSON(datos);
                    debugConsole(datos);

                    for (i = 0; i < datos.length; i++) {
                        $("#select-from").append("<option value='" + datos[i].id_alumno + "'>" + datos[i].nombre_pila + " " + datos[i].primer_apellido + " " + datos[i].segundo_apellido + "</option>");
                    }

                } else {
                    debugConsole("no hay alumnos");
                    $("#noAlumnos").show("fast");
                }
                $(".cargando").html("");
            });
        }

    }

    $("#formAltaGrupo").submit(function(event) {
        debugConsole("Guardando grupo...");
        //Antes de enviar el formulario selecciona las opciones
        seleccionaCombos("select-to");
    });

    $(".editarGrupo").click(function(even) {
        $(".cargando").html("<h4><b>Cargando datos...</b><img src='../img/loading.gif' width='30'></h4>");
        debugConsole("Editar grupo")
        var idGrupo = $(this).attr("name");

        $.post("../sources/Controlador.Admin.Grupos.php", {consulta: "consultaInfoGrupo", atributo: idGrupo}, function(datos) {
            debugConsole(datos);
            if (datos) {
                datos = jQuery.parseJSON(datos);
                debugConsole(datos);
                debugConsole("Los datos estan listos!");

                $(".cargando").html("");

                //Ajustar datos
                $("#id_grupo").val(datos.id_grupo);
                $("#edita_nombre_grupo").val(Encoder.htmlDecode(datos.nombre_grupo));
                $("#edita_clave").val(Encoder.htmlDecode(datos.clave));
                $("#tipo_grupo").val(datos.tipo_grupo);

                //Estudiante
                if (datos.tipo_grupo === "0") {
                    debugConsole("idInstitucion---------------:" + datos.id_institucion);
                    $("#idInstitucion option[value=" + datos.id_institucion + "]").attr("selected", true);
                    cambioEscuela = true;
                    id_escuela = datos.id_escuela;
                    $("#idInstitucion").change();
                } else if (datos.tipo_grupo === "1") {
                    //Empersarial
                    $("#id_empresa option[value=" + datos.id_empresa + "]").attr("selected", true);
                }

                $("#tipo_grupo").change();

            }
        });
    });

    var formSuccessGrupo = true;

//    $("#nombre_grupo").keyup(function(event) {
//        debugConsole("Verifica grupo");
//        var grupo = $(this).val();
//
//        $.post("../sources/Controlador.Admin.Grupos.php", {consulta: "consultaNombreGrupo", atributo: grupo}, function(respuesta) {
//            debugConsole("existe el grupo:" + respuesta);
//            if (respuesta === "true") {
//                formSuccessGrupo = false;
//                $("#errorGrupo").html("Este nombre ya ha sido registrado.");
//                $("button[type=submit]").attr("disabled", "disabled");
//            } else {
//                $("#errorGrupo").html("");
//                formSuccessGrupo = true;
//                verificaSuccessGrupo();
//            }
//        });
//    });
    $("#clave_grupo").on('input', (function(event) {
        debugConsole("Verifica grupo clave");
        var grupo = $(this).val();

        $.post("../sources/Controlador.Admin.Grupos.php", {consulta: "consultaClaveGrupo", atributo: grupo}, function(respuesta) {
            debugConsole("existe el grupo:" + respuesta);
            if (respuesta === "true") {
                formSuccessGrupo = false;
                $("#errorGrupo2").html("La Clave del grupo ya ha sido registrado.");
                $("button[type=submit]").attr("disabled", "disabled");
            } else {
                $("#errorGrupo2").html("");
                formSuccessGrupo = true;
                verificaSuccessGrupo();
            }
        });
    }));


    $("#edita_nombre_grupo").keyup(function(event) {
        debugConsole("Verifica grupo editando");
        var grupo = $(this).val();
        var idGrupo = $("#id_grupo").val();

        $.post("../sources/Controlador.Admin.Grupos.php", {consulta: "consultaNombreGrupoEditando", atributo: grupo, idGrupo: idGrupo}, function(respuesta) {
            debugConsole("existe el grupo:" + respuesta);
            if (respuesta === "true") {
                formSuccessGrupo = false;
                $("#errorGrupo").html("Este nombre ya ha sido registrado.");
                $("button[type=submit]").attr("disabled", "disabled");
            } else {
                $("#errorGrupo").html("");
                formSuccessGrupo = true;
                verificaSuccessGrupo();
            }
        });
    });

    function verificaSuccessGrupo() {
        if (formSuccessGrupo) {
            $("button[type=submit]").removeAttr("disabled");
        }
    }

    /**
     * Carga masiva!
     */

    $("#excel").change(function(event) {
        debugConsole("Cambia excel");
        var ext = "zip";
        if (!validaExtension($(this).val(), "xls") && !validaExtension($(this).val(), "xlsx")) {
            $(this).val("");
            $("#error").modal({
                show: true
            });
        }
    });

    $("#cargaMasiva").submit(function() {
        $(".bar").show("fast");
    });

    /**
     * Enrolar grupo, seleccionar grupos
     */
    $("#frmRelCursoGrupo").submit(function(event) {
        seleccionaCombos("select-to");
    });

    function actualizarScroll() {
        var div = document.getElementById('procesoMasiva');
        h = div.scrollHeight;
        div.scrollTop = h;
    }

    function cambiaStatusCargaTerminada(url_continuar) {
        $("#mensaje").html("Carga de Datos Concluida");
        $("#continuar").html("<a href='" + url_continuar + "' class='btn btn-success'>Continuar</a>");
    }

    function getAnio(fecha) {
        return fecha.substring(6, 10);
    }

    function getMes(fecha) {
        return  Number(fecha.substring(3, 5)) - 1;
    }

    function getDia(fecha) {
        return fecha.substring(0, 2);
    }

    /**
     * CHANGE CONTROL 0.99.6
     * FECHA DE MODIFICACION: 30 DE DICIEMBRE DEL 2013
     * AUTOR: JOSÉ MANUEL NIETO GÓMEZ
     * OBJETIVO: CREAR EVENTO PARA EDICION DE GRADOS ESCOLARES
     */

    $(".editaGrado").click(function(event) {
        var idAtributo = $(this).attr("id");
        debugConsole("Getting idAtributo:" + idAtributo);
        $.post("../sources/ControladorAdmin.Catalogos.php", {consulta: "getGradoEscolar", idAtributo: idAtributo}, function(respuesta) {
            debugConsole(respuesta);
            if (respuesta) {
                var atributo = jQuery.parseJSON(respuesta);
                $("#editaAtributo").val(Encoder.htmlDecode(atributo.nombre_grado));
                $("#idAtributo").val(atributo.id_grado_escolar);
                $("#id_nivel_escolar option[value=" + atributo.id_nivel + "]").attr("selected", true);

                debugConsole("Campos actualizados");
            } else {
                if (confirm("Ocurrió un error al cargar los datos, por favor recargue la página")) {
                    location.reload();
                }
            }

        });
    });

    /* En el change del campo file, cambiamos el val del campo ficticio por el del verdadero */
    $('#imagen').change(function() {
        $('#url-archivo').val($(this).val());
    });

    $('#cancelarUPFile').click(function()
    {
        window.location.reload();
    });
    /*Copia para reportes*/
    $('#excel').change(function() {
        $('#url-archivo').val($(this).val());
    });


    /*Copia para reportes*/
    $('#excel2').change(function() {
        $('#url-archivo2').val($(this).val());
    });

    $('#reportesFORM').submit(function(event) {
        if ($('#dpd1').length && $('#dpd2').length) {
            debugConsole("ENTRO");
            if (!compare_dates($("#dpd1").val(), $("#dpd2").val()))
            {//La fecha es Mayor que fecha2
                return;
            }
            alert("Rango de Fechas Invalido");
            event.preventDefault();
        }
    });

    $("#confirmAltaMasiva").click(function() {
        $("#progress").show("fast");
    });

    $("form").submit(function(event) {
        $(this).attr("disabled", "disabled");
        $("button[type=submit]").attr("disabled", "disabled");
        $("input[type=submit]").attr("disabled", "disabled");
    });

});


