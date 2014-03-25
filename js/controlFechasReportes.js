var nowTemp = new Date();
var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
 
var checkin = $('#dpd1').datepicker({
    format: 'dd/mm/yyyy',
    onRender: function(date) {
    return  '';
  }
}).on('changeDate', function(ev) {

  checkin.hide();
  $('#dpd2')[0].focus();
}).data('datepicker');

var checkout = $('#dpd2').datepicker({
  format: 'dd/mm/yyyy',
  onRender: function(date) {
   return  '';
  }
}).on('changeDate', function(ev) {
  checkout.hide();
}).data('datepicker');

