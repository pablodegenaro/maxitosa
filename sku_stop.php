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
						<li class="breadcrumb-item active">SKU STOP</li>
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
						if (document.getElementById("proveedor").value != "" ){
								/* document.forms["registro_usuarios"].submit();*/
						}else{
							alert("Debe Seleccionar un Proveedor");
						}
					}
					function regresa(){
						window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
					}
				</script>
				<div class="card-header">
					<h3 class="card-title">SKU STOP</h3>
				</div>
				<form class="form-horizontal" action="principal1.php?page=sku_stop_ver&mod=1" method="post" id="" name="">
					<div class="card-body">
						<div class="form-group">
							<label>Seleccion</label>
							<div class="form-group row">
								<div class="col-sm-12">
									<div class="form-check form-check-inline">
										<select class="form-control custom-select" name="proveedor" id="proveedor" style="width: 100%;" required>
											<option value="">Seleccione Proveedor</option>
											<option value="-">TODOS</option>
											<?php 
											$proveedor= mssql_query("SELECT distinct(proveedor) from SAPROD_99 where proveedor is not null order by proveedor asc");
											for($i=0;$i<mssql_num_rows($proveedor);$i++){
												?>
												<option value="<?php echo mssql_result($proveedor,$i,"proveedor"); ?>"><?php echo utf8_encode(mssql_result($proveedor,$i,"proveedor")); ?></option>
												<?php
											} ?>
										</select>
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
