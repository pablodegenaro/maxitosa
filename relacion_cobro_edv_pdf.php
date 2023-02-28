<?php
require("conexion.php");
require("funciones.php");
require('pdf/fpdf.php');
set_time_limit(0);
$id = $_GET['i'];
class PDF extends FPDF
{
	function Header()
	{
		$this->Image('images/logotri.jpg',10,10,20);
		$this->SetFont('Arial','B',8);
		$this->Cell(80);
		$consul_empresa = mssql_query("SELECT Descrip from SACONF");
		$consul_empresa1 =  mssql_result($consul_empresa, 0, 'DESCRIP'); 
        $this->Cell(30,10,'Relacion de Facutura para Cobro',0,0,'C');
        $this->Ln();
        $this->Cell(0,10,$consul_empresa1,0,0,'C');
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

$query = mssql_query("SELECT id_relacion, numerod, codclie, rsocial, emision, vencimiento, monto, ven.CodVend, ven.descrip, CONCAT(ven.CodVend, '-',ven.Descrip) Vendedor, sucu.Descrip AS Sucursal, tipofac 
    FROM app_relacion_cobros_items i
    INNER JOIN SASUCURSAL sucu ON sucu.CodSucu = i.codsucu
    INNER JOIN SAVEND ven ON ven.CodVend = i.vendedor
    WHERE id_relacion='$id' ORDER BY emision ASC");

if (mssql_num_rows($query) > 0) 
{
    $NroRela = mssql_result($query,0,"id_relacion");
    $fechae = mssql_result($query,$m,"emision");
    $edv = mssql_result($query,$m,"descrip");

    $pdf->Cell(50,7,utf8_decode('Nro de Relación: ').str_pad($NroRela, 8, 0, STR_PAD_LEFT),0,0,C);
    $pdf->Ln();
    $pdf->Cell(50,7,utf8_decode('Fecha: ').$fechae,0,0,C);
    $pdf->Ln();
    $pdf->Cell(180,7,utf8_decode('Representante de Ventas: ').$edv,0,0,R);
    $pdf->Ln();
    $pdf->Cell(62,7,'Listado de Documentos',0,0,C);
    $pdf->Ln();
    $pdf->SetFillColor(200,220,255);
    $pdf->Cell(5,7,' ',0,0,C);
    $pdf->Cell(20,7,'Nro Fact',1,0,C,true);
    $pdf->Cell(22,7,'Fecha E',1,0,C,true);
    $pdf->Cell(22,7,'Fecha V',1,0,C,true);
    $pdf->Cell(10,7,'Ruta',1,0,C,true);
    $pdf->Cell(20,7,'CodCliente',1,0,C,true);
    $pdf->Cell(70,7,'Cliente',1,0,C,true);
    $pdf->Cell(20,7,'Total',1,0,C,true);
    $pdf->Ln();
    $pdf->SetFont('Arial','',7);
    for ($m=0; $m<mssql_num_rows($query); $m++) {
        $pdf->Cell(5,7,' ',0,0,C);
        $pdf->Cell(20,7,mssql_result($query,$m,"numerod"),1,0,C);
        $pdf->Cell(22,7,mssql_result($query,$m,"emision"),1,0,C);
        $pdf->Cell(22,7,mssql_result($query,$m,"vencimiento"),1,0,C);
        $pdf->Cell(10,7,mssql_result($query,$m,"codvend"),1,0,C);
        $pdf->Cell(20,7,mssql_result($query,$m,"codclie"),1,0,C);
        $pdf->Cell(70,7,mssql_result($query,$m,"rsocial"),1,0,C);
        $pdf->Cell(20,7,rdecimal(mssql_result($query,$m,"monto"), 2),1,0,C);
        $pdf->Ln();
    }

    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Cell(62,7,'_____________________',0,0,C);
    $pdf->Cell(62,7,'________________',0,0,C);
    $pdf->Cell(62,7,'___________________',0,0,C);
    $pdf->Ln();
    $pdf->Cell(62,7,'Preparado ',0,0,C);
    $pdf->Cell(62,7,'Conformado ',0,0,C);
    $pdf->Cell(62,7,'Recibido',0,0,C);
    $pdf->Ln();
} else {
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Cell(62,7,'Sin Registros',0,0,C);
}

$pdf->Output();
?>
