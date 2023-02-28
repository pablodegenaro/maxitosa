<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
set_time_limit(0);
session_start();
ini_set('memory_limit', '512M');
if ($_SESSION['login']) {
    $rango = $_GET['rango'];
    $codsucu = $_GET['sucu'];
    $suma = 0;
    $fechas = "TODO";
    switch ($rango) {
        case 2:
        $query = mssql_query("SELECT (case when a.tipocxp = 10 and Document like 'NE%' then 'NE' when a.tipocxP = 10 and Document not like 'NE%' then 'FACT' else 'N/D' end) as TipoOpe, a.numerod as NroDoc, a.codprov as Codprov, isnull(b.Descrip,'No Existe') as Proveedor,
            CONVERT( VARCHAR ,a.fechae,103)as FechaEmi, 
            DATEDIFF(dd, a.FechaE,a.FechaV) as DiasCred,
            DATEDIFF(DD, a.fechav, getdate()) as DiasVenc,
            CONVERT( VARCHAR ,a.FechaV,103) as Vencimiento,
            a.saldo as SaldoPendBs, isnull(c.Factor,a.factorp) as factor,  a.saldo/isnull(c.Factor,a.factorp) as saldoPend$
            from saacxp a 
            inner join saprov b on a.codprov = b.codprov 
            inner join SASUCURSAL sucu on sucu.CodSucu=a.CodSucu
            left  join SACOMP_01 c on a.numerod=c.NumeroD and (case when a.TipoCxP= 10 then 'XX' else '' end) = (case when c.TipoCom in ('H','J') then 'XX' else '' end)  
            where (DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between DATEADD(day, -7, CONVERT( date ,GETDATE())) and DATEADD(day, -0, CONVERT( date ,GETDATE()))) 
            and sucu.CodSucu='$codsucu' and a.saldo>0 AND (a.tipocxP='10' OR a.tipocxP='20') 
            order by a.FechaE asc");
        $fechas = "DE 0 A 7 DIAS";
        break;
        case 3:
        $query = mssql_query("SELECT (case when a.tipocxp = 10 and Document like 'NE%' then 'NE' when a.tipocxp = 10 and Document not like 'NE%' then 'FACT' else 'N/D' end) as TipoOpe, a.numerod as NroDoc, a.codprov as Codprov, isnull(b.Descrip,'No Existe') as Proveedor,
            CONVERT( VARCHAR ,a.fechae,103)as FechaEmi, 
            DATEDIFF(dd, a.FechaE,a.FechaV) as DiasCred,
            DATEDIFF(DD, a.fechav, getdate()) as DiasVenc,
            CONVERT( VARCHAR ,a.FechaV,103) as Vencimiento, 
            a.saldo as SaldoPendBs, isnull(c.Factor,a.factorp) as factor,  a.saldo/isnull(c.Factor,a.factorp) as saldoPend$
            from saacxp a
            inner join saprov b on a.codprov = b.codprov 
            inner join SASUCURSAL sucu on sucu.CodSucu=a.CodSucu
            left  join SACOMP_01 c on a.numerod=c.NumeroD and (case when a.TipoCxP= 10 then 'XX' else '' end) = (case when c.TipoCom in ('H','J') then 'XX' else '' end)  
            where (DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between DATEADD(day, -15, CONVERT( date ,GETDATE())) and DATEADD(day, -8, CONVERT( date ,GETDATE()))) 
            and sucu.CodSucu='$codsucu' and a.saldo>0 AND (a.tipocxp='10' OR a.tipocxp='20') 
            order by a.FechaE asc");
        $fechas = "DE 8 A 15 DIAS";
        break;
        case 4:
        $query = mssql_query("SELECT (case when a.tipocxp = 10 and Document like 'NE%' then 'NE' when a.tipocxp = 10 and Document not like 'NE%' then 'FACT' else 'N/D' end) as TipoOpe, a.numerod as NroDoc, a.codprov as Codprov, isnull(b.Descrip,'No Existe') as Proveedor, 
            CONVERT( VARCHAR ,a.fechae,103)as FechaEmi, 
            DATEDIFF(dd, a.FechaE,a.FechaV) as DiasCred,
            DATEDIFF(DD, a.fechav, getdate()) as DiasVenc,
            CONVERT( VARCHAR ,a.FechaV,103) as Vencimiento,
            a.saldo as SaldoPendBs, isnull(c.Factor,a.factorp) as factor,  a.saldo/isnull(c.Factor,a.factorp) as saldoPend$
            from saacxp a
            inner join saprov b on a.codprov = b.codprov 
            inner join SASUCURSAL sucu on sucu.CodSucu=a.CodSucu
            left  join SACOMP_01 c on a.numerod=c.NumeroD and (case when a.TipoCxP= 10 then 'XX' else '' end) = (case when c.TipoCom in ('H','J') then 'XX' else '' end)  
            where (DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between DATEADD(day, -40, CONVERT( date ,GETDATE())) and DATEADD(day, -16, CONVERT( date ,GETDATE()))) 
            and sucu.CodSucu='$codsucu' and a.saldo>0 AND (a.tipocxp='10' OR a.tipocxp='20') 
            order by a.FechaE asc");
        $fechas = "DE 16 A 40 DIAS";
        break;
        case 5:
        $query = mssql_query("SELECT (case when a.tipocxp = 10 and Document like 'NE%' then 'NE' when a.tipocxp = 10 and Document not like 'NE%' then 'FACT' else 'N/D' end) as TipoOpe, a.numerod as NroDoc, a.Codprov as Codprov, isnull(b.Descrip,'No Existe') as Proveedor, 
            CONVERT( VARCHAR ,a.fechae,103)as FechaEmi, 
            DATEDIFF(dd, a.FechaE,a.FechaV) as DiasCred,
            DATEDIFF(DD, a.fechav, getdate()) as DiasVenc,
            CONVERT( VARCHAR ,a.FechaV,103) as Vencimiento, 
            a.saldo as SaldoPendBs, isnull(c.Factor,a.factorp) as factor,  a.saldo/isnull(c.Factor,a.factorp) as saldoPend$
            from saacxp a 
            inner join saprov b on a.codprov = b.codprov 
            inner join SASUCURSAL sucu on sucu.CodSucu=a.CodSucu
            left  join SACOMP_01 c on a.numerod=c.NumeroD and (case when a.TipoCxP= 10 then 'XX' else '' end) = (case when c.TipoCom in ('H','J') then 'XX' else '' end) 
            where (a.FechaE < DATEADD(day, -40, CONVERT( date ,GETDATE()))) 
            and sucu.CodSucu='$codsucu' and a.saldo>0 AND (a.tipocxp='10' OR a.tipocxp='20') 
            order by a.FechaE asc");
        $fechas = "MAYOR A 40 DIAS";
        break;
        case 6:
        $query = mssql_query("SELECT (case when a.tipocxp = 10 and Document like 'NE%' then 'NE' when a.tipocxp = 10 and Document not like 'NE%' then 'FACT' else 'N/D' end) as TipoOpe, a.numerod as NroDoc, a.codprov as Codprov, isnull(b.Descrip,'No Existe') as Proveedor, 
            CONVERT( VARCHAR ,a.fechae,103)as FechaEmi, 
            DATEDIFF(dd, a.FechaE,a.FechaV) as DiasCred,
            DATEDIFF(DD, a.fechav, getdate()) as DiasVenc,
            CONVERT( VARCHAR ,a.FechaV,103) as Vencimiento,
            a.saldo as SaldoPendBs, isnull(c.Factor,a.factorp) as factor,  a.saldo/isnull(c.Factor,a.factorp) as saldoPend$
            from saacxp a
            left join saprov b on a.codprov = b.codprov 
            inner join SASUCURSAL sucu on sucu.CodSucu=a.CodSucu
            left  join SACOMP_01 c on a.numerod=c.NumeroD and (case when a.TipoCxP= 10 then 'XX' else '' end) = (case when c.TipoCom in ('H','J') then 'XX' else '' end) 
            where sucu.CodSucu='$codsucu' and a.saldo>0 AND (a.tipocxp='10' OR a.tipocxp='20') 
            order by a.FechaE asc");
        $fechas = "TODAS LAS CUENTAS";
        break;
    }
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
            </div>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card card-saint">
                        <div class="card-header">
                            <script type="text/javascript">
                                function regresa(){
                                    window.location.href = "principal1.php?page=fact_pendientes_pagar&mod=1";
                                }
                            </script>
                            <h3 class="card-title">PENDIENTE POR PAGAR: <?php echo $fechas; ?></h3>&nbsp;&nbsp;&nbsp;
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
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php include "footer.php"; ?>
            <script src="Icons.js" type="text/javascript"></script>
            <?php
        } else {
            header('Location: index.php');
        }
    ?>