$(function () {
    $("#tablaBS")
    .DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        searching: false,
        language: texto_español_datatables,
        destroy: true,
    })
    .buttons()
    .container()
    .appendTo("#tablaBS_wrapper .col-md-6:eq(0)");
    
    $("#tablaDL")
    .DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        searching: false,
        destroy: true,
        language: texto_español_datatables,
    })
    .buttons()
    .container()
    .appendTo("#tablaDL_wrapper .col-md-6:eq(0)");
    
    $("#tablaEU")
    .DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        searching: false,
        destroy: true,
        language: texto_español_datatables,
    })
    .buttons()
    .container()
    .appendTo("#tablaEU_wrapper .col-md-6:eq(0)");
});

$(document).ready(function(){
    $('#vistaFormulario').hide();
    $('#mostrarTabla').hide();
    $('#procesar').hide();
    $('#form_bs').hide();
    $('#form_dl').hide();
    $('#form_eu').hide();    
    $('#tablaBS').hide();
    $('#tablaDL').hide();
    $('#tablaEU').hide();
    $('#billetes').hide();
    $('#tipoEntidad').hide();
    $('#tpagar').hide();
    $('#vueltos1').hide();
    tipoTransaccion();
})

function tipoTransaccion(){
    var moneda = $('#moneda').val(); 
    const fecha = $('#fecha').val();
    const url = 'recepcion_efectivo_buscar.php';
    fetch(url,{
        method:'POST',
        body:JSON.stringify({fecha:fecha.toString(),moneda:moneda.toString()})
    }).then((res) => res.json())
    .then((res)=>{
        $('#correl').empty();
        $('#tipo_doc').on('change',function(){
            $('#tipoEntidad').show();
            if($('#tipo_doc').val() === 'I'){
                $('#texto').text('Total a Pagar');
                $('#monto').prop('placeholder','Ingrese monto a pagar');
                $('#denominacion').hide();
                checkMoneda(moneda);
            }
            if($('#tipo_doc').val() === 'E'){
                $('#denominacion').show();
                $('#texto').text('Total a Retirar');
                $('#monto').prop('placeholder','Ingrese monto a retirar');
                
                if(moneda === 'BS'){
                    $('#tablaBS').show();
                    muestraDenominacion(moneda,res.denom);
                    checkMoneda(moneda);
                }
                if(moneda === 'DL'){
                    $('#tablaDL').show();
                    muestraDenominacion(moneda,res.denom);
                    checkMoneda(moneda);
                }
                if(moneda === 'EU'){
                    $('#tablaEU').show();
                    muestraDenominacion(moneda,res.denom);
                    checkMoneda(moneda);
                }
            }   
        });

        $('#correl').append('<h5>Correlativo Nro '+res.correlativo+'</h5>')
        $('#correlativo').val(res.correlativo);         
    });  
}

function checkMoneda(moneda){
    if(moneda === 'BS'){
        $('#form_bs').show();
        $('#form_dl').hide();
        $('#form_eu').hide();
        $('#BS2').prop('checked',true);
        $('#DL2').attr('disabled','disabled');
        $('#EU2').attr('disabled','disabled');

    }
    if(moneda === 'DL'){
        $('#form_bs').hide();
        $('#form_dl').show();
        $('#form_eu').hide();
        $('#BS2').attr('disabled','disabled');
        $('#DL2').prop('checked',true);
        $('#EU2').attr('disabled','disabled');
    }
    if(moneda === 'EU'){
        $('#form_bs').hide();
        $('#form_dl').hide();
        $('#form_eu').show();
        $('#BS2').attr('disabled','disabled');
        $('#DL2').attr('disabled','disabled');
        $('#EU2').prop('checked',true);
    }
}

