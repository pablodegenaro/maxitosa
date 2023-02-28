<?php 
require("conexion.php");
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
$aux = $_GET['id'];
$cedula = "";
$descrip = "";
$pass = "";
if ($aux != ""){
	$consulta_user = mssql_query("SELECT * from appChofer where id_chofer = '$aux'");
	$cedula = mssql_result($consulta_user,0,"cedula");
	$descrip = mssql_result($consulta_user,0,"descripcion");
}
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
						<li class="breadcrumb-item active">Crear Chofer</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="col-md-12">
			<div class="card card-saint">
				<script type="text/javascript">
					function guarda(){
						if (document.getElementById("cedula").value != "" ){
							document.forms["registro_chofer"].submit();
						}else{
							alert("Debe Rellenar Todos Los Campos");
						}
					}
					function regresa(){
						window.location.href = "principal1.php?page=choferes&mod=1";
					}
				</script>
				<div class="card-header">
					<h3 class="card-title">Creacion de Choferes</h3>
				</div>
				<form class="form-horizontal" action="principal1.php?page=choferes_inserta&mod=1&id=<?php echo $aux; ?>" method="post" id="registro_chofer" name="registro_chofer">
					<div class="card-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="cedula" class="col-sm-2 col-form-label">Cedula</label>
								<div class="col-sm-10">
									<input type="number" class="form-control" value="<?php echo $cedula; ?>" id="cedula" name="cedula" placeholder="Cedula del Chofer" require>
								</div>
							</div>
							<div class="form-group row">
								<label for="chofer" class="col-sm-2 col-form-label">Nombre y Apellido</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" value="<?php echo $descrip; ?>" id="chofer" name="chofer" placeholder="Nombre y Apellido del Chofer" requiere>
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
