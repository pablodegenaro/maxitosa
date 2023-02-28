<?php 
$top10marcas = $top10clientes = array();
switch($sucursal) {

    # ====================
    # === PUERTO ORDAZ === 
    # ====================
    case $pto_ordaz: 
        // documentos por despachar
    $doc_x_despachar = mssql_query("SELECT count(numerod) AS CUENTA from SAFACT where numerod not in (select numeros from appfacturas_det) and (TipoFac ='A' or TipoFac ='C') AND numeror is null and CodSucu='$pto_ordaz'");
    $pordespachar = mssql_result($doc_x_despachar,0,"CUENTA");

        // documentos por facturarA
    $doc_x_facturar = mssql_query("SELECT count(numerod) AS CUENTA from SAFACT where TipoFac ='E' AND CodSucu='$pto_ordaz' ");
    $porfacturar = mssql_result($doc_x_facturar,0,"CUENTA");

        // saldo pendiente por cobrar BS
    $cxcbs = mssql_query("SELECT COALESCE(SUM(Saldo),0) as saldo FROM saacxc WHERE saldo > 0 AND tipocxc='10' AND CodSucu='$pto_ordaz'");
    $cxcsaldo = mssql_result($cxcbs,0,"saldo");

        // saldo pendiente por cobrar $
    $cxc_divisa = mssql_query("SELECT COALESCE(sum(saldo / Factor),0) as saldo from SAACXC WHERE saldo > 0 AND tipocxc='10' AND CodSucu='$pto_ordaz'");
    $cxc_divisa_saldo = mssql_result($cxc_divisa,0,"saldo");

    $factor = mssql_query("SELECT top 1 factor from saconf where CodSucu='$pto_ordaz'");
    $factor1 = mssql_result($factor,0,"factor");

        // saldo pendiente por pagar bs
    $cxpbs = mssql_query("SELECT COALESCE(SUM(Saldo),0) as saldo FROM SAACXP WHERE saldo > 0 AND tipocxp='10' AND CodSucu='$pto_ordaz'");
    $cxpsaldo = mssql_result($cxpbs,0,"saldo");

        // saldo pendiente por pagar $
    $cxpdivisa = mssql_query("SELECT COALESCE(sum(a.saldo / c.Factor),0) as saldo from saacxp as a inner join saprov as b on a.codprov = b.codprov left  join SACOMP_01 as c on a.numerod=c.NumeroD and (case when a.TipoCxP= 10 then 'XX' else '' end) = (case when c.TipoCom in ('H','J') then 'XX' else '' end)  
        where a.tipocxp='10' and a.saldo>0 AND a.CodSucu='$pto_ordaz'");
    $cxp_divisa_saldo = mssql_result($cxpdivisa,0,"saldo");

        // operaciones del dia
    $opera_del_dia = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        DECLARE @EDV VARCHAR(MAX)
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        SELECT count(numerod) as cuenta FROM SAFACT WHERE (TipoFac ='A' or tipofac='C') and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf AND CodSucu='$pto_ordaz'");
    $opera_del_dia_ver = mssql_result($opera_del_dia,0,"cuenta");

        // ventas del mes
    $venta_mes = mssql_query("
     DECLARE @fechai DATE
     DECLARE @fechaf DATE
     DECLARE @fecha_ini_mes DATE
     set @fechai = GETDATE()
     set @fechaf = GETDATE()
     set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
     select
     CONVERT(varchar, CAST(sum(case when TipoFac in ('A','c') then MtoTotal/FactorP when TipoFac in ('B','d') then (MtoTotal/FactorP) * -1 end) - sum(case when TipoFac in ('A','c') then (Descto1 + Descto2)/FactorP when TipoFac in ('B','d') then (Descto1 + Descto2)/FactorP *-1  end) AS money), 1) Total
     from
     SAFACT where TipoFac in ('A','b','c','d') and DATEADD(dd, 0, DATEDIFF(dd, 0, safact.fechae)) BETWEEN @fecha_ini_mes and @fechaf AND CodSucu='$pto_ordaz'");
    $ventas_mes_ver = mssql_result($venta_mes,0,"Total");

        // ventas del dia
    $venta_dia = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        SELECT CONVERT(varchar, CAST(sum(case when TipoFac in ('A','c') then MtoTotal/FactorP when TipoFac in ('B','d') then (MtoTotal/FactorP) * -1 end) - sum(case when TipoFac in ('A','c') then (Descto1 + Descto2)/FactorP when TipoFac in ('B','d') then (Descto1 + Descto2)/FactorP *-1  end) AS money), 1) Total
        from
        SAFACT where TipoFac in ('A','b','c','d') and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf AND CodSucu='$pto_ordaz' ");
    $ventas_dia_ver = mssql_result($venta_dia,0,"Total");

    $venta_dia_D = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()

        select
        COALESCE(sum(MtoTotal/factorp),0) as total from SAFACT where TipoFac in ('B','D') and DATEADD(dd, 0, DATEDIFF(dd, 0, safact.fechae)) BETWEEN @fechai and @fechaf AND CodSucu='$pto_ordaz' ");
    $ventas_dia_ver_D = mssql_result($venta_dia_D,0,"Total");

        // top marcas
    $top10marcas = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        DECLARE @fecha_ini_mes DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
        SELECT top 10 marca, sum(montod)as montod 
        from
        (

            SELECT top 10 marca,
            SUM(COALESCE(((TotalItem+itemfact.mtotax)/NULLIF(itemfact.factorp,0)) * (CASE WHEN itemfact.TipoFac in ('A') THEN 1 ELSE -1 END), 0)) as montod
            FROM SAFACT fact
            INNER JOIN SAITEMFAC itemfact ON itemfact.NumeroD = fact.NumeroD
            INNER JOIN SAPROD prod ON prod.CodProd = itemfact.CodItem
            WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, fact.FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND itemfact.tipofac IN ('A') AND fact.CodSucu = '$pto_ordaz'
            AND fact.NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE x.CodSucu = '$pto_ordaz' and X.TipoFac = 'A' AND x.NumeroR IS NOT NULL AND CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE z.CodSucu = '$pto_ordaz' and Z.NumeroD = x.NumeroR AND Z.TipoFac in ('B')) AS BIGINT))
            GROUP BY marca
            ORDER BY montod DESC
            UNION ALL
            SELECT top 10 marca,
            SUM(COALESCE((TotalItem/NULLIF(itemfact.factorp,0)) * (CASE WHEN itemfact.TipoFac in ('C') THEN 1 ELSE -1 END), 0)) as montod
            FROM SAFACT fact
            INNER JOIN SAITEMFAC itemfact ON itemfact.NumeroD = fact.NumeroD
            INNER JOIN SAPROD prod ON prod.CodProd = itemfact.CodItem
            WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, fact.FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND itemfact.tipofac IN ('C') AND fact.CodSucu = '$pto_ordaz'
            AND fact.NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE x.CodSucu = '$pto_ordaz' and X.TipoFac = 'C' AND x.NumeroR IS NOT NULL AND CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE z.CodSucu = '$pto_ordaz' and Z.NumeroD = x.NumeroR AND Z.TipoFac in ('D')) AS BIGINT))
            GROUP BY marca
            ORDER BY montod DESC
        ) as T  GROUP BY marca  ORDER BY montod DESC");

        // top clientes
        //
    $top10clientes = mssql_query("
       DECLARE @fechai DATE
       DECLARE @fechaf DATE
       DECLARE @fecha_ini_mes DATE
       set @fechai = GETDATE()
       set @fechaf = GETDATE()
       set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
       SELECT top 10 codclie, Descrip,  sum(case when TipoFac in ('A','C') then MtoTotal when TipoFac in ('B','D') then -MtoTotal else 0 end/Factorp) MontoD
       from SAFACT 

       WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, FechaE)) BETWEEN @fecha_ini_mes and @fechaf and TipoFac in ('A','B','C','D') and CodSucu = '$pto_ordaz'
       group by codclie, Descrip
       order by MontoD desc");

    $inv_valor = mssql_query("SELECT c.Descrip ,a.CodUbic, sum((a.existen  + (a.ExUnidad/d.CantEmpaq)) * b.costo_total) as total, sum((a.existen  + (a.ExUnidad/d.CantEmpaq)) * b.Profit1) as total_venta
        from saexis a inner join saprod_99 b on a.CodProd=b.CodProd inner join SADEPO as c on a.CodUbic=c.CodUbic inner join saprod as d on a.CodProd=d.CodProd 
        where  a.Existen > 0 or a.ExUnidad > 0  GROUP BY  c.Descrip, a.CodUbic  order by a.CodUbic");

    $saldo_bancos = mssql_query("SELECT id, Descrip, NroCta, Saldo from Bancos_App");

    $ventasxasesorfac = mssql_query("
       DECLARE @fechai DATE
       DECLARE @fechaf DATE
       DECLARE @fecha_ini_mes DATE
       set @fechai = GETDATE()
       set @fechaf = GETDATE()
       set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
       select SAFACT.CodVend, SAVEND.Descrip, sum(case when TipoFac = 'A' then MtoTotal when TipoFac = 'b' then -MtoTotal else 0 end/Factorp) MontoD from SAFACT inner join SAVEND ON SAFACT.CodVend = SAVEND.CodVend
       WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND tipofac IN ('A','B') AND SAFACT.CODSUCU='$pto_ordaz'
       group by SAFACT.CodVend, SAVEND.Descrip
       order by MontoD desc");


    $ventasxasesorne = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        DECLARE @fecha_ini_mes DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
        select SAFACT.CodVend, SAVEND.Descrip, sum(case when TipoFac = 'C' then MtoTotal when TipoFac = 'D' then -MtoTotal else 0 end/Factorp) MontoD from SAFACT inner join SAVEND ON SAFACT.CodVend = SAVEND.CodVend
        WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND tipofac IN ('C','D')  and safact.CodSucu='$pto_ordaz'
        group by SAFACT.CodVend, SAVEND.Descrip
        order by MontoD desc");
    break;


    # ================
    # === MATURIN ==== 
    # ================
    case $maturin: 
        // documentos por despachar
    $doc_x_despachar = mssql_query("SELECT count(numerod) AS CUENTA from SAFACT where numerod not in (select numeros from appfacturas_det) and (TipoFac ='A' or TipoFac ='C') AND numeror is null and CodSucu='$maturin'");
    $pordespachar = mssql_result($doc_x_despachar,0,"CUENTA");

        // documentos por facturar
    $doc_x_facturar = mssql_query("SELECT count(numerod) AS CUENTA from SAFACT where TipoFac ='E' AND CodSucu='$maturin' ");
    $porfacturar = mssql_result($doc_x_facturar,0,"CUENTA");

        // saldo pendiente por cobrar BS
    $cxcbs = mssql_query("SELECT COALESCE(SUM(Saldo),0) as saldo FROM saacxc WHERE saldo > 0 AND tipocxc='10' AND CodSucu='$maturin'");
    $cxcsaldo = mssql_result($cxcbs,0,"saldo");

        // saldo pendiente por cobrar $
    $cxc_divisa = mssql_query("SELECT COALESCE(sum(saldo / Factor),0) as saldo from SAACXC WHERE saldo > 0 AND tipocxc='10' AND CodSucu='$maturin'");
    $cxc_divisa_saldo = mssql_result($cxc_divisa,0,"saldo");

    $factor = mssql_query("SELECT top 1 factor from saconf where CodSucu='$maturin'");
    $factor1 = mssql_result($factor,0,"factor");

        // saldo pendiente por pagar bs
    $cxpbs = mssql_query("SELECT COALESCE(SUM(Saldo),0) as saldo FROM SAACXP WHERE saldo > 0 AND tipocxp='10' AND CodSucu='$maturin'");
    $cxpsaldo = mssql_result($cxpbs,0,"saldo");

        // saldo pendiente por pagar $
    $cxpdivisa = mssql_query("SELECT COALESCE(sum(a.saldo / c.Factor),0) as saldo from saacxp as a inner join saprov as b on a.codprov = b.codprov left  join SACOMP_01 as c on a.numerod=c.NumeroD and (case when a.TipoCxP= 10 then 'XX' else '' end) = (case when c.TipoCom in ('H','J') then 'XX' else '' end)  
        where  a.tipocxp='10' and a.saldo>0 and a.CodSucu='$maturin'");
    $cxp_divisa_saldo = mssql_result($cxpdivisa,0,"saldo");

        // operaciones del dia
    $opera_del_dia = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        DECLARE @EDV VARCHAR(MAX)
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        SELECT count(numerod) as cuenta FROM SAFACT WHERE (TipoFac ='A' or tipofac='C') and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf AND CodSucu='$maturin'");
    $opera_del_dia_ver = mssql_result($opera_del_dia,0,"cuenta");

        // ventas del mes
    $venta_mes = mssql_query("
      DECLARE @fechai DATE
      DECLARE @fechaf DATE
      DECLARE @fecha_ini_mes DATE
      set @fechai = GETDATE()
      set @fechaf = GETDATE()
      set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
      select
      CONVERT(varchar, CAST(sum(case when TipoFac in ('A','c') then MtoTotal/FactorP when TipoFac in ('B','d') then (MtoTotal/FactorP) * -1 end) - sum(case when TipoFac in ('A','c') then (Descto1 + Descto2)/FactorP when TipoFac in ('B','d') then (Descto1 + Descto2)/FactorP *-1  end) AS money), 1) Total
      from
      SAFACT where TipoFac in ('A','b','c','d') and DATEADD(dd, 0, DATEDIFF(dd, 0, safact.fechae)) BETWEEN @fecha_ini_mes and @fechaf AND CodSucu='$maturin'");
    $ventas_mes_ver = mssql_result($venta_mes,0,"Total");

        // ventas del dia
    $venta_dia = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        SELECT CONVERT(varchar, CAST(sum(case when TipoFac in ('A','c') then MtoTotal/FactorP when TipoFac in ('B','d') then (MtoTotal/FactorP) * -1 end) - sum(case when TipoFac in ('A','c') then (Descto1 + Descto2)/FactorP when TipoFac in ('B','d') then (Descto1 + Descto2)/FactorP *-1  end) AS money), 1) Total
        from
        SAFACT where TipoFac in ('A','b','c','d') and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf AND CodSucu='$maturin' ");
    $ventas_dia_ver = mssql_result($venta_dia,0,"Total");

    $venta_dia_D = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()

        select
        COALESCE(sum(MtoTotal/factorp),0) as total from SAFACT where TipoFac in ('B','D') and DATEADD(dd, 0, DATEDIFF(dd, 0, safact.fechae)) BETWEEN @fechai and @fechaf AND CodSucu='$maturin' ");
    $ventas_dia_ver_D = mssql_result($venta_dia_D,0,"Total");

        // top marcas
    $top10marcas = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        DECLARE @fecha_ini_mes DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
        SELECT top 10 marca, sum(montod)as montod 
        from
        (

            SELECT top 10 marca,
            SUM(COALESCE(((TotalItem+itemfact.mtotax)/NULLIF(itemfact.factorp,0)) * (CASE WHEN itemfact.TipoFac in ('A') THEN 1 ELSE -1 END), 0)) as montod
            FROM SAFACT fact
            INNER JOIN SAITEMFAC itemfact ON itemfact.NumeroD = fact.NumeroD
            INNER JOIN SAPROD prod ON prod.CodProd = itemfact.CodItem
            WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, fact.FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND itemfact.tipofac IN ('A') AND fact.CodSucu = '$maturin'
            AND fact.NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE x.CodSucu = '$maturin' and X.TipoFac = 'A' AND x.NumeroR IS NOT NULL AND CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE z.CodSucu = '$maturin' and Z.NumeroD = x.NumeroR AND Z.TipoFac in ('B')) AS BIGINT))
            GROUP BY marca
            ORDER BY montod DESC
            UNION ALL
            SELECT top 10 marca,
            SUM(COALESCE((TotalItem/NULLIF(itemfact.factorp,0)) * (CASE WHEN itemfact.TipoFac in ('C') THEN 1 ELSE -1 END), 0)) as montod
            FROM SAFACT fact
            INNER JOIN SAITEMFAC itemfact ON itemfact.NumeroD = fact.NumeroD
            INNER JOIN SAPROD prod ON prod.CodProd = itemfact.CodItem
            WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, fact.FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND itemfact.tipofac IN ('C') AND fact.CodSucu = '$maturin'
            AND fact.NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE x.CodSucu = '$maturin' and X.TipoFac = 'C' AND x.NumeroR IS NOT NULL AND CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE z.CodSucu = '$maturin' and Z.NumeroD = x.NumeroR AND Z.TipoFac in ('D')) AS BIGINT))
            GROUP BY marca
            ORDER BY montod DESC
        ) as T  GROUP BY marca  ORDER BY montod DESC");

        // top clientes
        //
    $top10clientes = mssql_query("
       DECLARE @fechai DATE
       DECLARE @fechaf DATE
       DECLARE @fecha_ini_mes DATE
       set @fechai = GETDATE()
       set @fechaf = GETDATE()
       set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
       SELECT top 10 codclie, Descrip,  sum(case when TipoFac in ('A','C') then MtoTotal when TipoFac in ('B','D') then -MtoTotal else 0 end/Factor) MontoD
       from SAFACT 

       WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, FechaE)) BETWEEN @fecha_ini_mes and @fechaf and TipoFac in ('A','B','C','D') and CodSucu='$maturin'
       group by codclie, Descrip
       order by MontoD desc");

    $inv_valor = mssql_query("SELECT c.Descrip ,a.CodUbic, sum((a.existen  + (a.ExUnidad/d.CantEmpaq)) * b.costo_total) as total, sum((a.existen  + (a.ExUnidad/d.CantEmpaq)) * b.Profit1) as total_venta
        from saexis a inner join saprod_99 b on a.CodProd=b.CodProd inner join SADEPO as c on a.CodUbic=c.CodUbic inner join saprod as d on a.CodProd=d.CodProd 
        where  a.Existen > 0 or a.ExUnidad > 0  GROUP BY  c.Descrip, a.CodUbic  order by a.CodUbic");

    $saldo_bancos = mssql_query("SELECT id, Descrip, NroCta, Saldo from Bancos_App");


    $ventasxasesorfac = mssql_query("
      DECLARE @fechai DATE
      DECLARE @fechaf DATE
      DECLARE @fecha_ini_mes DATE
      set @fechai = GETDATE()
      set @fechaf = GETDATE()
      set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
      select SAFACT.CodVend, SAVEND.Descrip, sum(case when TipoFac = 'A' then MtoTotal when TipoFac = 'b' then -MtoTotal else 0 end/Factorp) MontoD from SAFACT inner join SAVEND ON SAFACT.CodVend = SAVEND.CodVend
      WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND tipofac IN ('A','B') AND SAFACT.CODSUCU='$maturin'
      group by SAFACT.CodVend, SAVEND.Descrip
      order by MontoD desc");


    $ventasxasesorne = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        DECLARE @fecha_ini_mes DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
        select SAFACT.CodVend, SAVEND.Descrip, sum(case when TipoFac = 'C' then MtoTotal when TipoFac = 'D' then -MtoTotal else 0 end/Factorp) MontoD from SAFACT inner join SAVEND ON SAFACT.CodVend = SAVEND.CodVend
        WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND tipofac IN ('C','D')  and safact.CodSucu='$maturin'
        group by SAFACT.CodVend, SAVEND.Descrip
        order by MontoD desc");
    break;


     # ====================
    # === CARUPANO === 
    # ====================
    case $carupano: 
        // documentos por despachar
    $doc_x_despachar = mssql_query("SELECT count(numerod) AS CUENTA from SAFACT where numerod not in (select numeros from appfacturas_det) and (TipoFac ='A' or TipoFac ='C') AND numeror is null and CodSucu='$carupano'");
    $pordespachar = mssql_result($doc_x_despachar,0,"CUENTA");

        // documentos por facturarA
    $doc_x_facturar = mssql_query("SELECT count(numerod) AS CUENTA from SAFACT where TipoFac ='E' AND CodSucu='$carupano' ");
    $porfacturar = mssql_result($doc_x_facturar,0,"CUENTA");

        // saldo pendiente por cobrar BS
    $cxcbs = mssql_query("SELECT COALESCE(SUM(Saldo),0) as saldo FROM saacxc WHERE saldo > 0 AND tipocxc='10' AND CodSucu='$carupano'");
    $cxcsaldo = mssql_result($cxcbs,0,"saldo");

        // saldo pendiente por cobrar $
    $cxc_divisa = mssql_query("SELECT COALESCE(sum(saldo / Factor),0) as saldo from SAACXC WHERE saldo > 0 AND tipocxc='10' AND CodSucu='$carupano'");
    $cxc_divisa_saldo = mssql_result($cxc_divisa,0,"saldo");

    $factor = mssql_query("SELECT top 1 factor from saconf where CodSucu='$carupano'");
    $factor1 = mssql_result($factor,0,"factor");

        // saldo pendiente por pagar bs
    $cxpbs = mssql_query("SELECT COALESCE(SUM(Saldo),0) as saldo FROM SAACXP WHERE saldo > 0 AND tipocxp='10' AND CodSucu='$carupano'");
    $cxpsaldo = mssql_result($cxpbs,0,"saldo");

        // saldo pendiente por pagar $
    $cxpdivisa = mssql_query("SELECT COALESCE(sum(a.saldo / c.Factor),0) as saldo from saacxp as a inner join saprov as b on a.codprov = b.codprov left  join SACOMP_01 as c on a.numerod=c.NumeroD and (case when a.TipoCxP= 10 then 'XX' else '' end) = (case when c.TipoCom in ('H','J') then 'XX' else '' end)  
        where  a.tipocxp='10' and a.saldo>0 and a.CodSucu='$carupano'");
    $cxp_divisa_saldo = mssql_result($cxpdivisa,0,"saldo");

        // operaciones del dia
    $opera_del_dia = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        DECLARE @EDV VARCHAR(MAX)
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        SELECT count(numerod) as cuenta FROM SAFACT WHERE (TipoFac ='A' or tipofac='C') and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf AND CodSucu='$carupano'");
    $opera_del_dia_ver = mssql_result($opera_del_dia,0,"cuenta");

        // ventas del mes
    $venta_mes = mssql_query("
      DECLARE @fechai DATE
      DECLARE @fechaf DATE
      DECLARE @fecha_ini_mes DATE
      set @fechai = GETDATE()
      set @fechaf = GETDATE()
      set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
      select
      CONVERT(varchar, CAST(sum(case when TipoFac in ('A','c') then MtoTotal/FactorP when TipoFac in ('B','d') then (MtoTotal/FactorP) * -1 end) - sum(case when TipoFac in ('A','c') then (Descto1 + Descto2)/FactorP when TipoFac in ('B','d') then (Descto1 + Descto2)/FactorP *-1  end) AS money), 1) Total
      from
      SAFACT where TipoFac in ('A','b','c','d') and DATEADD(dd, 0, DATEDIFF(dd, 0, safact.fechae)) BETWEEN @fecha_ini_mes and @fechaf AND CodSucu='$carupano'");
    $ventas_mes_ver = mssql_result($venta_mes,0,"Total");

        // ventas del dia
    $venta_dia = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        SELECT CONVERT(varchar, CAST(sum(case when TipoFac in ('A','c') then MtoTotal/FactorP when TipoFac in ('B','d') then (MtoTotal/FactorP) * -1 end) - sum(case when TipoFac in ('A','c') then (Descto1 + Descto2)/FactorP when TipoFac in ('B','d') then (Descto1 + Descto2)/FactorP *-1  end) AS money), 1) Total
        from
        SAFACT where TipoFac in ('A','b','c','d') and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai  and @fechaf AND CodSucu='$carupano' ");
    $ventas_dia_ver = mssql_result($venta_dia,0,"Total");

    $venta_dia_D = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()

        select
        COALESCE(sum(MtoTotal/factorp),0) as total from SAFACT where TipoFac in ('B','D') and DATEADD(dd, 0, DATEDIFF(dd, 0, safact.fechae)) BETWEEN @fechai and @fechaf AND CodSucu='$carupano' ");
    $ventas_dia_ver_D = mssql_result($venta_dia_D,0,"Total");

        // top marcas
    $top10marcas = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        DECLARE @fecha_ini_mes DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
        SELECT top 10 marca, sum(montod)as montod 
        from
        (

            SELECT top 10 marca,
            SUM(COALESCE(((TotalItem+itemfact.mtotax)/NULLIF(itemfact.factorp,0)) * (CASE WHEN itemfact.TipoFac in ('A') THEN 1 ELSE -1 END), 0)) as montod
            FROM SAFACT fact
            INNER JOIN SAITEMFAC itemfact ON itemfact.NumeroD = fact.NumeroD
            INNER JOIN SAPROD prod ON prod.CodProd = itemfact.CodItem
            WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, fact.FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND itemfact.tipofac IN ('A') AND fact.CodSucu = '$carupano'
            AND fact.NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE x.CodSucu = '$carupano' and X.TipoFac = 'A' AND x.NumeroR IS NOT NULL AND CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE z.CodSucu = '$carupano' and Z.NumeroD = x.NumeroR AND Z.TipoFac in ('B')) AS BIGINT))
            GROUP BY marca
            ORDER BY montod DESC
            UNION ALL
            SELECT top 10 marca,
            SUM(COALESCE((TotalItem/NULLIF(itemfact.factorp,0)) * (CASE WHEN itemfact.TipoFac in ('C') THEN 1 ELSE -1 END), 0)) as montod
            FROM SAFACT fact
            INNER JOIN SAITEMFAC itemfact ON itemfact.NumeroD = fact.NumeroD
            INNER JOIN SAPROD prod ON prod.CodProd = itemfact.CodItem
            WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, fact.FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND itemfact.tipofac IN ('C') AND fact.CodSucu = '$carupano'
            AND fact.NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE x.CodSucu = '$carupano' and X.TipoFac = 'C' AND x.NumeroR IS NOT NULL AND CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE z.CodSucu = '$carupano' and Z.NumeroD = x.NumeroR AND Z.TipoFac in ('D')) AS BIGINT))
            GROUP BY marca
            ORDER BY montod DESC
        ) as T  GROUP BY marca  ORDER BY montod DESC");

        // top clientes
        //
    $top10clientes = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        DECLARE @fecha_ini_mes DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
        SELECT top 10 codclie, Descrip,  sum(case when TipoFac in ('A','C') then MtoTotal when TipoFac in ('B','D') then -MtoTotal else 0 end/Factorp) MontoD
        from SAFACT 

        WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, FechaE)) BETWEEN @fecha_ini_mes and @fechaf and TipoFac in ('A','B','C','D') and CodSucu = '$carupano'
        group by codclie, Descrip
        order by MontoD desc");

    $inv_valor = mssql_query("SELECT c.Descrip ,a.CodUbic, sum((a.existen  + (a.ExUnidad/d.CantEmpaq)) * b.costo_total) as total, sum((a.existen  + (a.ExUnidad/d.CantEmpaq)) * b.Profit1) as total_venta
        from saexis a inner join saprod_99 b on a.CodProd=b.CodProd inner join SADEPO as c on a.CodUbic=c.CodUbic inner join saprod as d on a.CodProd=d.CodProd 
        where  a.Existen > 0 or a.ExUnidad > 0  GROUP BY  c.Descrip, a.CodUbic  order by a.CodUbic");

    $saldo_bancos = mssql_query("SELECT id, Descrip, NroCta, Saldo from Bancos_App");

    $ventasxasesorfac = mssql_query("
     DECLARE @fechai DATE
     DECLARE @fechaf DATE
     DECLARE @fecha_ini_mes DATE
     set @fechai = GETDATE()
     set @fechaf = GETDATE()
     set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
     select SAFACT.CodVend, SAVEND.Descrip, sum(case when TipoFac = 'A' then MtoTotal when TipoFac = 'b' then -MtoTotal else 0 end/Factorp) MontoD from SAFACT inner join SAVEND ON SAFACT.CodVend = SAVEND.CodVend
     WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND tipofac IN ('A','B') AND SAFACT.CODSUCU='$carupano'
     group by SAFACT.CodVend, SAVEND.Descrip
     order by MontoD desc");


    $ventasxasesorne = mssql_query("
     DECLARE @fechai DATE
     DECLARE @fechaf DATE
     DECLARE @fecha_ini_mes DATE
     set @fechai = GETDATE()
     set @fechaf = GETDATE()
     set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
     select SAFACT.CodVend, SAVEND.Descrip, sum(case when TipoFac = 'C' then MtoTotal when TipoFac = 'D' then -MtoTotal else 0 end/Factorp) MontoD from SAFACT inner join SAVEND ON SAFACT.CodVend = SAVEND.CodVend
     WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND tipofac IN ('C','D')  and safact.CodSucu='$carupano'
     group by SAFACT.CodVend, SAVEND.Descrip
     order by MontoD desc");
    break;

    # =============================
    # === TODAS LAS SUCURSALEs ==== 
    # =============================
    default:
        // documentos por despachar
    $doc_x_despachar = mssql_query("SELECT count(numerod) AS CUENTA from SAFACT where numerod not in (select numeros from appfacturas_det) and (TipoFac ='A' or TipoFac ='C') and numeror is null");
    $pordespachar = mssql_result($doc_x_despachar,0,"CUENTA");

        // documentos por facturar
    $doc_x_facturar = mssql_query("SELECT count(numerod) AS CUENTA from SAFACT where TipoFac ='E' ");
    $porfacturar = mssql_result($doc_x_facturar,0,"CUENTA");

        // saldo pendiente por cobrar BS
    $cxcbs = mssql_query("SELECT COALESCE(SUM(Saldo),0) as saldo FROM saacxc WHERE saldo > 0 AND tipocxc='10'");
    $cxcsaldo = mssql_result($cxcbs,0,"saldo");

        // saldo pendiente por cobrar $
    $cxc_divisa = mssql_query("SELECT COALESCE(sum(saldo / Factor),0) as saldo from SAACXC WHERE saldo > 0 AND tipocxc='10'");
    $cxc_divisa_saldo = mssql_result($cxc_divisa,0,"saldo");

    $factor = mssql_query("SELECT top 1 factor from saconf where CodSucu='$maturin'");
    $factor1 = mssql_result($factor,0,"factor");

        // saldo pendiente por pagar bs
    $cxpbs = mssql_query("SELECT COALESCE(SUM(Saldo),0) as saldo FROM SAACXP WHERE saldo > 0 AND tipocxp='10'");
    $cxpsaldo = mssql_result($cxpbs,0,"saldo");

        // saldo pendiente por pagar $
    $cxpdivisa = mssql_query("SELECT sum(a.saldo / isnull(c.Factor,a.factorp)) as saldo from saacxp as a inner join saprov as b on a.codprov = b.codprov left  join SACOMP_01 as c on a.numerod=c.NumeroD and (case when a.TipoCxP= 10 then 'XX' else '' end) = (case when c.TipoCom in ('H','J') then 'XX' else '' end)  
        where  a.tipocxp='10' and a.saldo>0");
    $cxp_divisa_saldo = mssql_result($cxpdivisa,0,"saldo");

        // operaciones del dia
    $opera_del_dia = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        DECLARE @EDV VARCHAR(MAX)
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        SELECT count(numerod) as cuenta FROM SAFACT WHERE (TipoFac ='A' or tipofac='C') and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf");
    $opera_del_dia_ver = mssql_result($opera_del_dia,0,"cuenta");

        // ventas del mes
    $venta_mes = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        DECLARE @fecha_ini_mes DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
        select
        CONVERT(varchar, CAST(sum(case when TipoFac in ('A','C') then MtoTotal/FactorP when TipoFac in ('B','D') then (MtoTotal/FactorP) * -1 end) - sum(case when TipoFac in ('A','C') then (Descto1 + Descto2)/FactorP when TipoFac in ('B','D') then (Descto1 + Descto2)/FactorP *-1  end) AS money), 1) Total
        from
        SAFACT where TipoFac in ('A','b','c','d') and DATEADD(dd, 0, DATEDIFF(dd, 0, safact.fechae)) BETWEEN @fecha_ini_mes and @fechaf");
    $ventas_mes_ver = mssql_result($venta_mes,0,"Total");

        // ventas del dia
    $venta_dia = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()
        SELECT CONVERT(varchar, CAST(sum(case when TipoFac in ('A','c') then MtoTotal/FactorP when TipoFac in ('B','d') then (MtoTotal/FactorP) * -1 end) - sum(case when TipoFac in ('A','c') then (Descto1 + Descto2)/FactorP when TipoFac in ('B','d') then (Descto1 + Descto2)/FactorP *-1  end) AS money), 1) Total
        from
        SAFACT where TipoFac in ('A','b','c','d') and DATEADD(dd, 0, DATEDIFF(dd, 0, fechae)) BETWEEN @fechai and @fechaf  ");
    $ventas_dia_ver = mssql_result($venta_dia,0,"Total");

    $venta_dia_D = mssql_query("
        DECLARE @fechai DATE
        DECLARE @fechaf DATE
        set @fechai = GETDATE()
        set @fechaf = GETDATE()

        select
        COALESCE(sum(MtoTotal/factorp),0) as total from SAFACT where TipoFac in ('B','D') and DATEADD(dd, 0, DATEDIFF(dd, 0, safact.fechae)) BETWEEN @fechai and @fechaf ");
    $ventas_dia_ver_D = mssql_result($venta_dia_D,0,"Total");

        // top marcas
    $top10marcas = mssql_query("
       DECLARE @fechai DATE
       DECLARE @fechaf DATE
       DECLARE @fecha_ini_mes DATE
       set @fechai = GETDATE()
       set @fechaf = GETDATE()
       set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
       SELECT top 10 marca, sum(montod) as montod 
       from
       (

           SELECT top 10 marca,
           SUM(COALESCE(((TotalItem+itemfact.mtotax)/NULLIF(itemfact.factorp,0)) * (CASE WHEN itemfact.TipoFac in ('A') THEN 1 ELSE -1 END), 0)) as montod
           FROM SAFACT fact
           INNER JOIN SAITEMFAC itemfact ON itemfact.NumeroD = fact.NumeroD
           INNER JOIN SAPROD prod ON prod.CodProd = itemfact.CodItem
           WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, fact.FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND itemfact.tipofac IN ('A') 
           AND fact.NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac = 'A' AND x.NumeroR IS NOT NULL AND CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE Z.NumeroD = x.NumeroR AND Z.TipoFac in ('B')) AS BIGINT))
           GROUP BY marca
           ORDER BY montod DESC
           UNION ALL
           SELECT top 10 marca,
           SUM(COALESCE((TotalItem/NULLIF(itemfact.factorp,0)) * (CASE WHEN itemfact.TipoFac in ('C') THEN 1 ELSE -1 END), 0)) as montod
           FROM SAFACT fact
           INNER JOIN SAITEMFAC itemfact ON itemfact.NumeroD = fact.NumeroD
           INNER JOIN SAPROD prod ON prod.CodProd = itemfact.CodItem
           WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, fact.FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND itemfact.tipofac IN ('C') 
           AND fact.NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac = 'C' AND x.NumeroR IS NOT NULL AND CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE Z.NumeroD = x.NumeroR AND Z.TipoFac in ('D')) AS BIGINT))
           GROUP BY marca
           ORDER BY montod DESC
       ) as T  GROUP BY marca  ORDER BY montod DESC");

        // top clientes
        //
    $top10clientes = mssql_query("
      DECLARE @fechai DATE
      DECLARE @fechaf DATE
      DECLARE @fecha_ini_mes DATE
      set @fechai = GETDATE()
      set @fechaf = GETDATE()
      set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
      SELECT top 10 codclie, Descrip,  sum(case when TipoFac in ('A','C') then MtoTotal when TipoFac in ('B','D') then -MtoTotal else 0 end/Factorp) MontoD
      from SAFACT 

      WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, FechaE)) BETWEEN @fecha_ini_mes and @fechaf and TipoFac in ('A','B','C','D') and CodSucu = '$maturin'
      group by codclie, Descrip
      order by MontoD desc");

    $inv_valor = mssql_query("SELECT c.Descrip ,a.CodUbic, sum((a.existen  + (a.ExUnidad/d.CantEmpaq)) * b.costo_total) as total, sum((a.existen  + (a.ExUnidad/d.CantEmpaq)) * b.Profit1) as total_venta
        from saexis a inner join saprod_99 b on a.CodProd=b.CodProd inner join SADEPO as c on a.CodUbic=c.CodUbic inner join saprod as d on a.CodProd=d.CodProd 
        where  a.Existen > 0 or a.ExUnidad > 0  GROUP BY  c.Descrip, a.CodUbic  order by a.CodUbic");

    $saldo_bancos = mssql_query("SELECT id, Descrip, NroCta, Saldo from Bancos_App");


    $ventasxasesorfac = mssql_query("
      DECLARE @fechai DATE
      DECLARE @fechaf DATE
      DECLARE @fecha_ini_mes DATE
      set @fechai = GETDATE()
      set @fechaf = GETDATE()
      set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
      select SAFACT.CodVend, SAVEND.Descrip, sum(case when TipoFac = 'A' then MtoTotal when TipoFac = 'b' then -MtoTotal else 0 end/Factorp) MontoD from SAFACT inner join SAVEND ON SAFACT.CodVend = SAVEND.CodVend
      WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND tipofac IN ('A','B') 
      group by SAFACT.CodVend, SAVEND.Descrip
      order by MontoD desc");


    $ventasxasesorne = mssql_query("
       DECLARE @fechai DATE
       DECLARE @fechaf DATE
       DECLARE @fecha_ini_mes DATE
       set @fechai = GETDATE()
       set @fechaf = GETDATE()
       set @fecha_ini_mes = DATEADD(dd,-(DAY(GETDATE())-1),GETDATE())
       select SAFACT.CodVend, SAVEND.Descrip, sum(case when TipoFac = 'C' then MtoTotal when TipoFac = 'D' then -MtoTotal else 0 end/Factorp) MontoD from SAFACT inner join SAVEND ON SAFACT.CodVend = SAVEND.CodVend
       WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, FechaE)) BETWEEN @fecha_ini_mes and @fechaf AND tipofac IN ('C','D') 
       group by SAFACT.CodVend, SAVEND.Descrip
       order by MontoD desc");
}