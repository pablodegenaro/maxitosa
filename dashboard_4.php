<?php
set_time_limit(0);
require_once ("permisos/Mssql.php");
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
$time = date("d-m-Y h:i:s a");
$actual = date("d/m/Y");

$pto_ordaz = '00000';
$maturin = '00001';
$carupano = '00002';

$sucursal = (isset($_GET['s'])) ? $_GET['s'] : $pto_ordaz;

require_once 'query_dashboard.php';
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <select title='Lista de Sucursales' class="form-control custom-select" name="sucursal" style="width: auto;" id="sucursal">
                        <?php
                        $sucur= mssql_query("SELECT CodSucu, Descrip from SASUCURSAL");
                        for($i=0;$i<mssql_num_rows($sucur);$i++){
                            ?>
                            <option value="<?= mssql_result($sucur,$i,"CodSucu"); ?>" <?php if($_GET['s']==mssql_result($sucur,$i,"CodSucu")) {echo 'selected';} ?>>
                                SUCURSAL <?= mssql_result($sucur,$i,"Descrip"); ?>
                            </option>
                            <?php
                        } ?>
                        <option value="-" <?php if($_GET['s']=='-') {echo 'selected';} ?>>TODAS LAS SUCURSALES</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info" title='Resumen de Documentos por Despachar'>
                        <div class="inner">
                            <p >Documentos por Despachar</p>
                            <h3 style="font-weight: 700">
                                <span id="docPorDespachar"><?php echo  rdecimal5($pordespachar); ?></span> </br>
                                <span class="text-info">00</span>
                            </h3>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-copy"></i>
                        </div>
                        <a href="principal1.php?page=despacho_crea&mod=1" class="small-box-footer"><i
                            class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning" title='Resumen de Cuentas Pendientes por Cobrar'>
                            <div class="inner">
                                <p >Cuentas por Cobrar</p>
                                <h5 style="font-weight: 700">
                                    <span id="cxc_in_bs"><?php echo  rdecimal5($cxcsaldo); ?> </span>Bs <br> <hr>
                                    <span id="cxc_in_$"><?php echo rdecimal5($cxc_divisa_saldo); ?> </span>$
                                </h5>
                            </div>
                            <div class="icon">
                                <i class="ion ion-cash"></i>
                            </div>
                            <a href="principal1.php?page=fact_pendientes_cobrar&mod=1" class="small-box-footer"><i
                                class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger" title='Resumen de Cuentas Pendientes por Pagar'>
                                <div class="inner">
                                    <p >Cuentas por Pagar</p>
                                    <h5 style="font-weight: 700">
                                        <span id="cxp_in_bs"><?php echo  rdecimal5($cxpsaldo); ?> </span>Bs <br> <hr>
                                        <span id="cxp_in_$"><?php  echo rdecimal5($cxp_divisa_saldo); ?> </span>$
                                    </h5>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-cash"></i>
                                </div>
                                <a href="principal1.php?page=analisis_vencimiento_proveedores&mod=1" class="small-box-footer"><i
                                    class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success" title='Resumen de Pedidos Pendientes por facturar'>
                                    <div class="inner">
                                        <p >Pedidos por facturar</p>
                                        <h3 style="font-weight: 700">
                                            <span id="pedsPorFacturar"><?php echo  rdecimal5($porfacturar); ?></span> </br>
                                            <span class="text-success">00</span>
                                        </h3>
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
                                    <div class="info-box" title='Transacciones del Dia'>
                                        <span class="info-box-icon bg-primary"><i class="fas fa-chart-line"></i></span>
                                        <div  class="info-box-content">
                                            <span class="info-box-text ">Trans. Dia</span>
                                            <span id="clientes" class="info-box-number "><?php echo  rdecimal5($opera_del_dia_ver); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box" title='Ventas Acumuladas del Mes'>
                                        <span class="info-box-icon bg-primary"><i class="fa fa-money-check-alt"></i></span>
                                        <div  class="info-box-content">
                                            <span class="info-box-text">Ventas del Mes <span id="ventas_mes_text"></span></span>
                                            <span id="ventas_mes_encurso" class="info-box-number"><?php echo  $ventas_mes_ver; ?> $</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box" title='Ventas del Dia'>
                                        <span class="info-box-icon bg-primary"><i class="fa fa-sort-amount-down-alt"></i></span>
                                        <div  class="info-box-content">
                                            <span class="info-box-text">Ventas del Dia</span>
                                            <span id="devoluciones_sin_motivo" class="info-box-number"><?php echo  $ventas_dia_ver; ?> $ </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box" title='Factor de Cambio del Sistema'>
                                        <span class="info-box-icon bg-primary"><i class="fa fa-hand-holding-usd"></i></span>
                                        <div  class="info-box-content inner">
                                            <span class="info-box-text">Tasa dolar</span>
                                            <span id="tasa_dolar" class="info-box-number"><?php echo rdecimal5($factor1); ?> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card" title='TOP 10 de Marcas'>
                                        <div class="card-header border-0">
                                            <h3 class="card-title" >TOP 10 - Ventas por Marca</h3>&nbsp;&nbsp; <a href="#" class="text-muted">  <i title='Listar Marcas'  class="fas fa-search"></i></a>
                                        </div>
                                        <div class="card-body table-responsive p-0" style="height: 360px;">
                                            <table id="ventas_por_marca" class="table table-striped table-valign-middle table-head-fixed text-nowrap text-center">
                                                <thead>
                                                    <tr>
                                                        <th>Marca</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php for ($i = 0; $i < mssql_num_rows($top10marcas); $i++) { ?>
                                                        <tr>
                                                            <td><?php echo utf8_encode(mssql_result($top10marcas,$i,"marca")); ?></td>
                                                            <td><?php echo rdecimal5(mssql_result($top10marcas,$i,"montod")); ?> $</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card" title="TOP 10 de Clientes">
                                        <div class="card-header border-0">
                                            <h3 class="card-title" >TOP 10 - Clientes</h3>  &nbsp;&nbsp; <a href="#" class="text-muted">  <i title='Listar Clientes'  class="fas fa-search"></i></a>
                                        </div>
                                        <div class="card-body table-responsive p-0" style="height: 360px;">
                                            <table id="top_clientes" class="table table-striped table-valign-middle table-head-fixed text-nowrap text-center">
                                                <thead>
                                                    <tr>
                                                        <th>Razon Social</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php for ($i = 0; $i < mssql_num_rows($top10clientes); $i++) { ?>
                                                        <tr>
                                                            <td><?php echo utf8_encode(mssql_result($top10clientes,$i,"Descrip")); ?></td>
                                                            <td><?php echo rdecimal5(mssql_result($top10clientes,$i,"montod")); ?> $</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div title='Saldo Disponible en Cuentas' class="card">
                                        <div  class="card-header border-0">
                                            <h3 class="card-title">Saldo en Cuentas</h3>
                                        </div>
                                        <div class="card-body table-responsive p-0" style="height: 360px;">
                                            <table id="saldo_bancos" class="table table-striped table-valign-middle table-head-fixed text-nowrap text-center">
                                                <thead>
                                                    <tr>
                                                        <th>Cuenta</th>
                                                        <th>Saldo Bs</th>
                                                        <th>Saldo Divisas</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php for ($i = 0; $i < mssql_num_rows($saldo_bancos); $i++) { ?>
                                                        <tr>
                                                            <td><?php echo
                                                            '<span class="right badge badge-info">'.mssql_result($saldo_bancos,$i,"Descrip").'</span><br>'
                                                            . utf8_encode(mssql_result($saldo_bancos,$i,"NroCta")); ?>
                                                        </td>
                                                        <td class="text-right">
                                                            <?php echo rdecimal5(mssql_result($saldo_bancos,$i,"Saldo")); ?> Bs
                                                        </td>
                                                        <td class="text-right">
                                                            <?php echo rdecimal5(mssql_result($saldo_bancos,$i,"Saldo") / $factor1); ?> $
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div title='Resumen del Inventario Valorizado' class="card-header border-0">
                                        <h3 class="card-title" >Inventario Valorizado</h3> &nbsp;&nbsp; <a href="#" class="text-muted">  <i title='Listar Marcas'  class="fas fa-search"></i></a>
                                    </div>
                                    <div class="card-body table-responsive p-0" style="height: 360px;">
                                        <table id="inventario_valorizado" class="table table-striped table-valign-middle table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Almacen</th>
                                                    <th>Valor Costo</th>
                                                    <th>Valor Venta</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for ($i = 0; $i < mssql_num_rows($inv_valor); $i++) { ?>
                                                    <tr>
                                                        <td align="left"><?php echo utf8_encode(mssql_result($inv_valor,$i,"Descrip")); ?> &nbsp;&nbsp;<?php echo mssql_result($inv_valor,$i,"CodUbic"); ?></td>
                                                        <td align="right"><?php echo rdecimal5(mssql_result($inv_valor,$i,"Total")); ?> $</td>
                                                        <td align="right"><?php echo rdecimal5(mssql_result($inv_valor,$i,"Total_venta")); ?> $</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div  title='Resumen de Ventas por Mes del Año en Curso' class="card">
                                    <canvas id="myChart" width="400" height="300"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div title='Resumen de Ventas por Dia de la Semana en Curso' class="card">
                                    <canvas id="myChart1" width="400" height="300"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div title='Resumen de Ventas por Vendedores de Facturas Realizadas' class="card">
                                    <div class="card-header border-0">
                                        <h3 class="card-title">Ventas por Vendedores Facturas </span></h3>
                                    </div>
                                    <div class="card-body table-responsive p-0" style="height: 360px;">
                                        <table id="ventas_por_marca" class="table table-striped table-valign-middle table-head-fixed text-nowrap ">
                                            <thead>
                                                <tr>
                                                    <th>Vendedor</th>
                                                    <th>Venta</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                for ($i = 0; $i < mssql_num_rows($ventasxasesorfac); $i++) { ?>
                                                    <tr>
                                                        <td align="left"><?php echo mssql_result($ventasxasesorfac,$i,"CodVend") ." " . " ".utf8_encode(mssql_result($ventasxasesorfac,$i,"descrip")); ?></td>
                                                        <td><?php echo rdecimal5(mssql_result($ventasxasesorfac,$i,"montod")); ?> $</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                               <div title='Resumen de Ventas por Vendedores de NE Realizadas' class="card">
                                <div class="card-header border-0">
                                    <h3 class="card-title">Ventas por Vendedores NE</h3>
                                </div>
                                <div class="card-body table-responsive p-0" style="height: 360px;">
                                    <table id="ventas_por_marca" class="table table-striped table-valign-middle table-head-fixed text-nowrap ">
                                        <thead>
                                            <tr>
                                                <th>Vendedor</th>
                                                <th>Venta</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            for ($i = 0; $i < mssql_num_rows($ventasxasesorne); $i++) { ?>
                                                <tr>
                                                    <td align="left"><?php echo mssql_result($ventasxasesorne,$i,"CodVend") ." " . " ".utf8_encode(mssql_result($ventasxasesorne,$i,"descrip")); ?></td>
                                                    <td><?php echo rdecimal5(mssql_result($ventasxasesorne,$i,"montod")); ?> $</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
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
                url: 'das_grafico_ventas_anio.php?&s=<?= $_GET['s']; ?>',
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
                                datasets: [{
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
                                }]
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
        <script>
            $.ajax({
                async: true,
                url: 'das_grafico_ventas_semana.php?&s=<?= $_GET['s']; ?>',
                method: "POST",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                },
                success: function (data) {
                    if (!jQuery.isEmptyObject(data)) {
                        let { nombres_dia, data_dia } = data;

                        const ctx1 = document.getElementById('myChart1').getContext('2d');
                        const myChart1 = new Chart(ctx1, {
                            type: 'line',
                            data: {
                                labels: nombres_dia,
                                datasets: [{
                                    label: 'Ventas de la Semana ',
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
                                    data: data_dia
                                }]
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
        <script type="text/javascript">
            $(document).ready(function () {
                $('#sucursal').change(() => {
                    window.location = "principal1.php?page=<?php echo $_GET['page']; ?>&mod=1&s="+$('#sucursal').val();
                });
            });
        </script>
