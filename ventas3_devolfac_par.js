var tabla_productos;
var tabla_documentos;
var cant_renglones = 9;
var flag_fechae = false;

var generacion_documento_en_proceso = false;

Number.prototype.format_money = function (n, x, s, c) {
    /**
     * Number.prototype.format(n, x, s, c)
     *
     * @param integer n: length of decimal
     * @param integer x: length of whole part
     * @param mixed   s: sections delimiter
     * @param mixed   c: decimal delimiter
     */
 const re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
 num = this.toFixed(Math.max(0, ~~n));

 return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};



function isNumberKey(txt, evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46) {
        //Check if the text already contains the . character
        if (txt.value.indexOf('.') === -1) {
            return true;
        } else {
            return false;
        }
    } else {
        if (charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;
    }
    return true;
}



//Función que se ejecuta al inicio
function init() {
    $('#clie').one('select2:open', function (e) {
        $('input.select2-search__field').prop('placeholder', 'Buscar...');
    });

    $('#vend').one('select2:open', function (e) {
        $('input.select2-search__field').prop('placeholder', 'Buscar...');
    });

    $('#depo').one('select2:open', function (e) {
        $('input.select2-search__field').prop('placeholder', 'Buscar...');
    });

    $("#btn_prod").on("click", function (e) {
        mostrarProd();
    });

    $("#btn_cargar").on("click", function (e) {
        mostrarDoc();
    });

    $("#btn_limpiar").on("click", function (e) {
        $("#clie").val("").change();
        limpiar();
        init_tabla();
        evaluarTabla();
        correlYtasa();
        $('#carga_nc').val("0");
        $("#cxc_data tbody").empty();
        $('#nc_form')[0].reset();
    });

    $("#btn_limpiar_tabla").on("click", function (e) {
        $('#correl_c_text').text('');
        $('#correl_c').text('');
        $('#tipofac_c').text('');
        init_tabla();
        evaluarTabla();
    });

    $("#btn_ttl").on("click", function (e) {
        if ($('#correl_c').text().length > 0 && $('#tipofac_c').text() === 'A') {
            $('#btn_modal_facturar').prop('disabled', true);
            $('#div_nc').show();
        } else {
            $('#div_nc').hide();
        }
        mostrarTotales();
    });

    $("#nc_btn").on("click", function (e) {
        mostrarCXC();
    });

    $('#totalModal').on('shown.bs.modal', function () {
        $('#primer_des').focus();
    });

    $("#btn_modal_comentario").on("click", function (e) {
        mostrarModalComentario();
    });

    $("#btn_modal_comentario_aceptar").on("click", function (e) {
        e.preventDefault(); //No se activará la acción predeterminada del evento
        cerrarComentario()
    });

    $("#btn_accept_nc").on("click", function (e) {
        e.preventDefault(); //No se activará la acción predeterminada del evento
        $('#carga_nc').val('1');
        $('#btn_modal_facturar').prop('disabled', false);
        cerrarModalCXC()
    });

    //cuando se da click al boton submit entonces se ejecuta la funcion guardaryeditar(e);
    $("#total_modal_form").on("submit", function (e) {
        procesarFacturacionCr(e);
    });

    limpiar();
    correlYtasa();
    $('#btn_prod').hide();
}

function limpiar() {
    $("#clie").prop("disabled", false);
    /* $("#codvend").val("");
    $("#vend").val(""); */
    $("#ant_input").val(0);
    $("#cred_input").val(0);
    $("#tasa").text("0,00");
    $("#vend").val("").change();
    $("#vend").prop("disabled", true);
    $("#depo").val("").change();
    $("#depo").prop("disabled", true);
    $("#precio").val("1").change();
    $("#precio").prop("disabled", true);
    $("#convenio").val("0");
    $("#input_convenio").val("Sin Convenio");
    $("#input_convenio").prop("disabled", true);
    $("#div_convenio").hide();
    $("#div_precio").show();
    $("#btn_prod").prop("disabled", true);
    $("#btn_ttl").prop("disabled", true);
    $("#btn_limpiar_tabla").prop("disabled", false);
    $('#correl_c_text').text('');
    $('#correl_c').text('');
    $('#tipofac_c').text('');
    $('#tipo_precio').prop('disabled', false);
}

