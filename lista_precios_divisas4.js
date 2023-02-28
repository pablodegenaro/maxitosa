
function init() {

    $("#check_marca").on("click", function (e) {
        let isChecked = ($("#check_marca").is(':checked'));
        if (isChecked) {
            $("#marca > option").prop("selected", "selected");
        } else {
            $("#marca > option").prop("selected", "");
        }
        $("#marca").trigger("change");
    });

    $("#check_prove").on("click", function (e) {
        let isChecked = ($("#check_prove").is(':checked'));
        if (isChecked) {
            $("#prove > option").prop("selected", "selected");
        } else {
            $("#prove > option").prop("selected", "");
        }
        $("#prove").trigger("change");
    });
};

$(document).ready(function () {
    $('[name="instap[]"]').change(() => obtener_insta_hijos());
});

function obtener_insta_hijos() {
    let instap = $('[name="instap[]"]').val()

    $('#insta').val(null).trigger('change');
    $('#insta').html("").trigger('change');
    if (instap.length > 0) {
        $.ajax({
            url: "lista_precios_divisas4_data.php?op=instancias_hijo",
            type: "POST",
            dataType: "json",
            data: { instap: instap },
            beforeSend: function () {
                //
            },
            error: function (e) {
                console.log(e.responseText);
            },
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    //lista de seleccion
                    $.each(data, function (idx, opt) {
                        //se itera con each para llenar el select en la vista
                        $('#insta').append('<option name="" value="' + opt.codinst + '" selected="">' + opt.descrip.substr(0, 35) + '</option>').trigger('change');
                    });
                }
            }
        });
    }
}

function generarPdf() {
    let depo = $('#depo').val()
    let marca = $('[name="marca[]"]').val()
    let instap = $('[name="instap[]"]').val()
    let insta = $('[name="insta[]"]').val()
    let prove = $('[name="prove[]"]').val()
    let orden = $('#orden').val()
    let p1 = $("#p1").is(':checked')
    let p2 = $("#p2").is(':checked')
    let p3 = $("#p3").is(':checked')
    let p4 = $("#p4").is(':checked')
    let p5 = $("#p5").is(':checked')
    let p6 = $("#p6").is(':checked')
    let p7 = $("#p7").is(':checked')
    let p8 = $("#p8").is(':checked')
    let exis = $("#exis").is(':checked')
    let divisa = $("#divisa").is(':checked')


    if (depo !== "" && marca.length > 0 && instap.length > 0 && insta.length > 0 && prove.length > 0 && orden !== "") {
        let datos = $('#formulario').serialize();
        window.open('lista_precios_divisas4_pdf.php?&' + datos, '_blank');
    } else {
        Swal.fire({
            title: "Atención!",
            html: "Hay campos faltantes",
            icon: "error",
            allowOutsideClick: false
        });
    }
}

function generarExcel() {
    //let depo = $('#depo').val()
    let marca = $('[name="marca[]"]').val()
    let instap = $('[name="instap[]"]').val()
    let insta = $('[name="insta[]"]').val()
    let prove = $('[name="prove[]"]').val()
    let orden = $('#orden').val()
    let p1 = $("#p1").is(':checked')
    let p2 = $("#p2").is(':checked')
    let p3 = $("#p3").is(':checked')
    let p4 = $("#p4").is(':checked')
    let p5 = $("#p5").is(':checked')
    let p6 = $("#p6").is(':checked')
    let p7 = $("#p7").is(':checked')
    let p8 = $("#p").is(':checked')
    let exis = $("#exis").is(':checked')
    let divisa = $("#divisa").is(':checked')

    if ( marca.length > 0 && instap.length > 0 && insta.length > 0 && prove.length > 0 && orden !== "") {
        let datos = $('#formulario').serialize();
        window.open('lista_precios_divisas4_excel.php?&' + datos, '_blank');
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