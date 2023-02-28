<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  set_time_limit(0);
  $fechai = $_POST['fechai'].' 00:00:00';
  $fechaf = $_POST['fechaf'].' 23:59:59';
  $sucursal = '00000';
  $fechaii = normalize_date($_POST['fechai']);
  $fechaff = normalize_date($_POST['fechaf']);

  ?>
  <div class="content-header">
    <div class="container">
    </div>
  </div>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Libro de Compras PZO</h1>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-3 mt-4 form-check-inline">
          </div>
          <div class="col-sm-3 mt-4 form-check-inline">
            <dt class="col-sm-3 text-gray">Desde:</dt>
            <input type="text" class="form-control-sm col-8 text-center" id="fechai" value="<?php echo $fechaii; ?>" readonly>
          </div>
          <div class="col-sm-3 mt-4 form-check-inline">
            <dt class="col-sm-4 text-gray">Hasta:</dt>
            <input type="text" class="form-control-sm col-sm-8 text-center" id="fechaf" value="<?php echo $fechaff; ?>" readonly>&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<script type="text/javascript">
              function regresa(){
                window.location.href = "principal1.php?page=libro_compras_pzo&mod=1";
              }
            </script>

            <button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
          </div>
        </div>
      </div>
    </div>
    <div class="content">
      <?php   $resumen1 = mssql_query("SELECT * FROM DBO.VW_ADM_LIBROIVACOMPRAS WHERE ('$fechai'<=FECHATRAN) AND (FECHATRAN<='$fechaf') AND CODSUCU = '$sucursal' ORDER BY (YEAR(FechaCompra)*10000)+(MONTH(FechaCompra)*100)+DAY(FechaCompra),FECHAT ");
      $num = mssql_num_rows($resumen1); ?>
      <table id="example1" class="table table-sm text-center table-condensed table-bordered table-striped" style="width:100%;">


        <thead  style="background-color: #00137f;color: white;">

          <tr id="cells">
            <th align="center">Nro. Ope</th>
            <th align="center" width="100">Fecha Documento</th>
            <th align="center" width="100">Rif</th>
            <th align="center" width="200">Nombre o Razón Social</th>
            <th align="center" width="50">Tip. Doc.</th>
            <th align="center" width="100">Nro. Comprobante Retención</th>
            <th align="center" width="100">Nro. Documento</th>
            <th align="center" width="100">Nro. Control</th>
            <th align="center" width="100">Tipo Tran.</th>
            <th align="center" width="100">Nro Fac. Afectada</th>
            <th align="center" width="200">Total Compras</th>
            <th align="center" width="100">Compras Exentas</th>
            <th align="center" width="150">Base Imponible</th>
            <th align="center" width="50">% Alic</th>
            <th align="center" width="150">Monto IVA</th>
            <th align="center" width="100">Monto Retenido</th>
            <th align="center" width="50">% Ret</th>
            <th align="center" width="100">Fecha Comprobante</th>
          </tr>
        </thead>
        <tbody style="background-color: aliceblue">
          <?php
          $tcci = $mtoex = $totcom = $mtoiva = $retiva = 0;
          for($i=0;$i<$num;$i++){
            $k = $i+1;
            $numerodoc = mssql_result($resumen1, $i, 'numerodoc');   
            ?>
            <tr >
              <td><?php echo $k; ?></td>
              <td ><?php echo date('d/m/Y', strtotime(mssql_result($resumen1, $i, 'fechacompra'))); ?></td>
              <td><?php echo mssql_result($resumen1, $i, 'id3ex'); ?></td>
              <td><?php echo utf8_encode(mssql_result($resumen1, $i, 'descripex')); ?></td>
              <td ><?php echo mssql_result($resumen1, $i, 'tipodoc'); ?></td>
              <td><?php echo mssql_result($resumen1, $i, 'nroretencion'); ?></td>
              <td><?php echo mssql_result($resumen1, $i, 'numerodoc'); ?></td>
              <td><?php echo mssql_result($resumen1, $i, 'nroctrol'); ?></td>
              <td ><?php echo mssql_result($resumen1, $i, 'tiporeg'); ?></td>
              <td ><?php echo mssql_result($resumen1, $i, 'docafectado'); ?></td>
              <td ><?php echo number_format(mssql_result($resumen1, $i, 'totalcompraconiva'), 2, ',', '.'); $tcci += mssql_result($resumen1, $i, 'totalcompraconiva'); ?></td>
              <td ><?php echo number_format(mssql_result($resumen1, $i, 'mtoexento'), 2, ',', '.'); $mtoex += mssql_result($resumen1, $i, 'mtoexento'); ?></td>
              <td ><?php echo number_format(mssql_result($resumen1, $i, 'totalcompra'), 2, ',', '.'); $totcom += mssql_result($resumen1, $i, 'totalcompra'); ?></td>
              <td ><?php echo number_format(mssql_result($resumen1, $i, 'alicuota_iva'), 0, ',', '.'); ?>%</td>
              <td ><?php echo number_format(mssql_result($resumen1, $i, 'monto_iva'), 2, ',', '.'); $mtoiva += mssql_result($resumen1, $i, 'monto_iva'); ?></td>
              <td ><?php echo number_format(mssql_result($resumen1, $i, 'retencioniva'), 2, ',', '.'); $retiva += mssql_result($resumen1, $i, 'retencioniva'); ?></td>
              <td ><?php echo number_format(mssql_result($resumen1, $i, 'porctreten'), 0, ',', '.'); ?>%</td>
              <?php
              if (mssql_result($resumen1, $i, 'fecharetencion')) {
                ?>
                <td align="center"><?php echo date('d/m/Y', strtotime(mssql_result($resumen1, $i, 'fecharetencion'))); ?></td>
                <?php
              }else{
                ?>
                <td></td>
                <?php
              }
              ?>
            </tr>
            <?php
          }
          ?>
          <tr>
            <td colspan="10" align="right"><strong>Totales</strong></td>
            <td align="right"><?php echo number_format($tcci, 2, ',', '.'); ?></td>
            <td align="right"><?php echo number_format($mtoex, 2, ',', '.'); ?></td>
            <td align="right"><?php echo number_format($totcom, 2, ',', '.'); ?></td>
            <td></td>
            <td align="right"><?php echo number_format($mtoiva, 2, ',', '.'); ?></td>
            <td align="right"><?php echo number_format($retiva, 2, ',', '.'); ?></td>
          </tr>
        </tbody>
      </table>
      <hr>
      <table id="tabla1" class="table table-sm text-center table-condensed table-bordered table-striped" style="width:40%;">
        <thead  style="background-color: #00137f;color: white;">
          <tr id="cells">
            <th width="500"><strong>RESUMEN DE CRÉDITOS FISCALES</strong></th>
            <th width="100"><strong>BASE IMPONIBLE</strong></th>
            <th width="100"><strong>CRÉDITO FISCAL</strong></th>
          </tr>
        </thead>
        <tbody style="background-color: aliceblue">
          <tr>
            <td>Total Compras Exentas y/o sin derecho a crédito Fiscal</td>
            <td align="right"><?php echo number_format($mtoex, 2, ',', '.'); ?></td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
          </tr>
          <tr>
            <td>Total Compras Importación Afectas solo Alícuota General</td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
          </tr>
          <tr>
            <td>Total Compras Importación Afectas en Alícuota General + Adicional</td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
          </tr>
          <tr>
            <td>Total Compras Importación Afectas en Alícuota Reducida</td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
          </tr>
          <tr>
            <td>Total Compras Internas Afectas solo Alícuota General (16%): </td>
            <td align="right"><?php echo number_format($totcom, 2, ',', '.'); ?></td>
            <td align="right"><?php echo number_format($mtoiva, 2, ',', '.'); ?></td>
          </tr>
          <tr>
            <td>Total Compras Internas Afectas solo Alícuota General + Adicional</td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
          </tr>
          <tr>
            <td>Total Compras Internas Afectas solo Alícuota Reducida</td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
          </tr>
          <tr class="ui-widget-header">
            <td width="500"><strong>Total Compras y créditos fiscales del período</strong></td>
            <td width="100" align="right"><strong><?php echo number_format(($totcom+$mtoex), 2, ',', '.'); ?></strong></td>
            <td width="100" align="right"><strong><?php echo number_format($mtoiva, 2, ',', '.'); ?></strong></td>
          </tr>
          <tr>
            <td>Créditos Fiscales producto de la aplicación del porcentaje de la prorrata</td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
          </tr>
          <tr>
            <td>Excedente de Crédito Fiscal del Periodo Anterior</td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
          </tr>
          <tr>
            <td>Ajustes a los créditos fiscales de periodos anteriores</td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
          </tr>
          <tr class="ui-widget-header">
            <td width="500"><strong>Compras no gravadas y/o sin derecho a credito fiscal</strong></td>
            <td width="100" align="right"><strong><?php echo number_format(0, 2, ',', '.'); ?></strong></td>
            <td width="100" align="right"><strong><?php echo number_format(0, 2, ',', '.'); ?></strong></td>
          </tr>
        </tbody>
      </table>

    </div>
    <div align="center">
      <a href="libro_compras_pzo_excel.php?&fechai=<?php echo $fechaii; ?>&fechaf=<?php echo $fechaff; ?>"><img src="images/excel.jpeg" width="19" height="18" border="0" /> Exportar a Excel</a>
    </div>
    <br>
  </div>

</div>
<?php
} else {
  header('Location: index.php');
}
?>