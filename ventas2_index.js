var search_by_enterpress = false;
var tabla_documentos;
var tabla_documentos_borrar;

function init() {
    $('#imprimirModal').on('shown.bs.modal', function () {
        $('#numerod_input').focus();
    });

    $("#btn_buscar_doc").on("click", function (e) {
        let tipofac = $('#tipo').val();
        switch (tipofac) {
        case "A": $('#title_doc').text('Buscar Factura'); break;
        case "B": $('#title_doc').text('Buscar Devolución Factura'); break;
        case "C": $('#title_doc').text('Buscar Nota de Entrega'); break;
        case "D": $('#title_doc').text('Buscar Devolución N/E'); break;
        case "E": $('#title_doc').text('Buscar Pedido'); break;
        case "F": $('#title_doc').text('Buscar Presupuesto'); break;
        case "G": $('#title_doc').text('Buscar Fact. en Espera'); break;
        }
        mostrarModalDoc();
    });

    $("#btn_buscar_doc1").on("click", function (e) {
        let tipofac = $('#tipo1').val();
        switch (tipofac) {
        case "A": $('#title_doc1').text('Buscar Factura'); break;
        case "B": $('#title_doc1').text('Buscar Devolución Factura'); break;
        case "C": $('#title_doc1').text('Buscar Nota de Entrega'); break;
        case "D": $('#title_doc1').text('Buscar Devolución N/E'); break;
        case "E": $('#title_doc1').text('Buscar Pedido'); break;
        case "F": $('#title_doc1').text('Buscar Presupuesto'); break;
        case "G": $('#title_doc1').text('Buscar Fact. en Espera'); break;
        }
        mostrarModalDocBorrar();
    });
}

function limpiarModal() {
    $("#tipo_doc").val("");
    $("#numerod_input").val("");
    $("#rs_input").val("");
    $("#sucursal_input").val("");
    $('#alert_error').hide();
    $('#tipo').val("");
    $('#nrounico').val("");
    $('#btn_modal_imp_aceptar').prop('disabled', true);
}

function limpiarModalEliminar() {
    $("#eliminar_title").text("BORRAR");
    $("#numerod1_input").val("");
    $("#rs1_input").val("");
    $("#sucursal1_input").val("");
    $('#alert_error1').hide();
    $('#tipo1').val("");
    $('#nrounico1').val("");
    $('#btn_modal_borrar_aceptar').prop('disabled', true);
}

$(document).ready(function () {
    // imprimmir
    $("#numerod_input").on('keydown', () => {
        $('#alert_error').hide();
        $("#rs_input").val("");
        $('#nrounico').val("");
        if (!search_by_enterpress) {
            $('#btn_modal_imp_aceptar').prop('disabled', true);
            if ($('#btn_modal_imp_aceptar').hasClass('btn-saint')) {
                $("#btn_modal_imp_aceptar").removeClass('btn-saint');
                $("#btn_modal_imp_aceptar").addClass('btn-outline-saint');
            }
        }
    }).keydown();

    // borrar
    $("#numerod1_input").on('keydown', () => {
        $('#alert_error1').hide();
        $("#rs1_input").val("");
        $('#nrounico1').val("");
        if (!search_by_enterpress) {
            $('#btn_modal_borrar_aceptar').prop('disabled', true);
            if ($('#btn_modal_borrar_aceptar').hasClass('btn-saint')) {
                $("#btn_modal_borrar_aceptar").removeClass('btn-saint');
                $("#btn_modal_borrar_aceptar").addClass('btn-outline-saint');
            }
        }
    }).keydown();

    // imprimir
    $("#buscaDoc").on('keyup', () => {
        $('#alert_error').hide();
        $("#rs_input").val("");
        $('#nrounico').val("");
        $('#numerod_input').val($("#buscaDoc").val());
        listarDocumentos($("#buscaDoc").val());
    }).keyup();

    // borrar
    $("#buscaDoc1").on('keyup', () => {
        $('#alert_error1').hide();
        $("#rs1_input").val("");
        $('#nrounico1').val("");
        $('#numerod1_input').val($("#buscaDoc1").val());
        listarDocumentosBorrar($("#buscaDoc1").val());
    }).keyup();
});

function enterKeyPressed(it, event) {
    if (event.keyCode == 13) {
        search_by_enterpress = true;
        buscarDoc();
    }

    return true;
}

function mostrar(tipo = "") {
    let sucursal = $('#sucursal_hidden').val();
    limpiarModal();

    $('#imprimirModal').modal('show');
    switch (tipo) {
    case "A": $('#tipo_doc').val('Factura'.toLocaleUpperCase() ); break;
    case "B": $('#tipo_doc').val('Devolución Factura'.toLocaleUpperCase() ); break;
    case "C": $('#tipo_doc').val('Nota de Entrega'.toLocaleUpperCase() ); break;
    case "D": $('#tipo_doc').val('Devolución N/E'.toLocaleUpperCase() ); break;
    case "E": $('#tipo_doc').val('Pedido'.toLocaleUpperCase() ); break;
    case "F": $('#tipo_doc').val('Presupuesto'.toLocaleUpperCase() ); break;
    }
    $('#tipo').val(tipo);
    $('#sucursal_input').val(sucursal);
}

