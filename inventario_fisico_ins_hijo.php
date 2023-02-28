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
						<li class="breadcrumb-item active">Inventario Fisico por Instancia Hijo</li>
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
						if ( document.getElementById("almacen").value != "" ){
						}else{
							alert("Debe Seleccionar un almacen");
						}
					}
					function regresa(){
						window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
					}
				</script>
				<div class="card-header">
					<h3 class="card-title">Inventario Fisico por Instancia Hijo</h3>
				</div>
				<form class="form-horizontal" action="principal1.php?page=inventario_fisico_ins_hijo_ver&mod=1" method="post" id="" name="">
					<div class="card-body">
						<div class="form-group">
							<div class="form-group row">
								<div class="col-sm-12">										

									<div class="form-check form-check-inline">
										<select class="form-control custom-select" name="instancia" id="instancia" style="width: 100%;" required>
											<option value="">Seleccione Instancia</option>
											<option value="-">TODAS</option>
											<?php 
											$instancia= mssql_query("SELECT distinct(B.Descrip), a.CodInst from saprod AS A LEFT JOIN SAINSTA AS B ON A.CodInst=B.CodInst where A.Activo = '1' AND InsPadre != 0  ORDER BY B.Descrip ASC");
											for($i=0;$i<mssql_num_rows($instancia);$i++){
												
												?>
												<option value="<?php echo mssql_result($instancia,$i,"CodInst"); ?>"><?php echo utf8_encode(mssql_result($instancia,$i,"Descrip")); ?></option>
												<?php
											} ?>
										</select>
									</div>
									<div class="form-check form-check-inline">
										<select class="form-control custom-select" name="almacen" id="almacen" style="width: 100%;" required>
											<option value="-">Seleccione Almacen</option>
											<?php 
											$almacen= mssql_query("SELECT CodUbic, Descrip from SADEPO where Clase !='SERVICIOS' order by Descrip ASC");
											for($i=0;$i<mssql_num_rows($almacen);$i++){
												?>
												<option value="<?php echo mssql_result($almacen,$i,"CodUbic"); ?>"><?php echo utf8_encode(mssql_result($almacen,$i,"descrip")); ?></option>
												<?php
											} ?>
										</select>
									</div>
									<br><br>
									<div class="form-group row">
										<div class="col-sm-10">
											<div class="form-group row ml-2 mt-1">
												<div class="custom-control custom-checkbox">
													<input class="custom-control-input" type="checkbox" id="existencia" value="1" name="existencia" checked="">
													<label for="existencia" class="custom-control-label">Con Existencia</label>
												</div>
											</div>
										</div>
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
