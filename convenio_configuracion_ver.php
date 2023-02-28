<?php 
require("conexion.php");
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
$aux = $_GET['id'];
if ($aux != ""){
	$consulta_user = mssql_query("SELECT *  FROM convenio_configuracion where descripcion = '$aux'");
	$convenio = mssql_result($consulta_user,0,"nivel_precio");
	$descripcion = mssql_result($consulta_user,0,"descripcion");
	$pbase = mssql_result($consulta_user,0,"precio_base");	
	$porcentaje = mssql_result($consulta_user,0,"porcentaje");
	$aplicacion = mssql_result($consulta_user,0,"tipo_aplicacion");
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
						<li class="breadcrumb-item active">Modificar Convenio</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="container">
			<div class="col-md-12">
				<div class="card card-saint">
					<script type="text/javascript">
						function guarda(){
							if (document.getElementById("pbase").value != "" ){
								document.forms["mod_convenio"].submit();
							}else{
								alert("Debe Rellenar Todos Los Campos");
							}
						}
						function regresa(){
							window.location.href = "principal1.php?page=convenio_configuracion&mod=1";
						}
					</script>
					<div class="card-header">
						<h3 class="card-title">Modificacion del Convenio <?php echo $aux; ?></h3>
					</div>
					<form class="form-horizontal" action="principal1.php?page=convenio_inserta&mod=1&id=<?php echo $aux; ?>" method="post" id="mod_convenio" name="mod_convenio">
						<input type="text" class="form-control" value="<?php echo $_SESSION['login']; ?>" id="usuario" name="usuario" hidden >
						<div class="card-body">
							<!-- Date -->
							<div class="card-body">
								<div class="form-group row">
									<label for="pbase" class="col-sm-2 col-form-label">Precio Base</label>
									<div class="col-sm-10">
										<select class="form-control custom-select" name="pbase" id="pbase" style="width: 100%;">
											<option name="" value="">--SELECCIONE UNA OPCION--</option>
											<option value="0"   <?php if ($pbase=='0') echo 'selected'; ?>>Manual</option>
											<option value="1" <?php if ($pbase=='1') echo 'selected'; ?>>Sur</option>
											<option value="2" <?php if ($pbase=='2') echo 'selected'; ?>>Casco</option>
											<option value="3" <?php if ($pbase=='3') echo 'selected'; ?>>Mayorista</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="porcentaje" class="col-sm-2 col-form-label">Porcentaje</label>
									<div class="col-sm-10">
										<input type="number" class="form-control" value="<?php echo $porcentaje; ?>" id="porcentaje" name="porcentaje" placeholder="Porcentaje" >
									</div>
								</div>
								<div class="form-group row">
									<label for="aplicacion" class="col-sm-2 col-form-label">Aplicacion</label>
									<div class="col-sm-10">
										<select class="form-control custom-select" name="aplicacion" id="aplicacion" style="width: 100%;">
											<option name="" value="">--SELECCIONE UNA OPCION--</option>
											<option value="0"   <?php if ($aplicacion=='0') echo 'selected'; ?>>No Aplica</option>
											<option value="1" <?php if ($aplicacion=='1') echo 'selected'; ?>>Incremento</option>
											<option value="2" <?php if ($aplicacion=='2') echo 'selected'; ?>>Decremento</option>
										</select>
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
	</div>
</section>
</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