function limpiar_totales() {
    $('#emi_nc').prop('checked', true);
    $('#nc_btn').prop('disabled', false);
    $('#btn_modal_facturar').prop('disabled', true);
    $("#total_ope_bs").val(0);
    $("#total_ope_d").val(0);
    $("#primer_des").val('0');
    $('#primer_des').prop("readonly", true);
    if ($('#tipofac_c').text() == '') {
        $('#div_primerdes').hide(); //hide descuento
        $('#div_montodes').hide(); //hide descuento
    }
    $("#ttl_neto_bs").val(0);
    $("#ttl_neto_d").val(0);
    $("#ttl_imp_16_bs").val(0);
    $("#ttl_imp_16_d").val(0);
    $("#ttl_imp_per_bs").val(0);
    $("#ttl_imp_per_d").val(0);
    $("#ttl_imp_18_bs").val(0);
    $("#ttl_imp_18_d").val(0);
    $("#ttl_gral_bs").val(0);
    $("#ttl_gral_d").val(0);
    $("#diasven").val('0');
    $("#anticipo").val('');
    $("#fechaemi").prop("readonly", !flag_fechae);
    setFechaV('num');
    $("#coment1").val('');
    $("#coment2").val('');
    $("#coment3").val('');
    $("#coment4").val('');
    $("#coment5").val('');
    $('#comentario_delvol').val('');
}

function habilitar() {
    $("#vend").prop("disabled", false);
    $("#depo").prop("disabled", false);
    $("#precio").prop("disabled", false);
    $("#btn_prod").prop("disabled", false);
    $("#btn_ttl").prop("disabled", false);
}

function correlYtasa() {
    $.post("ventas3_devolfac_par_data.php?op=index", function (data, status) {
        if (!jQuery.isEmptyObject(data)) {
            $('#correl').text(data.correl);
            $('#tasa').text(data.factor + ' Bs');
            flag_fechae = data.flag_fechae;
        }
    }, 'json');
}

$(document).ready(function () {
    $("#clie").change(() => {
        if ($("#clie").val() !== "") {
            habilitar();
            let codclie = $("#clie").val();
            $.post("ventas3_devolfac_par_data.php?op=datos_clie", { codclie: codclie }, function (data, status) {
                if (!jQuery.isEmptyObject(data)) {
                    $('#tipo_precio').val(data.precio);
                    //$('#vend').val(data.codvend).change();
                    //$('#cred_input').val(data.cred);
                    $('#cred_input').val(0);
                    setFechaV('num');
                    $('#ant_input').val(data.pagosa);

                    if (data.convenio !== 0) {
                        $("#div_convenio").show();
                        $("#div_precio").hide();
                    } else {
                        $("#div_convenio").hide();
                        $("#div_precio").show();
                    }
                    $('#convenio').val(data.convenio);
                    $('#input_convenio').val("Convenio "+data.nomperConvenio);
                }
            }, 'json');
        } else {
            limpiar();
        }
    });

    $("#tipo_precio").change(() => {
        actualizarPreciosTabla();
    });

    $("#buscaPrd").on('keyup', () => {
        listarProductos($("#buscaPrd").val());
    }).keyup();

    $("#buscaDoc").on('keyup', () => {
        listarDocumentos($("#buscaDoc").val());
    }).keyup();

    $("#primer_des").on('keyup', () => {
        let value = Number($("#primer_des").val());
        if (value < 0) {
            $("#primer_des").val(0);
        } else if (value > 100) {
            $("#primer_des").val(100);
        }
        calculos_totales();
    }).keyup();

    $("#emi_nc").on("click", function (e) {
        if ($("#emi_nc").is(':checked')) {
            $('#nc_btn').prop('disabled', false);
            $('#btn_modal_facturar').prop('disabled', true);
        } else {
            $('#nc_btn').prop('disabled', true);
            $('#btn_modal_facturar').prop('disabled', false);
        }
        $('#carga_nc').val("0");
        $("#cxc_data tbody").empty();
        $('#nc_form')[0].reset();
    });
    //$("#clie").val("J298041765").change();
    //$("#depo").val('1000').change();
});


function cantPrdResult(search = "") {
    let cant = 0;
    let depo = $("#depo").val();
    $.ajax({
        async: false, cache: false,
        url: "ventas3_devolfac_par_data.php?op=buscar_cant_prd",
        type: "POST", dataType: "json",
        data: { search: search, depo: depo },
        success: function (data) { cant = data.c; }
    });
    return cant;
}

function mostrarProd(search = "", index = "-1") {
    $("#idxprd").val(index);
    $("#buscaPrd").val("");
    let renglones = parseInt($("#table_data tbody tr:last-child").index()) + 1;
    if ((renglones == cant_renglones && $('#subtotal' + (renglones - 1)).val().length == 1)
        || (renglones < cant_renglones)) {
        let clie = $("#clie").val();
    let vend = $("#vend").val();
    let depo = $("#depo").val();
    if (clie !== '' && vend !== '' && depo !== '') {
        $('#productosModal').modal('show');
        $("#span_depo").text(depo);
        $("#buscaPrd").val(search);
        listarProductos(search);
    }
    else {
        if (clie === '') {
            Swal.fire({
                title: "Atención!",
                html: "debe seleccionar un Cliente".substring(0, 400) + "...",
                icon: "error",
                allowOutsideClick: false
            });
            return (false);
        }
        else if (vend === '') {
            Swal.fire({
                title: "Atención!",
                html: "debe seleccionar un Vendedor".substring(0, 400) + "...",
                icon: "error",
                allowOutsideClick: false
            });
            return (false);
        }
        else if (depo === '') {
            Swal.fire({
                title: "Atención!",
                html: "debe seleccionar un Depósito".substring(0, 400) + "...",
                icon: "error",
                allowOutsideClick: false
            });
            return (false);
        }

    }
} else {
    Swal.fire({
        title: "Atención!",
        html: "Solo se puede ingresar " + renglones + " Items.",
        icon: "error",
        allowOutsideClick: false
    });
    return (false);
}
}

