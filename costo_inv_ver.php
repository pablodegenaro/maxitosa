<?php
set_time_limit(0);
$marca = $_POST['marca'];
$numero = $_POST['depo'];
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {

    $edv   = "";
    $count  = count($numero);
    for ($i = 0; $i < $count; $i++) {
        $edv = $edv . "'" . $numero[$i] . "',";
    }

    $depo = "(" . substr($edv, 0, strlen($edv) - 1) . ")";
    if ($depo != "()") {
        $codubic = "and saexis.codubic in " . $depo;
    } else {
        $codubic = "";
    }
    $q_marca = "";
    if ($marca != "-") {
        $q_marca = "and marca like '$marca'";
    }

    $query = mssql_query("SELECT  saexis.codprod Codprod, Descrip, tara, Marca, CostAct Costo, precio1 Precio, sum(saexis.existen) Bultos,
        sum(saexis.exunidad) Paquetes  from saprod inner join saexis on
        saprod.codprod = saexis.codprod where (saexis.existen > 0 or saexis.exunidad > 0) and len(marca) > 0 $codubic $q_marca
        group by saexis.codprod, descrip, CostAct, precio1, Marca, tara
        ");

    $query_1          = mssql_query("SELECT factor from SACONF where CodSucu = 00000");
    $factor           = mssql_result($query_1, 0, 'factor');
    $costos           = 0;
    $costos_p         = 0;
    $precios          = 0;
    $bultos           = 0;
    $paquetes         = 0;
    $tot_cos_bultos   = 0;
    $tot_cos_paquetes = 0;
    ?>
    <div class="content-wrapper">
        <!-- BOX DE LA MIGA DE PAN -->
        <section class="content-header">
            <div class="container-fluid">
                <!--      <div class="row mb-2">
<div class="col-sm-6">
<h2 id="title_permisos">Ultima Activacion Clientes</h2>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
<li class="breadcrumb-item active">Ultima Activacion Clientes</li>
</ol>
</div>
</div> -->
</div>
</section>
<!-- BOX DEL CONTENIDO DE LA VISTA FORMULARIO Y TABLA -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card card-saint">
                <div class="card-header">
                    <script type="text/javascript">
                        function regresa(){
                            window.location.href = "principal1.php?page=costo_inv&mod=1";
                        }
                    </script>
                    <h3 class="card-title">Costo e Inventario</h3>&nbsp;&nbsp;&nbsp;
                    <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
                </div>
                <div class="card-body">
                    <p>Factor Utilizado para el calculo : <?php echo rdecimal2($factor); ?> </p>
                    <!-- <table id="example2" class="table table-bordered table-hover"> -->
                        <table id="example1" class="table table-sm table-bordered table-striped">
                            <thead style="background-color: #00137f;color: white;">
                                <tr>
                                    <th>Codpro</th>
                                    <th>Descri</th>
                                    <th>Marc</th>
                                    <th>Costo Bultos B</th>
                                    <th>Costo Unid B</th>
                                    <th>Preci</th>
                                    <th>Costo Bultos $</th>
                                    <th>Costo Unid </th>
                                    <th>Bulto</th>
                                    <th>Paquete</th>
                                    <th>Total Bs Costo Bulto</th>
                                    <th>Total Bs Costo Unidade</th>
                                    <th>Total $ Costo Bulto</th>
                                    <th>Total $ Costo Uni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($j = 0; $j < mssql_num_rows($query); $j++) {
                                    $codprod = mssql_result($query, $j, 'Codprod');
                                    $query_s = mssql_query("SELECT CantEmpaq Display from saprod where codprod = '" . $codprod . "'");
                                    if (mssql_result($query_s, 0, 'Display') == 0) {
                                        $cdisplay = 0;
                                    } else {
                                        $cdisplay = mssql_result($query, $j, 'Costo') / mssql_result($query_s, 0, 'Display');
                                    }

                                    $tdisplayd = $cdisplay / $factor;

                                    ?>
                                    <tr>
                                        <td><?php echo utf8_encode($codprod); ?></td>
                                        <td><?php echo utf8_encode(mssql_result($query, $j, 'Descrip')); ?></td>
                                        <td><?php echo utf8_encode(mssql_result($query, $j, 'Marca')); ?></td>
                                        <td><?php echo rdecimal2(mssql_result($query, $j, 'Costo'), 2); ?></td>
                                        <td><?php echo rdecimal2($cdisplay, 2); ?></td>
                                        <td><?php echo rdecimal2(mssql_result($query, $j, 'Precio'), 2); ?></td>
                                        <td><?php echo rdecimal2(mssql_result($query, $j, 'Costo') / $factor, 2); ?></td>
                                        <td><?php echo rdecimal2($tdisplayd,2); ?></td>
                                        <td><?php echo rdecimal2(mssql_result($query, $j, 'Bultos'), 2); ?></td>
                                        <td><?php echo rdecimal2(mssql_result($query, $j, 'Paquetes'), 2); ?></td>
                                        <td><?php echo rdecimal2(mssql_result($query, $j, 'Costo') * mssql_result($query, $j, 'Bultos'), 2); ?></td>
                                        <td><?php echo rdecimal2($cdisplay * mssql_result($query, $j, 'Paquetes'), 2); ?></td>
                                        <td><?php echo rdecimal2((mssql_result($query, $j, 'Costo') * mssql_result($query, $j, 'Bultos')) / $factor, 2); ?></td>
                                        <td><?php echo rdecimal2(($cdisplay * mssql_result($query, $j, 'Paquetes')) / $factor, 2); ?></td>
                                        </tr> <?php

                                        $costos   = $costos + mssql_result($query, $j, "Costo");
                                        $costos_p = $costos_p + $cdisplay;

                                        $tdisplayd  = $cdisplay / $factor;
                                        $costosd    = $costos + mssql_result($query, $j, 'Costo') / $factor;
                                        $costos_p_d = $costos_p + $tdisplayd;
                                        $precios          = $precios + mssql_result($query, $j, "Precio");

                                        $costos_pd = $costos_pd + (mssql_result($query, $j, 'Costo') / $factor);
                                        $precios_d = $precios_d + $tdisplayd;

                                        $bultos           = $bultos + mssql_result($query, $j, "Bultos");
                                        $paquetes         = $paquetes + mssql_result($query, $j, "Paquetes");
                                        $tot_cos_bultos   = $tot_cos_bultos + (mssql_result($query, $j, 'Costo') * mssql_result($query, $j, 'Bultos'));
                                        $tot_cos_paquetes = $tot_cos_paquetes + ($cdisplay * mssql_result($query, $j, 'Paquetes'));

                                        $tot_cos_bultosd   = $tot_cos_bultosd + (mssql_result($query, $j, 'Costo') * mssql_result($query, $j, 'Bultos')) / $factor;
                                        $tot_cos_paquetesd = $tot_cos_paquetesd + ($cdisplay * mssql_result($query, $j, 'Paquetes')) / $factor;
                                    } ?>
                                </tbody>
                            </table>
                            <br>
                            <div align="center"><a href="costo_inv_excel.php?&marca=<?php echo $_POST['marca']; ?>&depo=<?php echo str_replace("'", "-", $depo); ?>" >
                                <img src="images/excel.jpeg" width="19" height="18" border="0" /> Exportar a Excel</a>&nbsp;&nbsp;
                            </div>
                            <br>
                        </div>
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