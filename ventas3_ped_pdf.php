<?php
require ("conexion.php");
require ("funciones.php");
require ("Functions.php");
require_once ("permisos/Mssql.php");
require('fpdf/fpdf.php');
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
set_time_limit(0);
if ($_SESSION['login'] == ""){
	echo "<script language=Javascript> location.href=\"close.php\";</script>";
}

$sucursal = $_SESSION["codsucu"];
$nrounico = $_GET['i'];

$numerod = '';
$tipofac = '';
$subtotal = '';
$descto = '';
$total = '';
$notas1 = '';
$notas2 = '';
$notas3 = '';
$notas4 = '';
$notas5 = '';

class PDF extends FPDF
{
	var $widths;
	var $aligns;

    // Cabecera de página
	function Header()
	{
		$sucursal = $_SESSION["codsucu"];
		$nrounico = $_GET['i'];

		$querySafact = mssql_query("SELECT a.numerod, a.tipofac, a.codclie, a.ID3, a.Descrip, a.Direc1, A.Direc2, a.Telef, c.Descrip vendedor, ISNULL(a.Descto1/a.FactorP,0) Descto1, NumeroR,
			c.codvend, ISNULL(a.MtoTotal/a.FactorP,0) MtoTotal, a.fechae, a.fechav, a.notas1, a.Descto1, a.Monto, z.Descrip zona, EsCredito, ISNULL((Monto+Mtotax)/a.FactorP,0) SubTotal,
			DATEDIFF(day, a.FechaE, a.FechaV) DiasCredito, Notas1, Notas2, Notas3, Notas4, Notas5
			FROM SAFACT a 
			INNER JOIN SACLIE b ON a.codclie=b.codclie 
			INNER JOIN SAVEND c ON a.codvend = c.CodVend  
			INNER JOIN SAZONA z ON b.CodZona = z.CodZona 
			WHERE a.NroUnico='$nrounico' AND a.CodSucu='$sucursal'");

		$GLOBALS['numerod'] = mssql_result($querySafact,0,"numerod");
		$GLOBALS['tipofac'] = mssql_result($querySafact,0,"tipofac");
		$GLOBALS['subtotal'] = mssql_result($querySafact,0,"SubTotal");
		$GLOBALS['descto'] = mssql_result($querySafact,0,"Descto1");
		$GLOBALS['total'] = mssql_result($querySafact,0,"MtoTotal");
		$GLOBALS['notas1'] = mssql_result($querySafact,0,"Notas1");
		$GLOBALS['notas2'] = mssql_result($querySafact,0,"Notas2");
		$GLOBALS['notas3'] = mssql_result($querySafact,0,"Notas3");
		$GLOBALS['notas4'] = mssql_result($querySafact,0,"Notas4");
		$GLOBALS['notas5'] = mssql_result($querySafact,0,"Notas5");

		$condicionpago = '';
		switch (mssql_result($querySafact,0,"EsCredito")) {
			case 0: $condicionpago = 'CONTADO'; break;
			case 1: $condicionpago = 'CREDITO'; break;
		}

		$cantCaracteresDirect = 65;
		$direccion = mssql_result($querySafact,0,"Direc1").' '.mssql_result($querySafact,0,"Direc2");
		$direccion1 = '';
		if (strlen($direccion) > $cantCaracteresDirect) {
			$direccion1 = trim(substr($direccion, $cantCaracteresDirect, strlen($direccion)));
			$direccion = substr($direccion, 0, $cantCaracteresDirect);
		}

        // datos empresa
		$this->SetFont('Arial','B', 14);
		$this->Cell(106,6, "PEDIDO A NOMBRE DE:",0,0,'L');
		$this->Cell(42,6, utf8_decode(" N°:"),0,0,'R');
		$this->Cell(5);
		$this->SetFont('Arial','B', 16);
		$this->Cell(20,6, mssql_result($querySafact,0,"numerod"),0,1,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(19,4, "Codigo: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(110,4, mssql_result($querySafact,0,"ID3"),0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(25,4, "Fecha de Emision: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(20,4, date('d/m/Y', strtotime(mssql_result($querySafact,0,"fechae"))),0,1,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(19,4, "Razon Social: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(110,4, mssql_result($querySafact,0,"Descrip"),0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(25,4, "Fecha de Vencim.: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(20,4, date('d/m/Y', strtotime(mssql_result($querySafact,0,"fechav"))),0,1,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(19,4, "Direccion: ",0,0,'L');
		$this->SetFont('Arial','B',8);
		$this->Cell(110,4, mssql_result($querySafact,0,"Direc1"),0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(25,4, "Vendedor: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(20,4, mssql_result($querySafact,0,"vendedor"),0,1,'L');
		$this->Cell(19);
		$this->SetFont('Arial','B',8);
		$this->Cell(110,4, mssql_result($querySafact,0,"Direc2"),0,0,'L');
		//$this->Cell(129);
		$this->SetFont('Arial','',8);
		$this->Cell(25,4, "Zona de venta: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(20,4, mssql_result($querySafact,0,"codvend"),0,1,'L');
		//$this->Cell(129);
		$this->SetFont('Arial','',8);
		$this->Cell(16,5, utf8_decode("Teléfonos: "),0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(113,5, mssql_result($querySafact,0,"Telef"),0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(25,4, "Condi. Pago: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(20,4, $condicionpago,0,1,'L');

		$this->SetFont('Arial','',8);
		$this->Cell(12,4, "R.I.F: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(30,4, mssql_result($querySafact,0,"codclie"),0,0,'L');
		$this->Cell(87);
		$this->SetFont('Arial','',8);
		$this->Cell(25,4, "Dias de Credito: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(20,4, mssql_result($querySafact,0,"DiasCredito"),0,1,'L');

        // titulo de la tabla
		$this->Ln(1);
		$this->SetFont('Arial','B',9);
		$this->Cell(10,5,'','LT');
		$this->Cell(42,5,'CANTIDAD','T',0,'L',0);
		$this->Cell(138,5,'PRODUCTO','TR',1,'L',0);

		$this->Cell(21,5,'Botellas','LB',0,'C',0);
		$this->Cell(23,5,'Cajas','B',0,'L',0);
		$this->Cell(17,5,'Codigo','B',0,'L',0);
		$this->Cell(63,5,'Descripcion','B',0,'L',0);
		$this->Cell(30,5,'Precio','B',0,'C',0);
		$this->Cell(36,5,'Total','BR',1,'C',0);
		$this->Ln(2);
	}
    // Pie de página
	function Footer()
	{
        // Posición: a 1,2 cm del final
		$this->SetY(-12);
        // Arial italic 8
		$this->SetFont('Arial','',4);
        // Número de página
		/* $empresa = mssql_query("SELECT Descrip, rif FROM SACONF");
		$i = mssql_fetch_assoc($empresa);
		$this->Cell(0,4,$i["Descrip"].'  '.$i["rif"],0,0,'L'); */
	}

	function SetWidths($w)
	{
        //Set the array of column widths
		$this->widths = $w;
	}

	function SetAligns($a)
	{
        //Set the array of column alignments
		$this->aligns = $a;
	}

	function Row($data)
	{
        //Calculate the height of the row
		$nb = 0;
		for ($i = 0; $i < count($data); $i++)
			$nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
		$h = 5 * $nb;
        //Issue a page break first if needed
		$this->CheckPageBreak($h);
        //Draw the cells of the row
		for ($i = 0; $i < count($data); $i++) {
			$w = $this->widths[$i];
			$a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Save the current position
			$x = $this->GetX();
			$y = $this->GetY();
            //Draw the border
			$this->Rect($x, $y, $w, $h);
            //Print the text
			$this->MultiCell($w, 5, $data[$i], 0, $a);
            //Put the position to the right of the cell
			$this->SetXY($x + $w, $y);
		}
        //Go to the next line
		$this->Ln($h);
	}

	function CheckPageBreak($h)
	{
        //If the height h would cause an overflow, add a new page immediately
		if ($this->GetY() + $h > $this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}

	function NbLines($w, $txt)
	{
        //Computes the number of lines a MultiCell of width w will take
		$cw =& $this->CurrentFont['cw'];
		if ($w == 0)
			$w = $this->w - $this->rMargin - $this->x;
		$wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
		$s = str_replace("\r", '', $txt);
		$nb = strlen($s);
		if ($nb > 0 and $s[$nb - 1] == "\n")
			$nb--;
		$sep = -1;
		$i = 0;
		$j = 0;
		$l = 0;
		$nl = 1;
		while ($i < $nb) {
			$c = $s[$i];
			if ($c == "\n") {
				$i++;
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
				continue;
			}
			if ($c == ' ')
				$sep = $i;
			$l += $cw[$c];
			if ($l > $wmax) {
				if ($sep == -1) {
					if ($i == $j)
						$i++;
				} else
				$i = $sep + 1;
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
			} else
			$i++;
		}
		return $nl;
	}
}

$pdf = new PDF('L', 'mm', array(139.7,215.9));
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);
$pdf->SetAutoPageBreak(false);

$saitemfac = mssql_query("SELECT CodItem, Descrip1, CASE WHEN EsUnid=0 THEN Cantidad ELSE 0 END cajas, CASE WHEN EsUnid=1 THEN Cantidad ELSE 0 END unidades, 
	ISNULL((Precio+(MtoTax/cantidad))/FactorP,0) Preciod, ISNULL((TotalItem+MtoTax)/FactorP,0) Totald FROM SAITEMFAC 
	WHERE NumeroD='$numerod' AND TipoFac='$tipofac' AND CodSucu='$sucursal'");

while ($i = mssql_fetch_assoc($saitemfac)) {
	$pdf->Cell(13,4, number_format($i['unidades'], 0, ',', '.'),'',0,'R',0);
	$pdf->Cell(15,4, number_format($i['cajas'], 0, ',', '.'),'',0,'R',0);
	$pdf->Cell(12);
	$pdf->Cell(22,4,$i['CodItem'],'',0,'C',0);
	$pdf->Cell(64,4,$i['Descrip1'],'',0,'L',0);
	$pdf->Cell(20,4, number_format($i['Preciod'], 2, ',', '.'),'',0,'R',0);
	$pdf->Cell(35,4, number_format($i['Totald'], 2, ',', '.'),'',1,'R',0);
}

$pdf->SetY(-40);
$pdf->Cell(6);
$pdf->SetFont('Arial','',7);
$pdf->Cell(15,3,'Observacion:','',1,'L',0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(6); $pdf->Cell(80,3,$notas1,'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3,$notas2,'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3,$notas3,'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3,$notas4,'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3,$notas5,'',1,'L',0);
$pdf->Ln(2);
$pdf->SetFont('Arial','',6);
$pdf->Cell(6); $pdf->Cell(15,3,'Fecha de Recibido:','',1,'L',0);
$pdf->Cell(6); $pdf->Cell(15,3,'Firma:','',1,'L',0);
$pdf->Cell(6); $pdf->Cell(15,3,'Sello','',1,'L',0);

$pdf->SetY(-22);
$pdf->SetFont('Arial','',8);
$pdf->Cell(110); $pdf->Cell(25,4,'Subtotal $','',0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(24,4, number_format($subtotal, 2, ',', '.'),'',1,'R',0);

$pdf->SetY(-18);
$pdf->SetFont('Arial','',8);
$pdf->Cell(110); $pdf->Cell(25,4,'Descto $','',0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(24,4, number_format($descto, 2, ',', '.'),'',1,'R',0);

$pdf->SetY(-14);
$pdf->SetFont('Arial','',8);
$pdf->Cell(110); $pdf->Cell(25,4,'Total $','',0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(24,4, number_format($total, 2, ',', '.'),'',1,'R',0);

# PARA LA SALIDA DEL PDF
#	I --> abrir en una pestaña
#	D --> descarga automaticamente
$pdf->Output('I',"$numerod.pdf");
?>
