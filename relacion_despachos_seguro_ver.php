<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {

	$fechai = $_POST['fechai'];
	$fechaf = $_POST['fechaf'];

	$consultadespacho= mssql_query("SELECT a.correl, a.fechad,a.nota,'' as empresa_transporte, a.placa from appfacturas as a  inner join SSUSRS as b on a.usuario = b.CodUsua where DATEADD(dd, 0, DATEDIFF(dd, 0, a.fechad))
		between '$fechai' and '$fechaf'  order by correl desc");
		?>

		<div class="content-wrapper">
			<?php 	if (mssql_num_rows($consultadespacho)!=0){ ?>
				<section class="content-header">					
					<div class="container-fluid">
						<div class="row mb-2">
							<div class="col-sm-6">
								<!--  <h2 id="title_permisos">Ultimaa Activacion Clientes</h2> -->
							</div>
							<div class="col-sm-6">
								<ol class="breadcrumb float-sm-right">
									<li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
									<li class="breadcrumb-item active">Relacion de Despachos</li>
								</ol>
							</div>
						</div>
					</div>
				</section>
				<!-- BOX DEL CONTENIDO DE LA VISTA FORMULARIO Y TABLA -->
				<section class="content">
					<div class="row">
						<div class="col-12">
							<div class="card card-saint">
								<div class="card-header">
									<script type="text/javascript">
										function regresa(){
											window.location.href = "principal1.php?page=relacion_despachos_seguro&mod=1";
										}
									</script>
									<h3 class="card-title">Relacion Despachos Seguro</h3>&nbsp;&nbsp;&nbsp;
									<button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
								</div>
								<div class="card-body">
									<div class="card-body">
										<table id="example3" class="table table-sm table-bordered table-striped">
											<thead style="background-color: #00137f;color: white;">
												<th>Nro. Guia</th>
												<th>Fecha Salida</th>
												<th>Lugar Salida</th>
												<th>Empresa Transporte</th>
												<th>Placa</th>
												<th>Nro. Factura</th>
												<th>Clase Mercancia</th>
												<th>Fecha Llegada</th>
												<th>Destinatario</th>
												<th>Valor Carga US $</th>
												<th>Valor Asegurdado US $</th>
											</thead>
											<tbody>
												<?php

												for($i=0;$i<mssql_num_rows($consultadespacho);$i++){ 
													$correl = mssql_result($consultadespacho, $i, "correl");
													$listfacturas = mssql_query("SELECT numeros, tipofac from appfacturas_det where correl ='$correl'");
													?>
													<tr >
														<td><?php echo mssql_result($consultadespacho, $i, "correl"); ?></td>
														<td><?php echo date("d/m/Y", strtotime(mssql_result($consultadespacho,$i,"fechad"))); ?></td>
														<td><?php echo mssql_result($consultadespacho, $i, "nota"); ?></td>
														<td>TRANSPORTE PROPIO</td>
														<td><?php echo mssql_result($consultadespacho, $i, "placa"); ?></td>
														<td><?php 
														$montod=0;
														while ($row = mssql_fetch_assoc($listfacturas)) {
															$numero= $row['numeros'];
															$tipofac= $row['tipofac'];
															$montofac = mssql_query("SELECT mtototal/factorp as montod from safact where numerod ='$numero' and tipofac ='$tipofac'");
															$montod +=  mssql_result($montofac, $j, "montod");
															echo $row['numeros'] . ' ,';
														} ?></td>
														<td>Licores Varios</td>
														<td><?php echo date("d/m/Y", strtotime(mssql_result($consultadespacho,$i,"fechad"))); ?></td>
														<td><?php echo mssql_result($consultadespacho, $i, "nota"); ?></td>
														<td><?php echo rdecimal2($montod); ?></td>
														<td>10.000,00</td>
													</tr>
												<?php } ?>	
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