var formulario = document.getElementById("form_recepcion");  
formulario.addEventListener('submit',function(e){
    e.preventDefault();
    var factura = $('#factura').val();
    var foraneo = $('#otros').val();
    var selectEntidad = tipoCaja(); 
    var selectVend = $('#vendedor').select2('val');
    var selectClie = $('#cliente').select2('val');
    console.log(selectEntidad);
    if(factura.length === 0){
        $('#errorFactura').append('<p class="text-danger">Ingrese los datos del documento</p>');
        setTimeout(() => {
            $('#errorFactura').empty();
        }, 5000);
    }
    if(selectEntidad === 'O' && foraneo.length === 0){
        $('#errorOtros').append('<p class="text-danger">Ingrese los datos del Foraneo</p>');
        setTimeout(() => {
            $('#errorOtros').empty();
        }, 5000);
    }
    if(selectEntidad === 'V' && selectVend.length === 0){
        $('#errorVendedor').append('<p class="text-danger">Ingrese los datos del Vendedor</p>');
        setTimeout(() => {
            $('#errorVendedor').empty();
        }, 5000);
    }
    if(selectEntidad === 'V' && selectClie.length === 0){
        $('#errorCliente').append('<p class="text-danger">Ingrese los datos del Cliente</p>');
        setTimeout(() => {
            $('#errorCliente').empty();
        }, 5000);
    }

    if(selectEntidad === 'V' && factura.length != 0 && selectVend.length != 0 && selectClie.length != 0){
        Swal.fire({
            title: 'Desea realizar esta Operacion?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var moneda = $('#moneda').val();
                var data = new FormData(formulario);
                var url = "recepcion_efectivo_inserta.php";
                fetch(url,{
                    method:'POST',
                    body:data
                }).then((res) => res.json())
                .then((res) => {
                    if(res.length == 0){
                        $('#tablaRecepcion').hide();
                    }
                    if(res.length != 0){
                        if(res.error){
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: `${res.error}!`,
                                
                            })
                            formulario.reset();               
                        }
                        if(res.error2){
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: `${res.error2}!`,
                                
                            })
                            formulario.reset();               
                        }
                        else{
                            formulario.reset();                          
                            const fecha = $('#fecha').val();
                            const url = 'recepcion_efectivo_buscar.php';
                            fetch(url,{
                                method:'POST',
                                body:JSON.stringify({fecha:fecha.toString(),moneda:moneda.toString()})
                            }).then((res) => res.json())
                            .then((res)=>{
                                muestraDenominacion($('#moneda').val(),res.denom);       
                            });    
                            $('#vendedor').val('').change();
                            $('#cliente').val('').change();
                            $('#otros').val('');
                            $('#tipoEntidad').hide();
                            $('#vistaFormulario').hide();
                            $('#tpagar').hide();
                            $('#mostrarTabla').show();
                            $('#tablaRecepcion').show();
                            checkMoneda(moneda);
                            $('#moneda').val();
                            $('#correl').empty();
                            console.log("LOG "+res.correlativo);
                            $('#correl').append('<h5>Correlativo Nro '+res.correlativo+'</h5>');
                            $('#correlativo').val(res.correlativo);
                            dataRecepcion(res.data);
                        }
                    }
                }).catch((err) => {console.log(err)}); 
            }
        })
    }    
    if(selectEntidad === 'O' && factura.length != 0 && foraneo.length != 0){
        Swal.fire({
            title: 'Desea realizar esta Operacion?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var moneda = $('#moneda').val();
                var data = new FormData(formulario);
                var url = "recepcion_efectivo_inserta.php";
                fetch(url,{
                    method:'POST',
                    body:data
                }).then((res) => res.json())
                .then((res) => {
                    if(res.length == 0){
                        $('#tablaRecepcion').hide();
                    }
                    if(res.length != 0){
                        if(res.error){
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: `${res.error}!`,
                                
                            })
                            formulario.reset();               
                        }
                        if(res.error2){
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: `${res.error2}!`,
                                
                            })
                            formulario.reset();               
                        }
                        else{
                            formulario.reset();                          
                            const fecha = $('#fecha').val();
                            const url = 'recepcion_efectivo_buscar.php';
                            fetch(url,{
                                method:'POST',
                                body:JSON.stringify({fecha:fecha.toString(),moneda:moneda.toString()})
                            }).then((res) => res.json())
                            .then((res)=>{
                                muestraDenominacion($('#moneda').val(),res.denom);       
                            });    
                            $('#vendedor').val('').change();
                            $('#cliente').val('').change();
                            $('#otros').val('');
                            $('#tipoEntidad').hide();
                            $('#vistaFormulario').hide();
                            $('#tpagar').hide();
                            $('#mostrarTabla').show();
                            $('#tablaRecepcion').show();
                            checkMoneda(moneda);
                            $('#moneda').val();
                            $('#correl').empty();
                            $('#correl').append('<h5>Correlativo Nro '+res.correlativo+'</h5>');
                            $('#correlativo').val(res.correlativo);
                            dataRecepcion(res.data);
                        }
                    }
                }).catch((err) => {console.log(err)}); 
            }
        })
    }    
});



