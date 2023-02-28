
let tabla_modulo;
let tabla_menu;
let tabla_dasboard;

let icon;

function init() {
    //cuando se da click al boton submit entonces se ejecuta la funcion guardaryeditar(e);
    $("#modulo_form").on("submit", function (e) {
        guardaryeditarModulo(e);
    });

    //cuando se da click al boton submit entonces se ejecuta la funcion guardaryeditar(e);
    $("#menu_form").on("submit", function (e) {
        guardaryeditarMenu(e);
    });

    $("#custom-tabs-one-tab li a").on("click", function (e) {

        switch (this.id.substr(11, 35)) {
        case 'modulos':
            listar_modulos();
            break;
        case 'menu':
            listar_menus();
            break;
        case 'dashboard':
            listar_roles_para_dashboards();
            break;
        }
    });

    $("#custom-tab-modulos").trigger("click");
}

/*funcion para limpiar formulario de modal*/
function limpiar_modulo() {
    $('#id_modulo').val('');
    $('#modulo_form')[0].reset();
    $(".modal-title").text("Agregar Módulo");
    $('#ruta').html('<option value="">--Seleccione--</option>');
    $('#menu_id').html('<option value="-1">--Seleccione--</option>');
    $('#moduloModal #icono').val('').change();
    icon = '';
}

/*funcion para limpiar formulario de modal*/
function limpiar_menu() {
    $('#id_menu').val('');
    $('#menu_form')[0].reset();
    $(".modal-title").text("Agregar Menú");
    $('#menu_padre').html('');
    $('#menu_hijo').html('');
    $('#menuModal #icono').val('').change();
    icon = '';
}

$(document).ready(function () {
    $("#moduloModal #icono").change(() => {
        $('#moduloModal #icon').removeClass(icon).addClass($("#moduloModal #icono").val());
        icon = $("#moduloModal #icono").val();
    });

    $("#moduloModal #icono").on('keyup', () => {
        $('#moduloModal #icon').removeClass(icon).addClass($("#moduloModal #icono").val());
        icon = $("#moduloModal #icono").val();
    }).keyup();

    $("#menuModal #icono").change(() => {
        $('#menuModal #icon').removeClass(icon).addClass($("#menuModal #icono").val());
        icon = $("#menuModal #icono").val();
    });

    $("#menuModal #icono").on('keyup', () => {
        $('#menuModal #icon').removeClass(icon).addClass($("#menuModal #icono").val());
        icon = $("#menuModal #icono").val();
    }).keyup();
});

//function listar
function listar_modulos() {
    let isError = false;
    tabla_modulo = $('#modulo_data').dataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        "ajax":
        {
            url: 'gestionit_modulos.php?op=listar_modulos',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            },
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,//Por cada 10 registros hace una paginación
        "order": [[0, "asc"]],//Ordenar (columna,orden)
        "language": texto_español_datatables
    }).DataTable();
}

//function listar
function listar_menus() {
    let isError = false;
    tabla_menu = $('#menu_data').dataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        "ajax":
        {
            url: 'gestionit_menus.php?op=listar_menu',
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

//function listar
function listar_roles_para_dashboards() {
    let isError = false;
    tabla_dasboard = $('#dashboard_data').dataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        "ajax":
        {
            url: 'gestionit_dashboard.php?op=listar_dashboard',
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

function cambiarEstado_modulo(id, est) {

    Swal.fire({
        title: '¿Estas Seguro?',
        text: "¿De realizar el cambio de estado?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, cambiar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "gestionit_modulos.php?op=activarydesactivar_modulo",
                method: "POST",
                data: {id: id, est: est},
                success: function (data) {
                    $('#modulo_data').DataTable().ajax.reload();
                }
            });
        }
    })
}

function cambiarEstado_menu(id, est) {

    Swal.fire({
        title: '¿Estas Seguro?',
        text: "¿De realizar el cambio de estado?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, cambiar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "gestionit_menus.php?op=activarydesactivar_menu",
                method: "POST",
                data: {id: id, est: est},
                success: function (data) {
                    $('#menu_data').DataTable().ajax.reload();
                }
            });
        }
    })
}

function mostrar_modulo(id_modulo= -1) {
    let isError = false;
    limpiar_modulo();

    $('#moduloModal').modal('show');

    $.ajax({
        url: "gestionit_modulos.php?op=mostrar_modulo",
        method: "POST",
        dataType: "json",
        data: {id_modulo: id_modulo},
        error: function (e) {
            console.log(e.responseText);
        },
        success: function (data) {
            //lista de seleccion
            $.each(data.lista_menus, function(idx, opt) {
                //se itera con each para llenar el select en la vista
                $('#menu_id').append('<option name="" value="' + opt.id +'">' + opt.nombre.substr(0, 35) + '</option>');
            });

            if(id_modulo !== -1) {
                $('#moduloModal #nombre').val(data.nombre);
                $('#moduloModal #icono').val(data.icono).change();
                $('#ruta').val(data.ruta);
                $('#orden_modulo').val(data.modulo_orden);
                $('#menu_id').val(data.menu_id);
                $('#estado').val(data.status);
                $('.modal-title').text("Editar Módulo");
                $('#id_modulo').val(id_modulo);
            }

        }
    });
}

