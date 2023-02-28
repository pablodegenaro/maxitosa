
var cant_renglones = 9;

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

    setfocus(txt, evt)
    return true;
}

function rounding2decimal(number) {
    let float = parseFloat(number);
    return Math.round(float * 100) / 100;
}

function rounding1decimal(number) {
    let float = parseFloat(number);
    return Math.round(float * 10) / 10;
}

function truncate2decimal(number) {
    let float = parseFloat(number);
    return Math.trunc(float * 100) / 100;
}

function truncate1decimal(number) {
    let float = parseFloat(number);
    return Math.trunc(float * 10) / 10;
}

function enterKeyPressed(it, event) {
    let index = parseInt($(it).parents("tr").index());
    evaluarTabla();
    if (event.keyCode == 13) {
        //consultamos el codigo ingresado 
        let result = cantPrdResult($('#item' + index).val());
        // si el resultado es 1, significa que consiguio un producto
        //en caso contrario, levanta el modal para listar los productos
        if (result === 1) {
            //carga el producto resultado
            seleccionarPrd($('#item' + index).val(), index);
            // si hubo error de existencia procede a eliminar 
            if (!carga_de_documento_en_proceso) {
                evaluar_error_existencia();
            }
        } else {
            //lista las posibles coincidencias
            mostrarProd($('#item' + index).val(), index);
        }

        /* if ($('#renglon' + (index + 1)).length) {
            $('#item' + (index + 1)).focus()
        } */
        setfocus(it, event);
        event.preventDefault(); //No se activará la acción predeterminada del evento
    }

    return true;
}

function setfocus(it, event) {
    let index = parseInt($(it).parents("tr").index());
    if (event.keyCode == 13) {

        switch ($(it).prop("id")) {
        case ('item' + index):
            if ($('#item' + index).val().length > 0 && parseFloat($('#subtotal' + index).val()) > 0) {
                $('#cant' + index).focus()
                $('#cant' + index).select()
            }
            break;
        case ('cant' + index):
            if ($('#cant' + index).val() !== '') {
                $('#unid' + index).focus()
                $('#unid' + index).select()
            }
            break;
        case ('unid' + index):
            if ($('#renglon' + (index + 1)).length) {
                $('#item' + (index + 1)).focus()
            }
            break;
        }
        event.preventDefault(); //No se activará la acción predeterminada del evento
    }
    return true;
}



function init_tabla() {
    $("#div_btn_add").hide();

    $("#table_data tbody").empty();
    $(".add-new").trigger('click');
}

function verificarItem(it) {
    let index = parseInt($(it).parents("tr").index());
    let cant_digits = $('#item' + index).val();
    if (cant_digits.length < 1) {
        $('#cant' + index).val(1);
        $('#unid' + index).val(0);
        $('#cant' + index).prop('readonly', true);
        $('#unid' + index).prop('disabled', true);
    } else {
        $('#cant' + index).prop('readonly', false);
        $('#unid' + index).prop('disabled', false);
    }
}

$(document).ready(function () {

    // Append table with add row form on add new button click
    $(".add-new").click(function () {
        let tipo_precio = $('#tipo_precio').val();
        if (tipo_precio !== '') {
            var index = parseInt($("#table_data tbody tr:last-child").index()) + 1;
            var row = '<tr>' +
            '<input type="hidden" name="idx[]" id="idx_' + index + '" value="' + index + '"/>' +
            '<input type="hidden" name="ui[]" id="ui' + index + '" value="1"/>' +
            '<input type="hidden" name="umb[]" id="umb' + index + '" value="1"/>' +
            '<input type="hidden" name="ump[]" id="ump' + index + '" value="1"/>' +
            '<input type="hidden" name="ucb[]" id="ucb' + index + '" value="0"/>' +
            '<input type="hidden" name="ucp[]" id="ucp' + index + '" value="0"/>' +
            '<input type="hidden" name="uniemp[]" id="uniemp' + index + '" value="0"/>' +
            '<input type="hidden" name="iva16[]" id="iva16_' + index + '" value="0"/>' +
            '<input type="hidden" name="precio[]" id="precio' + index + '" value="0"/>' +
            '<input type="hidden" name="preciod[]" id="preciod' + index + '" value="0"/>' +
            '<input type="hidden" name="subtotal[]" id="subtotal' + index + '" value="0"/>' +
            '<input type="hidden" name="subtotald[]" id="subtotald' + index + '" value="0"/>' +
            '<input type="hidden" name="total[]" id="total' + index + '" value="0"/>' +
            '<input type="hidden" name="totald[]" id="totald' + index + '" value="0"/>' +
            '<input type="hidden" name="tipopvp[]" id="tipopvp' + index + '" value="' + tipo_precio + '"/>' +
            '<td class="pl-1 text-center"><span id="renglon' + index + '">' + (index + 1) + '</span></td>' +
                //(index === 0 ? '<td>--</td>' : '<td><i class="fa fa-trash text-blue delete"></i></td>') +
            '<td><i id="delete' + index + '" class="fa fa-trash text-blue delete"></i></td>' +
            '<td><input type="text" class="form-control form-control-sm text-left" name="prod[]" id="item' + index + '" onkeypress="return enterKeyPressed(this,event)" onkeyup="verificarItem(this)"></td>' +
            '<td><input type="text" class="form-control form-control-sm text-left" id="descrip' + index + '" disabled=""></td>' +
            '<td><input type="number" class="form-control form-control-sm text-left" name="cant[]" id="cant' + index + '" min="1" onkeypress="return isNumberKey(this, event)" onkeyup="cantidadPrd(this)" onchange="cantidadPrd(this)"></td>' +
            '<td>' +
            '<select id="unid' + index + '" name="unid[]" onkeypress="return setfocus(this,event)" onchange="cantidadPrd(this)" class="form-control custom-select-sm custom-select" style="width: 100%;">' +
            '<option value="1">BOT</option>' +
            '<option value="0" selected>CAJ</option>' +
            '</select>' +
            '</td>' +
            '<td><input type="text" class="form-control form-control-sm text-right" id="precio_input' + index + '"  readonly=""></td>' +
            '<td><input type="text" class="form-control form-control-sm text-right" id="preciod_input' + index + '" readonly=""></td>' +
            '<td><input type="text" class="form-control form-control-sm text-right" id="total_input' + index + '"   readonly=""></td>' +
            '<td><input type="text" class="form-control form-control-sm text-right" id="totald_input' + index + '"  readonly=""></td>' +
            '</tr>';
            $("#table_data").append(row);

            //init in 1
            $('#cant' + index).val(1);
            $('#cant' + index).prop('readonly', true);
            $('#unid' + index).prop('disabled', true);
        } else {
            Swal.fire({
                title: 'Atención!',
                html: "Seleccione un Precio",
                icon: 'error',
                allowOutsideClick: false
            });
        }
    });

    // Delete row on delete button click
    $(document).on("click", ".delete", function () {
        let index = parseInt($(this).parents("tr").index());
        let tamano = parseInt($("#table_data tbody tr:last-child").index());

        //remove colum
        $(this).parents("tr").remove();

        // changes id
        actualizarIdxAlBorrar(index, tamano);

        evaluarTabla();
    });

    init_tabla();
});

