<?php 
set_time_limit(0);
$fechai = $_POST['fechai'];
// $fechai = normalize_date2($fechai);
$fechaf = $_POST['fechaf'];
// $fechaf = normalize_date2($fechaf);
$edv = $_POST['edv'];
//$sucursal = $_POST['sucursal'];
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
	if ($edv != "-"){
		$notas_debitos = mssql_query("SELECT safact.codvend as code_vendedor, numerod, notas1, notas2,notas10, safact.fechae as fecha_fact, safact.codclie as cod_clie, safact.descrip as cliente, MtoTotal/factorp as monto, NumeroR  FROM safact inner join saclie on safact.codclie = saclie.codclie  where   DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai' and '$fechaf' and safact.codvend = '$edv' and tipofac = 'B' order by numerod");
	}else{
		$notas_debitos = mssql_query("SELECT safact.codvend as code_vendedor, numerod, notas1, notas2,notas10, safact.fechae as fecha_fact, safact.codclie as cod_clie, safact.descrip as cliente, MtoTotal/factorp as monto, NumeroR  FROM safact inner join saclie on safact.codclie = saclie.codclie  where   DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai' and '$fechaf'  and tipofac = 'B' order by safact.codvend");
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
<section class="content">
	<div class="row">
		<div class="col-12">
			<div class="card card-saint">
				<div class="card-header">
					<script type="text/javascript">
						function regresa(){
							window.location.href = "principal1.php?page=devoluciones&mod=1";
						}
					</script>
					<h3 class="card-title">Devoluciones Facturas y Notas de Entrega</h3>&nbsp;&nbsp;&nbsp;
					<button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
				</div>
				<div class="card-body">
					<!--  <table id="example2" class="table table-bordered table-hover"> -->
						<table id="example1" class="table table-sm table-bordered table-striped">
							<thead style="background-color: #00137f;color: white;">
								<tr>
									<th>Vendedor</th>
									<th># Devolucion</th>
									<th># Factura</th>
									<th>Fecha Dev</th>
									<th>Codigo Cliente</th>
									<th>Razon Social</th>
									<th>Monto $</th>
									<th>Notas 1</th>
									<th>Notas 2</th>
									<th>Notas 10</th>
								</tr>
							</thead>
							<tbody>
								<?php
								for ($j = 0; $j < mssql_num_rows($notas_debitos); $j++) {
									?>
									<tr>                    
										<td><?php echo mssql_result($notas_debitos,$j,"code_vendedor"); ?></td>
										<td><?php echo mssql_result($notas_debitos,$j,"numerod"); ?></td>
										<td><?php echo mssql_result($notas_debitos,$j,"numeror"); ?></td>
										<td><?php echo date("d/m/Y", strtotime(mssql_result($notas_debitos,$i,"fecha_fact"))); ?></td>
										<td><?php echo mssql_result($notas_debitos,$j,"cod_clie"); ?></td>
										<td><?php echo utf8_encode(mssql_result($notas_debitos,$j,"cliente")); ?></td>
										<td><?php echo rdecimal2(mssql_result($notas_debitos,$j,"monto")); ?></td>
										<td><?php echo utf8_encode(mssql_result($notas_debitos,$j,"notas1")); ?></td>
										<td><?php echo utf8_encode(mssql_result($notas_debitos,$j,"notas2")); ?></td>
										<td><?php echo utf8_encode(mssql_result($notas_debitos,$j,"notas10")); ?></td>
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
</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
<?php
} else {
	header('Location: index.php');
}
?>