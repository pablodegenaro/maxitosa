<?
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Libro_Compras_El_Triunfo".date('d-m-Y h:i a',time() - 3600*date('I')).".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php 
require("conexion.php");
require("funciones.php");
session_start();
set_time_limit(0);
ini_set('memory_limit', '512M');

$sucursal = $_GET['sucursal'];
$fechai = $_GET['fechai'];
$fechaf = $_GET['fechaf'];
$fechaii = normalize_date($fechai) . ' 00:00:00';
$fechaff = normalize_date($fechaf) . ' 23:59:59';

if ($sucursal != '-') {
$query = mssql_query("
SELECT FechaI FechaDoc, ID3 ID3,  Descrip DescripProv, ''''+NumeroR NroComprob, case when cm.TipoCom = 'H' then cm.NumeroD else '' end NroFact, NULL NroND, case when cm.TipoCom = 'I' then cm.NumeroD else '' end NroNC,case when cm.TipoCom = 'H' then '01-REG' when cm.TipoCom = 'I' then '03-REG' END TipTran, case when cm.TipoCom = 'I' then NumeroN else '' end NroFactAfec, case when cm.TipoCom = 'H' then 1 else -1 end * (cm.mtotax+cm.tgravable+cm.texento) TotalCompras, case when cm.TipoCom = 'H' then 1 else -1 end * (isnull(pvp.Monto,0)+isnull(ial.Monto,0)) + case when cm.TipoCom = 'H' then 1 else -1 end * cm.TExento ComprasExe, case when cm.TipoCom = 'H' then 1 else -1 end * tax.TGravable BaseImpo,  tax.MtoTax PorIVA,case when cm.TipoCom = 'H' then 1 else -1 end * tax.Monto IVA, case when cm.TipoCom = 'H' then 1 else -1 end * RetenIVA IVAReten, 1 EnPeriodo, cm.CodSucu CodSucu, FechaE FechaF, null nrocontrol
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
SELECT FechaI FechaDoc, ID3 ID3,  Descrip DescripProv, ''''+NumeroR NroComprob, case when cm.TipoCom = 'H' then cm.NumeroD else '' end NroFact, NULL NroND, case when cm.TipoCom = 'I' then cm.NumeroD else '' end NroNC,case when cm.TipoCom = 'H' then '01-REG' when cm.TipoCom = 'I' then '03-REG' END TipTran, case when cm.TipoCom = 'I' then NumeroN else '' end NroFactAfec, case when cm.TipoCom = 'H' then 1 else -1 end * (cm.mtotax+cm.tgravable+cm.texento) TotalCompras, case when cm.TipoCom = 'H' then 1 else -1 end * (isnull(pvp.Monto,0)+isnull(ial.Monto,0)) + case when cm.TipoCom = 'H' then 1 else -1 end * cm.TExento ComprasExe, case when cm.TipoCom = 'H' then 1 else -1 end * tax.TGravable BaseImpo,  tax.MtoTax PorIVA,case when cm.TipoCom = 'H' then 1 else -1 end * tax.Monto IVA, case when cm.TipoCom = 'H' then 1 else -1 end * RetenIVA IVAReten, 1 EnPeriodo, cm.CodSucu CodSucu, FechaE FechaF
FROM SACOMP cm 
left join SATAXCOM tax on cm.NumeroD = tax.NumeroD and cm.CodProv = tax.CodProv and cm.TipoCom = tax.TipoCom and tax.CodTaxs = 'IVA'
left join SATAXCOM IAL on cm.NumeroD = ial.NumeroD and cm.CodProv = ial.CodProv and cm.TipoCom = ial.TipoCom and ial.CodTaxs = 'IAL'
left join SATAXCOM PVP on cm.NumeroD = pvp.NumeroD and cm.CodProv = pvp.CodProv and cm.TipoCom = pvp.TipoCom and pvp.CodTaxs = 'PVP'
where cm.TipoCom in ('H','I') and 
cm.FechaE  >= '$fechai'
and
cm.FechaE <= '$fechaf'  order by FechaE");

}

$num = mssql_num_rows($query);  ?>

<style type="text/css">
table, th, td {
border: 1px solid black;
border-collapse: collapse;
}
.Estilo1 {
font-size: 24px;
color: #000000;
font-weight: bold;
}
.Estilo2 {
font-size: 20px;
color: #000;
font-weight: bold;
}
.Estilo3 {
font-size: 12px;
font-weight: bold;
font-family: "ARIAL", Courier, monospace;
}
.Estilo4 {
font-size: 12px;
font-family: "ARIAL", Courier, monospace;
}
.Estilo4-bold {
font-size: 12px;
font-family: "ARIAL", Courier, monospace;
font-weight: bold;
}
.Estilo4-white {
font-size: 12px;
color: #FFFFFF;
font-family: "ARIAL", Courier, monospace;
}
.Estilo6 {color: #006600}
.Estilo8 {color: #FF0000}
.Estilo9 {color: #FFFF33}
</style>
<table id="example1"  class="Estilo4" style="width:100%;">
<thead  style="background-color: #00137f;color: white;">
<tr id="cells">
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Nro. Ope</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Fecha Documento</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Rif</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Nombre o Razón Social</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numero Comprobante</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numero de Factura</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numeo Control Factura</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numero Nota Debito</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numero Nota Credito</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Tipo Transaccion</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numero Factura Afectada</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Total Compras Incluyendo IVA</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Compras Exentas</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Base Imponible 16%</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">A/G 16%</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Impuesto IVA 16%</th>
          <th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">IVA Retenido (al Vendedor)</th>                   
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
<hr> <br>

<!--TABLE DE RESUMEN -->
<table id="example1"  class="Estilo4" style="width:100%;">
<thead  style="background-color: #00137f;color: white;">
<tr >
<th align="center" width="500" bgcolor="#B0C4DE"><span class="Estilo4-bold"><strong>RESUMEN LIBROS DE COMPRAS</strong></th>
<th align="center" width="500" bgcolor="#B0C4DE"><span class="Estilo4-bold"><strong>BASE IMPONIBLE</strong></th>
<th align="center" width="500" bgcolor="#B0C4DE"><span class="Estilo4-bold"><strong>CREDITO FISCAL</strong></th>
<th align="center" width="500" bgcolor="#B0C4DE"><span class="Estilo4-bold"><strong>IVA RETENIDO (A TERCEROS) </strong></th>
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