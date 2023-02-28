<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
	$edv = $_POST['edv'];
	switch (true) {
    # =============================================================
    # === UNA SUCURSAL, UNA INSTANCIA, UN PROVEEDOR, UNA MARCA ==== 
    # =============================================================
		case ($edv!="-" ):

		$clientes = mssql_query("SELECT a.CodClie, a.Descrip, a.Represent, a.Telef, a.Direc1, c.Descrip as ztransporte, b.portafolio,b.licencia_licor, a.Clase, a.TipoPVP, a.LimiteCred, a.DiasCred, b.dia_visita, b.frecuencia_visita, a.CodVend, b.ruta_alternativa, b.ruta_alternativa_2, b.canal, b.formato_cliente, b.pdv_ocasion, b.formato_cliente_2, b.alcance, b.nivel_ejecucion, a.Activo , a.EsCredito,b.tipo, b.segmentacion, a.fechae, B.convenio
			from saclie as a 
			left join SACLIE_99 as b on a.CodClie=b.CodClie  left join SAZONA as c on a.CodZona=c.CodZona
			where a.CodVend = '$edv' or b.Ruta_Alternativa = '$edv' or b.Ruta_Alternativa_2 =  '$edv'  order by a.CodClie desc");

		break;

    # ====================================================================
    # === UNA SUCURSAL, UNA INSTANCIA, UN PROVEEDOR, TODAS LAS MARCAS ==== 
    # ====================================================================
		case ($edv=="-"):

		$clientes = mssql_query("SELECT a.CodClie, a.Descrip, a.Represent, a.Telef, a.Direc1, c.Descrip as ztransporte, b.portafolio,b.licencia_licor, a.Clase, a.TipoPVP, a.LimiteCred, a.DiasCred, b.dia_visita, b.frecuencia_visita, a.CodVend, b.ruta_alternativa, b.ruta_alternativa_2, b.canal, b.formato_cliente, b.pdv_ocasion, b.formato_cliente_2, b.alcance, b.nivel_ejecucion, a.Activo, a.EsCredito,b.tipo, b.segmentacion, a.fechae, B.convenio
			from saclie as a 
			left join SACLIE_99 as b on a.CodClie=b.CodClie  left join SAZONA as c on a.CodZona=c.CodZona
			order by a.CodClie desc");

		break;
		default:
	}
	?>
	<div class="content-wrapper">
		<!-- BOX DE LA MIGA DE PAN -->
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
<section class="content" style=" margin-bottom: 70%;">

	<div class="row">
		<div class="col-12">
			<div class="card card-saint">
				<div class="card-header">
					<script type="text/javascript">
						function regresa(){
							window.location.href = "principal1.php?page=maestro_clientes&mod=1";
						}
					</script>
					<h3 class="card-title">Maestro de Clientes del Vendedor <?php echo $edv; ?></h3>&nbsp;&nbsp;&nbsp;
					<button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
				</div>
				<div class="card-body">
					<!--  <table id="example2" class="table table-bordered table-hover"> -->
						<table id="example3" class="table table-sm table-bordered table-striped">
							<thead style="background-color: #00137f;color: white;">
								<tr>
									<th>Codigo Cliente</th>
									<th>Razon Social</th>
									<th>Persona de Contacto</th>
									<th>Telefono</th>
									<th>Direccion</th>
									<th>Zona de Transporte</th>
									<th>Portafolio</th>
									<th>Licencia Licor</th>
									<th>Clase</th>
									<th>Lista de Precio</th>
									<th>Limite de Credito</th>
									<th>Dias de Credito</th>
									<th>Dia Visita</th>
									<th>Frecuencia</th>
									<th>Condicion de Pago</th>
									<th>Ruta Principal</th>
									<th>Ruta Alternativa 1</th>
									<th>Ruta Alternativa 2</th>
									<th>Canal</th>
									<th>Clasificacion / Formato</th>
									<th>Formato PDV / Ocasion</th>
									<th>Formato Cliente / OC Secundaria</th>
									<th>Alcance</th>
									<th>Nivel Ejecucion</th>
									<th>Estatus</th>
									<th>Tipo</th>
									<th>Segmentacion</th>
									<th>Convenio</th>
									<th>Fecha Creacion</th>
								</tr>
							</thead>
							<tbody>
								<?php for ($i = 0; $i < mssql_num_rows($clientes); $i++) {
									$activo = (mssql_result($clientes, $i, "Activo")=='1')
									? '<span class="badge badge-success mt-1">Activo</span>'
									: '<span class="badge badge-secondary mt-1">Inactivo</span>' ;
									if (mssql_result($clientes, $i, "EsCredito")=='1') {
										$condpago= 'Credito';
									} else {
										$condpago= 'Contado';
									}
									?>
									<tr>
										<td class="text-center"><?php echo mssql_result($clientes, $i, "CodClie"); ?></td>
										<td class="text-left"><?php echo utf8_encode(mssql_result($clientes, $i, "descrip")) ; ?></td>
										<td class="text-left"><?php echo utf8_encode(mssql_result($clientes, $i, "Represent")) ; ?></td>
										<td class="text-center"><?php echo mssql_result($clientes, $i, "Telef"); ?></td>
										<td class="text-left"><?php echo utf8_encode(mssql_result($clientes, $i, "Direc1")) ; ?></td>	
										<td class="text-center"><?php echo utf8_encode(mssql_result($clientes, $i, "ztransporte")); ?></td>
										<td class="text-center"><?php echo utf8_encode(mssql_result($clientes, $i, "portafolio")); ?></td>
										<td class="text-center"><?php echo utf8_encode(mssql_result($clientes, $i, "licencia_licor")); ?></td>
										<td class="text-center"><?php echo utf8_encode(mssql_result($clientes, $i, "clase")); ?></td>
										<td class="text-center"><?php echo mssql_result($clientes, $i, "tipopvp"); ?></td>
										<td class="text-center"><?php echo rdecimal2(mssql_result($clientes, $i, "LimiteCred")); ?></td>
										<td class="text-center"><?php echo mssql_result($clientes, $i, "diascred"); ?></td>
										<td class="text-center"><?php echo mssql_result($clientes, $i, "dia_visita"); ?></td>
										<td class="text-center"><?php echo mssql_result($clientes, $i, "frecuencia_visita"); ?></td>
										<td class="text-center"><?php echo $condpago; ?></td>
										<td class="text-center"><?php echo mssql_result($clientes, $i, "CodVend"); ?></td>
										<td class="text-center"><?php echo mssql_result($clientes, $i, "ruta_alternativa"); ?></td>
										<td class="text-center"><?php echo mssql_result($clientes, $i, "ruta_alternativa_2"); ?></td>										
										<td class="text-center"><?php echo mssql_result($clientes, $i, "canal"); ?></td>										
										<td class="text-center"><?php echo mssql_result($clientes, $i, "formato_cliente"); ?></td>
										<td class="text-center"><?php echo mssql_result($clientes, $i, "pdv_ocasion"); ?></td>										
										<td class="text-center"><?php echo mssql_result($clientes, $i, "formato_cliente_2"); ?></td>										
										<td class="text-center"><?php echo mssql_result($clientes, $i, "alcance"); ?></td>
										<td class="text-center"><?php echo mssql_result($clientes, $i, "nivel_ejecucion"); ?></td>
										<td class="text-center"><?php echo $activo; ?></td>
										<td class="text-center"><?php echo mssql_result($clientes, $i, "tipo"); ?></td>
										<td class="text-center"><?php echo mssql_result($clientes, $i, "segmentacion"); ?></td>
										<td class="text-center"><?php 
										$convenio= mssql_result($clientes, $i, "convenio");
										switch ($convenio) {
											case 0:
											$escon = "Sin Convenio";
											break;
											case 1:
											$escon = "CDLC";
											break;
											case 2:
											$escon = "EURO";
											break;
											case 3:
											$escon = "CALL CENTER";
											break;
											case 4:
											$escon = "EMPLEADOS";
											break;
										}
										echo $escon;
									?></td>
									<td class="text-center"><?php echo date('d/m/Y', strtotime(mssql_result($clientes, $i, "fechae"))); ?></td>										  
								</tr>
							<?php }?>
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