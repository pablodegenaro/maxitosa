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
						<li class="breadcrumb-item active">Geolocalizacion de Clientes</li>
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
						if (document.getElementById("edv").value != "" && document.getElementById("opc").value != "" ){
							/* document.forms["registro_usuarios"].submit();*/
						}else{
							alert("Debe Rellenar Todos Los Campos");
						}
					}
					function regresa(){
						window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
					}
				</script>
				<div class="card-header">
					<h3 class="card-title">Geolocalizacion de Clientes</h3>
				</div>
				<form class="form-horizontal" action="geolocalizacion_ver.php" method="post" id="" name="" target="_blank">
					<div class="card-body">
						<!-- Date -->
						<div class="form-group">
							<label>Seleccion</label>
							<div class="form-group row">
								<div class="col-sm-12">
									<div class="form-check form-check-inline">
										<select class="form-control custom-select" name="edv" id="edv" required>
											<option value="">Seleccione</option>
											<option value="Todos">Todos</option>
											<?php 
											$vendedores= mssql_query("SELECT * from savend where activo = '1' ");
											if (mssql_num_rows($vendedores) != 0){ 
												for($i=0;$i<mssql_num_rows($vendedores);$i++){
													?>
													<option value="<?php echo mssql_result($vendedores,$i,"codvend"); ?>"><?php echo mssql_result($vendedores,$i,"codvend"); ?>: <?php echo utf8_encode(substr(mssql_result($vendedores,$i,"descrip"), 0, 35)); ?></option>
													<?php 
												}
											}
											?>
										</select>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
										<select class="form-control custom-select" name="opc" id="opc" style="width: 100%;" required>
											<option value="">Seleccione</option>
											<option value="0">Todos</option>
											<option value="1">Lunes</option>
											<option value="2">Martes</option>
											<option value="3">Miercoles</option>
											<option value="4">Jueves</option>
											<option value="5">Viernes</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<button type="submit" name="Submit"   onclick="guarda()"  class="btn btn-saint">Procesar</button>
						<button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
					</div>
				</form>
			</div>
		</div>
	</section>

</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
