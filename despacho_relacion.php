<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
	$consultadespacho= mssql_query("SELECT TOP 100 * from appfacturas  inner join SSUSRS on appfacturas.usuario = SSUSRS.CodUsua order by correl desc");

	?>
	<script>				
		function elimna_des(correl){
			if(confirm("Esta Seguro de Eliminar este Despacho nro "+ correl)){
				location.href = "principal.php?&page=despachoc_elimina&mod=1&id="+correl;
			}
		}
	</script>	
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
			<section class="content">
				<div class="row">
					<div class="col-12">
						<div class="card card-saint">
							<div class="card-header">
								<h3 class="card-title">Relacion de Despachos</h3>
								&nbsp;&nbsp;&nbsp;
								<a href="principal.php?page=relacion_despachos&mod=1"></a>
								&nbsp;&nbsp;&nbsp;
								<a href="principal.php?page=vehiculos&mod=1"></a>
								&nbsp;&nbsp;&nbsp;
								<a href="principal.php?page=choferes&mod=1"></a>
							</div>
							<div class="card-body">
								<div class="card-body">
									<table id="example1" class="table table-sm table-bordered table-striped">
										<tr class="ui-widget-header" style="background-color: #00137f;color: white;">
											<td width="36" height="22"><div align="center"><strong># Despacho</strong></div></td>
											<td width="36" height="22"><div align="center"><strong>Fecha</strong></div></td>
											<td width="98"><div align="center"><strong>Usuario</strong></div></td>
											<td width="69"><div align="center"><strong>Cantidad Doc</strong></div></td>
											<td width="189"><div align="center"><strong>Destino</strong></div></td>
											<td width="86"><div align="center"><strong>Editar</strong></div></td>
											<td width="53"><div align="center"><strong>Eliminar</strong></div></td>
											<td width="62"><div align="center"><strong>Detalle</strong></div></td>
											<td width="46"><div align="center"><strong>PDF</strong></div></td>
										</tr>
										<?php for($i=0;$i<mssql_num_rows($consultadespacho);$i++){ ?>
											<tr >
												<td>
													<div align="center">
														<div align="center"><?php echo mssql_result($consultadespacho,$i,"correl"); ?></div>
													</div>
												</td>
												<td>
													<div align="center">
														<div align="center"><?php echo date("d/m/Y", strtotime(mssql_result($consultadespacho,$i,"fechad"))); ?></div>
													</div>
												</td>
												<td>
													<div align="center"><div align="center"><?php echo mssql_result($consultadespacho,$i,"descrip"); ?></div>
												</div>
											</td>
											<td>
												<div align="center">
													<div align="center">
														<?php 
														$corr = mssql_result($consultadespacho,$i,"correl");
														$consul_cant_fact = mssql_query("SELECT numeros from appfacturas_det where correl = '$corr'");
														echo mssql_num_rows($consul_cant_fact);
														?>
													</div>
												</td>
												<td>
													<div align="center">
														<div align="center"><?php 
														echo utf8_encode(mssql_result($consultadespacho,$i,"nota"));
														$cedula = mssql_result($consultadespacho,$i,"cedula_chofer"); 
														$consul_chofer = mssql_query("SELECT descripcion from appChofer where cedula = '$cedula'");
														if (mssql_num_rows($consul_chofer) != 0){
															echo " - ".mssql_result($consul_chofer,0,"descripcion");
														}
														?>
													</div>
												</td>
												<td>
													<div align="center">
														<div align="center">
															<!-- EDITAR DESPACHO -->
															<a href="principal1.php?&page=despacho_visual&mod=1&correl2=<?php echo mssql_result($consultadespacho,$i,"correl"); ?>&usuario=<?php echo  $_SESSION['login']; ?>"> <img src="images/edt.png" width="19" height="18" border="0" />
															</a>
														</div>
													</div>
												</td>
												<td>
													<div align="center">
														<!-- ELIMINAR DESPACHO -->
														<a href="#" onclick="elimna_des('<?php echo mssql_result($consultadespacho,$i,"correl"); ?>','<?php echo mssql_result($consultadespacho,$i,"correl"); ?>')"> <img src="images/cancel.png" width="15" height="15" border="0" />
														</a>
													</div>
												</td>
												<td>
													<div align="center">
														<!-- IMPRIMIR DESPACHO -->
														<a href="pdf_despacho.php?&correl=<?php echo mssql_result($consultadespacho,$i,"correl"); ?>" target="_blank"><img border="0" src="images/imp.gif" width="19" height="18" />
														</a>
													</div>
												</td>
												<td>
													<div align="center">
														<!-- IMPRIMIR DETALLE DE DESPACHO -->
														<a href="pdf_despacho1.php?&correl=<?php echo mssql_result($consultadespacho,$i,"correl"); ?>" target="_blank"><img border="0" src="images/indicadores.png" width="19" height="18" />
														</a>
													</div>
												</td>											
											</tr>
										<?php } ?>										
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