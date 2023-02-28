<?php
set_time_limit(0);
require_once ("permisos/Mssql.php");
// saldo pendiente por cobrar
$cxcbs = mssql_query("SELECT SUM(Saldo) as saldo FROM saacxc WHERE saldo > 0 AND tipocxc='10'");
$cxcsaldo = mssql_result($cxcbs,0,"saldo");

$factor = mssql_query("SELECT top 1 factorm from saconf");
$factor1 = mssql_result($factor,0,"factorm");

// saldo pendiente por pagar
$cxpbs = mssql_query("SELECT SUM(Saldo) as saldo FROM SAACXP WHERE saldo > 0 AND tipocxp='10'");
$cxpsaldo = mssql_result($cxpbs,0,"saldo");

// top marcas
// 
$top10marcas = mssql_query("SELECT marca,
 SUM(COALESCE((TotalItem/NULLIF(itemfact.factorp,0)) * (CASE WHEN itemfact.TipoFac = 'A' THEN 1 ELSE -1 END), 0)) as montod
 FROM SAFACT fact
 INNER JOIN SAITEMFAC itemfact ON itemfact.NumeroD = fact.NumeroD
 INNER JOIN SAPROD prod ON prod.CodProd = itemfact.CodItem
 WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, fact.FechaE)) BETWEEN '2022-01-01' AND '2022-03-22' AND itemfact.tipofac IN ('A')
 AND fact.NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac = 'A' AND x.NumeroR IS NOT NULL AND CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE Z.NumeroD = x.NumeroR AND Z.TipoFac = 'B') AS BIGINT))
 GROUP BY marca
 ORDER BY montod DESC");

