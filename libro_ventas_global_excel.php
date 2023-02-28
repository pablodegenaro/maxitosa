<?
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Libro_Ventas_El_Triunfo".date('d-m-Y h:i a',time() - 3600*date('I')).".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php 
require("conexion.php");
require("funciones.php");
session_start();
set_time_limit(0);
ini_set('memory_limit', '512M');

$sucursal = $_GET['sucursal'];
$fechai = $_GET['fechai'];
$fechaf = $_GET['fechaf'];
$fechaii = normalize_date($fechai) . ' 00:00:00';
$fechaff = normalize_date($fechaf) . ' 23:59:59';

if ($sucursal != '-') {
	$query = mssql_query("
		SELECT * from 
		(select FechaE FechaDoc, ID3 ID3, Descrip DescripClie, NULL NroComprob, case when ft.TipoFac = 'A' then ft.NumeroD else '' end NroFact, NULL NroND, case when ft.TipoFac = 'B' then ft.NumeroD else '' end NroNC, case when ft.TipoFac = 'A' then '01-REG' when ft.TipoFac = 'B' then '03-REG' else '' end  TipTran, case when ft.TipoFac = 'B' then ft.NumeroR else '' end NroFactAfec, ft.signo*ft.MtoTotal TotalVentas, ft.signo*PVP.Monto ImpPVP, ft.signo*IAL.Monto ImpIAL, ft.signo*isnull(pvp.Monto,0)+isnull(ial.Monto,0) VentasNoGra, ft.signo*ft.TExento VEntasExe, ft.signo*iva.TGravable BaseImpo, ft.signo*iva.MtoTax PorIVA, ft.signo*iva.Monto IVA, NULL IVAReten, case when datediff(mm,ft.FechaE,ft.FechaT) != 0 then 0 else 1 end EnPeriodo, ft.CodSucu CodSucu, ft.FechaE FechaF
			from safact ft left join 
			SATAXVTA iva on ft.NumeroD = iva.NumeroD and ft.TipoFac = iva.TipoFac and ft.CodSucu = iva.CodSucu and iva.CodTaxs = 'IVA' 
			left join 
			SATAXVTA PVP on ft.NumeroD = PVP.NumeroD and ft.TipoFac = PVP.TipoFac and ft.CodSucu = PVP.CodSucu and PVP.CodTaxs = 'PVP' 
			left join 
			SATAXVTA IAL on ft.NumeroD = IAL.NumeroD and ft.TipoFac = IAL.TipoFac and ft.CodSucu = IAL.CodSucu and IAL.CodTaxs = 'IAL'
			where ft.TipoFac in ('A','B')
			union all
			select cxc.FechaE FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, NULL NroComprob, NULL NroFact, cxc.NumeroD NroND, NULL NroNC,'02-REG' TipTran, NULL NroFactAfec, Monto TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, cxc.TExento VEntasExe, cxc.BaseImpo BaseImpo, (cxc.MtoTax*100)/case when cxc.BaseImpo = 0 then 1 else cxc.BaseImpo end PorIVA,cxc.MtoTax IVA, NULL IVAReten, 1 EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
			from SAACXC cxc INNER JOIN 
			SACLIE CLI on cxc.CodClie = cli.CodClie
			WHERE TipoCxc in ('20') and EsLibroI = 1
			union all
			select cxc.FechaE FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, NULL NroComprob, NULL NroFact, NULL NroND, cxc.NumeroD NroNC, '03-REG' TipTran, NumeroN NroFactAfec, BaseImpo+TExento+MtoTax TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, -cxc.TExento VEntasExe, -cxc.BaseImpo BaseImpo, -(cxc.MtoTax*100)/case when cxc.BaseImpo = 0 then 1 else cxc.BaseImpo end PorIVA, -cxc.MtoTax IVA, NULL IVAReten, 1 EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
			from SAACXC cxc INNER JOIN 
			SACLIE CLI on cxc.CodClie = cli.CodClie
			WHERE TipoCxc in ('31') and EsLibroI = 1
			union all
			select cxc.FechaR FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, ''''+cxc.NumeroT NroComprob, NULL NroFact, NULL NroND, NULL NroNC, '81-RET' TipTran, isnull(pag.NumeroD,cxc.NumeroD) NroFactAfec, NULL TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, NULL VEntasExe,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then isnull(tax.TGravable,cxc.BaseImpo) else 0 end BaseImpo, NULL PorIVA,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then ISNULL(tax.Monto,cxc.MtoTax) else 0 end IVA, isnull(case when count(*) over (Partition by cxc.NroUnico) = 1 then cxc.Monto else pag.MontoDocA end,cxc.RetenIVA) IVAReten, case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then 0 else 1 end EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
			from SAACXC cxc 
			INNER JOIN SACLIE CLI on cxc.CodClie = cli.CodClie
			left join SAPAGCXC PAG ON cxc.NroUnico = pag.NroPpal
			left join SATAXVTA tax on pag.NumeroD = tax.NumeroD and tax.TipoFac = 'A' and tax.CodTaxs = 'IVA'
			left join SAFACT ft on tax.NumeroD = ft.NumeroD and tax.TipoFac = ft.TipoFac and tax.CodSucu = ft.CodSucu
			WHERE cxc.TipoCxc in ('81') and EsLibroI = 1 
			)
		as asasasa 
		where Fechaf BETWEEN  '$fechai' and '$fechaf' and CodSucu ='$sucursal' and EnPeriodo='1' 
		order by FechaDoc");
}else{
	$query = mssql_query("
		SELECT * from 
		(select FechaE FechaDoc, ID3 ID3, Descrip DescripClie, NULL NroComprob, case when ft.TipoFac = 'A' then ft.NumeroD else '' end NroFact, NULL NroND, case when ft.TipoFac = 'B' then ft.NumeroD else '' end NroNC, case when ft.TipoFac = 'A' then '01-REG' when ft.TipoFac = 'B' then '03-REG' else '' end  TipTran, case when ft.TipoFac = 'B' then ft.NumeroR else '' end NroFactAfec, ft.signo*ft.MtoTotal TotalVentas, ft.signo*PVP.Monto ImpPVP, ft.signo*IAL.Monto ImpIAL, ft.signo*isnull(pvp.Monto,0)+isnull(ial.Monto,0) VentasNoGra, ft.signo*ft.TExento VEntasExe, ft.signo*iva.TGravable BaseImpo, ft.signo*iva.MtoTax PorIVA, ft.signo*iva.Monto IVA, NULL IVAReten, case when datediff(mm,ft.FechaE,ft.FechaT) != 0 then 0 else 1 end EnPeriodo, ft.CodSucu CodSucu, ft.FechaE FechaF
			from safact ft left join 
			SATAXVTA iva on ft.NumeroD = iva.NumeroD and ft.TipoFac = iva.TipoFac and ft.CodSucu = iva.CodSucu and iva.CodTaxs = 'IVA' 
			left join 
			SATAXVTA PVP on ft.NumeroD = PVP.NumeroD and ft.TipoFac = PVP.TipoFac and ft.CodSucu = PVP.CodSucu and PVP.CodTaxs = 'PVP' 
			left join 
			SATAXVTA IAL on ft.NumeroD = IAL.NumeroD and ft.TipoFac = IAL.TipoFac and ft.CodSucu = IAL.CodSucu and IAL.CodTaxs = 'IAL'
			where ft.TipoFac in ('A','B')
			union all
			select cxc.FechaE FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, NULL NroComprob, NULL NroFact, cxc.NumeroD NroND, NULL NroNC,'02-REG' TipTran, NULL NroFactAfec, Monto TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, cxc.TExento VEntasExe, cxc.BaseImpo BaseImpo, (cxc.MtoTax*100)/case when cxc.BaseImpo = 0 then 1 else cxc.BaseImpo end PorIVA,cxc.MtoTax IVA, NULL IVAReten, 1 EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
			from SAACXC cxc INNER JOIN 
			SACLIE CLI on cxc.CodClie = cli.CodClie
			WHERE TipoCxc in ('20') and EsLibroI = 1
			union all
			select cxc.FechaE FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, NULL NroComprob, NULL NroFact, NULL NroND, cxc.NumeroD NroNC, '03-REG' TipTran, NumeroN NroFactAfec, BaseImpo+TExento+MtoTax TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, -cxc.TExento VEntasExe, -cxc.BaseImpo BaseImpo, -(cxc.MtoTax*100)/case when cxc.BaseImpo = 0 then 1 else cxc.BaseImpo end PorIVA, -cxc.MtoTax IVA, NULL IVAReten, 1 EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
			from SAACXC cxc INNER JOIN 
			SACLIE CLI on cxc.CodClie = cli.CodClie
			WHERE TipoCxc in ('31') and EsLibroI = 1
			union all
			select cxc.FechaR FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, ''''+cxc.NumeroT NroComprob, NULL NroFact, NULL NroND, NULL NroNC, '81-RET' TipTran, isnull(pag.NumeroD,cxc.NumeroD) NroFactAfec, NULL TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, NULL VEntasExe,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then isnull(tax.TGravable,cxc.BaseImpo) else 0 end BaseImpo, NULL PorIVA,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then ISNULL(tax.Monto,cxc.MtoTax) else 0 end IVA, isnull(case when count(*) over (Partition by cxc.NroUnico) = 1 then cxc.Monto else pag.MontoDocA end,cxc.RetenIVA) IVAReten, case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then 0 else 1 end EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
			from SAACXC cxc 
			INNER JOIN SACLIE CLI on cxc.CodClie = cli.CodClie
			left join SAPAGCXC PAG ON cxc.NroUnico = pag.NroPpal
			left join SATAXVTA tax on pag.NumeroD = tax.NumeroD and tax.TipoFac = 'A' and tax.CodTaxs = 'IVA'
			left join SAFACT ft on tax.NumeroD = ft.NumeroD and tax.TipoFac = ft.TipoFac and tax.CodSucu = ft.CodSucu
			WHERE cxc.TipoCxc in ('81') and EsLibroI = 1 
			)
		as asasasa 
		where Fechaf BETWEEN  '$fechai' and '$fechaf' and EnPeriodo='1' 
		order by FechaDoc");

}

if ($sucursal != '-') {
	$query_retenciones = mssql_query("
		SELECT * from 
		(select FechaE FechaDoc, ID3 ID3, Descrip DescripClie, NULL NroComprob, case when ft.TipoFac = 'A' then ft.NumeroD else '' end NroFact, NULL NroND, case when ft.TipoFac = 'B' then ft.NumeroD else '' end NroNC, case when ft.TipoFac = 'A' then '01-REG' when ft.TipoFac = 'B' then '03-REG' else '' end  TipTran, case when ft.TipoFac = 'B' then ft.NumeroR else '' end NroFactAfec, ft.signo*ft.MtoTotal TotalVentas, ft.signo*PVP.Monto ImpPVP, ft.signo*IAL.Monto ImpIAL, ft.signo*isnull(pvp.Monto,0)+isnull(ial.Monto,0) VentasNoGra, ft.signo*ft.TExento VEntasExe, ft.signo*iva.TGravable BaseImpo, ft.signo*iva.MtoTax PorIVA, ft.signo*iva.Monto IVA, NULL IVAReten, case when datediff(mm,ft.FechaE,ft.FechaT) != 0 then 0 else 1 end EnPeriodo, ft.CodSucu CodSucu, ft.FechaE FechaF
			from safact ft left join 
			SATAXVTA iva on ft.NumeroD = iva.NumeroD and ft.TipoFac = iva.TipoFac and ft.CodSucu = iva.CodSucu and iva.CodTaxs = 'IVA' 
			left join 
			SATAXVTA PVP on ft.NumeroD = PVP.NumeroD and ft.TipoFac = PVP.TipoFac and ft.CodSucu = PVP.CodSucu and PVP.CodTaxs = 'PVP' 
			left join 
			SATAXVTA IAL on ft.NumeroD = IAL.NumeroD and ft.TipoFac = IAL.TipoFac and ft.CodSucu = IAL.CodSucu and IAL.CodTaxs = 'IAL'
			where ft.TipoFac in ('A','B')
			union all
			select cxc.FechaE FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, NULL NroComprob, NULL NroFact, cxc.NumeroD NroND, NULL NroNC,'02-REG' TipTran, NULL NroFactAfec, Monto TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, cxc.TExento VEntasExe, cxc.BaseImpo BaseImpo, (cxc.MtoTax*100)/case when cxc.BaseImpo = 0 then 1 else cxc.BaseImpo end PorIVA,cxc.MtoTax IVA, NULL IVAReten, 1 EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
			from SAACXC cxc INNER JOIN 
			SACLIE CLI on cxc.CodClie = cli.CodClie
			WHERE TipoCxc in ('20') and EsLibroI = 1
			union all
			select cxc.FechaE FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, NULL NroComprob, NULL NroFact, NULL NroND, cxc.NumeroD NroNC, '03-REG' TipTran, NumeroN NroFactAfec, BaseImpo+TExento+MtoTax TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, -cxc.TExento VEntasExe, -cxc.BaseImpo BaseImpo, -(cxc.MtoTax*100)/case when cxc.BaseImpo = 0 then 1 else cxc.BaseImpo end PorIVA, -cxc.MtoTax IVA, NULL IVAReten, 1 EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
			from SAACXC cxc INNER JOIN 
			SACLIE CLI on cxc.CodClie = cli.CodClie
			WHERE TipoCxc in ('31') and EsLibroI = 1
			union all
			select cxc.FechaR FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, ''''+cxc.NumeroT NroComprob, NULL NroFact, NULL NroND, NULL NroNC, '81-RET' TipTran, isnull(pag.NumeroD,cxc.NumeroD) NroFactAfec, NULL TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, NULL VEntasExe,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then isnull(tax.TGravable,cxc.BaseImpo) else 0 end BaseImpo, NULL PorIVA,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then ISNULL(tax.Monto,cxc.MtoTax) else 0 end IVA, isnull(case when count(*) over (Partition by cxc.NroUnico) = 1 then cxc.Monto else pag.MontoDocA end,cxc.RetenIVA) IVAReten, case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then 0 else 1 end EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
			from SAACXC cxc 
			INNER JOIN SACLIE CLI on cxc.CodClie = cli.CodClie
			left join SAPAGCXC PAG ON cxc.NroUnico = pag.NroPpal
			left join SATAXVTA tax on pag.NumeroD = tax.NumeroD and tax.TipoFac = 'A' and tax.CodTaxs = 'IVA'
			left join SAFACT ft on tax.NumeroD = ft.NumeroD and tax.TipoFac = ft.TipoFac and tax.CodSucu = ft.CodSucu
			WHERE cxc.TipoCxc in ('81') and EsLibroI = 1 
			)
		as asasasa 
		where Fechaf BETWEEN  '$fechai' and '$fechaf' and CodSucu ='$sucursal' and EnPeriodo='0' 
		order by FechaDoc");
}else{
	$query_retenciones = mssql_query("
		SELECT * from 
		(select FechaE FechaDoc, ID3 ID3, Descrip DescripClie, NULL NroComprob, case when ft.TipoFac = 'A' then ft.NumeroD else '' end NroFact, NULL NroND, case when ft.TipoFac = 'B' then ft.NumeroD else '' end NroNC, case when ft.TipoFac = 'A' then '01-REG' when ft.TipoFac = 'B' then '03-REG' else '' end  TipTran, case when ft.TipoFac = 'B' then ft.NumeroR else '' end NroFactAfec, ft.signo*ft.MtoTotal TotalVentas, ft.signo*PVP.Monto ImpPVP, ft.signo*IAL.Monto ImpIAL, ft.signo*isnull(pvp.Monto,0)+isnull(ial.Monto,0) VentasNoGra, ft.signo*ft.TExento VEntasExe, ft.signo*iva.TGravable BaseImpo, ft.signo*iva.MtoTax PorIVA, ft.signo*iva.Monto IVA, NULL IVAReten, case when datediff(mm,ft.FechaE,ft.FechaT) != 0 then 0 else 1 end EnPeriodo, ft.CodSucu CodSucu, ft.FechaE FechaF
			from safact ft left join 
			SATAXVTA iva on ft.NumeroD = iva.NumeroD and ft.TipoFac = iva.TipoFac and ft.CodSucu = iva.CodSucu and iva.CodTaxs = 'IVA' 
			left join 
			SATAXVTA PVP on ft.NumeroD = PVP.NumeroD and ft.TipoFac = PVP.TipoFac and ft.CodSucu = PVP.CodSucu and PVP.CodTaxs = 'PVP' 
			left join 
			SATAXVTA IAL on ft.NumeroD = IAL.NumeroD and ft.TipoFac = IAL.TipoFac and ft.CodSucu = IAL.CodSucu and IAL.CodTaxs = 'IAL'
			where ft.TipoFac in ('A','B')
			union all
			select cxc.FechaE FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, NULL NroComprob, NULL NroFact, cxc.NumeroD NroND, NULL NroNC,'02-REG' TipTran, NULL NroFactAfec, Monto TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, cxc.TExento VEntasExe, cxc.BaseImpo BaseImpo, (cxc.MtoTax*100)/case when cxc.BaseImpo = 0 then 1 else cxc.BaseImpo end PorIVA,cxc.MtoTax IVA, NULL IVAReten, 1 EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
			from SAACXC cxc INNER JOIN 
			SACLIE CLI on cxc.CodClie = cli.CodClie
			WHERE TipoCxc in ('20') and EsLibroI = 1
			union all
			select cxc.FechaE FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, NULL NroComprob, NULL NroFact, NULL NroND, cxc.NumeroD NroNC, '03-REG' TipTran, NumeroN NroFactAfec, BaseImpo+TExento+MtoTax TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, -cxc.TExento VEntasExe, -cxc.BaseImpo BaseImpo, -(cxc.MtoTax*100)/case when cxc.BaseImpo = 0 then 1 else cxc.BaseImpo end PorIVA, -cxc.MtoTax IVA, NULL IVAReten, 1 EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
			from SAACXC cxc INNER JOIN 
			SACLIE CLI on cxc.CodClie = cli.CodClie
			WHERE TipoCxc in ('31') and EsLibroI = 1
			union all
			select cxc.FechaR FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, ''''+cxc.NumeroT NroComprob, NULL NroFact, NULL NroND, NULL NroNC, '81-RET' TipTran, isnull(pag.NumeroD,cxc.NumeroD) NroFactAfec, NULL TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, NULL VEntasExe,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then isnull(tax.TGravable,cxc.BaseImpo) else 0 end BaseImpo, NULL PorIVA,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then ISNULL(tax.Monto,cxc.MtoTax) else 0 end IVA, isnull(case when count(*) over (Partition by cxc.NroUnico) = 1 then cxc.Monto else pag.MontoDocA end,cxc.RetenIVA) IVAReten, case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then 0 else 1 end EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
			from SAACXC cxc 
			INNER JOIN SACLIE CLI on cxc.CodClie = cli.CodClie
			left join SAPAGCXC PAG ON cxc.NroUnico = pag.NroPpal
			left join SATAXVTA tax on pag.NumeroD = tax.NumeroD and tax.TipoFac = 'A' and tax.CodTaxs = 'IVA'
			left join SAFACT ft on tax.NumeroD = ft.NumeroD and tax.TipoFac = ft.TipoFac and tax.CodSucu = ft.CodSucu
			WHERE cxc.TipoCxc in ('81') and EsLibroI = 1 
			)
		as asasasa 
		where Fechaf BETWEEN  '$fechai' and '$fechaf' and EnPeriodo='0' 
		order by FechaDoc");

}
$num = mssql_num_rows($query);  ?>

<style type="text/css">
	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
	}
	.Estilo1 {
		font-size: 24px;
		color: #000000;
		font-weight: bold;
	}
	.Estilo2 {
		font-size: 20px;
		color: #000;
		font-weight: bold;
	}
	.Estilo3 {
		font-size: 12px;
		font-weight: bold;
		font-family: "ARIAL", Courier, monospace;
	}
	.Estilo4 {
		font-size: 12px;
		font-family: "ARIAL", Courier, monospace;
	}
	.Estilo4-bold {
		font-size: 12px;
		font-family: "ARIAL", Courier, monospace;
		font-weight: bold;
	}
	.Estilo4-white {
		font-size: 12px;
		color: #FFFFFF;
		font-family: "ARIAL", Courier, monospace;
	}
	.Estilo6 {color: #006600}
	.Estilo8 {color: #FF0000}
	.Estilo9 {color: #FFFF33}
</style>
<table id="example1"  class="Estilo4" style="width:100%;">
	<thead  style="background-color: #00137f;color: white;">
		<tr id="cells">
			<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Nro. OP</th>
				<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Fecha Documento</th>
					<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">RIF</th>
						<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Nombre o Raz&oacute;n Social</th>
							<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Nro. de Comp. Retencion</th>
								<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numero Factura</th>
									<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numero Nota Debido</th>
										<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numero Nota Credito</th>
											<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Tipo Tran.</th>
												<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numero Factura Afectada</th>
													<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Ventas Gravadas incluyendo IVA</th>
														<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Impuesto al PVP</th>
															<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">IVA Percibido</th>
																<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Ventas No Gravadas</th>
																	<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Ventas Excentas</th>
																		<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Base Imponible 16%</th>
																			<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">% Alicuota</th>
																				<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Impuesto IVA</th>
																					<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">IVA Retenido (por el comprador)</th>                        
																					</tr>
																				</thead>
																				<tbody style="background-color: aliceblue">
																					<?php
																					$totalventas = $totalimppvp = $totalimpial = $totalventasnogra = $totalventasexe = $totalbaseimpo = $totaliva = $totalivareten = 0;
																					for($i=0;$i<$num;$i++){
																						$k = $i+1;
																						?>
																						<tr>
																							<!-- <td align="center">N° OP</td> -->
																							<td><?php echo $k; ?></td>
																							<!-- <th align="center" >Fecha Documento</th> -->
																							<td class="text-center"><?php echo date('d/m/Y', strtotime(mssql_result($query, $i, 'FechaDoc'))); ?></td>
																							<!-- <th align="center" >RIF</th> -->
																							<td><?php echo mssql_result($query, $i, 'ID3'); ?></td>
																							<!-- <th align="center" >Nombre o Raz&oacute;n Social</th> -->
																							<td><?php echo utf8_encode(mssql_result($query, $i, 'DescripClie')); ?></td>
																							<!-- <th align="center" >Nro. de Comp. Retención</th> -->
																							<td><?php echo mssql_result($query, $i, 'NroComprob'); ?></td>
																							<!--<th align="center" >Numero Factura</th> -->
																							<td><?php echo mssql_result($query, $i, 'NroFact'); ?></td>
																							<!-- <th align="center" >Numero Nota Debido</th> -->
																							<td><?php echo mssql_result($query, $i, 'NroND'); ?></td>
																							<!-- <th align="center" >Numero Nota Credito</th> -->
																							<td><?php echo mssql_result($query, $i, 'NroNC'); ?></td>
																							<!-- <th align="center" >Tipo Tran.</th> -->
																							<td><?php echo mssql_result($query, $i, 'TipTran'); ?></td>
																							<!-- <th align="center" >Numero Factura Afectada</th> -->
																							<td><?php echo mssql_result($query, $i, 'NroFactAfec'); ?></td>

																							<!-- <th align="center" >Ventas Gravadas incluyendo IVA</th>-->
																							<td align="right"><?php echo number_format(mssql_result($query, $i, 'TotalVentas'), 2, ',', '.'); $totalventas += mssql_result($query, $i, 'TotalVentas'); ?></td>
																							<!-- <th align="center" >Impuesto al PVP</th>-->
																							<td align="right"><?php echo number_format(mssql_result($query, $i, 'ImpPVP'), 2, ',', '.'); $totalimppvp += mssql_result($query, $i, 'ImpPVP'); ?></td>
																							<!-- <th align="center" >IVA Percibido</th>-->
																							<td align="right"><?php echo number_format(mssql_result($query, $i, 'ImpIAL'), 2, ',', '.'); $totalimpial += mssql_result($query, $i, 'ImpIAL'); ?></td>
																							<!-- <th align="center" >Ventas No Gravadas</th> -->    
																							<td align="right"><?php echo '0'; /*number_format(mssql_result($query, $i, 'VentasNoGra'));*/ /*$totalventasnogra += mssql_result($query, $i, 'VentasNoGra');*/  ?></td>
																							<!-- <th align="center" >Ventas Excentas</th> -->
																							<td align="right"><?php echo number_format(mssql_result($query, $i, 'VEntasEXE'), 2, ',', '.'); $totalventasexe += mssql_result($query, $i, 'VEntasEXE'); ?></td>
																							<!-- <th align="center" >Base Imponible 16%</th> -->    
																							<td align="right"><?php echo number_format(mssql_result($query, $i, 'BaseImpo'), 2, ',', '.'); $totalbaseimpo += mssql_result($query, $i, 'BaseImpo'); ?></td>                  
																							<!-- <th align="center" >% Alicuota</th> -->
																							<td align="right"><?php echo rdecimal0(mssql_result($query, $i, 'PorIVA'), 2, ',', '.'); ?> %</td>
																							<!-- <th align="center" >Impuesto IVA</th> -->
																							<td align="right"><?php echo number_format(mssql_result($query, $i, 'IVA'), 2, ',', '.'); $totaliva += mssql_result($query, $i, 'IVA'); ?></td>
																							<!-- <th align="center" >IVA Retenido (por el comprador)</th> -->
																							<td align="right"><?php echo number_format(mssql_result($query, $i, 'IVAReten'), 2, ',', '.'); $totalivareten += mssql_result($query, $i, 'IVAReten'); ?></td>
																						</tr>
																						<?php
																						$totalresumen = $totalimppvp + $totalimpial + $totalventasexe + $totalbaseimpo + $totalventasnogra;
																						$totalresumen2 = $totaliva;
																					}
																					?>
																					<tr class="bg-dark text-white">
																						<td colspan="9" align="right"><strong>Totales</strong></td>
																						<td></td>
																						<td align="right"><?php echo number_format($totalventas, 2, ',', '.'); ?></td>
																						<td align="right"><?php echo number_format($totalimppvp, 2, ',', '.'); ?></td>
																						<td align="right"><?php echo number_format($totalimpial, 2, ',', '.'); ?></td>
																						<td align="right"><?php echo number_format($totalventasnogra, 2, ',', '.'); ?></td>
																						<td align="right"><?php echo number_format($totalventasexe, 2, ',', '.'); ?></td>
																						<td align="right"><?php echo number_format($totalbaseimpo, 2, ',', '.'); ?></td>
																						<td></td>
																						<td align="right"><?php echo number_format($totaliva, 2, ',', '.'); ?></td>
																						<td align="right"><?php echo number_format($totalivareten, 2, ',', '.'); ?></td>
																					</tr>
																				</tbody>
																			</table>
																			<hr> <br>

																			<!-- RELACION DE COMPROBANTES MESES ANTERIORES -->
																			<h4>RELACION DE COMPROBANTES DE MESES ANTERIORES</h4>           
																			<table id="example1"  class="Estilo4" style="width:100%;">
																				<thead  style="background-color: #00137f;color: white;">
																					<tr id="cells">
																						<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Nro. OP</th>
																							<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Fecha Documento</th>
																								<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">RIF</th>
																									<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Nombre o Raz&oacute;n Social</th>
																										<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Nro. de Comp. Retencion</th>
																											<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numero Factura</th>
																												<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numero Nota Debido</th>
																													<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numero Nota Credito</th>
																														<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Tipo Tran.</th>
																															<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Numero Factura Afectada</th>
																																<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Ventas Gravadas incluyendo IVA</th>
																																	<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Impuesto al PVP</th>
																																		<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">IVA Percibido</th>
																																			<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Ventas No Gravadas</th>
																																				<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Ventas Excentas</th>
																																					<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Base Imponible 16%</th>
																																						<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">% Alicuota</th>
																																							<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">Impuesto IVA</th>
																																								<th align="center" height="50"  bgcolor="#B0C4DE"><span class="Estilo4-bold">IVA Retenido (por el comprador)</th>                        
																																								</tr>
																																							</thead>
																																							<tbody style="background-color: aliceblue">
																																								<?php
																																								$num1 = mssql_num_rows($query_retenciones);
																																								$totalventas1 = $totalimppvp1 = $totalimpial1 = $totalventasnogra1 = $totalventasexe1 = $totalbaseimpo1 = $totaliva1 = $totalivareten1 = 0;
																																								for($i=0;$i<$num1;$i++){
																																									$k = $i+1;
																																									?>
																																									<tr>
																																										<!-- <td align="center">N° OP</td> -->
																																										<td><?php echo $k; ?></td>
																																										<!-- <th align="center" >Fecha Documento</th> -->
																																										<td class="text-center"><?php echo date('d/m/Y', strtotime(mssql_result($query_retenciones, $i, 'FechaDoc'))); ?></td>
																																										<!-- <th align="center" >RIF</th> -->
																																										<td><?php echo mssql_result($query_retenciones, $i, 'ID3'); ?></td>
																																										<!-- <th align="center" >Nombre o Raz&oacute;n Social</th> -->
																																										<td><?php echo utf8_encode(mssql_result($query_retenciones, $i, 'DescripClie')); ?></td>
																																										<!-- <th align="center" >Nro. de Comp. Retención</th> -->
																																										<td><?php echo mssql_result($query_retenciones, $i, 'NroComprob'); ?></td>
																																										<!--<th align="center" >Numero Factura</th> -->
																																										<td><?php echo mssql_result($query_retenciones, $i, 'NroFact'); ?></td>
																																										<!-- <th align="center" >Numero Nota Debido</th> -->
																																										<td><?php echo mssql_result($query_retenciones, $i, 'NroND'); ?></td>
																																										<!-- <th align="center" >Numero Nota Credito</th> -->
																																										<td><?php echo mssql_result($query_retenciones, $i, 'NroNC'); ?></td>
																																										<!-- <th align="center" >Tipo Tran.</th> -->
																																										<td><?php echo mssql_result($query_retenciones, $i, 'TipTran'); ?></td>
																																										<!-- <th align="center" >Numero Factura Afectada</th> -->
																																										<td><?php echo mssql_result($query_retenciones, $i, 'NroFactAfec'); ?></td>
																																										<!-- <th align="center" >Ventas Gravadas incluyendo IVA</th>-->
																																										<td align="right"><?php echo number_format(mssql_result($query_retenciones, $i, 'TotalVentas'), 2, ',', '.'); $totalventas1 += mssql_result($query_retenciones, $i, 'TotalVentas'); ?></td>
																																										<!-- <th align="center" >Impuesto al PVP</th>-->
																																										<td align="right"><?php echo number_format(mssql_result($query_retenciones, $i, 'ImpPVP'), 2, ',', '.'); $totalimppvp1 += mssql_result($query_retenciones, $i, 'ImpPVP'); ?></td>
																																										<!-- <th align="center" >IVA Percibido</th>-->
																																										<td align="right"><?php echo number_format(mssql_result($query_retenciones, $i, 'ImpIAL'), 2, ',', '.'); $totalimpial1 += mssql_result($query_retenciones, $i, 'ImpIAL'); ?></td>
																																										<!-- <th align="center" >Ventas No Gravadas</th> -->    
																																										<td align="right"><?php echo number_format(mssql_result($query_retenciones, $i, 'VentasNoGra'), 2, ',', '.'); $totalventasnogra1 += mssql_result($query_retenciones, $i, 'VentasNoGra');  ?></td>
																																										<!-- <th align="center" >Ventas Excentas</th> -->
																																										<td align="right"><?php echo number_format(mssql_result($query_retenciones, $i, 'VEntasEXE'), 2, ',', '.'); $totalventasexe1 += mssql_result($query_retenciones, $i, 'VEntasEXE'); ?></td>
																																										<!-- <th align="center" >Base Imponible 16%</th> -->    
																																										<td align="right"><?php echo number_format(mssql_result($query_retenciones, $i, 'BaseImpo'), 2, ',', '.'); $totalbaseimpo1 += mssql_result($query_retenciones, $i, 'BaseImpo'); ?></td>    
																																										<!-- <th align="center" >% Alicuota</th> -->
																																										<td align="right"><?php echo rdecimal0(mssql_result($query, $i, 'PorIVA')); ?> %</td>
																																										<!-- <th align="center" >Impuesto IVA</th> -->
																																										<td align="right"><?php echo number_format(mssql_result($query_retenciones, $i, 'IVA'), 2, ',', '.'); $totaliva1 += mssql_result($query_retenciones, $i, 'IVA'); ?></td>
																																										<!-- <th align="center" >IVA Retenido (por el comprador)</th> -->
																																										<td align="right"><?php echo number_format(mssql_result($query_retenciones, $i, 'IVAReten'), 2, ',', '.'); $totalivareten1 += mssql_result($query_retenciones, $i, 'IVAReten'); ?></td>
																																									</tr>
																																									<?php
																																								}
																																								?>
																																								<tr class="bg-dark text-white">
																																									<td colspan="9" align="right"><strong>Totales</strong></td>
																																									<td></td>
																																									<td align="right"><?php echo number_format($totalventas1, 2, ',', '.'); ?></td>
																																									<td align="right"><?php echo number_format($totalimppvp1, 2, ',', '.'); ?></td>
																																									<td align="right"><?php echo number_format($totalimpial1, 2, ',', '.'); ?></td>
																																									<td align="right"><?php echo number_format($totalventasnogra1, 2, ',', '.'); ?></td>
																																									<td align="right"><?php echo number_format($totalventasexe1, 2, ',', '.'); ?></td>
																																									<td align="right"><?php echo number_format($totalbaseimpo1, 2, ',', '.'); ?></td>
																																									<td></td>
																																									<td align="right"><?php echo number_format($totaliva1, 2, ',', '.'); ?></td>
																																									<td align="right"><?php echo number_format($totalivareten1, 2, ',', '.'); ?></td>
																																								</tr>
																																							</tbody>
																																						</table>
																																						<hr> <br>

																																						<!--TABLE DE RESUMEN -->
																																						<table id="example1"  class="Estilo4" style="width:100%;">
																																							<thead  style="background-color: #00137f;color: white;">
																																								<tr >
																																									<th align="center" width="500" bgcolor="#B0C4DE"><span class="Estilo4-bold">RESUMEN DEL LIBRO DE VENTAS</th>
																																										<th align="center" width="100" bgcolor="#B0C4DE"><span class="Estilo4-bold">BASE IMPONIBLE</th>
																																											<th align="center" width="100" bgcolor="#B0C4DE"><span class="Estilo4-bold">DEBITO FISCAL</th>
																																											</tr>
																																										</thead>
																																										<tbody style="background-color: aliceblue">
																																											<tr>
																																												<td class="text-left">Total Ventas No Gravadas</td>
																																												<td align="right"><?php echo number_format($totalventasnogra, 2, ',', '.'); ?></td>
																																												<td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
																																											</tr>
																																											<tr>
																																												<td class="text-left">Total Impuesto al PVP</td>
																																												<td align="right"><?php echo number_format($totalimppvp, 2, ',', '.'); ?></td>
																																												<td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
																																											</tr>
																																											<tr>
																																												<td class="text-left">Total IVA Percibido</td>
																																												<td align="right"><?php echo number_format($totalimpial, 2, ',', '.'); ?></td>
																																												<td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
																																											</tr>
																																											<tr>
																																												<td class="text-left">Total Ventas Internas Exentas de IVA</td>
																																												<td align="right"><?php echo number_format($totalventasexe, 2, ',', '.'); ?></td>
																																												<td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
																																											</tr>
																																											<tr>
																																												<td class="text-left">Total Ventas Internas Gravadas por Alicuota General 12%</td>
																																												<td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
																																												<td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
																																											</tr>
																																											<tr>
																																												<td class="text-left">Total Ventas Internas Gravadas por Alicuota General 16%</td>
																																												<td align="right"><?php echo number_format($totalbaseimpo, 2, ',', '.'); ?></td>
																																												<td align="right"><?php echo number_format($totaliva, 2, ',', '.'); ?></td>
																																											</tr>
																																											<tr>
																																												<td class="text-left">Total Ventas Internas Gravadas por Alicuota Reducida 8%</td>
																																												<td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
																																												<td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
																																											</tr>
																																											<tr class="ui-widget-header">
																																												<td class="text-left" width="500"><strong>Total Ventas y Debitos para Efectos de Determinacion</strong></td>
																																												<td width="100" align="right"><strong><?php echo number_format($totalresumen, 2, ',', '.'); ?></strong></td>
																																												<td width="100" align="right"><strong><?php echo number_format($totalresumen2, 2, ',', '.'); ?></strong></td>
																																											</tr>
																																										</tbody>
																																									</table>