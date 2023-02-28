<?php
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
$suma = 0;

$query = mssql_query("SELECT cxc.CodVend as Codigo, d.descrip as Vendedor,
    COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>=0 and DATEDIFF(DD, CXC.FechaE, GETDATE())<=60 ) then CXC.Saldo/CXC.factor else 0 end),0) as 'SAP Vencimiento -60',
    COALESCE(SUM(case when (DATEDIFF(DD, CXC.FechaE, GETDATE())>60  ) then CXC.Saldo/cxc.factor else 0 end),0) as 'SAP Vencimiento +60'
    from 
    SAACXC cxc 
    inner join SACLIE CL ON CXC.CodClie = CL.CodClie
    inner join SAVEND d on cxc.CodVend = d.CodVend
    and  cxc.NumeroD not in (select numerod from safact) where cxc.saldo!=0 and cxc.numerod not like 'NE%' and cxc.TipoCxc='10'
    group by cxc.CodVend, d.descrip");

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
                            <li class="breadcrumb-item active">Pendientes por Cobrar por Clase SAP</li>
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
                        <h3 class="card-title">Pendientes por Cobrar por Clase SAP</h3>
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
                                                    <a target="_blank" href="principal1.php?page=pendientexcobrarxclase_sap_ver&mod=1&rango=<?php echo $i; ?>&vend=<?php echo mssql_result($query,$j,mssql_field_name($query, 0)); ?>">
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