function mostrarDoc() {
    $("#buscaDoc").val("");
    $('#documentosModal').modal('show');
    listarDocumentos();
}

function mostrarTotales() {
    limpiar_totales();
    $('#totalModal').modal('show');
    $('#tipo_ope').prop('disabled', true);
    let anticipo = parseFloat($("#ant_input").val());
    $("#anticipo").attr({
        "placeholder": "0"/*"(máx. " + anticipo.format_money(2, 3, '.', ',') + "bs)"*/,
        //"max": anticipo,
        "min": 0
    });
    calculos_totales();
}

function mostrarModalComentario() {
    $('#comentarioModal').modal('show');
}

function cerrarTotales() { $('#totalModal').modal('hide'); }

function cerrarComentario() { $('#comentarioModal').modal('hide'); }

function mostrarCXC() {
    let carga_nc = $('#carga_nc').val();
    if (carga_nc == 0) {
        $('#btn_accept_nc').prop('disabled', true);
        $('#alm_anti').prop('checked', false)
        $('#monto_anticipo').prop('disabled', true);
        let codclie = $("#clie").val();
        $.ajax({
            async: false,
            url: "ventas3_devolfac_par_data.php?op=listar_cxc",
            method: "POST",
            dataType: "json",
            data: { codclie: codclie },
            error: function (e) {
                console.log(e.responseText);
            },
            success: function (data) {
                listarCXC(data);
            }
        });
    }
    $('#div_alm_anticipo').hide();
    $('#pagosdevolModal').modal('show');
}

function listarProductos(search = "") {
    let depo = $("#depo").val();
    if (tabla_productos instanceof $.fn.dataTable.Api) {
        $('#productos_data').DataTable().clear().destroy();
    }
    tabla_productos = $('#productos_data').dataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        "sDom": "tipr",
        "ajax":
        {
            url: 'ventas3_devolfac_par_data.php?op=listar_prd',
            type: "POST",
            dataType: "json",
            data: {
                search: search,
                depo: depo
            },
            error: function (e) {
                console.log(e.responseText);
            },
            complete: function () {
                // elimina el error de compatibilidad quitando el with
                $("#productos_data").css({ 'width': '' });
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,//Por cada 10 registros hace una paginación
        "order": [[0, "asc"]],//Ordenar (columna,orden)
        "language": texto_español_datatables
    }).DataTable();
}

