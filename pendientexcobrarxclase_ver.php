<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
set_time_limit(0);
session_start();
ini_set('memory_limit', '512M');
if ($_SESSION['login']) {
    $rango = $_GET['rango'];
    $codsucu = $_GET['sucu'];
    $codvend = $_GET['vend'];
    $suma = 0;
    $fechas = "TODO";

    switch ($rango) {
        case 2:
            $query = mssql_query("SELECT cl.CodClie, cl.Descrip,--CXC.CodVend, CL.Descrip, *, 
                (case when cxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when cxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, cxc.numerod as NroDoc, 
                CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
                (case when cxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                   case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else 'N/A' end) as FechaDesp,

                DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                    case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
                CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
                UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  cxc.saldo/cxc.Factor as saldoPend$,
                (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                    --, inss.Descrip
                    from 
                    SAACXC cxc 
                    inner join SAFACT ft on cxc.NumeroD = case when ft.TipoFac = 'A' then ft.NumeroD when ft.TipoFac = 'C' then 'NE'+ft.NumeroD else '' end  and case when ft.TipoFac in ('A','C') then '10' else '' end  = cxc.TipoCxc and cxc.Saldo != 0 AND ft.CodVend='$codvend'
                    inner join (select substring(ORDERBYFIELD,0,6) inst, NumeroD, TipoFac from SAITEMFAC itff inner join SAPROD prd on itff.CodItem = prd.CodProd inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST group by substring(ORDERBYFIELD,0,6), NumeroD, TipoFac ) itf on ft.NumeroD = itf.NumeroD and ft.TipoFac = itf.TipoFac
                    inner join SAINSTA inss on convert(int,itf.inst) = inss.CodInst
                    inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE cl.Clase= 'Casco' AND inss.Descrip in ('LICORES')
                    AND (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -60, CONVERT( date ,GETDATE())) and DATEADD(day, -0, CONVERT( date ,GETDATE()))) 
                    order by FechaEmi asc");
            $fechas = "Licores Casco Vencimiento -60";
            break;
            case 3:
            $query = mssql_query("SELECT cl.CodClie, cl.Descrip,--CXC.CodVend, CL.Descrip, *,
                (case when cxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when cxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, cxc.numerod as NroDoc, 
                CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
                (case when cxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                   case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else 'N/A' end) as FechaDesp,

                DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                    case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
                CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
                UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  cxc.saldo/cxc.Factor as saldoPend$,
                (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                        --, inss.Descrip
                        from 
                        SAACXC cxc 
                        inner join SAFACT ft on cxc.NumeroD = case when ft.TipoFac = 'A' then ft.NumeroD when ft.TipoFac = 'C' then 'NE'+ft.NumeroD else '' end  and case when ft.TipoFac in ('A','C') then '10' else '' end  = cxc.TipoCxc and cxc.Saldo != 0 AND ft.CodVend='$codvend'
                        inner join (select substring(ORDERBYFIELD,0,6) inst, NumeroD, TipoFac from SAITEMFAC itff inner join SAPROD prd on itff.CodItem = prd.CodProd inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST group by substring(ORDERBYFIELD,0,6), NumeroD, TipoFac ) itf on ft.NumeroD = itf.NumeroD and ft.TipoFac = itf.TipoFac
                        inner join SAINSTA inss on convert(int,itf.inst) = inss.CodInst
                        inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE cl.Clase= 'Casco' AND inss.Descrip in ('LICORES')
                        AND (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -50000, CONVERT( date ,GETDATE())) and DATEADD(day, -60, CONVERT( date ,GETDATE()))) 
                        order by FechaEmi asc");
            $fechas = "Licores Casco Vencimiento +60";
            break;
            case 4:
            $query = mssql_query("SELECT cl.CodClie, cl.Descrip,--CXC.CodVend, CL.Descrip, *,
                (case when cxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when cxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, cxc.numerod as NroDoc, 
                CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
                (case when cxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                   case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else 'N/A' end) as FechaDesp,

                DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                    case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
                CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
                UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  cxc.saldo/cxc.Factor as saldoPend$,
                (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                    --, inss.Descrip
                    from 
                    SAACXC cxc 
                    inner join SAFACT ft on cxc.NumeroD = case when ft.TipoFac = 'A' then ft.NumeroD when ft.TipoFac = 'C' then 'NE'+ft.NumeroD else '' end  and case when ft.TipoFac in ('A','C') then '10' else '' end  = cxc.TipoCxc and cxc.Saldo != 0 AND ft.CodVend='$codvend'
                    inner join (select substring(ORDERBYFIELD,0,6) inst, NumeroD, TipoFac from SAITEMFAC itff inner join SAPROD prd on itff.CodItem = prd.CodProd inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST group by substring(ORDERBYFIELD,0,6), NumeroD, TipoFac ) itf on ft.NumeroD = itf.NumeroD and ft.TipoFac = itf.TipoFac
                    inner join SAINSTA inss on convert(int,itf.inst) = inss.CodInst
                    inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE cl.Clase= 'Casco' AND inss.Descrip in ('MISCELANEOS','BEBIDAS NO-ALCOHOLICAS')
                    AND (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -60, CONVERT( date ,GETDATE())) and DATEADD(day, -0, CONVERT( date ,GETDATE()))) 
                    order by FechaEmi asc");
            $fechas = "Miscelaneos Casco Vencimiento -60";
            break;
            case 5:
            $query = mssql_query("SELECT cl.CodClie, cl.Descrip,--CXC.CodVend, CL.Descrip, *,
                (case when cxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when cxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, cxc.numerod as NroDoc, 
                CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
                (case when cxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                   case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else 'N/A' end) as FechaDesp,

                DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                    case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
                CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
                UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  cxc.saldo/cxc.Factor as saldoPend$,
                (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                        --, inss.Descrip
                        from 
                        SAACXC cxc 
                        inner join SAFACT ft on cxc.NumeroD = case when ft.TipoFac = 'A' then ft.NumeroD when ft.TipoFac = 'C' then 'NE'+ft.NumeroD else '' end  and case when ft.TipoFac in ('A','C') then '10' else '' end  = cxc.TipoCxc and cxc.Saldo != 0 AND ft.CodVend='$codvend'
                        inner join (select substring(ORDERBYFIELD,0,6) inst, NumeroD, TipoFac from SAITEMFAC itff inner join SAPROD prd on itff.CodItem = prd.CodProd inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST group by substring(ORDERBYFIELD,0,6), NumeroD, TipoFac ) itf on ft.NumeroD = itf.NumeroD and ft.TipoFac = itf.TipoFac
                        inner join SAINSTA inss on convert(int,itf.inst) = inss.CodInst
                        inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE cl.Clase= 'Casco' AND inss.Descrip in ('MISCELANEOS','BEBIDAS NO-ALCOHOLICAS')
                        AND (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -50000, CONVERT( date ,GETDATE())) and DATEADD(day, -60, CONVERT( date ,GETDATE()))) 
                        order by FechaEmi asc");
            $fechas = "Miscelaneos Casco Vencimiento +60";
            break;
            case 6:
            $query = mssql_query("SELECT cl.CodClie, cl.Descrip,--CXC.CodVend, CL.Descrip, *,
                (case when cxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when cxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, cxc.numerod as NroDoc, 
                CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
                (case when cxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                   case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else 'N/A' end) as FechaDesp,

                DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                    case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
                CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
                UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  cxc.saldo/cxc.Factor as saldoPend$,
                (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                    --, inss.Descrip
                    from 
                    SAACXC cxc 
                    inner join SAFACT ft on cxc.NumeroD = case when ft.TipoFac = 'A' then ft.NumeroD when ft.TipoFac = 'C' then 'NE'+ft.NumeroD else '' end  and case when ft.TipoFac in ('A','C') then '10' else '' end  = cxc.TipoCxc and cxc.Saldo != 0 AND ft.CodVend='$codvend'
                    inner join (select substring(ORDERBYFIELD,0,6) inst, NumeroD, TipoFac from SAITEMFAC itff inner join SAPROD prd on itff.CodItem = prd.CodProd inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST group by substring(ORDERBYFIELD,0,6), NumeroD, TipoFac ) itf on ft.NumeroD = itf.NumeroD and ft.TipoFac = itf.TipoFac
                    inner join SAINSTA inss on convert(int,itf.inst) = inss.CodInst
                    inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE cl.Clase= 'Sur' AND inss.Descrip in ('LICORES')
                    AND (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -60, CONVERT( date ,GETDATE())) and DATEADD(day, -0, CONVERT( date ,GETDATE()))) 
                    order by FechaEmi asc");
            $fechas = "Licores Sur Vencimiento -60";
            break;
            case 7:
            $query = mssql_query("SELECT cl.CodClie, cl.Descrip,--CXC.CodVend, CL.Descrip, *,
                (case when cxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when cxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, cxc.numerod as NroDoc, 
                CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
                (case when cxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                   case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else 'N/A' end) as FechaDesp,

                DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                    case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
                CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
                UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  cxc.saldo/cxc.Factor as saldoPend$,
                (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                        --, inss.Descrip
                        from 
                        SAACXC cxc 
                        inner join SAFACT ft on cxc.NumeroD = case when ft.TipoFac = 'A' then ft.NumeroD when ft.TipoFac = 'C' then 'NE'+ft.NumeroD else '' end  and case when ft.TipoFac in ('A','C') then '10' else '' end  = cxc.TipoCxc and cxc.Saldo != 0 AND ft.CodVend='$codvend'
                        inner join (select substring(ORDERBYFIELD,0,6) inst, NumeroD, TipoFac from SAITEMFAC itff inner join SAPROD prd on itff.CodItem = prd.CodProd inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST group by substring(ORDERBYFIELD,0,6), NumeroD, TipoFac ) itf on ft.NumeroD = itf.NumeroD and ft.TipoFac = itf.TipoFac
                        inner join SAINSTA inss on convert(int,itf.inst) = inss.CodInst
                        inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE cl.Clase= 'Sur' AND inss.Descrip in ('LICORES')
                        AND (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -50000, CONVERT( date ,GETDATE())) and DATEADD(day, -60, CONVERT( date ,GETDATE()))) 
                        order by FechaEmi asc");
            $fechas = "Licores Sur Vencimiento +60";
            break;
            case 8:
            $query = mssql_query("SELECT cl.CodClie, cl.Descrip,--CXC.CodVend, CL.Descrip, *,
                (case when cxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when cxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, cxc.numerod as NroDoc, 
                CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
                (case when cxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                   case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else 'N/A' end) as FechaDesp,

                DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                    case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
                CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
                UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  cxc.saldo/cxc.Factor as saldoPend$,
                (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                    --, inss.Descrip
                    from 
                    SAACXC cxc 
                    inner join SAFACT ft on cxc.NumeroD = case when ft.TipoFac = 'A' then ft.NumeroD when ft.TipoFac = 'C' then 'NE'+ft.NumeroD else '' end  and case when ft.TipoFac in ('A','C') then '10' else '' end  = cxc.TipoCxc and cxc.Saldo != 0 AND ft.CodVend='$codvend'
                    inner join (select substring(ORDERBYFIELD,0,6) inst, NumeroD, TipoFac from SAITEMFAC itff inner join SAPROD prd on itff.CodItem = prd.CodProd inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST group by substring(ORDERBYFIELD,0,6), NumeroD, TipoFac ) itf on ft.NumeroD = itf.NumeroD and ft.TipoFac = itf.TipoFac
                    inner join SAINSTA inss on convert(int,itf.inst) = inss.CodInst
                    inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE cl.Clase= 'Sur' AND inss.Descrip in ('MISCELANEOS','BEBIDAS NO-ALCOHOLICAS')
                    AND (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -60, CONVERT( date ,GETDATE())) and DATEADD(day, -0, CONVERT( date ,GETDATE()))) 
                    order by FechaEmi asc");
            $fechas = "Miscelaneos Sur Vencimiento -60";
            break;
            case 9:
            $query = mssql_query("SELECT cl.CodClie, cl.Descrip,--CXC.CodVend, CL.Descrip, *,
                (case when cxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when cxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, cxc.numerod as NroDoc, 
                CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
                (case when cxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                   case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else 'N/A' end) as FechaDesp,

                DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                    case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
                CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
                UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  cxc.saldo/cxc.Factor as saldoPend$,
                (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                        --, inss.Descrip
                        from 
                        SAACXC cxc 
                        inner join SAFACT ft on cxc.NumeroD = case when ft.TipoFac = 'A' then ft.NumeroD when ft.TipoFac = 'C' then 'NE'+ft.NumeroD else '' end  and case when ft.TipoFac in ('A','C') then '10' else '' end  = cxc.TipoCxc and cxc.Saldo != 0 AND ft.CodVend='$codvend'
                        inner join (select substring(ORDERBYFIELD,0,6) inst, NumeroD, TipoFac from SAITEMFAC itff inner join SAPROD prd on itff.CodItem = prd.CodProd inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST group by substring(ORDERBYFIELD,0,6), NumeroD, TipoFac ) itf on ft.NumeroD = itf.NumeroD and ft.TipoFac = itf.TipoFac
                        inner join SAINSTA inss on convert(int,itf.inst) = inss.CodInst
                        inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE cl.Clase= 'Sur' AND inss.Descrip in ('MISCELANEOS','BEBIDAS NO-ALCOHOLICAS')
                        AND (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -50000, CONVERT( date ,GETDATE())) and DATEADD(day, -60, CONVERT( date ,GETDATE()))) 
                        order by FechaEmi asc");
            $fechas = "Miscelaneos Sur Vencimiento +60";
            break;


            case 10:
            $query = mssql_query("SELECT cl.CodClie, cl.Descrip,--CXC.CodVend, CL.Descrip, *,
                (case when cxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when cxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, cxc.numerod as NroDoc, 
                CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
                (case when cxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                   case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else 'N/A' end) as FechaDesp,

                DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                    case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
                CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
                UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  cxc.saldo/cxc.Factor as saldoPend$,
                (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                    --, inss.Descrip
                    from 
                    SAACXC cxc 
                    inner join SAFACT ft on cxc.NumeroD = case when ft.TipoFac = 'A' then ft.NumeroD when ft.TipoFac = 'C' then 'NE'+ft.NumeroD else '' end  and case when ft.TipoFac in ('A','C') then '10' else '' end  = cxc.TipoCxc and cxc.Saldo != 0 AND ft.CodVend='$codvend'
                    inner join (select substring(ORDERBYFIELD,0,6) inst, NumeroD, TipoFac from SAITEMFAC itff inner join SAPROD prd on itff.CodItem = prd.CodProd inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST group by substring(ORDERBYFIELD,0,6), NumeroD, TipoFac ) itf on ft.NumeroD = itf.NumeroD and ft.TipoFac = itf.TipoFac
                    inner join SAINSTA inss on convert(int,itf.inst) = inss.CodInst
                    inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE cl.Clase= 'Norte' AND inss.Descrip in ('LICORES')
                    AND (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -60, CONVERT( date ,GETDATE())) and DATEADD(day, -0, CONVERT( date ,GETDATE()))) 
                    order by FechaEmi asc");
            $fechas = "Licores Norte Vencimiento -60";
            break;
            case 11:
            $query = mssql_query("SELECT cl.CodClie, cl.Descrip,--CXC.CodVend, CL.Descrip, *,
                (case when cxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when cxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, cxc.numerod as NroDoc, 
                CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
                (case when cxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                   case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else 'N/A' end) as FechaDesp,

                DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                    case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
                CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
                UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  cxc.saldo/cxc.Factor as saldoPend$,
                (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                        --, inss.Descrip
                        from 
                        SAACXC cxc 
                        inner join SAFACT ft on cxc.NumeroD = case when ft.TipoFac = 'A' then ft.NumeroD when ft.TipoFac = 'C' then 'NE'+ft.NumeroD else '' end  and case when ft.TipoFac in ('A','C') then '10' else '' end  = cxc.TipoCxc and cxc.Saldo != 0 AND ft.CodVend='$codvend'
                        inner join (select substring(ORDERBYFIELD,0,6) inst, NumeroD, TipoFac from SAITEMFAC itff inner join SAPROD prd on itff.CodItem = prd.CodProd inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST group by substring(ORDERBYFIELD,0,6), NumeroD, TipoFac ) itf on ft.NumeroD = itf.NumeroD and ft.TipoFac = itf.TipoFac
                        inner join SAINSTA inss on convert(int,itf.inst) = inss.CodInst
                        inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE cl.Clase= 'Norte' AND inss.Descrip in ('LICORES')
                        AND (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -50000, CONVERT( date ,GETDATE())) and DATEADD(day, -60, CONVERT( date ,GETDATE()))) 
                        order by FechaEmi asc");
            $fechas = "Licores Norte Vencimiento +60";
            break;
            case 12:
            $query = mssql_query("SELECT cl.CodClie, cl.Descrip,--CXC.CodVend, CL.Descrip, *,
                (case when cxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when cxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, cxc.numerod as NroDoc, 
                CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
                (case when cxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                   case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else 'N/A' end) as FechaDesp,

                DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                    case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
                CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
                UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  cxc.saldo/cxc.Factor as saldoPend$,
                (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                    --, inss.Descrip
                    from 
                    SAACXC cxc 
                    inner join SAFACT ft on cxc.NumeroD = case when ft.TipoFac = 'A' then ft.NumeroD when ft.TipoFac = 'C' then 'NE'+ft.NumeroD else '' end  and case when ft.TipoFac in ('A','C') then '10' else '' end  = cxc.TipoCxc and cxc.Saldo != 0 AND ft.CodVend='$codvend'
                    inner join (select substring(ORDERBYFIELD,0,6) inst, NumeroD, TipoFac from SAITEMFAC itff inner join SAPROD prd on itff.CodItem = prd.CodProd inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST group by substring(ORDERBYFIELD,0,6), NumeroD, TipoFac ) itf on ft.NumeroD = itf.NumeroD and ft.TipoFac = itf.TipoFac
                    inner join SAINSTA inss on convert(int,itf.inst) = inss.CodInst
                    inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE cl.Clase= 'Norte' AND inss.Descrip in ('MISCELANEOS','BEBIDAS NO-ALCOHOLICAS')
                    AND (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -60, CONVERT( date ,GETDATE())) and DATEADD(day, -0, CONVERT( date ,GETDATE()))) 
                    order by FechaEmi asc");
            $fechas = "Miscelaneos Norte Vencimiento -60";
            break;
            case 13:
            $query = mssql_query("SELECT cl.CodClie, cl.Descrip,--CXC.CodVend, CL.Descrip, *,
                (case when cxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when cxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, cxc.numerod as NroDoc, 
                CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
                (case when cxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                   case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else 'N/A' end) as FechaDesp,

                DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
                    case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
                CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
                UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  cxc.saldo/cxc.Factor as saldoPend$,
                (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                        --, inss.Descrip
                        from 
                        SAACXC cxc 
                        inner join SAFACT ft on cxc.NumeroD = case when ft.TipoFac = 'A' then ft.NumeroD when ft.TipoFac = 'C' then 'NE'+ft.NumeroD else '' end  and case when ft.TipoFac in ('A','C') then '10' else '' end  = cxc.TipoCxc and cxc.Saldo != 0 AND ft.CodVend='$codvend'
                        inner join (select substring(ORDERBYFIELD,0,6) inst, NumeroD, TipoFac from SAITEMFAC itff inner join SAPROD prd on itff.CodItem = prd.CodProd inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST group by substring(ORDERBYFIELD,0,6), NumeroD, TipoFac ) itf on ft.NumeroD = itf.NumeroD and ft.TipoFac = itf.TipoFac
                        inner join SAINSTA inss on convert(int,itf.inst) = inss.CodInst
                        inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE cl.Clase= 'Norte' AND inss.Descrip in ('MISCELANEOS','BEBIDAS NO-ALCOHOLICAS')
                        AND (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -50000, CONVERT( date ,GETDATE())) and DATEADD(day, -60, CONVERT( date ,GETDATE()))) 
                        order by FechaEmi asc");
            $fechas = "Miscelaneos Norte Vencimiento +60";
            break;
        }
        ?>
        <div class="content-wrapper">
            <!-- BOX DE LA MIGA DE PAN -->
            <section class="content-header">
                <div class="container-fluid">

                </div>
            </section>
            <!-- BOX DEL CONTENIDO DE LA VISTA FORMULARIO Y TABLA -->
            <section class="content">
                <!-- <div class="container"> -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-saint">
                                <div class="card-header">
                                    <script type="text/javascript">
                                        function regresa(){
                                            window.location.href = "principal1.php?page=pendientexcobrarxclase&mod=1";
                                        }
                                    </script>
                                    <h3 class="card-title">PENDIENTE POR COBRAR POR CLASE: <?php echo $fechas; ?></h3>&nbsp;&nbsp;&nbsp;
                                    <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
                                </div>
                                <div class="card-body">
                                    <!--  <table id="example2" class="table table-bordered table-hover"> -->
                                        <table id="example1" class="table table-sm table-bordered table-striped">
                                            <thead style="background-color: #00137f;color: white;">
                                                <tr>
                                                    <?php
                                                    for ($i = 0; $i < mssql_num_fields($query); ++$i){ ?>
                                                        <th><?php echo mssql_field_name($query, $i); ?></th> <?php
                                                    } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                for($j=0;$j<mssql_num_rows($query);$j++) {
                                                    $suma += mssql_result($query,$j,"saldoPend$") ?>
                                                    <tr>
                                                        <?php
                                                        for($i=0;$i<mssql_num_fields($query);$i++){ ?>
                                                            <td>
                                                                <?php
                                                                if(is_numeric(mssql_result($query,$j,mssql_field_name($query, $i))) and strstr(mssql_result($query,$j,mssql_field_name($query, $i)),'.')) {
                                                                    echo rdecimal2(mssql_result($query,$j,mssql_field_name($query, $i)));
                                                                }else{
                                                                    echo utf8_encode(mssql_result($query,$j,mssql_field_name($query, $i)));
                                                                }
                                                                ?>
                                                                </td> <?php
                                                            } ?>
                                                        </tr>
                                                        <?php
                                                    } ?>
                                                </tbody>
                                            </table>
                                            <br>
                                            <?php echo " TOTAL MONTO: ".rdecimal2($suma); ?>
                                <!-- <div align="center"><a href="fact_pendientes_cobrar_excel.php?&rango=<?php //echo $rango; ?>&sucu=<?php //echo $codsucu; ?>" >
                                        <img src="images/excel.jpeg" width="19" height="18" border="0" /> Exportar a Excel</a>&nbsp;&nbsp;
                                    </div> -->
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div><!-- 
                    </div> -->
                </section>
            </div>
            <?php include "footer.php"; ?>
            <script src="Icons.js" type="text/javascript"></script>
            <?php
        } else {
            header('Location: index.php');
        }
    ?>