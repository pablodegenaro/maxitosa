var tabla;

//Función que se ejecuta al inicio
function init() {
    listar();
}

//function listar
function listar() {
    tabla = $('#usuario_data').dataTable({

        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        "ajax":
        {
            url: 'usuario_data.php?op=listar',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
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

function guardarRolSeleccionado(usuario_id, key) {
    let value = $('#rol'+key).val();
    $.ajax({
        url: "usuario_data.php?op=guardarseleccionado",
        type: "POST",
        dataType: "json",
        data: {id: usuario_id, value: value},
        error: function(e){
            $('#rol'+key).val(value);
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

            //verifica si el mensaje de insercion contiene error
            if(icono.includes('error')) {
                $('#rol'+key).val('');
                return (false);
            } else {
                //$('#usuario_data').DataTable().ajax.reload();
            }
        }
    });
}

function guardarAcceso(codusua, idx) {
    let state = (document.getElementById('access_'+idx).checked === true) ? 1 : 0;
    $.ajax({
        url: 'usuario_data.php?op=guardaracceso',
        type: "POST",
        dataType: "json",
        data: { codusua: codusua, access: state },
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






