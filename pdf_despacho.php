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
		$consul_empresa = mssql_query("SELECT Descrip from SACONF where CodSucu='00000'");
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
		$txt = $file;
		$this->SetFont('Arial','',10);
		$this->MultiCell(0,5,$txt);
		$this->Ln();
	}
}
putenv("TZ=America/Caracas");
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$consul_des = mssql_query("SELECT * from appfacturas where correl = '$correl'");
$consul_des_det = mssql_query("SELECT numeros, tipofac from appfacturas_det where correl = '$correl'");
$num = mssql_num_rows($consul_des_det);
$condiciones = "";
$lote = "";
for($i=0;$i<$num;$i++){
	if ($num > 1){
		if ($i != ($num - 1)){
			if($i==0)
			{
				$condiciones = $condiciones."'".mssql_result($consul_des_det,$i,"numeros")."'";
				$condiciones1 =  $condiciones1."'".mssql_result($consul_des_det,$i,"numeros");
			}else{
				$condiciones=$condiciones.",'".mssql_result($consul_des_det,$i,"numeros")."'";
				$condiciones1 = $condiciones1.",'".mssql_result($consul_des_det,$i,"numeros");
			}
		}else{
			$condiciones = $condiciones.",'".mssql_result($consul_des_det,$i,"numeros")."'";
			$condiciones1 =  $condiciones1.",'".mssql_result($consul_des_det,$i,"numeros")."'";
		}
	}else{
		$condiciones = $condiciones."'".mssql_result($consul_des_det,$i,"numeros")."'";
		$condiciones1 =  $condiciones1."'".mssql_result($consul_des_det,$i,"numeros")."'";
	}
	$cadena=(string)mssql_result($consul_des_det,$i,"numeros");
	$lote = $lote.$cadena.",";
}
$genera = mssql_query("
	SELECT DISTINCT CodItem, Descrip,
	(SELECT SUM(Cantidad) FROM SAITEMFAC WHERE CodItem = SAPROD.CodProd AND
		TipoFac in ('A','C') AND EsUnid = 0 AND (numerod in ($condiciones)))
	AS BULTOS,

	(SELECT SUM(Cantidad) FROM SAITEMFAC WHERE CodItem = SAPROD.CodProd AND
		TipoFac in ('A','C') AND EsUnid = 1 AND (numerod in ($condiciones)))
	AS PAQUETES,CantEmpaq,EsEmpaque,saprod.Tara as tara,CodInst, saprod.marca
	FROM SAITEMFAC INNER JOIN SAPROD ON SAITEMFAC.CodItem = SAPROD.CodProd WHERE
	TipoFac in ('A','C') AND (numerod in ($condiciones)) order by saprod.marca
	");

$fecha = mssql_result($consul_des,0,"fechad");
$nota = mssql_result($consul_des,0,"nota");

$total_bultos = 0;
$total_paq = 0;
$total_peso = 0;

$total_peso_azucar = 0;
$total_peso_galleta = 0;
$total_peso_chocolote = 0;

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

$pdf->Cell(90,7,'Nro de Despacho: '.str_pad($correl, 8, 0, STR_PAD_LEFT),0,0,C);
$pdf->Ln();
$pdf->SetFont ('Arial','',7);
$pdf->Cell(90,7,'Fecha Despacho: '.$fecha,0,0,L);
$pdf->Cell(90,7,'Vehiculo de Carga: : '.$placa.' '.$modelo,0,0,L);
$pdf->Ln();

$pdf->Cell(150,7,'Destino : '.utf8_decode($nota),0,0,L);
$pdf->Ln();
$pdf->Cell(62,7,'Listado de Productos a Despachar',0,0,C);
$pdf->Ln();
$pdf->SetFillColor(200,220,255);
$pdf->Cell(5,7,' ',0,0,C);
$pdf->Cell(20,7,'Cod Prod',1,0,C,true);
$pdf->Cell(70,7,'Descripcion',1,0,C,true);
$pdf->Cell(40,7,'Marca',1,0,C,true);
$pdf->Cell(30,7,'Cant Bultos',1,0,C,true);
$pdf->Cell(30,7,'Cant Paquetes',1,0,C,true);
// $pdf->Cell(30,7,'Peso',1,0,C,true);
$pdf->Ln();
$total_bultos = 0;
$total_paq = 0;
$total_peso = 0;
for($i=0;$i<mssql_num_rows($genera);$i++){
	$bultos = 0;
	$paq = 0;
	if (mssql_result($genera,$i,"bultos")){
		$bultos = mssql_result($genera,$i,"bultos");
	}
	if (mssql_result($genera,$i,"paquetes")){
		$paq = mssql_result($genera,$i,"paquetes");
	}
	if (mssql_result($genera,$i,"EsEmpaque")!= 0){
		if (mssql_result($genera,$i,"paquetes") >= mssql_result($genera,$i,"CantEmpaq")){
			if (mssql_result($genera,$i,"CantEmpaq")!=0) {
				$bultos_total = mssql_result($genera,$i,"paquetes")/mssql_result($genera,$i,"CantEmpaq");
			}else{
				$bultos_total = 0;
			}
			$decimales = explode(".",$bultos_total);
			$bultos_deci = $bultos_total - $decimales[0];
			$paq = $bultos_deci * mssql_result($genera,$i,"CantEmpaq");
			$bultos = $decimales[0] + $bultos;
		}
	}
	$peso = $bultos * mssql_result($genera,$i,"tara");
	if (mssql_result($genera,$i,"CantEmpaq")!=0) {
		$peso = $peso + ((mssql_result($genera,$i,"tara")*$paq)/mssql_result($genera,$i,"cantempaq"));
	}


	$total_peso = $peso + $total_peso;
	$total_bultos = $total_bultos + $bultos;
	$total_paq = $total_paq + $paq;

	$pdf->Cell(5,7,' ',0,0,C);
	$pdf->Cell(20,7,mssql_result($genera,$i,"CodItem"),1,0,C);
	$pdf->Cell(70,7,' '.mssql_result($genera,$i,"descrip"),1,0,L);
	$pdf->Cell(40,7,' '.mssql_result($genera,$i,"marca"),1,0,L);
	$pdf->Cell(30,7,round($bultos),1,0,C);
	$pdf->Cell(30,7,round($paq),1,0,C);
	// $pdf->Cell(30,7,rdecimal($peso),1,0,C);
	$pdf->Ln();

}
$pdf->Cell(5,7,' ',0,0,C);
$pdf->Cell(40,7,' ',0,0,C);
$pdf->SetFont ('Arial','B',8);
$pdf->Cell(90,7,'Total = ',1,0,C);
$pdf->Cell(30,7,$total_bultos.' Bult',1,0,C,true);
$pdf->Cell(30,7,$total_paq.' Paq',1,0,C,true);
// $pdf->Cell(30,7,rdecimal($total_peso).'Kg'.' - '.(rdecimal($total_peso)/1000).'TN',1,0,C,true);
$pdf->Ln();
$pdf->Cell(62,7,'FACTURAS DESPACHADAS '.$num,0,0,C);
$pdf->Ln();
$pdf->Cell(20,7,' ',0,0,C);
$lote = str_replace(";"," ",$lote);
$pdf->MultiCell(140,5,$lote);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(62,7,'_____________________',0,0,C);
$pdf->Cell(62,7,'________________',0,0,C);
$pdf->Cell(62,7,'___________________',0,0,C);
$pdf->Ln();
$pdf->Cell(62,7,'OPERADOR DE ALMACEN ',0,0,C);
$pdf->Cell(62,7,'CHEQUEADOR ',0,0,C);
$pdf->Cell(62,7,'TRANSPORTISTA',0,0,C);
$pdf->Ln();
$pdf->Output();
?>