function dataRecepcion(dataSet){
    $(document).ready(function () {
        $('#tablaRecepcion').DataTable({
            "data": dataSet,
            "columns": [
                { title: 'Correl' },
                { title: 'Fecha' },
                { title: 'Vendedor' },
                { title: 'Cliente' },
                { title: 'Foraneo'},
                { title: 'Factura' },
                { title: 'Tipo' },
                { title: 'Moneda'},
                { title: 'Debe'},
                { title: 'Haber'},
                { title: 'Saldo' },
                { title: 'Observacion'}
                ],
            "responsive": true, 
            "lengthChange": false,
            "autoWidth": false,
            "searching": false,
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

function muestraDenominacion(moneda,res){             
    if(moneda === "BS"){
        $('#tablaBS').show();    
        $('#tablaDL').hide();
        $('#tablaEU').hide();                     
        $(document).ready(function () {
            $('#tablaBS').DataTable({
                "data": res,
                "responsive": true, 
                "lengthChange": false,
                "autoWidth": false,
                "searching": false,
                "paging":false,
                "info" : false,
                "oLanguage":{
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "sLengthMenu": "_MENU_ entradas por paginas",
                    "sZeroRecords": "Nada encontrado- lo sentimos",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "sInfoEmpty": "Mostrando 0 ta 0 de 0 entradas",
                    "sInfoFiltered": "(filtrado de _MAX_ entradas en total)",
                },
                destroy:true
            }).buttons().container().appendTo('#tablaBS_wrapper .col-md-6:eq(0)');
        });
    }

    if(moneda === "DL"){
        $('#tablaDL').show();    
        $('#tablaBS').hide();
        $('#tablaEU').hide();  
        $('#tablaDL').DataTable({
            "data": res,
            "responsive": true, 
            "lengthChange": false,
            "autoWidth": false,
            "searching": false,
            "paging":false,
            "info" : false,
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
        }).buttons().container().appendTo('#tablaDL_wrapper .col-md-6:eq(0)');
    }

    if(moneda === "EU"){
        $('#tablaEU').show();    
        $('#tablaDL').hide();
        $('#tablaBS').hide();    
        $('#tablaEU').DataTable({
            "data": res,
            "responsive": true, 
            "lengthChange": false,
            "autoWidth": false,
            "searching": false,
            "paging":false,
            "info" : false,
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
        }).buttons().container().appendTo('#tablaEU_wrapper .col-md-6:eq(0)');
    }  
}

function tipoCaja(){
    var selectBox = document.querySelector("#seleccion");
    var selectValue = selectBox.options[selectBox.selectedIndex].value;
    $('#vistaFormulario').show();
    $('#tpagar').show();
    $('#procesar').show();
    if(selectValue === 'O'){
        $('#vendedor1').hide();
        $('#vueltos1').hide();
        $('#cliente1').hide();
        $('#vendedor').val('').change();
        $('#cliente').val('').change();
        $('#foraneo').show();
        for(let i = 1; i <= 7; i++){
            $('#denom_'+i).prop("disabled",false);
        }
    }
    if(selectValue === 'V'){    
        $('#vueltos1').show();  
        $('#vendedor1').show();
        $('#cliente1').show();    
        $('#cliente').next('.select2-container').show();
        $('#vendedor').next('.select2-container').show();
        $('#clientev').next('.select2-container').hide();
        $('#foraneo').hide();
        $('#otros').val('');
        for(let i = 1; i <= 7; i++){
            $('#denom_'+i).prop("disabled",false);
        }
    }
    
    return selectValue;
}


function key(){
    $(function(){
        $("#monto1").keydown(function(event){
                //alert(event.keyCode);
            if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
                return false;
            }
        });
        $("#monto2").keydown(function(event){
                //alert(event.keyCode);
            if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && event.keyCode !==190  && event.keyCode !==110 && event.keyCode !==8 && event.keyCode !==9  ){
                return false;
            }
        });
        $("#monto3").keydown(function(event){
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
    window.location.href = `principal1.php?page=recepcion_efectivo_principal&mod=1`;
}



document.getElementById('monto').addEventListener('keyup',presion,false);
document.getElementById('denom_1').addEventListener('keyup',presion,false);
document.getElementById('denom_2').addEventListener('keyup',presion,false);
document.getElementById('denom_3').addEventListener('keyup',presion,false);
document.getElementById('denom_4').addEventListener('keyup',presion,false);
document.getElementById('denom_5').addEventListener('keyup',presion,false);
document.getElementById('denom_6').addEventListener('keyup',presion,false);
document.getElementById('denom_7').addEventListener('keyup',presion,false);
document.getElementById('denom_8').addEventListener('keyup',presion,false);
document.getElementById('denom_9').addEventListener('keyup',presion,false);
document.getElementById('denom_10').addEventListener('keyup',presion,false);
document.getElementById('denom_11').addEventListener('keyup',presion,false);
document.getElementById('denom_12').addEventListener('keyup',presion,false);
document.getElementById('denom_15').addEventListener('keyup',presion,false);
document.getElementById('denom_16').addEventListener('keyup',presion,false);
document.getElementById('denom_17').addEventListener('keyup',presion,false);
document.getElementById('denom_18').addEventListener('keyup',presion,false);
document.getElementById('denom_19').addEventListener('keyup',presion,false);
document.getElementById('denom_20').addEventListener('keyup',presion,false);
document.getElementById('denom_21').addEventListener('keyup',presion,false);

function presion()
{
    var sum1=document.getElementById('monto').value;
    var sum2 = 0;
    if(document.getElementById('denom_1').value.length>0){
        sum2 = sum2 + (100 * Number(document.getElementById('denom_1').value));
    }
    if(document.getElementById('denom_2').value.length>0){
        sum2 = sum2 + (50 * Number(document.getElementById('denom_2').value));
    }
    if(document.getElementById('denom_3').value.length>0){
        sum2 = sum2 + (20 * Number(document.getElementById('denom_3').value));
    }
    if(document.getElementById('denom_4').value.length>0){
        sum2 = sum2 + (10 * Number(document.getElementById('denom_4').value));
    }
    if(document.getElementById('denom_5').value.length>0){
        sum2 = sum2 + (5 * Number(document.getElementById('denom_5').value));
    }
    if(document.getElementById('denom_6').value.length>0){
        sum2 = sum2 + (1 * Number(document.getElementById('denom_6').value));
    }
    if(document.getElementById('denom_7').value.length>0){
        sum2 = sum2 + (0.5 * Number(document.getElementById('denom_7').value));
    }
    if(document.getElementById('denom_8').value.length>0){
        sum2 = sum2 + (100 * Number(document.getElementById('denom_8').value));
    }
    if(document.getElementById('denom_9').value.length>0){
        sum2 = sum2 + (50 * Number(document.getElementById('denom_9').value));
    }
    if(document.getElementById('denom_10').value.length>0){
        sum2 = sum2 + (20 * Number(document.getElementById('denom_10').value));
    }
    if(document.getElementById('denom_11').value.length>0){
        sum2 = sum2 + (10 * Number(document.getElementById('denom_11').value));
    }
    if(document.getElementById('denom_12').value.length>0){
        sum2 = sum2 + (5 * Number(document.getElementById('denom_12').value));
    }
    if(document.getElementById('denom_13').value.length>0){
        sum2 = sum2 + (2 * Number(document.getElementById('denom_13').value));
    }
    if(document.getElementById('denom_14').value.length>0){
        sum2 = sum2 + (1 * Number(document.getElementById('denom_14').value));
    }
    if(document.getElementById('denom_15').value.length>0){
        sum2 = sum2 + (500 * Number(document.getElementById('denom_15').value));
    }
    if(document.getElementById('denom_16').value.length>0){
        sum2 = sum2 + (200 * Number(document.getElementById('denom_16').value));
    }
    if(document.getElementById('denom_17').value.length>0){
        sum2 = sum2 + (100 * Number(document.getElementById('denom_17').value));
    }
    if(document.getElementById('denom_18').value.length>0){
        sum2 = sum2 + (50 * Number(document.getElementById('denom_18').value));
    }
    if(document.getElementById('denom_19').value.length>0){
        sum2 = sum2 + (20 * Number(document.getElementById('denom_19').value));
    }
    if(document.getElementById('denom_20').value.length>0){
        sum2 = sum2 + (10 * Number(document.getElementById('denom_20').value));
    }
    if(document.getElementById('denom_21').value.length>0){
        sum2 = sum2 + (5 * Number(document.getElementById('denom_21').value));
    }

    var vueltos=sum2-sum1;
    if($('#moneda').val() === 'BS'){
        var mon = 'Bs';
    }
    
    if($('#moneda').val() === 'EU'){
        var mon = '€';
    }
    if($('#moneda').val() === 'DL'){
        var mon = '$';
    }
    
    if(vueltos < 0){
        document.getElementById('vueltos').innerHTML=Number(0).toFixed(2)+" "+mon;
    }else{
        document.getElementById('vueltos').innerHTML=Number(vueltos).toFixed(2)+" "+mon;
    }
}



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

