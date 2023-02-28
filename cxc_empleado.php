<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  function diasEntreFechas($fechainicio, $fechafin){
    return ((strtotime($fechafin)-strtotime($fechainicio))/86400);
  }

  $pto_ordaz = '00000';
  $sucursal = (isset($_GET['s'])) ? $_GET['s'] : $pto_ordaz;

  switch($sucursal) {
    case $pto_ordaz:     
    $facturas = mssql_query("
      SELECT a.CodSucu, a.CodClie codigocliente,b.Descrip razonsocial, a.FechaE emision, a.CodVend,a.numerod numerod, SUBSTRING(a.numerod,3,12) documento, a.TipoCxc tipodocumento, a.Factor, SUBSTRING(a.Document,1,3) compa,a.monto totalbs, a.saldo totald , a.Factorp factor from saacxc as a inner join SACLIE as b on a.CodClie=b.CodClie  where a.saldo > 0 and a.CodVend in ('1992','2992') and TipoCxc='10' order by a.CodSucu, a.fechae, a.CodClie desc");
    break;
    default:

  }

  $hoy = date("d-m-Y");
  ?>
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!--  <h2 id="title_permisos">Ultima Activacion Clientes</h2> -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
              <li class="breadcrumb-item active">CXC Empleados</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card card-saint">
            <div class="card-header">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h3 class="card-title">CXC Empleados</h3>
                </div>
              </div>
            </div>
            <form class="form-horizontal" action="principal1.php?page=despachos_ver&mod=1" method="post" id="" name="">
              <div class="card-body">
                <!-- <table id="example2" class="table table-sm table-bordered table-striped text-center" > -->
                  <table id="example9" class="table table-sm table-bordered table-hover">
                    <thead style="background-color: #00137f;color: white;">
                      <th width="36" height="22"><div align="center">Seleccionar</div></th>
                      <th width="36" height="22"><div align="center">Documento</div></th>
                      <th width="98"><div align="center">Emision</div></th>
                      <th width="69"><div align="center">Codigo</div></th>
                      <th width="189"><div align="center">Razon Social</div></th>
                      <th width="86"><div align="center">Dias</div></th>
                      <th width="62"><div align="center">Total Bs</div></th>
                      <th width="62"><div align="center">Factor Doc</div></th>
                      <th width="62"><div align="center">Total $</div></th>
                      <th width="46"><div align="center">Vendedor</div></th>
                    </thead>
                    <tbody>
                      <?php
                      $suma_bulto = 0;
                      $suma_paq = 0;
                      $suma_monto = 0;
                      $porcent = 0;
                      for($i=0;$i<mssql_num_rows($facturas);$i++){
                        if ($sindes != 0){
                          if (round(diasEntreFechas(date("d-m-Y", strtotime(mssql_result($facturas,$i,"emision"))),date("d-m-Y", strtotime(mssql_result($facturas,$i,"fechad"))))) != 0){
                            $calcula = (2 / round(diasEntreFechas(date("d-m-Y", strtotime(mssql_result($facturas,$i,"emision"))),date("d-m-Y", strtotime(mssql_result($facturas,$i,"fechad"))))))*100;
                          }else{ $calcula = 0;}
                          if ($calcula > 100){
                            $calcula = 100;
                          }
                          $porcent = $porcent + $calcula;
                        }
                        ?>
                        <tr <?php if (($cont % 2) != 0){ ?>bgcolor="#CCCCCC"<?php } ?> >
                          <td>
                            <div align="center">
                              <input type="checkbox" class="form-check-input" id="exampleCheck1" name="check_lista[]" value="<?php echo mssql_result($facturas,$i,"numerod").",".mssql_result($facturas,$i,"tipodocumento"); ?>">
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <a href="javascript:;" onclick="ver_fact('<?= mssql_result($facturas,$i,"documento"); ?>','<?= mssql_result($facturas,$i,"tipodocumento"); ?>','<?= mssql_result($facturas,$i,"codigocliente"); ?>','<?= mssql_result($facturas,$i,"razonsocial"); ?>')"><?php echo mssql_result($facturas,$i,"numerod"); ?></a>
                            </br>
                            <label for="tipo_fact" class="col-form-label-sm" >
                              <?php 
                              if (mssql_result($facturas,$i,"compa") == 'Not') {
                                echo "Nota de Entrega";
                              } else { echo "Factura";} ?></label>
                            </div>
                          </td>
                          <td>
                            <div align="center"><?php echo date("d/m/Y", strtotime(mssql_result($facturas,$i,"emision"))); ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo mssql_result($facturas,$i,"codigocliente"); ?></div>
                          </td>
                          <td><?php echo utf8_encode(mssql_result($facturas,$i,"razonsocial")); ?></td>
                          <td>
                            <div align="center"><?php echo round(diasEntreFechas(date("d-m-Y", strtotime(mssql_result($facturas,$i,"emision"))),$hoy)); ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo rdecimal2(mssql_result($facturas,$i,"totalbs")); ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo rdecimal2(mssql_result($facturas,$i,"factor")); ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo rdecimal2(mssql_result($facturas,$i,"totald")/mssql_result($facturas,$i,"factor"));  ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo mssql_result($facturas,$i,"codvend"); ?></div>
                          </td>
                        </tr>
                        <?php
                        $cont++;
                      }?>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer">
                  <input type="hidden" id="usuario" name="usuario" value="<?php echo  $_SESSION['login']; ?>">
                  <button type="submit" name="Submit" class="btn btn-saint float-right">Procesar</button>
                  <button type="button"  class="btn btn-outline-saint float-left">Regresar</button>
                </div> 
              </form>
            </div>
          </div>
        </div>
      </section>
    </div>
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
        let tipo = (tipofac==='C') ? "" : "";
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
  <?php
} else {
  header('Location: index.php');
}
?>