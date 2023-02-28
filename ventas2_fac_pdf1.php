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
$tasa = '';
$montoMEx = '';
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
			c.codvend, MtoTotal, a.fechae, a.fechav, a.notas1, a.Descto1, a.Monto, z.Descrip zona, EsCredito, ONumero, OTipo, licencia_licor, 
			factorp, ISNULL(mtototal/factorp,0) montod, ISNULL(Monto,0) SubTotal, ISNULL(a.Descto1/a.FactorP,0) Descto1, TExento, TGravable,
			ISNULL((SELECT Monto FROM SATAXVTA WHERE NumeroD=a.NumeroD AND TipoFac=a.TipoFac AND CodSucu=a.CodSucu AND CodTaxs='IAL' GROUP BY CodTaxs, Monto), 0) AS ial,
			ISNULL((SELECT Monto FROM SATAXVTA WHERE NumeroD=a.NumeroD AND TipoFac=a.TipoFac AND CodSucu=a.CodSucu AND CodTaxs='PVP' GROUP BY CodTaxs, Monto), 0) AS pvp,
			ISNULL((SELECT Monto FROM SATAXVTA WHERE NumeroD=a.NumeroD AND TipoFac=a.TipoFac AND CodSucu=a.CodSucu AND CodTaxs='IVA' GROUP BY CodTaxs, Monto), 0) AS iva,
			Notas1, Notas2, Notas3, Notas4, Notas5
			FROM SAFACT a 
			INNER JOIN SACLIE b ON a.codclie=b.codclie 
			INNER JOIN SACLIE_99 b99 ON a.codclie=b99.codclie 
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
		$GLOBALS['tasa'] = mssql_result($querySafact,0,"factorp");
		$GLOBALS['montoMEx'] = mssql_result($querySafact,0,"montod");
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

		$otipo = '';
		switch (mssql_result($querySafact,0,"OTipo")) {
			case 'C': $otipo = 'Nota de entrega'; break;
			case 'E': $otipo = 'Pedido'; break;
			case 'F': $otipo = 'Presupuesto'; break;
		}

		$this->SetMargins(3, 3, 3);

        // datos empresa
		$this->Ln(10);
		$this->Cell(122);
		$this->SetFont('Arial','', 12);
		$this->Cell(45,4, utf8_decode("Factura Nro:"),0,0,'R');
		$this->SetFont('Arial','B',12);
		$this->Cell(20,4, mssql_result($querySafact,0,"numerod"),0,1,'L');
		$this->SetFont('Arial','', 8);
		$this->Cell(17);
		$this->Cell(31,4, utf8_decode("DATOS DEL CLIENTE"),'',0,'R');
		$this->Cell(45);
		$this->Cell(37,4, utf8_decode("DATOS DEL DOCUMENTO"),'',0,'R');
		//$this->SetY(3);
		$this->Ln(1.5);
		$this->Cell(139);
		$this->SetFont('Arial','',8);
		$this->Cell(30,4, "Fecha de Vencimiento: ",'',0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(17,4, date('d/m/Y', strtotime(mssql_result($querySafact,0,"fechav"))),'',1,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(19,4, "Codigo: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(35,4, mssql_result($querySafact,0,"ID3"),0,0,'L');
		$this->Cell(38);
		$this->SetFont('Arial','',8);
		$this->Cell(18,4, "Nro Pedido:",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(20,4, mssql_result($querySafact,0,"numerod"),'',0,'L');
		
		$this->Cell(9);
		$this->SetFont('Arial','',8);
		$this->Cell(16,4, "Vendedor: ",'',0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(17,4, mssql_result($querySafact,0,"vendedor"),'',1,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(19,4, "Razon Social: ",0,0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(51,4, substr(mssql_result($querySafact,0,"Descrip"),0,43),'',0,'L');
		$this->Cell(22);
		$this->SetFont('Arial','',8);
		$this->Cell(21,4, "Fecha Emision: ",'',0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(16,4, date('d/m/Y', strtotime(mssql_result($querySafact,0,"fechae"))),0,0,'L');
		$this->Cell(10);
		$this->SetFont('Arial','',8);
		$this->Cell(16,4, "Zona Venta: ",'',0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(19,4, mssql_result($querySafact,0,"codvend"),0,1,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(21,4, "Domicilio Fiscal: ",'',0,'L');
		$this->SetFont('Arial','B',8);
		$this->Cell(100,4, mssql_result($querySafact,0,"Direc1"),'',1,'L');
		//$this->Ln(0.7);
		$this->SetFont('Arial','',8);
		$this->Cell(70,4, mssql_result($querySafact,0,"Direc2"),'',0,'L');
		$this->Cell(22);
		$this->SetFont('Arial','',8);
		$this->Cell(21,4, "Cond. de Pago: ",'',0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(16,4, $condicionpago,'',0,'L');
		$this->Cell(71);
		$this->SetFont('Arial','',8);
		$this->Cell(28,3, utf8_decode("Licencia de Licor N°"),'',1,'L');
		//$this->Ln(1);
		$this->SetFont('Arial','',8);
		$this->Cell(8,5, "R.I.F: ",'',0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(18,5, mssql_result($querySafact,0,"codclie"),'',0,'L');
		$this->Cell(8);
		$this->SetFont('Arial','',8);
		$this->Cell(18,4, "Nro. Licencia ",'',0,'L');
		$this->SetFont('Arial','B',8);
		$this->Cell(17,4, utf8_decode(mssql_result($querySafact,0,"licencia_licor")),'',0,'L');
		$this->Cell(24);
		$this->SetFont('Arial','',8);
		$this->Cell(22,5, utf8_decode("Dias de Credito: "),'',0,'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(5,5, mssql_result($querySafact,0,"DiasCredito"),'',0,'L');
		$this->Cell(80);
		$this->SetFont('Arial','',8);
		$this->Cell(28,3, utf8_decode("MY-315-MN-495 REGION"),'',1,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(14,6, utf8_decode("Teléfonos"),'',0,'L');
		$this->SetFont('Arial','B',8.5);
		$this->Cell(21,6, mssql_result($querySafact,0,"Telef"),'',0,'L');
		$this->Cell(20);
		$this->SetFont('Arial','',8);
		$this->Cell(10,6, "Zona:",'',0,'L');
		$this->SetFont('Arial','B',8);
		$this->Cell(21,6, mssql_result($querySafact,0,"zona"),'',0,'L');
		$this->Cell(114);
		$this->SetFont('Arial','',8);
		$this->Cell(28,3, utf8_decode("GUAYANA ESTADO BOLIVAR"),'',1,'L');
		$this->Cell(200);
		$this->SetFont('Arial','',8);
		$this->Cell(28,3, utf8_decode("PATENTE AE NRO. 17650 RUC:"),'',1,'L');
		
		$this->Ln(2);
        // titulo de la tabla
		$this->SetFont('Arial','B',8.4);
		$this->Cell(4,5.5,'','LT');
		$this->Cell(13,5.5,'Codigo','T',0,'C',0);
		$this->Cell(4,5.5,'','T');
		$this->Cell(14,5.5,'Cantidad','T',0,'C',0);
		$this->Cell(35,5.5,'','T');
		$this->Cell(36,5.5,'Denominacion Comercial','T',0,'C',0);
		$this->Cell(24,5.5,'','T');		
		$this->Cell(6,5.5,'Precio','T',0,'C',0);
		$this->Cell(14,5.5,'','T');
		$this->Cell(8,5.5,'Precio','T',0,'C',0);
		$this->Cell(25,5.5,'','T');
		$this->Cell(22,5.5,'Total Mercancia','T',0,'C',0);
		$this->Cell(20,5.5,'','T');
		$this->Cell(14,5.5,'Total Imp.','T',0,'C',0);
		$this->Cell(19,5.5,'','TR',1);

		$this->Ln(-3);
		$this->SetFont('Arial','B',8.4);
		$this->Cell(38,5.5,'','L');
		$this->Cell(25,5.5,'Lts   Grado    CAP','',0,'C',0);
		$this->Cell(100,5.5,'','');
		$this->Cell(17,5.5,'Desc. %','',0,'C',0);
		$this->Cell(30,5.5,'','');
		$this->Cell(10,5.5,'IVA %','',0,'C',0);
		$this->Cell(26,5.5,'','');
		$this->Cell(10,5.5,'TOTAL','',0,'C',0);
		$this->Cell(2,5.5,'','R',1);
		
		$this->Ln(-3.6);
		$this->SetFont('Arial','B',8.4);
		$this->Cell(4,5.5,'','LB');
		$this->Cell(13,5.5,'Producto','B',0,'C',0);
		$this->Cell(5,5.5,'','B');
		$this->Cell(12,5.5,'Caj/Bot','B',0,'C',0);
		$this->Cell(46,5.5,'','B');
		$this->Cell(19,5.5,'de la Especie','B',0,'C',0);
		$this->Cell(25,5.5,'','B');		
		$this->Cell(18,5.5,'Sugerido','B',0,'C',0);
		$this->Cell(6,5.5,'','B');
		$this->Cell(12,5.5,'Caj/Bot','B',0,'C',0);
		$this->Cell(24,5.5,'','B');
		$this->Cell(20,5.5,'Base Imponible','B',0,'C',0);
		$this->Cell(23,5.5,'','B');
		$this->Cell(9,5.5,'Art 18','B',0,'C',0);
		$this->Cell(22,5.5,'','RB',1);
		$this->Ln(2);
	}
    // Pie de página
	function Footer()
	{
        // Posición: a 1,2 cm del final
		$this->SetY(-12);
        // Arial italic 8
		$this->SetFont('Arial','',4);
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

$pdf = new PDF('L', 'mm', array(215.9,266.7));
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(3, 3, 3);
$pdf->SetFont('Arial','',8);
$pdf->SetAutoPageBreak(false);

$cajas = 0;
$botellas = 0;
$litros = 0;
$kilos = 0;

$saitemfac = mssql_query("SELECT item.CodItem, Descrip1, item.Precio, TotalItem, item.MtoTax, Descto, item.MtoTax+TotalItem Total, grado_alcoholico, capacidad_botella, p99.sugerido,
	CASE WHEN EsUnid=0 THEN Cantidad ELSE 0 END cajas, 
	CASE WHEN EsUnid=1 THEN Cantidad ELSE p.CantEmpaq*Cantidad END unidades, 
	CASE WHEN EsUnid=1 THEN ((p.Volumen/CantEmpaq)*Cantidad) ELSE (p.Volumen*Cantidad) END litros, 
	CASE WHEN EsUnid=1 THEN ((p.Peso/CantEmpaq)*Cantidad) ELSE (p.Peso*Cantidad) END kilos, 
	ISNULL((SELECT Monto FROM SATAXITF WHERE NumeroD=item.NumeroD AND TipoFac=item.TipoFac AND CodItem=item.CodItem AND NroLinea=item.NroLinea AND CodSucu=item.CodSucu AND CodTaxs='IAL' GROUP BY CodTaxs, CodItem, Monto), 0) AS ial,
	ISNULL((SELECT Monto FROM SATAXITF WHERE NumeroD=item.NumeroD AND TipoFac=item.TipoFac AND CodItem=item.CodItem AND NroLinea=item.NroLinea AND CodSucu=item.CodSucu AND CodTaxs='PVP' GROUP BY CodTaxs, CodItem, Monto), 0) AS pvp,
	ISNULL((SELECT Monto FROM SATAXITF WHERE NumeroD=item.NumeroD AND TipoFac=item.TipoFac AND CodItem=item.CodItem AND NroLinea=item.NroLinea AND CodSucu=item.CodSucu AND CodTaxs='IVA' GROUP BY CodTaxs, CodItem, Monto), 0) AS iva,
	ISNULL((SELECT CONVERT(INT, MtoTax) FROM SATAXITF WHERE NumeroD=item.NumeroD AND TipoFac=item.TipoFac AND CodItem=item.CodItem AND NroLinea=item.NroLinea AND CodSucu=item.CodSucu AND CodTaxs='IVA'), 0) AS porc_iva
	FROM SAITEMFAC item 
	INNER JOIN SAFACT f ON f.NumeroD=item.NumeroD AND item.TipoFac='$tipofac' 
	INNER JOIN SAPROD p ON p.CodProd=item.CodItem 
	INNER JOIN SAPROD_99 p99 ON item.CodItem=p99.CodProd
	WHERE item.CodSucu='$sucursal' AND item.NumeroD='$numerod'");


while ($i = mssql_fetch_assoc($saitemfac)) {
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(2);
	$pdf->Cell(15,4,$i['CodItem'],'',0,'L',0);
	$pdf->Cell(6);
	$pdf->Cell(10,4, number_format($i['cajas'], 0).'  /  '.number_format($i['unidades'], 0),'',0,'C',0);
	$pdf->Cell(3);
	$pdf->Cell(8,4, number_format($i['litros'], 2, ',', '.'),'',0,'R',0);
	$pdf->Cell(8,4, number_format($i['grado_alcoholico'], 0),'',0,'R',0);
	$pdf->Cell(11,4, number_format($i['capacidad_botella'], 2, ',', '.'),'',0,'R',0);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(59,4, $i['Descrip1'],'',0,'L',0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(16,4, number_format($i['sugerido'], 2, ',', '.'),'',0,'R',0);
	$pdf->Cell(23,4, number_format($i['Precio'], 2, ',', '.'),'',0,'R',0);
	$pdf->Cell(14,4, number_format($i['Descto'], 2, ',', '.'),'',0,'R',0);
	$pdf->Cell(26,4, number_format($i['TotalItem'], 2, ',', '.'),'',0,'R',0);
	$pdf->Cell(5);
	$pdf->Cell(13,4, number_format($i['porc_iva'], 0, ',', '.').' %','',0,'R',0);
	$pdf->Cell(19,4, number_format($i['ial'], 2, ',', '.'),'',0,'R',0);
	$pdf->Cell(19,4, number_format($i['TotalItem'] + $i['pvp'] + $i['ial'], 2, ',', '.'),'',1,'R',0);

	$cajas += intval($i['cajas']);
	$botellas += intval($i['unidades']);
	$litros += floatval($i['litros']);
	$kilos += floatval($i['kilos']);
}

$pdf->SetY(-49);
$pdf->SetFont('Arial','',8);
//$pdf->Cell(6);
$pdf->Cell(13,3,'Total','',0,'C',0);
$pdf->Cell(13,3,'Total','',0,'C',0);
$pdf->Cell(13,3,'Total','',0,'C',0);
$pdf->Cell(13,3,'Total','',1,'C',0);
//$pdf->Cell(6);
$pdf->Cell(13,3,'Cajas','',0,'C',0);
$pdf->Cell(13,3,'Botellas','',0,'C',0);
$pdf->Cell(13,3,'Litros','',0,'C',0);
$pdf->Cell(13,3,'Kgrs.','',1,'C',0);
//$pdf->Cell(8);
$pdf->Cell(13,4, number_format($cajas, 2, ',', '.'),'',0,'C',0);
$pdf->Cell(13,4, number_format($botellas, 2, ',', '.'),'',0,'C',0);
$pdf->Cell(13,4, number_format($litros, 2, ',', '.'),'',0,'C',0);
$pdf->Cell(13,4, number_format($kilos, 2, ',', '.'),'',1,'C',0);
$pdf->SetFont('Arial','',8);
$pdf->Ln(1);
$pdf->Cell(6);
$pdf->Cell(15,3,'OBSERVACIONES:','',1,'L',0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(6); $pdf->Cell(80,3, $notas1,'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3, $notas2,'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3, $notas3,'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3, $notas4,'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3, $notas5,'',1,'L',0);
$pdf->Ln(1);
$pdf->Cell(6); $pdf->Cell(80,3, utf8_decode('-Se emitirá N/D por diferencial de precio debido a variación T/C en'),'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3, utf8_decode('fecha de pago'),'',1,'L',0);
$pdf->Ln(1);
$pdf->Cell(6); $pdf->Cell(80,3, '-T/C BCV: '.number_format($tasa, 2, ',', '.'),'',1,'L',0);
$pdf->Cell(6); $pdf->Cell(80,3, '-Monto Factura: '.number_format($montoMEx, 2, ',', '.').' $','',1,'L',0);

$pdf->SetY(-33);
$pdf->SetFont('Arial','',8);
$pdf->Cell(100); $pdf->Cell(15,3.5,'TRANSPORTISTA:','',1,'L',0);
$pdf->Cell(100); $pdf->Cell(15,3.5,'CONDUCTOR:','',1,'L',0);
$pdf->Cell(100); $pdf->Cell(15,3.5,'CI:','',1,'L',0);
$pdf->Cell(100); $pdf->Cell(15,3.5,'VEHICULO:','',1,'L',0);
$pdf->Cell(100); $pdf->Cell(15,3.5,'PLACA:','',1,'L',0);

$pdf->SetY(-33);
$pdf->SetFont('Arial','',8);
$pdf->Cell(160); $pdf->Cell(30,3.5,'DATOS RECEPCION','',1,'L',0);
$pdf->Cell(160); $pdf->Cell(30,3.5,'FECHA RECIBIDO:','',1,'L',0);
$pdf->Cell(160); $pdf->Cell(30,3.5,'FIRMA:','',1,'L',0);
$pdf->Cell(160); $pdf->Cell(30,3.5,'SELLO:','',1,'L',0);

$pdf->SetY(-40);
$pdf->SetFont('Arial','',8);
$pdf->Cell(205); $pdf->Cell(35,4,'SUBTOTAL','',0,'L',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($subtotal, 2, ',', '.'),'',1,'R',0);
$pdf->Cell(205); $pdf->Cell(35,4,'DESCUENTO','',0,'L',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($descto, 2, ',', '.'),'',1,'R',0);
$pdf->Cell(205); $pdf->Cell(35,4,'EXENTO','',0,'L',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($exento, 2, ',', '.'),'',1,'R',0);
$pdf->Cell(205); $pdf->Cell(35,4,'BASE IMPONIBLE','',0,'L',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($base_imponible, 2, ',', '.'),'',1,'R',0);
$pdf->Cell(205); $pdf->Cell(35,4,'IVA 16%','',0,'L',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($imp16, 2, ',', '.'),'',1,'R',0);
$pdf->Cell(205); $pdf->Cell(35,4,'IMPUESTO ART. 18 PVP:','',0,'L',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($ial, 2, ',', '.'),'',1,'R',0);
$pdf->Cell(205); $pdf->Cell(35,4,'TOTAL GENERAL:','',0,'L',0);
$pdf->Cell(10);  $pdf->Cell(10,4, number_format($total, 2, ',', '.'),'',1,'R',0);

# PARA LA SALIDA DEL PDF
#	I --> abrir en una pestaña
#	D --> descarga automaticamente
$pdf->Output('I',"$numerod.pdf");
?>
