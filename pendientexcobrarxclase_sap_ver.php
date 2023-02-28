<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
set_time_limit(0);
session_start();
ini_set('memory_limit', '512M');
$rango = $_GET['rango'];
$codsucu = $_GET['sucu'];
$codvend = $_GET['vend'];
$suma = 0;
$fechas = "TODO";
if ($_SESSION['login']) {

    switch ($rango) {
        case 2:
        $query = mssql_query("SELECT 
         cxc.numerod as NroDoc, 
         CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
         DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
            appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
         CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
         UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  coalesce(cxc.saldo/nullif(cxc.Factor, 0), 0) as saldoPend$,
         (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                    --, inss.Descrip
                    from 
                    SAACXC cxc 
                    
                    inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE  cxc.CodVend='$codvend' AND cxc.Saldo != 0 and  cxc.NumeroD not in (select numerod from safact) and 
                    (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -60, CONVERT( date ,GETDATE())) and DATEADD(day, -0, CONVERT( date ,GETDATE()))) and cxc.numerod not like 'NE%'
                    order by FechaEmi asc");
        $fechas = "SAP Vencimiento -60";
        break;
        case 3:
        $query = mssql_query("SELECT 
         cxc.numerod as NroDoc, 
         CONVERT( VARCHAR ,cxc.fechae,103)as FechaEmi, 
         DATEDIFF(DD,  (case when cxc.tipocxc = 10 then (select top 1 CONVERT( date ,fechae) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
            appfacturas_det.numeros = cxc.numerod) else cxc.fechae end), getdate())as DiasTrans,
         CONVERT( VARCHAR ,cxc.FechaV,103) as Vencimiento,
         UPPER(cxc.codvend) as Ruta, cxc.saldo as SaldoPendBs,  cxc.Factor as factor,  coalesce(cxc.saldo/nullif(cxc.Factor, 0), 0) as saldoPend$,
         (select supervisor from SAVEND_99 where CodVend = cxc.CodVend) as Supervisor
                        --, inss.Descrip
                        from 
                        SAACXC cxc 
                        
                        inner join SACLIE CL ON CXC.CodClie = CL.CodClie WHERE  cxc.CodVend='$codvend' AND cxc.Saldo != 0 and   cxc.NumeroD not in (select numerod from safact) and 
                        (DATEADD(dd, 0, DATEDIFF(dd, 0, CXC.FechaE)) between DATEADD(day, -50000, CONVERT( date ,GETDATE())) and DATEADD(day, -60, CONVERT( date ,GETDATE()))) and cxc.numerod not like 'NE%'
                        order by FechaEmi asc");
        $fechas = "SAP Vencimiento +60";
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
                                    window.location.href = "principal1.php?page=pendientexcobrarxclase_sap&mod=1";
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