// top clientes
// 
$top10clientes = mssql_query("SELECT clie.codclie, clie.Descrip,
    SUM(COALESCE((TotalItem/NULLIF(itemfact.factorp,0)) * (CASE WHEN itemfact.TipoFac = 'A' THEN 1 ELSE -1 END), 0)) as montod
    FROM SAFACT fact
    INNER JOIN SAITEMFAC itemfact ON itemfact.NumeroD = fact.NumeroD
    INNER JOIN SAPROD prod ON prod.CodProd = itemfact.CodItem
    INNER JOIN SACLIE clie ON clie.CodClie = fact.codclie
    WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, fact.FechaE)) BETWEEN '2022-01-01' AND '2022-03-22'  AND itemfact.tipofac IN ('A')
    AND fact.NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac = 'A' AND x.NumeroR IS NOT NULL AND CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE Z.NumeroD = x.NumeroR AND Z.TipoFac = 'B') AS BIGINT))
    GROUP BY clie.codclie, clie.Descrip
    ORDER BY montod DESC");

    ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <H1>Dashboard</H1>
            </div>
        </div>
        <div class="content">
            <div class="container">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>
                                        <span id="docPorDespachar">0</span>
                                    </h3>
                                    <p>Documentos por Despachar</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios-copy"></i>
                                </div>
                                <a href="#" class="small-box-footer"><i
                                    class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                       <p>Cuentas por Cobrar</p>
                                       <h5 style="font-weight: 700">
                                        <span id="cxc_in_bs"><?php echo  rdecimal2($cxcsaldo); ?> </span>Bs <br> <hr>
                                        <span id="cxc_in_$"><?php $total_cxc= $cxcsaldo * $factor1; echo rdecimal2($total_cxc); ?> </span>$
                                    </h5>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-cash"></i>
                                </div>
                                <a href="#" class="small-box-footer"><i
                                    class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                       <p>Cuentas por Pagar</p>
                                       <h5 style="font-weight: 700">
                                        <span id="cxp_in_bs"><?php echo  rdecimal2($cxpsaldo); ?> </span>Bs <br> <hr>
                                        <span id="cxp_in_$"><?php $total_cxp= $cxpsaldo * $factor1; echo rdecimal2($total_cxp); ?> </span>$
                                    </h5>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-cash"></i>
                                </div>
                                <a href="#" class="small-box-footer"><i
                                    class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>
                                            <span id="pedsPorFacturar">0</span>
                                        </h3>
                                        <p>Pedidos por facturar</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer"><i
                                        class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>                            
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box">

                                        <span class="info-box-icon bg-primary"><i class="fas fa-chart-line"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text ">Transacciones del Dia</span>
                                            <span id="clientes" class="info-box-number ">0</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box">

                                        <span class="info-box-icon bg-primary"><i class="fa fa-money-check-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Ventas del Mes <span id="ventas_mes_text"></span></span>
                                            <span id="ventas_mes_encurso" class="info-box-number">0,00 </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-primary"><i class="fa fa-sort-amount-down-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Ventas del Dia</span>
                                            <span id="devoluciones_sin_motivo" class="info-box-number">0</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box">

                                        <span class="info-box-icon bg-primary"><i class="fa fa-hand-holding-usd"></i></span>
                                        <div class="info-box-content inner">
                                            <span class="info-box-text">Tasa dolar</span>
                                            <span id="tasa_dolar" class="info-box-number">0,00 </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <h3 class="card-title">TOP 10 - Ventas por Marca </span></h3>
                                        </div>
                                        <div class="card-body table-responsive p-0" style="height: 360px;">
                                            <table id="ventas_por_marca" class="table table-striped table-valign-middle table-head-fixed text-nowrap text-center">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                 <?php for ($i = 0; $i < mssql_num_rows($top10marcas); $i++) { ?>
                                                    <tr>
                                                        <td><?php echo utf8_encode(mssql_result($top10marcas,$i,"marca")); ?></td>
                                                        <td><?php echo rdecimal2(mssql_result($top10marcas,$i,"montod")); ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header border-0">
                                        <h3 class="card-title">TOP 10 - Clientes </h3>
                                    </div>
                                    <div class="card-body table-responsive p-0" style="height: 360px;">
                                        <table id="top_clientes" class="table table-striped table-valign-middle table-head-fixed text-nowrap text-center">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                             <?php for ($i = 0; $i < mssql_num_rows($top10clientes); $i++) { ?>
                                                <tr>
                                                    <td><?php //echo mssql_result($top10clientes,$i,"codclie"); ?></td>
                                                    <td class="float-left"><?php echo utf8_encode(mssql_result($top10clientes,$i,"Descrip")); ?></td>
                                                    <td class="float-right"><?php echo rdecimal2(mssql_result($top10clientes,$i,"montod")); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">

                              <canvas id="myChart" width="400" height="300"></canvas>
                          </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">Inventario Valorizado</h3>
                            </div>
                            <div class="card-body table-responsive p-0" style="height: 360px;">
                                <table id="inventario_valorizado" class="table table-striped table-valign-middle table-head-fixed text-nowrap text-center">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--el contenido llega por ajax-->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>


<script>
    $.ajax({
        async: true,
        url: 'das_grafico.php',
        method: "POST",
        dataType: "json",
        error: function (e) {
            console.log(e.responseText);
        },
        success: function (data) {
            if (!jQuery.isEmptyObject(data)) {
                let { nombres_meses, data_meses } = data;

                const ctx = document.getElementById('myChart').getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: nombres_meses,
                        datasets: [
                        {
                            label: 'Ventas del Año ' + <?php echo date('Y') ?>,
                            backgroundColor: 'rgba(210, 214, 222, 1)',
                            // borderColor: '#07193C',
                            // pointRadius: false,
                            // pointColor: 'rgba(210, 214, 222, 1)',
                            // pointStrokeColor: '#c1c7d1',
                            // pointHighlightFill: '#fff',
                            // pointHighlightStroke: 'rgba(220,220,220,1)',
                            backgroundColor: 'transparent',
                            borderColor: '#007bff',
                            pointBorderColor: '#007bff',
                            pointBackgroundColor: '#007bff',
                            fill: false,
                            data: data_meses
                        }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

            }
        },
    });
</script>
