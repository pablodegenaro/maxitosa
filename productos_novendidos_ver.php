<?php 
set_time_limit(0);
$fechai = $_POST['fechai'];
//$sucursal = $_POST['sucursal'];
// $fechai = normalize_date2($fechai);

$fechaf = $_POST['fechaf'];
// $fechaf = normalize_date2($fechaf);
// 
//$almacen=''1000','2000'';

session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
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
							window.location.href = "principal1.php?page=productos_novendidos&mod=1";
						}
					</script>
					<h3 class="card-title">Productos no Vendidos</h3>&nbsp;&nbsp;&nbsp;
					<button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
				</div>
				<div class="card-body">

					<!--  <table id="example2" class="table table-bordered table-hover"> -->
						<table id="example3" class="table table-sm table-bordered table-striped">
							<thead style="background-color: #00137f;color: white;">
								<tr>
									<th># Documento</th>
									<th>Codigo Vendedor</th>
									<th>Vendedor</th>
									<th>Codigo Cliente</th>
									<th>Razon Social</th>
									<th>Codigo Producto</th>
									<th>Descripcion</th>
									<th>Marca</th>
									<th>Unid. Empaque</th>
									<th>Cantidad</th>
									<th>Inv. Bultos</th>
									<th>Inv. Paquetes</th>
									<th>Fecha</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$novendidos = mssql_query("SELECT  sf.NumeroD, sf.CodVend, sv.Descrip as vendedor, sc.CodClie as codclie,sc.Descrip as Cliente,  si.CodItem, si.Descrip1,  SP.Marca,  si.EsUnid, sf.Signo*si.Cantidad as Cantidad, sf.FechaE, sax.existen, sax.exunidad  From SACLIE as SC inner join SAFACT as SF on SC.CodClie = SF.CodClie inner join SAITEMFAC as SI on SF.NumeroD = SI.NumeroD INNER JOIN SAPROD AS SP ON SI.CodItem = SP.CodProd inner join SAVEND as sv on sf.CodVend = sv.CodVend inner join saexis as SAX on SI.coditem= SAX.codprod Where  (SUBSTRING(CONVERT(VARCHAR,SF.FechaE,120),1,10) >= '$fechai' And SUBSTRING(CONVERT(VARCHAR,SF.FechaE,120),1,10) <= '$fechaf') And   (SF.NumeroD = SI.NumeroD And SF.TipoFac = SI.TipoFac) And   SC.CodClie = SF.CodClie And SI.NroLineaC = 0 And   SF.TipoFac = 'F'   Order By SF.FechaE desc");
								
								for ($i = 0; $i < mssql_num_rows($novendidos); $i++) {
									$codprod = mssql_result($novendidos, $i, 'CodItem');
									$query = mssql_query("SELECT existen,  exunidad from saexis where CodUbic in ('1000','2000') and codprod ='$codprod'");
									?>
									<tr>
										<td><?php echo mssql_result($novendidos,$i,"NumeroD"); ?></td>
										<td><?php echo mssql_result($novendidos,$i,"CodVend"); ?></td>
										<td><?php echo utf8_encode(mssql_result($novendidos,$i,"vendedor")); ?></td>
										<td><?php echo utf8_encode(mssql_result($novendidos,$i,"codclie")); ?></td>
										<td><?php echo utf8_encode(mssql_result($novendidos,$i,"Cliente")); ?></td>
										<td><?php echo mssql_result($novendidos,$i,"Coditem"); ?></td>
										<td><?php echo utf8_encode(mssql_result($novendidos,$i,"Descrip1")); ?></td>
										<td><?php echo utf8_encode(mssql_result($novendidos,$i,"Marca")); ?></td>
										<td><?php 
										$unidad = mssql_result($novendidos,$i,"EsUnid");
										if ($unidad == '1') {
											echo "PAQUETE";
										} else {
											echo "BULTO";
										}; ?></td>
										<td><?php echo rdecimal2(mssql_result($novendidos,$i,"Cantidad")); ?></td>
										<td><?php echo rdecimal2(mssql_result($novendidos,$i,"existen")); ?></td>
										<td><?php echo rdecimal2(mssql_result($novendidos,$i,"exunidad")); ?></td>
										<td><?php echo mssql_result($novendidos,$i,"fechae"); ?></td>    
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