$(function () {
  $("#tablaBS")
  .DataTable({
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    searching: false,
    language: texto_español_datatables,
    destroy: true,
  })
  .buttons()
  .container()
  .appendTo("#tablaBS_wrapper .col-md-6:eq(0)");

  $("#tablaDL")
  .DataTable({
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    searching: false,
    language: texto_español_datatables,
  })
  .buttons()
  .container()
  .appendTo("#tablaDL_wrapper .col-md-6:eq(0)");

  $("#tablaEU")
  .DataTable({
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    searching: false,
    language: texto_español_datatables,
  })
  .buttons()
  .container()
  .appendTo("#tablaEU_wrapper .col-md-6:eq(0)");
});

$(document).ready(function () {
  $("#form_bs").hide();
  $("#form_dl").hide();
  $("#form_eu").hide();
  key();
});

function editarForm(codVendedor, moneda) {
  const urlFactura = "recepcion_efectivo_buscar.php";
  fetch(urlFactura, {
    method: "POST",
    body: JSON.stringify({
      codVendedor: codVendedor.toString(),
      moneda: moneda.toString(),
    }),
  })
  .then((res) => res.json())
  .then((res) => {
    if (res.data.length > 0) {
      res.data.forEach((element) => {
        $("#otros").hide();
        $("#nombre1").val(element.nombreVendedor);
        $("#nombre").val(element.Descrip);
        $("#codVendedor").val(element.codVendedor);
        $("#moneda").val(element.moneda);
        $("#monto_acreditado").val(element.saldo);
        $("#correlativo").val(res.correlativo);
        $("#tipo_doc").val("Retiro");
        $(".modal-title").append(
          "<h5>Documento Nro " + res.correlativo + "</h5>"
          );
      });
    }
  })
  .catch((err) => console.log(err));

  const urlDenom = "recepcion_efectivo_denom.php";
  fetch(urlDenom, {
    method: "POST",
    body: JSON.stringify({ moneda: moneda.toString() }),
  })
  .then((res) => res.json())
  .then((res) => {
    if (res.data) {
      mostrarDenominacion(res.data);
    }
  })
  .catch((err) => console.log(err));

  const form = document.getElementById("edit_form");
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var factura = $('#factura').val();
    if(factura.length === 0){
      $('#errorFactura').append('<p class="text-danger">Ingrese los datos del documento</p>');
      setTimeout(() => {
        $('#errorFactura').empty();
      }, 5000);
    }else{
      Swal.fire({
        title: "Desea realizar un retiro?",
        text: "No podrás revertir esta operacion!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          const url = "recepcion_efectivo_retirov.php";
          fetch(url, {
            method: "POST",
            body: new FormData(form),
          })
          .then((res) => res.json())
          .then((res) => {
            if (res.ok) {
                //actualizarTabla(res);
              Swal.fire({
                title: "Operacion Satisfactoria!",
                html: "<p>" + res.ok + "</p>",
                confirmButtonText: "Ok",
                icon: "success",
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href =
                  "principal1.php?page=recepcion_efectivo_vuelto&mod=1";
                }
              });
            }
            if (res.error) {
              $("#observacion").val("");
              $("#monto").val("");
              $("#denom_1").val("");
              $("#denom_2").val("");
              $("#denom_3").val("");
              $("#denom_4").val("");
              $("#denom_5").val("");
              $("#denom_6").val("");
              $("#denom_7").val("");
              $("#denom_8").val("");
              $("#denom_9").val("");
              $("#denom_10").val("");
              $("#denom_11").val("");
              $("#denom_12").val("");
              $("#denom_13").val("");
              $("#denom_14").val("");
              $("#denom_15").val("");
              $("#denom_16").val("");
              $("#denom_17").val("");
              $("#denom_18").val("");
              $("#denom_19").val("");
              $("#denom_20").val("");
              $("#denom_21").val("");
              
              Swal.fire({
                title: "Error en la Operacion!",
                html: "<p>" + res.error + "</p>",
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                confirmButtonText: "Confirmar",
                icon: "info",
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href =
                  "principal1.php?page=recepcion_efectivo_vuelto&mod=1";
                } else {
                    //window.location.href = "principal1.php?page=recepcion_efectivo_vuelto&mod=1";
                }
              });
            }
            if (res.error1) {
              $("#observacion").val("");
              $("#monto").val("");
              $("#denom_1").val("");
              $("#denom_2").val("");
              $("#denom_3").val("");
              $("#denom_4").val("");
              $("#denom_5").val("");
              $("#denom_6").val("");
              $("#denom_7").val("");
              $("#denom_8").val("");
              $("#denom_9").val("");
              $("#denom_10").val("");
              $("#denom_11").val("");
              $("#denom_12").val("");
              $("#denom_13").val("");
              $("#denom_14").val("");
              $("#denom_15").val("");
              $("#denom_16").val("");
              $("#denom_17").val("");
              $("#denom_18").val("");
              $("#denom_19").val("");
              $("#denom_20").val("");
              $("#denom_21").val("");
              
              Swal.fire({
                title: "Error en la Operacion!",
                html: "<p>" + res.error1 + "</p>",
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                confirmButtonText: "Confirmar",
                icon: "info",
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href =
                  "principal1.php?page=recepcion_efectivo_vuelto&mod=1";
                }
              });
            }
            if (res.error2) {
              $("#observacion").val("");
              $("#monto").val("");
              $("#denom_1").val("");
              $("#denom_2").val("");
              $("#denom_3").val("");
              $("#denom_4").val("");
              $("#denom_5").val("");
              $("#denom_6").val("");
              $("#denom_7").val("");
              $("#denom_8").val("");
              $("#denom_9").val("");
              $("#denom_10").val("");
              $("#denom_11").val("");
              $("#denom_12").val("");
              $("#denom_13").val("");
              $("#denom_14").val("");
              $("#denom_15").val("");
              $("#denom_16").val("");
              $("#denom_17").val("");
              $("#denom_18").val("");
              $("#denom_19").val("");
              $("#denom_20").val("");
              $("#denom_21").val("");
              
              Swal.fire({
                title: "Error en la Operacion!",
                html: "<p>" + res.error2 + "</p>",
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                confirmButtonText: "Confirmar",
                icon: "info",
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href =
                  "principal1.php?page=recepcion_efectivo_vuelto&mod=1";
                  window.location.href =
                  "principal1.php?page=recepcion_efectivo_vuelto&mod=1";
                }
              });
            }
            if (res.error3) {
              $("#observacion").val("");
              $("#monto").val("");
              $("#denom_1").val("");
              $("#denom_2").val("");
              $("#denom_3").val("");
              $("#denom_4").val("");
              $("#denom_5").val("");
              $("#denom_6").val("");
              $("#denom_7").val("");
              $("#denom_8").val("");
              $("#denom_9").val("");
              $("#denom_10").val("");
              $("#denom_11").val("");
              $("#denom_12").val("");
              $("#denom_13").val("");
              $("#denom_14").val("");
              $("#denom_15").val("");
              $("#denom_16").val("");
              $("#denom_17").val("");
              $("#denom_18").val("");
              $("#denom_19").val("");
              $("#denom_20").val("");
              $("#denom_21").val("");
              
              Swal.fire({
                title: "Error en la Operacion!",
                html: "<p>" + res.error3 + "</p>",
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                confirmButtonText: "Confirmar",
                icon: "info",
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href =
                  "principal1.php?page=recepcion_efectivo_vuelto&mod=1";
                }
              });
            }
          });
}
});
}
});
}

