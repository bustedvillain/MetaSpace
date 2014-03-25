<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap-transition.js"></script>
<script src="../assets/js/bootstrap-alert.js"></script>
<script src="../assets/js/bootstrap-modal.js"></script>
<script src="../assets/js/bootstrap-dropdown.js"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrap-tab.js"></script>
<script src="../assets/js/bootstrap-tooltip.js"></script>
<script src="../assets/js/bootstrap-popover.js"></script>
<script src="../assets/js/bootstrap-button.js"></script>
<script src="../assets/js/bootstrap-collapse.js"></script>
<script src="../assets/js/bootstrap-carousel.js"></script>
<script src="../assets/js/bootstrap-typeahead.js"></script>  
<script src="../assets/js/bootstrap-typeahead.js"></script>  
<?php include("../template/jsDatePicker.php"); ?>


<script src="../js/encoder.js"></script> 

<script src="../js/jqueryAdmin.js"></script> 
<script src="../js/jqueryAdmin2.js"></script> 
<script src="../js/jquery.js"></script> 


<script type="text/javascript">
    $(document).on("ready", arranca);
    function arranca() {
//        alert('si arranco');
        if ($(".smCargando").length) {
            if ($(".frameMoodle1").length) {
                $(".smCargando").show();
                $(".frameMoodle1").load(function() {
                    $(".smCargando").css("display", "none");
                });
            }
            if ($(".frameMoodle2").length) {
                $(".smCargando").show();
                $(".frameMoodle2").load(function() {
                    $(".smCargando").css("display", "none");
                });
            }
            if ($(".frameMoodle3").length) {
                $(".smCargando").show();
                $(".frameMoodle3").load(function() {
                    $(".smCargando").css("display", "none");
                });
            }
            //Para alta masiva
            if ($(".frameMoodleMasiva").length) {
                $(".smCargando").show();
                $(".frameMoodleMasiva").load(function() {
                    $(".smCargando").css("display", "none");
                    var location = $(".frameMoodleMasiva").attr("name");
                    alert("Inserción Exitosa.");
                    window.location.href=location;
                });
            }
          

        }
    }
</script>


<?php // include '../sources/Funciones.php';
imprimeScriptDeTiempoMaxSesion();
?>