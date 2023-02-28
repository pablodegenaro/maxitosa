var tabla_productos;
var tabla_documentos;
var cant_renglones = 9;
var flag_fechae = false;

var generacion_documento_en_proceso = false;
var carga_de_documento_en_proceso = false;

var arr_message_items_to_eliminate = new Array();

var arr_notas = new Array();

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
    });

    $("#btn_limpiar_tabla").on("click", function (e) {
        $('#correl_c_text').text('');
        $('#correl_c').text('');
        $('#tipofac_c').text('');
        init_tabla();
        evaluarTabla();
    });

    $("#btn_ttl").on("click", function (e) {
        mostrarTotales();
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

    //cuando se da click al boton submit entonces se ejecuta la funcion guardaryeditar(e);
    $("#total_modal_form").on("submit", function (e) {
        procesarPedido(e);
    });

    limpiar();
    correlYtasa();
}

function limpiar() {
    $("#clie").prop("disabled", false);
    /* $("#codvend").val("");
    $("#vend").val(""); */
    $("#ant_input").val(0);
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
    $('#correl_c_text').text('');
    $('#correl_c').text('');
    $('#tipofac_c').text('');
}

function limpiar_totales() {
    $("#total_ope_bs").val(0);
    $("#total_ope_d").val(0);
    $("#primer_des").val('0');
    $('#div_primerdes').hide(); //hide descuento
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
    $("#anticipo").val('');
    $("#fechaemi").prop("readonly", !flag_fechae);
    $("#coment1").val('');
    $("#coment2").val('');
    $("#coment3").val('');
    $("#coment4").val('');
    $("#coment5").val('');
}

function habilitar() {
    $("#vend").prop("disabled", false);
    $("#depo").prop("disabled", false);
    $("#precio").prop("disabled", false);
    $("#btn_prod").prop("disabled", false);
    $("#btn_ttl").prop("disabled", false);
}

function correlYtasa() {
    $.post("ventas3_ped_data.php?op=index", function (data, status) {
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
            $.post("ventas3_ped_data.php?op=datos_clie", { codclie: codclie }, function (data, status) {
                if (!jQuery.isEmptyObject(data)) {
                    $('#tipo_precio').val(data.precio);
                    //$('#vend').val(data.codvend).change();
                    //$('#tipo_precio').val(data.precio);
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
                    /* switch (data.convenio) {
                        case 0: $('#input_convenio').val("Sin Convenio"); break;
                        case 1: $('#input_convenio').val("Convenio DIAGEO"); break;
                        case 2: $('#input_convenio').val("Convenio EURO"); break;
                        case 3: $('#input_convenio').val("Convenio CALL CENTER"); break;
                        case 4: $('#input_convenio').val("Convenio EMPLEADOS"); break;
                        case 5: $('#input_convenio').val("Convenio MAYORISTA"); break;
                    } */
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

    //$("#clie").val("E822923758").change();
    //$("#depo").val('1000').change();
});

function cantPrdResult(search = "") {
    let cant = 0;
    let depo = $("#depo").val();
    $.ajax({
        async: false, cache: false,
        url: "ventas3_ped_data.php?op=buscar_cant_prd",
        type: "POST", dataType: "json",
        data: { search: search, depo: depo },
        success: function (data) { cant = data.c; }
    });
    return cant;
}

function actualizarExistenciasEnCasoError() {
    const formData = new FormData($("#clie_form")[0]);
    let formTabla = new FormData($("#form_table")[0]);
    for (let pair of formTabla.entries()) {
        formData.append(pair[0], pair[1]);
    }
    $.ajax({
        url: "ventas3_ped_data.php?op=existencias",
        type: "POST",
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        error: function (e) {
            console.log(e.responseText);
        },
        success: function (data) {
            if (!jQuery.isEmptyObject(data)) {
                $.each(data, function (idx, opt) {
                    let index = opt.idx;
                    $("#umb" + index).val(opt.umb);
                    $("#ump" + index).val(opt.ump);
                    $('#cant' + index).trigger("change");
                });
            }
        }
    });
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
    let clie = $("#clie").val();
    let vend = $("#vend").val();
    let depo = $("#depo").val();
    if (clie !== '' && vend !== '' && depo !== '') {
        limpiar_totales();
        $('#totalModal').modal('show');
        $('#tipo_ope').prop('disabled', true);
        let anticipo = Number($("#ant_input").val());
        $("#anticipo").attr({
            "placeholder": "0"/*"(máx. " + anticipo.format_money(2, 3, '.', ',') + "bs)"*/,
            //"max": anticipo,
            "min": 0
        });
        $('#coment1').val(arr_notas[0]);
        $('#coment2').val(arr_notas[1]);
        $('#coment3').val(arr_notas[2]);
        $('#coment4').val(arr_notas[3]);
        $('#coment5').val(arr_notas[4]);

        calculos_totales();
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
}

function mostrarModalComentario() {
    $('#comentarioModal').modal('show');
}

function cerrarTotales() { $('#totalModal').modal('hide'); }

function cerrarComentario() { $('#comentarioModal').modal('hide'); }

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
            url: 'ventas3_ped_data.php?op=listar_prd',
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
            url: 'ventas3_ped_data.php?op=listar_docs',
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
    let ui = parseInt($("#ui" + index).val());
    let umb = parseInt($("#umb" + index).val());
    let ump = parseInt($("#ump" + index).val());
    let ucb = parseInt($("#ucb" + index).val());
    let ucp = parseInt($("#ucp" + index).val());

    switch (true) {
        //si unid_selected = 0 ==> CAJ o BUL
        //si unid_selected = 1 ==> UND o BOT
    case ((unid_selected == 0 && (umb - ucb) < 1) || (unid_selected == 1 && ((((umb - ucb) > 0) ? (((umb - ucb) * uniemp) + (ump - ucp)) : (ump - ucp)) < 1))):
        $("#unid" + index).val((unid_selected == 1) ? 0 : 1).trigger('click');
        $("#cant" + index).val(1);
        $('#item' + index).trigger(jQuery.Event('keypress', { keyCode: 13 }));
        let tipo_unid_text = (unid_selected == 1) ? $("#unid" + index + " option[value='1']").text() : $("#unid" + index + " option[value='0']").text();
        if (!carga_de_documento_en_proceso) {
            Swal.fire({
                title: 'Atención!',
                html: 'la cantidad de ' + tipo_unid_text + ' inferior a 1',
                icon: 'error',
                allowOutsideClick: false
            });
        }
        break;
    case (cant_selected < ui):
        $("#cant" + index).val(ui);
        if (!carga_de_documento_en_proceso) {
            Swal.fire({
                title: 'Atención!',
                html: 'la cantidad es inferior a 1',
                icon: 'error',
                allowOutsideClick: false
            });
        }
        break;
    case (unid_selected == 1 && cant_selected > uniemp):
        $("#cant" + index).val(uniemp);
        let tipo_unid_text2 = $("#unid" + index + " option[value='1']").text();
        if (!carga_de_documento_en_proceso) {
            Swal.fire({
                title: 'Atención!',
                html: 'la cantidad de ' + tipo_unid_text2 + ' no puede ser mayor a la unidad de empaque (' + uniemp + " " + tipo_unid_text2 + ")",
                icon: 'error',
                allowOutsideClick: false
            });
        }
        break;
    case ((unid_selected == 0 && cant_selected > (umb - ucb)) || (unid_selected == 1 && (cant_selected > (((umb - ucb) > 0) ? (((umb - ucb) * uniemp) + (ump - ucp)) : (ump - ucp))))):
        $("#cant" + index).val((unid_selected == 0) ? (umb - ucb) : (((umb - ucb) > 0) ? (((umb - ucb) * uniemp) + (ump - ucp)) : (ump - ucp)));
        let descrip = $("#descrip" + index).val();
        let tipo_unid_text1 = (unid_selected == 1) ? $("#unid" + index + " option[value='1']").text() : $("#unid" + index + " option[value='0']").text();
        if (!carga_de_documento_en_proceso) {
            Swal.fire({
                title: 'Atención!',
                html: 'la cantidad ingresada de ' + descrip + ' es mayor a la disponible (' + ((unid_selected == 0) ? (umb - ucb) : (((umb - ucb) > 0) ? (((umb - ucb) * uniemp) + (ump - ucp)) : (ump - ucp))) + ' ' + tipo_unid_text1 + ')',
                icon: 'error',
                allowOutsideClick: false
            });
        }
        break;
    case (cant_selected >= ui && ((unid_selected == 0 && cant_selected <= (umb - ucb)) || (unid_selected == 1 && (cant_selected <= (((umb - ucb) > 0) ? (((umb - ucb) * uniemp) + (ump - ucp)) : (ump - ucp)))))):
        let codprod = $("#item" + index).val();
        seleccionarPrd(codprod, index);
            // si hubo error de existencia procede a eliminar 
        if (!carga_de_documento_en_proceso) {
            evaluar_error_existencia();
        }
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
        let tipofac_c = $('#tipofac_c').text();
        let precio = (tipofac_c !== '' && carga_de_documento_en_proceso) ? $("#tipopvp" + index).val() : $("#tipo_precio").val();
        let tasa = parseFloat($('#tasa').text().replace('.', '').replace(',', '.'));

        $.ajax({
            async: false,
            cache: false,
            url: "ventas3_ped_data.php?op=datos_prod",
            method: "POST",
            dataType: "json",
            data: {
                codprod: codprod,
                cant: cant,
                unid: unid,
                precio: precio,
                depo: depo,
                tasa: tasa,
                convenio: convenio,
            },
            error: function (e) {
                console.log(e.responseText);
            },
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {

                    if (data.umb >= 1 || data.ump >= 1) {

                        $("#uniemp" + index).val(data.cantempaq);
                        $("#umb" + index).val(data.umb);
                        $("#ump" + index).val(data.ump);
                        $("#ucb" + index).val(data.ucb);
                        $("#ucp" + index).val(data.ucp);
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

                        let tipo_unid_text = '';
                        let uniemp = parseInt(data.cantempaq);
                        let cant_selected = parseInt($("#cant" + index).val());
                        let unid_selected = $("#unid" + index).val();

                        if ((data.umb - data.ucb) >= 1 || (data.ump - data.ucp) >= 1 || ((data.umb - data.ucb) > 0 && (data.ump - data.ucp) > 0)) {
                            switch (true) {
                                //si unid_selected = 0 ==> CAJ o BUL
                                //si unid_selected = 1 ==> UND o BOT
                            case (unid_selected == 0 && (data.umb - data.ucb) < 1) || (unid_selected == 1 && (((data.umb - data.ucb) > 0) ? (((data.umb - data.ucb) * uniemp) + (data.ump - data.ucp)) : (data.ump - data.ucp)) < 1):
                                $("#unid" + index).val((unid_selected == 1) ? 0 : 1).trigger('click');
                                $("#cant" + index).val(1);
                                $('#item' + index).trigger(jQuery.Event('keypress', { keyCode: 13 }));
                                tipo_unid_text = (unid_selected == 1) ? $("#unid" + index + " option[value='1']").text() : $("#unid" + index + " option[value='0']").text();
                                Swal.fire({
                                    title: 'Atención!',
                                    html: 'la cantidad de ' + tipo_unid_text + ' inferior a 1',
                                    icon: 'error',
                                    allowOutsideClick: false
                                });
                                break;
                            case (cant_selected < 1):
                                $("#cant" + index).val(1);
                                Swal.fire({
                                    title: 'Atención!',
                                    html: 'la cantidad es inferior a 1',
                                    icon: 'error',
                                    allowOutsideClick: false
                                });
                                break;
                            case (unid_selected == 1 && cant_selected > data.cantempaq):
                                $("#cant" + index).val(data.cantempaq);
                                let tipo_unid_text2 = $("#unid" + index + " option[value='1']").text();
                                Swal.fire({
                                    title: 'Atención!',
                                    html: 'la cantidad de ' + tipo_unid_text2 + ' no puede ser mayor a la unidad de empaque (' + data.cantempaq + " " + tipo_unid_text2 + ")",
                                    icon: 'error',
                                    allowOutsideClick: false
                                });
                                break;
                            case ((unid_selected == 0 && cant_selected > (data.umb - data.ucb)) || (unid_selected == 1 && (cant_selected > (((data.umb - data.ucb) > 0) ? (((data.umb - data.ucb) * uniemp) + (data.ump - data.ucp)) : (data.ump - data.ucp))))):
                                $("#cant" + index).val(parseInt((unid_selected == 0) ? (data.umb - data.ucb) : (((data.umb - data.ucb) > 0) ? (((data.umb - data.ucb) * uniemp) + (data.ump - data.ucp)) : (data.ump - data.ucp))));
                                let tipo_unid_text1 = (unid_selected == 1) ? $("#unid" + index + " option[value='1']").text() : $("#unid" + index + " option[value='0']").text();
                                Swal.fire({
                                    title: 'Atención!',
                                    html: 'la cantidad ingresada de ' + data.descrip + ' es mayor a la disponible (' + ((unid_selected == 0) ? (data.umb - data.ucb) : (((data.umb - data.ucb) > 0) ? (((data.umb - data.ucb) * uniemp) + (data.ump - data.ucp)) : (data.ump - data.ucp))) + ' ' + tipo_unid_text1 + ')',
                                    icon: 'error',
                                    allowOutsideClick: false
                                });
                                break;
                            }
                        } else {
                            let tamano = parseInt($("#table_data tbody tr:last-child").index());
                            //remove colum 
                            $("#item" + parseInt($("#idx_" + index).parents("tr").index())).parents("tr").remove();
                            // changes id
                            actualizarIdxAlBorrar(index, tamano);
                            // si no tiene existencia disponible no comprometida, entonces lista el error
                            arr_message_items_to_eliminate.push('no se agregó el producto "' + data.descrip + ' (sku:' + data.codprod + ')" porque no tiene existencia disponible.');
                        }

                    } else {
                        let tamano = parseInt($("#table_data tbody tr:last-child").index());
                        //remove colum 
                        $("#item" + parseInt($("#table_data tbody tr:last-child").index())).parents("tr").remove();
                        // changes id
                        actualizarIdxAlBorrar(parseInt($("#table_data tbody tr:last-child").index()), tamano);
                        // si no tiene existencia disponible no comprometida, entonces lista el error
                        arr_message_items_to_eliminate.push('no se agregó el producto "' + data.descrip + ' (sku:' + data.codprod + ')" porque no tiene existencia disponible.');
                    }
                }
            }
        });
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
        async: false,
        cache: false,
        url: "ventas3_ped_data.php?op=datos_doc",
        method: "POST",
        dataType: "json",
        data: {
            numerod: numerod,
            tipofac: tipofac
        },
        beforeSend: function () {
            carga_de_documento_en_proceso = true;
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
                    $("#depo").val(data.head.codubic);
                    $("#clie").prop("disabled", true);
                    $("#vend").prop("disabled", true);
                    $("#depo").prop("disabled", true);
                    $("#precio").val(data.head.precio);
                    $("#subttl").text(parseFloat(Math.round(data.head.subtotal * 100) / 100).format_money(2, 3, '.', ','));
                    $("#imp_16").text(parseFloat(Math.round(data.head.iva * 100) / 100).format_money(2, 3, '.', ','));
                    $("#imp_per").text(parseFloat(Math.round(data.head.pvp * 100) / 100).format_money(2, 3, '.', ','));
                    $("#imp_18").text(parseFloat(Math.round(data.head.ial * 100) / 100).format_money(2, 3, '.', ','));
                    $("#ttlbs").text(parseFloat(Math.round(data.head.total * 100) / 100).format_money(2, 3, '.', ','));
                    $("#ttld").text(parseFloat(Math.round(data.head.totald * 100) / 100).format_money(2, 3, '.', ','));
                    $("#tasa").text(parseFloat(data.head.tasa).format_money(2, 3, '.', ','));

                    // variables oculta
                    $('#subttl_input').val(data.head.subtotal);
                    $('#imp_16_input').val(data.head.iva);
                    $('#imp_per_input').val(data.head.pvp);
                    $('#imp_18_input').val(data.head.ial);
                    $('#ttlbs_input').val(data.head.total);

                    //comentary
                    arr_notas = Array(
                        data.head.notas1,
                        data.head.notas2,
                        data.head.notas3,
                        data.head.notas4,
                        data.head.notas5
                        );

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
                        $("#uniemp" + index).val(opt.cantempaq);
                        $("#umb" + index).val(opt.umb);
                        $("#ump" + index).val(opt.ump);
                        $("#ucb" + index).val(opt.ucb);
                        $("#ucp" + index).val(opt.ucp);
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
                        $('#cant' + index).trigger("change");
                        $("#item" + index).trigger("keyup");
                    });
                    let renglones = parseInt($("#table_data tbody tr:last-child").index()) + 1;
                    if (renglones != cant_renglones) {
                        $(".add-new").trigger('click');
                    }
                    //$("#btn_prod").prop("disabled", true);
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
        },
        complete: function () {
            carga_de_documento_en_proceso = false;
        }
    });

    // si hubo error de existencia procede
evaluar_error_existencia()
}

function evaluar_error_existencia() {
    if (arr_message_items_to_eliminate.length > 0) {
        let mensaje_error = '';
        $.each(arr_message_items_to_eliminate, function (index, value) {
            mensaje_error += (value + '</br></br>');
        });
        arr_message_items_to_eliminate = new Array();
        Swal.fire({
            title: 'Atención!',
            html: mensaje_error,
            icon: 'error',
            allowOutsideClick: false
        });
    }
    calculos_facturar()
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
    let iva_16 = 0;
    let tasa = parseFloat($('#tasa').text().replace('.', '').replace(',', '.'));
    let subttl = parseFloat($('#subttl_input').val());
    let imp_16 = parseFloat($('#imp_16_input').val());
    let imp_per = parseFloat($('#imp_per_input').val());
    let imp_18 = parseFloat($('#imp_18_input').val());
    let primer_des = $('#primer_des').val();
    primer_des = (primer_des === '') ? 0 : (parseFloat(primer_des) / 100);
    descuento = subttl * primer_des;
    subttl_con_des = subttl - descuento;
    iva_16 = imp_16 - (imp_16 * primer_des);
    //iva_16 = (primer_des > 0) ? (subttl_con_des * 0.16) : imp_16;
    let total = subttl_con_des + iva_16 + imp_per + imp_18;

    // Bs
    $("#total_ope_bs").val((Math.round(subttl * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_neto_bs").val((Math.round(subttl_con_des * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_imp_16_bs").val((Math.round(iva_16 * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_imp_per_bs").val((Math.round(imp_per * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_imp_18_bs").val((Math.round(imp_18 * 100) / 100).format_money(2, 3, '.', ','));
    $('#ttl_gral_bs').val((Math.round(total * 100) / 100).format_money(2, 3, '.', ','));

    // $$
    $("#total_ope_d").val((Math.round((subttl / tasa) * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_neto_d").val((Math.round((subttl_con_des / tasa) * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_imp_16_d").val((Math.round((iva_16 / tasa) * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_imp_per_d").val((Math.round((imp_per / tasa) * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_imp_18_d").val((Math.round((imp_18 / tasa) * 100) / 100).format_money(2, 3, '.', ','));
    $('#ttl_gral_d').val((Math.round((total / tasa) * 100) / 100).format_money(2, 3, '.', ','));

}

function setValueOnPressEnter(it, event) {
    if (event.keyCode == 13) {
        switch ($(it).prop("id")) {
        case 'primer_des':
            if ($(it).val() === '') {
                $(it).val('0.00');
            } else {
                if (!generacion_documento_en_proceso) {
                        //$('#total_modal_form').submit();
                    generacion_documento_en_proceso = true;
                }
            }
            break;
        }
        event.preventDefault(); //No se activará la acción predeterminada del evento
    }

    return true;
}

function procesarPedido(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento

    $('#tipo_ope').prop('disabled', false);
    $("#clie").prop("disabled", false);
    $("#vend").prop("disabled", false);
    $("#depo").prop("disabled", false);
    const formData = new FormData($("#clie_form")[0]);
    let formTabla = new FormData($("#form_table")[0]);
    let formTotales = new FormData($("#total_modal_form")[0]);
    let formComentario = new FormData($("#comentario_form")[0]);
    for (let pair of formTabla.entries()) {
        formData.append(pair[0], pair[1]);
    }
    for (let pair of formTotales.entries()) {
        formData.append(pair[0], pair[1]);
    }
    for (let pair of formComentario.entries()) {
        formData.append(pair[0], pair[1]);
    }
    formData.append('tasa', parseFloat($('#tasa').text().replace('.', '').replace(',', '.')));
    formData.append('numerod_c', $('#correl_c').text());
    formData.append('tipofac_c', $('#tipofac_c').text());

    $.ajax({
        url: "ventas3_ped_data.php?op=generarpedido",
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
            $("#clie").prop("disabled", true);
            $("#vend").prop("disabled", true);
            $("#depo").prop("disabled", true);
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
            $("#clie").prop("disabled", true);
            $("#vend").prop("disabled", true);
            $("#depo").prop("disabled", true);

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
                if (mensaje.includes('Cantidad superior a la Existencia Actual')) {
                    actualizarExistenciasEnCasoError();
                    $('#totalModal').modal('hide');
                }
                return (false);
            } else {
                $('#totalModal').modal('hide');
                $("#btn_limpiar").trigger('click');
                arr_notas = Array();
            }
        }
    });
}


init();