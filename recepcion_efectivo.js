$(function(){
  $("#tablaRecepcion").DataTable({
    "responsive": true, 
    "lengthChange": false,
    "autoWidth": false,
    "language": texto_español_datatables,
    "destroy":true
  }).buttons().container().appendTo('#tablaRecepcion_wrapper .col-md-6:eq(0)');

  $("#tablaRevAnu").DataTable({
    "responsive": true, 
    "lengthChange": false,
    "autoWidth": false,
    "language": texto_español_datatables
  }).buttons().container().appendTo('#tablaRevAnu_wrapper .col-md-6:eq(0)');
  
  $("#tablaVueltos").DataTable({
    "responsive": true, 
    "lengthChange": false,
    "autoWidth": false,
    "searching": false,
    "language": texto_español_datatables
  }).buttons().container().appendTo('#tablaVueltos_wrapper .col-md-6:eq(0)');

  $("#tablaBS").DataTable({
    "responsive": true, 
    "lengthChange": false,
    "autoWidth": false,
    "searching":false,
    "language": texto_español_datatables
  }).buttons().container().appendTo('#tablaBS_wrapper .col-md-6:eq(0)');

  $("#tablaDL").DataTable({
    "responsive": true, 
    "lengthChange": false,
    "autoWidth": false,
    "searching":false,
    "language": texto_español_datatables
  }).buttons().container().appendTo('#tablaDL_wrapper .col-md-6:eq(0)');

  $("#tablaEU").DataTable({
    "responsive": true, 
    "lengthChange": false,
    "autoWidth": false,
    "searching":false,
    "language": texto_español_datatables
  }).buttons().container().appendTo('#tablaEU_wrapper .col-md-6:eq(0)');

})
function regresa(){
  window.location.href = "principal1.php?page=recepcion_efectivo_principal&mod=1";
}

