<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
	$query = mssql_query("SELECT id, NroRelacion, CodUsua, CONCAT(ven.CodVend, '-',ven.Descrip) Vendedor , sucu.Descrip AS Sucursal, FechaE,
		(SELECT SUM(monto) FROM app_relacion_cobros_items i WHERE i.id_relacion = arc.NroRelacion) Monto 
		FROM app_relacion_cobros arc
		INNER JOIN SASUCURSAL sucu ON sucu.CodSucu = arc.CodSucu
		INNER JOIN SAVEND ven ON ven.CodVend = arc.CodVend
		ORDER BY FechaE DESC");

		?>
		<div class="content-wrapper">
			<?php 	if (mssql_num_rows($query)!=0){ ?>
				<section class="content-header">
					<div class="container-fluid">
						<div class="row mb-2">
							<div class="col-sm-6">
								<!--  <h2 id="title_permisos">Ultima Activacion Clientes</h2> -->
							</div>
							<div class="col-sm-6">
								<ol class="breadcrumb float-sm-right">
									<li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
									<li class="breadcrumb-item active">Relación de Cobros Edv</li>
								</ol>
							</div>
						</div>
					</div>
				</section>
				<section class="content">
					<div class="row">
						<div class="col-12">
							<div class="card card-saint">
								<div class="card-header">
									<h3 class="card-title">Relación de Cobros Edv</h3>
								</div>
								<div class="card-body">
									<div class="card-body">
										<table id="example3" class="table table-bordered table-hover table-striped"> 
											<thead style="background-color: #00137f;color: white;">
												<tr class="text-center">
													<th>#</th>
													<th>Nro. Relación</th>
													<th>Vendedor</th>
													<th>Sucursal</th>
													<th>Emisión</th>
													<th>Monto</th>
													<th>PDF</th>
												</tr>
											</thead>
											<tbody>
												<?php
												for ($i=0; $i<mssql_num_rows($query); $i++) {                         
													?>

													<tr>
														<td class="text-center"><?= $i+1; ?></td>
														<td class="text-center"> <?= str_pad(mssql_result($query,$i,"NroRelacion"), 8,"0", STR_PAD_LEFT); ?> </td>
														<td class="text-center"> <?= utf8_encode(mssql_result($query,$i,"Vendedor")); ?> </td>
														<td class="text-center"> <?= utf8_encode(mssql_result($query,$i,"Sucursal")); ?> </td>
														<td class="text-center"> <?= date("d/m/Y", strtotime(mssql_result($query,$i,"FechaE"))); ?> </td>
														<td class="text-center"> <?= rdecimal(mssql_result($query,$i,"Monto"), 2); ?> </td>
														<td class="text-center"> 
															<!-- PDF -->
															<a href="relacion_cobro_edv_pdf.php?&i=<?= mssql_result($query,$i,"NroRelacion"); ?>" target="_blank"><img border="0" src="images/indicadores.png" width="19" height="18" /></a>
														</td>
													</tr>
													<?php
													$cont++;
												} ?>
											</tbody>
										</table>
										
										<?php 
									}else{
										echo "NO HAY REGISTROS DE DESPACHOS";
									} 
									?>
								</div>
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