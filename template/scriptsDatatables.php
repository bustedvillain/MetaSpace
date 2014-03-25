<?php echo "\n"; ?>
<!--Inicia scripts para datatables-->
<script type="text/javascript" language="javascript" src="../jqueryApis/datatables/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../jqueryApis/datatables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../jqueryApis/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#example').dataTable({
            "sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "oTableTools": {
                "aButtons": [
                    "copy",
                    "print",
                    {
                        "sExtends": "collection",
                        "sButtonText": 'Save <span class="caret" />',
                        "aButtons": ["csv", "xls", "pdf"]
                    }
                ]
            },
            "oLanguage": {
                "sLengthMenu": "Mostrando _MENU_ registros por tabla",
                "sZeroRecords": "No se encuentran registros",
                "sInfo": "Mostrando de _START_ a _END_ de _TOTAL_ totales",
                "sInfoEmpty": "Mostrando 0 de 0 registros",
                "sInfoFiltered": "(filstrado de _MAX_ registros totales)",
                "sSearch": "Buscar:",
                "oPaginate": {
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                }


            },
            "aaSorting": [[ 1, "desc" ]]
        });

        $('.tabla').dataTable({
            "sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "oTableTools": {
                "aButtons": [
                    "copy",
                    "print",
                    {
                        "sExtends": "collection",
                        "sButtonText": 'Save <span class="caret" />',
                        "aButtons": ["csv", "xls", "pdf"]
                    }
                ]
            },
            "oLanguage": {
                "sLengthMenu": "Mostrando _MENU_ registros por tabla",
                "sZeroRecords": "No se encuentran registros",
                "sInfo": "Mostrando de _START_ al _END_ de _TOTAL_ totales",
                "sInfoEmpty": "",
                "sInfoFiltered": "(filtrado de _MAX_ registros totales)",
                "sSearch": "Buscar:",
                "oPaginate": {
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "aaSorting": [[ 2, "desc" ]]


            },
        });
    });
</script>
<!--Finaliza scripts para datatables-->

<script type="text/javascript" language="javascript" src="../jqueryApis/datepickerBoot/bootstrap-datepicker.js"></script>
