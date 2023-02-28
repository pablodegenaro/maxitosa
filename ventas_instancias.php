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
						<li class="breadcrumb-item active">Ventas por Instancias</li>
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
						if (document.getElementById("fechai").value != "" && document.getElementById("fechaf").value != "" && document.getElementById("edv").value != "" && document.getElementById("insta").value != ""  && document.getElementById("sucursal").value != "" ){
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
					<h3 class="card-title">Ventas por Instancias</h3>
				</div>
				<form class="form-horizontal" action="principal1.php?page=ventas_instancias_ver&mod=1" method="post" id="" name="">
					<div class="card-body">
						<!-- Date -->
						<div class="form-group">
							<label>Seleccion</label>
							<div class="form-group row">
								<div class="form-group col-2">
									<input type="date" class="form-control" id="fechai" name="fechai" required>
								</div>
								<div class="form-group col-2">
									<input type="date" class="form-control" id="fechaf" name="fechaf" required>
								</div>
								<div class="form-group col-4">
									<select class="form-control custom-select" name="edv" id="edv" style="width: 100%;" required>
										<option value="">-- Seleccione un Vendedor --</option>
										<option value="-">TODOS LOS VENDEDORES</option>
										<?php 
										$vendedores= mssql_query("SELECT * from savend where activo = '1'");
										if (mssql_num_rows($vendedores) != 0){ 
											for($i=0;$i<mssql_num_rows($vendedores);$i++){
												?>                         
												<option value="<?php echo mssql_result($vendedores,$i,"codvend"); ?>"><?php echo mssql_result($vendedores,$i,"codvend"); ?>: <?php echo substr(utf8_encode(mssql_result($vendedores,$i,"descrip")), 0, 35); ?></option>
												<?php 
											}
										} ?>
									</select>
								</div>
								<div class="form-group col-4">
									<select class="form-control custom-select" name="insta" id="insta" style="width: 100%;" required>
										<option value="">-- Seleccione una Instancia --</option>
										<option value="-">TODAS LAS INSTANCIAS</option>
										<?php 
										$instancias= mssql_query("SELECT codinst, descrip from sainsta order by descrip");
										if (mssql_num_rows($instancias) != 0){ 
											for($i=0;$i<mssql_num_rows($instancias);$i++){
												?>                         
												<option value="<?php echo mssql_result($instancias,$i,"codinst"); ?>"><?php echo substr(mssql_result($instancias,$i,"descrip"), 0, 35); ?></option>
												<?php 
											}
										} ?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label>Sucursal</label>
									<div class="form-group">
										<select class="form-control custom-select" name="sucursal" id="sucursal" style="width: 100%;" required>
											<option value="">-- Seleccione Sucursal --</option>
											<option value="-">TODAS LAS SUCURSALES</option>
											<?php
											$sucur= mssql_query("select CodSucu, Descrip from SASUCURSAL");
											for($i=0;$i<mssql_num_rows($sucur);$i++){
												?>
												<option value="<?php echo mssql_result($sucur,$i,"CodSucu"); ?>"><?php echo mssql_result($sucur,$i,"Descrip"); ?></option>
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
	</div>
</div>
</section>

</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
