<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {

  $fechai = $_POST['fechai'];
// $fechai = normalize_date2($fechai);
  $fechaf = $_POST['fechaf'];
  $sucursal = $_POST['sucursal'];
// $fechaf = normalize_date2($fechaf);
//$convend = $_POST['edv'];
  switch (true) {
    # =================================================a
    # === UN TIPO DE SUCURSAL
    # =================================================
    case ($sucursal!="-" ):
    $query = mssql_query("SELECT a.fechae, a.NumeroD, a.TipoFac,  a.CodClie, a.Descrip, b.clase, a.MtoTotal, a.factorp, a.MtoTotal/a.FactorP as totald, a.codvend,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='C' and EsUnid = '0') as cajas,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='C' and EsUnid = '1') as unidades
      from SAFACT as a left join SACLIE as b on a.CodClie=b.CodClie inner join saitemfac as c on a.numerod=c.numerod and a.tipofac=c.tipofac 
      where a.TipoFac in ('C') and  a.numeror is null and   DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf' and a.CodSucu = '$sucursal'   group by a.numerod, a.fechae, a.TipoFac, a.CodClie, a.Descrip, b.Clase, a.MtoTotal, a.FactorP,a.CodVend order by fechae desc");
    break;

    # =============================================================
    # === todas las sucursales
    # =============================================================
    case ($sucursal="-" ):
    $query = mssql_query("SELECT a.fechae, a.NumeroD, a.TipoFac,  a.CodClie, a.Descrip, b.clase, a.MtoTotal, a.factorp, a.MtoTotal/a.FactorP as totald, a.codvend,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='C' and EsUnid = '0') as cajas,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='C' and EsUnid = '1') as unidades
      from SAFACT as a left join SACLIE as b on a.CodClie=b.CodClie inner join saitemfac as c on a.numerod=c.numerod and a.tipofac=c.tipofac 
      where a.TipoFac in ('C') and  a.numeror is null and    DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf' group by a.numerod, a.fechae, a.TipoFac, a.CodClie, a.Descrip, b.Clase, a.MtoTotal, a.FactorP,a.CodVend ");
    break;

    default:
    $query = mssql_query("SELECT a.fechae, a.NumeroD, a.TipoFac,  a.CodClie, a.Descrip, b.clase, a.MtoTotal, a.factorp, a.MtoTotal/a.FactorP as totald, a.codvend,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='C' and EsUnid = '0') as cajas,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='C' and EsUnid = '1') as unidades
      from SAFACT as a left join SACLIE as b on a.CodClie=b.CodClie inner join saitemfac as c on a.numerod=c.numerod and a.tipofac=c.tipofac 
      where a.TipoFac in ('C') and  a.numeror is null and    DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf'   group by a.numerod, a.fechae, a.TipoFac, a.CodClie, a.Descrip, b.Clase, a.MtoTotal, a.FactorP,a.CodVend order by fechae desc
      ");
    break;
  }
  ?>
  <div class="content-wrapper">
    <!-- BOX DE LA MIGA DE PAN -->
    <section class="content-header">
      <div class="container-fluid">
<!--      <div class="row mb-2">
<div class="col-sm-6">
<h2 id="title_permisos">Ultima Activacion Clientes</h2>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
<li class="breadcrumb-item active">Ultima Activacion Clientes</li>
</ol>
</div>
</div> -->
</div>
</section>

<!-- BOX DEL CONTENIDO DE LA VISTA FORMULARIO Y TABLA -->
<section class="content">
  <div class="row">
    <div class="col-12">
      <div class="card card-saint">
        <div class="card-header">
          <script type="text/javascript">
            function regresa(){
              window.location.href = "principal1.php?page=resumen_ne&mod=1";
            }
          </script>
          <h3 class="card-title">Resumen de Notas de Entrega</h3>&nbsp;&nbsp;&nbsp;
          <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
        </div>
        <div class="card-body">
          <?php 
          $num = mssql_num_rows($query); 
          ?>
          <!--  <table id="example2" class="table table-bordered table-hover"> -->
            <table id="example1" class="table table-sm table-bordered table-striped text-center" >
              <thead style="background-color: #00137f;color: white;">
                <tr>
                  <th>Fecha</th>
                  <th>Documentos</th>
                  <th>Codigo Cliente</th>
                  <th>Razon Social</th>
                  <th>Clase</th>
                  <th>Cajas</th>
                  <th>Unidades</th>
                  <th>Total Bs</th>
                  <th>Factor</th>
                  <th>Total $</th>
                  <th>Codigo Vendedor</th>
                </tr>
              </thead>
              <tbody>
                <?php for ($i = 0; $i < mssql_num_rows($query); $i++) {
                  ?>
                  <tr>
                    <td><?php echo date("d/m/Y", strtotime(mssql_result($query,$i,"fechae"))); ?></td>
                    <td>  
                     <div align="center">
                      <a href="javascript:;" onclick="ver_fact('<?= mssql_result($query,$i,"NumeroD"); ?>','<?= mssql_result($query,$i,"tipofac"); ?>','<?= mssql_result($query,$i,"CodClie"); ?>','<?= mssql_result($query,$i,"Descrip"); ?>')"><?php echo mssql_result($query,$i,"numerod"); ?></a>
                    </br>
                    <label for="tipo_fact" class="col-form-label-sm" >
                      <?php 
                      if (mssql_result($query,$i,"tipofac") == 'C') {
                        echo "Nota de Entrega";
                      } else { echo "Devolucion NE";} ?></label>
                    </div>
                  </td>
                  <td><?php echo mssql_result($query, $i, "codclie"); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $i, "Descrip")); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $i, "clase")); ?></td>
                  <td><?php echo rdecimal2(mssql_result($query, $i, "cajas")); ?></td>
                  <td><?php echo rdecimal2(mssql_result($query, $i, "unidades")); ?></td>
                  <td><?php echo rdecimal2(mssql_result($query,$i,"MtoTotal"));?></td>
                  <td><?php echo rdecimal2(mssql_result($query,$i,"factorp"));?></td>
                  <td><?php echo rdecimal2(mssql_result($query,$i,"totald"));?></td>
                  <td><?php echo mssql_result($query, $i, "codvend"); ?></td>
                </tr>
              <?php }?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</section>


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
      let tipo = (tipofac==='C') ? " (Nota de Entrega)" : " (Devolucion NE)";
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
            url: 'detalle_ne.php',
            type: "post",
            dataType: "json",
            data: {documento_id: numerod},
            error: function (e) {
              ///console.log(e.responseText);
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
<?php
} else {
  header('Location: index.php');
}
?>