function mostrar_menu(id_menu= -1) {
    let isError = false;
    limpiar_menu();

    $('#menuModal').modal('show');

    $.ajax({
        url: "gestionit_menus.php?op=mostrar_menu",
        method: "POST",
        dataType: "json",
        data: {id_menu: id_menu},
        error: function (e) {
            console.log(e.responseText);
        },
        success: function (data) {
            //lista de seleccion
            $('#menu_padre').append('<option name="" value="-1">Ninguno</option>');
            $.each(data.lista_menus, function(idx, opt) {
                //se itera con each para llenar el select en la vista
                if (opt.id !== id_menu) {
                    $('#menu_padre').append('<option name="" value="' + opt.id +'">' + opt.nombre.substr(0, 35) + '</option>');
                }
            });

            //lista de seleccion
            $('#menu_hijo').append('<option name="" value="-1">Ninguno</option>');
            $.each(data.lista_menus, function(idx, opt) {
                //se itera con each para llenar el select en la vista
                if (opt.id !== id_menu) {
                    $('#menu_hijo').append('<option name="" value="' + opt.id + '">' + opt.nombre.substr(0, 35) + '</option>');
                }
            });

            if(id_menu !== -1) {
                $('#menuModal #nombre').val(data.nombre);
                $('#orden').val(data.menu_orden);
                $('#menu_padre').val(data.menu_padre);
                $('#menu_hijo').val(data.menu_hijo);
                $('#menuModal #icono').val(data.icono).change();

                $('#estado').val(data.status);
                $('.modal-title').text("Editar Menú");
                $('#id_menu').val(id_menu);
            }

        }
    });
}

//la funcion guardaryeditar(e); se llama cuando se da click al boton submit
function guardaryeditarModulo(e) {

    e.preventDefault(); //No se activará la acción predeterminada del evento
    let ruta = $('#modulo_form #ruta').val();
    const formData = new FormData($("#modulo_form")[0]);

    if (ruta !== "") {
        $.ajax({
            url: "gestionit_modulos.php?op=guardaryeditar_modulo",
            type: "POST",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            error: function (e) {
                console.log(e.responseText);
            },
            success: function (data) {
                let { icono, mensaje } = data

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
                    $('#modulo_form')[0].reset();
                    $('#moduloModal').modal('hide');
                    $('#modulo_data').DataTable().ajax.reload();
                    limpiar_modulo();
                }
            }
        });
    } else {
        Swal.fire({
            title: 'Atención!',
            html: 'Debe seleccionar una RUTA para continuar.'.substring(0, 400) + "...",
            icon: 'error',
            allowOutsideClick: false
        });
        return (false);
    }
}

//la funcion guardaryeditar(e); se llama cuando se da click al boton submit
function guardaryeditarMenu(e) {

    e.preventDefault(); //No se activará la acción predeterminada del evento
    const formData = new FormData($("#menu_form")[0]);

    $.ajax({
        url: "gestionit_menus.php?op=guardaryeditar_menu",
        type: "POST",
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        error: function (e) {
            console.log(e.responseText);
        },
        success: function (data) {
            let { icono, mensaje } = data

            //verifica si el mensaje de insercion contiene error
            if(mensaje.includes('error')) {
                return (false);
            } else {
                $('#menu_form')[0].reset();
                $('#menuModal').modal('hide');
                $('#menu_data').DataTable().ajax.reload();
                limpiar_menu();
            }
        }
    });
}

function guardarMenuSeleccionado(id, key, tipo) {
    let $tipo, $tabla, module;

    switch (tipo) {
    case 'modulo':
        module = 'modulos';
        $tabla = $('#modulo_data');
        $tipo = $('#menu'+key);
        break;
    case 'menu_padre':
        module = 'menus';
        $tabla = $('#menu_data');
        $tipo = $('#menu_padre'+key);
        break;
    case 'dashboard':
        module = 'dashboard';
        $tabla = $('#dashboard_data');
        $tipo = $('#dashboard'+key);
        break;
    }
    let tipo_value = $tipo.val();

    $.ajax({
        url: "gestionit_"+module+".php?op=guardarseleccionado",
        type: "POST",
        dataType: "json",
        data: {id: id, tipo: tipo, tipo_value: tipo_value},
        error: function(e){
            $tipo.val(tipo_value);
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
                $tipo.val('');
                return (false);
            } else {
                $tabla.DataTable().ajax.reload();
            }
        }
    });
}

function eliminar_modulo(id, modulo) {

    Swal.fire({
        // title: '¿Estas Seguro?',
        text: "¿Estas Seguro de Eliminar el módulo "+modulo+" ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "gestionit_modulos.php?op=eliminar_modulo",
                method: "POST",
                dataType: "json",
                data: {id: id},
                error: function (e) {
                    console.log(e.responseText);
                },
                success: function (data) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    })
                    Toast.fire({
                        icon: data.icono,
                        title: data.mensaje
                    })
                    $('#modulo_data').DataTable().ajax.reload();
                }
            });
        }
    })
}

function eliminar_menu(id, menu) {

    Swal.fire({
        // title: '¿Estas Seguro?',
        text: "¿Estas Seguro de Eliminar el menú "+menu+" ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "gestionit_menus.php?op=eliminar_menu",
                method: "POST",
                dataType: "json",
                data: {id: id},
                error: function (e) {
                    console.log(e.responseText);
                },
                success: function (data) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    })
                    Toast.fire({
                        icon: data.icono,
                        title: data.mensaje
                    })
                    $('#menu_data').DataTable().ajax.reload();
                }
            });
        }
    })
}

//Mostrar datos del usuario en la ventana modal del formularioS
init();






