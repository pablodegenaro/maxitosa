
function init() {
    $('#sucursal').val('1');
    setTimeout(() => { $('#sucursal').trigger('change'); }, 500);

    $('#codvend').one('select2:open', function (e) {
        $('input.select2-search__field').prop('placeholder', 'Buscar...');
    });

    $("#check_dia").on("click", function (e) {
        let isChecked = ($("#check_dia").is(':checked'));
        if (isChecked) {
            $("#dia > option").prop("selected", "selected");
        } else {
            $("#dia > option").prop("selected", "");
        }
        $("#dia").trigger("change");
    });
};

$(document).ready(function () {
    $("#sucursal").change(() => {
        let sucursal = $("#sucursal").val();
        $('#codvend').empty();
        $('#codvend').val(null);
        $.post("frente_ruta_data.php?op=vendedor_por_sucursal", { sucursal: sucursal }, function (data, status) {
            if (!jQuery.isEmptyObject(data)) {
                $('#codvend').append('<option name="" value="">-- Seleccione --</option>');
                $.each(data, function(idx, opt) {
                    //se itera con each para llenar el select en la vista
                    $('#codvend').append('<option name="" value="' + opt.CodVend +'">' + opt.CodVend.substr(0, 35) + ' - '+ opt.Descrip.substr(0, 35) + '</option>');
                });
                $('#codvend').change();
            }
        }, 'json');
    });
});

function generarExcel() {
    let sucursal = $('#sucursal').val()
    let codvend = $('#codvend').val()
    let dia = $('[name="dia[]"]').val()

    if (sucursal !== "" && codvend !== "" && dia.length>0) {
        let datos = $('#formulario').serialize();
        window.open('frente_ruta_excel.php?&' + datos, '_blank');
    } else {
        Swal.fire({
            title: "Atención!",
            html: "Hay campos faltantes",
            icon: "error",
            allowOutsideClick: false
        });
    }
};

init()