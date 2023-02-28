<?php 
set_time_limit(0);
$fechai = $_POST['fechai'];
$fechaf = $_POST['fechaf'];
$edv = $_POST['edv'];
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
	if ($edv != "-"){
		$notas_debitos = mssql_query("SELECT a.CodClie, b.Descrip, a.FechaE as fecha, a.CodUsua, a.NumeroD, a.NumeroN, a.Document, a.Monto/a.Factorp as monto, a.CodVend, a.notas1, a.notas2, a.CodSucu, a.Document  from saacxc as a inner join SACLIE as b on a.CodClie=b.codclie where DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf' and a.TipoCxc ='31' and a.CodVend ='$edv' order by a.numerod desc");
	}else{
		$notas_debitos = mssql_query("SELECT a.CodClie, b.Descrip, a.FechaE as fecha, a.CodUsua, a.NumeroD, a.NumeroN, a.Document, a.Monto/a.Factorp as monto, a.CodVend ,a.notas1, a.notas2 , a.CodSucu, a.Document from saacxc as a inner join SACLIE as b on a.CodClie=b.codclie where DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf' and a.TipoCxc ='31'  order by a.fechae desc");
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
							window.location.href = "principal1.php?page=resumen_nc&mod=1";
						}
					</script>
					<h3 class="card-title">Resumen de Notas de Credito</h3>&nbsp;&nbsp;&nbsp;
					<button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
				</div>
				<div class="card-body">
					<!--  <table id="example2" class="table table-bordered table-hover"> -->
						<table id="example3" class="table table-sm table-bordered table-striped">
							<thead style="background-color: #00137f;color: white;">
								<tr>
									<th>Sucursal</th>
									<th>Vendedor</th>
									<th># NC</th>
									<th># Factura</th>
									<th>Fecha Dev</th>
									<th>Codigo Cliente</th>
									<th>Razon Social</th>
									<th>Detalle</th>
									<th>Monto $</th>
									<th>Notas 1</th>
									<th>Notas 2</th>
									<th>Usuario</th>
								</tr>
							</thead>
							<tbody>
								<?php
								for ($j = 0; $j < mssql_num_rows($notas_debitos); $j++) {
									$codsusu = mssql_result($notas_debitos,$j,"CodSucu"); 
									?>
									<tr>                    
										<td><?php if ($codsusu == '00000') {
											echo "Puerto Ordaz";
										} elseif ($codsusu == '00001') {
											echo "Maturin";
										}elseif ($codsusu == '00002') {
											echo "Carupano";
										} ?></td>
										<td><?php echo mssql_result($notas_debitos,$j,"CodVend"); ?></td>
										<td><?php echo mssql_result($notas_debitos,$j,"numerod"); ?></td>
										<td><?php echo mssql_result($notas_debitos,$j,"numeron"); ?></td>
										<td><?php echo date("d/m/Y", strtotime(mssql_result($notas_debitos,$j,'fecha'))); ?></td>
										<td><?php echo mssql_result($notas_debitos,$j,"codclie"); ?></td>
										<td><?php echo utf8_encode(mssql_result($notas_debitos,$j,"descrip")); ?></td>
										<td><?php echo utf8_encode(mssql_result($notas_debitos,$j,"Document")); ?></td>
										<td><?php echo rdecimal2(mssql_result($notas_debitos,$j,"monto")); ?></td>
										<td><?php echo utf8_encode(mssql_result($notas_debitos,$j,"notas1")); ?></td>
										<td><?php echo utf8_encode(mssql_result($notas_debitos,$j,"notas2")); ?></td>
										<td><?php echo utf8_encode(mssql_result($notas_debitos,$j,"CodUsua")); ?></td>
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