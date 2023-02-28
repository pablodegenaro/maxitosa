<?php
date_default_timezone_set('America/Caracas');
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
require ("conexion.php");
require ("funciones.php");
require_once ("permisos/Mssql.php");

$fechai = $_POST['fechai'].' 00:00:00';
$fechaf = $_POST['fechaf'].' 23:59:59';

$file = "RET_IVA_".date('d_m_Y_H_i').".txt";
$fh = fopen($file, 'w');

$newperiodo = date("Ym", strtotime($fechai));

$fecha = date('Y-m-d');
$domicilio = mssql_query("SELECT DISTINCT
  Replace(CF.RIF, '-', '') as RIF_Contribuyente,
  CONVERT(VARCHAR,'202208') as Periodo ,
  CONVERT(VARCHAR,SUBSTRING(CONVERT(VARCHAR, CM.FechaI,120),1,10)) as Fecha_Documento,
  'C' as Tipo_Operacion, 
  (CASE CM.TipoCom WHEN  'H' THEN '01' WHEN  'I' THEN '03' END) as Tipo_Documento,
  (CASE LEN(ISNULL(REPLACE(cxp.ID3,'-',''),'')) WHEN 0 THEN 'J000000000' ELSE ISNULL(REPLACE(cxp.ID3,'-',''),'')+SUBSTRING('',1,15-LEN(ISNULL(REPLACE(cxp.ID3,'-',''),''))) END) as RIF_Comprador_Vendedor,
  Cm.NumeroD as Numero_Documento,
  cm.NroCtrol as Numero_Control_Documento,
  convert(decimal(24,2),cm.TGravable+cm.TExento+cm.MtoTax) AS MONTO_DOCUMENTO,
  convert(decimal(24,2),txc.TGravable) as Base_Imponible,
  convert(decimal(24,2),cxp.Monto) as Monto_IVA,
  CASE cm.TIPOCOM WHEN 'I' THEN ISNULL(cm.NUMERON,'0') ELSE '0' END As Numero_Documento_Afectado,
  case when len(cxp.NumeroD) = 14 then cxp.NumeroD else substring(cxp.NumeroD,0,7)+'000'+right(cxp.NumeroD, 5)  end as Numero_Comprobante,
  convert(decimal(24,2),cm.TExento+ISNULL(TXPVP.Monto,0)) AS MONTO_EXENTO_IVA,
  txc.MtoTax as Alicuota,
  0 as Numero_Expediente  from 
  SAACXP cxp inner join SACOMP CM ON CXP.TipoCxP IN ('81','82') AND CM.TipoCom IN ('H','I') AND CXP.NumeroN = CM.NumeroD AND CASE WHEN CXP.TipoCxP = '81' THEN 'H' ELSE 'I' END = CM.TipoCom AND CXP.CodProv = CM.CodProv 
  LEFT JOIN SATAXCOM TXC ON CM.NumeroD = TXC.NumeroD AND CM.CodProv = TXC.CodProv AND CM.TipoCom = TXC.TipoCom and txc.CodTaxs = 'IVA' LEFT JOIN SATAXCOM TXPVP ON CM.NumeroD = TXPVP.NumeroD AND CM.CodProv = TXPVP.CodProv AND CM.TipoCom = TXPVP.TipoCom and TXPVP.CodTaxs = 'PVP', SACONF CF WHERE CF.CodSucu = '00000'  and Cxp.FechaE >= '$fechai' and 
  Cxp.FechaE <= '$fechaf'");

for($i=0; $i < mssql_num_rows($domicilio); $i++) {

  $id3 = mssql_result($domicilio, $i, "RIF_Contribuyente");
  $periodo = mssql_result($domicilio, $i, "Periodo");
  $fechad = mssql_result($domicilio, $i, "Fecha_Documento");
  $tipo_operacion = mssql_result($domicilio, $i, "Tipo_Operacion");
  $tipodoc = mssql_result($domicilio, $i, "Tipo_Documento");
  $id3_comprador = mssql_result($domicilio, $i, "RIF_Comprador_Vendedor");
  $numerod = mssql_result($domicilio, $i, "Numero_Documento");
  $numerod_control_documento = mssql_result($domicilio, $i, "Numero_Control_Documento");    
  $monto = mssql_result($domicilio, $i, "monto_documento");    
  $gravable = mssql_result($domicilio, $i, "Base_Imponible");  
  $iva = mssql_result($domicilio, $i, "monto_iva");
  $numerod_doc_afectado = mssql_result($domicilio, $i, "numero_documento_afectado");
  $numero_comprobante = mssql_result($domicilio, $i, "numero_comprobante");
  $monto_exento_iva = mssql_result($domicilio, $i, "monto_exento_iva");
  $alicuota = mssql_result($domicilio, $i, "alicuota");
  $numero_expediente = mssql_result($domicilio, $i, "Numero_Expediente");

  $output .= str_pad($id3, 1)."\t";
  $output .= str_pad($newperiodo, 1)."\t";
  $output .= str_pad($fechad, 1)."\t";
  $output .= str_pad($tipo_operacion, 1)."\t";
  $output .= str_pad($tipodoc, 1)."\t";
  $output .= str_pad($id3_comprador, 1)."\t";
  $output .= str_pad($numerod, 1)."\t";
  $output .= str_pad($numerod_control_documento, 1)."\t";
  $output .= str_pad($monto, 1)."\t";
  $output .= str_pad($gravable, 1)."\t";
  $output .= str_pad($iva, 1)."\t";
  $output .= str_pad($numerod_doc_afectado, 1)."\t";
  $output .= str_pad($numero_comprobante, 1)."\t";
  $output .= str_pad($monto_exento_iva, 1)."\t";
  $output .= str_pad($alicuota, 1)."\t";
  $output .= str_pad($numero_expediente, 1)."\t";
  $output .= "\n";

  $serial += 1;
}

fwrite($fh, $output);
fclose($fh);

$enlace = $file;
header ("Content-Disposition: attachment; filename=".$enlace);
header ("Content-Type: application/octet-stream");
header ("Content-Length: ".filesize($enlace));
readfile($enlace);
unlink($file);
?>