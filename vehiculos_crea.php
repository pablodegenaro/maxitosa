<?php 
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
$aux = $_GET['id'];
$placa = "";
$modelo = "";
$capacidad = "";
$volumen = "";
if ($aux != ""){
	$consulta_vehiculo = mssql_query("SELECT * from appVehiculo where id_vehiculos = '$aux'");
	$placa = mssql_result($consulta_vehiculo,0,"placa");
	$modelo = mssql_result($consulta_vehiculo,0,"modelo");
	$capacidadkg = mssql_result($consulta_vehiculo,0,"capacidad");
	$volumencm3 = mssql_result($consulta_vehiculo,0,"volumen");
}?>
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
						<li class="breadcrumb-item active">Crear Vehiculos</li>
					</ol>
				</div>
			</div>
		</div>
	</section>

	<!-- BOX DEL CONTENIDO DE LA VISTA FORMULARIO Y TABLA -->
	<section class="content">
		<div class="col-md-12">
			<div class="card card-saint">
				<script type="text/javascript">
					function guarda(){
						if (document.getElementById("placa").value != "" && document.getElementById("modelo").value != "" && document.getElementById("capacidadkg").value != ""){
							document.forms["registro_usuarios"].submit();
						}else{
							alert("Debe Rellenar Todos Los Campos");
						}
					}
					function regresa(){
						window.location.href = "principal1.php?page=vehiculos&mod=1";
					}
				</script>
				<div class="card-header">
					<h3 class="card-title">Creacion de Vehiculos</h3>
				</div>
				<form class="form-horizontal" action="principal1.php?page=vehiculos_inserta&mod=1&id=<?php echo $aux; ?>" method="post" id="registro_vehiculo" name="registro_vehiculo">
					<div class="card-body">
						<!-- Date -->
						<div class="card-body">
							<div class="form-group row">
								<label for="placa" class="col-sm-2 col-form-label">Placa</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" value="<?php echo $placa; ?>" id="placa" name="placa" placeholder="Placa del Camion" require>
								</div>
							</div>
							<div class="form-group row">
								<label for="modelo" class="col-sm-2 col-form-label">Modelo</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" value="<?php echo $modelo; ?>" id="modelo" name="modelo" placeholder="Modelo del Camion" requiere>
								</div>
							</div>
							<div class="form-group row">
								<label for="capacidadkg" class="col-sm-2 col-form-label">Capacidad Kg</label>
								<div class="col-sm-10">
									<input type="number" class="form-control" id="capacidadkg" value="<?php echo $capacidadkg; ?>" name="capacidadkg" placeholder="Capacidad Expresada en Kilogramos" requiere>
								</div>
							</div>
							<div class="form-group row">
								<label for="volumencm3" class="col-sm-2 col-form-label">Volumen</label>
								<div class="col-sm-10">
									<input type="number" class="form-control" id="volumencm3" value="<?php echo $volumencm3; ?>" name="volumencm3" placeholder="Volumen cm3">
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<button type="submit" name="Submit"   onclick="guarda()"  class="btn btn-saint">Guardar</button>
						<button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
