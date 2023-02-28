<?php
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
$suma = 0;

$query = mssql_query("SELECT 
    sucu.CodSucu as Sucursal,
    sucu.Descrip as Empresa,
    COALESCE(SUM(case when (DATEDIFF(DD, SAACXC.FechaE, GETDATE())>=0 and DATEDIFF(DD, SAACXC.FechaE, GETDATE())<=7) then SAACXC.Saldo/saacxc.factor else 0 end),0) as 'Total 0 a 7 Dias',
    COALESCE(SUM(case when (DATEDIFF(DD, SAACXC.FechaE, GETDATE())>=8 and DATEDIFF(DD, SAACXC.FechaE, GETDATE())<=15) then SAACXC.Saldo/saacxc.factor else 0 end),0) as 'Total 8 a 15 Dias',
    COALESCE(SUM(case when (DATEDIFF(DD, SAACXC.FechaE, GETDATE())>=16 and DATEDIFF(DD, SAACXC.FechaE, GETDATE())<=40) then SAACXC.Saldo/saacxc.factor else 0 end),0) as 'Total 16 a 40 Dias',
    COALESCE(SUM(case when (DATEDIFF(DD, SAACXC.FechaE, GETDATE())>40) then SAACXC.Saldo/saacxc.factor else 0 end),0) as 'Total Mayor a 40 Dias',
    COALESCE(SUM(SAACXC.Saldo/saacxc.factor),0) as SubTotal
    from saacxc 
    inner join SASUCURSAL sucu on sucu.CodSucu=saacxc.CodSucu
    where saacxc.saldo>0 AND (saacxc.tipocxc='10' OR saacxc.tipocxc='20')
    group by sucu.Descrip, sucu.CodSucu
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
                            <li class="breadcrumb-item active">Pendientes por Cobrar</li>
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
                        <h3 class="card-title">Pendientes por Cobrar</h3>
                    </div>
                    <div class="card-body">
                        <table id="example2" class="table table-sm table-bordered table-hover">
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
                                for($j=0;$j<mssql_num_rows($query);$j++){
                                    $suma += mssql_result($query,$j,"SubTotal"); ?>
                                    <tr> <?php

                                    for($i=0;$i<mssql_num_fields($query);$i++){
                                        if(is_numeric(mssql_result($query,$j,mssql_field_name($query, $i))) and strstr(mssql_result($query,$j,mssql_field_name($query, $i)),'.')) {?>
                                            <td align="right">
                                                <a target="_blank" href="principal1.php?page=fact_pendientes_cobrar_ver&mod=1&rango=<?php echo $i; ?>&sucu=<?php echo mssql_result($query,$j,"Sucursal"); ?>">
                                                    <?php echo rdecimal(mssql_result($query,$j,mssql_field_name($query, $i))); ?>
                                                </a>
                                            </td>
                                            <?php
                                        }else{?>
                                            <td  align="center"><?php echo utf8_encode(mssql_result($query,$j,mssql_field_name($query, $i)));?></td><?php
                                        }
                                    } ?>
                                    </tr> <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <br>
                        <?php echo " TOTAL MONTO: ".number_format($suma, 2, ',', '.'); ?>

                        <br>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include "footer.php"; ?>
    <script src="Icons.js" type="text/javascript"></script>
