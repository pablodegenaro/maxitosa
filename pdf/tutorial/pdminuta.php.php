<?php
require('../fpdf.php');
require_once ("../../../BD/class.utilbd.php");
require_once ("../../conf/global.php");
$doa=new DOA(BD_NAME,BD_HOST,BD_USUARIO,BD_PASSWORD,DEPURAR);
$id= 39;
class PDF extends FPDF
{
function Header()
{
	global $title;
	//Arial bold 15
	$this->Image('orinoco.PNG',10,8,25);
	$this->SetFont('Arial','B',14);
	//Calculamos ancho y posición del título.
	$w=$this->GetStringWidth($title)+6;
	$this->SetX((210-$w)/2);
	//Colores de los bordes, fondo y texto
	//$this->SetDrawColor(0,80,180);
	$this->SetFillColor(230,230,0);
	$this->SetTextColor(1,50,0);
	//Ancho del borde (1 mm)
	$this->SetLineWidth(20);
	//Título
	$this->Cell($w,0,$title,0,0,'C',true);
	//Salto de línea
	$this->Ln(10);
}

function Footer()
{
	//Posición a 1,5 cm del final
	$this->SetY(-15);
	//Arial itálica 8
	$this->SetFont('Arial','I',8);
	//Color del texto en gris
	$this->SetTextColor(128);
	//Número de página
	$this->Cell(0,10,''.$this->PageNo(),0,0,'C');
}

function ChapterTitle($label)
{
	//Arial 12
	$this->SetFont('Arial','',12);
	//Color de fondo
	//$this->SetFillColor(200,220,255);
	//Título
	$this->Cell(1,6," $num $label",0,1,'L',false);
	//Salto de línea
	$this->Ln(2);
}

function ChapterBody($file)
{
	//Leemos el fichero
	//$f=fopen($file,'r');
	
	//$txt=fread($f,filesize($file));
	
	$txt=$file;
	//fclose($f);
	
	//Times 12
	$this->SetFont('Times','',12);
	//Imprimimos el texto justificado
	$this->MultiCell(0,5,$txt);
	//Salto de línea
	$this->Ln();
	//Cita en itálica
	$this->SetFont('','I');
	//$this->Cell(0,5,'(fin del extracto)');
}

function PrintChapter($num,$title,$file)
{
	$this->AddPage();
	$this->ChapterTitle($title);
	$this->ChapterBody($file);
}
}

//Tabla simple
function BasicTable($header,$data)
{
	//Cabecera
	foreach($header as $col)
		$this->Cell(40,7,$col,1);
	$this->Ln();
	//Datos
	foreach($data as $row)
	{
		foreach($row as $col)
			$this->Cell(40,6,$col,1);
		$this->Ln();
	}
}



$consulta = $doa->QuerySelectWhere("pasante.minuta",'*',"id_minuta = '".$id."'",array('id_minuta',ACENDENTE),'ASSOCC');
while ($row=$doa->Recordsetassoc($consulta))
{
$resultado.=$row['Num_minuta'];
$t.=$row['titulo'];
$fecha.=$row['fecha'];
}

$pdf=new PDF();
$title= 'Minuta de Reunion '.$resultado;
$tminuta= $t;
//$pdf->Cell(40,10,$titulo);
//$pdf->Cell(40,10,$tminuta);
//$pdf->BasicTable($header,$data);
//$pdf->SetTitle($title);
//$pdf->SetAuthor('Julio Verne');
$pdf->PrintChapter(1,$tminuta,$fecha);
//$pdf->PrintChapter(2,'LOS PROS Y LOS CONTRAS','20k_c2.txt');
$pdf->Output();
?>
