<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
	$fechai = $_POST['fechai'];
	/*$fechai = normalize_date2($fechai);*/

	$fechaf = $_POST['fechaf'];
	/*$fechaf = normalize_date2($fechaf);*/

	$codprov = $_POST['prove'];
	$sucursal = $_POST['sucursal'];

	switch (true) {
    # =================================================
    # === UN TIPO DE SUCURSAL,  UN PROVEEDOR ==== 
    # =================================================
		case ($sucursal!="-" && $codprov!="-"):
		$query = mssql_query("SELECT a.codprov, a.Descrip, c.TipoCom, a.NumeroD, a.Document, a.FechaE, a.FechaV, a.Saldo, c.Factor from saacxp as a inner join saprov as b on a.codprov = b.codprov left  join SACOMP_01 as c on a.numerod=c.NumeroD and (case when a.TipoCxP= 10 then 'XX' else '' end) = (case when c.TipoCom in ('H','J') then 'XX' else '' end)  
			where a.CodSucu = '$sucursal' AND DATEADD(dd, 0, DATEDIFF(dd, 0, a.fechae)) between '$fechai' and '$fechaf' and a.tipocxp='10' and a.saldo>0  and b.codprov = '$codprov' order by a.fechae desc");
		break;

    # =============================================================
    # === UN TIPO DE SUCURSAL , TODOS LOS PROVEEDORES ==== 
    # =============================================================
		case ($sucursal!="-" && $codprov=="-"):
		$query = mssql_query("SELECT a.codprov, a.Descrip, c.TipoCom, a.NumeroD, a.Document, a.FechaE, a.FechaV, a.Saldo, c.Factor from saacxp as a inner join saprov as b on a.codprov = b.codprov left  join SACOMP_01 as c on a.numerod=c.NumeroD and (case when a.TipoCxP= 10 then 'XX' else '' end) = (case when c.TipoCom in ('H','J') then 'XX' else '' end)  
			where a.CodSucu = '$sucursal' AND DATEADD(dd, 0, DATEDIFF(dd, 0, a.fechae)) between '$fechai' and '$fechaf' and a.tipocxp='10' and a.saldo>0 order by a.fechae desc");
		break;


    # =============================================================
    # ===  UN PROVEEDOR, TODAS LAS SUCURSALES ==== 
    # =============================================================
		case ($sucursal=="-" && $codprov!="-"):
		$query = mssql_query("SELECT a.codprov, a.Descrip, c.TipoCom, a.NumeroD, a.Document, a.FechaE, a.FechaV, a.Saldo, c.Factor from saacxp as a inner join saprov as b on a.codprov = b.codprov left  join SACOMP_01 as c on a.numerod=c.NumeroD and (case when a.TipoCxP= 10 then 'XX' else '' end) = (case when c.TipoCom in ('H','J') then 'XX' else '' end)  
			where  DATEADD(dd, 0, DATEDIFF(dd, 0, a.fechae)) between '$fechai' and '$fechaf' and a.tipocxp='10' and a.saldo>0 and b.codprov = '$codprov' order by a.fechae desc");
		break;


    # ===============================================================
    # === UN TIPO DE VENDEDOR, TODAS LAS SUCURSALES ==== 
    # ===============================================================
		case ($sucursal=="-"  && $codprov=="-"):
		$query = mssql_query("SELECT a.codprov, a.Descrip, c.TipoCom, a.NumeroD, a.Document, a.FechaE, a.FechaV, a.Saldo, c.Factor from saacxp as a inner join saprov as b on a.codprov = b.codprov left  join SACOMP_01 as c on a.numerod=c.NumeroD and (case when a.TipoCxP= 10 then 'XX' else '' end) = (case when c.TipoCom in ('H','J') then 'XX' else '' end)  
			where  DATEADD(dd, 0, DATEDIFF(dd, 0, a.fechae)) between '$fechai' and '$fechaf' and a.tipocxp='10' and a.saldo>0 order by a.fechae desc");
		break;

    # =====================================================
    # === TODAS LAS SUCURSALES,  VENDEDORES ==== 
    # =====================================================
		default:
		$query = mssql_query("");

	}

	$num = mssql_num_rows($query);

	function dias_transcurridos($fecha_i,$fecha_f)
	{
		$dias = (strtotime($fecha_i)-strtotime($fecha_f))/86400;
		$dias   = abs($dias); $dias = floor($dias);   
		return $dias;
	}

	function interval_date($init,$finish)
	{
		$diferencia = strtotime($finish) - strtotime($init);
		if($diferencia < 60){
			$tiempo = "Hace " . floor($diferencia) . " segundos";
		}else if($diferencia > 60 && $diferencia < 3600){
			$tiempo = "Hace " . floor($diferencia/60) . " minutos'";
		}else if($diferencia > 3600 && $diferencia < 86400){
			$tiempo = "Hace " . floor($diferencia/3600) . " horas";
		}else if($diferencia > 86400 && $diferencia < 2592000){
			$tiempo = "Hace " . floor($diferencia/86400) . " días";
		}else if($diferencia > 2592000 && $diferencia < 31104000){
			$tiempo = "Hace " . floor($diferencia/2592000) . " meses";
		}else if($diferencia > 31104000){
			$tiempo = "Hace " . floor($diferencia/31104000) . " años";
		}else{
			$tiempo = "Error";
		}
		return $tiempo;
	}

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
							window.location.href = "principal1.php?page=analisis_vencimiento_proveedores&mod=1";
						}
					</script>
					<h3 class="card-title">Analisis de Vencimiento Proveedores</h3>&nbsp;&nbsp;&nbsp;
					<button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
				</div>
				<div class="card-body">
					<!--  <table id="example2" class="table table-bordered table-hover"> -->
						<table id="example1" class="table table-sm table-bordered table-striped">
							<thead style="background-color: #00137f;color: white;">
								<tr>
									<th>Codigo Proveedor</th>
									<th>Razon Social</th>
									<th>Numero</th>
									<th>Documento</th>
									<th>Fecha Doc</th>
									<th>Fecha Venc</th>
									<th>Dias Trans</th>
									<th>Monto Bs</th>
									<th>Factor</th>
									<th>Monto $</th>
								</tr>
							</thead>
							<tbody>
								<?php for ($i = 0; $i < mssql_num_rows($query); $i++) {
									?>
									<tr>
										<td><?php echo mssql_result($query,$i,"codprov"); ?></td>
										<td><?php echo utf8_encode(mssql_result($query,$i,"descrip")); ?></td>
										<td><?php echo mssql_result($query,$i,"numerod"); ?></td>
										<td><?php echo mssql_result($query,$i,"document"); ?></td>
										<td><?php echo date("d/m/Y", strtotime(mssql_result($query,$i,"fechae"))); ?></td>
										<td><?php echo date("d/m/Y", strtotime(mssql_result($query,$i,"fechav"))); ?></td>
										<td>
											<?php 
											putenv("TZ=America/Caracas");
											/*$fecha = mssql_result($query,$i,"fechae");*/
											$fecha = date("Y-m-d");
											$nuevav = date("Y-m-d", strtotime(mssql_result($query,$i,"fechav")));

											if ($fecha > $nuevav){
												echo dias_transcurridos($fecha,$nuevav);
											}else{
												echo "-".dias_transcurridos($fecha,$nuevav);
											} 
											?>
										</td>
										<td><?php echo number_format(mssql_result($query,$i,"Saldo"), 2, ",", "."); ?></td>
										<td><?php echo number_format(mssql_result($query,$i,"factor"), 2, ",", "."); ?></td>
										<td><?php 
										$suma = mssql_result($query,$i,"Saldo");
										$factor = mssql_result($query,$i,"factor");
										if ($factor > 0) {
											$factor = mssql_result($query,$i,"factor");
										}else{
											$factor= 1;
										}
										$total = $suma / $factor;
										echo number_format($total, 2, ",", "."); ?>
									</td>
								</tr>
								<?php 
								$cont++;
							} ?>
						</tbody>
					</table>
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