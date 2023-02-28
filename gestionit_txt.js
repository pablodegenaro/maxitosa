
function init1() {
    $("#permisos_form").on("submit", function(e){
        leer_txt_permisos(e);
    });
}


//la funcion leer_txt_permisos(e); se llama cuando se da click al boton submit
function leer_txt_permisos(e) {

    e.preventDefault(); //No se activará la acción predeterminada del evento
    let check = (document.getElementById('customRadio1').checked === true) ? 1 : 0;
    let text = (check === 1)
    ? 'ACTUALIZAR' 
    : 'REEMPLAZAR';
    
    Swal.fire({
        title: '¿Estas Seguro?',
        text: "¿De "+text+" los Permisos?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, '+text+'!',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.value) {
            var formData = new FormData($("#permisos_form")[0]);
            $.ajax({
                url: "gestionit_permisostxt.php?op=leer_archivo",
                type: "POST",
                dataType: "json",
                data: formData,
                contentType: false,
                processData: false,
                cache:false,
                enctype: 'multipart/form-data',
                beforeSend: function () {
                    $('#cargar_button').prop('disabled', true);
                },
                error: function (e) {
                    $('#cargar_button').prop('disabled', false);
                    console.log(e.responseText);
                },
                success: function (data) {
                    let { icono, mensaje } = data;
                    $('#cargar_button').prop('disabled', false);

                    
                    //verifica si el mensaje de insercion contiene error
                    if (icono.includes('error')) {
                        Swal.fire({
                            title: 'Atención!',
                            html: mensaje,
                            icon: 'error',
                            allowOutsideClick: false
                        });
                        return (false);
                    } else {
                        window.location.reload();
                    }
                }
            });
        }
    });

}

init1();