function eliminar(tipo = "") {
    let sucursal = $('#sucursal_hidden').val();
    limpiarModalEliminar();

    $('#eliminarModal').modal('show');
    switch (tipo) {
    case "A": $('#eliminar_title').text('Borrar Factura'.toLocaleUpperCase() ); break;
    case "B": $('#eliminar_title').text('Borrar Devolución Factura'.toLocaleUpperCase() ); break;
    case "C": $('#eliminar_title').text('Borrar Nota de Entrega'.toLocaleUpperCase() ); break;
    case "D": $('#eliminar_title').text('Borrar Devolución N/E'.toLocaleUpperCase() ); break;
    case "E": $('#eliminar_title').text('Borrar Pedido'.toLocaleUpperCase() ); break;
    case "F": $('#eliminar_title').text('Borrar Presupuesto'.toLocaleUpperCase() ); break;
    }
    $('#tipo1').val(tipo);
    $('#sucursal1_input').val(sucursal);
}

function mostrarModalDoc() {
    $('#documentosModal').modal('show');
    let search = $('#numerod_input').val();
    $('#buscaDoc').val(search);
    listarDocumentos(search);
}

function mostrarModalDocBorrar() {
    $('#documentos1Modal').modal('show');
    let search = $('#numerod1_input').val();
    $('#buscaDoc1').val(search);
    listarDocumentosBorrar(search);
}

function listarDocumentos(search = "") {
    let tipo_input = $('#tipo').val();
    if (tabla_documentos instanceof $.fn.dataTable.Api) {
        $('#documentos_data').DataTable().clear().destroy();
    }
    tabla_documentos = $('#documentos_data').dataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        "sDom": "tipr",
        "ajax":
        {
            url: 'ventas2_index_data.php?op=listar_docs',
            type: "POST",
            dataType: "json",
            data: { search: search, tipo: tipo_input },
            error: function (e) {
                console.log(e.responseText);
                Swal.fire({
                    title: "Error!",
                    html: e.responseText,
                    icon: 'danger',
                    allowOutsideClick: false
                });
            },
            complete: function () {
                // elimina el error de compatibilidad quitando el with
                $("#documentos_data").css({ 'width': '' });
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,//Por cada 10 registros hace una paginación
        "order": [[0, "desc"]],//Ordenar (columna,orden)
        "language": texto_español_datatables
    }).DataTable();
}

function listarDocumentosBorrar(search = "") {
    let tipo_input = $('#tipo1').val();
    if (tabla_documentos_borrar instanceof $.fn.dataTable.Api) {
        $('#documentos1_data').DataTable().clear().destroy();
    }
    tabla_documentos_borrar = $('#documentos1_data').dataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        "sDom": "tipr",
        "ajax":
        {
            url: 'ventas2_index_data.php?op=listar_docs_borrar',
            type: "POST",
            dataType: "json",
            data: { search: search, tipo: tipo_input },
            error: function (e) {
                console.log(e.responseText);
                Swal.fire({
                    title: "Error!",
                    html: e.responseText,
                    icon: 'danger',
                    allowOutsideClick: false
                });
            },
            complete: function () {
                // elimina el error de compatibilidad quitando el with
                $("#documentos1_data").css({ 'width': '' });
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,//Por cada 10 registros hace una paginación
        "order": [[0, "desc"]],//Ordenar (columna,orden)
        "language": texto_español_datatables
    }).DataTable();
}

function cerrarModalImp() {
    $('#imprimirModal').modal('hide');
}

function cerrarModalBorrar() {
    $('#eliminarModal').modal('hide');
}

function cerrarModalBuscarDoc() {
    $('#documentosModal').modal('hide');
}

function cerrarModalBuscarDocBorrar() {
    $('#documentos1Modal').modal('hide');
}

function buscarDoc() {
    let numerod_input = $('#numerod_input').val();
    let tipo_input = $('#tipo').val();

    $.ajax({
        url: "ventas2_index_data.php?op=buscar_doc",
        type: "POST",
        dataType: "json",
        data: {
            numerod : numerod_input,
            tipo    : tipo_input,
        },
        error: function (e) {
            console.log(e.responseText);
            Swal.fire({
                title: "Error!",
                html: e.responseText,
                icon: 'danger',
                allowOutsideClick: false
            });
        },
        success: function (data) {
            if (!jQuery.isEmptyObject(data)) {
                let { numerod, nrounico, descrip } = data;
                $('#rs_input').val(descrip);
                $('#nrounico').val(nrounico);
                if (descrip !== '') {
                    $('#btn_modal_imp_aceptar').prop('disabled', false);
                    if ($('#btn_modal_imp_aceptar').hasClass('btn-outline-saint')) {
                        $("#btn_modal_imp_aceptar").removeClass('btn-outline-saint');
                        $("#btn_modal_imp_aceptar").addClass('btn-saint');
                    }
                } else {
                    $('#alert_error').show();
                    $('#btn_modal_imp_aceptar').prop('disabled', true);
                    if ($('#btn_modal_imp_aceptar').hasClass('btn-saint')) {
                        $("#btn_modal_imp_aceptar").removeClass('btn-saint');
                        $("#btn_modal_imp_aceptar").addClass('btn-outline-saint');
                    }
                }
            }
        },
        complete: function () {
            search_by_enterpress = false;
        }
    });
}

