$(function(){
  $("#tablaBS").DataTable({
    "responsive": true, 
    "lengthChange": false,
    "autoWidth": false,
    "language": texto_español_datatables,
    "destroy":true
  }).buttons().container().appendTo('#tablaBS_wrapper .col-md-6:eq(0)');

  $("#tablaDL").DataTable({
    "responsive": true, 
    "lengthChange": false,
    "autoWidth": false,
    "language": texto_español_datatables
  }).buttons().container().appendTo('#tablaDL_wrapper .col-md-6:eq(0)');

  $("#tablaEU").DataTable({
    "responsive": true, 
    "lengthChange": false,
    "autoWidth": false,
    "language": texto_español_datatables
  }).buttons().container().appendTo('#tablaEU_wrapper .col-md-6:eq(0)');
})

$(document).ready(function(){
  $('#form_bs').hide();
  $('#form_dl').hide();
  $('#form_eu').hide();
  $('#editTablaBS').hide();
  $('#editTablaDL').hide();
  $('#editTablaEU').hide();
});

function anular(factura){
  const url = 'recepcion_efectivo_buscar.php';
  fetch(url,{
    method:'POST',
    body:JSON.stringify({factura:factura.toString()}),
  }).then((res) => res.json())
  .then((res) => {
    if(res.data.length > 0){
      Swal.fire({
        title: 'Desea Anular esta Documento?',
        text: "No podrás revertir esta operacion!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          const url = 'recepcion_efectivo_update.php';
          fetch(url,{
            method:'POST',
            body: JSON.stringify({factura:factura.toString(),tipo_doc:'A'})
          }).then((res) => res.json())
          .then((res) => {
            if(res.ok){
              Swal.fire({
                title:'Procesado!',
                html:`${res.ok}`,
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Crear',
                icon:'success',
              }).then((result)=>{
                if(result.isConfirmed){
                  window.location.href = "principal1.php?page=recepcion_efectivo_crear&mod=1";
                }else{
                  window.location.href = "principal1.php?page=recepcion_efectivo_detalle&mod=1";
                }
              });
            }
            if(res.error){}
          });
        }
      })
    }
  }).catch((err) => console.log(err));
  
}


function editar(factura,correlativo){
  const url = 'recepcion_efectivo_buscar.php';
  fetch(url,{
    method:'POST',
    body:JSON.stringify({factura:factura.toString(),correlativo:correlativo.toString()}),
  }).then((res) => res.json())
  .then((res) => {
    if(res.data.length > 0){
      res.data.forEach(element => {
        if(element.otros){
          $('#foraneo').show();
          $('#otros').val(element.otros);
          $('#vendedor1').hide();
          $('#cliente1').hide();
        }else{
          $('#foraneo').hide();
          $('#vendedor1').show();
          $('#cliente1').show();
          $("#vendedor").val(element.codVendedor).trigger('change');
          $("#cliente").val(element.codCliente).trigger('change');
        }
        $('#factura1').val(element.factura);
        $('#correlativo').val(element.correlativo);
        $('#correlativo1').val(element.correlativo);
        $('#moneda').val(element.moneda);
        $('#monto').val(element.monto);
        $('#observacion').val(element.observacion);
        $('.modal-title').append('<h5>Documento Nro '+element.correlativo+'</h5>');
        if(element.moneda == 'Bolivares'){
          $('#form_bs').show();
          res.denom.forEach(element => {
            $('#denom_1').val(element.denom_1);
            $('#denom_2').val(element.denom_2);
            $('#denom_3').val(element.denom_3);
            $('#denom_4').val(element.denom_4);
            $('#denom_5').val(element.denom_5);
            $('#denom_6').val(element.denom_6);
            $('#denom_7').val(element.denom_7);
            key();
          });
        }
        if(element.moneda == 'Dolares'){
          $('#form_dl').show();
          res.denom.forEach(element => {
            $('#denom_8').val(element.denom_1);
            $('#denom_9').val(element.denom_2);
            $('#denom_10').val(element.denom_3);
            $('#denom_11').val(element.denom_4);
            $('#denom_12').val(element.denom_5);
            $('#denom_13').val(element.denom_6);
            $('#denom_14').val(element.denom_7);
            key();
          });
        }
        if(element.moneda == 'Euros'){
          $('#form_eu').show();
          $('#denom_15').val(element.denom_1);
          $('#denom_16').val(element.denom_2);
          $('#denom_17').val(element.denom_3);
          $('#denom_18').val(element.denom_4);
          $('#denom_19').val(element.denom_5);
          $('#denom_20').val(element.denom_6);
          $('#denom_21').val(element.denom_7);
          key();
        }
      });
    }
  }).catch((err) => console.log(err));

  const form = document.getElementById('edit_form');
  form.addEventListener('submit',function(e){
    e.preventDefault();
    Swal.fire({
      title: 'Desea Editar esta Documento?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Confirmar',
    }).then((result) => {
      if (result.isConfirmed) {
        const url = 'recepcion_efectivo_update.php';
        fetch(url,{
          method:'POST',
          body: new FormData(form)
        }).then((res) => res.json())
        .then((res) => {
          if(res.ok){
            Swal.fire({
              title:'Procesado!',
              html:`${res.ok}`,
              confirmButtonText: 'Ok',
              icon:'success',
            }).then((result)=>{
              if(result.isConfirmed){
                window.location.href = "principal1.php?page=recepcion_efectivo_detalle&mod=1";
              }
            });
          }
          if(res.error){
            Swal.fire({
              title:'Error en la Operacion!',
              html:`${res.error}`,
              showCancelButton: true,
              confirmButtonText: 'Ok',
              icon:'info',
            })
          }
        });
      }
    })
  })
}

function key(){
  $(function(){
    $("#monto").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_1").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_2").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_3").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_4").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_5").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_6").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_7").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_8").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_9").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_10").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_11").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_12").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_13").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_14").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_15").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_16").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_17").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_18").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_19").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_20").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
    $("#denom_21").keydown(function(event){
          //alert(event.keyCode);
      if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
        return false;
      }
    });
  });
}

function regresa(){
  window.location.href = "principal1.php?page=recepcion_efectivo_principal&mod=1";
}

function ocultar(){
  $('.modal-title').empty('h5');
  $('#vendedor').val('').change();
  $('#cliente').val('').change();
  $('#otros').val('');
}

$('.close').on('click',function(){
  $('.modal-title').empty('h5');
  $('#vendedor').val('').change();
  $('#cliente').val('').change();
  $('#otros').val('');
});
//EVITA QUEL MODAL SE CIERRE AL HACER CLICK FUERA
$('.modal').modal({
  backdrop:'static',
  keyboard:true,
  show:false
})

$(function(){
  $('#vendedor').one('select2:open', function(e) {
    $('input.select2-search__field').prop('placeholder', 'Buscar...');
  });
});


$(function(){
  $('#cliente').one('select2:open', function(e) {
    $('input.select2-search__field').prop('placeholder', 'Buscar...');
  });
});