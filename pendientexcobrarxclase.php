<?php
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
$suma = 0;

$query = mssql_query("SELECT cxc.CodVend as Codigo, d.descrip as Vendedor,
            --Casco
            COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>=0 and DATEDIFF(DD, CXC.FechaE, GETDATE())<=60 and cl.Clase = 'Casco' and inss.Descrip ='LICORES') then CXC.Saldo/CXC.factor else 0 end),0) as 'Licores Casco Vencimiento -60',
            COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>60 and cl.Clase = 'Casco' and inss.Descrip = 'LICORES') then CXC.Saldo/CXC.factor else 0 end),0) as 'Licores Casco Vencimiento +60',
            COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>=0 and DATEDIFF(DD, CXC.FechaE, GETDATE())<=60 and cl.Clase = 'Casco' and inss.Descrip in ('MISCELANEOS','BEBIDAS NO-ALCOHOLICAS')) then CXC.Saldo/CXC.factor else 0 end),0) as 'Misce. Casco Vencimiento -60',
            COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>60 and cl.Clase = 'Casco' and inss.Descrip in ('MISCELANEOS','BEBIDAS NO-ALCOHOLICAS')) then CXC.Saldo/CXC.factor else 0 end),0) as 'Misce. Casco Vencimiento +60',
            --Sur
            COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>=0 and DATEDIFF(DD, CXC.FechaE, GETDATE())<=60 and cl.Clase = 'Sur' and inss.Descrip in ('LICORES')) then CXC.Saldo/CXC.factor else 0 end),0) as 'Licores Sur Vencimiento -60',
            COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>60 and cl.Clase = 'Sur' and inss.Descrip in ('LICORES')) then CXC.Saldo/cxc.factor else 0 end),0) as 'Licores Sur Vencimiento +60',
            COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>=0 and DATEDIFF(DD, CXC.FechaE, GETDATE())<=60 and cl.Clase = 'Sur' and inss.Descrip in ('MISCELANEOS','BEBIDAS NO-ALCOHOLICAS')) then CXC.Saldo/CXC.factor else 0 end),0) as 'Misce. Sur Vencimiento -60',
            COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>60 and cl.Clase = 'Sur' and inss.Descrip in ('MISCELANEOS','BEBIDAS NO-ALCOHOLICAS')) then CXC.Saldo/cxc.factor else 0 end),0) as 'Misce. Sur Vencimiento +60',
            --Norte
            COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>=0 and DATEDIFF(DD, CXC.FechaE, GETDATE())<=60 and cl.Clase = 'Norte' and inss.Descrip in ('LICORES')) then CXC.Saldo/CXC.factor else 0 end),0) as 'Licores Norte Vencimiento -60',
            COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>60 and cl.Clase = 'Norte' and inss.Descrip in ('LICORES')) then CXC.Saldo/cxc.factor else 0 end),0) as 'Licores Norte Vencimiento +60',
            COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>=0 and DATEDIFF(DD, CXC.FechaE, GETDATE())<=60 and cl.Clase = 'Norte' and inss.Descrip in ('MISCELANEOS','BEBIDAS NO-ALCOHOLICAS')) then CXC.Saldo/CXC.factor else 0 end),0) as 'Misce. Norte Vencimiento -60',
            COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>60 and cl.Clase = 'Norte' and inss.Descrip in ('MISCELANEOS','BEBIDAS NO-ALCOHOLICAS')) then CXC.Saldo/cxc.factor else 0 end),0) as 'Misce. Norte Vencimiento +60'
            from 
            SAACXC cxc 
            inner join SAFACT ft 
            on cxc.NumeroD = case when ft.TipoFac = 'A' then ft.NumeroD when ft.TipoFac = 'C' then 'NE'+ft.NumeroD else '' end  and case when ft.TipoFac in ('A','C') then '10' else '' end  = cxc.TipoCxc and cxc.Saldo != 0
            inner join (select substring(ORDERBYFIELD,0,6) inst, NumeroD, TipoFac from SAITEMFAC itff inner join SAPROD prd on itff.CodItem = prd.CodProd inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST
                group by substring(ORDERBYFIELD,0,6), NumeroD, TipoFac ) itf on ft.NumeroD = itf.NumeroD and ft.TipoFac = itf.TipoFac
            inner join SAINSTA inss on convert(int,itf.inst) = inss.CodInst
            inner join SACLIE CL ON CXC.CodClie = CL.CodClie
            inner join SAVEND d on cxc.CodVend = d.CodVend
            group by cxc.CodVend, d.descrip
            ");

            ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <!--  <h2 id="title_permisos">Ultima Activacion Clientes</h2> -->
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
                                    <li class="breadcrumb-item active">Pendientes por Cobrar por Clase</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="content">
                    <div class="col-md-12">
                        <div class="card card-saint">
                            <script type="text/javascript">
                                function regresa(){
                                    window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
                                }
                            </script>
                            <div class="card-header">
                                <h3 class="card-title">Pendientes por Cobrar por Clase</h3>
                            </div>
                            <div class="card-body" style="width:auto;">
                                <table id="example3" class="table table-lg table-bordered table-hover">
                                    <thead style="background-color: #00137f;color: white;">
                                        <tr>
                                            <?php
                                            for ($i = 0; $i < mssql_num_fields($query); ++$i){ ?>
                                                <th  align="center"><?php echo mssql_field_name($query, $i); ?></th><?php
                                            } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        for($j=0;$j<mssql_num_rows($query);$j++) {
                                        // $suma += mssql_result($query,$j,"SubTotal"); ?>
                                        <tr> <?php
                                        for ($i=0; $i<mssql_num_fields($query); $i++) {
                                            if (is_numeric(mssql_result($query,$j,mssql_field_name($query, $i))) and strstr(mssql_result($query,$j,mssql_field_name($query, $i)),'.')) {?>
                                                <td align="right">
                                                    <a target="_blank" href="principal1.php?page=pendientexcobrarxclase_ver&mod=1&rango=<?php echo $i; ?>&vend=<?php echo mssql_result($query,$j,mssql_field_name($query, 0)); ?>">
                                                        <?php echo rdecimal2(mssql_result($query,$j,mssql_field_name($query, $i))); ?>
                                                    </a>
                                                </td>
                                                <?php
                                            } else {?>
                                                <td  align="center"><?php echo utf8_encode(mssql_result($query,$j,mssql_field_name($query, $i)));?></td><?php
                                            }
                                        } ?>
                                        </tr> <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <br>
                            <?php //echo " TOTAL MONTO: ".number_format($suma, 2, ',', '.'); ?>
                            <br>
                        </div>
                    </div>
                </div>
            </section>      
        </div>
        <?php include "footer.php"; ?>
        <script src="Icons.js" type="text/javascript"></script>
