<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
	set_time_limit(0);

	$edv = $_POST['edv'];
	$sucursal = $_POST['sucursal'];
	$fechai = $_POST['fechai'].' 00:00:00';
	$fechaf = $_POST['fechaf'].' 23:59:59';
	$fechaii = normalize_date($_POST['fechai']);
	$fechaff = normalize_date($_POST['fechaf']);


	switch (true) {
    # =============================================================
    # ======= UN VENDEDOR Y UNA SUCURSAL
    # =============================================================
		case ( $edv!="-" &&  $sucursal!="-"):

		$consulta = mssql_query("
			SELECT
			SAITEMFAC.fechae,
			DATENAME(month, saitemfac.fechae) as MES,
			SAITEMFAC.tipofac AS tipo,
			SAITEMFAC.numerod as numerod,
			c.formato_cliente,
			c.formato_cliente_2,
			(select codclie from SAFACT where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as codclie,
			(select Descrip from SAFACT where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as cliente,
			(select saclie.clase from SAFACT inner join saclie on SAFACT.codclie = saclie.codclie where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as clase,
			(select codvend from savend where savend.codvend = SAITEMFAC.codvend) as codvend,
			(select descrip from savend where savend.codvend = SAITEMFAC.codvend) as vendedor,
			d.proveedor,
			d.clasificacion_categoria,
			d.sub_clasificacion_categoria,
			(select marca from SAPROD where SAITEMFAC.coditem = SAPROD.CodProd) as marca,
			saprod.refere,
			SAITEMFAC.coditem,
			SAITEMFAC.Descrip1 as descripcion,
			SAITEMFAC.cantidad,
			(CASE SAITEMFAC.EsUnid WHEN 1 then 'PAQ' ELSE 'BULT' END) AS unid,
			(CASE SAITEMFAC.EsUnid WHEN 1 then cantidad ELSE cantidad*cantempaq END) AS paq,
			(CASE SAITEMFAC.EsUnid WHEN 1 then cantidad/cantempaq ELSE cantidad END) AS bul,
			SAITEMFAC.TotalItem as montod,
			SAITEMFAC.MTOTAX as impuesto,
			(select factorp from SAFACT where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as factor	, a.Notas1, a.Notas2
			from SAITEMFAC inner join saprod on SAITEMFAC.coditem = saprod.codprod 
			inner join SAFACT as a on SAITEMFAC.NumeroD=a.NumeroD and SAITEMFAC.TipoFac=a.TipoFac
			left join SACLIE_99 as c on a.CodClie=c.CodClie
			left join SAPROD_99 as d on SAITEMFAC.CodItem=d.CodProd
			where 
			DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMFAC.FechaE)) between '$fechai' and '$fechaf'  and SAITEMFAC.CodSucu='$sucursal' and  SAITEMFAC.codvend like '$edv' and (SAITEMFAC.tipofac = 'C' or SAITEMFAC.Tipofac = 'D')  order by SAITEMFAC.fechae");

		break;

    # ====================================================================
    # ===  ==== TODOS LOS VENDEDORES Y UNA SUCURSAL
    # ====================================================================
		case ( $edv="-" &&  $sucursal!="-"):
		$consulta = mssql_query("
			SELECT
			SAITEMFAC.fechae,
			DATENAME(month, saitemfac.fechae) as MES,
			SAITEMFAC.tipofac AS tipo,
			SAITEMFAC.numerod as numerod,
			c.formato_cliente,
			c.formato_cliente_2,
			(select codclie from SAFACT where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as codclie,
			(select Descrip from SAFACT where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as cliente,
			(select saclie.clase from SAFACT inner join saclie on SAFACT.codclie = saclie.codclie where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as clase,
			(select codvend from savend where savend.codvend = SAITEMFAC.codvend) as codvend,
			(select descrip from savend where savend.codvend = SAITEMFAC.codvend) as vendedor,
			d.proveedor,
			d.clasificacion_categoria,
			d.sub_clasificacion_categoria,
			(select marca from SAPROD where SAITEMFAC.coditem = SAPROD.CodProd) as marca,
			saprod.refere,
			SAITEMFAC.coditem,
			SAITEMFAC.Descrip1 as descripcion,
			SAITEMFAC.cantidad,
			(CASE SAITEMFAC.EsUnid WHEN 1 then 'PAQ' ELSE 'BULT' END) AS unid,
			(CASE SAITEMFAC.EsUnid WHEN 1 then cantidad ELSE cantidad*cantempaq END) AS paq,
			(CASE SAITEMFAC.EsUnid WHEN 1 then cantidad/cantempaq ELSE cantidad END) AS bul,
			SAITEMFAC.TotalItem as montod,
			SAITEMFAC.MTOTAX as impuesto,
			(select factorp from SAFACT where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as factor	, a.Notas1, a.Notas2		
			from SAITEMFAC inner join saprod on SAITEMFAC.coditem = saprod.codprod 
			inner join SAFACT as a on SAITEMFAC.NumeroD=a.NumeroD and SAITEMFAC.TipoFac=a.TipoFac
			left join SACLIE_99 as c on a.CodClie=c.CodClie
			left join SAPROD_99 as d on SAITEMFAC.CodItem=d.CodProd
			where 
			DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMFAC.FechaE)) between '$fechai' and '$fechaf'   and SAITEMFAC.CodSucu='$sucursal' and  (SAITEMFAC.tipofac = 'C' or SAITEMFAC.Tipofac = 'D')  order by SAITEMFAC.fechae");

		break;

 # =============================================================================
    # ===  ==== TODOS LOS VENDEDORES Y TODAS LAS SUCURSALES
    # =============================================================================
		case ( $edv="-" &&  $sucursal="-"):

		$consulta = mssql_query("
			SELECT
			SAITEMFAC.fechae,
			DATENAME(month, saitemfac.fechae) as MES,
			SAITEMFAC.tipofac AS tipo,
			SAITEMFAC.numerod as numerod,
			c.formato_cliente,
			c.formato_cliente_2,
			(select codclie from SAFACT where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as codclie,
			(select Descrip from SAFACT where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as cliente,
			(select saclie.clase from SAFACT inner join saclie on SAFACT.codclie = saclie.codclie where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as clase,
			(select codvend from savend where savend.codvend = SAITEMFAC.codvend) as codvend,
			(select descrip from savend where savend.codvend = SAITEMFAC.codvend) as vendedor,
			d.proveedor,
			d.clasificacion_categoria,
			d.sub_clasificacion_categoria,
			(select marca from SAPROD where SAITEMFAC.coditem = SAPROD.CodProd) as marca,
			saprod.refere,
			SAITEMFAC.coditem,
			SAITEMFAC.Descrip1 as descripcion,
			SAITEMFAC.cantidad,
			(CASE SAITEMFAC.EsUnid WHEN 1 then 'PAQ' ELSE 'BULT' END) AS unid,
			(CASE SAITEMFAC.EsUnid WHEN 1 then cantidad ELSE cantidad*cantempaq END) AS paq,
			(CASE SAITEMFAC.EsUnid WHEN 1 then cantidad/cantempaq ELSE cantidad END) AS bul,
			SAITEMFAC.TotalItem as montod,
			SAITEMFAC.MTOTAX as impuesto,
			(select factorp from SAFACT where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as factor		, a.Notas1, a.Notas2	
			from SAITEMFAC inner join saprod on SAITEMFAC.coditem = saprod.codprod 
			inner join SAFACT as a on SAITEMFAC.NumeroD=a.NumeroD and SAITEMFAC.TipoFac=a.TipoFac
			left join SACLIE_99 as c on a.CodClie=c.CodClie
			left join SAPROD_99 as d on SAITEMFAC.CodItem=d.CodProd
			where 
			DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMFAC.FechaE)) between '$fechai' and '$fechaf'  and   (SAITEMFAC.tipofac = 'C' or SAITEMFAC.Tipofac = 'D')  order by SAITEMFAC.fechae");

		break;
    # =============================================================================
    # ===  ==== UN VENDEDOR Y TODAS LAS SUCURSALES
    # =============================================================================
		case ( $edv!="-" &&  $sucursal="-"):

		$consulta = mssql_query("
			SELECT
			SAITEMFAC.fechae,
			DATENAME(month, saitemfac.fechae) as MES,
			SAITEMFAC.tipofac AS tipo,
			SAITEMFAC.numerod as numerod,
			c.formato_cliente,
			c.formato_cliente_2,
			(select codclie from SAFACT where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as codclie,
			(select Descrip from SAFACT where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as cliente,
			(select saclie.clase from SAFACT inner join saclie on SAFACT.codclie = saclie.codclie where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as clase,
			(select codvend from savend where savend.codvend = SAITEMFAC.codvend) as codvend,
			(select descrip from savend where savend.codvend = SAITEMFAC.codvend) as vendedor,
			d.proveedor,
			d.clasificacion_categoria,
			d.sub_clasificacion_categoria,
			(select marca from SAPROD where SAITEMFAC.coditem = SAPROD.CodProd) as marca,
			saprod.refere,
			SAITEMFAC.coditem,
			SAITEMFAC.Descrip1 as descripcion,
			SAITEMFAC.cantidad,
			(CASE SAITEMFAC.EsUnid WHEN 1 then 'PAQ' ELSE 'BULT' END) AS unid,
			(CASE SAITEMFAC.EsUnid WHEN 1 then cantidad ELSE cantidad*cantempaq END) AS paq,
			(CASE SAITEMFAC.EsUnid WHEN 1 then cantidad/cantempaq ELSE cantidad END) AS bul,
			SAITEMFAC.TotalItem as montod,
			SAITEMFAC.MTOTAX as impuesto,
			(select factorp from SAFACT where SAFACT.numerod = SAITEMFAC.numerod and SAFACT.tipofac = SAITEMFAC.tipofac) as factor		, a.Notas1, a.Notas2	
			from SAITEMFAC inner join saprod on SAITEMFAC.coditem = saprod.codprod 
			inner join SAFACT as a on SAITEMFAC.NumeroD=a.NumeroD and SAITEMFAC.TipoFac=a.TipoFac
			left join SACLIE_99 as c on a.CodClie=c.CodClie
			left join SAPROD_99 as d on SAITEMFAC.CodItem=d.CodProd
			where 
			DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMFAC.FechaE)) between '$fechai' and '$fechaf'  and  SAITEMFAC.codvend like '$edv' and (SAITEMFAC.tipofac = 'C' or SAITEMFAC.Tipofac = 'D')  order by SAITEMFAC.fechae");

		break;
		default:
	}
	?>
	<div class="content-header">
		<div class="container">
		</div>
	</div>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Tabla Dinamica de Notas de Entrega</h1>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-3 mt-4 form-check-inline">
					</div>
					<div class="col-sm-3 mt-4 form-check-inline">
						<dt class="col-sm-3 text-gray">Desde:</dt>
						<input type="text" class="form-control-sm col-8 text-center" id="fechai" value="<?php echo $fechaii; ?>" readonly>
					</div>
					<div class="col-sm-3 mt-4 form-check-inline">
						<dt class="col-sm-4 text-gray">Hasta:</dt>
						<input type="text" class="form-control-sm col-sm-8 text-center" id="fechaf" value="<?php echo $fechaff; ?>" readonly>&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<script type="text/javascript">
							function regresa(){
								window.location.href = "principal1.php?page=tabla_dinamica_ne&mod=1";
							}
						</script>
						<button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
					</div>
				</div>
			</div>
		</div>
		<div class="content">
			<!-- <table id="example2" class="table table-bordered table-hover"> -->
				<table id="example3" class="table table-sm text-center  table-bordered table-striped table-responsive p-0" style="width:100%;">
					<thead style="background-color: #00137f;color: white;">
						<tr id="cells">
							<th align="center">Nro. Ope</th>
							<th align="center" width="100">Fecha</th>
							<th align="center" width="100">Mes</th>
							<th align="center" width="50">Ope</th>
							<th align="center" width="100">Numero</th>
							<th align="center" width="100">Clasificacion Cliente</th>
							<th align="center" width="100">Sub Clasificacion Cliente</th>
							<th align="center" width="100">CodClie</th>
							<th align="center" width="100">Cliente</th>
							<th align="center" width="200">Clase</th>
							<th align="center" width="100">CodVend</th>
							<th align="center" width="100">Vendedor</th>
							<th align="center" width="100">Proveedor</th>
							<th align="center" width="100">Clasificacion 1</th>
							<th align="center" width="100">Clasificacion 2</th>
							<th align="center" width="200">Marca</th>
							<th align="center" width="200">Codigo Barra</th>
							<th align="center" width="100">CodProd</th>
							<th align="center" width="100">Producto</th>
							<th align="center" width="100">Cantidad</th>
							<th align="center" width="150">Unidad</th>
							<th align="center" width="50">Paquete</th>
							<th align="center" width="150">Bulto</th>							
							<th align="center" width="100">Monto</th>
							<th align="center" width="100">Factor</th>
							<th align="center" width="100">Notas 1</th>
							<th align="center" width="100">Notas 2</th>
						</tr>
					</thead>
					<tbody style="background-color: aliceblue">
						<?php
						$paq = 0;
						$bult = 0;
						$kilo = 0;
						$total = 0;
						for ($i = 0; $i < mssql_num_rows($consulta); $i++) {?>
							<tr >
								<td><?php echo $i + 1; ?></td>
								<td><?php echo date("d/m/Y", strtotime(mssql_result($consulta, $i, "fechae"))); ?></td>
								<td><?php echo mssql_result($consulta,$i,"MES"); ?></td>
								<td><?php echo mssql_result($consulta,$i,"tipo"); ?></td>
								<td><?php echo mssql_result($consulta,$i,"numerod"); ?></td>
								<td><?php echo mssql_result($consulta,$i,"formato_cliente"); ?></td>
								<td><?php echo mssql_result($consulta,$i,"formato_cliente_2"); ?></td>
								<td><?php echo mssql_result($consulta,$i,"codclie"); ?></td>
								<td><?php echo utf8_encode(mssql_result($consulta,$i,"cliente")); ?></td>
								<td><?php echo utf8_encode(mssql_result($consulta,$i,"clase")); ?></td>
								<td><?php echo mssql_result($consulta,$i,"codvend"); ?></td>
								<td><?php echo mssql_result($consulta,$i,"vendedor"); ?></td>
								<td><?php echo mssql_result($consulta,$i,"proveedor"); ?></td>
								<td><?php echo mssql_result($consulta,$i,"clasificacion_categoria"); ?></td>
								<td><?php echo mssql_result($consulta,$i,"sub_clasificacion_categoria"); ?></td>
								<td><?php echo utf8_encode(mssql_result($consulta,$i,"marca")); ?></td>
								<td><?php echo mssql_result($consulta,$i,"refere"); ?></td>
								<td><?php echo mssql_result($consulta,$i,"coditem"); ?></td>
								<td><?php echo utf8_encode(mssql_result($consulta,$i,"descripcion")); ?></td>
								<td><?php if (mssql_result($consulta, $i, "tipo") == 'C') {
									echo round(mssql_result($consulta, $i, "cantidad"));
								} else {
									echo round(mssql_result($consulta, $i, "cantidad") * -1);
								} ?></td>
								<td><?php echo mssql_result($consulta,$i,"unid"); ?></td>
								<td><?php if (mssql_result($consulta, $i, "tipo") == 'C') {
									echo rdecimal2(mssql_result($consulta, $i, "paq"));
								} else {
									echo rdecimal2(mssql_result($consulta, $i, "paq") * -1);
								}  ?></td>
								<td><?php if (mssql_result($consulta, $i, "tipo") == 'C') {
									echo rdecimal2(mssql_result($consulta, $i, "bul"));
								} else {
									echo rdecimal2(mssql_result($consulta, $i, "bul") * -1);
								} ?></td>
								<td>	<?php
								$totalnt = mssql_result($consulta, $i, "montod");
								$tipofac = mssql_result($consulta, $i, "tipo");
								$factor = mssql_result($consulta, $i, "factor");
								$impuesto = mssql_result($consulta, $i, "impuesto");
								$totaltotal = ($totalnt + $impuesto) / $factor;
								if ($tipofac == 'C') {
									echo rdecimal2($totaltotal);
								} else {
									echo rdecimal2($totaltotal * -1);
								} ?></td>
								<td><?php echo rdecimal2(mssql_result($consulta,$i,"factor")); ?></td>
								<td><?php echo utf8_encode(mssql_result($consulta,$i,"notas1")); ?></td>
								<td><?php echo utf8_encode(mssql_result($consulta,$i,"notas2")); ?></td>
							</tr>
						<?php } ?>
						<tr>
							<td colspan="20" align="center">
								<div align="center">
									<div align="center"><a href="tabladinamica_ne_excel.php?&fechai=<?php echo $_POST['fechai']; ?>&fechaf=<?php echo $_POST['fechaf']; ?>&edv=<?php echo $edv; ?>&sucursal=<?php echo $sucursal; ?>"><img src="images/excel.jpeg" width="19" height="18" border="0" /> Exportar a Excel</a>&nbsp;&nbsp;</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<br>
		</div>
	</div>
	<?php
} else {
	header('Location: index.php');
}
?>