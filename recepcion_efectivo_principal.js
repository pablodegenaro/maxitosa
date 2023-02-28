
$(document).ready(function(){
    $('#principal').hide();
    $('#reporte').hide();
});


var formulario = document.getElementById('form_recepcion');
formulario.addEventListener('submit',function(e){
    e.preventDefault();
    var url = "principal1.php?page=recepcion_efectivo_principal&mod=1";
    if($('#fecha').val() == '' || $('#moneda').val() == ''){
        Swal.fire({
            icon: 'error',
            title: 'Error...',
            text: "Seleccione la Fecha y Moneda",
        })
    }
    if($('#fecha').val() != '' && $('#moneda').val() != ''){
        var data = new FormData(formulario);        
        var getfecha = $('#fecha').val().split('-');
        var fecha = getfecha[2]+"-"+getfecha[1]+"-"+getfecha[0];
        if($('#moneda').val() === 'TD'){
            fetch(url,{
                method:'POST',
                body:data
            }).then((res) => {
                if(res){
                    $('.card-title').empty();
                    $('.card-title').append('<p>Caja del Dia'+' '+fecha+'</p>');
                    $('#reporte').show();
                    $('#principal').hide();
                }
            }).catch((err) => {console.log(err)}); 
        }
        if($('#moneda').val() !== 'TD'){
            fetch(url,{
                method:'POST',
                body:data
            }).then((res) => {
                if(res){
                    $('.card-title').empty();
                    $('#principal').show();
                    $('#reporte').hide();
                    $('.card-title').append('<p>Caja del Dia'+' '+fecha+'</p>');
                }
            }).catch((err) => {console.log(err)}); 
        }
    }     
});       


function regresa(){
    window.location = "principal1.php?page=dashboard_1&mod=1&s=00000";
}