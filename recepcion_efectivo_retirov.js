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
                        muestraDenominacion();
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

muestraDenominacion();

function muestraDenominacion(){
    var form = document.getElementById("form_denominacion");
    var data = new FormData(formulario);
    var url = "recepcion_efectivo_denom.php";
    fetch(url,{
        method:'POST',
        body: data
    }).then((res) => res.json())
    .then((res) => {              
        if(res.data[0].moneda === "Bolivares"){
            $('#tablaBS').show();    
            $('#tablaDL').hide();
            $('#tablaEU').hide();                        
            $(document).ready(function () {
                $('#tablaBS').DataTable({
                    "data": res.data,
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

            $(function(){
                form.innerHTML = `
                <div class="col-sm-1">     
                <label>Total 100 Bs</label>                                 
                <input type="text" name="denom_1" id="denom_1" class="form-control font-weight-bold" placeholder="${res.data[0].total_100}">
                </div>
                <div class="col-sm-1">  
                <label>Total 50 Bs</label>                                    
                <input type="text" name="denom_2" id="denom_2" class="form-control font-weight-bold" placeholder="${res.data[0].total_50}" >
                </div>
                <div class="col-sm-1">  
                <label>Total 20 Bs</label>                                    
                <input type="text" name="denom_3" id="denom_3" class="form-control font-weight-bold" placeholder="${res.data[0].total_20}" > 
                </div>
                <div class="col-sm-1">  
                <label>Total 10 Bs</label>                                    
                <input type="text" name="denom_4" id="denom_4" class="form-control font-weight-bold" placeholder="${res.data[0].total_10}" >
                </div>
                <div class="col-sm-1"> 
                <label>Total 5 Bs</label>                                     
                <input type="text" name="denom_5" id="denom_5" class="form-control font-weight-bold" placeholder="${res.data[0].total_5}" >
                </div>
                <div class="col-sm-1"> 
                <label>Total 1 Bs</label>                                     
                <input type="text" name="denom_6" id="denom_6" class="form-control font-weight-bold" placeholder="${res.data[0].total_2}" >
                </div>
                <div class="col-sm-1"> 
                <label>Total 0.5 Bs</label>                                     
                <input type="text" name="denom_7" id="denom_7" class="form-control font-weight-bold" placeholder="${res.data[0].total_1}" >
                </div>
                `;

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
            });

}

if(res.data[0].moneda === "Dolares"){
    $('#tablaDL').show();    
    $('#tablaEU').hide();
    $('#tablaBS').hide();   
    $('#tablaDL').DataTable({
        "data": res.data,
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
    $(function(){
        form.innerHTML = `
        <div class="col-sm-1">  
        <label>Total 100 $</label>   
        <input type="text" name="denom_1" id="denom_1" class="form-control font-weight-bold" placeholder="${res.data[0].total_100}">
        </div>
        <div class="col-sm-1"> 
        <label>Total 50 $</label>                                     
        <input type="text" name="denom_2" id="denom_2" class="form-control font-weight-bold" placeholder="${res.data[0].total_50}">
        </div>
        <div class="col-sm-1">
        <label>Total 20 $</label>                                      
        <input type="text" name="denom_3" id="denom_3" class="form-control font-weight-bold" placeholder="${res.data[0].total_20}">
        </div>
        <div class="col-sm-1"> 
        <label>Total 10 $</label>                                     
        <input type="text" name="denom_4" id="denom_4" class="form-control font-weight-bold" placeholder="${res.data[0].total_10}">
        </div>
        <div class="col-sm-1">
        <label>Total 5 $</label>                                      
        <input type="text" name="denom_5" id="denom_5" class="form-control font-weight-bold" placeholder="${res.data[0].total_5}">
        </div>
        <div class="col-sm-1">
        <label>Total 2 $</label>                                      
        <input type="text" name="denom_6" id="denom_6" class="form-control font-weight-bold" placeholder="${res.data[0].total_2}">
        </div>
        <div class="col-sm-1">
        <label>Total 1 $</label>                                      
        <input type="text" name="denom_7" id="denom_7" class="form-control font-weight-bold" placeholder="${res.data[0].total_1}">
        </div>
        `;


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
    });

}

if(res.data[0].moneda === "Euros"){
    $('#tablaEU').show();    
    $('#tablaDL').hide();
    $('#tablaBS').hide();   
    $('#tablaEU').DataTable({
        "data": res.data,
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

    $(function(){
        form.innerHTML = `
        <div class="col-sm-1">
        <label>Total 500 €</label>                                      
        <input type="text" name="denom_1" id="denom_1" class="form-control font-weight-bold" placeholder="${res.data[0].total_100}">
        </div>
        <div class="col-sm-1"> 
        <label>Total 200 €</label>                                     
        <input type="text" name="denom_2" id="denom_2" class="form-control font-weight-bold" placeholder="${res.data[0].total_50}">
        </div>
        <div class="col-sm-1">
        <label>Total 100 €</label>                                      
        <input type="text" name="denom_3" id="denom_3" class="form-control font-weight-bold" placeholder="${res.data[0].total_20}">
        </div>
        <div class="col-sm-1"> 
        <label>Total 50 €</label>                                     
        <input type="text" name="denom_4" id="denom_4" class="form-control font-weight-bold" placeholder="${res.data[0].total_10}">
        </div>
        <div class="col-sm-1"> 
        <label>Total 20 €</label>                                     
        <input type="text" name="denom_5" id="denom_5" class="form-control font-weight-bold" placeholder="${res.data[0].total_5}">
        </div>
        <div class="col-sm-1">
        <label>Total 10 €</label>                                      "
        <input type="text" name="denom_6" id="denom_6" class="form-control font-weight-bold" placeholder="${res.data[0].total_2}">
        </div>
        <div class="col-sm-1"> 
        <label>Total 5 €</label>                                     
        <input type="text" name="denom_7" id="denom_7" class="form-control font-weight-bold" placeholder="${res.data[0].total_1}">
        </div>
        `;


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
    });
}
                    //$('#correlativo').val('').attr("placeholder","Correlativo...");
for(let i = 1; i <= 7; i++){
    $('#denom_'+i).val('');
}
})
}

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
        form.innerHTML = ``;
    }else{
        muestraDenominacion();
    }
}
