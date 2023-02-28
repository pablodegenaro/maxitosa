$(document).ready(function () {
    $("#alm_anti").on("click", function (e) {
        if ($("#alm_anti").is(':checked')) {
            $('#monto_anticipo').prop('disabled', false);
        } else {
            $('#monto_anticipo').prop('disabled', true);
        }
        $('#monto_anticipo').val("0");
    });
});

function enterKeyPressedCxc(it, event) {
    let index = parseInt($(it).parents("tr").index());
    let tamano = parseInt($("#cxc_data tbody tr:last-child").index()) + 1;
    //console.log('enterKeyPressedCxc ' + index);
    if (event.keyCode == 13) {

        let montototalnc = Number($('#monto_nc').val().replace('.', '').replace(',', '.'));
        let pagototalnc = Number($('#pagototal').val().replace('.', '').replace(',', '.')) - Number($('#pago' + index).val());
        let saldocxc = Number($('#saldocxc' + index).val());

        //set mount
        let monto_disponible = montototalnc - pagototalnc;
        if (monto_disponible > 0) {
            switch (true) {
            case (monto_disponible <= saldocxc):
                $('#pago' + index).val(Math.round(monto_disponible * 100) / 100);
                console.log('monto_disponible <= saldocxc', monto_disponible, saldocxc)
                break;
            case (monto_disponible > saldocxc):
                $('#pago' + index).val(saldocxc);
                console.log('monto_disponible > saldocxc', monto_disponible, saldocxc)
                break;
            }
        }

        //set focused
        if (index < tamano) {
            $('#pago' + (index + 1)).focus();
        }

        calculos_CXC();
        event.preventDefault(); //No se activará la acción predeterminada del evento
    }

    return true;
}

function enterKeyPressedAnticipoCxc(it, event) {
    if (event.keyCode == 13) {
        let montototalnc = Number($('#monto_nc').val().replace('.', '').replace(',', '.'));
        let pagototalnc = Number($('#pagototal').val().replace('.', '').replace(',', '.'));
        let monto_anticipo = Number($('#monto_anticipo').val());

        //set mount
        let monto_disponible = montototalnc - pagototalnc + monto_anticipo;
        if (monto_disponible > 0) {
            $('#monto_anticipo').val(truncate2decimal(monto_disponible));
        }

        calculos_CXC();
        event.preventDefault(); //No se activará la acción predeterminada del evento
    }

    return true;
}

function cerrarModalCXC() { $('#pagosdevolModal').modal('hide'); }

function listarCXC(data) {
    $("#cxc_data tbody").empty();
    $.each(data, function (idx, opt) {
        var index = parseInt($("#cxc_data tbody tr:last-child").index()) + 1;
        //se va llenando cada registo en el tbody
        $('#cxc_data')
        .append(
            '<tr>' +
            '<input type="hidden"id="idxcxc' + index + '" value="' + index + '"/>' +
            '<input type="hidden" name="unicocxc[]" id="unicocxc' + index + '" value="' + opt.unico + '"/>' +
            '<input type="hidden" name="doccxc[]" id="doccxc' + index + '" value="' + opt.numero + '"/>' +
            '<input type="hidden" id="saldocxc' + index + '" value="' + opt.saldo.replace('.', '').replace(',', '.') + '"/>' +
            '<td style="width: 6%" class="text-left">' + opt.tipo + '</td>' +
            '<td style="width: 14%" class="text-left">' + opt.numero + '</td>' +
            '<td style="width: 14%" class="text-left">' + opt.detalle + '</td>' +
            '<td style="width: 10%" class="text-left">' + opt.vendedor + '</td>' +
            '<td style="width: 10%" class="text-center">' + opt.fechae + '</td>' +
            '<td style="width: 10%" class="text-center">' + opt.fechav + '</td>' +
            '<td style="width: 17%" class="text-right">' + opt.saldo + '</td>' +
            '<td style="width: 19%"><input type="text" class="form-control text-right" name="pago[]" id="pago' + index + '" value="0" min="0" onkeypress="return isNumberKey(this, event)" onkeyup="evaluarSaldo(' + index + ')" onkeydown="return enterKeyPressedCxc(this,event)"></td>' +
            '</tr>'
            );
    });
    $('#nc_nro').val($('#correl').text());
    $('#monto_nc').val($('#ttl_gral_bs').val());
    $('#pagototal').val((0).format_money(2, 3, '.', ','));
    calculos_CXC();
}

function evaluarSaldo(index) {
    //console.log('evaluarSaldo ' + index);
    let pago = $('#pago' + index).val();
    if (pago !== '') {
        pago = Number(pago);
        let sum_monto_nc = 0;
        let montototalnc = Number($('#monto_nc').val().replace('.', '').replace(',', '.'));
        let monto_anticipo = Number($('#monto_anticipo').val());
        $("#nc_form").serializeArray().forEach(value => {
            if (value.name === 'pago[]' && value.value !== '0' && value.value !== '') {
                sum_monto_nc += Number(value.value);
            }
        });
        let saldocxc = Number($('#saldocxc' + index).val());

        let monto_disponible = montototalnc - monto_anticipo - sum_monto_nc;
        switch (true) {
        case (monto_disponible >= 0 && pago <= saldocxc):
            $('#pago' + index).val(Math.round(pago * 100) / 100);
            break;
        case (monto_disponible >= 0 && pago > saldocxc):
            $('#pago' + index).val(saldocxc);
            break;
        case (monto_disponible <= 0 && pago > saldocxc):
            $('#pago' + index).val(saldocxc);
            break;
        case ((sum_monto_nc + monto_anticipo) > montototalnc):
                // en esta caso monto_disponible es negativo
            $('#pago' + index).val(truncate2decimal(pago + monto_disponible));
            break;
        }
    }
    calculos_CXC();
}

function evaluarAnticipoNe() {
    let sum_monto_nc = 0;
    let montototalnc = Number($('#monto_nc').val().replace('.', '').replace(',', '.'));
    let monto_anticipo = Number($('#monto_anticipo').val());
    $("#nc_form").serializeArray().forEach(value => {
        if (value.name === 'pago[]' && value.value !== '0' && value.value !== '') {
            sum_monto_nc += Number(value.value);
        }
    });

    let monto_disponible = montototalnc - sum_monto_nc - monto_anticipo;
    if ((sum_monto_nc + monto_anticipo) > montototalnc) {
        // en esta caso monto_disponible es negativo
        $('#monto_anticipo').val(monto_anticipo + monto_disponible);
    }

    calculos_CXC();
}

function calculos_CXC() {
    let idx = 0;
    let montopago = Number('0.00');
    let montototalnc = Number($('#monto_nc').val().replace('.', '').replace(',', '.'));
    let monto_anticipo = Number($('#monto_anticipo').val()) + 0;
    $("#nc_form").serializeArray().forEach(value => {
        if (value.name === 'idx[]' && value.value !== '') {
            idx = value.value;
        }
        if (value.name === 'pago[]' && value.value !== '0' && value.value !== '') {
            montopago += Number(value.value);
        }
    });
    $('#pagototal').val((montopago + monto_anticipo).format_money(2, 3, '.', ','));
    //console.error(montototalnc, (montopago + monto_anticipo))
    $('#btn_accept_nc').prop('disabled', !(montototalnc.format_money(2, 3, '.', ',') == (montopago + monto_anticipo).format_money(2, 3, '.', ',')));
}