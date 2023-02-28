<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {

 $fechai = $_POST['fechai'].' 00:00:00';
 /*$fechai = normalize_date2($fechai).' 00:00:00';*/
 $fechaf = $_POST['fechaf'].' 23:59:59';
 $sucursal = $_POST['sucursal'];
 /*$fechaf = normalize_date2($fechaf).' 23:59:59';*/
 $fechaii = normalize_date($_POST['fechai']);
 $fechaff = normalize_date($_POST['fechaf']);

 if ($sucursal != '-') {
  $query = mssql_query("
    SELECT FechaI FechaDoc, ID3 ID3,  Descrip DescripProv, NumeroR NroComprob, case when cm.TipoCom = 'H' then cm.NumeroD else '' end NroFact, NULL NroND, case when cm.TipoCom = 'I' then cm.NumeroD else '' end NroNC,case when cm.TipoCom = 'H' then '01-REG' when cm.TipoCom = 'I' then '03-REG' END TipTran, case when cm.TipoCom = 'I' then NumeroN else '' end NroFactAfec, case when cm.TipoCom = 'H' then 1 else -1 end * (cm.mtotax+cm.tgravable+cm.texento) TotalCompras, case when cm.TipoCom = 'H' then 1 else -1 end * (isnull(pvp.Monto,0)+isnull(ial.Monto,0)) + case when cm.TipoCom = 'H' then 1 else -1 end * cm.TExento ComprasExe, case when cm.TipoCom = 'H' then 1 else -1 end * tax.TGravable BaseImpo,  tax.MtoTax PorIVA,case when cm.TipoCom = 'H' then 1 else -1 end * tax.Monto IVA, case when cm.TipoCom = 'H' then 1 else -1 end * RetenIVA IVAReten, 1 EnPeriodo, cm.CodSucu CodSucu, FechaE FechaF, null nrocontrol
    FROM SACOMP cm 
    left join SATAXCOM tax on cm.NumeroD = tax.NumeroD and cm.CodProv = tax.CodProv and cm.TipoCom = tax.TipoCom and tax.CodTaxs = 'IVA'
    left join SATAXCOM IAL on cm.NumeroD = ial.NumeroD and cm.CodProv = ial.CodProv and cm.TipoCom = ial.TipoCom and ial.CodTaxs = 'IAL'
    left join SATAXCOM PVP on cm.NumeroD = pvp.NumeroD and cm.CodProv = pvp.CodProv and cm.TipoCom = pvp.TipoCom and pvp.CodTaxs = 'PVP'
    where cm.TipoCom in ('H','I') and 
    cm.FechaE  >= '$fechai'
    and
    cm.FechaE <= '$fechaf' and cm.codsucu ='$sucursal' order by FechaE");
}else{
  $query = mssql_query("
   SELECT FechaI FechaDoc, ID3 ID3,  Descrip DescripProv, NumeroR NroComprob, case when cm.TipoCom = 'H' then cm.NumeroD else '' end NroFact, NULL NroND, case when cm.TipoCom = 'I' then cm.NumeroD else '' end NroNC,case when cm.TipoCom = 'H' then '01-REG' when cm.TipoCom = 'I' then '03-REG' END TipTran, case when cm.TipoCom = 'I' then NumeroN else '' end NroFactAfec, case when cm.TipoCom = 'H' then 1 else -1 end * (cm.mtotax+cm.tgravable+cm.texento) TotalCompras, case when cm.TipoCom = 'H' then 1 else -1 end * (isnull(pvp.Monto,0)+isnull(ial.Monto,0)) + case when cm.TipoCom = 'H' then 1 else -1 end * cm.TExento ComprasExe, case when cm.TipoCom = 'H' then 1 else -1 end * tax.TGravable BaseImpo,  tax.MtoTax PorIVA,case when cm.TipoCom = 'H' then 1 else -1 end * tax.Monto IVA, case when cm.TipoCom = 'H' then 1 else -1 end * RetenIVA IVAReten, 1 EnPeriodo, cm.CodSucu CodSucu, FechaE FechaF
   FROM SACOMP cm 
   left join SATAXCOM tax on cm.NumeroD = tax.NumeroD and cm.CodProv = tax.CodProv and cm.TipoCom = tax.TipoCom and tax.CodTaxs = 'IVA'
   left join SATAXCOM IAL on cm.NumeroD = ial.NumeroD and cm.CodProv = ial.CodProv and cm.TipoCom = ial.TipoCom and ial.CodTaxs = 'IAL'
   left join SATAXCOM PVP on cm.NumeroD = pvp.NumeroD and cm.CodProv = pvp.CodProv and cm.TipoCom = pvp.TipoCom and pvp.CodTaxs = 'PVP'
   where cm.TipoCom in ('H','I') and 
   cm.FechaE  >= '$fechai'
   and
   cm.FechaE <= '$fechaf'  order by FechaE");

}

if ($sucursal != '-') {
  $query_retenciones = mssql_query("
   SELECT FechaI FechaDoc, ID3 ID3,  Descrip DescripProv, NumeroR NroComprob, case when cm.TipoCom = 'H' then cm.NumeroD else '' end NroFact, NULL NroND, case when cm.TipoCom = 'I' then cm.NumeroD else '' end NroNC,case when cm.TipoCom = 'H' then '01-REG' when cm.TipoCom = 'I' then '03-REG' END TipTran, case when cm.TipoCom = 'I' then NumeroN else '' end NroFactAfec, case when cm.TipoCom = 'H' then 1 else -1 end * (cm.mtotax+cm.tgravable+cm.texento) TotalCompras, case when cm.TipoCom = 'H' then 1 else -1 end * (isnull(pvp.Monto,0)+isnull(ial.Monto,0)) + case when cm.TipoCom = 'H' then 1 else -1 end * cm.TExento ComprasExe, case when cm.TipoCom = 'H' then 1 else -1 end * tax.TGravable BaseImpo,  tax.MtoTax PorIVA,case when cm.TipoCom = 'H' then 1 else -1 end * tax.Monto IVA, case when cm.TipoCom = 'H' then 1 else -1 end * RetenIVA IVAReten, 1 EnPeriodo, cm.CodSucu CodSucu, FechaE FechaF
   FROM SACOMP cm 
   left join SATAXCOM tax on cm.NumeroD = tax.NumeroD and cm.CodProv = tax.CodProv and cm.TipoCom = tax.TipoCom and tax.CodTaxs = 'IVA'
   left join SATAXCOM IAL on cm.NumeroD = ial.NumeroD and cm.CodProv = ial.CodProv and cm.TipoCom = ial.TipoCom and ial.CodTaxs = 'IAL'
   left join SATAXCOM PVP on cm.NumeroD = pvp.NumeroD and cm.CodProv = pvp.CodProv and cm.TipoCom = pvp.TipoCom and pvp.CodTaxs = 'PVP'
   where cm.TipoCom in ('H','I') and 
   cm.FechaE  >= '$fechai'
   and
   cm.FechaE <= '$fechaf'  and cm.codsucu ='$sucursal' order by FechaE");
}else{
  $query_retenciones = mssql_query("
   SELECT FechaI FechaDoc, ID3 ID3,  Descrip DescripProv, NumeroR NroComprob, case when cm.TipoCom = 'H' then cm.NumeroD else '' end NroFact, NULL NroND, case when cm.TipoCom = 'I' then cm.NumeroD else '' end NroNC,case when cm.TipoCom = 'H' then '01-REG' when cm.TipoCom = 'I' then '03-REG' END TipTran, case when cm.TipoCom = 'I' then NumeroN else '' end NroFactAfec, case when cm.TipoCom = 'H' then 1 else -1 end * (cm.mtotax+cm.tgravable+cm.texento) TotalCompras, case when cm.TipoCom = 'H' then 1 else -1 end * (isnull(pvp.Monto,0)+isnull(ial.Monto,0)) + case when cm.TipoCom = 'H' then 1 else -1 end * cm.TExento ComprasExe, case when cm.TipoCom = 'H' then 1 else -1 end * tax.TGravable BaseImpo,  tax.MtoTax PorIVA,case when cm.TipoCom = 'H' then 1 else -1 end * tax.Monto IVA, case when cm.TipoCom = 'H' then 1 else -1 end * RetenIVA IVAReten, 1 EnPeriodo, cm.CodSucu CodSucu, FechaE FechaF
   FROM SACOMP cm 
   left join SATAXCOM tax on cm.NumeroD = tax.NumeroD and cm.CodProv = tax.CodProv and cm.TipoCom = tax.TipoCom and tax.CodTaxs = 'IVA'
   left join SATAXCOM IAL on cm.NumeroD = ial.NumeroD and cm.CodProv = ial.CodProv and cm.TipoCom = ial.TipoCom and ial.CodTaxs = 'IAL'
   left join SATAXCOM PVP on cm.NumeroD = pvp.NumeroD and cm.CodProv = pvp.CodProv and cm.TipoCom = pvp.TipoCom and pvp.CodTaxs = 'PVP'
   where cm.TipoCom in ('H','I') and 
   cm.FechaE  >= '$fechai'
   and
   cm.FechaE <= '$fechaf'  order by FechaE");

}
$num = mssql_num_rows($query); 
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
          <h1 class="m-0 text-dark">Libro de Compras</h1>
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
              window.location.href = "principal1.php?page=libro_compras_global&mod=1";
            }
          </script>

          <button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <table id="example1" class="table table-sm text-center table-condensed table-bordered table-striped" style="width:100%;">
      <thead  style="background-color: #00137f;color: white;">
        <tr id="cells">
          <th align="center">Nro. Ope</th>
          <th align="center">Fecha Documento</th>
          <th align="center">Rif</th>
          <th align="center">Nombre o Razón Social</th>
          <th align="center">Numero Comprobante</th>
          <th align="center">Numero de Factura</th>
          <th align="center">Numeo Control Factura</th>
          <th align="center">Numero Nota Debito</th>
          <th align="center">Numero Nota Credito</th>
          <th align="center">Tipo Transaccion</th>
          <th align="center">Numero Factura Afectada</th>
          <th align="center">Total Compras Incluyendo IVA</th>
          <th align="center">Compras Exentas</th>
          <th align="center">Base Imponible 16%</th>
          <th align="center">A/G 16%</th>
          <th align="center">Impuesto IVA 16%</th>
          <th align="center">IVA Retenido (al Vendedor)</th>
        </tr>
      </thead>
      <tbody style="background-color: aliceblue">
        <?php
        $totalcomprasiva = $comprasexentas = $baseimponible = $impuestoiva = $ivaretenido = 0;
        for($i=0;$i<$num;$i++){
          $k = $i+1;   
          ?>
          <tr>
            <!-- <th align="center">Nro. Ope</th> -->
            <td><?php echo $k; ?></td>
            <!-- <th align="center">Fecha Documento</th> -->
            <td><?php echo date('d/m/Y', strtotime(mssql_result($query, $i, 'FechaDoc'))); ?></td>
            <!-- <th align="center">Rif</th> -->
            <td><?php echo mssql_result($query, $i, 'ID3'); ?></td>            
            <!-- <th align="center">Nombre o Razón Social</th> -->
            <td><?php echo utf8_decode(mssql_result($query, $i, 'DescripProv')); ?></td>
            <!-- <th align="center">Numero Comprobante</th> -->
            <td><?php echo mssql_result($query, $i, 'NroComprob'); ?></td>
            <!-- <th align="center">Numero de Factura</th> -->
            <td><?php echo mssql_result($query, $i, 'NroFact'); ?></td>
            <!-- <th align="center">Numeo Control Factura</th> -->
            <td></td>
            <!-- <th align="center">Numero Nota Debito</th> -->
            <td><?php echo mssql_result($query, $i, 'NroND'); ?></td>
            <!-- <th align="center">Numero Nota Credito</th> -->
            <td><?php echo mssql_result($query, $i, 'NroNC'); ?></td>
            <!-- <th align="center">Tipo Transaccion</th> -->
            <td><?php echo mssql_result($query, $i, 'TipTran'); ?></td>
            <!-- <th align="center">Numero Factura Afectada</th> -->
            <td><?php echo mssql_result($query, $i, 'NroFactAfec'); ?></td>
            <!-- <th align="center">Total Compras Incluyendo IVA</th> -->
            <td><?php echo number_format(mssql_result($query, $i, 'TotalCompras'), 2, ',', '.'); $totalcomprasiva += mssql_result($query, $i, 'TotalCompras'); ?></td>
            <!-- <th align="center">Compras Exentas</th> -->           
            <td><?php echo number_format(mssql_result($query, $i, 'ComprasExe'), 2, ',', '.'); $comprasexentas += mssql_result($query, $i, 'ComprasExe'); ?></td>
            <!-- <th align="center">Base Imponible 16%</th> -->
            <td><?php echo number_format(mssql_result($query, $i, 'BaseImpo'), 2, ',', '.'); $baseimponible += mssql_result($query, $i, 'BaseImpo');  ?></td>
            <!-- <th align="center">A/G 16%</th> -->
            <td class="text-right"><?php echo rdecimal0(mssql_result($query, $i, 'PorIVA')); ?> %</td>
            <!-- <th align="center">Impuesto IVA 16%</th> -->
            <td><?php echo number_format(mssql_result($query, $i, 'IVA'), 2, ',', '.');  $impuestoiva += mssql_result($query, $i, 'IVA'); ?></td>
            <!-- <th align="center">IVA Retenido (al Vendedor)</th> -->
            <td><?php echo number_format(mssql_result($query, $i, 'IVAReten'), 2, ',', '.'); $ivaretenido += mssql_result($query, $i, 'IVAReten');  ?></td>
          </tr>
          <?php
          $totalresumen= $comprasexentas+ $baseimponible;
          $totalresumen1= $impuestoiva;
          $totalresumen2= $ivaretenido;
        }
        ?>
        <tr class="bg-dark text-white">
          <td colspan="11" align="right"><strong>Totales</strong></td>
          <td class="text-right"><?php echo number_format($totalcomprasiva, 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format($comprasexentas, 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format($baseimponible, 2, ',', '.'); ?></td>
          <td></td>
          <td class="text-right"><?php echo number_format($impuestoiva, 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format($ivaretenido, 2, ',', '.'); ?></td>
        </tr>
      </tbody>
    </table>
    <br>
    <hr>

    <table id="tabla1" class="table table-sm text-center table-condensed table-bordered table-striped" style="width:60%;">
      <thead  style="background-color: #00137f;color: white;">
        <tr id="cells">
          <th width="500"><strong>RESUMEN LIBROS DE COMPRAS</strong></th>
          <th width="100"><strong>BASE IMPONIBLE</strong></th>
          <th width="100"><strong>CREDITO FISCAL</strong></th>
          <th width="100"><strong>IVA RETENIDO (A TERCEROS) </strong></th>
        </tr>
      </thead>
      <tbody style="background-color: aliceblue">
        <tr>
          <td class="text-left">Total Compras Exentas y/o sin derecho a Credito Fiscal</td>
          <td class="text-right"><?php echo number_format($comprasexentas, 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td class="text-left">Total Compras Importacion Afectadas solo Alicuota General 12%</td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td class="text-left">Total Compras Importacion Afectadas en Alicuota General + Adicional</td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td class="text-left">Total Compras Importacion Afectadas en Alicuota Reducida</td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td class="text-left">Total Compras Importacion Afectadas solo Alicuota General 12%</td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td class="text-left">Total Compras Internas Afectadas solo Alicuota General 16%</td>
          <td class="text-right"><?php echo number_format($baseimponible, 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format($impuestoiva, 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format($ivaretenido, 2, ',', '.'); ?></td>
        </tr>
        <tr>
          <td class="text-left">Total Compras Internas Afectadas solo Alicuota General 12% Ajustes</td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
          <td class="text-right"><?php echo number_format('0', 2, ',', '.'); ?></td>
        </tr>
        <tr></tr>
        <tr class="ui-widget-header">
          <td class="text-left" width="500"><strong>Compras no gravadas y/o sin derecho a credito fiscal</strong></td>
          <td class="text-right" width="100"><strong><?php echo number_format($totalresumen, 2, ',', '.'); ?></strong></td>
          <td class="text-right" width="100"><strong><?php echo number_format($totalresumen1, 2, ',', '.'); ?></strong></td>
          <td class="text-right" width="100"><strong><?php echo number_format($totalresumen2, 2, ',', '.'); ?></strong></td>
        </tr>
      </tbody>
    </table>

  </div>
   <div align="center">
    <a href="libro_compras_global_excel.php?&fechai=<?php echo $fechai; ?>&fechaf=<?php echo $fechaf; ?>&sucursal=<?php echo $sucursal; ?>"><img src="images/excel.jpeg" width="19" height="18" border="0" /> Exportar a Excel</a>
  </div>
  <br>
</div>

</div>
<?php
} else {
  header('Location: index.php');
}
?>