function buscarDocBorrar() {
    let numerod_input = $('#numerod1_input').val();
    let tipo_input = $('#tipo1').val();

    $.ajax({
        url: "ventas2_index_data.php?op=buscar_doc",
        type: "POST",
        dataType: "json",
        data: {
            numerod : numerod_input,
            tipo    : tipo_input,
        },
        error: function (e) {
            console.log(e.responseText);
            Swal.fire({
                title: "Error!",
                html: e.responseText,
                icon: 'danger',
                allowOutsideClick: false
            });
        },
        success: function (data) {
            if (!jQuery.isEmptyObject(data)) {
                let { numerod, nrounico, descrip } = data;
                $('#rs1_input').val(descrip);
                $('#nrounico1').val(nrounico);
                if (descrip !== '') {
                    $('#btn_modal_borrar_aceptar').prop('disabled', false);
                    if ($('#btn_modal_borrar_aceptar').hasClass('btn-outline-saint')) {
                        $("#btn_modal_borrar_aceptar").removeClass('btn-outline-saint');
                        $("#btn_modal_borrar_aceptar").addClass('btn-saint');
                    }
                } else {
                    $('#alert_error1').show();
                    $('#btn_modal_borrar_aceptar').prop('disabled', true);
                    if ($('#btn_modal_borrar_aceptar').hasClass('btn-saint')) {
                        $("#btn_modal_borrar_aceptar").removeClass('btn-saint');
                        $("#btn_modal_borrar_aceptar").addClass('btn-outline-saint');
                    }
                }
            }
        },
        complete: function () {
            search_by_enterpress = false;
        }
    });
}

function seleccionarDoc(numerod) {
    $('#alert_error').hide();
    $('#numerod_input').val(numerod);
    cerrarModalBuscarDoc()
    setTimeout(() => { buscarDoc(); }, 800);
    
}

function seleccionarDocBorrar(numerod) {
    $('#alert_error1').hide();
    $('#numerod1_input').val(numerod);
    cerrarModalBuscarDocBorrar()
    setTimeout(() => { buscarDocBorrar(); }, 800);
    
}

//ACCION AL PRECIONAR EL BOTON PDF.
$(document).on("click", "#btn_modal_imp_aceptar", function () {
    let numerod_input = $('#numerod_input').val();
    let tipo_input = $('#tipo').val();
    let nrounico_input = $('#nrounico').val();
    if (numerod_input !== "" && tipo_input !== "" && nrounico_input !== "") {
        switch (tipo_input) {
        case 'A': window.open('ventas2_fac_pdf1.php?&i=' + nrounico_input, '_blank'); break;
        case 'B': window.open('ventas2_devolfac_pdf.php?&i=' + nrounico_input, '_blank'); break;
        case 'C': window.open('ventas2_ne_pdf.php?&i=' + nrounico_input, '_blank'); break;
        case 'D': window.open('ventas2_devolne_pdf.php?&i=' + nrounico_input, '_blank'); break;
        case 'E': window.open('ventas2_ped_pdf.php?&i=' + nrounico_input, '_blank'); break;
        case 'F': window.open('ventas2_presu_pdf.php?&i=' + nrounico_input, '_blank'); break;
        }
        
        cerrarModalImp();
    }
});

$(document).on("click", "#btn_modal_borrar_aceptar", function () {
    let numerod_input = $('#numerod1_input').val();
    let tipo_input = $('#tipo1').val();
    let nrounico_input = $('#nrounico1').val();
    if (numerod_input !== "" && tipo_input !== "" && nrounico_input !== "") {

        let documento_text = ''
        switch (tipo_input) {
        case "A": documento_text = 'Factura'; break;
        case "B": documento_text = 'Devolución Factura'; break;
        case "C": documento_text = 'Nota de Entrega'; break;
        case "D": documento_text = 'Devolución N/E'; break;
        case "E": documento_text = 'Pedido'; break;
        case "F": documento_text = 'Presupuesto'; break;
        case "G": documento_text = 'Fact. en Espera'; break;
        }
        Swal.fire({
            title: "¿Estas Seguro de Borrar "+documento_text+" "+numerod_input+" ?",
            text: "esta acción es irreversible",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Borrar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "ventas2_index_data.php?op=eliminar_doc",
                    method: "POST",
                    dataType: "json",
                    data: {
                        numerod: numerod_input,
                        tipo: tipo_input,
                        nrounico: nrounico_input 
                    },
                    error: function (e) {
                        console.log(e.responseText);
                    },
                    success: function (data) {
                        let { icono, mensaje } = data;

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                        Toast.fire({
                            icon: icono,
                            title: mensaje
                        });
                        
                        //verifica si el mensaje de insercion contiene error
                        if( !icono.includes('error') ) {
                            cerrarModalBorrar();
                        }
                    }
                });
            }
        })

        
    }
});

init();