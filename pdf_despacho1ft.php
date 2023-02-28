<?php
require("conexion.php");
require("funciones.php");
require('pdf/fpdf.php');
set_time_limit(0);
$correl = $_GET['correl'];
class PDF extends FPDF
{
	function Header()
	{
		$this->Image('images/logotri.jpg',10,10,20);
		$this->SetFont('Arial','B',8);
		$this->Cell(80);
		$consul_empresa = mssql_query("SELECT Descrip from SACONF");
		$consul_empresa1 =  mssql_result($consul_empresa, 0, 'DESCRIP'); 
		$this->Cell(30,10,$consul_empresa1,0,0,'C');
		$this->Ln();
	}
	function Footer()
	{
		$this->SetFont('Arial','',7);
		$this->SetY(-20);
		$this->SetFont('Arial','',7);

		$this->Cell(0,10,'Desarrollado por Rsistems Developer. Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	function ChapterBody($file)
	{
// Leemos el fichero
		$txt = $file;
// Times 12
		$this->SetFont('Arial','',10);
// Imprimimos el texto justificado
		$this->MultiCell(0,5,$txt);
// Salto de línea
		$this->Ln();
// Cita en itálica
/*$this->SetFont('','I');
$this->Cell(0,5,'(fin del extracto)');*/
}
}
putenv("TZ=America/Caracas");
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$consul_des = mssql_query("SELECT * from appfacturasft where correl = '$correl'");
$consul_des_det = mssql_query("SELECT * from appfacturas_detft where correl = '$correl'");
$num = mssql_num_rows($consul_des_det);
$condiciones = "";
for($i=0;$i<$num;$i++){
	if ($num > 1){
		if ($i != ($num - 1)){
			if($i==0)
			{
				$condiciones = $condiciones."'".mssql_result($consul_des_det,$i,"numeros")."'";
				$condiciones1 =  $condiciones1."'".mssql_result($consul_des_det,$i,"numeros")."'";
			}else{
				$condiciones=$condiciones.",'".mssql_result($consul_des_det,$i,"numeros")."'";
//	echo	$condiciones = $condiciones.'numerod = '.mssql_result($consul_des_det,$i,"numeros")." or ";
				$condiciones1 = $condiciones1.",'".mssql_result($consul_des_det,$i,"numeros")."'";
			}
		}else{
			$condiciones = $condiciones.",'".mssql_result($consul_des_det,$i,"numeros")."'";
			$condiciones1 =  $condiciones1.",'".mssql_result($consul_des_det,$i,"numeros")."'";
		}
	}else{
		$condiciones = $condiciones."'".mssql_result($consul_des_det,$i,"numeros")."'";
		$condiciones1 =  $condiciones1."'".mssql_result($consul_des_det,$i,"numeros")."'";
	}
}

$genera = mssql_query("
	SELECT DISTINCT CodItem, Descrip,

	(SELECT SUM(Cantidad) FROM SAITEMFAC WHERE CodItem = SAPROD.CodProd AND
		TipoFac in ('F') AND EsUnid = 0 AND (numerod in ($condiciones)))
	AS BULTOS,

	(SELECT SUM(Cantidad) FROM SAITEMFAC WHERE CodItem = SAPROD.CodProd AND
		TipoFac in ('F') AND EsUnid = 1 AND (numerod in ($condiciones)))
	AS PAQUETES,
	CantEmpaq,
	EsEmpaque,
	saprod.Tara as tara,
	CodInst
	FROM SAITEMFAC INNER JOIN SAPROD ON SAITEMFAC.CodItem = SAPROD.CodProd WHERE
	TipoFac in ('F') AND (numerod in ($condiciones)) order by SAITEMFAC.CodItem
	");

$genera_dev = mssql_query("SELECT DISTINCT CodItem, Descrip,

	(SELECT SUM(Cantidad) FROM SAITEMFAC WHERE CodItem = SAPROD.CodProd AND
		EsUnid = 0 AND TipoFac = 'B' AND OTipo = 'A' AND (ONumero in ($condiciones1))) AS BULTOS,

	(SELECT SUM(Cantidad) FROM SAITEMFAC WHERE CodItem = SAPROD.CodProd AND
		EsUnid = 1 AND TipoFac = 'B' AND OTipo = 'A' AND (ONumero in ($condiciones1))) AS PAQUETES,

	CantEmpaq,
	EsEmpaque,
	saprod.Tara as tara,
	CodInst
	FROM SAITEMFAC INNER JOIN SAPROD ON SAITEMFAC.CodItem = SAPROD.CodProd WHERE
	TipoFac = 'B' AND OTipo = 'A' AND (ONumero in ($condiciones1)) order by SAITEMFAC.CodItem");


$fecha = mssql_result($consul_des,0,"fechad");
$nota = mssql_result($consul_des,0,"nota");

$total_bultos = 0;
$total_paq = 0;
$total_peso = 0;


$nota = mssql_result($consul_des,0,"nota");
$cedula = mssql_result($consul_des,0,"cedula_chofer");
$consul_chofer = mssql_query("SELECT descripcion, cedula from appChofer where cedula = '$cedula'");
if (mssql_num_rows($consul_chofer) != 0){
	$nota = $nota." - ".mssql_result($consul_chofer,0,"descripcion")." - ".mssql_result($consul_chofer,0,"cedula");
}

$placa = mssql_result($consul_des,0,"placa");
$modelo = "";
$consul_vehi = mssql_query("SELECT * from appVehiculo where placa = '$placa'");
if (mssql_num_rows($consul_vehi) != 0){
	$modelo = mssql_result($consul_vehi,0,"modelo")." ".mssql_result($consul_vehi,0,"capacidad")."Kg";
}


$pdf->Cell(90,7,'Nro de Despacho FT: '.str_pad($correl, 8, 0, STR_PAD_LEFT),0,0,C);
$pdf->Ln();
$pdf->SetFont ('Arial','',7);
$pdf->Cell(90,7,'Fecha Despacho: '.$fecha,0,0,L);
$pdf->Cell(90,7,'Vehiculo de Carga: : '.$placa.' '.$modelo,0,0,L);
$pdf->Ln();



$pdf->Cell(150,7,'Destino: '.utf8_decode($nota),0,0,L);


$pdf->Ln();
////////////////////////////////////////////////////////////////////LISTA DE CLIENTES Y FACTURAS
$pdf->Cell(62,7,'Listado de Facturas Seleccionadas',0,0,C);
$pdf->Ln();
$pdf->SetFillColor(200,220,255);
$pdf->Cell(5,7,' ',0,0,C);
$pdf->Cell(20,7,'Nro Fact',1,0,C,true);
$pdf->Cell(30,7,'Fecha E',1,0,C,true);
$pdf->Cell(10,7,'Ruta',1,0,C,true);
$pdf->Cell(30,7,'CodCliente',1,0,C,true);
$pdf->Cell(70,7,'Cliente',1,0,C,true);
$pdf->Cell(20,7,'Total $',1,0,C,true);
$pdf->Ln();
$fact_new = mssql_query("SELECT *, MtoTotal/factorp as montod from safact where (numerod in ($condiciones)) and tipofac in ('F') order by numerod");
$num_fact_new = mssql_num_rows($fact_new);
for($m=0;$m<$num_fact_new;$m++){
	$fact_clie = mssql_query("SELECT DISTINCT(codclie) from safact where (numerod in ($condiciones)) and tipofac in ('F') ");
	$totalcli = mssql_num_rows($fact_clie);
	$suma = $suma + mssql_result($fact_new,$m,"montod"); 
	$pdf->Cell(5,7,' ',0,0,C);
	$pdf->Cell(20,7,mssql_result($fact_new,$m,"numerod"),1,0,C);
	$pdf->Cell(30,7,mssql_result($fact_new,$m,"fechae"),1,0,C);
	$pdf->Cell(10,7,mssql_result($fact_new,$m,"codvend"),1,0,C);
	$pdf->Cell(30,7,mssql_result($fact_new,$m,"codclie"),1,0,C);
	$pdf->Cell(70,7,mssql_result($fact_new,$m,"descrip"),1,0,C);
	$pdf->Cell(20,7,rdecimal2(mssql_result($fact_new,$m,"montod")),1,0,C);
	$pdf->Ln();
}
$pdf->Cell(62,7,'Total de Facturas Emitidas: '.$num_fact_new,0,0,C);
$pdf->Cell(62,7,'Total de Divisas del Despacho: '.rdecimal2($suma).' $',0,0,C);
$pdf->Cell(62,7,'Total de Clientes: '.$totalcli,0,0,C);

///////////////////////////////////////////////////////////////////////LISTA DE PRODUCTOS A DESPACHAR
// $pdf->Ln();
// $pdf->Cell(62,7,'Listado de Productos a Despachar',0,0,C);
// $pdf->Ln();
// $pdf->SetFillColor(200,220,255);
// $pdf->Cell(5,7,' ',0,0,C);
// $pdf->Cell(20,7,'Cod Prod',1,0,C,true);
// $pdf->Cell(70,7,'Descripcion',1,0,C,true);
// $pdf->Cell(30,7,'Cant Bultos',1,0,C,true);
// $pdf->Cell(30,7,'Cant Paquetes',1,0,C,true);
// $pdf->Cell(30,7,'Peso',1,0,C,true);
// $pdf->Ln();

// $total_bultos = 0;
// $total_paq = 0;
// $total_peso = 0;
// for($i=0;$i<mssql_num_rows($genera);$i++){
// 	$bultos = 0;
// 	$paq = 0;
// 	if (mssql_result($genera,$i,"bultos")){
// 		$bultos = mssql_result($genera,$i,"bultos");
// 	}
// 	if (mssql_result($genera,$i,"paquetes")){
// 		$paq = mssql_result($genera,$i,"paquetes");
// 	}
// 	if (mssql_result($genera,$i,"EsEmpaque")!= 0){
// 		if (mssql_result($genera,$i,"paquetes") >= mssql_result($genera,$i,"CantEmpaq")){
// 			if (mssql_result($genera,$i,"CantEmpaq")!=0) {
// 				$bultos_total = mssql_result($genera,$i,"paquetes")/mssql_result($genera,$i,"CantEmpaq");
// 			}else{
// 				$bultos_total = 0;
// 			}
// 			$decimales = explode(".",$bultos_total);
// 			$bultos_deci = $bultos_total - $decimales[0];
// 			$paq = $bultos_deci * mssql_result($genera,$i,"CantEmpaq");
// 			$bultos = $decimales[0] + $bultos;
// 		}
// 	}
// 	$peso = $bultos * mssql_result($genera,$i,"tara");
// 	if (mssql_result($genera,$i,"CantEmpaq")!=0) {
// 		$peso = $peso + ((mssql_result($genera,$i,"tara")*$paq)/mssql_result($genera,$i,"cantempaq"));
// 	}


// 	$total_peso = $peso + $total_peso;
// 	$total_bultos = $total_bultos + $bultos;
// 	$total_paq = $total_paq + $paq;


// 	$pdf->Cell(5,7,' ',0,0,C);
// 	$pdf->Cell(20,7,mssql_result($genera,$i,"CodItem"),1,0,C);
// 	$pdf->Cell(70,7,' '.mssql_result($genera,$i,"descrip"),1,0,L);
// 	$pdf->Cell(30,7,round($bultos),1,0,C);
// 	$pdf->Cell(30,7,round($paq),1,0,C);
// 	$pdf->Cell(30,7,rdecimal($peso),1,0,C);
// 	$pdf->Ln();

// }
// $pdf->Cell(5,7,' ',0,0,C);
// $pdf->SetFont ('Arial','B',8);
// $pdf->Cell(90,7,'Total = ',1,0,C);
// $pdf->Cell(30,7,$total_bultos.' Bult',1,0,C,true);
// $pdf->Cell(30,7,$total_paq.' Paq',1,0,C,true);
// $pdf->Cell(30,7,rdecimal($total_peso).'Kg'.' - '.(rdecimal($total_peso)/1000).'TN',1,0,C,true);
// $pdf->Ln();
// $pdf->Cell(120,7,'Total Azucar (TN): '.($total_peso_azucar/1000).' Total Chocolate (TN): '.($total_peso_chocolote/100).' Total Galleta (TN): '.($total_peso_galleta/1000),0,0,C);

$pdf->Ln();
//////////////////////DEVOLUCIONES
/*if (mssql_num_rows($genera_dev)!=0){
	$devol = mssql_query("SELECT DISTINCT ONumero
		FROM SAITEMFAC INNER JOIN SAPROD ON SAITEMFAC.CodItem = SAPROD.CodProd WHERE
		TipoFac = 'B' AND OTipo = 'A'  AND (ONumero in ($condiciones1)) order by SAITEMFAC.ONumero");
	$pdf->SetFont ('Arial','',7);
	$pdf->Cell(62,7,'PRODUCTOS DEVUELTOS',0,0,C);
	$pdf->Ln();
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(255,0,0);
	$pdf->Cell(5,7,' ',0,0,C);
	$pdf->Cell(20,7,'Cod Prod',1,0,C,true);
	$pdf->Cell(70,7,'Descripcion',1,0,C,true);
	$pdf->Cell(30,7,'Cant Bultos',1,0,C,true);
	$pdf->Cell(30,7,'Cant Paquetes',1,0,C,true);
	$pdf->Cell(30,7,'Peso',1,0,C,true);
	$pdf->Ln();
	$pdf->SetTextColor(0,0,0);
	$total_bultos = 0;
	$total_paq = 0;
	$total_peso = 0;
	for($i=0;$i<mssql_num_rows($genera_dev);$i++){
		$bultos = 0;
		$paq = 0;
		if (mssql_result($genera_dev,$i,"bultos")){
			$bultos = mssql_result($genera_dev,$i,"bultos");
		}
		if (mssql_result($genera_dev,$i,"paquetes")){
			$paq = mssql_result($genera_dev,$i,"paquetes");
		}
		if (mssql_result($genera_dev,$i,"EsEmpaque")!= 0){
			if (mssql_result($genera_dev,$i,"paquetes") >= mssql_result($genera_dev,$i,"CantEmpaq")){
				$bultos_total = mssql_result($genera_dev,$i,"paquetes")/mssql_result($genera_dev,$i,"CantEmpaq");
				$decimales = explode(".",$bultos_total);
				$bultos_deci = $bultos_total - $decimales[0];
				$paq = $bultos_deci * mssql_result($genera_dev,$i,"CantEmpaq");
				$bultos = $decimales[0] + $bultos;
			}
		}
		$peso = $bultos * mssql_result($genera_dev,$i,"tara");
		$peso = $peso + ((mssql_result($genera_dev,$i,"tara")*$paq)/mssql_result($genera_dev,$i,"cantempaq"));




		$total_peso = $peso + $total_peso;
		$total_bultos = $total_bultos + $bultos;
		$total_paq = $total_paq + $paq;

		$pdf->Cell(5,7,' ',0,0,C);
		$pdf->Cell(20,7,mssql_result($genera_dev,$i,"CodItem"),1,0,C);
		$pdf->Cell(70,7,' '.mssql_result($genera_dev,$i,"descrip"),1,0,L);
		$pdf->Cell(30,7,round($bultos),1,0,C);
		$pdf->Cell(30,7,round($paq),1,0,C);
		$pdf->Cell(30,7,rdecimal($peso),1,0,C);
		$pdf->Ln();
	}
	$pdf->Cell(5,7,' ',0,0,C);
	$pdf->SetFont ('Arial','B',8);
	$pdf->Cell(90,7,'Total Devuelto = ',1,0,C);
	$pdf->SetTextColor(255,255,255);
	$pdf->Cell(30,7,$total_bultos.' Bult',1,0,C,true);
	$pdf->Cell(30,7,$total_paq.' Paq',1,0,C,true);
	$pdf->Cell(30,7,rdecimal($total_peso).'Kg'.' - '.(rdecimal($total_peso)/1000).'TN',1,0,C,true);
	$pdf->Ln();
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(62,7,'FACTURAS AFECTADAS ('.mssql_num_rows($devol).') :',0,0,C);
	$pdf->Ln();
	$notas = "";
	for($i=0;$i<mssql_num_rows($devol);$i++){
		$notas = $notas.mssql_result($devol,$i,"ONumero").",  ";
	}
	$pdf->Cell(20,7,' ',0,0,C);
	$pdf->MultiCell(140,5,$notas);

}else{*/
	$pdf->Cell(20,7,' ',0,0,C);
	$pdf->Cell(62,7,'',0,0,C);
	/*}*/

	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell(62,7,'_____________________',0,0,C);
	$pdf->Cell(62,7,'___________________',0,0,C);
	$pdf->Ln();
	$pdf->Cell(62,7,'Entregado Por ',0,0,C);
	$pdf->Cell(62,7,'TRANSPORTISTA',0,0,C);

	$pdf->Output();
	?>
