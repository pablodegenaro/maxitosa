<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  function diasEntreFechas($fechainicio, $fechafin){
    return ((strtotime($fechafin)-strtotime($fechainicio))/86400);
  }

  $pto_ordaz = '00000';
  $maturin = '00001';
  $carupano = '00002';

  $sucursal = (isset($_GET['s'])) ? $_GET['s'] : $pto_ordaz;

  switch($sucursal) {

  # ====================
  # === PUERTO ORDAZ === 
  # ====================
    case $pto_ordaz: 
    
    $facturas = mssql_query("
      SELECT a.numerod as documento,a.TipoFac as tipodocumento, a.fechae as emision, a.CodClie codigocliente, a.Descrip as razonsocial,c.Descrip AS zona, 
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='F' and EsUnid = '0') as bultos,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='F' and EsUnid = '1') as paquetes,
      MtoTotal/FactorP as totald,a.codvend
      FROM 
      SAFACT AS A left join SACLIE as B on a.CodClie=b.CodClie 
      left join SAZONA as c on b.CodZona=c.CodZona 
      where  A.TipoFac ='F' and A.NumeroR is null  and A.NumeroD not in (SELECT numeros FROM appfacturas_detft)  and CodSucu = '$pto_ordaz'");

    break;
    

  # ================
  # === MATURIN ==== 
  # ================
    case $maturin: 

    $facturas = mssql_query("
      SELECT a.numerod as documento,a.TipoFac as tipodocumento, a.fechae as emision, a.CodClie codigocliente, a.Descrip as razonsocial,c.Descrip AS zona, 
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='F' and EsUnid = '0') as bultos,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='F' and EsUnid = '1') as paquetes,
      MtoTotal/FactorP as totald,a.codvend
      FROM 
      SAFACT AS A left join SACLIE as B on a.CodClie=b.CodClie 
      left join SAZONA as c on b.CodZona=c.CodZona 
      where  A.TipoFac ='F' and A.NumeroR is null and A.NumeroD not in (SELECT numeros FROM appfacturas_detft)  and CodSucu = '$maturin'");

    break;


    # ====================
  # === CARUPANO === 
  # ====================
    case $carupano: 

    $facturas = mssql_query("
      SELECT a.numerod as documento,a.TipoFac as tipodocumento, a.fechae as emision, a.CodClie codigocliente, a.Descrip as razonsocial,c.Descrip AS zona, 
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='F' and EsUnid = '0') as bultos,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='F' and EsUnid = '1') as paquetes,
      MtoTotal/FactorP as totald,a.codvend
      FROM 
      SAFACT AS A left join SACLIE as B on a.CodClie=b.CodClie 
      left join SAZONA as c on b.CodZona=c.CodZona 
      where  A.TipoFac ='F' and A.NumeroR is null  and A.NumeroD not in (SELECT numeros FROM appfacturas_detft)  and CodSucu = '$carupano'");

    break;


  # =============================
  # === TODAS LAS SUCURSALEs ==== 
  # =============================
    default:
    $facturas = mssql_query("
      SELECT a.numerod as documento,a.TipoFac as tipodocumento, a.fechae as emision, a.CodClie codigocliente, a.Descrip as razonsocial,c.Descrip AS zona, 
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='F' and EsUnid = '0') as bultos,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='F' and EsUnid = '1') as paquetes,
      MtoTotal/FactorP as totald, a.codvend
      FROM 
      SAFACT AS A left join SACLIE as B on a.CodClie=b.CodClie 
      left join SAZONA as c on b.CodZona=c.CodZona 
      where  A.TipoFac ='F' and (A.NumeroR is null or A.NumeroR in (select x.NumeroD from SAFACT as x where cast(x.Monto as int)<cast(A.Monto as int) and X.TipoFac  ='B'
        and x.NumeroD=A.NumeroR)) and A.NumeroD not in (SELECT numeros FROM appfacturas_detft)");
  }


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
              <li class="breadcrumb-item active">Despachos</li>
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
                  <h3 class="card-title">Despachos</h3>
                  &nbsp;&nbsp;&nbsp;
                  <a href="principal.php?page=despacho_relacionft&mod=1">Relacion de Despachos FT</a>
                  &nbsp;&nbsp;&nbsp;
                  <a href="principal.php?page=vehiculos&mod=1"></a>
                  &nbsp;&nbsp;&nbsp;
                  <a href="principal.php?page=choferes&mod=1"></a>
                </div>
                <div class="col-sm-6 text-right">
                  <select class="form-control custom-select" name="sucursal" style="width: auto;" id="sucursal">
                    <option value="-" <?php if($_GET['s']=='-' || !isset($_GET['s'])) {echo 'selected';} ?>>TODAS LAS SUCURSALES</option>
                    <?php
                    $sucur= mssql_query("SELECT CodSucu, Descrip from SASUCURSAL");
                    for($i=0;$i<mssql_num_rows($sucur);$i++){
                      ?>
                      <option value="<?= mssql_result($sucur,$i,"CodSucu"); ?>" <?php if($_GET['s']==mssql_result($sucur,$i,"CodSucu")) {echo 'selected';} ?>>
                        SUCURSAL <?= mssql_result($sucur,$i,"Descrip"); ?>
                      </option>
                      <?php
                    } ?>
                  </select>
                </div>
              </div>

            </div>
            <form class="form-horizontal" action="principal1.php?page=despachos_verft&mod=1" method="post" id="" name="">
              <div class="card-body">
                <div class="form-group">
                  <label>Fecha Despacho</label>
                  <div class="input-group date" id="fechadesp" data-target-input="nearest">
                    <input type="text" name="fechadespacho" id="fechadespacho" class="form-control datetimepicker-input" data-target="#fechadesp"/>
                    <div class="input-group-append" data-target="#fechadesp" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Choferes</label>
                  <select class="form-control custom-select" name="chofer" id="chofer" style="width: 100%;" required>
                    <option value="">Seleccione un Chofer</option>
                    <?php 
                    $choferes= mssql_query("SELECT * from appChofer where estatus = '1'");
                    if (mssql_num_rows($choferes) != 0){ 
                      for($i=0;$i<mssql_num_rows($choferes);$i++){
                        ?>                         
                        <option value="<?php echo mssql_result($choferes,$i,"cedula"); ?>"><?php echo mssql_result($choferes,$i,"cedula"); ?>: <?php echo substr(mssql_result($choferes,$i,"descripcion"), 0, 35); ?></option>
                        <?php 
                      }
                    } ?>
                  </select>
                </div> 
                <div class="form-group">
                  <label>Vehiculos</label>
                  <select class="form-control custom-select" name="vehiculo" id="vehiculo" style="width: 100%;" required>
                    <option value="">Seleccione un Vehiculo</option>
                    <?php 
                    $vehiculo= mssql_query("SELECT * from appVehiculo");
                    if (mssql_num_rows($vehiculo) != 0){ 
                      for($i=0;$i<mssql_num_rows($vehiculo);$i++){
                        ?>                         
                        <option value="<?php echo mssql_result($vehiculo,$i,"placa"); ?>"><?php echo mssql_result($vehiculo,$i,"placa"); ?>: <?php echo substr(mssql_result($vehiculo,$i,"modelo"), 0, 35); ?></option>
                        <?php 
                      }
                    } ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Zona Despacho</label>
                  <select class="form-control custom-select" name="destino" id="destino" style="width: 100%;" required>
                    <option value="">Seleccione una Zona</option>
                    <?php 
                    $destino= mssql_query("SELECT CodZona, Descrip from SAZONA where Activo='1' ");
                    if (mssql_num_rows($destino) != 0){ 
                      for($i=0;$i<mssql_num_rows($destino);$i++){
                        ?>                         
                        <option value="<?php echo mssql_result($destino,$i,"descrip"); ?>"><?php echo substr(utf8_encode(mssql_result($destino,$i,"descrip")), 0, 35); ?></option>
                        <?php 
                      }
                    } ?>
                  </select>
                </div> 
                <table id="example10" class="table table-sm table-bordered table-striped text-center" >
                  <thead style="background-color: #00137f;color: white;">
                    <th width="36" height="22"><div align="center">Seleccionar</div></th>
                    <th width="36" height="22"><div align="center"><strong>Documento</div></th>
                      <th width="98"><div align="center">Emision</div></th>
                      <th width="69"><div align="center">Codigo</div></th>
                      <th width="69"><div align="center">Zona</div></th>
                      <th width="189"><div align="center">Razon Social</div></th>
                      <th width="86"><div align="center">Dias</div></th>
                      <th width="53"><div align="center">Bultos</div></th>
                      <th width="53"><div align="center">BOT / UND</div></th>
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
                              <input type="checkbox" class="form-check-input" id="exampleCheck1" name="check_lista[]" value="<?php echo mssql_result($facturas,$i,"documento").",".mssql_result($facturas,$i,"tipodocumento"); ?>">
                            </div>
                          </td>
                          <td>
                            <div align="center">
                              <a href="javascript:;" onclick="ver_fact('<?= mssql_result($facturas,$i,"documento"); ?>','<?= mssql_result($facturas,$i,"tipodocumento"); ?>','<?= mssql_result($facturas,$i,"codigocliente"); ?>','<?= mssql_result($facturas,$i,"razonsocial"); ?>')"><?php echo mssql_result($facturas,$i,"documento"); ?></a>
                            </br>
                            <label for="tipo_fact" class="col-form-label-sm" >
                              <?php 
                              if (mssql_result($facturas,$i,"tipodocumento") == 'C') {
                                echo "Nota de Entrega";
                              } else { echo "Factura FT";} ?></label>
                            </div>
                          </td>
                          <td>
                            <div align="center"><?php echo date("d/m/Y", strtotime(mssql_result($facturas,$i,"emision"))); ?></div>
                          </td>
                          <?php if ($sindes != 0){ ?>
                            <td>
                              <div align="center"><?php echo date("d/m/Y", strtotime(mssql_result($facturas,$i,"fechad"))); ?></div>
                            </td>
                            <td>
                              <div align="center"><?php echo round(diasEntreFechas(date("d-m-Y", strtotime(mssql_result($facturas,$i,"emision"))),date("d-m-Y", strtotime(mssql_result($facturas,$i,"emision"))))); ?></div>
                            </td>
                          <?php } ?>
                          <td>
                            <div align="center"><?php echo mssql_result($facturas,$i,"codigocliente"); ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo mssql_result($facturas,$i,"zona"); ?></div>
                          </td>
                          <td><?php echo utf8_encode(mssql_result($facturas,$i,"razonsocial")); ?></td>
                          <td>
                            <div align="center"><?php echo round(diasEntreFechas(date("d-m-Y", strtotime(mssql_result($facturas,$i,"emision"))),$hoy)); ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo round(mssql_result($facturas,$i,"bultos")); ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo round(mssql_result($facturas,$i,"paquetes")); ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo rdecimal2(mssql_result($facturas,$i,"totald")); $suma_monto = $suma_monto + mssql_result($facturas,$i,"totald"); ?></div>
                          </td>
                          <td>
                            <div align="center"><?php echo mssql_result($facturas,$i,"codvend"); ?></div>
                          </td>
                          <?php if ($sindes != 0){ ?>
                            <td>
                              <div align="center"><?php echo 2; ?></div>
                            </td>
                            <td>
                              <div align="center"><?php echo rdecimal2($calcula);?>%</div>
                            </td>
                            <?php 
                          } ?>
                        </tr>
                        <?php
                        $cont++;
                      }?>
                    </tbody>
                  </table>
                  <div>  
                    Documentos por despachar: <?php  
                    if (is_null($cont)) {
                      echo '0';
                    }else { echo $cont; } ?>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" id="usuario" name="usuario" value="<?php echo  $_SESSION['login']; ?>">
                  <button type="submit" name="Submit" class="btn btn-saint">Procesar</button>
                  <button type="button"  class="btn btn-outline-saint float-right">Regresar</button>
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
            url: 'detalle_facturaft.php',
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