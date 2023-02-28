<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {

	$fechai = $_POST['fechai'];
	$fechaf = $_POST['fechaf'];
	$convend = $_POST['edv'];
	$inst = $_POST['insta'];
	$sucursal = $_POST['sucursal'];
	
	switch (true) {
		# =================================================
		# === UN TIPO DE SUCURSAL, INSTACIA y VENDEDOR ==== 
		# =================================================
		case ($sucursal!="-" && $inst!="-" && $convend!="-"):
		$productos = mssql_query("SELECT distinct coditem, saprod.Descrip FROM saitemfac 
			INNER JOIN saprod ON saitemfac.coditem = saprod.codprod 
			INNER JOIN sainsta ON saprod.codinst = sainsta.codinst 
			INNER JOIN safact ON saitemfac.numerod = safact.numerod 
			WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) BETWEEN '$fechai' and '$fechaf' AND (saitemfac.tipofac = 'A' OR saitemfac.tipofac = 'B')
			AND saprod.codinst = '$inst' AND saitemfac.codvend = '$convend' AND SAITEMFAC.CodSucu = '$sucursal' ORDER BY saitemfac.coditem");
		break;

		# =============================================================
		# === UN TIPO DE SUCURSAL E INSTACIA, TODOS LOS VENDEDORES ==== 
		# =============================================================
		case ($sucursal!="-" && $inst!="-" && $convend=="-"):
		$productos = mssql_query("SELECT distinct coditem, saprod.Descrip FROM saitemfac 
			INNER JOIN saprod ON saitemfac.coditem = saprod.codprod 
			INNER JOIN sainsta ON saprod.codinst = sainsta.codinst 
			INNER JOIN safact ON saitemfac.numerod = safact.numerod 
			WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) BETWEEN '$fechai' and '$fechaf' AND (saitemfac.tipofac = 'A' OR saitemfac.tipofac = 'B')
			AND saprod.codinst = '$inst' AND SAITEMFAC.CodSucu = '$sucursal' ORDER BY saitemfac.coditem");
		break;
		
		# ============================================================
		# === UN TIPO DE SUCURSAL Y VENDEDOR, TODAS LAS INSTACIAS ==== 
		# ============================================================
		case ($sucursal!="-" && $inst=="-" && $convend!="-"):
		$productos = mssql_query("SELECT distinct coditem, saprod.Descrip FROM saitemfac 
			INNER JOIN saprod ON saitemfac.coditem = saprod.codprod 
			INNER JOIN sainsta ON saprod.codinst = sainsta.codinst 
			INNER JOIN safact ON saitemfac.numerod = safact.numerod 
			WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) BETWEEN '$fechai' and '$fechaf' AND (saitemfac.tipofac = 'A' OR saitemfac.tipofac = 'B')
			AND saitemfac.codvend = '$convend' AND SAITEMFAC.CodSucu = '$sucursal' ORDER BY saitemfac.coditem");
		break;

		# =============================================================
		# === UN TIPO DE INSTACIA y VENDEDOR, TODAS LAS SUCURSALES ==== 
		# =============================================================
		case ($sucursal=="-" && $inst!="-" && $convend!="-"):
		$productos = mssql_query("SELECT distinct coditem, saprod.Descrip FROM saitemfac 
			INNER JOIN saprod ON saitemfac.coditem = saprod.codprod 
			INNER JOIN sainsta ON saprod.codinst = sainsta.codinst 
			INNER JOIN safact ON saitemfac.numerod = safact.numerod 
			WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) BETWEEN '$fechai' and '$fechaf' AND (saitemfac.tipofac = 'A' OR saitemfac.tipofac = 'B')
			AND saprod.codinst = '$inst' AND saitemfac.codvend = '$convend' ORDER BY saitemfac.coditem");
		break;

		# ===============================================================
		# === UN TIPO DE INSTACIA, TODAS LAS SUCURSALES y VENDEDORES ==== 
		# ===============================================================
		case ($sucursal=="-" && $inst!="-" && $convend=="-"):
		$productos = mssql_query("SELECT distinct coditem, saprod.Descrip FROM saitemfac 
			INNER JOIN saprod ON saitemfac.coditem = saprod.codprod 
			INNER JOIN sainsta ON saprod.codinst = sainsta.codinst 
			INNER JOIN safact ON saitemfac.numerod = safact.numerod 
			WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) BETWEEN '$fechai' and '$fechaf' AND (saitemfac.tipofac = 'A' OR saitemfac.tipofac = 'B')
			AND saprod.codinst = '$inst' ORDER BY saitemfac.coditem");
		break;

		# ===============================================================
		# === UN TIPO DE VENDEDOR, TODAS LAS SUCURSALES E INSTANCIAS ==== 
		# ===============================================================
		case ($sucursal=="-" && $inst=="-" && $convend!="-"):
		$productos = mssql_query("SELECT distinct coditem, saprod.Descrip FROM saitemfac 
			INNER JOIN saprod ON saitemfac.coditem = saprod.codprod 
			INNER JOIN sainsta ON saprod.codinst = sainsta.codinst 
			INNER JOIN safact ON saitemfac.numerod = safact.numerod 
			WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) BETWEEN '$fechai' and '$fechaf' AND (saitemfac.tipofac = 'A' OR saitemfac.tipofac = 'B')
			AND saitemfac.codvend = '$convend' ORDER BY saitemfac.coditem");
		break;

		# ==============================================================
		# === UN TIPO DE SUCURSAL; TODAS LAS INSTACIAS y VENDEDORES ==== 
		# ==============================================================
		case ($sucursal!="-" && $inst=="-" && $convend=="-"):
		$productos = mssql_query("SELECT distinct coditem, saprod.Descrip FROM saitemfac 
			INNER JOIN saprod ON saitemfac.coditem = saprod.codprod 
			INNER JOIN sainsta ON saprod.codinst = sainsta.codinst 
			INNER JOIN safact ON saitemfac.numerod = safact.numerod 
			WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) BETWEEN '$fechai' and '$fechaf' AND (saitemfac.tipofac = 'A' OR saitemfac.tipofac = 'B')
			AND SAITEMFAC.CodSucu = '$sucursal' ORDER BY saitemfac.coditem");
		break;

		# =====================================================
		# === TODAS LAS SUCURSALES, INSTACIAS y VENDEDORES ==== 
		# =====================================================
		default:
		$productos = mssql_query("SELECT distinct coditem, saprod.Descrip FROM saitemfac 
			INNER JOIN saprod ON saitemfac.coditem = saprod.codprod 
			INNER JOIN sainsta ON saprod.codinst = sainsta.codinst 
			INNER JOIN safact ON saitemfac.numerod = safact.numerod 
			WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) BETWEEN '$fechai' and '$fechaf' AND (saitemfac.tipofac = 'A' OR saitemfac.tipofac = 'B')
			ORDER BY saitemfac.coditem");
	}
	?>
	<div class="content-wrapper">
		<!-- BOX DE LA MIGA DE PAN -->
		<section class="content-header">
			<div class="container-fluid">
			<!-- 
				<div class="row mb-2">
					<div class="col-sm-6">
						<h2 id="title_permisos">Ultima Activacion Clientes</h2>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
							<li class="breadcrumb-item active">Ultima Activacion Clientes</li>
						</ol>
					</div>
				</div> 
			-->
		</div>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-12">
				<div class="card card-saint">
					<div class="card-header">
						<script type="text/javascript">
							function regresa(){
								window.location.href = "principal1.php?page=ventas_instancias&mod=1";
							}
						</script>
						<h3 class="card-title">Ventas por Instancias</h3>&nbsp;&nbsp;&nbsp;
						<button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
					</div>
					<div class="card-body">
						<!--  <table id="example2" class="table table-bordered table-hover"> -->
							<table id="example1" class="table table-sm table-bordered table-striped">
								<thead style="background-color: #00137f;color: white;">
									<tr>
										<th>Código Producto</th>
										<th>Descipción Producto</th>
										<th>Cantidad Cajas</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$suma = 0;
									for ($i=0; $i<mssql_num_rows($productos); $i++) 
									{
										$codprod = mssql_result($productos,$i,"coditem");
										$descrip = mssql_result($productos,$i,"descrip");
										switch (true) {
												# ======================================
												# === UN TIPO DE SUCURSAL Y VENDEDOR ===
												# ======================================
											case ($sucursal!="-" && $convend!="-"):
											$detalle = mssql_query("SELECT 
												SUM(CASE WHEN tipofac = 'A' and Esunid = '0' THEN saitemfac.cantidad ELSE 0 END) as Total_Bultos_F, 
												SUM(CASE WHEN tipofac = 'B' and Esunid = '0' THEN saitemfac.cantidad ELSE 0 END) as Total_Bultos_D, 
												SUM(CASE WHEN tipofac = 'A' and Esunid = '1' THEN saitemfac.cantidad/saprod.cantempaq ELSE 0 END) as Total_Paq_F, 
												SUM(CASE WHEN tipofac = 'B' and Esunid = '1' THEN saitemfac.cantidad/saprod.cantempaq ELSE 0 END) as Total_Paq_D  
												from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod 
												where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' 
												and SAITEMFAC.CodSucu = '$sucursal' and codvend = '$convend' AND coditem = '$codprod'
												and (saitemfac.tipofac = 'A' or saitemfac.tipofac = 'B')");
											break;

												# =================================================
												# === UN TIPO DE SUCURSAL, TODOS LOS VENDEDORES ===
												# =================================================
											case ($sucursal!="-" && $convend=="-"):
											$detalle = mssql_query("SELECT 
												SUM(CASE WHEN tipofac = 'A' and Esunid = '0' THEN saitemfac.cantidad ELSE 0 END) as Total_Bultos_F, 
												SUM(CASE WHEN tipofac = 'B' and Esunid = '0' THEN saitemfac.cantidad ELSE 0 END) as Total_Bultos_D, 
												SUM(CASE WHEN tipofac = 'A' and Esunid = '1' THEN saitemfac.cantidad/saprod.cantempaq ELSE 0 END) as Total_Paq_F, 
												SUM(CASE WHEN tipofac = 'B' and Esunid = '1' THEN saitemfac.cantidad/saprod.cantempaq ELSE 0 END) as Total_Paq_D  
												from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod 
												where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' 
												and SAITEMFAC.CodSucu = '$sucursal' AND coditem = '$codprod'
												and (saitemfac.tipofac = 'A' or saitemfac.tipofac = 'B')");
											break;

												# =================================================
												# === TODAS LAS SUCURSALES, UN TIPO DE VENDEDOR ===
												# =================================================
											case ($sucursal=="-" && $convend!="-"):
											$detalle = mssql_query("SELECT 
												SUM(CASE WHEN tipofac = 'A' and Esunid = '0' THEN saitemfac.cantidad ELSE 0 END) as Total_Bultos_F, 
												SUM(CASE WHEN tipofac = 'B' and Esunid = '0' THEN saitemfac.cantidad ELSE 0 END) as Total_Bultos_D, 
												SUM(CASE WHEN tipofac = 'A' and Esunid = '1' THEN saitemfac.cantidad/saprod.cantempaq ELSE 0 END) as Total_Paq_F, 
												SUM(CASE WHEN tipofac = 'B' and Esunid = '1' THEN saitemfac.cantidad/saprod.cantempaq ELSE 0 END) as Total_Paq_D  
												from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod 
												where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' 
												and codvend = '$convend' AND coditem = '$codprod'
												and (saitemfac.tipofac = 'A' or saitemfac.tipofac = 'B')");
											break;

												# =========================================
												# === TODAS LAS SUCURSALES Y VENDEDORES ===
												# =========================================
											default:
											$detalle = mssql_query("SELECT 
												SUM(CASE WHEN tipofac = 'A' and Esunid = '0' THEN saitemfac.cantidad ELSE 0 END) as Total_Bultos_F, 
												SUM(CASE WHEN tipofac = 'B' and Esunid = '0' THEN saitemfac.cantidad ELSE 0 END) as Total_Bultos_D, 
												SUM(CASE WHEN tipofac = 'A' and Esunid = '1' THEN saitemfac.cantidad/saprod.cantempaq ELSE 0 END) as Total_Paq_F, 
												SUM(CASE WHEN tipofac = 'B' and Esunid = '1' THEN saitemfac.cantidad/saprod.cantempaq ELSE 0 END) as Total_Paq_D  
												from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod 
												where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' 
												and coditem = '$codprod' 
												and (saitemfac.tipofac = 'A' or saitemfac.tipofac = 'B')");
										}	
										?>
										<tr>
											<td><?php echo $codprod; ?></td>
											<td><?php echo utf8_encode($descrip); ?></td>
											<td>
												<?php 
												echo rdecimal2((mssql_result($detalle,0,"Total_Bultos_F") - mssql_result($detalle,0,"Total_Bultos_D"))+(mssql_result($detalle,0,"Total_Paq_F") - mssql_result($detalle,0,"Total_Paq_D")));
												$aux = (mssql_result($detalle,0,"Total_Bultos_F") - mssql_result($detalle,0,"Total_Bultos_D"))+(mssql_result($detalle,0,"Total_Paq_F") - mssql_result($detalle,0,"Total_Paq_D"));
												$suma = $suma + $aux;
												?>
											</td>
											</tr> <?php 
										}?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<?php include "footer.php"; ?>
		<script src="Icons.js" type="text/javascript"></script>
		<?php
	} else {
		header('Location: index.php');
	}
?>