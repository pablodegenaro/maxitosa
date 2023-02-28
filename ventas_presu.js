var tabla_productos;
var tabla_documentos;
var cant_renglones = 9;

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
        procesarPresupuestoCr(e);
    });

    limpiar();
    correlYtasa();
}

function limpiar() {
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
    $("#ttl_gral_bs").val(0);
    $("#ttl_gral_d").val(0);
    $("#anticipo").val('');
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
    $.post("ventas_presu_data.php?op=index", function (data, status) {
        if (!jQuery.isEmptyObject(data)) {
            $('#correl').text(data.correl);
            $('#tasa').text(data.factor + ' Bs');
        }
    }, 'json');
}

$(document).ready(function () {
    $("#clie").change(() => {
        if ($("#clie").val() !== "") {
            habilitar();
            let codclie = $("#clie").val();
            $.post("ventas_presu_data.php?op=datos_clie", { codclie: codclie }, function (data, status) {
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
        url: "ventas_presu_data.php?op=buscar_cant_prd",
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
            url: 'ventas_presu_data.php?op=listar_prd',
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

function cantidadPrd(it) {
    let index = parseInt($(it).parents("tr").index());
    let codprod = $("#item" + index).val();
    seleccionarPrd(codprod, index);
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
        let precio = $("#tipo_precio").val();
        let tasa = parseFloat($('#tasa').text().replace('.', '').replace(',', '.'));
        $.post("ventas_presu_data.php?op=datos_prod",
        {
            codprod: codprod,
            cant: cant,
            unid: unid,
            precio: precio,
            depo: depo,
            tasa: tasa
        },
        function (data, status) {
            if (!jQuery.isEmptyObject(data)) {
                $("#uniemp" + index).val(data.cantempaq);
                $("#umb" + index).val(data.umb);
                $("#ump" + index).val(data.ump);
                $("#ucb" + index).val(data.ucb);
                $("#ucp" + index).val(data.ucp);
                $("#iva16_" + index).val(data.iva);
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
                $("#precio_input" + index).removeClass('bg-primary').removeClass('bg-navy').removeClass('bg-info').removeClass('bg-purple')
                switch (parseInt(precio)) {
                case 1: $("#precio_input" + index).addClass('bg-navy'); break;
                case 2: $("#precio_input" + index).addClass('bg-primary'); break;
                case 3: $("#precio_input" + index).addClass('bg-info'); break;
                default: $("#precio_input" + index).addClass('bg-purple'); break;
                }
                $("#item" + index).trigger("keyup");
                evaluarTabla();
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

function calculos_facturar() {
    let idx = 0;
    let iva16 = 0;
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
        if (value.name === 'subtotal[]' && value.value !== '0') {
            subtotal += parseFloat(value.value.replace('.', '').replace(',', '.'));
        }
    });

    total = (subtotal + iva16);

    // variables oculta
    $('#subttl_input').val(subtotal);
    $('#imp_16_input').val(iva16);
    $('#ttlbs_input').val(total);

    //variables visibles
    $('#subttl').text((Math.round(subtotal * 100) / 100).format_money(2, 3, '.', ','));
    $('#imp_16').text((Math.round(iva16 * 100) / 100).format_money(2, 3, '.', ','));
    $('#ttlbs').text((Math.round(total * 100) / 100).format_money(2, 3, '.', ','));
    $('#ttld').text(((tasa !== 0) ? Math.round((total / tasa) * 100) / 100 : (0)).format_money(2, 3, '.', ','));
}

function calculos_totales() {
    let iva_16 = 0;
    let tasa = parseFloat($('#tasa').text().replace('.', '').replace(',', '.'));
    
    let subttl = parseFloat($('#subttl_input').val());
    let imp_16 = parseFloat($('#imp_16_input').val());
    let primer_des = $('#primer_des').val();
    primer_des = (primer_des === '') ? 0 : (parseFloat(primer_des) / 100);
    descuento = subttl * primer_des;
    subttl_con_des = subttl - descuento;
    iva_16 = imp_16 - (imp_16 * primer_des);
    let total = subttl_con_des + iva_16;

    // Bs
    $("#total_ope_bs").val((Math.round(subttl * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_neto_bs").val((Math.round(subttl_con_des * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_imp_16_bs").val((Math.round(iva_16 * 100) / 100).format_money(2, 3, '.', ','));
    $('#ttl_gral_bs').val((Math.round(total * 100) / 100).format_money(2, 3, '.', ','));

    // $$
    $("#total_ope_d").val((Math.round((subttl / tasa) * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_neto_d").val((Math.round((subttl_con_des / tasa) * 100) / 100).format_money(2, 3, '.', ','));
    $("#ttl_imp_16_d").val((Math.round((iva_16 / tasa) * 100) / 100).format_money(2, 3, '.', ','));
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

function procesarPresupuestoCr(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento

    $('#tipo_ope').prop('disabled', false);
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
        url: "ventas_presu_data.php?op=presupuestar",
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
            }
        }
    });


}


init();