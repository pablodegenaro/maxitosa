
//Función que se ejecuta al inicio
function init() {
    titulo_permisos();
    listar_permisos();

    //cuando se da click al boton submit entonces se ejecuta la funcion guardaryeditar(e);
    $("#rol_form").on("submit", function (e) {
        guardaryeditar(e);
    })

    //cambia el titulo de la ventana modal cuando se da click al boton
    /*$("#btnGestion").click(function () {
        window.location = ("../gestionsistema/gestionsistema.php");
    });*/

    $("#btnVolver").click(function () {
        switch (parseInt($("#tipo").val())) {
            case 0: //volver a roles
                window.location = ("principal1.php?page=roles&mod=1");
                break;
            case 1: //volver a usuarios
                window.location = ("principal1.php?page=usuario&mod=1");
                break;
            }
        });


}

$(document).ready(function () {
    switch (parseInt($("#tipo").val())) {
    case 0: $('#btnVolver').val('Volver a roles'); break;
    case 1: $('#btnVolver').val('Volver a usuarios'); break;
    }
});

function titulo_permisos() {
    let isError = false;
    let id = $("#tipoid").val();
    let tipo = $("#tipo").val();
    $.ajax({
        url: 'permiso_descripcion.php',
        method: "POST",
        dataType: "json",
        data: {id: id, tipo: tipo},
        error: function (e) {
            console.log(e.responseText);
        },
        success: function (data) {
            if (!jQuery.isEmptyObject(data)) {
                $('#title_permisos').text('Permisos de ' + data.descripcion.toUpperCase());
            }
        }
    });
}

function listar_permisos() {
    let isError = false;
    let id = $("#tipoid").val();
    let tipo = $("#tipo").val();
    $.ajax({
        url: 'permiso_lista.php',
        method: "POST",
        dataType: "json",
        data: {
            id: id, 
            tipo: tipo, 
            esMenuLateral: 0,
            codemenu: "0"
        },
        error: function (e) {
            console.log(e.responseText);
        },
        success: function (data) {
            if (!jQuery.isEmptyObject(data)) {
                $('#permisos').html(permisosRecursion(data, ''));
            }
        }
    });
}

function guardar(modulo_id) {
    let id = $("#tipoid").val();
    let tipo = $("#tipo").val();
    let state = document.getElementById('modulo_'+modulo_id).checked === true;
    $.ajax({
        url: 'permiso_guardar.php',
        type: "POST",
        dataType: "json",
        data: {tipo: tipo, id: id, modulo_id: modulo_id, state: state},
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
            })
            Toast.fire({
                icon: icono,
                title: mensaje
            })
        }
    });
}

init();