<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {

	$fechai = $_POST['fechai'].' 00:00:00';
	/*$fechai = normalize_date2($fechai).' 00:00:00';*/
	$fechaf = $_POST['fechaf'].' 23:59:59';
	$sucursal = $_POST['sucursal'];
	/*$fechaf = normalize_date2($fechaf).' 23:59:59';*/
	$fechaii = normalize_date($_POST['fechai']);
	$fechaff = normalize_date($_POST['fechaf']);

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
				select cxc.FechaR FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, cxc.NumeroT NroComprob, NULL NroFact, NULL NroND, NULL NroNC, '81-RET' TipTran, isnull(pag.NumeroD,cxc.NumeroD) NroFactAfec, NULL TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, NULL VEntasExe,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then isnull(tax.TGravable,cxc.BaseImpo) else 0 end BaseImpo, NULL PorIVA,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then ISNULL(tax.Monto,cxc.MtoTax) else 0 end IVA, isnull(case when count(*) over (Partition by cxc.NroUnico) = 1 then cxc.Monto else pag.MontoDocA end,cxc.RetenIVA) IVAReten, case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then 0 else 1 end EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
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
				select cxc.FechaR FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, cxc.NumeroT NroComprob, NULL NroFact, NULL NroND, NULL NroNC, '81-RET' TipTran, isnull(pag.NumeroD,cxc.NumeroD) NroFactAfec, NULL TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, NULL VEntasExe,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then isnull(tax.TGravable,cxc.BaseImpo) else 0 end BaseImpo, NULL PorIVA,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then ISNULL(tax.Monto,cxc.MtoTax) else 0 end IVA, isnull(case when count(*) over (Partition by cxc.NroUnico) = 1 then cxc.Monto else pag.MontoDocA end,cxc.RetenIVA) IVAReten, case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then 0 else 1 end EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
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
				select cxc.FechaR FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, cxc.NumeroT NroComprob, NULL NroFact, NULL NroND, NULL NroNC, '81-RET' TipTran, isnull(pag.NumeroD,cxc.NumeroD) NroFactAfec, NULL TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, NULL VEntasExe,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then isnull(tax.TGravable,cxc.BaseImpo) else 0 end BaseImpo, NULL PorIVA,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then ISNULL(tax.Monto,cxc.MtoTax) else 0 end IVA, isnull(case when count(*) over (Partition by cxc.NroUnico) = 1 then cxc.Monto else pag.MontoDocA end,cxc.RetenIVA) IVAReten, case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then 0 else 1 end EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
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
				select cxc.FechaR FechaDoc, cli.ID3 ID3,  cli.Descrip DescripClie, cxc.NumeroT NroComprob, NULL NroFact, NULL NroND, NULL NroNC, '81-RET' TipTran, isnull(pag.NumeroD,cxc.NumeroD) NroFactAfec, NULL TotalVentas, NULL ImpPVP, NULL ImpIAL, NULL VentasNoGra, NULL VEntasExe,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then isnull(tax.TGravable,cxc.BaseImpo) else 0 end BaseImpo, NULL PorIVA,case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then ISNULL(tax.Monto,cxc.MtoTax) else 0 end IVA, isnull(case when count(*) over (Partition by cxc.NroUnico) = 1 then cxc.Monto else pag.MontoDocA end,cxc.RetenIVA) IVAReten, case when datepart(mm,ft.FechaE) != datepart(mm,cxc.FechaE) then 0 else 1 end EnPeriodo, cxc.CodSucu CodSucu, cxc.FechaE FechaF
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
	$num = mssql_num_rows($query);	?>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">Libro de Ventas</h1>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-sm-3 mt-4 form-check-inline">
					</div>
					<div class="col-sm-3 mt-4 form-check-inline">
						<dt class="col-sm-3 text-gray">Desde:</dt>
						<input type="text" class="form-control-sm col-8 text-center" id="fechai" value="<?php echo $fechaii; ?>" readonly>
					</div>
					<div class="col-sm-3 mt-4 form-check-inline">
						<dt class="col-sm-4 text-gray">Hasta:</dt>
						<input type="text" class="form-control-sm col-sm-8 text-center" id="fechaf" value="<?php echo $fechaff; ?>" readonly>&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<script type="text/javascript">
							function regresa(){
								window.location.href = "principal1.php?page=libro_ventas_global&mod=1";
							}
						</script>

						<button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
					</div>
				</div>
			</div>
		</div> 
		<div class="content"> 
			<table id="example1" class="table table-sm text-sm text-center table-condensed table-bordered table-striped" style="width:100%;">
				<thead  style="background-color: #00137f;color: white;">
					<tr id="cells">
						<td align="center">N° OP</td>
						<th align="center" >Fecha Documento</th>
						<th align="center" >RIF</th>
						<th align="center" >Nombre o Raz&oacute;n Social</th>
						<th align="center" >Nro. de Comp. Retencion</th>
						<th align="center" >Numero Factura</th>
						<th align="center" >Numero Nota Debido</th>
						<th align="center" >Numero Nota Credito</th>
						<th align="center" >Tipo Tran.</th>
						<th align="center" >Numero Factura Afectada</th>
						<th align="center" >Ventas Gravadas incluyendo IVA</th>
						<th align="center" >Impuesto al PVP</th>
						<th align="center" >IVA Percibido</th>
						<th align="center" >Ventas No Gravadas</th>
						<th align="center" >Ventas Excentas</th>
						<th align="center" >Base Imponible 16%</th>
						<th align="center" >% Alicuota</th>
						<th align="center" >Impuesto IVA</th>
						<th align="center" >IVA Retenido (por el comprador)</th>						
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
							<td><?php echo rdecimal2(mssql_result($query, $i, 'TotalVentas')); $totalventas += mssql_result($query, $i, 'TotalVentas'); ?></td>
							<!-- <th align="center" >Impuesto al PVP</th>-->
							<td><?php echo rdecimal2(mssql_result($query, $i, 'ImpPVP')); $totalimppvp += mssql_result($query, $i, 'ImpPVP'); ?></td>
							<!-- <th align="center" >IVA Percibido</th>-->
							<td class="text-right"><?php echo rdecimal2(mssql_result($query, $i, 'ImpIAL')); $totalimpial += mssql_result($query, $i, 'ImpIAL'); ?></td>
							<!-- <th align="center" >Ventas No Gravadas</th> -->	
							<td class="text-right"><?php echo '0'; /*rdecimal2(mssql_result($query, $i, 'VentasNoGra'));*/ /*$totalventasnogra += mssql_result($query, $i, 'VentasNoGra');*/  ?></td>
							<!-- <th align="center" >Ventas Excentas</th> -->
							<td class="text-right"><?php echo rdecimal2(mssql_result($query, $i, 'VEntasEXE')); $totalventasexe += mssql_result($query, $i, 'VEntasEXE'); ?></td>
							<!-- <th align="center" >Base Imponible 16%</th> -->	
							<td class="text-right"><?php echo rdecimal2(mssql_result($query, $i, 'BaseImpo')); $totalbaseimpo += mssql_result($query, $i, 'BaseImpo'); ?></td>					
							<!-- <th align="center" >% Alicuota</th> -->
							<td class="text-right"><?php echo rdecimal0(mssql_result($query, $i, 'PorIVA')); ?> %</td>
							<!-- <th align="center" >Impuesto IVA</th> -->
							<td class="text-right"><?php echo rdecimal2(mssql_result($query, $i, 'IVA')); $totaliva += mssql_result($query, $i, 'IVA'); ?></td>
							<!-- <th align="center" >IVA Retenido (por el comprador)</th> -->
							<td class="text-right"><?php echo rdecimal2(mssql_result($query, $i, 'IVAReten')); $totalivareten += mssql_result($query, $i, 'IVAReten'); ?></td>
						</tr>
						<?php
						$totalresumen = $totalimppvp + $totalimpial + $totalventasexe + $totalbaseimpo + $totalventasnogra;
						$totalresumen2 = $totaliva;
					}
					?>
					<tr class="bg-dark text-white">
						<td colspan="9"><strong>Totales</strong></td>
						<td></td>
						<td class="text-right"><?php echo rdecimal2($totalventas); ?></td>
						<td class="text-right"><?php echo rdecimal2($totalimppvp); ?></td>
						<td class="text-right"><?php echo rdecimal2($totalimpial); ?></td>
						<td class="text-right"><?php echo rdecimal2($totalventasnogra); ?></td>
						<td class="text-right"><?php echo rdecimal2($totalventasexe); ?></td>
						<td class="text-right"><?php echo rdecimal2($totalbaseimpo); ?></td>
						<td></td>
						<td class="text-right"><?php echo rdecimal2($totaliva); ?></td>
						<td class="text-right"><?php echo rdecimal2($totalivareten); ?></td>
					</tr>
				</tbody>
			</table>
			<hr>

			<!-- RELACION DE COMPROBANTES MESES ANTERIORES -->
			<h4>RELACION DE COMPROBANTES DE MESES ANTERIORES</h4>			
			<table id="example1" class="table table-sm text-sm text-center table-condensed table-bordered table-striped" style="width:100%;">
				<thead  style="background-color: #00137f;color: white;">
					<tr id="cells">
						<td align="center">N° OP</td>
						<th align="center" >Fecha Documento</th>
						<th align="center" >RIF</th>
						<th align="center" >Nombre o Raz&oacute;n Social</th>
						<th align="center" >Nro. de Comp. Retención</th>
						<th align="center" >Numero Factura</th>
						<th align="center" >Numero Nota Debido</th>
						<th align="center" >Numero Nota Credito</th>
						<th align="center" >Tipo Tran.</th>
						<th align="center" >Numero Factura Afectada</th>
						<th align="center" >Ventas Gravadas incluyendo IVA</th>
						<th align="center" >Impuesto al PVP</th>
						<th align="center" >IVA Percibido</th>
						<th align="center" >Ventas No Gravadas</th>
						<th align="center" >Ventas Excentas</th>
						<th align="center" >Base Imponible 16%</th>
						<th align="center" >% Alicuota</th>
						<th align="center" >Impuesto IVA</th>
						<th align="center" >IVA Retenido (por el comprador)</th>						
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
							<td><?php echo number_format(mssql_result($query_retenciones, $i, 'TotalVentas'), 2, ',', '.'); $totalventas1 += mssql_result($query_retenciones, $i, 'TotalVentas'); ?></td>
							<!-- <th align="center" >Impuesto al PVP</th>-->
							<td><?php echo number_format(mssql_result($query_retenciones, $i, 'ImpPVP'), 2, ',', '.'); $totalimppvp1 += mssql_result($query_retenciones, $i, 'ImpPVP'); ?></td>
							<!-- <th align="center" >IVA Percibido</th>-->
							<td class="text-right"><?php echo number_format(mssql_result($query_retenciones, $i, 'ImpIAL'), 2, ',', '.'); $totalimpial1 += mssql_result($query_retenciones, $i, 'ImpIAL'); ?></td>
							<!-- <th align="center" >Ventas No Gravadas</th> -->	
							<td class="text-right"><?php echo number_format(mssql_result($query_retenciones, $i, 'VentasNoGra'), 2, ',', '.'); $totalventasnogra1 += mssql_result($query_retenciones, $i, 'VentasNoGra');  ?></td>
							<!-- <th align="center" >Ventas Excentas</th> -->
							<td class="text-right"><?php echo number_format(mssql_result($query_retenciones, $i, 'VEntasEXE'), 2, ',', '.'); $totalventasexe1 += mssql_result($query_retenciones, $i, 'VEntasEXE'); ?></td>
							<!-- <th align="center" >Base Imponible 16%</th> -->	
							<td class="text-right"><?php echo number_format(mssql_result($query_retenciones, $i, 'BaseImpo'), 2, ',', '.'); $totalbaseimpo1 += mssql_result($query_retenciones, $i, 'BaseImpo'); ?></td>	
							<!-- <th align="center" >% Alicuota</th> -->
							<td class="text-right"><?php echo rdecimal0(mssql_result($query, $i, 'PorIVA')); ?> %</td>
							<!-- <th align="center" >Impuesto IVA</th> -->
							<td class="text-right"><?php echo number_format(mssql_result($query_retenciones, $i, 'IVA'), 2, ',', '.'); $totaliva1 += mssql_result($query_retenciones, $i, 'IVA'); ?></td>
							<!-- <th align="center" >IVA Retenido (por el comprador)</th> -->
							<td class="text-right"><?php echo number_format(mssql_result($query_retenciones, $i, 'IVAReten'), 2, ',', '.'); $totalivareten1 += mssql_result($query_retenciones, $i, 'IVAReten'); ?></td>
						</tr>
						<?php
					}
					?>
					<tr class="bg-dark text-white">
						<td colspan="9"><strong>Totales</strong></td>
						<td></td>
						<td class="text-right"><?php echo number_format($totalventas1, 2, ',', '.'); ?></td>
						<td class="text-right"><?php echo number_format($totalimppvp1, 2, ',', '.'); ?></td>
						<td class="text-right"><?php echo number_format($totalimpial1, 2, ',', '.'); ?></td>
						<td class="text-right"><?php echo number_format($totalventasnogra1, 2, ',', '.'); ?></td>
						<td class="text-right"><?php echo number_format($totalventasexe1, 2, ',', '.'); ?></td>
						<td class="text-right"><?php echo number_format($totalbaseimpo1, 2, ',', '.'); ?></td>
						<td></td>
						<td class="text-right"><?php echo number_format($totaliva1, 2, ',', '.'); ?></td>
						<td class="text-right"><?php echo number_format($totalivareten1, 2, ',', '.'); ?></td>
					</tr>
				</tbody>
			</table>


			<!--TABLE DE RESUMEN -->
			<table id="tabla1" class="table table-sm text-center table-condensed table-bordered table-striped" style="width:40%;">
				<thead  style="background-color: #00137f;color: white;">
					<tr >
						<td width="500">RESUMEN DEL LIBRO DE VENTAS</td>
						<td width="100">BASE IMPONIBLE</td>
						<td width="100">DEBITO FISCAL</td>
					</tr>
				</thead>
				<tbody style="background-color: aliceblue">
					<tr>
						<td class="text-left">Total Ventas No Gravadas</td>
						<td class="text-right"><?php echo number_format($totalventasnogra, 2, ',', '.'); ?></td>
						<td class="text-right"><?php echo number_format(0, 2, ',', '.'); ?></td>
					</tr>
					<tr>
						<td class="text-left">Total Impuesto al PVP</td>
						<td class="text-right"><?php echo number_format($totalimppvp, 2, ',', '.'); ?></td>
						<td class="text-right"><?php echo number_format(0, 2, ',', '.'); ?></td>
					</tr>
					<tr>
						<td class="text-left">Total IVA Percibido</td>
						<td class="text-right"><?php echo number_format($totalimpial, 2, ',', '.'); ?></td>
						<td class="text-right"><?php echo number_format(0, 2, ',', '.'); ?></td>
					</tr>
					<tr>
						<td class="text-left">Total Ventas Internas Exentas de IVA</td>
						<td class="text-right"><?php echo number_format($totalventasexe, 2, ',', '.'); ?></td>
						<td class="text-right"><?php echo number_format(0, 2, ',', '.'); ?></td>
					</tr>
					<tr>
						<td class="text-left">Total Ventas Internas Gravadas por Alicuota General 12%</td>
						<td class="text-right"><?php echo number_format(0, 2, ',', '.'); ?></td>
						<td class="text-right"><?php echo number_format(0, 2, ',', '.'); ?></td>
					</tr>
					<tr>
						<td class="text-left">Total Ventas Internas Gravadas por Alicuota General 16%</td>
						<td class="text-right"><?php echo number_format($totalbaseimpo, 2, ',', '.'); ?></td>
						<td class="text-right"><?php echo number_format($totaliva, 2, ',', '.'); ?></td>
					</tr>
					<tr>
						<td class="text-left">Total Ventas Internas Gravadas por Alicuota Reducida 8%</td>
						<td class="text-right"><?php echo number_format(0, 2, ',', '.'); ?></td>
						<td class="text-right"><?php echo number_format(0, 2, ',', '.'); ?></td>
					</tr>
					<tr class="ui-widget-header">
						<td class="text-left" width="500"><strong>Total Ventas y Debitos para Efectos de Determinacion</strong></td>
						<td width="100" class="text-right"><strong><?php echo number_format($totalresumen, 2, ',', '.'); ?></strong></td>
						<td width="100" class="text-right"><strong><?php echo number_format($totalresumen2, 2, ',', '.'); ?></strong></td>
					</tr>
				</tbody>
			</table>
			<div align="center">
				<a href="libro_ventas_global_excel.php?&fechai=<?php echo $fechai; ?>&fechaf=<?php echo $fechaf; ?>&sucursal=<?php echo $sucursal; ?>"><img src="images/excel.jpeg" width="19" height="18" border="0" /> Exportar a Excel</a>
			</div>
			<br> 
		</div>



	</div>
	<?php
} else {
	header('Location: index.php');
}
?>