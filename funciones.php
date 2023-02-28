<?php
require('conexion.php');
function buscando($numero){
	$aux = 0;
	$cont = 0;
	$lista = mssql_query("SELECT d.numeros, n.numerof from appfacturas_det as d Full outer join SANOTA as n on d.numeros = n.numerof where d.numeros = '$numero' or n.numerof = '$numero'
		");
	$num_lista = mssql_num_rows($lista);

	return ($num_lista);
}


function bultos_activados($fechai,$fechaf,$marca,$codvend){

	$consulta = mssql_query("SELECT distinct(CodClie) from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod inner join
		SAFACT on SAITEMFAC.NumeroD = SAFACT.NumeroD where
		DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' and saprod.marca like '%$marca%' and
		SAITEMFAC.codvend = '$codvend' and saitemfac.tipofac in ('A') AND SAFACT.tipofac in ('A') AND SAFACT.NumeroD NOT IN
		(SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac in ('A') AND x.NumeroR is not NULL AND
			cast(X.Monto as BIGINT) = cast((SELECT Z.Monto from SAFACT AS Z where Z.NumeroD = x.NumeroR and Z.TipoFac in ('B'))as BIGINT))
		
		UNION
		
		SELECT distinct(CodClie) from saitemnota inner join saprod on saitemnota.coditem = saprod.codprod inner join
		sanota on saitemnota.NumeroD = sanota.NumeroD where
		DATEADD(dd, 0, DATEDIFF(dd, 0, saitemnota.FechaE)) between '$fechai' and '$fechaf' and saprod.marca like '%$marca%' and
		saitemnota.codvend = '$codvend' and saitemnota.tipofac in ('C') AND sanota.tipofac in ('C') and numerof = '0' AND sanota.NumeroD NOT IN
		(SELECT X.NumeroD FROM sanota AS X WHERE X.TipoFac in ('C') AND x.Numerof is not NULL AND
			cast(X.subtotal as BIGINT) = cast((SELECT Z.subtotal from sanota AS Z where Z.NumeroD = x.Numerof and Z.TipoFac in ('D'))as BIGINT))");

	return mssql_num_rows($consulta);
}

function marcas($marca,$opc,$codvend,$fechai,$fechaf){
	if ($opc == 1){
		$consulta = mssql_query("SELECT distinct(CodClie) from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod inner join
			SAFACT on SAITEMFAC.NumeroD = SAFACT.NumeroD where
			DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' and saprod.marca like '$marca' and
			SAITEMFAC.codvend = '$codvend' and saitemfac.tipofac = 'A' AND SAFACT.tipofac = 'A' AND SAFACT.NumeroD NOT IN
			(SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac = 'A' AND x.NumeroR is not NULL AND
				cast(X.Monto as int) = cast((SELECT Z.Monto from SAFACT AS Z where Z.NumeroD = x.NumeroR and Z.TipoFac = 'B')as int))");
	}else{
		$consulta = mssql_query("SELECT distinct(CodClie) from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod inner join
			SAFACT on SAITEMFAC.NumeroD = SAFACT.NumeroD where
			DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' and saprod.codinst = '$marca' and
			SAITEMFAC.codvend = '$codvend' and saitemfac.tipofac = 'A' AND SAFACT.tipofac = 'A' AND SAFACT.NumeroD NOT IN
			(SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac = 'A' AND x.NumeroR is not NULL AND
				cast(X.Monto as int) = cast((SELECT Z.Monto from SAFACT AS Z where Z.NumeroD = x.NumeroR and Z.TipoFac = 'B')as int))");
	}
	return mssql_num_rows($consulta);
}

function calcula_Requerido_Bult_Und($fechai,$fechaf,$edv,$canal){
	if ($canal != "DTS"){
		$consulta = mssql_query("SELECT
			(SUM(case when SAITEMFAC.TipoFac = 'A' and EsUnid = 1 then saitemfac.Cantidad/saprod.CantEmpaq else 0 end) +
				SUM(case when SAITEMFAC.TipoFac = 'A' and EsUnid = 0 then saitemfac.Cantidad else 0 end)) - (
				SUM(case when SAITEMFAC.TipoFac = 'B' and EsUnid = 1 then saitemfac.Cantidad/saprod.CantEmpaq else 0 end) +
				SUM(case when SAITEMFAC.TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad else 0 end)) as Volumen,
				SUM(case when SAITEMFAC.TipoFac = 'A' then saitemfac.Cantidad*saitemfac.precio else 0 end) -
				SUM(case when SAITEMFAC.TipoFac = 'B' then saitemfac.Cantidad*saitemfac.precio else 0 end)  as Bs
				FROM SAITEMFAC inner join SAPROD on saitemfac.coditem = saprod.codprod
				where
				DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMFAC.FechaE)) between '$fechai' and '$fechaf' and saitemfac.codvend = '$edv'
				and (SAITEMFAC.tipofac = 'A' or SAITEMFAC.tipofac = 'B')");
	}else{
		$consulta = mssql_query("SELECT
			(SUM(case when SAITEMFAC.TipoFac = 'A' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
				SUM(case when SAITEMFAC.TipoFac = 'A' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) - (
				SUM(case when SAITEMFAC.TipoFac = 'B' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
				SUM(case when SAITEMFAC.TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) as Volumen,

				SUM(case when SAITEMFAC.TipoFac = 'A' then saitemfac.Cantidad*saitemfac.precio else 0 end) -
				SUM(case when SAITEMFAC.TipoFac = 'B' then saitemfac.Cantidad*saitemfac.precio else 0 end)  as Bs
				FROM SAITEMFAC inner join SAPROD on saitemfac.coditem = saprod.codprod
				where
				DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMFAC.FechaE)) between '$fechai' and '$fechaf' and saitemfac.codvend = '$edv'
				and (SAITEMFAC.tipofac = 'A' or SAITEMFAC.tipofac = 'B')");
	}
	if (mssql_result($consulta,0,"Volumen")!=0){
		return mssql_result($consulta,0,"Volumen");
	}else{
		return 0;
	}
}

function calcula_Requerido_Bult_Und_kg($fechai,$fechaf,$edv,$tipo){
	if ($tipo == "KG"){
		$query_logro_kg_a = mssql_query("SELECT 
			sum(CASE saitemfac.Esunid WHEN 1 then (cantidad/cantempaq)*saprod.tara ELSE cantidad*saprod.tara END) AS kg
			from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod where 
			DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' and codvend like '$edv' and (tipofac = 'A')");
		$query_logro_kg_b = mssql_query("SELECT 
			sum((CASE saitemfac.Esunid WHEN 1 then (cantidad/cantempaq)*saprod.tara ELSE cantidad*saprod.tara END)*-1) AS kg
			from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod where 
			DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' and codvend like '$edv' and (Tipofac = 'B')");
	}elseif($tipo == "UNI"){
		$query_logro_kg_a = mssql_query("SELECT 
			SUM((CASE saitemfac.Esunid WHEN 1 then cantidad ELSE cantidad*cantempaq END)) AS paq
			from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod where 
			DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' and codvend like '$edv' and (tipofac = 'A')");
		$query_logro_kg_b = mssql_query("SELECT 
			SUM((CASE saitemfac.Esunid WHEN 1 then cantidad ELSE cantidad*cantempaq END)*-1) AS paq
			from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod where 
			DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' and codvend like '$edv' and (Tipofac = 'B')");
		$logrado_kg = mssql_result($query_logro_kg_a,0,"paq")+mssql_result($query_logro_kg_b,0,"paq");
	}elseif($tipo == "BUL"){
		$query_logro_kg_a = mssql_query("SELECT 
			sum((CASE saitemfac.Esunid WHEN 1 then cantidad/cantempaq ELSE cantidad END)) AS bul
			from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod where 
			DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' and codvend like '$edv' and (tipofac = 'A')");
		$query_logro_kg_b = mssql_query("SELECT 
			sum((CASE saitemfac.Esunid WHEN 1 then cantidad/cantempaq ELSE cantidad END)*-1) AS bul
			from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod where 
			DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' and codvend like '$edv' and (Tipofac = 'B')");
		$logrado_kg = mssql_result($query_logro_kg_a,0,"bul") + mssql_result($query_logro_kg_b,0,"bul");
	}
	if ($logrado_kg!=0){
		return $logrado_kg;
	}else{
		return 0;
	}
}

function calcula_requerido($fechai,$fechaf,$edv,$canal){
	if ($canal != "DTS"){
		$consulta = mssql_query("SELECT
			(SUM(case when SAITEMFAC.TipoFac = 'A' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
				SUM(case when SAITEMFAC.TipoFac = 'A' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) - (
				SUM(case when SAITEMFAC.TipoFac = 'B' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
				SUM(case when SAITEMFAC.TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) as Volumen,

				SUM(case when SAITEMFAC.TipoFac = 'A' then saitemfac.Cantidad*saitemfac.precio else 0 end) -
				SUM(case when SAITEMFAC.TipoFac = 'B' then saitemfac.Cantidad*saitemfac.precio else 0 end)  as Bs
				FROM SAITEMFAC inner join SAPROD on saitemfac.coditem = saprod.codprod
				where
				DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMFAC.FechaE)) between '$fechai' and '$fechaf' and saitemfac.codvend = '$edv'
				and (SAITEMFAC.tipofac = 'A' or SAITEMFAC.tipofac = 'B')");
	}else{
		$consulta = mssql_query("SELECT
			(SUM(case when SAITEMFAC.TipoFac = 'A' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
				SUM(case when SAITEMFAC.TipoFac = 'A' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) - (
				SUM(case when SAITEMFAC.TipoFac = 'B' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
				SUM(case when SAITEMFAC.TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) as Volumen,

				SUM(case when SAITEMFAC.TipoFac = 'A' then saitemfac.Cantidad*saitemfac.precio else 0 end) -
				SUM(case when SAITEMFAC.TipoFac = 'B' then saitemfac.Cantidad*saitemfac.precio else 0 end)  as Bs
				FROM SAITEMFAC inner join SAPROD on saitemfac.coditem = saprod.codprod
				where
				DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMFAC.FechaE)) between '$fechai' and '$fechaf' and saitemfac.codvend = '$edv'
				and (SAITEMFAC.tipofac = 'A' or SAITEMFAC.tipofac = 'B')");
	}
	if (mssql_result($consulta,0,"Volumen")!=0){
		return mssql_result($consulta,0,"Volumen");
	}else{
		return 0;
	}
}

function obj_especial($fechai,$fechaf,$canal,$edv,$sku){
	if ($canal != "DTS"){
		$consulta = mssql_query("SELECT
			(SUM(case when SAITEMFAC.TipoFac = 'A' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
				SUM(case when SAITEMFAC.TipoFac = 'A' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) - (
				SUM(case when SAITEMFAC.TipoFac = 'B' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
				SUM(case when SAITEMFAC.TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) as Volumen,

				SUM(case when SAITEMFAC.TipoFac = 'A' then saitemfac.Cantidad*saitemfac.precio else 0 end) -
				SUM(case when SAITEMFAC.TipoFac = 'B' then saitemfac.Cantidad*saitemfac.precio else 0 end)  as Bs
				FROM SAITEMFAC inner join SAPROD on saitemfac.coditem = saprod.codprod inner join safact on safact.numerod = saitemfac.numerod
				where
				DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMFAC.FechaE)) between '$fechai' and '$fechaf' and saitemfac.codvend = '$edv' and saprod.codprod = '$sku'
				and (SAITEMFAC.tipofac = 'A' or SAITEMFAC.tipofac = 'B')");
	}else{
		$consulta = mssql_query("SELECT
			(SUM(case when SAITEMFAC.TipoFac = 'A' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
				SUM(case when SAITEMFAC.TipoFac = 'A' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) - (
				SUM(case when SAITEMFAC.TipoFac = 'B' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
				SUM(case when SAITEMFAC.TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) as Volumen,

				SUM(case when SAITEMFAC.TipoFac = 'A' then saitemfac.Cantidad*saitemfac.precio else 0 end) -
				SUM(case when SAITEMFAC.TipoFac = 'B' then saitemfac.Cantidad*saitemfac.precio else 0 end)  as Bs
				FROM SAITEMFAC inner join SAPROD on saitemfac.coditem = saprod.codprod inner join safact on safact.numerod = saitemfac.numerod
				where
				DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMFAC.FechaE)) between '$fechai' and '$fechaf' and saitemfac.codvend = '$edv' and saprod.codprod = '$sku'
				and (SAITEMFAC.tipofac = 'A' or SAITEMFAC.tipofac = 'B')");
	}
	if (mssql_result($consulta,0,"Volumen")!=0){
		return mssql_result($consulta,0,"Volumen");
	}else{
		return 0;
	}
}



function especial_detal_kpi($fechai,$fechaf,$codvend,$sku){
	$consulta = mssql_query("SELECT (
		SUM(case when TipoFac = 'A' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
		SUM(case when TipoFac = 'A' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) - (
		SUM(case when TipoFac = 'B' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
		SUM(case when TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) as paq
		from SAITEMFAC INNER JOIN SAPROD ON SAITEMFAC.CodItem = SAPROD.CodProd
		where DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMFAC.FechaE)) between '$fechai' and '$fechaf' and SAITEMFAC.CodVend = '$codvend' and SAITEMFAC.coditem = '$sku'");
	return mssql_result($consulta,0,"paq");
}

function peso_producto($codprod,$unidad,$cant){
	$busca_prod = mssql_query("SELECT saprod.tara as peso, saprod.cantempaq as paquetes from saprod where codprod= '$codprod'");
	$peso = 0;
	if (mssql_num_rows($busca_prod) != 0){
		if ($unidad == 0){
			$peso = mssql_result($busca_prod,0,"peso") * $cant;
		}else{
			$peso = (mssql_result($busca_prod,0,"peso")/mssql_result($busca_prod,0,"paquetes")) * $cant;
		}
	}


	return number_format($peso, 2, ".", ",");

}

function calcula_peso($nfatc){
	$busca_items_fact = mssql_query("SELECT saitemfac.coditem as cod_prod, saprod.tara as peso, saitemfac.esunid as unidad, saprod.cantempaq as paquetes, saitemfac.cantidad as cantidad, tipofac from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod where numerod = '$nfatc' and saprod.marca like '%nestle%' AND TIPOFAC in ('A','B' OR TIPOFAC = 'B') order by tipofac");
	$peso = 0;
	if (mssql_num_rows($busca_items_fact) != 0){
		for($j=0;$j<mssql_num_rows($busca_items_fact);$j++){
					if (mssql_result($busca_items_fact,$j,"tipofac") == "A"){ ////// suma
						if (mssql_result($busca_items_fact,$j,"unidad") == 0){
							$peso = $peso + (mssql_result($busca_items_fact,$j,"peso") * mssql_result($busca_items_fact,$j,"cantidad"));
						}else{
							$peso = $peso + ((mssql_result($busca_items_fact,$j,"peso")/mssql_result($busca_items_fact,$j,"paquetes")) * mssql_result($busca_items_fact,$j,"cantidad"));
						}
					}else{ ////////// resta
						if (mssql_result($busca_items_fact,$j,"unidad") == 0){
							$peso = $peso - (mssql_result($busca_items_fact,$j,"peso")* mssql_result($busca_items_fact,$j,"cantidad"));
						}else{
							$peso = $peso - ((mssql_result($busca_items_fact,$j,"peso")/mssql_result($busca_items_fact,$j,"paquetes")) * mssql_result($busca_items_fact,$j,"cantidad"));
						}
					}
				}
			}
			return $peso;

		}
function normalize_date($date){ //VENESUR
	if(!empty($date)){
		$var = explode('/',str_replace('-','/',$date));
		return "$var[2]-$var[1]-$var[0]";
	}
	/*return $date;*/
}
function normalize_date2($date){ //VENESUR
	if(!empty($date)){
		$var = explode('/',str_replace('-','/',$date));
		return "$var[2]-$var[0]-$var[1]";
	}
	/*return $date;*/
}
/*function rdecimal($valor) {
   //$float_redondeado=round($valor * 10) / 10;
   $float_redondeado = number_format($valor, 1, ",", ".");
   return $float_redondeado;
}*/

function rdecimal($number, $precision = 1, $separator = '.', $separatorDecimal = ',')
{
	$numberParts = explode($separator, sprintf('%f', floatval($number)));
	$response = number_format($numberParts[0], 0, ",", ".");
	if (count($numberParts) > 1) {
		$response .= $separatorDecimal;
		$response .= substr(
			$numberParts[1],
			0,
			$precision
		);
	}
	return $response;
}


function rdecimal2($valor) {
   //$float_redondeado=round($valor * 10) / 10;
	$float_redondeado = number_format($valor, 2, ".", ",");
	return $float_redondeado;
}
function rdecimal0($valor) {
   //$float_redondeado=round($valor * 10) / 10;
	$float_redondeado = number_format($valor, 0, ".", ",");
	return $float_redondeado;
}


function rdecimal5($number, $precision = 2, $separator = '.', $separatorDecimal = ',')
{
	$numberParts = explode($separator, $number);
	$response = number_format($numberParts[0], 0, ",", ".");
	if (count($numberParts) > 1) {
		$response .= $separatorDecimal;
		$response .= substr(
			$numberParts[1],
			0,
			$precision
		);
	}
	return $response;
}
function rdecimal3($valor) {
   //$float_redondeado=round($valor * 10) / 10;
	$float_redondeado = number_format($valor, 3, ",", ".");
	return $float_redondeado;
}


function calcula_peso_x_instancia($nfatc){
	$busca_items_fact = mssql_query("SELECT saitemfac.coditem as cod_prod, saprod.tara as peso, saitemfac.esunid as unidad, saprod.cantempaq as paquetes, saitemfac.cantidad as cantidad from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod inner join sainsta on saprod.codinst = sainsta.codinst where numerod = '$nfatc'");
	$peso = 0;
	if (mssql_num_rows($busca_items_fact) != 0){
		for($j=0;$j<mssql_num_rows($busca_items_fact);$j++){
			if (mssql_result($busca_items_fact,$j,"unidad") == 0){
				$peso = $peso + (mssql_result($busca_items_fact,$j,"peso") * mssql_result($busca_items_fact,$j,"cantidad"));
			}else{
				$peso = $peso + ((mssql_result($busca_items_fact,$j,"peso")/mssql_result($busca_items_fact,$j,"paquetes")) * mssql_result($busca_items_fact,$j,"cantidad"));
			}
		}
	}
	return $peso;
}

function calcula_peso_x_factura($nfatc){
	$busca_items_fact = mssql_query("SELECT saitemfac.coditem as cod_prod, saprod.tara as peso, saitemfac.esunid as unidad, saprod.cantempaq as paquetes, saitemfac.cantidad as cantidad, tipofac from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod where numerod = '$nfatc' AND TIPOFAC in ('A','C') order by saitemfac.coditem");
	$peso = 0;
	if (mssql_num_rows($busca_items_fact) != 0){
		for($j=0;$j<mssql_num_rows($busca_items_fact);$j++){
					if (mssql_result($busca_items_fact,$j,"tipofac") == "A"){ ////// suma
						if (mssql_result($busca_items_fact,$j,"unidad") == 0){
							$peso = $peso + (mssql_result($busca_items_fact,$j,"peso") * mssql_result($busca_items_fact,$j,"cantidad"));
						}else{
							$peso = $peso + ((mssql_result($busca_items_fact,$j,"peso")/mssql_result($busca_items_fact,$j,"paquetes")) * mssql_result($busca_items_fact,$j,"cantidad"));
						}
					}
				}
			}
			return $peso;

		}
		function calcula_bulto($numd){
			$consulta = mssql_query("SELECT
				(SUM(case when EsUnid = 1 then saitemfac.Cantidad/saprod.CantEmpaq else 0 end) +
					SUM(case when EsUnid = 0 then saitemfac.Cantidad else 0 end)) AS Bultos
				FROM SAITEMFAC inner join SAPROD on saitemfac.coditem = saprod.codprod
				where NUMEROD = '$numd' AND TipoFac = 'A'");
			return mssql_result($consulta,0,"Bultos");
		}

		function restaFechas($dFecIni, $dFecFin)
		{
			$dFecIni = str_replace("-","",$dFecIni);
			$dFecIni = str_replace("/","",$dFecIni);
			$dFecFin = str_replace("-","",$dFecFin);
			$dFecFin = str_replace("/","",$dFecFin);

			ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
			ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);

			$date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
			$date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);

			return round(($date2 - $date1) / (60 * 60 * 24));
		}

		function busca_desct($fechai,$fechaf,$cod_insta){
			$aux = 0;
			$total_fact = 0;
			$consulta = mssql_query("SELECT DISTINCT numerod from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod inner join sainsta on saprod.codinst = sainsta.codinst where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' and saprod.codinst = '$cod_insta' and (saitemfac.tipofac = 'A' or saitemfac.tipofac = 'B')");
			for($i=0;$i<mssql_num_rows($consulta);$i++){

				$numerod = mssql_result($consulta,$i,"numerod");
				$consul_facturas = mssql_query("SELECT tipofac, descto1, mtototal from safact where numerod = '$numerod'");
				if (mssql_result($consul_facturas,0,"tipofac") == "A"){
					$aux = mssql_result($consul_facturas,0,"descto1") + $aux;
				}else{
					$aux = $aux - mssql_result($consul_facturas,0,"descto1");
				}
			}
			return $aux;
		}

		function clie_descrip($codclie){
			$clientes = mssql_query("SELECT descrip, codvend from saclie where codclie = '$codclie'");
			return mssql_result($clientes,0,"descrip");
		}

		function clie_vend($codclie){
			$clientes = mssql_query("SELECT descrip, codvend from saclie where codclie = '$codclie'");
			return mssql_result($clientes,0,"codvend");
		}
		function clie_rif($codclie){
			$clientes = mssql_query("SELECT id3 from saclie where codclie = '$codclie'");
			return mssql_result($clientes,0,"id3");
		}

function formatof($date){ //VENESUR
	if($date){
		/*return $date;*/
		return date("d/m/Y", strtotime($date));
	}else{
		return "";
	}
}

function calcula_peso_kpi($fechai,$fechaf,$codvend){
	$busca_items_fact = mssql_query("SELECT saitemfac.coditem as cod_prod, saprod.tara as peso, saitemfac.esunid as unidad, saprod.cantempaq as paquetes, saitemfac.cantidad as cantidad, tipofac from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' and saitemfac.codvend = '$codvend' and saprod.marca like '%nestle%' and (tipofac = 'A' or tipofac = 'B') order by tipofac");
	$peso = 0;
	if (mssql_num_rows($busca_items_fact) != 0){
		for($j=0;$j<mssql_num_rows($busca_items_fact);$j++){
			if (mssql_result($busca_items_fact,$j,"unidad") == 0){
				if (mssql_result($busca_items_fact,$j,"tipofac") == "A"){
					$peso = $peso + (mssql_result($busca_items_fact,$j,"peso")* mssql_result($busca_items_fact,$j,"cantidad"));
				}else{
					$peso = $peso - (mssql_result($busca_items_fact,$j,"peso")* mssql_result($busca_items_fact,$j,"cantidad"));
				}
			}else{
				if (mssql_result($busca_items_fact,$j,"tipofac") == "A"){
					$peso = $peso + ((mssql_result($busca_items_fact,$j,"peso")/mssql_result($busca_items_fact,$j,"paquetes")) * mssql_result($busca_items_fact,$j,"cantidad"));
				}else{
					$peso = $peso - ((mssql_result($busca_items_fact,$j,"peso")/mssql_result($busca_items_fact,$j,"paquetes")) * mssql_result($busca_items_fact,$j,"cantidad"));
				}
			}
		}
	}
	return $peso;
}


function calcula_kpi_aj_detal($fechai,$fechaf,$codvend,$tipo){
	$busca_items_fact = mssql_query("SELECT (SUM(case when TipoFac = 'A' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
		SUM(case when TipoFac = 'A' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) -
	(SUM(case when TipoFac = 'B' and EsUnid = 1 then saitemfac.Cantidad else 0 end) +
		SUM(case when TipoFac = 'B' and EsUnid = 0 then saitemfac.Cantidad*saprod.CantEmpaq else 0 end)) AS PAQ
	from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' and saitemfac.codvend = '$codvend' and (tipofac = 'A' or tipofac = 'B')");

	return mssql_result($busca_items_fact,0,"PAQ");
}

function calcula_kpi_aj_mayor($fechai,$fechaf,$codvend,$tipo){

	$busca_items_fact = mssql_query("SELECT saitemfac.coditem as cod_prod, saprod.tara as peso, saitemfac.esunid as unidad, saprod.cantempaq as paquetes, saitemfac.cantidad as cantidad, tipofac from saitemfac inner join saprod on saitemfac.coditem = saprod.codprod where DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) between '$fechai' and '$fechaf' and saitemfac.codvend = '$codvend' and saitemfac.esunid = '0' and (tipofac = 'A' or tipofac = 'B') order by tipofac");
	$peso = 0;
	if (mssql_num_rows($busca_items_fact) != 0){
		for($j=0;$j<mssql_num_rows($busca_items_fact);$j++){

			if (mssql_result($busca_items_fact,$j,"tipofac") == "A"){
				$peso = $peso + mssql_result($busca_items_fact,$j,"cantidad");
			}else{
				$peso = $peso - mssql_result($busca_items_fact,$j,"cantidad");
			}
		}
	}
	return $peso;
}

function ranking_act($fechai,$fechaf){
	$vendedores= mssql_query("SELECT codvend from savend where codvend = '01' or codvend = '03' or codvend = '04' or codvend = '05' or codvend = '06' or codvend = '11' or codvend = '12' or codvend = '14' or codvend = 's19' or codvend = '15' and activo = '1' order by codvend");
	for($i=0;$i<mssql_num_rows($vendedores);$i++){
		$codvend = mssql_result($vendedores,$i,"codvend");
		$clientes = mssql_query("SELECT codclie from saclie where codvend = '$codvend' and  fechae <= '$fechaf' order by codclie");
		$clientes_activos = mssql_query("SELECT distinct saclie.codclie FROM saclie inner join safact on saclie.codclie = safact.codclie where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai' and '$fechaf' and tipofac = 'A' and saclie.codvend = '$codvend'");
		$porcent_act = (mssql_num_rows($clientes_activos)/mssql_num_rows($clientes))*100;

		$cvector[$i] = str_replace(",",".",rdecimal($porcent_act));
	}
	$cvector = $cvector;
	return $cvector;
}

function ranking_detal($fechai,$fechaf){
	$vendedores= mssql_query("SELECT codvend from savend where codvend = '01' or codvend = '03' or codvend = '04' or codvend = '05' or codvend = '06' or codvend = '11' or codvend = '12' or codvend = '14' and activo = '1' order by codvend");
	for($i=0;$i<mssql_num_rows($vendedores);$i++){
		$codvend = mssql_result($vendedores,$i,"codvend");

		$frecuencias = mssql_query("SELECT frecuencia, requerido_kg, ava_planificado, ava_colocado, tipo from savend_02 where codvend = '$codvend'");

		$objetivo_kg = mssql_result($frecuencias,0,"requerido_kg");
		$tipo = mssql_result($frecuencias,0,"tipo");
		if ($tipo == "unidad"){
			$unidad = mssql_result($frecuencias,0,"requerido_kg");
			$bulto = 0;
			$peso = calcula_kpi_aj_detal($fechai,$fechaf,$codvend,$tipo);
			$peso_mayor = 0;
			$objet_detal = mssql_result($frecuencias,0,"requerido_kg");
			$total_detal = $objet_detal + $total_detal;
			$objet_mayor = 0;
		}
		if ($objet_detal == 0){
			$objet_detal = 1;
		}

		$porcent_act = ($peso/$objet_detal)*100;
		$cvector[$i] = str_replace(",",".",rdecimal($porcent_act));
	}
	$cvector = $cvector;
	return $cvector;
}

function ranking_fre($fechai,$fechaf,$dias_habiles,$dias_trans){
	$vendedores= mssql_query("SELECT codvend from savend where codvend = '01' or codvend = '03' or codvend = '04' or codvend = '05' or codvend = '06' or codvend = '11' or codvend = '12' or codvend = '14' and activo = '1' order by codvend");
	for($i=0;$i<mssql_num_rows($vendedores);$i++){
		$codvend = mssql_result($vendedores,$i,"codvend");
		$facturas = mssql_query("SELECT numerod  FROM safact  where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai' and '$fechaf' and safact.codvend = '$codvend' and tipofac = 'A' order by numerod");
		$frecuencias = mssql_query("SELECT frecuencia, requerido_kg, ava_planificado, ava_colocado from savend_02 where codvend = '$codvend'");
		$clientes = mssql_query("SELECT codclie from saclie where codvend = '$codvend' and  fechae <= '$fechaf' order by codclie");

		$frecu = $dias_habiles / 5;
		if (mssql_result($frecuencias,0,"frecuencia") == 4){
			$frecu = $frecu * 1;
		}
		if (mssql_result($frecuencias,0,"frecuencia") == 2){
			$frecu = $frecu * 0.5;
		}
		if (mssql_result($frecuencias,0,"frecuencia") == 3){
			$frecu = $frecu * 0.75;
		}
		if (mssql_result($frecuencias,0,"frecuencia") == 2.5){
			$frecu = $frecu * 0.60;
		}
		if (mssql_result($frecuencias,0,"frecuencia") == 1){
			$frecu = $frecu * 1;
		}

		$objetivo_cant_mens = $frecu * mssql_num_rows($clientes);
		$obje_prom_cant_diarias = $objetivo_cant_mens/$dias_habiles;
		$prom_ventas_dia = round(mssql_num_rows($facturas)/$dias_trans);
		if ($obje_prom_cant_diarias != 0){
			$porcent_efect__ideal = ((mssql_num_rows($facturas)/$objetivo_cant_mens)*100);
		}else{
			$porcent_efect__ideal = 0;
		}

		$porcent_act = $porcent_efect__ideal;
		$cvector[$i] = str_replace(",",".",rdecimal($porcent_act));

	}
	$cvector = $cvector;
	return $cvector;

}

function ranking_kg($fechai,$fechaf){
	$vendedores= mssql_query("SELECT codvend from savend where codvend = '01' or codvend = '03' or codvend = '04' or codvend = '05' or codvend = '06' or codvend = '11' or codvend = '12' or codvend = '14' and activo = '1' order by codvend");
	for($i=0;$i<mssql_num_rows($vendedores);$i++){
		$codvend = mssql_result($vendedores,$i,"codvend");
		$clientes = mssql_query("SELECT codclie from saclie where codvend = '$codvend' and  fechae <= '$fechaf' order by codclie");
		$frecuencias = mssql_query("SELECT frecuencia, requerido_kg, ava_planificado, ava_colocado, tipo from savend_02 where codvend = '$codvend'");

		$tipo = mssql_result($frecuencias,0,"tipo");
		if ($tipo == "unidad"){
			$unidad = mssql_result($frecuencias,0,"requerido_kg");
			$bulto = 0;
			$peso = calcula_kpi_aj_detal($fechai,$fechaf,$codvend,$tipo);
			$porcet_kg = ($peso/$unidad)*100;
		}else{
			$unidad = 0;
			$bulto = mssql_result($frecuencias,0,"requerido_kg");
			$peso = calcula_kpi_aj_mayor($fechai,$fechaf,$codvend,$tipo);
			$porcet_kg = ($peso/$bulto)*100;
		}


		$porcent_act = $porcet_kg;
		$cvector[$i] = str_replace(",",".",rdecimal($porcent_act));
	}
	$cvector = $cvector;
	return $cvector;
}
function burbuja($array){
	$n = count($array);
	for($i=1;$i<$n;$i++){
		for($j=0;$j<$n-$i;$j++){
			if($array[$j]<$array[$j+1]){
				$k=$array[$j+1];
				$array[$j+1]=$array[$j];
				$array[$j]=$k;
			}
		}
	}
	return $array;
}

function busca_rankig($vector,$obje){
	$number = count($vector);
	$obje = str_replace(",",".",$obje);
	$aux = 0;
	$i = 0;
	while ($i < $number){
		$aux++;
		if (trim($vector[$i]) === trim($obje)){
			$i = $number;
		}
		$i++;
	}
	return $aux;
}

function fact_devol_complet($numfact){ //////devuelve 0 si no fue devuelta totalmente, 1 si lo fue
	$facturas = mssql_query("SELECT numeror FROM safact where numerod = '$numfact' and numeror is not null and tipofac = 'A'");
	if (mssql_num_rows($facturas)!= 0) {
		$numdevol = mssql_result($facturas,0,"numeror");
		$items_devol = mssql_query("SELECT * FROM saitemfac where numerod = '$numdevol' and tipofac = 'B' order by coditem");
		$items_fact = mssql_query("SELECT * FROM saitemfac where numerod = '$numfact' and tipofac = 'A' order by coditem");
		$suma = 0;
		if (mssql_num_rows($items_fact) == mssql_num_rows($items_devol)){
				for($i=0;$i<mssql_num_rows($items_fact);$i++){ // for
					if (mssql_result($items_fact,$i,"coditem") === mssql_result($items_devol,$i,"coditem") and mssql_result($items_fact,$i,"cantidad") == mssql_result($items_devol,$i,"cantidad") and mssql_result($items_fact,$i,"esunid") == mssql_result($items_devol,$i,"esunid")){
						$suma++;
					}else{
						$i = mssql_num_rows($items_fact);
					}
				} // for
				if ($suma == mssql_num_rows($items_fact)){
					return 1;
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

function valida_code($codnestle){//13000762
	switch ($codnestle) {
		case "010402":
		return "010402";
		break;
		case "050706":
		return "050706";
		break;
		case "040704":
		return "040704";
		break;
		case "030403":
		return "030403";
		break;
		case "030401":
		return "030401";
		break;
		case "050706":
		return "050706";
		break;
		case "020404":
		return "020404";
		break;
		case "010101":
		return "010101";
		break;
		case "031101":
		return "031101";
		break;
		case "040603":
		return "040603";
		break;
		case "050604":
		return "050604";
		break;
		case "060402":
		return "060402";
		break;
		case "060701":
		return "060701";
		break;
		case "050301":
		return "050301";
		break;
		case "060403":
		return "060403";
		break;
		case "020302":
		return "020302";
		break;
		case "040104":
		return "040104";
		break;
		case "030301":
		return "030301";
		break;
		case "061003":
		return "061003";
		break;
		case "060601":
		return "060601";
		break;
		case "040302":
		return "040302";
		break;
		case "060102":
		return "060102";
		break;
		case "020201":
		return "020201";
		break;
		case "030101":
		return "030101";
		break;
		case "010301":
		return "010301";
		break;
		case "010302":
		return "010302";
		break;
		case "010401":
		return "010401";
		break;
	}
}
function valida_Mes($mes){
	switch ($mes) {
		case 1:
		return "Enero";
		break;
		case 2:
		return "Febrero";
		break;
		case 3:
		return "Marzo";
		break;
		case 4:
		return "Abril";
		break;
		case 5:
		return "Mayo";
		break;
		case 6:
		return "Junio";
		break;
		case 7:
		return "Julio";
		break;
		case 8:
		return "Agosto";
		break;
		case 9:
		return "Septiembre";
		break;
		case 10:
		return "Octubre";
		break;
		case 11:
		return "Noviembre";
		break;
		case 12:
		return "Diciembre";
		break;
	}
}
function Mes_valida($mes){
	switch ($mes) {
		case "Enero":
		return 1;
		break;
		case "Febrero":
		return 2;
		break;
		case "Marzo":
		return 3;
		break;
		case "Abril":
		return 4;
		break;
		case "Mayo":
		return 5;
		break;
		case "Junio":
		return 6;
		break;
		case "Julio":
		return 7;
		break;
		case "Agosto":
		return 8;
		break;
		case "Septiembre":
		return 9;
		break;
		case "Octubre":
		return 10;
		break;
		case "Noviembre":
		return 11;
		break;
		case "Diciembre":
		return 12;
		break;
	}
}

function Nomina_nombre($nomina){
	switch ($nomina) {
		case "SWNOMMSSQL000001":
		return "Almacen";
		break;
		case "SWNOMMSSQL000002":
		return "Administracion";
		break;
		case "SWNOMMSSQL000003":
		return "Ventas";
		break;
		case "SWNOMMSSQL000004":
		return "Gerencia";
		break;
		case "SWNOMMSSQL000005":
		return "Semanal Campo";
		break;
		case "SWNOMMSSQL000006":
		return "Nomina Empleados Eventuales";
		break;
		case "SWNOMMSSQL000008":
		return "Nomina Vigilancia";
		break;
		case "SWNOMMSSQL000009":
		return "Idietca Diaria";
		break;
	
	}
}

function formato_fecha2($date){
	$date=date_create($date);
	$fd=date_format($date, 'd/m/Y');
	return $fd;
}

function remover_acentos($cadena) {

	//Reemplazamos la A y a
	$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
	);

	//Reemplazamos la E y e
	$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );

	//Reemplazamos la I y i
	$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );

	//Reemplazamos la O y o
	$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );

	//Reemplazamos la U y u
	$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );

	//Reemplazamos la N, n, C y c
	$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
	);

	return $cadena;
}

?>
