var formulario = document.getElementById("form_recepcion");    

if($('#codVendedor').val() != '' && $('#codCliente').val()!=''){
    $('#otros').hide();
    $('#codVendedor').show();
    $('#codCliente').show();
    $('#codClientev').hide();
}
if($('#otros').val() != ''){
    $('#otros').show();
    $('#codVendedor').hide();
    $('#codCliente').hide();
    $('#codClientev').hide();
}
if($('#codClientev').val() != '' && $('#codVendedor').val() === '' && $('#otros').val()===''){
    $('#codClientev').show();
    $('#otros').hide();
    $('#codVendedor').hide();
    $('#codCliente').hide();
}

$(document).ready(function(){
    if($('#vuelto').val() == 'vuelto'){ 
        $('#tipo_doc option[value=B]').attr('selected','selected');
        $('#tipo_doc').attr('disabled','disabled');
    }else{
        $('#tipo_doc').prop('disabled',false);
    }
    $('#tablaUpdate').hide();
});


formulario.addEventListener('submit',function(e){
    e.preventDefault();
    var factura = document.getElementById("factura").value;
    var correlativo = document.getElementById("correlativo").value;
    var errorFactura = document.getElementById("errorFactura");
    var errorCorrelativo = document.getElementById("errorCorrelativo");

        if(factura.length === 0){
            errorFactura.innerHTML = `<p class="text-danger">Ingrese los datos de la factura</p>`;
            setTimeout(() => {
                errorFactura.innerHTML = ``;
            }, 5000);
        }
        if(correlativo.length === 0){
            errorCorrelativo.innerHTML = `<p class="text-danger">Ingrese los datos del correlativo</p>`;
            setTimeout(() => {
                errorCorrelativo.innerHTML = ``;
            }, 5000);
        }

        else{
            var data = new FormData(formulario);
            var url = "recepcion_efectivo_update.php";
            fetch(url,{
                method:'POST',
                body:data
            }).then((res) => res.json())
            .then((res) => {
                if(res){
                    if(res.error){
                        Swal.fire({
                                    icon: 'error',
                                    title: 'Error...',
                                    text: `${res.error}!`,
                                   
                                })
                    }else{       
                            if(res.mensaje){
                                Swal.fire({
                                    title: res.mensaje,
                                    showCancelButton: true,
                                    confirmButtonText: 'Crear',
                                    html:'<strong>Desea Crear un nuevo Movimiento?</strong>',
                                    icon: 'info'
                                  }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "principal1.php?page=recepcion_efectivo_crear&mod=1";
                                    }                                     
                                  })
                                  $('#tablaUpdate').show();
                                  dataRecepcion(res.data);
                            }                 
                    }
                }   
            }).catch((err) => {console.log(err)});  
        }        
    });
       
        
function dataRecepcion(dataSet){
        $(document).ready(function () {
            $('#tablaRecepcion').DataTable({
                "data": dataSet,
                "columns": [
                    { title: 'Fecha' },
                    { title: 'Vendedor' },
                    { title: 'Cliente' },
                    { title: 'Factura' },
                    { title: 'Moneda'},
                    { title: 'Monto' },
                    { title: 'Correlativo' },
                    { title: 'Tipo' },
                    { title: 'Debe'},
                    { title: 'Haber'},
                    { title: 'Saldo' },
                    { title: 'Observacion'}
                ],
                "responsive": true, 
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["excel", "pdf", "print", "colvis"],
                "oLanguage":{
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "sSearch": "Buscar:",
                    "sLengthMenu": "_MENU_ entradas por paginas",
                    "sZeroRecords": "Nada encontrado- lo sentimos",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "sInfoEmpty": "Mostrando 0 ta 0 de 0 entradas",
                    "sInfoFiltered": "(filtrado de _MAX_ entradas en total)",
                },
                destroy:true
            }).buttons().container().appendTo('#tablaRecepcion_wrapper .col-md-6:eq(0)');
        });
    }

    //muestraDenominacion();

   
    
    function regresa(){
        if($('#vuelto').val()=='vuelto'){
            window.location.href = `principal1.php?page=recepcion_efectivo_vuelto&mod=1`;
        }else{
            window.location.href = `principal1.php?page=recepcion_efectivo_detalle&mod=1`;
        }
    }

    function ocultar(){

        var tipo_doc = document.getElementById("tipo_doc").value;
        var form = document.getElementById("form_denominacion");
        if(tipo_doc === "A" || tipo_doc === "R"){
         //   form.innerHTML = ``;
        }
    }
