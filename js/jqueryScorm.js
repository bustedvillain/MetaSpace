//control de reportes
$(document).on("ready", function () {
    var curso = $("#courseId").val();
    $("#divforma1").uploadFile({
        url: "client.php",
        allowedTypes: "zip",
        formData: {
            "idCurso": curso,
            "idSeccion": 1,
            "nombreActividad": function () {
                return $("#nombre_unidad_1").val();
            }
        },
        dragDropStr: "<span><b>Arrastra hasta esta zona el archivo zip</b></span>",
        onSubmit: function (files)
        {
            if ($("#nombre_unidad_1").val() == "") {
                alert("debes de poner  nombre a la actividad 1");
                return false;
            }
        },
        onSuccess: function (files, data, xhr)
        {   $("#divforma1").hide("slow");
            //$("#resultado1").html(JSON.stringify(data) + "<br>");	
             //alert(JSON.stringify(data));	
        },
        maxFileCount: 1,
        maxFileCountErrorStr: "ya has cargado un archivo",
        cancelStr: "Aceptar",
        uploadErrorStr: "Es necesario primero agregar un nombre y descripción al bloque"
    });
    $("#divforma2").uploadFile({
        url: "client.php",
        allowedTypes: "zip",
        formData: {
            "idCurso": curso,
            "idSeccion": 2,
            "nombreActividad": function () {
                return $("#nombre_unidad_2").val()
            }
        },
        dragDropStr: "<span><b>Arrastra hasta esta zona el archivo zip</b></span>",
        onSubmit: function (files)
        {
            if ($("#nombre_unidad_2").val() == "") {
                alert("debes de poner  nombre a la actividad 2");
                return false;
            }
        },
        onSuccess: function (files, data, xhr)
        {
            $("#divforma2").hide("slow");
            //$("#resultado2").html(JSON.stringify(data) + "<br>");	
        },
        maxFileCount: 1,
        maxFileCountErrorStr: "ya has cargado un archivo",
        cancelStr: "Aceptar",
        uploadErrorStr: "Es necesario primero agregar un nombre y descripción al bloque"
    });
    $("#divforma3").uploadFile({
        url: "client.php",
        allowedTypes: "zip",
        formData: {
            "idCurso": curso,
            "idSeccion": 3,
            "nombreActividad": function () {
                return $("#nombre_unidad_3").val()
            }
        },
        dragDropStr: "<span><b>Arrastra hasta esta zona el archivo zip</b></span>",
        onSubmit: function (files)
        {
            if ($("#nombre_unidad_3").val() == "") {
                alert("debes de poner  nombre a la actividad 3");
                return false;
            }
        },
        onSuccess: function (files, data, xhr)
        {
            $("#divforma3").hide("slow");
            //$("#resultado3").html(JSON.stringify(data) + "<br>");	
        },
        maxFileCount: 1,
        maxFileCountErrorStr: "ya has cargado un archivo",
        cancelStr: "Aceptar",
        uploadErrorStr: "Es necesario primero agregar un nombre y descripción al bloque"
    });
    $("#divforma4").uploadFile({
        url: "client.php",
        allowedTypes: "zip",
        formData: {
            "idCurso": curso,
            "idSeccion": 4,
            "nombreActividad": function () {
                return $("#nombre_unidad_4").val()
            }
        },
        dragDropStr: "<span><b>Arrastra hasta esta zona el archivo zip</b></span>",
        onSubmit: function (files)
        {
            if ($("#nombre_unidad_4").val() == "") {
                alert("debes de poner  nombre a la actividad 5");
                return false;
            }
        },
        onSuccess: function (files, data, xhr)
        {
            $("#divforma4").hide("slow");
            //$("#resultado4").html(JSON.stringify(data) + "<br>");	
        },
        maxFileCount: 1,
        maxFileCountErrorStr: "ya has cargado un archivo",
        cancelStr: "Aceptar",
        uploadErrorStr: "Es necesario primero agregar un nombre y descripción al bloque"
    });
    $("#divforma5").uploadFile({
        url: "client.php",
        allowedTypes: "zip",
        formData: {
            "idCurso": curso,
            "idSeccion": 5,
            "nombreActividad": function () {
                return $("#nombre_unidad_5").val()
            }
        },
        dragDropStr: "<span><b>Arrastra hasta esta zona el archivo zip</b></span>",
        onSubmit: function (files)
        {
            if ($("#nombre_unidad_5").val() == "") {
                alert("debes de poner  nombre a la actividad 5");
                return false;
            }
        },
        onSuccess: function (files, data, xhr)
        {
            $("#divforma5").hide("slow");
            //$("#resultado5").html(JSON.stringify(data) + "<br>");	
        },
        maxFileCount: 1,
        maxFileCountErrorStr: "ya has cargado un archivo",
        cancelStr: "Aceptar",
        uploadErrorStr: "Es necesario primero agregar un nombre y descripción al bloque"
    });
    $("#divforma6").uploadFile({
        url: "client.php",
        allowedTypes: "zip",
        formData: {
            "idCurso": curso,
            "idSeccion": 6,
            "nombreActividad": function () {
                return $("#nombre_unidad_6").val()
            }
        },
        dragDropStr: "<span><b>Arrastra hasta esta zona el archivo zip</b></span>",
        onSubmit: function (files)
        {
            if ($("#nombre_unidad_6").val() == "") {
                alert("debes de poner  nombre a la actividad 6");
                return false;
            }
        },
        onSuccess: function (files, data, xhr)
        {
            $("#divforma6").hide("slow");
            //$("#resultado6").html(JSON.stringify(data) + "<br>");	
        },
        maxFileCount: 1,
        maxFileCountErrorStr: "ya has cargado un archivo",
        cancelStr: "Aceptar",
        uploadErrorStr: "Es necesario primero agregar un nombre y descripción al bloque"
    });

    $("#tipoReporte").change(function () {
        if ($("#tipoReporte").val() == 1 || $("#tipoReporte").val() == 3) {
            $("#search").attr("required", "required");
            $("#grupo").removeAttr("required");
            $("#divGrupo").css("display", "none");
            $("#divSearch").css("display", "block");
        } else if ($("#tipoReporte").val() == 2 || $("#tipoReporte").val() == 4) {
            $("#grupo").attr("required", "required");
            $("#search").removeAttr("required");
            $("#divGrupo").css("display", "block");
            $("#divSearch").css("display", "none");
        } else {
            $("#search").removeAttr("required");
            $("#grupo").removeAttr("required");
            $("#divGrupo").css("display", "none");
            $("#divSearch").css("display", "none");

        }
    });
    $("#reporteScorm").submit(function () {
        $.ajax({
            type: 'POST',
            url: 'scorm.php',
            data: $("#reporteScorm").serialize(),
            beforeSend: function () {
                $("#alerta").html("<img src='cargando.gif'/>");
                $("#alerta").css("display", "block");
            },
            success: function (data) {
                $("#alerta").html("");
                $("#response").html(data);
                genRep();
            }
        });
        return false;
    });

    function genRep() {
        $(".userReport").on("click", function () {
            var id = $(this).attr("id");
            var user = $(this).attr("name");
            $.ajax({
                type: 'POST',
                url: 'scorm.php',
                data: 'id=' + id + '&userCourse=' + user,
                beforeSend: function () {
                    $("#alerta").html("<img src='cargando.gif'/>");
                    $("#alerta").css("display", "block");
                },
                success: function (data) {
                    $("#alerta").html("");
                    $("#response").html(data);
                    Rep();
                }
            });
        });

        $(".userReportGest").on('click', function () {
            var id = $(this).attr("id");
            var user = $(this).attr("name");
            $.ajax({
                type: 'POST',
                url: 'scorm.php',
                data: 'id=' + id + '&userReportGest=' + user,
                beforeSend: function () {
                    $("#alerta").html("<img src='cargando.gif'/>");
                    $("#alerta").css("display", "block");
                },
                success: function (data) {
                    $("#alerta").html("");
                    $("#response").html(data);
                }
            });
        });

    }
    function Rep() {
        $(".courseUser").on("click", function () {
            var curso = $(this).attr("id");
            var id = $("#id").val();
            var user = $("#user").val();
            $.ajax({
                type: 'POST',
                url: 'scorm.php',
                data: 'id=' + id + '&userReport=' + user + '&course=' + curso,
                beforeSend: function () {
                    $("#alerta").html("<img src='cargando.gif'/>");
                    $("#alerta").css("display", "block");
                },
                success: function (data) {
                    $("#alerta").html("");
                    $("#response").html(data);
                }
            });
        });

        $(".userReportGest").on('click', function () {
            var id = $(this).attr("id");
            var user = $(this).attr("name");
            $.ajax({
                type: 'POST',
                url: 'scorm.php',
                data: 'id=' + id + '&userReportGest=' + user,
                beforeSend: function () {
                    $("#alerta").html("<img src='cargando.gif'/>");
                    $("#alerta").css("display", "block");
                },
                success: function (data) {
                    $("#alerta").html("");
                    $("#response").html(data);
                }
            });
        });

    }

});