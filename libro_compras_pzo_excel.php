<?php
set_time_limit(0);
require_once("funciones.php");
$fechai = $_GET['fechai'];
$fechaf = $_GET['fechaf'];
$fechai = normalize_date($fechai).' 00:00:00';
$fechaf = normalize_date($fechaf).' 23:59:59';
require_once 'PHPExcel_new/PHPExcel.php';
session_start();
if ($_SESSION['login'] == ""){
  echo "<script language=Javascript> location.href=\"close.php\";</script>";
}
require_once("conexion.php");
$resumen1 = mssql_query("SELECT * FROM DBO.VW_ADM_LIBROIVACOMPRAS WHERE  CODSUCU = '00000' and ('$fechai'<=FECHATRAN) AND (FECHATRAN<='$fechaf') ORDER BY (YEAR(FechaCompra)*10000)+(MONTH(FechaCompra)*100)+DAY(FechaCompra),FECHAT ");
$num = mssql_num_rows($resumen1);
  // Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();
  // Se asignan las propiedades del libro
  $objPHPExcel->getProperties()->setCreator("APP") // Nombre del autor
    ->setLastModifiedBy("APP") //Ultimo usuario que lo modificó
    ->setTitle("Libro de Compras") // Titulo
    ->setSubject($nomb_arch);
    $tituloReporte = "Libro de Compras del ".$_GET['fechai']." al ".$_GET['fechaf'];
    $titulosColumnas = array('Nro. Ope', 'Fecha Documento', 'Rif', 'Nombre o Razón Social', 'Tip. Doc.', 'Nro. Comprobante Retención', 'Nro. Documento', 'Nro. Control', 'Tipo Tran.', 'Nro Fac. Afectada', 'Total Compras', 'Compras Exentas', 'Base Imponible', '% Alic', 'Monto IVA', 'Monto Retenido', '% Ret', 'Fecha Comprobante');
  // Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
    $objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('A1:R1');
  // Se agregan los titulos del reporte
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', $tituloReporte) // Titulo del reporte
    ->setCellValue('A3', $titulosColumnas[0])  //Titulo de las columnas
    ->setCellValue('B3', $titulosColumnas[1])
    ->setCellValue('C3', $titulosColumnas[2])
    ->setCellValue('D3', $titulosColumnas[3])
    ->setCellValue('E3', $titulosColumnas[4])
    ->setCellValue('F3', $titulosColumnas[5])
    ->setCellValue('G3', $titulosColumnas[6])
    ->setCellValue('H3', $titulosColumnas[7])
    ->setCellValue('I3', $titulosColumnas[8])
    ->setCellValue('J3', $titulosColumnas[9])
    ->setCellValue('K3', $titulosColumnas[10])
    ->setCellValue('L3', $titulosColumnas[11])
    ->setCellValue('M3', $titulosColumnas[12])
    ->setCellValue('N3', $titulosColumnas[13])
    ->setCellValue('O3', $titulosColumnas[14])
    ->setCellValue('P3', $titulosColumnas[15])
    ->setCellValue('Q3', $titulosColumnas[16])
    ->setCellValue('R3', $titulosColumnas[17]);
    $l = 4;
    $tcci = $mtoex = $totcom = $mtoiva = $retiva = 0;
    for($i=0;$i<$num;$i++){
      $k = $i+1;
      $tcci += mssql_result($resumen1, $i, 'totalcompraconiva');
      $mtoex += mssql_result($resumen1, $i, 'mtoexento');
      $totcom += mssql_result($resumen1, $i, 'totalcompra');
      $mtoiva += mssql_result($resumen1, $i, 'monto_iva');
      $retiva += mssql_result($resumen1, $i, 'retencioniva');
      if (mssql_result($resumen1, $i, 'fecharetencion')) {
        $fecha_retencion = date('d/m/Y', strtotime(mssql_result($resumen1, $i, 'fecharetencion')));
      }else{
        $fecha_retencion = '';
      }
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'.$l, $k)
      ->setCellValue('B'.$l, date('d/m/Y', strtotime(mssql_result($resumen1, $i, 'fechacompra'))))
      ->setCellValue('C'.$l, mssql_result($resumen1, $i, 'id3ex'))
      ->setCellValue('D'.$l, utf8_encode(mssql_result($resumen1, $i, 'descripex')))
      ->setCellValue('E'.$l, mssql_result($resumen1, $i, 'tipodoc'))
      ->setCellValue('F'.$l, mssql_result($resumen1, $i, 'nroretencion'))
      ->setCellValue('G'.$l, mssql_result($resumen1, $i, 'numerodoc'))
      ->setCellValue('H'.$l, mssql_result($resumen1, $i, 'nroctrol'))
      ->setCellValue('I'.$l, mssql_result($resumen1, $i, 'tiporeg'))
      ->setCellValue('J'.$l, mssql_result($resumen1, $i, 'docafectado'))
      ->setCellValue('K'.$l, number_format(mssql_result($resumen1, $i, 'totalcompraconiva'), 2, ',', '.'))
      ->setCellValue('L'.$l, number_format(mssql_result($resumen1, $i, 'mtoexento'), 2, ',', '.'))
      ->setCellValue('M'.$l, number_format(mssql_result($resumen1, $i, 'totalcompra'), 2, ',', '.'))
      ->setCellValue('N'.$l, number_format(mssql_result($resumen1, $i, 'alicuota_iva'), 0, ',', '.').'%')
      ->setCellValue('O'.$l, number_format(mssql_result($resumen1, $i, 'monto_iva'), 2, ',', '.'))
      ->setCellValue('P'.$l, number_format(mssql_result($resumen1, $i, 'retencioniva'), 2, ',', '.'))
      ->setCellValue('Q'.$l, number_format(mssql_result($resumen1, $i, 'porctreten'), 0, ',', '.').'%')
      ->setCellValue('R'.$l, $fecha_retencion);
      $l++;
      $f1 = $l;
    }
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$l, '')
    ->setCellValue('B'.$l, '')
    ->setCellValue('C'.$l, '')
    ->setCellValue('D'.$l, '')
    ->setCellValue('E'.$l, '')
    ->setCellValue('F'.$l, '')
    ->setCellValue('G'.$l, '')
    ->setCellValue('H'.$l, '')
    ->setCellValue('I'.$l, '')
    ->setCellValue('J'.$l, 'TOTALES')
    ->setCellValue('K'.$l, number_format($tcci, 2, ',', '.'))
    ->setCellValue('L'.$l, number_format($mtoex, 2, ',', '.'))
    ->setCellValue('M'.$l, number_format($totcom, 2, ',', '.'))
    ->setCellValue('N'.$l, '')
    ->setCellValue('O'.$l, number_format($mtoiva, 2, ',', '.'))
    ->setCellValue('P'.$l, number_format($retiva, 2, ',', '.'))
    ->setCellValue('Q'.$l, '')
    ->setCellValue('R'.$l, '');
    $l++; $l++; $l4 = $l+1; $l5 = $l4+1; $l6 = $l5+1; $l7 = $l6+1; $l8 = $l7+1;
    $l9 = $l8+1; $l10 = $l9+1; $l11 = $l10+1; $l12 = $l11+1; $l13 = $l12+1;
    $l14 = $l13+1; $l15 = $l14+1; $l16 = $l15+1;
  // Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
    $objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('A'.$l4.':D'.$l4)
    ->mergeCells('E'.$l4.':F'.$l4)
    ->mergeCells('G'.$l4.':H'.$l4)
    ->mergeCells('A'.$l5.':D'.$l5)
    ->mergeCells('E'.$l5.':F'.$l5)
    ->mergeCells('G'.$l5.':H'.$l5)
    ->mergeCells('A'.$l6.':D'.$l6)
    ->mergeCells('E'.$l6.':F'.$l6)
    ->mergeCells('G'.$l6.':H'.$l6)
    ->mergeCells('A'.$l7.':D'.$l7)
    ->mergeCells('E'.$l7.':F'.$l7)
    ->mergeCells('G'.$l7.':H'.$l7)
    ->mergeCells('A'.$l8.':D'.$l8)
    ->mergeCells('E'.$l8.':F'.$l8)
    ->mergeCells('G'.$l8.':H'.$l8)
    ->mergeCells('A'.$l9.':D'.$l9)
    ->mergeCells('E'.$l9.':F'.$l9)
    ->mergeCells('G'.$l9.':H'.$l9)
    ->mergeCells('A'.$l10.':D'.$l10)
    ->mergeCells('E'.$l10.':F'.$l10)
    ->mergeCells('G'.$l10.':H'.$l10)
    ->mergeCells('A'.$l11.':D'.$l11)
    ->mergeCells('E'.$l11.':F'.$l11)
    ->mergeCells('G'.$l11.':H'.$l11)
    ->mergeCells('A'.$l12.':D'.$l12)
    ->mergeCells('E'.$l12.':F'.$l12)
    ->mergeCells('G'.$l12.':H'.$l12)
    ->mergeCells('A'.$l13.':D'.$l13)
    ->mergeCells('E'.$l13.':F'.$l13)
    ->mergeCells('G'.$l13.':H'.$l13)
    ->mergeCells('A'.$l14.':D'.$l14)
    ->mergeCells('E'.$l14.':F'.$l14)
    ->mergeCells('G'.$l14.':H'.$l14)
    ->mergeCells('A'.$l15.':D'.$l15)
    ->mergeCells('E'.$l15.':F'.$l15)
    ->mergeCells('G'.$l15.':H'.$l15)
    ->mergeCells('A'.$l16.':D'.$l16)
    ->mergeCells('E'.$l16.':F'.$l16)
    ->mergeCells('G'.$l16.':H'.$l16);
  // Se agregan los titulos del reporte
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$l4, 'RESUMEN DE CRÉDITOS FISCALES')
    ->setCellValue('E'.$l4, 'BASE IMPONIBLE')
    ->setCellValue('G'.$l4, 'CRÉDITO FISCAL')
    ->setCellValue('A'.$l5, 'Total Compras Exentas y/o sin derecho a crédito Fiscal')
    ->setCellValue('E'.$l5, number_format($mtoex, 2, ',', '.'))
    ->setCellValue('G'.$l5, number_format(0, 2, ',', '.'))
    ->setCellValue('A'.$l6, 'Total Compras Importación Afectas solo Alícuota General')
    ->setCellValue('E'.$l6, number_format(0, 2, ',', '.'))
    ->setCellValue('G'.$l6, number_format(0, 2, ',', '.'))
    ->setCellValue('A'.$l7, 'Total Compras Importación Afectas en Alícuota General + Adicional')
    ->setCellValue('E'.$l7, number_format(0, 2, ',', '.'))
    ->setCellValue('G'.$l7, number_format(0, 2, ',', '.'))
    ->setCellValue('A'.$l8, 'Total Compras Importación Afectas en Alícuota Reducida')
    ->setCellValue('E'.$l8, number_format(0, 2, ',', '.'))
    ->setCellValue('G'.$l8, number_format(0, 2, ',', '.'))
    ->setCellValue('A'.$l9, 'Total Compras Internas Afectas solo Alícuota General (16%): ')
    ->setCellValue('E'.$l9, number_format($totcom, 2, ',', '.'))
    ->setCellValue('G'.$l9, number_format($mtoiva, 2, ',', '.'))
    ->setCellValue('A'.$l10, 'Total Compras Internas Afectas solo Alícuota General + Adicional')
    ->setCellValue('E'.$l10, number_format(0, 2, ',', '.'))
    ->setCellValue('G'.$l10, number_format(0, 2, ',', '.'))
    ->setCellValue('A'.$l11, 'Total Compras Internas Afectas solo Alícuota Reducida')
    ->setCellValue('E'.$l11, number_format(0, 2, ',', '.'))
    ->setCellValue('G'.$l11, number_format(0, 2, ',', '.'))
    ->setCellValue('A'.$l12, 'Total Compras y créditos fiscales del período')
    ->setCellValue('E'.$l12, number_format(($totcom+$mtoex), 2, ',', '.'))
    ->setCellValue('G'.$l12, number_format($mtoiva, 2, ',', '.'))
    ->setCellValue('A'.$l13, 'Créditos Fiscales producto de la aplicación del porcentaje de la prorrata')
    ->setCellValue('E'.$l13, number_format(0, 2, ',', '.'))
    ->setCellValue('G'.$l13, number_format(0, 2, ',', '.'))
    ->setCellValue('A'.$l14, 'Excedente de Crédito Fiscal del Periodo Anterior')
    ->setCellValue('E'.$l14, number_format(0, 2, ',', '.'))
    ->setCellValue('G'.$l14, number_format(0, 2, ',', '.'))
    ->setCellValue('A'.$l15, 'Ajustes a los créditos fiscales de periodos anteriores')
    ->setCellValue('E'.$l15, number_format(0, 2, ',', '.'))
    ->setCellValue('G'.$l15, number_format(0, 2, ',', '.'))
    ->setCellValue('A'.$l16, 'Compras no gravadas y/o sin derecho a credito fiscal')
    ->setCellValue('E'.$l16, number_format(0, 2, ',', '.'))
    ->setCellValue('G'.$l16, number_format(0, 2, ',', '.'));
    require_once 'estilos_excel.php';
    $objPHPExcel->getActiveSheet()->getStyle('A1:R1')->applyFromArray($estiloTituloReporte);
    $objPHPExcel->getActiveSheet()->getStyle('A3:R3')->applyFromArray($estiloTituloColumnas);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$l4.':H'.$l4)->applyFromArray($estiloTituloColumnas);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$l12.':D'.$l12)->applyFromArray($estiloTituloColumnas);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$l16.':D'.$l16)->applyFromArray($estiloTituloColumnas);
    $objPHPExcel->getActiveSheet()->getStyle('J4'.':J'.$f1)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle('L4'.':L'.$f1)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle('M4'.':M'.$f1)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle('N4'.':N'.$f1)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle('O4'.':O'.$f1)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle('P4'.':P'.$f1)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle('Q4'.':Q'.$f1)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle('E'.$l5.':E'.$l16)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $objPHPExcel->getActiveSheet()->getStyle('G'.$l5.':G'.$l16)->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    for($m = 'A'; $m <= 'R'; $m++){
      $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($m)->setAutoSize(TRUE);
    }
    $objPHPExcel->getActiveSheet()->getStyle('J'.$f1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    for($p = 4; $p <= $f1; $p++){
      $objPHPExcel->getActiveSheet()->getStyle('B'.$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('L'.$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('M'.$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('N'.$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('O'.$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('P'.$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('Q'.$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('R'.$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }
    for($p = $l5; $p <= $l16; $p++){
      $objPHPExcel->getActiveSheet()->getStyle('E'.$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    }
  // Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename=libro_compras_pzo.xls');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
  ?>