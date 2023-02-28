<?php
date_default_timezone_set('America/Caracas');
session_start();
set_time_limit(0);
session_name('S1sTem@RsIsT3m@#$%$@pP');
ini_set('memory_limit', '512M');
require("conexion.php");
require("funciones.php");
require('fpdf/fpdf.php');

$marca = $_GET['marca'];
$instap = $_GET['instap'];
$insta = $_GET['insta'];
$prove = $_GET['prove'];
$orden = $_GET['orden'];
$divisa = $_GET['divisa'];
$p1 = isset($_GET['p1']) ? 1 : 0;
$p2 = isset($_GET['p2']) ? 1 : 0;
$p3 = isset($_GET['p3']) ? 1 : 0;
$p4 = isset($_GET['p4']) ? 1 : 0;
$p5 = isset($_GET['p5']) ? 1 : 0;
$p6 = isset($_GET['p6']) ? 1 : 0;
$p7 = isset($_GET['p7']) ? 1 : 0;
$p8 = isset($_GET['p8']) ? 1 : 0;
$exis = isset($_GET['exis']) ? 1 : 0;


$p_1 = str_replace("1","1",$_GET['p1']);
$p_2 = str_replace("1","2",$_GET['p2']);
$p_3 = str_replace("1","3",$_GET['p3']);
$p_4 = str_replace("1","4",$_GET['p4']);
$p_5 = str_replace("1","5",$_GET['p5']);
$p_6 = str_replace("1","6",$_GET['p6']);
$p_7 = str_replace("1","7",$_GET['p7']);
$p_8 = str_replace("1","8",$_GET['p8']);

if ($divisa != 0) {
    $divi='$';
}else{
    $divi='Bs';
}

$sumap = $p1 + $p2 + $p3 + $p4+ $p5+ $p6+ $p7+ $p8;

$sumap2 = $p_1 + $p_2 + $p_3+ $p_4+ $p_5+ $p_6+ $p_7+ $p_8;
$pAux = '';
$i = 0;
$j = 0;
$documentsize = 'Legal';
$width = array();
$info = array();

function addWidthInArray($num){
    $GLOBALS['width'][$GLOBALS['i']] = $num;
    $GLOBALS['i'] = $GLOBALS['i'] + 1;
    return $num;
}

function addInfoInArray($info){
    $GLOBALS['info'][$GLOBALS['j']] = $info;
    $GLOBALS['j'] = $GLOBALS['j'] + 1;
}

