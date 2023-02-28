<?php 
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
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
						<li class="breadcrumb-item active">Cambiar Clave de Usuario</li>
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
						if (document.getElementById("clave_actual").value != "" && document.getElementById("clave_nueva").value != "" ){
							document.forms["cambio_clave"].submit();
						}else{
							alert("Debe Rellenar Ambos Campos");
						}
					}
					function regresa(){
						window.location.href = "principal1.php?page=user_clave&mod=1";
					}
				</script>
				<div class="card-header">
					<h3 class="card-title">Cambio de Clave para el Usuario : <?php echo $_SESSION['login']; ?></h3>
				</div>
				<form class="form-horizontal" action="principal1.php?page=user_clave_ver&mod=1" method="post" id="cambio_clave" name="cambio_clave">
					<div class="card-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="placa" class="col-sm-2 col-form-label">Clave Actual</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="clave_actual" name="clave_actual" placeholder="Ingrese su Clave Actual" require>
								</div>
							</div>
							<div class="form-group row">
								<label for="modelo" class="col-sm-2 col-form-label">Clave Nueva</label>
								<div class="col-sm-10">
									<input type="text" class="form-control"  id="clave_nueva" name="clave_nueva" placeholder="Ingrese su nueva Clave" requiere>
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
