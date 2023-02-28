<?php 
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=resumen_cobranza.xls");
header("Pragma: no-cache");
header("Expires: 0");
require("conexion.php");
require("funciones.php");
session_start();
set_time_limit(0);
ini_set('memory_limit', '512M');
$fechai = $_GET['fechai'];
$fechaf = $_GET['fechaf'];
$edv = $_GET['edv'];
if ($edv == "-") {
	$query = mssql_query ("SELECT distinct (codVend) edv from savend where activo = '1' ");
}else{
	$query = mssql_query ("SELECT distinct (codVend) edv from savend where codVend like '$edv'");
}

?>
<table id="example2" class="table table-sm table-bordered table-hover">
	<tr class="ui-widget-header" style="background-color: #00137f;color: white;">
		<td  align="center">RUTA</td>
		<td  align="center">0 A 7 DIAS</td>
		<td  align="center">8 A 14 DIAS</td>
		<td  align="center">15 A 21 DIAS</td>
		<td  align="center">MAS DE 21 DIAS</td>
		<td  align="center">TOTAL COBRANZAS</td>
	</tr>
	<?php;
	$j = 0;
	while($dato=mssql_fetch_array($query)){
		$cero_sie = $och_cat = $qui_vei = $more = $suma = 0;
		$edv = $dato['edv'];
		$query2 = mssql_query ("SELECT cxc.CodVend CodVend, cxc.FechaE, cxc.NumeroD NumeroD, pag.NumeroD NumeroFac, pag.Monto/fac.Factor MONTO, cl.Descrip Descrip, cxc.TipoCxc TipoFac, fac.Factor FactorP, cl.DiasCred DiasCred, isnull(appfac.fechad,pag.fechao) as fechafac,
			DATEDIFF(dd,isnull(appfac.fechad,pag.fechao),cxc.fechat) as dias
			from SAPAGCXC pag 
			inner join SAACXC cxc on pag.NroPpal = cxc.NroUnico and (cxc.TipoCxc like '4%' or cxc.TipoCxc like '2%')
			inner join SACLIE CL ON CXC.CodClie = CL.CodClie left join appfacturas_det as app on pag.NumeroD=app.numeros left join appfacturas appfac on app.correl=appfac.correl
			left  join SAACXC fac on pag.NroRegi = fac.NroUnico and fac.TipoCxC = '10'
			where 
			(CONVERT(DATETIME,CONVERT(DATETIME,'$fechai',120)+' 00:00:00',120)<=cxc.Fechat) AND
			(cxc.Fechat<=CONVERT(DATETIME,CONVERT(DATETIME,'$fechaf',120)+' 23:59:59',120)) and
			cxc.CodVend = '$edv'
			order by cxc.Fechat");
		for ($i=0; $i < mssql_num_rows($query2); $i++) {
			$tipofac = mssql_result($query2, $i, 'tipofac');
			$dias= mssql_result($query2, $i, 'dias');
			if (($tipofac == 41) ) {
				if($dias <= 7){
					$cero_sie += mssql_result($query2, $i, 'monto');

				}elseif(($dias > 7) and ($dias <= 14)){
					$och_cat += mssql_result($query2, $i, 'monto');
				}elseif(($dias > 14) and ($dias <= 21)){
					$qui_vei += mssql_result($query2, $i, 'monto');
				}else{
					$more += mssql_result($query2, $i, 'monto');
				}
				$suma += mssql_result($query2, $i, 'monto');
			}
		}
		?>
		<tr <?php if ($j%2 != 0){ ?> bgcolor="#CCCCCC" <?php } ?> >
			<td align="center"><?php echo $edv; ?></td>
			<td align="right"><a target="_blank" href="principal1.php?page=resumen_cobranza_rango_ver&mod=1&rango=1&edv=<?php echo $edv; ?>&fechaf=<?php echo $fechaf; ?>&fechai=<?php echo $fechai; ?>"><?php echo number_format($cero_sie, 2, '.', ','); ?></a></td>
			<td align="right"><a target="_blank" href="principal1.php?page=resumen_cobranza_rango_ver&mod=1&rango=2&edv=<?php echo $edv; ?>&fechaf=<?php echo $fechaf; ?>&fechai=<?php echo $fechai; ?>"><?php echo number_format($och_cat, 2, '.', ','); ?></a></td>
			<td align="right"><a target="_blank" href="principal1.php?page=resumen_cobranza_rango_ver&mod=1&rango=3&edv=<?php echo $edv; ?>&fechaf=<?php echo $fechaf; ?>&fechai=<?php echo $fechai; ?>"><?php echo number_format($qui_vei, 2, '.', ','); ?></a></td>
			<td align="right"><a target="_blank" href="principal1.php?page=resumen_cobranza_rango_ver&mod=1&rango=4&edv=<?php echo $edv; ?>&fechaf=<?php echo $fechaf; ?>&fechai=<?php echo $fechai; ?>"><?php echo number_format($more, 2, ',', '.'); ?></a></td>
			<td align="right"><?php echo number_format($suma, 2, '.', ','); ?></td>
		</tr>
		<?php
		$suma1 += $suma;
		$j++;
	}
	?>
</table> 