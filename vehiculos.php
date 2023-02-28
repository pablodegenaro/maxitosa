<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
	?>
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<!--  <h2 id="title_permisos">Ultima Activacion Clientes</h2> -->
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
							<li class="breadcrumb-item active">Vehiculos</li>
						</ol>
					</div>
				</div>
			</div>
		</section>
		<script type="text/javascript">
			function elimina(id){
				if(confirm("Esta Seguro de Desactivar a Este Vehiculo?")){
					location.href="principal.php?page=vehiculos_elimina&mod=1&id="+id;
				}
			}
		</script>
		<section class="content">
			<div class="row">
				<div class="col-12">
					<div class="card card-saint">
						<div class="card-header">
							<h3 class="card-title">Relacion De Vehiculos</h3>&nbsp;&nbsp;&nbsp;
						</div>
						<div class="card-body">
							<?php 
							$consulta_vehiculo = mssql_query("SELECT * from appVehiculo");
							?>
							<a href="principal1.php?page=vehiculos_crea&mod=1">Agregar Nuevo Vehiculo </a> 
							<table id="example2" class="table table-sm table-bordered table-hover">
								<thead style="background-color: #00137f;color: white;">
									<tr>
										<th>Placa</th>
										<th>Modelo</th>
										<th>Capacidad Kg</th>
										<th>Volumen</th>
										<th>Editar</th>
										<th>Eliminar</th>
									</tr>
								</thead>
								<tbody>
									<?php for ($i = 0; $i < mssql_num_rows($consulta_vehiculo); $i++) {
										?>
										<tr>
											<td><div align="center"><?php echo mssql_result($consulta_vehiculo,$i,"placa"); ?></div></td>
											<td><div align="center"><?php echo mssql_result($consulta_vehiculo,$i,"modelo"); ?></div></td>
											<td><div align="center"><?php echo mssql_result($consulta_vehiculo,$i,"capacidad"); ?></div></td>
											<td><div align="center"><?php echo mssql_result($consulta_vehiculo,$i,"volumen"); ?></div></td>
											<td><div align="center"><a href="principal1.php?page=vehiculos_crea&mod=1&id=<?php echo mssql_result($consulta_vehiculo,$i,"id_vehiculos"); ?>"><img src="images/edt.png" border="0" width="20" height="18" /></a></div></td>
											<td><div align="center"><a href="javascript:;" onClick="elimina('<?php echo mssql_result($consulta_vehiculo,$i,"id_vehiculos"); ?>')"><img src="images/cancel.png" border="0" width="20" height="18" /></a></div></td>
										</tr>
									<?php }?>
								</tbody>
								<tfoot>
									<tr>
										<th>Placa</th>
										<th>Modelo</th>
										<th>Capacidad</th>
										<th>Volumen</th>
										<th>Editar</th>
										<th>Eliminar</th>
									</tr>
								</tfoot>
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