class PDF extends FPDF
{
	function Header()
	{
		$this->Image('images/logotri.jpg',10,10,20);
		$this->SetFont('Arial','B',10);
		$this->Cell(80);
		$consul_empresa = mssql_query("SELECT top 1 Descrip from SACONF");
		$consul_empresa1 =  mssql_result($consul_empresa, 0, 'DESCRIP'); 
        $this->Cell(115,10,'LISTA DE PRECIOS',0,0,'C');
        $this->Ln();
        $this->Cell(0,11,$consul_empresa1,0,0,'C');
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
$pdf->AddPage('L');

$marcas = '()';
$aux2 = "";
foreach ($marca as $num) { $aux2 .= "'$num',"; }
$marcas = "(" . substr($aux2, 0, strlen($aux2)-1) . ")";

$instaciaspadre = '()';
if (count($instap) > 0) {
    $aux3 = "";
    foreach ($instap as $num) { $aux3 .= "$num,"; }
    $instaciaspadre = "(" . substr($aux3, 0, strlen($aux3)-1) . ")";
}

$instaciashijo = '()';
if (count($insta) > 0) {
    $aux4 = "";
    foreach ($insta as $num) { $aux4 .= "$num,"; }
    $instaciashijo = "(" . substr($aux4, 0, strlen($aux4)-1) . ")";
}

$proveedores = '()';
$aux5 = "";
foreach ($prove as $num) { $aux5 .= "'$num',"; }
$proveedores = "(" . substr($aux5, 0, strlen($aux5)-1) . ")";

$saconf = mssql_query("SELECT top 1 FactorM from SACONF");
$factor =  mssql_result($saconf, 0, 'FactorM'); 

/*calculo del ancho adicional para mantener el orden de las celdas de acuerdo a su seleccion segun las siguientes premisas:
    * para el ancho de las celdas que son dinamicas son p1=25, p2=25, p3=25 
    * si solo aparece visualmente un precio
    * si aparece visualmente dos precios
    * si aparecen los 3 precios, no se suma nada.
*/
$anchoAdicional = 0;
switch ($sumap) {
    case 1:
        $anchoAdicional += (44*2);// +22+22
        break;
        case 2:
        $anchoAdicional += (22*2);// +20
        break;
        default: /** 0 || 3**/
        $anchoAdicional += 0;// +0
    }

    $inst_padre = mssql_query("SELECT CODINST, DESCRIP, INSPADRE FROM VW_ADM_INSTANCIAS WHERE INSPADRE=0 AND CODINST IN $instaciaspadre");
    for ($p = 0; $p < mssql_num_rows($inst_padre); $p++) 
    {
        $insp_id = mssql_result($inst_padre, $p, "CODINST");

        $pdf->SetFont('Arial','B',11);
        $pdf->SetFillColor(94,119,187);
        $pdf->Cell(275,8, mssql_result($inst_padre, $p, "DESCRIP") ,1,1,L,true);

        $inst_hijo = mssql_query("SELECT CODINST, DESCRIP, INSPADRE FROM VW_ADM_INSTANCIAS WHERE INSPADRE=$insp_id AND CODINST IN $instaciashijo");
        for ($h = 0; $h < mssql_num_rows($inst_hijo); $h++) 
        {
            $ins_id = mssql_result($inst_hijo, $h, "CODINST");

            $i = 0;
            $width = array();
            $pdf->SetFont('Arial','B',9);
            $pdf->SetFillColor(176,196,222);
            $pdf->Cell(275,7, mssql_result($inst_hijo, $h, "DESCRIP"),'LRT',1,L,true);
        //
            $pdf->SetFont('Arial','B',7.5);
            $pdf->Cell(addWidthInArray(20),7,'CODIGO',1,0,C,true);
            $proporcion = 0.40;
            if ($p6==1 || $p7==1 || $p8==1) { 
                $proporcion = 0.20;
            }
            $pdf->Cell(addWidthInArray(46 + ($anchoAdicional*$proporcion)),7,'DESCRIPCION',1,0,C,true);
            $pdf->Cell(addWidthInArray(28),7,'SUB CATEGORIA',1,0,C,true);
            $pdf->Cell(addWidthInArray(12),7,'CAP.',1,0,C,true);
            $pdf->Cell(addWidthInArray(12),7,'EMP.',1,0,C,true);
            switch ($sumap) {
                case 1:
                $text = '';
                switch ($sumap2) {
                    case 1: $text = 'SUR'; break;
                    case 2: $text = 'CASCO'; break;
                    case 3: $text = 'MAYOR'; break;
                    case 4: $text = 'CONV. DIAGEO'; break;
                    case 5: $text = 'CONV. EURO'; break;
                    case 6: $text = 'CONV. CALLCENTER'; break;
                    case 7: $text = 'CONV. EMPLEADO'; break;
                    case 8: $text = 'CONV. MAYORISTA'; break;
                }
                $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*0.25)),7,''.$text.' CAJA '.$divi.'',1,0,C,true);
                $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*0.25)),7,''.$text.' BOT. '.$divi.'',1,0,C,true);
                break;
                case 2:
                $proporcion1 = 0.125;
                $proporcion2 = 0.125;
                if ($p6==1 || $p7==1 || $p8==1) { 
                    $proporcion1 = 0.20;
                    $proporcion2 = 0.20;
                }

                $text = '';
                if ($p1==1) { 
                    $text = 'SUR';
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion1)),7,''.$text.' CAJA '.$divi.'',1,0,C,true);
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion1)),7,''.$text.' BOT. '.$divi.'',1,0,C,true);
                }
                if ($p2==1) { 
                    $text = 'CASCO';
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion1)),7,''.$text.' CAJA '.$divi.'',1,0,C,true);
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion1)),7,''.$text.' BOT. '.$divi.'',1,0,C,true);
                }
                if ($p3==1) { 
                    $text = 'MAYOR';
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion1)),7,''.$text.' CAJA '.$divi.'',1,0,C,true);
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion1)),7,''.$text.' BOT. '.$divi.'',1,0,C,true);
                }
                if ($p4==1) { 
                    $text = 'DIAGEO';
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion1)),7,''.$text.' CAJA '.$divi.'',1,0,C,true);
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion1)),7,''.$text.' BOT. '.$divi.'',1,0,C,true);
                }
                if ($p5==1) { 
                    $text = 'EURO';
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion1)),7,''.$text.' CAJA '.$divi.'',1,0,C,true);
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion1)),7,''.$text.' BOT. '.$divi.'',1,0,C,true);
                }
                if ($p6==1) { 
                    $text = 'CALLCENTER';
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion2)),7,''.$text.' CAJA '.$divi.'',1,0,C,true);
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion2)),7,''.$text.' BOT. '.$divi.'',1,0,C,true);
                }
                if ($p7==1) { 
                    $text = 'EMPLEADO';
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion2)),7,''.$text.' CAJA '.$divi.'',1,0,C,true);
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion2)),7,''.$text.' BOT. '.$divi.'',1,0,C,true);
                }
                if ($p8==1) { 
                    $text = 'MAYORISTA';
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion2)),7,''.$text.' CAJA '.$divi.'',1,0,C,true);
                    $pdf->Cell(addWidthInArray(22 + ($anchoAdicional*$proporcion2)),7,''.$text.' BOT. '.$divi.'',1,0,C,true);
                }


                break;
                default: 
                $pdf->Cell(addWidthInArray(22),7,'SUR CAJA '.($divisa ? "$" : "Bs"),1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'SUR BOT. $',1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'CASCO CAJA $',1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'CASCO BOT. $',1,0,C,true); 
                $pdf->Cell(addWidthInArray(22),7,'MAYOR CAJA $',1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'MAYOR BOT. $',1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'DIAGEO CAJA $',1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'DIAGEO BOT. $',1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'EURO CAJA $',1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'EURO BOT. $',1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'CALL CENTER CAJA $',1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'CALL CENTER BOT. $',1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'EMPLEADO CAJA $',1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'EMPLEADO BOT. $',1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'MAYORISTA CAJA $',1,0,C,true);
                $pdf->Cell(addWidthInArray(22),7,'MAYORISTA BOT. $',1,0,C,true);
            }
            $proporcion = 0.10;
            if ($p6==1 || $p7==1 || $p8==1) { 
                $proporcion = 0.00;
            }
            $pdf->Cell(addWidthInArray(25 + ($anchoAdicional*$proporcion)),7,'CODIGO BARRA',1,1,C,true);

            if ($exis != 1 ) {
                if ($divisa != 0) {
                    $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  COALESCE(Profit1,0) as precio1, COALESCE(Profit1/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE(Profit2,0)  as precio2, COALESCE(Profit2/NULLIF(CantEmpaq,0), 0) as preciou2, COALESCE(Profit3,0)  as precio3,  COALESCE(Profit3/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria,
                        COALESCE(Profit4,0) as precio4, COALESCE(Profit4/NULLIF(CantEmpaq,0), 0) as preciou4,
                        COALESCE(Profit5,0) as precio5, COALESCE(Profit5/NULLIF(CantEmpaq,0), 0) as preciou5,
                        COALESCE(Profit6,0) as precio6, COALESCE(Profit6/NULLIF(CantEmpaq,0), 0) as preciou6,
                        COALESCE(Profit7,0) as precio7, COALESCE(Profit7/NULLIF(CantEmpaq,0), 0) as preciou7,
                        COALESCE(Profit8,0) as precio8, COALESCE(Profit8/NULLIF(CantEmpaq,0), 0) as preciou8
                        FROM saexis INNER JOIN saprod ON saexis.codprod = saprod.codprod
                        LEFT JOIN saprod_99 ON saprod.codprod = saprod_99.codprod
                        LEFT JOIN SAINSTA ON saprod.CodInst = SAINSTA.CodInst
                        WHERE proveedor IN $proveedores AND saprod.CodInst='$ins_id' AND saprod.Marca IN $marcas  GROUP  by saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  saprod_99.Profit1, saprod_99.Profit2, saprod_99.Profit3, saprod_99.Profit4, saprod_99.Profit5, saprod_99.Profit6, saprod_99.Profit7,saprod_99.Profit8,  saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria ORDER BY saprod.$orden ");
                }else{
                    $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  COALESCE((Profit1*ISNULL($factor,0)),0) as precio1, COALESCE((Profit1*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE((Profit2*ISNULL($factor,0)),0)  as precio2, COALESCE((Profit2*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou2, COALESCE((Profit3*ISNULL($factor,0)),0)  as precio3,  COALESCE((Profit3*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria,
                        COALESCE((Profit4*ISNULL($factor,0)),0) as precio4, COALESCE((Profit4*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou4,
                        COALESCE((Profit5*ISNULL($factor,0)),0) as precio5, COALESCE((Profit5*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou5,
                        COALESCE((Profit6*ISNULL($factor,0)),0) as precio6, COALESCE((Profit6*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou6,
                        COALESCE((Profit7*ISNULL($factor,0)),0) as precio7, COALESCE((Profit7*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou7,
                        COALESCE((Profit8*ISNULL($factor,0)),0) as precio8, COALESCE((Profit8*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou8
                        FROM saexis INNER JOIN saprod ON saexis.codprod = saprod.codprod
                        LEFT JOIN saprod_99 ON saprod.codprod = saprod_99.codprod
                        LEFT JOIN SAINSTA ON saprod.CodInst = SAINSTA.CodInst
                        WHERE proveedor IN $proveedores AND saprod.CodInst='$ins_id' AND saprod.Marca IN $marcas  GROUP  by saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  saprod_99.Profit1, saprod_99.Profit2, saprod_99.Profit3, saprod_99.Profit4, saprod_99.Profit5, saprod_99.Profit6, saprod_99.Profit7,saprod_99.Profit8,  saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria ORDER BY saprod.$orden ");
                }
            } else {
                if ($divisa != 0) {
                   $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  COALESCE(Profit1,0) as precio1, COALESCE(Profit1/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE(Profit2,0)  as precio2, COALESCE(Profit2/NULLIF(CantEmpaq,0), 0) as preciou2, COALESCE(Profit3,0)  as precio3,  COALESCE(Profit3/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria,
                     COALESCE(Profit4,0) as precio4, COALESCE(Profit4/NULLIF(CantEmpaq,0), 0) as preciou4,
                     COALESCE(Profit5,0) as precio5, COALESCE(Profit5/NULLIF(CantEmpaq,0), 0) as preciou5,
                     COALESCE(Profit6,0) as precio6, COALESCE(Profit6/NULLIF(CantEmpaq,0), 0) as preciou6,
                     COALESCE(Profit7,0) as precio7, COALESCE(Profit7/NULLIF(CantEmpaq,0), 0) as preciou7,
                     COALESCE(Profit7,0) as precio8, COALESCE(Profit7/NULLIF(CantEmpaq,0), 0) as preciou8
                     FROM saexis INNER JOIN saprod ON saexis.codprod = saprod.codprod
                     LEFT JOIN saprod_99 ON saprod.codprod = saprod_99.codprod
                     LEFT JOIN SAINSTA ON saprod.CodInst = SAINSTA.CodInst
                     WHERE (saexis.existen > 0 OR saexis.exunidad > 0) AND proveedor IN $proveedores AND saprod.CodInst='$ins_id' AND saprod.Marca IN $marcas  GROUP  by saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  saprod_99.Profit1, saprod_99.Profit2, saprod_99.Profit3,  saprod_99.Profit4, saprod_99.Profit5, saprod_99.Profit6, saprod_99.Profit7,saprod_99.Profit8, saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria ORDER BY saprod.$orden ");
               }else{
                $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  COALESCE((Profit1*ISNULL($factor,0)),0) as precio1, COALESCE((Profit1*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE((Profit2*ISNULL($factor,0)),0)  as precio2, COALESCE((Profit2*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou2, COALESCE((Profit3*ISNULL($factor,0)),0)  as precio3,  COALESCE((Profit3*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria,
                    COALESCE((Profit4*ISNULL($factor,0)),0) as precio4, COALESCE((Profit4*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou4,
                    COALESCE((Profit5*ISNULL($factor,0)),0) as precio5, COALESCE((Profit5*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou5,
                    COALESCE((Profit6*ISNULL($factor,0)),0) as precio6, COALESCE((Profit6*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou6,
                    COALESCE((Profit7*ISNULL($factor,0)),0) as precio7, COALESCE((Profit7*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou7,
                    COALESCE((Profit8*ISNULL($factor,0)),0) as precio8, COALESCE((Profit8*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou8
                    FROM saexis INNER JOIN saprod ON saexis.codprod = saprod.codprod
                    LEFT JOIN saprod_99 ON saprod.codprod = saprod_99.codprod
                    LEFT JOIN SAINSTA ON saprod.CodInst = SAINSTA.CodInst
                    WHERE (saexis.existen > 0 OR saexis.exunidad > 0) AND  proveedor IN $proveedores AND saprod.CodInst='$ins_id' AND saprod.Marca IN $marcas  GROUP  by saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  saprod_99.Profit1, saprod_99.Profit2, saprod_99.Profit3, saprod_99.Profit4, saprod_99.Profit5, saprod_99.Profit6, saprod_99.Profit7,saprod_99.Profit8,  saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria ORDER BY saprod.$orden ");
            }
        }

        $pdf->SetWidths($width);
        $pdf->SetFont('Arial','',7);
        for ($x = 0; $x < mssql_num_rows($productos); $x++) {
            $j = 0;
            $info = array();
            addInfoInArray(mssql_result($productos, $x, "CodProd"));
            addInfoInArray(utf8_decode(mssql_result($productos, $x, "Descrip")));
            addInfoInArray(mssql_result($productos, $x, "sub_clasificacion_categoria"));
            addInfoInArray(rdecimal2(mssql_result($productos, $x, "capacidad_botella")));
            addInfoInArray(rdecimal2(mssql_result($productos, $x, "CantEmpaq")));
            switch ($sumap) {
                case 1:
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio'. $sumap2 ), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou'. $sumap2 ), 2) );
                break;
                case 2:
                if ($p1==1) { 
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio1'), 2) );
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou1'), 2) );
                }
                if ($p2==1) { 
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio2'), 2) );
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou2'), 2) );
                }
                if ($p3==1) { 
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio3'), 2) );
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou3'), 2) );
                }
                if ($p4==1) { 
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio4'), 2) );
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou4'), 2) );
                }
                if ($p5==1) { 
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio5'), 2) );
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou5'), 2) );
                }
                if ($p6==1) { 
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio6'), 2) );
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou6'), 2) );
                }
                if ($p7==1) { 
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio7'), 2) );
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou7'), 2) );
                }
                if ($p8==1) { 
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio8'), 2) );
                    addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou8'), 2) );
                }

                break; 
                default:
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio1'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou1'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio2'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou2'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio3'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou3'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio4'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou4'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio5'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou5'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio6'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou6'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio7'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou7'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'precio8'), 2) );
                addInfoInArray( rdecimal(mssql_result($productos, $x, 'preciou8'), 2) );
            }
            addInfoInArray(mssql_result($productos, $x, "Refere"));

            $pdf->Row($info);
        }
    }
}
$pdf->Output();
?>
