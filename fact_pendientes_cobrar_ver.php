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
        $query = mssql_query("SELECT (case when saacxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when saacxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, saacxc.numerod as NroDoc, saclie.CodClie as CodClie, saclie.Descrip as Cliente, 
            CONVERT( VARCHAR ,saacxc.fechae,103)as FechaEmi, 
            (case when saacxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
               case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = saacxc.numerod) else 'N/A' end) as FechaDesp,
            DATEDIFF(dd, SAACXC.FechaE,saacxc.FechaV) as DiasCred,
            DATEDIFF(DD, saacxc.fechav, getdate()) as DiasVenc,
            CONVERT( VARCHAR ,saacxc.FechaV,103) as Vencimiento,
            UPPER(saacxc.codvend) as Ruta, saacxc.saldo as SaldoPendBs,  saacxc.Factor as factor,  saacxc.saldo/saacxc.Factor as saldoPend$,
            (select supervisor from SAVEND_99 where CodVend = saacxc.CodVend) as Supervisor
            from saacxc inner join saclie on saacxc.codclie = saclie.codclie inner join SASUCURSAL sucu on sucu.CodSucu=saacxc.CodSucu
            where (DATEADD(dd, 0, DATEDIFF(dd, 0, SAACXC.FechaE)) between DATEADD(day, -7, CONVERT( date ,GETDATE())) and DATEADD(day, -0, CONVERT( date ,GETDATE()))) 
            and sucu.CodSucu='$codsucu' and saacxc.saldo>0 AND (saacxc.tipocxc='10' OR saacxc.tipocxc='20') 
            order by saacxc.FechaE asc");
        $fechas = "DE 0 A 7 DIAS";
        break;
        case 3:
        $query = mssql_query("SELECT (case when saacxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when saacxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, saacxc.numerod as NroDoc, saclie.CodClie as CodClie, saclie.Descrip as Cliente, 
            CONVERT( VARCHAR ,saacxc.fechae,103)as FechaEmi, 
            (case when saacxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
               case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = saacxc.numerod) else 'N/A' end) as FechaDesp,
            DATEDIFF(dd, SAACXC.FechaE,saacxc.FechaV) as DiasCred,
            -- DATEDIFF(DD,  (case when saacxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
            DATEDIFF(DD, saacxc.fechav, getdate()) as DiasVenc,
            --     case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = saacxc.numerod) else saacxc.fechae end), getdate())as DiasVenc,
            CONVERT( VARCHAR ,saacxc.FechaV,103) as Vencimiento,
            UPPER(saacxc.codvend) as Ruta, saacxc.saldo as SaldoPendBs, saacxc.Factor as factor,  saacxc.saldo/saacxc.Factor as saldoPend$,
            (select supervisor from SAVEND_99 where CodVend = saacxc.CodVend) as Supervisor
            from saacxc inner join saclie on saacxc.codclie = saclie.codclie inner join SASUCURSAL sucu on sucu.CodSucu=saacxc.CodSucu
            where (DATEADD(dd, 0, DATEDIFF(dd, 0, SAACXC.FechaE)) between DATEADD(day, -15, CONVERT( date ,GETDATE())) and DATEADD(day, -8, CONVERT( date ,GETDATE()))) 
            and sucu.CodSucu='$codsucu' and saacxc.saldo>0 AND (saacxc.tipocxc='10' OR saacxc.tipocxc='20') 
            order by saacxc.FechaE asc");
        $fechas = "DE 8 A 15 DIAS";
        break;
        case 4:
        $query = mssql_query("SELECT (case when saacxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when saacxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, saacxc.numerod as NroDoc, saclie.CodClie as CodClie, saclie.Descrip as Cliente, 
            CONVERT( VARCHAR ,saacxc.fechae,103)as FechaEmi, 
            (case when saacxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
               case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = saacxc.numerod) else 'N/A' end) as FechaDesp,
            DATEDIFF(dd, SAACXC.FechaE,saacxc.FechaV) as DiasCred,
            DATEDIFF(DD, saacxc.fechav, getdate()) as DiasVenc,
            CONVERT( VARCHAR ,saacxc.FechaV,103) as Vencimiento,
            UPPER(saacxc.codvend) as Ruta, saacxc.saldo as SaldoPendBs, saacxc.Factor as factor,  saacxc.saldo/saacxc.Factor as saldoPend$,
            (select supervisor from SAVEND_99 where CodVend = saacxc.CodVend) as Supervisor
            from saacxc inner join saclie on saacxc.codclie = saclie.codclie inner join SASUCURSAL sucu on sucu.CodSucu=saacxc.CodSucu
            where (DATEADD(dd, 0, DATEDIFF(dd, 0, SAACXC.FechaE)) between DATEADD(day, -40, CONVERT( date ,GETDATE())) and DATEADD(day, -16, CONVERT( date ,GETDATE()))) 
            and sucu.CodSucu='$codsucu' and saacxc.saldo>0 AND (saacxc.tipocxc='10' OR saacxc.tipocxc='20') 
            order by saacxc.FechaE asc");
        $fechas = "DE 16 A 40 DIAS";
        break;
        case 5:
        $query = mssql_query("SELECT (case when saacxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when saacxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, saacxc.numerod as NroDoc, saclie.CodClie as CodClie, saclie.Descrip as Cliente, 
            CONVERT( VARCHAR ,saacxc.fechae,103)as FechaEmi, 
            (case when saacxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
               case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = saacxc.numerod) else 'N/A' end) as FechaDesp,
            DATEDIFF(dd, SAACXC.FechaE,saacxc.FechaV) as DiasCred,
            DATEDIFF(DD, saacxc.fechav, getdate()) as DiasVenc,
            CONVERT( VARCHAR ,saacxc.FechaV,103) as Vencimiento,
            UPPER(saacxc.codvend) as Ruta, saacxc.saldo as SaldoPendBs, saacxc.Factor as factor,  saacxc.saldo/saacxc.Factor as saldoPend$,
            (select supervisor from SAVEND_99 where CodVend = saacxc.CodVend) as Supervisor
            from saacxc inner join saclie on saacxc.codclie = saclie.codclie inner join SASUCURSAL sucu on sucu.CodSucu=saacxc.CodSucu
            where (SAACXC.FechaE < DATEADD(day, -40, CONVERT( date ,GETDATE()))) 
            and sucu.CodSucu='$codsucu' and saacxc.saldo>0 AND (saacxc.tipocxc='10' OR saacxc.tipocxc='20') 
            order by saacxc.FechaE asc");
        $fechas = "MAYOR A 40 DIAS";
        break;
        case 6:
        $query = mssql_query("SELECT (case when saacxc.tipocxc = 10 and Document like 'Nota%' then 'NE' when saacxc.tipocxc = 10 and Document not like 'Nota%' then 'FACT' else 'N/D' end) as TipoOpe, saacxc.numerod as NroDoc, saclie.CodClie as CodClie, saclie.Descrip as Cliente, 
            CONVERT( VARCHAR ,saacxc.fechae,103)as FechaEmi, 
            (case when saacxc.tipocxc = 10 then (select top 1 CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
             case when appfacturas_det.TipoFac = 'C' then 'NE' else '' end + appfacturas_det.numeros = saacxc.numerod) else 'N/A' end) as FechaDesp,
            DATEDIFF(dd, SAACXC.FechaE,saacxc.FechaV) as DiasCred,
            DATEDIFF(DD, saacxc.fechav, getdate()) as DiasVenc,
            CONVERT( VARCHAR ,saacxc.FechaV,103) as Vencimiento,
            UPPER(saacxc.codvend) as Ruta, saacxc.saldo as SaldoPendBs, saacxc.Factor as factor,  saacxc.saldo/saacxc.Factor as saldoPend$,
            (select supervisor from SAVEND_99 where CodVend = saacxc.CodVend) as Supervisor
            from saacxc left join saclie on saacxc.codclie = saclie.codclie inner join SASUCURSAL sucu on sucu.CodSucu=saacxc.CodSucu
            where sucu.CodSucu='$codsucu' and saacxc.saldo>0 AND (saacxc.tipocxc='10' OR saacxc.tipocxc='20') 
            order by saacxc.FechaE asc");
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
                                    window.location.href = "principal1.php?page=fact_pendientes_cobrar&mod=1";
                                }
                            </script>
                            <h3 class="card-title">PENDIENTE POR COBRAR: <?php echo $fechas; ?></h3>&nbsp;&nbsp;&nbsp;
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