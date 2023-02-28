<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {

	$proveedor = $_POST['proveedor'];

	switch (true) {
    # =============================================================
    # === UNA SUCURSAL, UNA INSTANCIA, UN PROVEEDOR, UNA MARCA ==== 
    # =============================================================
		case ($proveedor!="-" ):

		$query = mssql_query("SELECT a.CodProd, a.Descrip, b.capacidad_botella, a.UndVol, a.Unidad, a.UndEmpaq, a.CantEmpaq, a.EsExento, b.proveedor, b.codigo_origen, b.casa_representacion, a.Marca, b.clasificacion_categoria, b.sub_clasificacion_categoria, a.Refere, b.grado_alcoholico, c.Descrip as instancia , a.peso, a.Volumen from SAPROD as a inner join SAPROD_99 as b on a.CodProd=b.CodProd inner join SAINSTA as c on a.CodInst=c.CodInst where b.proveedor = '$proveedor' order by c.Descrip, b.proveedor, a.CodProd");

		break;

    # ====================================================================
    # === UNA SUCURSAL, UNA INSTANCIA, UN PROVEEDOR, TODAS LAS MARCAS ==== 
    # ====================================================================
		case ($proveedor=="-"):

		$query = mssql_query("SELECT a.CodProd, a.Descrip, b.capacidad_botella, a.UndVol, a.Unidad, a.UndEmpaq, a.CantEmpaq, a.EsExento, b.proveedor, b.codigo_origen, b.casa_representacion, a.Marca, b.clasificacion_categoria, b.sub_clasificacion_categoria, a.Refere, b.grado_alcoholico, c.Descrip as instancia, a.peso, a.Volumen from SAPROD as a inner join SAPROD_99 as b on a.CodProd=b.CodProd inner join SAINSTA as c on a.CodInst=c.CodInst order by c.Descrip, b.proveedor, a.CodProd");

		break;

		default:

	}


	?>
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
<!--      <div class="row mb-2">
<div class="col-sm-6">
<h2 id="title_permisos">Ultima Activacion Clientes</h2>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
<li class="breadcrumb-item active">Ultima Activacion Clientes</li>
</ol>
</div>
</div> -->
</div>
</section>
<section class="content" >
	<div class="row">
		<div class="col-12">
			<div class="card card-saint">
				<div class="card-header">
					<script type="text/javascript">
						function regresa(){
							window.location.href = "principal1.php?page=maestro_productos&mod=1";
						}
					</script>
					<h3 class="card-title">Maestro de Productos</h3>&nbsp;&nbsp;&nbsp;
					<button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
				</div>
				<div class="card-body">
					<table id="example3" class="table table-sm table-bordered table-striped">
						<thead style="background-color: #00137f;color: white;">
							<tr>
								<th>Codigo</th>
								<th>Descripcion</th>
								<th>Capacidad</th>
								<th>Unidad Medida</th>
								<th>Tipo Empaque MAX</th>
								<th>Tipo Empaque MIN</th>
								<th>Cantidad Empaque</th>
								<th>Exento</th>
								<th>Proveedor</th>
								<th>Codigo Origen</th>
								<th>Casa Representacion</th>
								<th>Marca</th>
								<th>Clasificacion o Categoria</th>
								<th>Sub Clasificacion o Categoria</th>
								<th>Codigo de Barra</th>
								<th>Grado Alcoholico</th>
								<th>Instancia</th>
								<th>Peso</th>
								<th>Volumen</th>
							</tr>
						</thead>
						<tbody>
							<?php for ($i = 0; $i < mssql_num_rows($query); $i++) {
								?>
								<tr>
									<td class="text-center"><?php echo mssql_result($query, $i, "Codprod"); ?></td>
									<td class="text-center"><?php echo utf8_encode(mssql_result($query, $i, "Descrip")); ?></td>
									<td class="text-center"><?php echo rdecimal2(mssql_result($query, $i, "capacidad_botella")); ?></td>
									<td class="text-center"><?php echo mssql_result($query, $i, "undvol"); ?></td>
									<td class="text-center"><?php echo mssql_result($query, $i, "unidad"); ?></td>
									<td class="text-center"><?php echo mssql_result($query, $i, "undempaq"); ?></td>
									<td class="text-center"><?php echo rdecimal2(mssql_result($query, $i, "cantempaq")); ?></td>
									<td class="text-center"><?php echo mssql_result($query, $i, "esexento"); ?></td>
									<td class="text-center"><?php echo utf8_encode(mssql_result($query, $i, "proveedor")); ?></td>
									<td class="text-center"><?php echo mssql_result($query, $i, "codigo_origen"); ?></td>
									<td class="text-center"><?php echo utf8_encode(mssql_result($query, $i, "casa_representacion")); ?></td>
									<td class="text-center"><?php echo utf8_encode(mssql_result($query, $i, "marca")); ?></td>
									<td class="text-center"><?php echo utf8_encode(mssql_result($query, $i, "clasificacion_categoria")); ?></td>
									<td class="text-center"><?php echo utf8_encode(mssql_result($query, $i, "sub_clasificacion_categoria")); ?></td>
									<td class="text-center"><?php echo mssql_result($query, $i, "refere"); ?></td>
									<td class="text-center"><?php echo mssql_result($query, $i, "grado_alcoholico"); ?></td>
									<td class="text-center"><?php echo utf8_encode(mssql_result($query, $i, "instancia")); ?></td>
									<td class="text-center"><?php echo rdecimal2(mssql_result($query, $i, "peso")); ?></td>
									<td class="text-center"><?php echo rdecimal2(mssql_result($query, $i, "volumen")); ?></td>
								</tr>
							<?php }?>
						</tbody>
					</table>
				</div>
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