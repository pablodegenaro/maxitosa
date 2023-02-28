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
						<li class="breadcrumb-item active">Disponible Almacen Todas las Sucursales</li>
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
						if (document.getElementById("marca").value != "" && document.getElementById("instancia").value != "" && document.getElementById("proveedor").value != "" ){
								/* document.forms["registro_usuarios"].submit();*/
						}else{
							alert("Debe Seleccionar una Marca");
						}
					}
					function regresa(){
						window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
					}
				</script>
				<div class="card-header">
					<h3 class="card-title">Disponible Almacen Todas las Sucursales</h3>
				</div>
				<form class="form-horizontal" action="principal1.php?page=disponible_almacen_ver2&mod=1" method="post" id="" name="">
					<div class="card-body">
						<!-- Date -->
						<div class="form-group">
							<label>Seleccion</label>
							<div class="form-group row">
								<div class="col-sm-12">
									<div class="form-check form-check-inline">
										<select class="form-control custom-select" name="instancia" id="instancia" style="width: 100%;" required>
											<option value="">Seleccione Instancia</option>
											<option value="-">TODAS</option>
											<?php 
											$instancia= mssql_query("SELECT distinct(B.Descrip), b.CodInst from saprod AS A LEFT JOIN SAINSTA AS B ON A.CodInst=B.CodInst where A.Activo = '1' AND InsPadre != 0  ORDER BY B.Descrip ASC");
											for($i=0;$i<mssql_num_rows($instancia);$i++){
												?>
												<option value="<?php echo mssql_result($instancia,$i,"CodInst"); ?>"><?php echo utf8_encode(mssql_result($instancia,$i,"Descrip")); ?></option>
												<?php
											} ?>
										</select>
										
									</div>
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
										
									</div>
									<div class="form-check form-check-inline">
										<select class="form-control custom-select" name="marca" id="marca" style="width: 100%;" required>
											<option value="">Seleccione Marca</option>
											<option value="-">TODAS</option>
											<?php 
											$marca= mssql_query("SELECT distinct(marca) from saprod where activo = '1' and marca is not null order by marca asc");
											for($i=0;$i<mssql_num_rows($marca);$i++){
												?>
												<option value="<?php echo mssql_result($marca,$i,"marca"); ?>"><?php echo utf8_encode(mssql_result($marca,$i,"marca")); ?></option>
												<?php
											} ?>
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
