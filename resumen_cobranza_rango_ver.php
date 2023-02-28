<?php 
ini_set('memory_limit', '512M');
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {

	$rango = $_GET['rango'];
	$edv = $_GET['edv'];
	$fechaf = $_GET['fechaf'];
	$fechai = $_GET['fechai'];
	$suma = 0;
	$query = mssql_query ("SELECT cxc.CodVend CodVend, cxc.Fechat, cxc.NumeroD NumeroD, pag.NumeroD NumeroFac, pag.Monto/fac.Factor MONTO, cl.Descrip Descrip, cxc.TipoCxc TipoFac, fac.Factor FactorP, cl.DiasCred DiasCred, isnull(appfac.fechad,pag.fechao) as fechafac,
		DATEDIFF(dd,isnull(appfac.fechad,pag.fechao),cxc.fechat) as dias
		from SAPAGCXC pag 
		inner join SAACXC cxc on pag.NroPpal = cxc.NroUnico and (cxc.TipoCxc like '4%')
		inner join SACLIE CL ON CXC.CodClie = CL.CodClie left join appfacturas_det as app on pag.NumeroD=app.numeros left join appfacturas appfac on app.correl=appfac.correl
		left  join SAACXC fac on pag.NroRegi = fac.NroUnico and fac.TipoCxC = '10'
		where 
		(CONVERT(DATETIME,CONVERT(DATETIME,'$fechai',120)+' 00:00:00',120)<=cxc.Fechat) AND
		(cxc.Fechat<=CONVERT(DATETIME,CONVERT(DATETIME,'$fechaf',120)+' 23:59:59',120)) and
		cxc.CodVend = '$edv'
		order by cxc.Fechat");
	$k = $l = $n = $o = $suma = 0;
	$cero_sie = $och_cat = $qui_vei = $more = $array = array();
	for ($i=0; $i < mssql_num_rows($query); $i++) {
		$tipofac= mssql_result($query, $i, 'tipofac');
		$dias= mssql_result($query, $i, 'dias');
		if (($tipofac == 41)  ) {
			if($dias <= 7){
				$cero_sie[$k]['fechafac'] = mssql_result($query, $i, 'fechafac');
				$cero_sie[$k]['fechat'] = mssql_result($query, $i, 'fechat');
				$cero_sie[$k]['NumeroD'] = mssql_result($query, $i, 'NumeroD');
				$cero_sie[$k]['numerofac'] = mssql_result($query, $i, 'numerofac');
				$cero_sie[$k]['descrip'] = mssql_result($query, $i, 'descrip');
				$cero_sie[$k]['diascred'] = mssql_result($query, $i, 'diascred');
				$cero_sie[$k]['FactorP'] = mssql_result($query, $i, 'FactorP');
				$cero_sie[$k]['monto'] = mssql_result($query, $i, 'monto');
				$k++;
			}elseif(($dias > 7) and ($dias <= 14)){
				$och_cat[$l]['fechafac'] = mssql_result($query, $i, 'fechafac');
				$och_cat[$l]['fechat'] = mssql_result($query, $i, 'fechat');
				$och_cat[$l]['NumeroD'] = mssql_result($query, $i, 'NumeroD');
				$och_cat[$l]['numerofac'] = mssql_result($query, $i, 'numerofac');
				$och_cat[$l]['descrip'] = mssql_result($query, $i, 'descrip');
				$och_cat[$l]['diascred'] = mssql_result($query, $i, 'diascred');
				$och_cat[$l]['FactorP'] = mssql_result($query, $i, 'FactorP');
				$och_cat[$l]['monto'] = mssql_result($query, $i, 'monto');
				$l++;
			}elseif(($dias > 14) and ($dias <= 21)){
				$qui_vei[$n]['fechafac'] = mssql_result($query, $i, 'fechafac');
				$qui_vei[$n]['fechat'] = mssql_result($query, $i, 'fechat');
				$qui_vei[$n]['NumeroD'] = mssql_result($query, $i, 'NumeroD');
				$qui_vei[$n]['numerofac'] = mssql_result($query, $i, 'numerofac');
				$qui_vei[$n]['descrip'] = mssql_result($query, $i, 'descrip');
				$qui_vei[$n]['diascred'] = mssql_result($query, $i, 'diascred');
				$qui_vei[$n]['FactorP'] = mssql_result($query, $i, 'FactorP');
				$qui_vei[$n]['monto'] = mssql_result($query, $i, 'monto');
				$n++;
			}else{
				$more[$o]['fechafac'] = mssql_result($query, $i, 'fechafac');
				$more[$o]['fechat'] = mssql_result($query, $i, 'fechat');
				$more[$o]['NumeroD'] = mssql_result($query, $i, 'NumeroD');
				$more[$o]['numerofac'] = mssql_result($query, $i, 'numerofac');
				$more[$o]['descrip'] = mssql_result($query, $i, 'descrip');
				$more[$o]['diascred'] = mssql_result($query, $i, 'diascred');
				$more[$o]['FactorP'] = mssql_result($query, $i, 'FactorP');
				$more[$o]['monto'] = mssql_result($query, $i, 'monto');
				$o++;
			}
		}
	}
	switch ($rango) {
		case 1:
		$array = $cero_sie;
		$rango_f = "0 a 7 dias";
		break;
		case 2:
		$array = $och_cat;
		$rango_f = "8 a 14 dias";
		break;
		case 3:
		$array = $qui_vei;
		$rango_f = "15 a 21 dias";
		break;
		case 4:
		$array = $more;
		$rango_f = "mas de 21 dias";
		break;
	}

	?>
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
			</div>
		</section>
		<!-- BOX DEL CONTENIDO DE LA VISTA FORMULARIO Y TABLA -->
		<section class="content">
			<div class="row">
				<div class="col-12">
					<div class="card card-saint">
						<script type="text/javascript">
							function regresa(){
								window.location.href = "principal1.php?page=resumen_cobranza&mod=1";
							}
						</script>
						<div class="card-body">
							<h2>Cobros EDV <?php echo $edv; ?> de <?php echo $rango_f; ?> <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button></h2>
							<table id="example2" class="table table-sm table-bordered table-hover">
								<tr class="ui-widget-header" style="background-color: #00137f;color: white;">
									<td width="120" align="center"><strong>Fecha Despacho</strong></td>
									<td width="120" align="center"><strong>Fecha Cobranza</strong></td>
									<td width="80" align="center"><strong>Recibo</strong></td>
									<td width="80" align="center"><strong>Factura</strong></td>
									<td width="250" align="center"><strong>Razon Social</strong></td>
									<td width="80" align="center"><strong>Dias de Credito</strong></td>
									<td width="80" align="center"><strong>Factor</strong></td>
									<td width="80" align="center"><strong>Monto $</strong></td>
								</tr>
								<?php for($j=0;$j<count($array);$j++){ 
									$suma += $array[$j]["monto"];
									?>
									<tr <?php if ($j%2 != 0){ ?> bgcolor="#CCCCCC" <?php } ?> >
										<td align="center"><?php echo date('d-m-Y', strtotime($array[$j]['fechafac'])); ?></td>
										<td align="center"><?php echo date('d-m-Y', strtotime($array[$j]['fechat'])); ?></td>
										<td><?php echo $array[$j]['NumeroD']; ?></td>
										<td><?php echo $array[$j]['numerofac']; ?></td>
										<td><?php echo utf8_encode($array[$j]['descrip']); ?></td>
										<td><?php echo utf8_encode($array[$j]['diascred']); ?></td>
										<td align="right"><?php echo number_format($array[$j]['FactorP'], 2, ',', '.'); ?></td>
										<td align="right"><?php echo number_format($array[$j]['monto'], 2, ',', '.'); ?></td>
									</tr>
								<?php } ?>
							</table> 
							<?php echo "TOTAL FACTURAS: ".count($array)." TOTAL MONTO: ".number_format($suma, 2, ',', '.'); ?>
							<div align="center"><a href="cobranza_edv_ver_rango_excel.php?&rango=<?php echo $_GET['rango']; ?>&fechai=<?php echo $_GET['fechai']; ?>&fechaf=<?php echo $_GET['fechaf']; ?>&edv=<?php echo $_GET['edv']; ?>" ><img src="images/excel.jpeg" width="19" height="18" border="0" /> Exportar a Excel</a>&nbsp;&nbsp;</div>	
							<br>
						</div>
					</div>
				</div>
			</div>
		</section>

	</div>
	<?php include "footer.php"; ?>
	<script src="Icons.js" type="text/javascript"></script>
	<?php
} else {
	header('Location: index.php');
}
?>