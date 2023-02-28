<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
						<li class="breadcrumb-item active">Factor de Cambio</li>
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
						if (document.getElementById("factor").value != "" && document.getElementById("sucursal").value != "" ){
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
					<h3 class="card-title">Factor Cambiario</h3>
				</div>
				<form class="form-horizontal" action="principal.php?page=cambiar_factor_ver&mod=1" method="post" id="" name="">
					<div class="card-body">
						<!-- Date -->
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<?php
									$buscafactor = mssql_query("SELECT factor from SACONF where CodSucu = 00000");
									for($j=0;$j<mssql_num_rows($buscafactor);$j++){
										$f = mssql_result($buscafactor,$j,"factor");
										?>
										<label>Factor Activo: <?php echo number_format($f, 2, '.', ',');}?> Bs</label>
										<div class="input-group" id="reservationdate" data-target-input="nearest">
											<input type="text" name="factor" id="factor" class="form-control" data-target="#reservationdate" required />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" name="Submit"   onclick="guarda()"  class="btn btn-saint">Actualizar</button>
							<button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
						</div>
					</form>
				</div>
			</div>
		</section>
	</div>
	<?php include "footer.php"; ?>
	<script src="Icons.js" type="text/javascript"></script>