function actualizarIdxAlBorrar(index, tamano) {
    // changes id
    if (index <= tamano) {
        for (let i = index + 1; i <= tamano; i++) {
            $("#renglon" + i).text(i);
            $("#renglon" + i).attr("id", "renglon" + (i - 1));
            $("#idx_" + i).val((i - 1));
            $("#idx_" + i).attr("id", "idx_" + (i - 1));
            $("#ui" + i).attr("id", "ui" + (i - 1));
            $("#umb" + i).attr("id", "umb" + (i - 1));
            $("#ump" + i).attr("id", "ump" + (i - 1));
            $("#ucb" + i).attr("id", "ucb" + (i - 1));
            $("#ucp" + i).attr("id", "ucp" + (i - 1));
            $("#uniemp" + i).attr("id", "uniemp" + (i - 1));
            $("#iva16_" + i).attr("id", "iva16_" + (i - 1));
            $("#subtotal" + i).attr("id", "subtotal" + (i - 1));
            $("#subtotald" + i).attr("id", "subtotald" + (i - 1));
            $("#tipopvp" + i).attr("id", "tipopvp" + (i - 1));
            $("#delete" + i).attr("id", "delete" + (i - 1));
            $("#item" + i).attr("id", "item" + (i - 1));
            $("#descrip" + i).attr("id", "descrip" + (i - 1));
            $("#cant" + i).attr("id", "cant" + (i - 1));
            $("#unid" + i).attr("id", "unid" + (i - 1));
            $("#precio" + i).attr("id", "precio" + (i - 1));
            $("#preciod" + i).attr("id", "preciod" + (i - 1));
            $("#total" + i).attr("id", "total" + (i - 1));
            $("#totald" + i).attr("id", "totald" + (i - 1));

            $("#precio_input" + i).attr("id", "precio_input" + (i - 1));
            $("#preciod_input" + i).attr("id", "preciod_input" + (i - 1));
            $("#total_input" + i).attr("id", "total_input" + (i - 1));
            $("#totald_input" + i).attr("id", "totald_input" + (i - 1));
        }
    }
}

function evaluarTabla() {
    calculos_facturar();
    let tipofac_c = $('#tipofac_c').text();
    let tamano = parseInt($("#table_data tbody tr:last-child").index()) + 1;

    // si el tamano >= 0 y item(tamano)!=='', agregar nueva fila
    if ((tamano === 0) || (tamano > 0 && tamano < cant_renglones && $("#item" + (tamano - 1)).val().length > 1 && tipofac_c == '')) {
        $(".add-new").trigger('click');
    }
    cantrenglones();
}

function cantrenglones() {
    let cant = 0;
    let tamano = parseInt($("#table_data tbody tr:last-child").index());
    for (let i = 0; i <= tamano; i++) {
        if ($('#item' + i).val().length > 1 && $('#subtotal' + i).val().length > 1) {
            cant += 1;
        }
    }
    $('#itemscargado').text(cant);
}

function actualizarPreciosTabla() {
    let tamano = parseInt($("#table_data tbody tr:last-child").index());
    /* for (let i = 0; i < tamano; i++) {
        $('#item' + i).trigger(
            jQuery.Event('keypress', { keyCode: 13 })
        );
    } */
}

