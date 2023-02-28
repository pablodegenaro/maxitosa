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
$exento = '';
$base_imponible = '';
$imp16 = '';
$ial = '';
$imp18 = '';
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

		$querySafact = mssql_query("SELECT a.numerod, a.tipofac, a.codclie, a.ID3, a.Descrip, a.Direc1, A.Direc2, a.Telef, c.Descrip vendedor, DATEDIFF(day, a.FechaE, a.FechaV) DiasCredito, NumeroR,
			c.codvend, MtoTotal, a.fechae, a.fechav, a.notas1, a.Descto1, a.Monto, z.Descrip zona, EsCredito, ONumero, OTipo,
			ISNULL(Monto,0) SubTotal, ISNULL(a.Descto1/a.FactorP,0) Descto1, TExento, TGravable,
			ISNULL((SELECT Monto FROM SATAXVTA WHERE NumeroD=a.NumeroD AND TipoFac=a.TipoFac AND CodSucu=a.CodSucu AND CodTaxs='IAL' GROUP BY CodTaxs, Monto), 0) AS ial,
			ISNULL((SELECT Monto FROM SATAXVTA WHERE NumeroD=a.NumeroD AND TipoFac=a.TipoFac AND CodSucu=a.CodSucu AND CodTaxs='PVP' GROUP BY CodTaxs, Monto), 0) AS pvp,
			ISNULL((SELECT Monto FROM SATAXVTA WHERE NumeroD=a.NumeroD AND TipoFac=a.TipoFac AND CodSucu=a.CodSucu AND CodTaxs='IVA' GROUP BY CodTaxs, Monto), 0) AS iva,
			Notas1, Notas2, Notas3, Notas4, Notas5
			FROM SAFACT a 
			INNER JOIN SACLIE b ON a.codclie=b.codclie 
			INNER JOIN SAVEND c ON a.codvend = c.CodVend  
			INNER JOIN SAZONA z ON b.CodZona = z.CodZona 
			WHERE a.NroUnico='$nrounico' AND a.CodSucu='$sucursal'");

		$GLOBALS['numerod'] = mssql_result($querySafact,0,"numerod");
		$GLOBALS['tipofac'] = mssql_result($querySafact,0,"tipofac");
		$GLOBALS['subtotal'] = mssql_result($querySafact,0,"SubTotal");
		$GLOBALS['descto'] = mssql_result($querySafact,0,"Descto1");
		$GLOBALS['exento'] = mssql_result($querySafact,0,"TExento");
		$GLOBALS['base_imponible'] = mssql_result($querySafact,0,"TGravable");
		$GLOBALS['imp16'] = mssql_result($querySafact,0,"iva");
		$GLOBALS['ial'] = mssql_result($querySafact,0,"ial");
		$GLOBALS['imp18'] = mssql_result($querySafact,0,"pvp");
		$GLOBALS['total'] = mssql_result($querySafact,0,"MtoTotal");
		$GLOBALS['notas1'] = mssql_result($querySafact,0,"Notas1");
		$GLOBALS['notas2'] = mssql_result($querySafact,0,"Notas2");
		$GLOBALS['notas3'] = mssql_result($querySafact,0,"Notas3");
		$GLOBALS['notas4'] = mssql_result($querySafact,0,"Notas4");
		$GLOBALS['notas5'] = mssql_result($querySafact,0,"Notas5");

		$cantCaracteresDirect = 60;
		$direccion = mssql_result($querySafact,0,"Direc1").' '.mssql_result($querySafact,0,"Direc2");
		$direccion1 = '';
		if (strlen($direccion) > $cantCaracteresDirect) {
			$direccion1 = trim(substr($direccion, $cantCaracteresDirect, strlen($direccion)));
			$direccion = substr($direccion, 0, $cantCaracteresDirect);
		}

        // datos empresa
		$this->Cell(140);
		$this->SetFont('Arial','', 7);
		$this->Cell(45,4, utf8_decode("NOTA DE CREDITO"),0,1,'R');
		$this->Cell(165);
		$this->SetFont('Arial','B',7);
		$this->Cell(20,4, mssql_result($querySafact,0,"numerod"),0,1,'L');
		$this->Ln(10);
		$this->SetFont('Arial','',8);
		$this->Cell(16,4, "Codigo: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(35,4, mssql_result($querySafact,0,"ID3"),0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(12,4, "R.I.F: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(30,4, mssql_result($querySafact,0,"codclie"),0,0,'L');
		$this->Cell(33);
		$this->SetFont('Arial','',8);
		$this->Cell(29,4, "Factura: ",0,0,'R');
		$this->SetFont('Arial','',9);
		$this->Cell(19,4, mssql_result($querySafact,0,"NumeroR"),0,1,'R');
		$this->SetFont('Arial','',8);
		$this->Cell(19,4, "Razon Social: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(107,4, mssql_result($querySafact,0,"Descrip"),0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(29,4, "Fecha de Emision: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(19,4, date('d/m/Y', strtotime(mssql_result($querySafact,0,"fechae"))),0,1,'R');
		$this->SetFont('Arial','',8);
		$this->Cell(19,4, "Direccion: ",0,0,'L');
		$this->SetFont('Arial','B',8);
		$this->Cell(107,4, mssql_result($querySafact,0,"Direc1"),0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(29,4, "Fecha de Vencim.: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(19,4, date('d/m/Y', strtotime(mssql_result($querySafact,0,"fechav"))),0,1,'R');
		$this->Cell(19);
		$this->SetFont('Arial','B',8);
		$this->Cell(107,4, mssql_result($querySafact,0,"Direc2"),0,0,'L');
		$this->Cell(48,4,'',0,1);
		$this->Cell(1);
		$this->SetFont('Arial','',8);
		$this->Cell(16,5, utf8_decode("Teléfonos: "),0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(110,5, mssql_result($querySafact,0,"Telef"),0,1,'L');

        // titulo de la tabla
		$this->SetFont('Arial','B',9);
		$this->Cell(20,5.5,'Codigo','LTB',0,'C',0);
		$this->Cell(32,5.5,'Caj/Bot','TB',0,'L',0);
		$this->Cell(75,5.5,'Litros  GL  N/I  Descripcion','TB',0,'L',0);
		$this->Cell(22,5.5,'Precio','TB',0,'C',0);
		$this->Cell(17,5.5,'% IVA','TB',0,'C',0);
		$this->Cell(27,5.5,'Importe','TBR',1,'C',0);
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

$saitemfac = mssql_query("SELECT item.CodItem, Descrip1, Precio, item.MtoTax, TotalItem, CASE WHEN EsUnid=0 THEN Cantidad ELSE 0 END cajas, CASE WHEN EsUnid=1 THEN Cantidad ELSE p.CantEmpaq*Cantidad END unidades, 
	ISNULL((SELECT Monto FROM SATAXITF WHERE NumeroD=item.NumeroD AND TipoFac=item.TipoFac AND CodItem=item.CodItem AND NroLinea=item.NroLinea AND CodSucu=item.CodSucu AND CodTaxs='IAL' GROUP BY CodTaxs, CodItem, Monto), 0) AS ial,
	ISNULL((SELECT Monto FROM SATAXITF WHERE NumeroD=item.NumeroD AND TipoFac=item.TipoFac AND CodItem=item.CodItem AND NroLinea=item.NroLinea AND CodSucu=item.CodSucu AND CodTaxs='PVP' GROUP BY CodTaxs, CodItem, Monto), 0) AS pvp,
	ISNULL((SELECT Monto FROM SATAXITF WHERE NumeroD=item.NumeroD AND TipoFac=item.TipoFac AND CodItem=item.CodItem AND NroLinea=item.NroLinea AND CodSucu=item.CodSucu AND CodTaxs='IVA' GROUP BY CodTaxs, CodItem, Monto), 0) AS iva,
	ISNULL((SELECT CONVERT(INT, MtoTax) FROM SATAXITF WHERE NumeroD=item.NumeroD AND TipoFac=item.TipoFac AND CodItem=item.CodItem AND NroLinea=item.NroLinea AND CodSucu=item.CodSucu AND CodTaxs='IVA'), 0) AS porc_iva
	FROM SAITEMFAC item INNER JOIN SAFACT f ON f.NumeroD=item.NumeroD AND item.TipoFac='$tipofac' INNER JOIN SAPROD p ON p.CodProd=item.CodItem
	WHERE item.CodSucu='$sucursal' AND item.NumeroD='$numerod'");
while ($i = mssql_fetch_assoc($saitemfac)) {
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(15,4,$i['CodItem'],'',0,'L',0);
	$pdf->Cell(20,4, number_format($i['cajas'], 0).' / '.number_format($i['unidades'], 0),'',0,'C',0);
	$pdf->Cell(16);
	$pdf->Cell(75,4,$i['Descrip1'],'',0,'L',0);
	$pdf->Cell(18,4, number_format($i['Precio'], 2, ',', '.'),'',0,'R',0);
	$pdf->Cell(18,4, number_format($i['porc_iva'], 0),'',0,'R',0);
	$pdf->Cell(2);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(25,4, number_format($i['Precio']+$i['iva'], 2, ',', '.'),'',1,'R',0);
}

$pdf->SetY(-22);
$pdf->SetFont('Arial','',8);
$pdf->Cell(6); $pdf->Cell(80,3, $notas1,'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3, $notas2,'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3, $notas3,'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3, $notas4,'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3, $notas5,'',1,'L',0);

$pdf->SetY(-40);
$pdf->SetFont('Arial','',8);
$pdf->Cell(148); $pdf->Cell(20,4,'SubTotal','',0,'R',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($subtotal, 2, ',', '.'),'',1,'R',0);
$pdf->Cell(148); $pdf->Cell(20,4,'Descuento','',0,'R',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($descto, 2, ',', '.'),'',1,'R',0);
$pdf->Cell(148); $pdf->Cell(20,4,'Monto Exento','',0,'R',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($exento, 2, ',', '.'),'',1,'R',0);
$pdf->Cell(148); $pdf->Cell(20,4,'Base Imponible','',0,'R',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($base_imponible, 2, ',', '.'),'',1,'R',0);
$pdf->Cell(148); $pdf->Cell(20,4,'Impuestos 16%','',0,'R',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($imp16, 2, ',', '.'),'',1,'R',0);
$pdf->Cell(148); $pdf->Cell(20,4,'IMPUESTO ART. 18 PVP:','',0,'R',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($imp18, 2, ',', '.'),'',1,'R',0);
$pdf->Cell(148); $pdf->Cell(20,4,'Total Bs:','',0,'R',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($total, 2, ',', '.'),'',1,'R',0);

# PARA LA SALIDA DEL PDF
#	I --> abrir en una pestaña
#	D --> descarga automaticamente
$pdf->Output('I',"$numerod.pdf");
?>
