<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Estado_de_Cuenta.xls");
header("Pragma: no-cache");
header("Expires: 0");
require("conexion.php");
require("funciones.php");
set_time_limit(0);

$cliente = $_GET['cliente'];
$fechai = $_GET['fechai'].' 00:00:00';
$fechaf = $_GET['fechaf'].' 23:59:59';
$fechaiii = $_GET['fechai'];
$fechafff = $_GET['fechaf'];
$fechaii = normalize_date($fechaiii);
$fechaff = normalize_date($fechafff);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
$estadocuenta = mssql_query("
	SELECT 
	cxc.CodClie,
	a.Descrip as rsocial,
	cxc.codvend as vendedor,
	a.clase as clase,
	a.Telef as telefono,
	cxc.FechaE as Emision,
	cxc.FechaV as Vencimiento,
	case 
	when TipoCxc = '10' then 'FAC'
	when TipoCxc = '50' then 'ADE'
	when TipoCxc = '41' then 'PAG'
	when TipoCxc = '31' then 'RET'
	when TipoCxc LIKE '8%' then 'RET'
	ELSE '' END Operacion, 
	NumeroD Numero,
	cxc.Document Descripcion,
	isnull(bnc.Descripcion,0) Banco, 
	isnull(trn.Documento,0) Nro_Documento,
	(CASE WHEN substring(cxc.TipoCxc,1,1) In ('1','2','6','7') Then cxc.Monto Else CONVERT(decimal(28,3),0) END) as Debitos,
	(CASE WHEN substring(cxc.TipoCxc,1,1) In ('3','4','5','8') Then cxc.Monto Else CONVERT(decimal(28,3),0) END) as Creditos,
	isnull(sum((CASE WHEN substring(cxc.TipoCxc,1,1) In ('1','2','6','7') Then cxc.Monto Else CONVERT(decimal(28,3),0) END) - (CASE WHEN substring(cxc.TipoCxc,1,1) In ('3','4','5','8') Then cxc.Monto Else CONVERT(decimal(28,3),0) END)) over (Order by NroUnico asc), 0) Saldo 
	from saacxc cxc 
	left join SBTRAN trn on cxc.NroUnico = trn.NroPpal and cxc.TipoCxc != 10
	left join SBBANC bnc on trn.CodBanc = bnc.CodBanc
	left join SACLIE a on cxc.CodClie=a.CodClie
	left join (select min(FechaE) Fecha, CodClie from SAACXC group by CodClie) Fe on cxc.CodClie = Fe.CodClie
	where cxc.CodClie='$cliente' and cxc.fechae BETWEEN FE.Fecha and '$fechaf'
	order by  NroUnico,CodClie asc");
	?>
	<div class="content-wrapper">
		<section class="content">
			<div class="row">
				<div class="col-12">
					<div class="card card-saint">
						<div class="card-header">
							<h2>EL TRIUNFO C.A</h1>
								<H4>J-300222004</H4>
								<h2 align="center">Reporte de Estado de Cuenta</h3>
									<h3 class="card-title" align="center"><?php
									$rsocial= mssql_query("SELECT descrip, codvend, Telef from saclie where codclie ='$cliente'");
									for($i=0;$i<mssql_num_rows($rsocial);$i++){                                                    
										echo utf8_decode(utf8_encode(mssql_result($rsocial,$i,"descrip")));
										$codvend = mssql_result($rsocial,$i,"codvend");
										$Telef = mssql_result($rsocial,$i,"Telef");
									} ?> &nbsp;&nbsp;<p align="center">Codigo: <?php echo $cliente; ?>&nbsp;&nbsp;&nbsp;Vendedor: <?php echo $codvend; ?> &nbsp;&nbsp;&nbsp; Telefono: <?php echo $Telef; ?></p>
									Desde <?php echo $fechaii; ?> &nbsp;&nbsp;&nbsp; Hasta  <?php echo $fechaff; ?></h3>
								</div>
								<div class="card-body">
									<?php
									$num = mssql_num_rows($estadocuenta); 
									?>
									<table id="example7" class="table table-sm table-bordered table-striped">
										<thead style="background-color: #00137f;color: white;">
											<tr>
												<th>Emision</th>
												<th>Vencimiento</th>
												<th>Operacion</th>                    
												<th>Numero</th>
												<th>Descripcion</th>
												<th>Banco</th>
												<th>N Deposito</th>
												<th>Debitos</th>
												<th>Creditos</th>
												<th>Saldo</th>
											</tr>
										</thead>
										<tbody>
											<?php for ($i = 0; $i < mssql_num_rows($estadocuenta); $i++) {
												?>
												<tr>
													<td ><?php echo date('d/m/Y', strtotime(mssql_result($estadocuenta, $i, 'emision'))); ?></td>
													<td ><?php echo date('d/m/Y', strtotime(mssql_result($estadocuenta, $i, 'vencimiento'))); ?></td>
													<td><?php echo mssql_result($estadocuenta, $i, "operacion"); ?></td>
													<td><?php echo mssql_result($estadocuenta, $i, "numero"); ?></td>
													<td><?php echo mssql_result($estadocuenta, $i, "descripcion"); ?></td>
													<td><?php echo mssql_result($estadocuenta, $i, "banco"); ?></td>
													<td><?php echo mssql_result($estadocuenta, $i, "nro_documento"); ?></td>
													<td><?php echo rdecimal2(mssql_result($estadocuenta, $i, "debitos")); ?></td>
													<td><?php echo rdecimal2(mssql_result($estadocuenta, $i, "creditos")); ?></td>
													<td><?php echo rdecimal2(mssql_result($estadocuenta, $i, "saldo")); ?></td>
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


