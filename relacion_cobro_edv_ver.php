<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  function diasEntreFechas($fechainicio, $fechafin){
    return ((strtotime($fechafin)-strtotime($fechainicio))/86400);
  }
  $sucursal = $_POST['sucursal'];
  $convend = $_POST['edv'];

  $query = mssql_query("SELECT numerod, TipoFac, CodClie, Descrip, fechae, FechaV, MtoTotal/FactorP as total, CodVend, CodSucu 
  from SAFACT where NumeroD in (select numeros from appfacturas_det where TipoFac in ('A','C') ) 
  AND NumeroD NOT IN (SELECT numerod FROM app_relacion_cobros_items WHERE vendedor='$convend' AND codsucu='$sucursal')
  AND codsucu ='$sucursal' and TipoFac in ('A','C') and CodVend =  '$convend'");


  $hoy = date("d-m-Y");
  ?>
  <div class="content-wrapper">
    <!-- BOX DE LA MIGA DE PAN -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!--  <h2 id="title_permisos">Ultima Activacion Clientes</h2> -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
              <li class="breadcrumb-item active">Relación Cobro</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- BOX DEL CONTENIDO DE LA VISTA FORMULARIO Y TABLA -->
    <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card card-saint">
              <div class="card-header">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h3 class="card-title">Relación de Documentos para Cobro</h3>
                    &nbsp;&nbsp;&nbsp;
                    <a href="principal.php?page=despacho_relacion&mod=1"></a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="principal.php?page=vehiculos&mod=1"></a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="principal.php?page=choferes&mod=1"></a>
                  </div>
                <div class="col-sm-6 text-right"></div>
              </div>
            </div>

            <form class="form-horizontal" action="principal1.php?page=relacion_cobro_edv_procesa&mod=1" method="post" id="" name="">
              <input type="hidden" id="sucu" name="sucu" value="<?php echo  $sucursal; ?>">
              <input type="hidden" id="vend" name="vend" value="<?php echo  $convend; ?>">
              <div class="card-body">
                <table id="example3" class="table table-bordered table-hover table-striped"> 
                  <thead style="background-color: #00137f;color: white;">
                    <tr class="text-center">
                      <th>Seleccionar</th>
                      <th>Documento</th>
                      <th>Código Cliente</th>
                      <th>Razón Social</th>
                      <th>Emisión</th>
                      <th>Vencimiento</th>
                      <th>Monto</th>
                      <th>Vendedor</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    for ($i=0; $i<mssql_num_rows($query); $i++) {                         
                      ?>

                      <tr>
                        <td class="text-center">
                          <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="check_lista[]" value="<?php echo mssql_result($query,$i,"numerod").",".mssql_result($query,$i,"tipofac"); ?>">
                            <label class="form-check-label"></label>
                          </div>
                        </td>
                        <td class="text-center">
                            <a href="javascript:;" onclick="ver_fact('<?= mssql_result($query,$i,"numerod"); ?>','<?= mssql_result($query,$i,"tipofac"); ?>','<?= mssql_result($query,$i,"codclie"); ?>','<?= mssql_result($query,$i,"descrip"); ?>')"><?php echo mssql_result($query,$i,"numerod"); ?></a>
                            </br>
                            <label for="tipo_fact" class="col-form-label-sm" >
                              <?php 
                              if (mssql_result($query,$i,"tipofac") == 'C') {
                                echo "Nota de Entrega";
                              } else { 
                                echo "Factura";
                              } 
                              ?>
                            </label>
                        </td>
                        <td class="text-center"> <?php echo mssql_result($query,$i,"codclie"); ?> </td>
                        <td class="text-center"> <?php echo utf8_encode(mssql_result($query,$i,"descrip")); ?> </td>
                        <td class="text-center"> <?php echo date("d/m/Y", strtotime(mssql_result($query,$i,"fechae"))); ?> </td>
                        <td class="text-center"> <?php echo date("d/m/Y", strtotime(mssql_result($query,$i,"fechav"))); ?> </td>
                        <td class="text-center"> <?php echo rdecimal2(mssql_result($query,$i,"total")); ?> </td>
                        <td class="text-center"> <?php echo mssql_result($query,$i,"codvend"); ?> </td>
                      </tr>
                      <?php
                      $cont++;
                    } ?>
                  </tbody>
                </table>
              </div>
              <div class="card-footer">
                <input type="hidden" id="usuario" name="usuario" value="<?php echo  $_SESSION['login']; ?>">
                <button type="submit" name="Submit" class="btn btn-saint">Procesar</button>
                <button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
              </div> 
            </form>
          </div>
        </div>
    </section>
  </div>

  <!-- MODAL  DETALLE DE FACTURA -->
  <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title">Detalles de Documento: &nbsp;&nbsp <span id="numerod"></span> </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row pl-2">
            <p>
              <strong>CODIGO:</strong>&nbsp;&nbsp; <span id="codclient"></span>
            </p>
          </div>
          <div class="row pl-2">
            <p>
              <strong>RAZON SOCIAL:</strong>&nbsp;&nbsp; <span id="descrip"></span>
            </p>
          </div>
          <div class="row">
            <div class="col-12">
              <table id="detalle_data" class="table table-sm table-bordered table-striped text-center">
                <thead style="background-color: #00137f;color: white;">
                  <tr>
                    <th class="small align-middle">Código</th>
                    <th class="small align-middle">Descripción</th>
                    <th class="small align-middle">Depósito</th>
                    <th class="small align-middle">Cantidad</th>
                    <th class="small align-middle">Precio</th>
                    <th class="small align-middle">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- TD de la tabla que se pasa por ajax -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

    </div>
    <?php include "footer.php"; ?>
    <script src="Icons.js" type="text/javascript"></script>
    <script type="text/javascript">
      var tabla1;
      function ver_fact(numerod= -1, tipofac, codclie, descrip) {
        $('#detalleModal').modal('show');
    //si es -1 el modal es crear usuario nuevo
    if(numerod !== -1)
    {
      let tipo = (tipofac==='C') ? " (Nota de Entrega)" : " (Factura)";
      $('#numerod').text(numerod + tipo);
      $('#codclient').text(codclie);
      $('#descrip').text(descrip);
      if(tabla1 instanceof $.fn.dataTable.Api){
        $('#detalle_data').DataTable().clear().destroy();
      }
      tabla1 = $('#detalle_data').dataTable({
          "aProcessing": true,//Activamos el procesamiento del datatables
          "aServerSide": true,//Paginación y filtrado realizados por el servidor
          "ajax":
          {
            url: 'detalle_factura.php',
            type: "post",
            dataType: "json",
            data: {documento_id: numerod},
            error: function (e) {
              console.log(e.responseText);
            },
            complete: function () {
                      // elimina el error de compatibilidad quitando el with
                      $("#detalle_data").css({'width':''});
                    }
                  },
                  "bDestroy": true,
                  "responsive": true,
                  "bInfo": true,
          "iDisplayLength": 10,//Por cada 10 registros hace una paginación
          "order": [[0, "asc"]],//Ordenar (columna,orden)
          'columnDefs':[{
            "targets": 1,
            "className": "text-left"
          }],
          "language": texto_español_datatables
        }).DataTable();
    }
  }
  </script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#sucursal').change(() => {
        window.location = "principal1.php?page=<?php echo $_GET['page']; ?>&mod=1&s="+$('#sucursal').val();
      });
    });
  </script>
  <script type="text/javascript">
      function guarda(){
          if (window.confirm("¿Estas seguro de "+$('#codbanc').text()+" es el banco para el reporte seleccionado?")){
              /* document.forms["registro_usuarios"].submit();*/
          }
      }
      function regresa(){
          window.location.href = "principal1.php?page=relacion_cobro_edv&mod=1";
      }
  </script>
  <?php
} else {
  header('Location: index.php');
}
?>