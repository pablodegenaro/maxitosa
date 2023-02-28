var tabla;

//Función que se ejecuta al inicio
function init() {
    listar();
}

/*funcion para limpiar formulario de modal*/
function limpiar() {
    $("#user").val("");
    $('#id_ope').val("");
    $(".modal-title").text("Crear Operador");
}

//function listar
function listar() {
    tabla = $('#operador_data').dataTable({

        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        "ajax":
        {
            url: 'taller_operador_data.php?op=listar',
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

function mostrar(id = -1) {
    limpiar();
    $('#operadorModal').modal('show');
    //si es -1 el modal es crear nuevo
    if(id !== -1)
    {
        $.ajax({
            url: "roles_data.php?op=mostrar",
            method: "POST",
            dataType: "json",
            data:  {id_rol: id_rol},
            error: function (e) {
                console.log(e.responseText);
            },
            success: function (data) {
                //si existe la cedula_relacion entonces tiene relacion con otras tablas
                if (data.cedula_relacion) {

                    $('#rolModal').modal('show');
                    $('#rol').val(data.cedula_relacion);
                    //desactiva el campo

                    $('#rol').val(data.descripcion);
                    $("#rol").prop("disabled", false);

                    $('.modal-title').text("Editar Rol");
                    $('#id_rol').val(id_rol);

                } else {

                    $('#rolModal').modal('show');
                    $('#rol').val(data.descripcion);
                    $("#rol").prop("disabled", false);

                    $('.modal-title').text("Editar Rol");
                    $('#id_rol').val(id_rol);
                }
            }
        });
    }
}

//la funcion guardaryeditar(e); se llama cuando se da click al boton submit
function guardaryeditar(e) {

    e.preventDefault(); //No se activará la acción predeterminada del evento
    var formData = new FormData($("#rol_form")[0]);

    $.ajax({
        url: "roles_data.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        error: function (e) {
            console.log(e.responseText);
        },
        success: function (datos) {
            let { icono, mensaje } = datos;
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
            if(mensaje.includes('error')) {
                return (false);
            } else {
                $('#rol_form')[0].reset();
                $('#rolModal').modal('hide');
                $('#roles_data').DataTable().ajax.reload();
                limpiar();
            }
        }
    });
}

function eliminar(id, rol) {

    Swal.fire({
        // title: '¿Estas Seguro?',
        text: "¿Estas Seguro de Eliminar el Rol "+rol+" ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "roles_data.php?op=eliminar",
                method: "POST",
                dataType: "json",
                data: {id_rol: id},
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
                    //verifica si el mensaje de insercion contiene error
                    if( !icono.includes('error') ) {
                        $('#rol_form')[0].reset();
                        $('#rolModal').modal('hide');
                        $('#roles_data').DataTable().ajax.reload();
                        limpiar();
                    }
                }
            });
        }
    })
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






