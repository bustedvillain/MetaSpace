<script src="../jqueryApis/datepickerBoot/bootstrap-datepicker.js"></script> 
<script type="text/javascript" >
    $(document).ready(function() {
        $('#vpFechaNacimiento').datepicker({
//            format: 'mm-dd-yyyy'
            format: 'dd/mm/yyyy'
        });

    });

</script>

<script type="text/javascript" >
    $(document).ready(function() {
        $('#fecha').datepicker({
            format: 'yyyy-mm-dd',
            viewMode: 2
        });
//        $("#date").datepicker().on('changeDate', function(ev) {
//            console.log("cambio de fecha");
//        });
    });

</script>