function key() {
  $(function () {
    $("#monto").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_1").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_2").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_3").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_4").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_5").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_6").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_7").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_8").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_9").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_10").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_11").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_12").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_13").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_14").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_15").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_16").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_17").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_18").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_19").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_20").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
    $("#denom_21").keydown(function (event) {
      //alert(event.keyCode);
      if (
        (event.keyCode < 48 || event.keyCode > 57) &&
        (event.keyCode < 96 || event.keyCode > 105) &&
        event.keyCode !== 190 &&
        event.keyCode !== 110 &&
        event.keyCode !== 8 &&
        event.keyCode !== 9
        ) {
        return false;
    }
  });
  });
}

function mostrarDenominacion(data) {
  if (data[0].moneda == "Bolivares") {
    $(document).ready(function () {
      $("#form_bs").show();
      $("#tablaVuelto")
      .DataTable({
        data: data,
        columns: [
          { title: "Denominacion" },
          { title: "100 Bs" },
          { title: "50 Bs" },
          { title: "20 Bs" },
          { title: "10 Bs" },
          { title: "5 Bs" },
          { title: "1 Bs" },
          { title: "0.5 Bs" },
          ],
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        oLanguage: {
          oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "Siguiente",
            sPrevious: "Anterior",
          },
          sSearch: "Buscar:",
          sLengthMenu: "_MENU_ entradas por paginas",
          sZeroRecords: "Nada encontrado- lo sentimos",
          sInfo: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
          sInfoEmpty: "Mostrando 0 ta 0 de 0 entradas",
          sInfoFiltered: "(filtrado de _MAX_ entradas en total)",
        },
        searching: false,
        destroy: true,
      })
      .buttons()
      .container()
      .appendTo("#tablaVuelto_wrapper .col-md-6:eq(0)");
    });
  }
  if (data[0].moneda == "Dolares") {
    $(document).ready(function () {
      $("#form_dl").show();
      $("#tablaVuelto")
      .DataTable({
        data: data,
        columns: [
          { title: "Denominacion" },
          { title: "100 $" },
          { title: "50 $" },
          { title: "20 $" },
          { title: "10 $" },
          { title: "5 $" },
          { title: "2 $" },
          { title: "1 $" },
          ],
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        oLanguage: {
          oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "Siguiente",
            sPrevious: "Anterior",
          },
          sSearch: "Buscar:",
          sLengthMenu: "_MENU_ entradas por paginas",
          sZeroRecords: "Nada encontrado- lo sentimos",
          sInfo: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
          sInfoEmpty: "Mostrando 0 ta 0 de 0 entradas",
          sInfoFiltered: "(filtrado de _MAX_ entradas en total)",
        },
        searching: false,
        destroy: true,
      })
      .buttons()
      .container()
      .appendTo("#tablaVuelto_wrapper .col-md-6:eq(0)");
    });
  }
  if (data[0].moneda == "Euros") {
    $(document).ready(function () {
      $("#form_eu").show();
      $("#tablaVuelto")
      .DataTable({
        data: data,
        columns: [
          { title: "Denominacion" },
          { title: "500 €" },
          { title: "200 €" },
          { title: "100 €" },
          { title: "50 €" },
          { title: "20 €" },
          { title: "10 €" },
          { title: "5 €" },
          ],
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        oLanguage: {
          oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "Siguiente",
            sPrevious: "Anterior",
          },
          sSearch: "Buscar:",
          sLengthMenu: "_MENU_ entradas por paginas",
          sZeroRecords: "Nada encontrado- lo sentimos",
          sInfo: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
          sInfoEmpty: "Mostrando 0 ta 0 de 0 entradas",
          sInfoFiltered: "(filtrado de _MAX_ entradas en total)",
        },
        searching: false,
        destroy: true,
      })
      .buttons()
      .container()
      .appendTo("#tablaVuelto_wrapper .col-md-6:eq(0)");
    });
  }
}

function ocultar() {
  $(".modal-title").empty("h5");
  $("#observacion").val("");
  $("#monto").val("");
  $("#denom_1").val("");
  $("#denom_2").val("");
  $("#denom_3").val("");
  $("#denom_4").val("");
  $("#denom_5").val("");
  $("#denom_6").val("");
  $("#denom_7").val("");
  $("#denom_8").val("");
  $("#denom_9").val("");
  $("#denom_10").val("");
  $("#denom_11").val("");
  $("#denom_12").val("");
  $("#denom_13").val("");
  $("#denom_14").val("");
  $("#denom_15").val("");
  $("#denom_16").val("");
  $("#denom_17").val("");
  $("#denom_18").val("");
  $("#denom_19").val("");
  $("#denom_20").val("");
  $("#denom_21").val("");
}

$(".close").on("click", function () {
  $(".modal-title").empty("h5");
});

$(".modal").modal({
  backdrop: "static",
  keyboard: true,
  show: false,
});
