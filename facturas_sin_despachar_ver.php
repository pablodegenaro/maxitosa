<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
	set_time_limit(0);
	$fechai = $_POST['fechai'];
	$fechaf = $_POST['fechaf'];
	$convend = $_POST['edv'];
	$sucursal = $_POST['sucursal'];
	function diasEntreFechas($fechainicio, $fechafin){
		return ((strtotime($fechafin)-strtotime($fechainicio))/86400);
	}
	if ($convend != "-"){
		$facturas = mssql_query("
			SELECT *,sa.MtoTotal/sa.factorp as monto_total,
			(select sum(cantidad) from saitemfac where SAITEMFAC.CodSucu = '$sucursal' AND saitemfac.numerod = SA.numerod and saitemfac.tipofac = 'A' and EsUnid = '0') as Bult,
			(select sum(cantidad) from saitemfac where SAITEMFAC.CodSucu = '$sucursal' AND saitemfac.numerod = SA.numerod and saitemfac.tipofac = 'A' and EsUnid = '1') as Paq
			from safact AS SA where SA.CodSucu = '$sucursal' AND DATEADD(dd, 0, DATEDIFF(dd, 0, SA.FechaE))
			between '$fechai' and '$fechaf' and SA.TipoFac = 'A' and SA.codvend = '$convend' and
			SA.NumeroD not in (SELECT numeros FROM appfacturas_det) and
			(SA.NumeroR is null or SA.NumeroR in (select x.NumeroD from SAFACT as x where cast(x.MtoTotal as int)<cast(SA.MtoTotal as int) and X.TipoFac  = 'B'
				and x.NumeroD=SA.NumeroR))

			UNION

			SELECT *,(sa.MtoTotal/sa.factorp) as monto_total,
			(select sum(cantidad) from saitemfac where SAITEMFAC.CodSucu = '$sucursal' AND saitemfac.numerod = SA.numerod and saitemfac.tipofac = 'C' and EsUnid = '0') as Bult,
			(select sum(cantidad) from saitemfac where SAITEMFAC.CodSucu = '$sucursal' AND saitemfac.numerod = SA.numerod and saitemfac.tipofac = 'C' and EsUnid = '1') as Paq
			from safact AS SA where SA.CodSucu = '$sucursal' AND DATEADD(dd, 0, DATEDIFF(dd, 0, SA.FechaE))
			between '$fechai' and '$fechaf' and SA.TipoFac = 'C' and SA.codvend = '$convend' and
			SA.NumeroD not in (SELECT numeros FROM appfacturas_det) and
			(SA.NumeroR is null or SA.NumeroR in (select x.NumeroD from SAFACT as x where cast(x.MtoTotal as int)<cast(SA.MtoTotal as int) and X.TipoFac  = 'D'
				and x.NumeroD=SA.NumeroR)) order by SA.NumeroD

			");
	}else{
		$facturas = mssql_query("
			SELECT *,(sa.MtoTotal/sa.factorp) as monto_total,
			(select sum(cantidad) from saitemfac where SAITEMFAC.CodSucu = '$sucursal' AND saitemfac.numerod = SA.numerod and saitemfac.tipofac = 'A' and EsUnid = '0') as Bult,
			(select sum(cantidad) from saitemfac where SAITEMFAC.CodSucu = '$sucursal' AND saitemfac.numerod = SA.numerod and saitemfac.tipofac = 'A' and EsUnid = '1') as Paq 
			from safact AS SA  where SA.CodSucu = '$sucursal' AND DATEADD(dd, 0, DATEDIFF(dd, 0, SA.FechaE))
			between '$fechai' and '$fechaf' and SA.TipoFac = 'A' and
			(SA.NumeroR is null or SA.NumeroR in (select x.NumeroD from SAFACT as x where cast(x.MtoTotal as int)<cast(SA.MtoTotal as int) and X.TipoFac  =  'B'
				and x.NumeroD=SA.NumeroR)) and SA.NumeroD not in (SELECT numeros FROM appfacturas_det)

			UNION

			SELECT *,(sa.MtoTotal/sa.factorp) as monto_total,
			(select sum(cantidad) from saitemfac where SAITEMFAC.CodSucu = '$sucursal' AND saitemfac.numerod = SA.numerod and saitemfac.tipofac = 'C' and EsUnid = '0') as Bult,
			(select sum(cantidad) from saitemfac where SAITEMFAC.CodSucu = '$sucursal' AND saitemfac.numerod = SA.numerod and saitemfac.tipofac = 'C' and EsUnid = '1') as Paq 
			from safact AS SA where SA.CodSucu = '$sucursal' AND DATEADD(dd, 0, DATEDIFF(dd, 0, SA.FechaE))
			between '$fechai' and '$fechaf' and SA.TipoFac = 'C' and
			(SA.NumeroR is null or SA.NumeroR in (select x.NumeroD from SAFACT as x where cast(x.MtoTotal as int)<cast(SA.MtoTotal as int) and X.TipoFac  =  'D'
				and x.NumeroD=SA.NumeroR)) and SA.NumeroD not in (SELECT numeros FROM appfacturas_det)  order by SA.NumeroD
			");
	}
	$hoy = date("d-m-Y");
	?>
	<div class="content-wrapper">
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
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card card-saint">
				<div class="card-header">
					<script type="text/javascript">
						function regresa(){
							window.location.href = "principal1.php?page=facturas_sin_despachar&mod=1";
						}
					</script>
					<h3 class="card-title">Facturas sin Despachar</h3>&nbsp;&nbsp;&nbsp;
					<button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
				</div>
				<div class="card-body">
					<table id="example1" class="table table-bordered table-striped">
						<thead style="background-color: #00137f;color: white;">
							<tr>
								<th>Documento</th>
								<th>Fecha Emision</th>
								<th>Cod Clie</th>
								<th>Razon Social</th>
								<th>DiasHastHoy</th>
								<th>CantBulto</th>
								<th>CantPaq</th>
								<th>Monto</th>
								<th>Edv</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$suma_bulto = 0;
							$suma_paq = 0;
							$suma_monto = 0;
							$porcent = 0;
							for($i=0;$i<mssql_num_rows($facturas);$i++){
								$tipofac = mssql_result($facturas,$i,"tipofac");
								?>
								<tr>
									<td><?php echo mssql_result($facturas,$i,"numerod"); ?> <br> 
										<label for="tipo_fact" class="col-form-label-sm">
											<?php 
											if ($tipofac == 'C') {
												echo "Nota de Entrega";
											} else { echo "Factura";} ?></label></td>
											<td><?php echo date('d/m/Y', strtotime(mssql_result($facturas,$i,"fechae"))); ?></td>
											<td><?php echo mssql_result($facturas,$i,"codclie"); ?></td>
											<td><?php echo utf8_encode(mssql_result($facturas, $i, 'descrip')); ?></td>
											<td><?php echo round(diasEntreFechas(date("d-m-Y", strtotime(mssql_result($facturas,$i,"fechae"))),$hoy)); ?></td>
											<td><?php echo round(mssql_result($facturas,$i,"bult")); ?></td>
											<td><?php echo round(mssql_result($facturas,$i,"paq")); ?></td>
											<td><?php echo rdecimal2(mssql_result($facturas,$i,"monto_total")); ?></td>
											<td><?php echo mssql_result($facturas,$i,"codvend"); ?></td>
										</tr>
										<?php
										$cont++;
									}
									?>
								</tbody>
							</table>
						</div>
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