function listarDocumentos(search = "") {
    let codclie = $("#clie").val();
    if (tabla_documentos instanceof $.fn.dataTable.Api) {
        $('#documentos_data').DataTable().clear().destroy();
    }
    tabla_documentos = $('#documentos_data').dataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        "sDom": "tipr",
        "ajax":
        {
            url: 'ventas3_devolfac_par_data.php?op=listar_docs',
            type: "POST",
            dataType: "json",
            data: { search: search, codclie: codclie },
            error: function (e) {
                console.log(e.responseText);
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

function cantidadPrd(it) {
    let index = parseInt($(it).parents("tr").index());
    let unid_selected = parseInt($("#unid" + index).val());
    let cant_selected = parseInt($("#cant" + index).val());
    let uniemp = parseInt($("#uniemp" + index).val());
    let um = parseInt($("#um" + index).val());

    switch (true) {
        //si unid_selected = 0 ==> CAJ o BUL
        //si unid_selected = 1 ==> UND o BOT
    case (cant_selected < 1):
        $("#cant" + index).val(1);
        Swal.fire({
            title: 'Atención!',
            html: 'la cantidad es inferior a 1',
            icon: 'error',
            allowOutsideClick: false
        });
        break;
    case (cant_selected > um):
        $("#cant" + index).val(um);
        let tipo_unid_text1 = $("#descrip" + index).val();
        Swal.fire({
            title: 'Atención!',
            html: 'la cantidad de ' + tipo_unid_text1 + ' no puede ser mayor a la del documento (' + um + ")",
            icon: 'error',
            allowOutsideClick: false
        });
        break;
    case (unid_selected == 1 && cant_selected > uniemp):
        $("#cant" + index).val(uniemp);
        let tipo_unid_text2 = $("#unid" + index + " option[value='1']").text();
        Swal.fire({
            title: 'Atención!',
            html: 'la cantidad de ' + tipo_unid_text2 + ' no puede ser mayor a la unidad de empaque (' + uniemp + " " + tipo_unid_text2 + ")",
            icon: 'error',
            allowOutsideClick: false
        });
        break;
    case ((unid_selected == 0 && cant_selected >= 1) || (unid_selected == 1 && cant_selected >= 1 && cant_selected <= uniemp)):
        let codprod = $("#item" + index).val();
        seleccionarPrd(codprod, index);
        break;
    }
}

function seleccionarPrd(codprod, idx = "") {
    let renglones = parseInt($("#table_data tbody tr:last-child").index()) + 1;
    if (renglones <= cant_renglones) {
        let index = (idx === "") ? parseInt($("#idxprd").val()) : idx;
        let cant = '';
        let unid = '';
        // si index == -1 es que se esta seleccionando un producto del modal.
        // si index >= 0  es que se esta buscando producto desde una linea de la tabla
        if (index === -1) {
            cant = 1;
            unid = "0";
            index = parseInt($("#table_data tbody tr:last-child").index());
            if (renglones != cant_renglones) {
                $(".add-new").trigger('click');
            }
        } else if (index >= 0) {
            cant = $("#cant" + index).val();
            unid = $("#unid" + index).val();
        }
        let depo = $("#depo").val();
        let convenio = $("#convenio").val();
        let correl_c = $('#correl_c').text();
        let tipofac_c = $('#tipofac_c').text();
        let precio = (tipofac_c !== '') ? $("#tipopvp" + index).val() : $("#tipo_precio").val();
        let tasa = parseFloat($('#tasa').text().replace('.', '').replace(',', '.'));
        $.post("ventas3_devolfac_par_data.php?op=datos_prod",
        {
            codprod: codprod,
            cant: cant,
            unid: unid,
            precio: precio,
            depo: depo,
            tasa: tasa,
            convenio: convenio,
            correl_c: correl_c,
            tipofac_c: tipofac_c,
        },
        function (data, status) {
            if (!jQuery.isEmptyObject(data)) {

                $("#uniemp" + index).val(data.cantempaq);
                $("#iva16_" + index).val(data.iva);
                $("#ivaper_" + index).val(data.pvp);
                $("#ial_" + index).val(data.ial);
                $("#item" + index).val(data.codprod);
                $("#descrip" + index).val(data.descrip.replace('\u0092', "'"));
                $("#precio" + index).val(data.precio);
                $("#preciod" + index).val(data.preciod);
                $("#subtotal" + index).val(data.subtotal);
                $("#subtotald" + index).val(data.subtotald);
                $("#total" + index).val(data.total);
                $("#totald" + index).val(data.totald);

                $("#precio_input" + index).val((Math.round(data.precio * 100) / 100).format_money(2, 3, '.', ','));
                $("#preciod_input" + index).val((Math.round(data.preciod * 100) / 100).format_money(2, 3, '.', ','));
                $("#subtotal_input" + index).val((Math.round(data.subtotal * 100) / 100).format_money(2, 3, '.', ','));
                $("#subtotald_input" + index).val((Math.round(data.subtotald * 100) / 100).format_money(2, 3, '.', ','));
                $("#total_input" + index).val((Math.round(data.total * 100) / 100).format_money(2, 3, '.', ','));
                $("#totald_input" + index).val((Math.round(data.totald * 100) / 100).format_money(2, 3, '.', ','));

                $("#unid" + index + " option[value='1']").text(data.und);
                $('#productosModal').modal('hide');
                $("#tipopvp" + index).val(data.tipopvp);
                $("#precio_input" + index).removeClass('bg-primary').removeClass('bg-navy').removeClass('bg-info').removeClass('bg-purple');
                switch (parseInt(data.tipopvp)) {
                case 1: $("#precio_input" + index).addClass('bg-navy'); break;
                case 2: $("#precio_input" + index).addClass('bg-primary'); break;
                case 3: $("#precio_input" + index).addClass('bg-info'); break;
                default: $("#precio_input" + index).addClass('bg-purple'); break;
                }
                $("#item" + index).trigger("keyup");
                evaluarTabla();

                let uniemp = parseInt(data.cantempaq);
                let cant_selected = parseInt($("#cant" + index).val());
                let unid_selected = $("#unid" + index).val();
                switch (true) {
                        //si unid_selected = 0 ==> CAJ o BUL
                        //si unid_selected = 1 ==> UND o BOT
                case (cant_selected < 1):
                    $("#cant" + index).val(1);
                    Swal.fire({
                        title: 'Atención!',
                        html: 'la cantidad es inferior a 1',
                        icon: 'error',
                        allowOutsideClick: false
                    });
                    break;
                case (unid_selected == 1 && cant_selected > uniemp):
                    $("#cant" + index).val(uniemp);
                    let tipo_unid_text2 = $("#unid" + index + " option[value='1']").text();
                    Swal.fire({
                        title: 'Atención!',
                        html: 'la cantidad de ' + tipo_unid_text2 + ' no puede ser mayor a la unidad de empaque (' + uniemp + " " + tipo_unid_text2 + ")",
                        icon: 'error',
                        allowOutsideClick: false
                    });
                    break;
                }
            }
        }, 'json'
        );
    } else {
        Swal.fire({
            title: "Atención!",
            html: "Solo se puede ingresar " + renglones + " Items.",
            icon: "error",
            allowOutsideClick: false
        });
        return (false);
    }
}

function seleccionarDoc(numerod, tipofac) {
    $.ajax({
        url: "ventas3_devolfac_par_data.php?op=datos_doc",
        method: "POST",
        dataType: "json",
        data: {
            numerod: numerod,
            tipofac: tipofac
        },
        beforeSend: function () {
            swal.fire({
                html: '<h5>Procesando, espere...</h5>',
                showConfirmButton: false,
                allowOutsideClick: false,
                onRender: function () {
                    // there will only ever be one sweet alert open.
                    $('.swal2-content').prepend(sweet_loader);
                }
            });
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

                if (jQuery.isEmptyObject(data.icono)) {
                    $('#correl_c').text(numerod);
                    $('#tipofac_c').text(tipofac);
                    switch (tipofac) {
                    case "A": $('#correl_c_text').text('Factura Cargado: '); break;
                    case "B": $('#correl_c_text').text('Devolución Factura Cargado: '); break;
                    case "C": $('#correl_c_text').text('Nota de Entrega Cargado: '); break;
                    case "D": $('#correl_c_text').text('Devolución N/E Cargado: '); break;
                    case "E": $('#correl_c_text').text('Pedido Cargado: '); break;
                    case "F": $('#correl_c_text').text('Presupuesto Cargado: '); break;
                    case "G": $('#correl_c_text').text('Fact. en Espera Cargado: '); break;
                    }

                    $("#table_data tbody").empty();
                    //header
                    $("#clie").val(data.head.codclie).change();
                    $("#vend").val(data.head.codvend).change();
                    $("#depo").val(data.head.codubic).change();
                    $("#clie").prop("disabled", true);
                    $("#vend").prop("disabled", true);
                    $("#depo").prop("disabled", true);
                    $("#precio").val(data.head.precio);
                    $('#desc_input').val(data.head.descuento);
                    $('#mdesc_input').val(data.head.mdescuento);
                    $('#tipo_precio').prop('disabled', true);
                    $("#subttl").text(parseFloat(data.head.subtotal).format_money(2, 3, '.', ','));
                    $("#imp_16").text(parseFloat(Math.round(data.head.iva * 100) / 100).format_money(2, 3, '.', ','));
                    $("#imp_per").text(parseFloat(Math.round(data.head.ial * 100) / 100).format_money(2, 3, '.', ','));
                    $("#imp_18").text(parseFloat(Math.round(data.head.pvp * 100) / 100).format_money(2, 3, '.', ','));
                    let total = parseFloat(data.head.subtotal) + parseFloat(data.head.iva) + parseFloat(data.head.pvp) + parseFloat(data.head.ial);
                    let totald = total / parseFloat(Math.round(data.head.tasa * 100) / 100);
                    if ($('#tipofac_c').text() !== '') {
                        //$("#ttlbs").text(parseFloat(Math.trunc(total * 100) / 100).format_money(2, 3, '.', ','));
                        $("#ttlbs").text(parseFloat(Math.round(total * 100) / 100).format_money(2, 3, '.', ','));
                    } else {
                        $("#ttlbs").text(parseFloat(Math.round(total * 100) / 100).format_money(2, 3, '.', ','));
                    }
                    $("#ttld").text(parseFloat(Math.round(totald * 100) / 100).format_money(2, 3, '.', ','));
                    $("#tasa").text(parseFloat(data.head.tasa).format_money(2, 3, '.', ','));

                    // variables oculta
                    $('#subttl_input').val(data.head.subtotal);
                    $('#imp_16_input').val(data.head.iva);
                    $('#imp_per_input').val(data.head.ial);
                    $('#imp_18_input').val(data.head.pvp);
                    $('#ttlbs_input').val(data.head.total);

                    //body
                    $.each(data.body, function (idx, opt) {
                        $(".add-new").trigger('click');
                        let index = parseInt($("#table_data tbody tr:last-child").index());
                        $("#iva16_" + index).val(opt.iva);
                        $("#ivaper_" + index).val(opt.pvp);
                        $("#ial_" + index).val(opt.ial);
                        $("#item" + index).val(opt.codprod);
                        $("#descrip" + index).val(opt.descrip.replace('\u0092', "'"));
                        $("#cant" + index).val(opt.cantidad);
                        $("#um" + index).val(opt.cantidad);
                        $("#cant" + index).attr({
                            "max" : opt.cantidad,
                            "min" : 1
                        });
                        $("#uniemp" + index).val(opt.cantempaq);
                        $("#unid" + index + " option[value='1']").text(opt.und);
                        $("#unid" + index).val(opt.unidad);
                        $("#precio" + index).val(opt.precio);
                        $("#preciod" + index).val(opt.preciod);
                        $("#subtotal" + index).val(opt.subtotal);
                        $("#subtotald" + index).val(opt.subtotald);
                        $("#total" + index).val(opt.total);
                        $("#totald" + index).val(opt.totald);

                        $("#precio_input" + index).val((Math.round(opt.precio * 100) / 100).format_money(2, 3, '.', ','));
                        $("#preciod_input" + index).val((Math.round(opt.preciod * 100) / 100).format_money(2, 3, '.', ','));
                        $("#subtotal_input" + index).val((Math.round(opt.subtotal * 100) / 100).format_money(2, 3, '.', ','));
                        $("#subtotald_input" + index).val((Math.round(opt.subtotald * 100) / 100).format_money(2, 3, '.', ','));
                        $("#total_input" + index).val((Math.round(opt.total * 100) / 100).format_money(2, 3, '.', ','));
                        $("#totald_input" + index).val((Math.round(opt.totald * 100) / 100).format_money(2, 3, '.', ','));

                        $("#tipopvp" + index).val(opt.tipopvp);
                        $("#precio_input" + index).removeClass('bg-primary').removeClass('bg-navy').removeClass('bg-info').removeClass('bg-purple');
                        switch (opt.tipopvp) {
                        case 1: $("#precio_input" + index).addClass('bg-navy'); break;
                        case 2: $("#precio_input" + index).addClass('bg-primary'); break;
                        case 3: $("#precio_input" + index).addClass('bg-info'); break;
                        default: $("#precio_input" + index).addClass('bg-purple'); break;
                        }
                        //$('#cant' + index).trigger("change");
                        //$("#item" + index).trigger("keyup");
                        $("#item" + index).prop("readonly", true);
                    });
                    //$(".add-new").trigger('click');
                    $("#btn_prod").prop("disabled", true);
                    $("#btn_limpiar_tabla").prop("disabled", true);
                    cantrenglones();

                    $('#documentosModal').modal('hide');

                    swal.close();
                } 
                else {
                    Swal.fire({
                        title: data.title,
                        html: data.mensaje,
                        icon: data.icono,
                        allowOutsideClick: false
                    });
                }
            }
        }
    });
}

function calculos_facturar() {
    let idx = 0;
    let iva16 = 0;
    let ivaper = 0;
    let ial = 0;
    let subtotal = 0;
    let total = 0;

    //console.log($("#form_table").serializeArray());
    let tasa = parseFloat($('#tasa').text().replace('.', '').replace(',', '.'));

    $("#form_table").serializeArray().forEach(value => {
        if (value.name === 'idx[]' && value.value !== '') {
            idx = value.value;
        }
        if (value.name === 'iva16[]' && value.value !== '0') {
            iva16 += (parseFloat(value.value) * $('#cant' + idx).val());
        }
        if (value.name === 'ivaper[]' && value.value !== '0') {
            ivaper += (parseFloat(value.value) * $('#cant' + idx).val());
        }
        if (value.name === 'ial[]' && value.value !== '0') {
            ial += (parseFloat(value.value) * $('#cant' + idx).val());
        }
        if (value.name === 'subtotal[]' && value.value !== '0') {
            subtotal += parseFloat(value.value/* .replace('.', '').replace(',', '.') */);
        }
    });

    total = (subtotal + iva16 + ivaper + ial);

    // variables oculta
    $('#subttl_input').val(subtotal);
    $('#imp_16_input').val(iva16);
    $('#imp_per_input').val(ial);
    $('#imp_18_input').val(ivaper);
    $('#ttlbs_input').val(total);

    //variables visibles
    $('#subttl').text((Math.round(subtotal * 100) / 100).format_money(2, 3, '.', ','));
    $('#imp_16').text((Math.round(iva16 * 100) / 100).format_money(2, 3, '.', ','));
    $('#imp_per').text((Math.round(ial * 100) / 100).format_money(2, 3, '.', ','));
    $('#imp_18').text((Math.round(ivaper * 100) / 100).format_money(2, 3, '.', ','));
    $('#ttlbs').text((Math.round(total * 100) / 100).format_money(2, 3, '.', ','));
    $('#ttld').text(((tasa !== 0) ? Math.round((total / tasa) * 100) / 100 : (0)).format_money(2, 3, '.', ','));
}

function calculos_totales() {
    if ($('#tipofac_c').text() !== '') {
        $('#primer_des').val(truncate2decimal($('#desc_input').val()));
    }

    let iva_16 = 0;
    let tasa = parseFloat($('#tasa').text().replace('.', '').replace(',', '.'));
    /* 
    let subttl = parseFloat($('#subttl').text().replace('.', '').replace(',', '.'));
    let imp_16 = parseFloat($('#imp_16').text().replace('.', '').replace(',', '.'));
    let imp_per = parseFloat($('#imp_per').text().replace('.', '').replace(',', '.'));
    let imp_18 = parseFloat($('#imp_18').text().replace('.', '').replace(',', '.')); 
    */
    let subttl = parseFloat($('#subttl_input').val());
    let mdecuento = parseFloat($('#mdesc_input').val());
    let imp_16 = parseFloat($('#imp_16_input').val());
    let imp_per = parseFloat($('#imp_per_input').val());
    let imp_18 = parseFloat($('#imp_18_input').val());
    let primer_des = ($('#tipofac_c').text() !== '') ? $('#desc_input').val() : $('#primer_des').val();
    primer_des = (primer_des === '') ? 0 : (parseFloat(primer_des) / 100);
    descuento = subttl * primer_des;
    subttl_con_des = subttl - descuento;
    iva_16 = imp_16 - (imp_16 * primer_des);
    let total = (primer_des > 0) ? (subttl_con_des + iva_16 + imp_per + imp_18) : $('#ttlbs_input').val();

    // Bs
    $("#monto_des").val((Math.round(descuento * 100) / 100).format_money(2, 3, '.', ','));
    $("#total_ope_bs").val((Math.round(subttl * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_neto_bs").val((Math.round(subttl_con_des * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_imp_16_bs").val((Math.round(iva_16 * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_imp_per_bs").val((Math.round(imp_per * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_imp_18_bs").val((Math.round(imp_18 * 100) / 100).format_money(2, 3, '.', ','));
    if ($('#tipofac_c').text() !== '') {
        //$('#ttl_gral_bs').val((Math.trunc(total * 100) / 100).format_money(2, 3, '.', ','));
        $('#ttl_gral_bs').val((Math.round(total * 100) / 100).format_money(2, 3, '.', ','));
    } else {
        $('#ttl_gral_bs').val((Math.round(total * 100) / 100).format_money(2, 3, '.', ','));
        7    }


    // $$
        $("#monto_des_d").val((Math.round((descuento / tasa) * 100) / 100).format_money(2, 3, '.', ','));
        $("#total_ope_d").val((Math.round((subttl / tasa) * 100) / 100).format_money(2, 3, '.', ','));
        $("#ttl_neto_d").val((Math.round((subttl_con_des / tasa) * 100) / 100).format_money(2, 3, '.', ','));
        $("#ttl_imp_16_d").val((Math.round((iva_16 / tasa) * 100) / 100).format_money(2, 3, '.', ','));
        $("#ttl_imp_per_d").val((Math.round((imp_per / tasa) * 100) / 100).format_money(2, 3, '.', ','));
        $("#ttl_imp_18_d").val((Math.round((imp_18 / tasa) * 100) / 100).format_money(2, 3, '.', ','));
        $('#ttl_gral_d').val((Math.round((total / tasa) * 100) / 100).format_money(2, 3, '.', ','));

        $('#diasven').val($('#cred_input').val());
        setFechaV('num');
    }

    function setValueOnPressEnter(it, event) {
        if (event.keyCode == 13) {
            switch ($(it).prop("id")) {
            case 'primer_des':
                if ($(it).val() === '') {
                    $(it).val('0.00');
                }
                else if (($('#correl_c').text().length > 0 && $('#tipofac_c').text() === 'A' && $('#carga_nc').val() !== '0') || ($('#correl_c').text().length == 0)) {
                    if (!generacion_documento_en_proceso) {
                        //$('#total_modal_form').submit();
                        generacion_documento_en_proceso = true;
                    }
                }
                else if ($('#carga_nc').val() === '0' && $('#correl_c').text().length !== 0) {
                    Swal.fire({
                        title: "Atención!",
                        html: "Aplicar nota de crédito".substring(0, 400) + "...",
                        icon: "error",
                        allowOutsideClick: false
                    });
                    return (false);
                }
                break;
            }
        event.preventDefault(); //No se activará la acción predeterminada del evento
    }

    return true;
}

function setFechaV(tipo = '') {
    let fecha = new Date($('#fechaemi').val());
    switch (tipo) {
    case 'num':
        let input = $('#diasven').val();
            let dias = parseInt((input !== '') ? input : 0); // Número de días a agregar
            fecha.setDate(fecha.getDate() + dias + 1);
            $('#fechaven').val(fecha.getFullYear() + '-' + (fecha.getMonth() + 1).toString().padStart(2, '0') + '-' + fecha.getDate().toString().padStart(2, '0'));
            $("#fechaven").attr({ "min": fecha.getFullYear() + '-' + (fecha.getMonth() + 1).toString().padStart(2, '0') + '-' + fecha.getDate().toString().padStart(2, '0') });
            break;
        case 'date':
            let fechainput = new Date($('#fechaven').val());
            let diasdif = fechainput.getTime() - fecha.getTime();
            let contdias = Math.ceil(diasdif / (1000 * 60 * 60 * 24));
            $('#diasven').val(contdias);
            break;
        }
    }

    function procesarFacturacionCr(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento

    $('#tipo_ope').prop('disabled', false);
    // si el tipofac esta cargado, se procede con estas lineas
    if ($('#tipofac_c').text() !== '') {
        $("#clie").prop("disabled", false);
        $("#vend").prop("disabled", false);
        $("#depo").prop("disabled", false);
        let tamano = parseInt($("#table_data tbody tr:last-child").index());
        for (let i = 0; i <= tamano; i++) { $('#unid' + i).prop('disabled', false); }
    }
const formData = new FormData($("#clie_form")[0]);
let formTabla = new FormData($("#form_table")[0]);
let formTotales = new FormData($("#total_modal_form")[0]);
let formNc = new FormData($("#nc_form")[0]);
let formComentario = new FormData($("#comentario_form")[0]);
for (let pair of formTabla.entries()) {
    formData.append(pair[0], pair[1]);
}
for (let pair of formTotales.entries()) {
    formData.append(pair[0], pair[1]);
}
for (let pair of formNc.entries()) {
    formData.append(pair[0], pair[1]);
}
for (let pair of formComentario.entries()) {
    formData.append(pair[0], pair[1]);
}
formData.append('tasa', parseFloat($('#tasa').text().replace('.', '').replace(',', '.')));
formData.append('numerod_c', $('#correl_c').text());
formData.append('tipofac_c', $('#tipofac_c').text());

$.ajax({
    url: "ventas3_devolfac_par_data.php?op=notacredito",
    type: "POST",
    data: formData,
    dataType: "json",
    contentType: false,
    processData: false,
    beforeSend: function () {
        $('#btn_modal_facturar').prop('disabled', true);
        swal.fire({
            html: '<h5>Procesando, espere...</h5>',
            showConfirmButton: false,
            allowOutsideClick: false,
            onRender: function () {
                // there will only ever be one sweet alert open.
                $('.swal2-content').prepend(sweet_loader);
            }
        });
    },
    error: function (e) {
        generacion_documento_en_proceso = false;
        $('#btn_modal_facturar').prop('disabled', false);
        $('#tipo_ope').prop('disabled', true);

            // si el tipofac esta cargado, se procede con estas lineas
        if ($('#tipofac_c').text() !== '') {
            $("#clie").prop("disabled", true);
            $("#vend").prop("disabled", true);
            $("#depo").prop("disabled", true);
            let tamano = parseInt($("#table_data tbody tr:last-child").index());
            for (let i = 0; i <= tamano; i++) { $('#unid' + i).prop('disabled', true); }
        }
    console.log(e.responseText);
    Swal.fire({
        title: "Error!",
        html: e.responseText,
        icon: 'danger',
        allowOutsideClick: false
    });
},
success: function (data) {
    let { title, icono, mensaje } = data;
    generacion_documento_en_proceso = false;

    $('#btn_modal_facturar').prop('disabled', false);
    $('#tipo_ope').prop('disabled', true);

            // si el tipofac esta cargado, se procede con estas lineas
    if ($('#tipofac_c').text() !== '') {
        $("#clie").prop("disabled", true);
        $("#vend").prop("disabled", true);
        $("#depo").prop("disabled", true);
        let tamano = parseInt($("#table_data tbody tr:last-child").index());
        for (let i = 0; i <= tamano; i++) { $('#unid' + i).prop('disabled', true); }
    }

if (!title.includes('ERROR')) {
    Swal.fire({
        title: title,
        html: mensaje,
        icon: icono,
        allowOutsideClick: false
    });

}
else {
    Swal.fire({
        title: title,
        html: mensaje.substring(0, 400) + "...",
        icon: icono,
        allowOutsideClick: false
    });
}

            //verifica si el mensaje de insercion contiene error
if (icono.includes('error')) {
    $('#totalModal').modal('hide');
    return (false);
} else {
    $('#totalModal').modal('hide');
    $("#btn_limpiar").trigger('click');
    setTimeout(() => {  window.open('ventas3_devolfac_pdf.php?&i=' + data.id, '_blank'); }, 2000);
}
}
});


}


init();